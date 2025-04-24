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


class MembersController extends Controller{
    
    public function index()
    {
        $plans = DB::table('menbership_plans')->where('gym_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $trainers = DB::table('trainers')->where('gym_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('admin.members.index', compact('plans', 'trainers')); // just loads view with empty or initial content
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
                'member_memberships.id as member_memberships_id'
            )
            ->paginate(10);

        return view('admin.members.partials.members-table', compact('members'))->render();
    }


    public function store(Request $request)
    {
        $request->merge([
            'plan_price' => str_replace(',', '', $request->plan_price),
            'final_price' => str_replace(',', '', $request->final_price),
            'due_amount' => str_replace(',', '', $request->due_amount),
        ]);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email:rfc,dns|unique:members,email',
            'mobile' => [
                'required',
                'regex:/^[6-9]\d{9}$/',
                'unique:members,mobile'
            ],
            'joining_date' => 'required|date|before_or_equal:today',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'required|in:Male,Female,Other',
            'plan' => 'required|exists:menbership_plans,id',
            'trainer' => 'nullable|exists:trainers,id',
            'batch' => 'required|string|max:50',
            'discount_type' => 'required|in:flat,percentage',
            'discount' => 'nullable|numeric|min:0',
            'plan_price' => 'required|numeric|min:1',
            'final_price' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $planPrice = $request->input('plan_price');
                    if ($value > $planPrice) {
                        $fail('Final price cannot be greater than plan price.');
                    }
                }
            ],
            'menberImg'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'admission_fee' => 'nullable|numeric|min:0',
            'due_amount' => [
                'required',
                'numeric',
                'min:0',
            ]
        ], [
            'name.required' => 'Please enter the member name.',
            'email.required' => 'Please provide an email address.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'mobile.required' => 'Mobile number is required.',
            'mobile.regex' => 'Enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
            'mobile.unique' => 'This mobile number is already registered.',
            'joining_date.required' => 'Please select a joining date.',
            'joining_date.before_or_equal' => 'Joining date cannot be in the future.',
            'birth_date.required' => 'Please select the birth date.',
            'birth_date.before' => 'Birth date must be before today.',
            'gender.required' => 'Please select a gender.',
            'gender.in' => 'Gender must be Male, Female, or Other.',
            'plan.required' => 'Please select a membership plan.',
            'plan.exists' => 'Selected plan does not exist.',
            'trainer.required' => 'Please assign a trainer.',
            'trainer.exists' => 'Selected trainer does not exist.',
            'batch.required' => 'Batch is required.',
            'batch.max' => 'Batch name cannot be more than 50 characters.',
            'discount_type.required' => 'Select a discount type.',
            'discount_type.in' => 'Discount type must be either Flat or Percentage.',
            'discount.numeric' => 'Discount must be a valid number.',
            'discount.min' => 'Discount cannot be negative.',
            'plan_price.required' => 'Enter the plan price.',
            'plan_price.numeric' => 'Plan price must be numeric.',
            'plan_price.min' => 'Plan price must be at least 1.',
            'final_price.required' => 'Final price is required.',
            'final_price.numeric' => 'Final price must be numeric.',
            'final_price.min' => 'Final price cannot be negative.',
            'menberImg.image' => 'The uploaded file must be an image.',
            'menberImg.mimes' => 'Image must be jpeg, png, jpg, gif, or svg format.',
            'menberImg.max' => 'Image must not exceed 2MB.',
            'admission_fee.numeric' => 'Admission fee must be numeric.',
            'admission_fee.min' => 'Admission fee cannot be negative.',
            'due_amount.required' => 'Due amount is required.',
            'due_amount.numeric' => 'Due amount must be numeric.',
            'due_amount.min' => 'Due amount cannot be negative.',
        ]);

        DB::beginTransaction();

        try {

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
            if ($request->hasFile('memberImg')) {
                $image = $request->file('memberImg');
                $path = uploadFile($image, 'memberProfilePicture', $insertId);
                DB::table('members')->where('id', $insertId)->update(['image' => $path]);
            }

            $plan = DB::table('menbership_plans')->where('id', $request->plan)->first();

            // Prepare duration string for strtotime (e.g., "+1 month", "+3 month", "+1 week")
            $duration_string = '+' . $plan->duration . ' ' . $plan->duration_type;

            // Subtract duration from joining date
            $expiry_date = date('Y-m-d', strtotime($request->joining_date . ' ' . $duration_string));

            if($expiry_date < date('Y-m-d')){
                DB::rollBack();
                return response()->json(['status' => 'error','expiry_date' => 'expiry_date', 'message' => 'Invalid joining date as per plan duration.']);
            }

            $discount_price = null;
            if($request->discount_type == 'flat'){
                $discount_price = $request->discount;
            }else{
                $discount_price = $request->discount * $request->plan_price / 100;
            }

            DB::table('member_memberships')->insert([
                'member_id' => $insertId,
                'gym_id' => Auth::user()->id,
                'plan_id' => $request->plan,
                'trainer_id' => $request->trainer,
                'start_date' => $request->joining_date,
                'end_date' => $expiry_date,
                'batch' => $request->batch,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount,
                'plan_price' => $request->plan_price,
                'discount_price' => $discount_price,
                'final_price' => $request->final_price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if($request->admission_fee > 0){
                DB::table('member_payments')->insert([
                    'member_id' => $insertId,
                    'gym_id' => Auth::user()->id,
                    'membership_id' => $request->plan,
                    'payment_mode' => $request->paymentMode,
                    'amount_paid' => $request->admission_fee ?? 0,
                    'due_amount' => $request->due_amount,
                    'total_amount' => $request->final_price,
                    'payment_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Generate QR code and update the path
            $qrCodePath = $this->generateQRCode($insertId);
            DB::table('members')->where('id', $insertId)->update([
                'qr_code_path' => $qrCodePath,
                'updated_at' => now(),
            ]);

            // Send WhatsApp message
            sendWhatsAppMessageForMemberRegistration($request->mobile, $request->name, $qrCodePath);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Member added successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to add member. Please try again.', 'error' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        $id = $request->editMembersId;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email:rfc,dns',
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

            if ($request->hasFile('memberImg')) {
                $image = $request->file('memberImg');
                $filename = uploadFile($image, 'memberProfilePicture', $id);

                DB::table('members')->where('id', $id)->update([
                    'image' => $filename,
                ]);
            }

            DB::table('members')->where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'updated_at' => now(),
            ]);


            $member_memberships = DB::table('member_memberships')->where('member_id', $id)->where('gym_id', Auth::user()->id)->where('status', 'active')->first();
            if(!$member_memberships){
                return response()->json(['status' => 'error', 'message' => 'member_memberships not found.']);
            }

            $plan = DB::table('menbership_plans')->where('id',$member_memberships->plan_id)->first();

            $duration_string = '+' . $plan->duration . ' ' . $plan->duration_type;
            $expiry_date = date('Y-m-d', strtotime($request->joining_date . ' ' . $duration_string));

            if($expiry_date < date('Y-m-d')){
                DB::rollBack();
                return response()->json(['status' => 'error','expiry_date' => 'expiry_date', 'message' => 'Invalid joining date as per plan duration.']);
            }

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
            return response()->json(['status' => 'error', 'message' => 'Failed to update member.', 'error' => $e->getMessage()]);
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

        return $filePath;
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

        $memberPayments = DB::table('member_payments')->where('member_id', $id)->orderBy('payment_date', 'desc')->get();

        return view('admin.members.view', compact('member', 'memberPayments'));
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
            'payment_mode' => $request->payment_mode,
            'amount_paid' => $request->amount,
            'due_amount' => $request->due_amount,
            'total_amount' => $total_amount,
            'payment_date' => $request->payment_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

            sendWhatsAppMessageForMemberPayment($request->mobile, $request->member_name, $total_amount, $request->payment_mode, $request->due_amount, $request->payment_date);
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
        
        $request->merge([
            'current_plan_price' => str_replace(',', '', $request->current_plan_price),
            'current_due_amount' => str_replace(',', '', $request->current_due_amount),
            'current_paid_amount' => str_replace(',', '', $request->current_paid_amount),
            'new_plan_price' => str_replace(',', '', $request->new_plan_price),
            'new_due_amount' => str_replace(',', '', $request->new_due_amount),
            'new_plan_price_after_discount' => str_replace(',', '', $request->new_plan_price_after_discount),
        ]);
        $request->validate([
            'changePlanMemberId' => 'required|exists:members,id',
            'plan' => 'required|exists:menbership_plans,id',
            'current_plan_price' => 'required|numeric|min:0',
            'current_due_amount' => 'required|numeric|min:0',
            'current_paid_amount' => 'required|numeric|min:0',
            'new_plan_price' => 'required|numeric|min:0',
            'new_plan_price_after_discount' => 'required|numeric|min:0',
            'batch' => 'required|string|max:255',
            'trainer' => 'nullable|exists:trainers,id',
            'discount_type' => 'required|string|max:255',
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
                        $fail('Member already paid more than due amount.');
                    }
                }
            ],
         ], [
            'newDueAmountForValidation.negative' => 'Member allready paid more than due amount.',
            'newDueAmountForValidation.regex' => 'Member allrey paid more than due amount',
        ]);


        DB::beginTransaction();
        try {

            $member_memberships = DB::table('member_memberships')->where('id', $request->memberMembershipsId)->first();
            if(!$member_memberships){
                return response()->json(['status' => 'error', 'message' => 'member_memberships not found.']);
            }


            $plan = DB::table('menbership_plans')->where('id', $request->plan)->first();
            $duration_string = '+' . $plan->duration . ' ' . $plan->duration_type;
            $expiry_date = date('Y-m-d', strtotime($request->start_date . ' ' . $duration_string));

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
                'created_at' => now(),
                'updated_at' => now(), 
            ]);
            

            DB::table('member_payments')->insert([
                'member_id' => $request->changePlanMemberId,
                'gym_id' => Auth::user()->id,
                'membership_id' => $request->plan,
                'payment_mode' => $request->payment_mode ?? "other",
                'amount_paid' => $request->current_paid_amount+$request->admission_fee,
                'due_amount' => $request->new_due_amount,
                'total_amount' => $request->new_plan_price,
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Plan changed successfully!']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to change plan. Please try again.', 'error' => $th->getMessage()]);
        }
    }
}
