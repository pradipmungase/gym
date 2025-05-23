<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Configuration;
use Exception;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class MembersController extends Controller{
    
    public function index()
    {
        $gymId = Auth::id(); // More concise than Auth::user()->id

        $plans = Cache::remember("plans_gym_{$gymId}", 60 * 60, function () use ($gymId) {
            return DB::table('menbership_plans')
                    ->where('gym_id', $gymId)
                    ->orderByDesc('created_at')
                    ->get();
        });

        $trainers = Cache::remember("trainers_gym_{$gymId}", 60 * 60, function () use ($gymId) {
            return DB::table('trainers')
                    ->where('gym_id', $gymId)
                    ->orderByDesc('created_at')
                    ->get();
        });

        return view('admin.members.index', compact('plans', 'trainers'));
    }


    public function fetchmembers(Request $request)
    {
        $query = $request->input('query');
        $genders = $request->input('genders', []);
        $status = $request->input('status');

        $latestPayments = DB::table('member_payments as mp1')
            ->select('mp1.*')
            ->whereRaw('mp1.id = (SELECT mp2.id FROM member_payments mp2 WHERE mp2.member_id = mp1.member_id ORDER BY mp2.id DESC LIMIT 1)');

        $membersQuery = DB::table('members')
            ->join('member_memberships', 'members.id', '=', 'member_memberships.member_id')
            ->leftJoinSub($latestPayments, 'member_payments', function ($join) {
                $join->on('members.id', '=', 'member_payments.member_id');
            })
            ->join('menbership_plans', 'member_memberships.plan_id', '=', 'menbership_plans.id')
            ->where('members.gym_id', Auth::user()->id)
            ->where('member_memberships.status', 'active')
            ->whereNull('members.deleted_at');

        if ($query) {
            $membersQuery->where(function ($q) use ($query) {
                $q->where('members.name', 'like', "%$query%")
                ->orWhere('members.mobile', 'like', "%$query%")
                ->orWhere('members.email', 'like', "%$query%");
            });
        }

        if (!empty($genders) && !in_array('All', $genders)) {
            $membersQuery->whereIn('members.gender', $genders);
        }

        if ($status) {
            $membersQuery->where('members.status', $status);
        }

        $members = $membersQuery
            ->orderBy('members.created_at', 'desc')
            ->select(
                'members.*',
                'member_memberships.*',
                'members.id as member_id',
                'members.status as member_status',
                'member_payments.due_amount',
                'member_payments.original_plan_amount',
                'member_memberships.id as member_memberships_id',
                'menbership_plans.name as plan_name'
            )
            ->paginate(10);

        return view('admin.members.partials.members-table', compact('members'))->render();
    }


    public function store(Request $request)
    {
        // Clean up price fields
        $request->merge([
            'plan_price' => str_replace(',', '', $request->plan_price),
            'final_price' => str_replace(',', '', $request->final_price),
            'due_amount' => str_replace(',', '', $request->due_amount),
        ]);

        // Initial validation rules for common fields
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email:rfc,dns|unique:members,email',
            'mobile' => [
                'required',
                'regex:/^[6-9]\d{9}$/',
                'unique:members,mobile'
            ],
            'joining_date' => 'required|date|before_or_equal:today',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female',
            'plan' => 'required|exists:menbership_plans,id',
            'trainer' => 'nullable|exists:trainers,id',
            'batch' => 'required|string|max:50',
            'discount_type' => 'nullable|in:flat,percentage',
            'discount' => 'nullable|numeric|min:0',
            'plan_price' => 'required|numeric|min:1',
            'final_price' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > $request->plan_price) {
                        $fail('Final price cannot be greater than plan price.');
                    }
                }
            ],
            'menberImg' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'admission_fee' => 'nullable|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
        ];

        // Conditional validation for admission_fee
        if ($request->admission_fee > 0) {
            $validationRules['paymentMode'] = 'required|in:cash,phone pay,google pay,other';
        }

        if(isset($request->paymentMode) && $request->paymentMode != ''){
            $validationRules['admission_fee'] = 'required';
        }

        // Conditional validation for discount
        if ($request->discount > 0) {
            $validationRules['discount_type'] = 'required|in:flat,percentage';
        }

        if(isset($request->discount_type) && $request->discount_type != ''){
            $validationRules['discount'] = 'required|numeric|min:0|max:100';
        }

        if(isset($request->discount) && $request->discount != ''){
            $validationRules['discount_type'] = 'required';
        }
        // Apply the validation
        $request->validate($validationRules);

        DB::beginTransaction();

        try {
            // Insert member details
            $insertId = DB::table('members')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'image' => null,
                'qr_code_path' => 'Temp QR Code',
                'gym_id' => Auth::user()->id,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Handle image upload
            if ($request->hasFile('memberImg')) {
                $image = $request->file('memberImg');
                $path = uploadFile($image, 'memberProfilePicture', $insertId);
                DB::table('members')->where('id', $insertId)->update(['image' => $path]);
            }

            if($request->memberImgOld != ''){
                $image = $request->memberImgOld;
                DB::table('members')->where('id', $insertId)->update(['image' => $image]);
            }

            // Get plan details and calculate expiry date
            $plan = DB::table('menbership_plans')->where('id', $request->plan)->first();
            $expiry_date = $this->calculateExpiryDate($request->joining_date, $plan->duration, $plan->duration_type);

            // Validate expiry date
            if ($expiry_date <= date('Y-m-d')) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'expiry_date' => 'expiry_date', 'message' => 'Invalid joining date as per plan duration.']);
            }
            // Calculate discount price
            $discount_price = $this->calculateDiscountPrice($request);

            // Insert membership details
            DB::table('member_memberships')->insert([
                'member_id' => $insertId,
                'gym_id' => Auth::user()->id,
                'plan_id' => $request->plan,
                'trainer_id' => $request->trainer,
                'start_date' => $request->joining_date,
                'end_date' => $expiry_date,
                'batch' => $request->batch,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount ?? null,
                'plan_price' => $request->plan_price,
                'discount_price' => $discount_price,
                'final_price' => $request->final_price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Handle admission fee payment if provided
            // if ($request->admission_fee > 0) {
                DB::table('member_payments')->insert([
                    'member_id' => $insertId,
                    'gym_id' => Auth::user()->id,
                    'membership_id' => $request->plan,
                    'payment_mode' => $request->paymentMode ?? 'system',
                    'amount_paid' => $request->admission_fee ?? 0,
                    'due_amount' => $request->due_amount,
                    'after_discount_amount' => $request->final_price,
                    'original_plan_amount' => $request->plan_price,
                    'payment_type' => 'admission',
                    'payment_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            // }

            // Generate QR code and update the path
            $qrCodePath = $this->generateQRCode($insertId);
            DB::table('members')->where('id', $insertId)->update(['qr_code_path' => $qrCodePath, 'updated_at' => now()]);


            if($request->MembersRequestId){
                DB::table('member_registration')->where('id', $request->MembersRequestId)->update(['status' => 'approved', 'updated_at' => now()]);
            }

            // Send WhatsApp message
            sendWhatsAppMessageForMemberRegistration($request->mobile, $request->name, $qrCodePath,$insertId);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Member added successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to add member. Please try again.', 'error' => $e->getMessage()]);
        }
    }

    private function calculateExpiryDate($joining_date, $duration, $duration_type)
    {
        return date('Y-m-d', strtotime($joining_date . " +$duration $duration_type"));
    }


    private function calculateDiscountPrice($request)
    {
        if ($request->admission_fee > 0) {
            if ($request->discount_type == 'flat') {
                return $request->discount;
            } else {
                return $request->discount * $request->plan_price / 100;
            }
        }
        return null;
    }

    public function update(Request $request)
    {
        $id = $request->editMembersId;

        // Validation Rules
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email:rfc',
                Rule::unique('members', 'email')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
            'mobile' => [
                'required',
                'regex:/^[6-9]\d{9}$/',
                Rule::unique('members', 'mobile')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
            'joining_date' => 'required|date|before_or_equal:today',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female',
            'trainer' => 'nullable|exists:trainers,id',
            'batch' => 'required|string|max:50',
            'menberImg' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Name is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'mobile.required' => 'Mobile number is required.',
            'mobile.regex' => 'Enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
            'mobile.unique' => 'This mobile number is already in use.',
            'joining_date.required' => 'Joining date is required.',
            'joining_date.before_or_equal' => 'Joining date cannot be in the future.',
            'birth_date.required' => 'Birth date is required.',
            'birth_date.before' => 'Birth date must be in the past.',
            'gender.required' => 'Please select a gender.',
            'gender.in' => 'Invalid gender selection.',
            'trainer.exists' => 'Selected trainer does not exist.',
            'batch.required' => 'Batch name is required.',
            'batch.max' => 'Batch name must not exceed 50 characters.',
            'menberImg.image' => 'The uploaded file must be an image.',
            'menberImg.mimes' => 'Allowed image types are jpeg, png, jpg, gif, svg.',
            'menberImg.max' => 'Image size should not exceed 2MB.',
        ]);

        DB::beginTransaction();

        try {
            // Handle image upload if exists
            if ($request->hasFile('memberImg')) {
                $image = $request->file('memberImg');
                $filename = uploadFile($image, 'memberProfilePicture', $id);
                DB::table('members')->where('id', $id)->update([
                    'image' => $filename,
                ]);
            }

            // Update member details
            DB::table('members')->where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'updated_at' => now(),
            ]);

            // Fetch the active membership plan for the member
            $member_memberships = DB::table('member_memberships')
                ->where('member_id', $id)
                ->where('gym_id', Auth::user()->id)
                ->where('status', 'active')
                ->first();

            if (!$member_memberships) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Active membership not found for the member.']);
            }

            $plan = DB::table('menbership_plans')->where('id', $member_memberships->plan_id)->first();

            // Calculate expiry date based on plan duration
            $duration_string = '+' . $plan->duration . ' ' . $plan->duration_type;
            $expiry_date = date('Y-m-d', strtotime($request->joining_date . ' ' . $duration_string));

            // Ensure the expiry date is valid
            if ($expiry_date <= date('Y-m-d')) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'expiry_date' => 'expiry_date', 'message' => 'Invalid joining date as per plan duration.']);
            }

            // Update membership details
            DB::table('member_memberships')->where('id', $member_memberships->id)->update([
                'trainer_id' => $request->trainer ?? null,
                'start_date' => $request->joining_date,
                'end_date' => $expiry_date,
                'batch' => $request->batch,
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Member updated successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to update member. Please try again.', 'error' => $e->getMessage()]);
        }
    }

    private function generateQRCode($memberId)
    {
        $fileName = $memberId . '_' . time() . '.png';
        $userFolderPath = public_path('uploads/QRCodesImages/' . $memberId);

        if (!File::exists($userFolderPath)) {
            File::makeDirectory($userFolderPath, 0755, true);
        }

        $filePath = $userFolderPath . '/' . $fileName;

        $qrText = $memberId;
        $result = Builder::create()
            ->data($qrText)
            ->size(300)
            ->margin(10)
            ->build();

        file_put_contents($filePath, $result->getString());

        return 'uploads/QRCodesImages/' . $memberId . '/' . $fileName;
    }

    public function view($id)
    {
        $id = decrypt($id);

        $member = DB::table('members')
            ->join('member_memberships', 'members.id', '=', 'member_memberships.member_id')
            ->leftJoin('trainers', 'member_memberships.trainer_id', '=', 'trainers.id')
            ->join('menbership_plans', 'member_memberships.plan_id', '=', 'menbership_plans.id')
            ->where('members.gym_id', Auth::user()->id)
            ->where('members.id', $id)
            ->orderBy('members.created_at', 'desc')
            ->select('members.*', 'member_memberships.*','members.id as member_id', 'trainers.name as trainer_name', 'menbership_plans.name as plan_name')
            ->first();

        $oldMembershipPlans = DB::table('member_memberships')
                    ->where('member_id', $id)
                    ->where('status', 'renew')
                    ->join('menbership_plans', 'member_memberships.plan_id', '=', 'menbership_plans.id')
                    ->select('menbership_plans.*', 'member_memberships.*')
                    ->get();

        $memberPayments = DB::table('member_payments')
            ->join('menbership_plans', 'member_payments.membership_id', '=', 'menbership_plans.id')
            ->where('member_id', $id)
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('admin.members.view', compact('member', 'memberPayments', 'oldMembershipPlans'));
    }

    public function delete($id)
    {
        DB::table('members')->where('id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['status' => 'success', 'message' => 'Member deleted successfully!']);
    }

    public function addPayment(Request $request)
    {
        $request->validate([
            'addPaymentMemberId' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'due_amount' => 'required|numeric|min:0',
            'payment_mode' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $total_amount = $request->amount + $request->due_amount;

            DB::table('member_payments')->insert([
                'member_id' => $request->addPaymentMemberId, 
                'gym_id' => Auth::user()->id,
                'membership_id' => $request->currentPlanId,
                'payment_mode' => $request->payment_mode ?? 'cash',
                'amount_paid' => $request->amount,
                'due_amount' => $request->due_amount,
                'after_discount_amount' => $total_amount,
                'original_plan_amount' => $request->plan_price,
                'payment_type' => 'Due Payment',
                'payment_date' => $request->payment_date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            sendWhatsAppMessageForMemberPayment($request->mobile, $request->member_name, $request->amount, ucfirst($request->payment_mode), $request->due_amount, $request->payment_date);
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Payment added successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to add payment. Please try again.', 'error' => $e->getMessage()]);
        }
    }   

    public function updateStatus(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'status' => 'required|in:active,inactive',
        ]);

        DB::table('members')->where('id', $request->member_id)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);
        return response()->json(['status' => 'success', 'message' => 'Member status updated successfully!']);
    }

    public function addNote(Request $request)
    {
        $request->validate([
            'addNoteMemberId' => 'required|exists:members,id',
            'note' => 'required|string|max:255',
        ]);

        DB::table('members')->where('id', $request->addNoteMemberId)->update([
            'note' => $request->note,
            'updated_at' => now(),  
        ]);
        return response()->json(['status' => 'success', 'message' => 'Note added successfully!']);
    }   

    public function changePlan(Request $request)
    {
        // Remove commas from numbers
        $request->merge([
            'current_plan_price' => str_replace(',', '', $request->current_plan_price),
            'current_due_amount' => str_replace(',', '', $request->current_due_amount),
            'current_paid_amount' => str_replace(',', '', $request->current_paid_amount),
            'new_plan_price' => str_replace(',', '', $request->new_plan_price),
            'new_due_amount' => str_replace(',', '', $request->new_due_amount),
            'new_plan_price_after_discount' => str_replace(',', '', $request->new_plan_price_after_discount),
        ]);

        // Conditional rules
        $conditionalRules = [];

        // Admission fee requires payment mode
        if ($request->filled('admission_fee') && $request->admission_fee > 0) {
            $conditionalRules['payment_mode'] = 'required|in:cash,phone pay,google pay,other';
        }

        // Payment mode requires admission fee
        if ($request->filled('payment_mode')) {
            $conditionalRules['admission_fee'] = 'required|numeric|min:0';
        }

        // Discount type requires discount value
        if ($request->filled('discount_type')) {
            $conditionalRules['discount'] = 'required|numeric|min:0';
        }

        // Discount value requires discount type
        if ($request->filled('discount')) {
            $conditionalRules['discount_type'] = 'required|string|max:255';
        }

        // Main validation rules
        $baseRules = [
            'changePlanMemberId' => 'required|exists:members,id',
            'plan' => 'required|exists:menbership_plans,id',
            'current_plan_price' => 'required|numeric|min:0',
            'current_due_amount' => 'required|numeric|min:0',
            'current_paid_amount' => 'required|numeric|min:0',
            'new_plan_price' => 'required|numeric|min:0',
            'new_plan_price_after_discount' => 'required|numeric|min:0',
            'batch' => 'required|string|max:255',
            'trainer' => 'nullable|exists:trainers,id',
            'discount_type' => 'nullable|string|max:255',
            'discount_value' => 'nullable|numeric|min:0',
            'admission_fee' => 'nullable|numeric|min:0',
            'payment_mode' => 'nullable|string|max:255',
            'joining_date' => 'required|date',
            'memberMembershipsId' => 'required|exists:member_memberships,id',
            'newDueAmountForValidation' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value < 0) {
                        $fail('Member has already paid more than the due amount.');
                    }
                }
            ],
        ];

        // Custom error messages
        $messages = [
            'newDueAmountForValidation.required' => 'Due amount cannot be 0.',
            'newDueAmountForValidation.negative' => 'Member has already paid more than the due amount.',
        ];

        // Merge base rules with conditional rules
        $rules = array_merge($baseRules, $conditionalRules);

        // Perform validation
        Validator::make($request->all(), $rules, $messages)->validate();


        // Begin transaction
        DB::beginTransaction();

        try {
            // Retrieve member membership details
            $member_memberships = DB::table('member_memberships')
                ->where('id', $request->memberMembershipsId)
                ->where('gym_id', Auth::user()->id)
                ->where('status', 'active')
                ->first();

            if (!$member_memberships) {
                return response()->json(['status' => 'error', 'message' => 'Member membership not found.']);
            }

            // Retrieve the selected plan details
            $plan = DB::table('menbership_plans')->where('id', $request->plan)->first();
            $duration_string = '+' . $plan->duration . ' ' . $plan->duration_type;
            $expiry_date = date('Y-m-d', strtotime($request->joining_date . ' ' . $duration_string));

            if($expiry_date <= date('Y-m-d')){
                return response()->json(['status' => 'error', 'expiry_date' => 'expiry_date', 'message' => 'Invalid joining date as per plan duration.']);
            }
            // Update the member membership
            DB::table('member_memberships')->where('id', $request->memberMembershipsId)->update([
                'member_id' => $request->changePlanMemberId,
                'gym_id' => Auth::user()->id,
                'plan_id' => $request->plan,
                'trainer_id' => $member_memberships->trainer_id ?? null,
                'start_date' => $request->joining_date,
                'end_date' => $expiry_date,
                'batch' => $request->batch,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value ?? null,
                'plan_price' => $request->new_plan_price,
                'discount_price' => $request->new_plan_price - $request->new_plan_price_after_discount,
                'final_price' => $request->new_plan_price_after_discount,
                'updated_at' => now(),
            ]);

            
            // Record the payment for the plan change
            DB::table('member_payments')->insert([
                'member_id' => $request->changePlanMemberId,
                'gym_id' => Auth::user()->id,
                'membership_id' => $request->plan,
                'payment_mode' => $request->payment_mode ?? 'system',
                'amount_paid' => $request->current_paid_amount + $request->admission_fee,
                'due_amount' => $request->new_due_amount,
                'after_discount_amount' => $request->new_plan_price_after_discount,
                'original_plan_amount' => $request->new_plan_price,
                'payment_date' => now(),
                'payment_type' => 'Plan Change',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            sendWhatsAppMessageForMemberPlanChange($request->changePlanMemberId,$member_memberships->plan_id,$request->plan,$request->new_plan_price,$request->new_plan_price_after_discount,$request->joining_date,$expiry_date,$request->new_due_amount);

            // Commit the transaction
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Plan changed successfully!']);
        } catch (\Throwable $th) {
            // Rollback the transaction on error
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to change plan. Please try again.', 'error' => $th->getMessage()]);
        }
    }

    public function renewMembership(Request $request)
    {
        // Input Validation
        $validationRules = [
            'renewMembershipMemberId' => 'required|exists:members,id',
            'plan' => 'required|exists:menbership_plans,id',
            'renewMembershipNewDueAmountForValidation' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value < 0) {
                        $fail('Due Amount cannot be negative.');
                    }
                }
            ],
        ];


        // Conditional validation for admission_fee
        if ($request->admission_fee > 0) {
            $validationRules['payment_mode'] = 'required|in:cash,phone pay,google pay,other';
        }

        if(isset($request->payment_mode) && $request->payment_mode != ''){
            $validationRules['admission_fee'] = 'required';
        }

        // Conditional validation for discount
        if ($request->discount > 0) {
            $validationRules['discount_type'] = 'required|in:flat,percentage';
        }

        if(isset($request->discount_type) && $request->discount_type != ''){
            $validationRules['discount'] = 'required|numeric|min:0|max:100';
        }

        if(isset($request->discount) && $request->discount != ''){
            $validationRules['discount_type'] = 'required';
        }
        // Apply the validation
        $request->validate($validationRules);



        // Begin Database Transaction
        DB::beginTransaction();

        try {
            // Fetch the current active membership of the member
            $member_memberships = DB::table('member_memberships')
                ->where('member_id', $request->renewMembershipMemberId)
                ->where('gym_id', Auth::user()->id)
                ->where('status', 'active')
                ->first();

            if (!$member_memberships) {
                return response()->json(['status' => 'error', 'message' => 'Member membership not found.']);
            }

            // Parse the dates and validate the format before proceeding
            $startDate = $this->parseDate($request->current_plan_expiry_date);
            $endDate = $this->parseDate($request->new_plan_expiry_date);

            // Expire the current membership
            DB::table('member_memberships')->where('member_id', $request->renewMembershipMemberId)->update([
                'status' => 'renew',
                'updated_at' => now(),
            ]);

            // Insert the new membership details
            $newMembershipData = [
                'member_id' => intval($request->renewMembershipMemberId),
                'gym_id' => Auth::user()->id,
                'plan_id' => intval($request->plan),
                'trainer_id' => $member_memberships->trainer_id ?? null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'batch' => $member_memberships->batch,
                'discount_type' => $request->discount_type ?? null,
                'discount_value' => $request->discount_value ?? null,
                'plan_price' => $this->sanitizeAmount($request->new_plan_price),
                'discount_price' => $this->calculateDiscountedPrice($request->new_plan_price, $request->new_plan_price_after_discount),
                'final_price' => $this->sanitizeAmount($request->new_plan_price_after_discount),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('member_memberships')->insert($newMembershipData);

            // Insert payment details
            $paymentData = [
                'member_id' => intval($request->renewMembershipMemberId),
                'gym_id' => Auth::user()->id,
                'membership_id' => intval($request->plan),
                'payment_mode' => $request->payment_mode ?? 'system',
                'amount_paid' => $this->sanitizeAmount($request->current_paid_amount) + $this->sanitizeAmount($request->admission_fee),
                'due_amount' => $this->sanitizeAmount($request->new_due_amount),
                'after_discount_amount' => $this->sanitizeAmount($request->new_plan_price_after_discount),
                'original_plan_amount' => $this->sanitizeAmount($request->new_plan_price),
                'payment_date' => now(),
                'payment_type' => 'renewal',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('member_payments')->insert($paymentData);

            sendWhatsAppMessageForMemberRenewal($request->renewMembershipMemberId,$request->new_plan_price,$request->new_plan_price_after_discount,$request->new_due_amount,$endDate);
            // Commit the transaction
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Membership renewed successfully!']);

        } catch (\Throwable $th) {
            // Rollback the transaction in case of an error
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to renew membership. Please try again.', 'error' => $th->getMessage()]);
        }
    }


    protected function parseDate($dateString)
    {
        try {
            return DateTime::createFromFormat('d M Y', $dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // return null if parsing fails
        }
    }

    protected function sanitizeAmount($amount)
    {
        return (float) str_replace(',', '', $amount);
    }


    protected function calculateDiscountedPrice($planPrice, $discountedPrice)
    {
        return (float) str_replace(',', '', $planPrice) - (float) str_replace(',', '', $discountedPrice);
    }

}
