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
            ->join('member_details', 'members.id', '=', 'member_details.member_id')
            ->joinSub($latestPayments, 'member_payments', function ($join) {
                $join->on('members.id', '=', 'member_payments.member_id');
            })
            ->where('members.gym_id', Auth::user()->id)
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
                'member_details.*',
                'members.id as member_id',
                'member_payments.*',
                'member_payments.due_amount as due_amount_payment'
            )
            ->paginate(10);

        return view('admin.members.partials.members-table', compact('members'))->render();
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:members,email',
            'mobile' => [
                'required',
                'regex:/^[6-9]\d{9}$/',
                'unique:members,mobile'
            ],
            'joining_date' => 'required|date|before_or_equal:today',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female,Other',
            'plan' => 'required|exists:menbership_plans,id',
            'trainer' => 'required|exists:trainers,id',
            'batch' => 'required|string|max:50',
            'discount_type' => 'required|in:Flat,Percentage',
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
                'joining_date' => $request->joining_date,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'image' => null,
                'gym_id' => Auth::user()->id,
                'status' => 'Active',
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

            DB::table('member_details')->insert([
                'member_id' => $insertId,
                'gym_id' => Auth::user()->id,
                'plan_id' => $request->plan,
                'trainer_id' => $request->trainer,
                'joining_date' => $request->joining_date,
                'expiry_date' => $expiry_date,
                'batch' => $request->batch,
                'admission_fee' => $request->admission_fee ?? 0,
                'discount_type' => $request->discount_type,
                'discount_inpute' => $request->discount,
                'after_discount_price' => $request->final_price,
                'plan_price' => $request->plan_price,
                'due_amount' => $request->due_amount,
                'payment_mode' => $request->paymentMode,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // if($request->admission_fee > 0){
                DB::table('member_payments')->insert([
                    'member_id' => $insertId,
                    'gym_id' => Auth::user()->id,
                    'plan_id' => $request->plan,
                    'payment_mode' => $request->paymentMode,
                    'amount' => $request->admission_fee ?? 0,
                    'due_amount' => $request->due_amount,
                    'total_amount' => $request->final_price,
                    'payment_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            // }

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
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female,Other',
            'plan' => 'required|exists:menbership_plans,id',
            'trainer' => 'required|exists:trainers,id',
            'batch' => 'required|string|max:50',
            'discount_type' => 'required|in:Flat,Percentage',
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
            'menberImg' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'admission_fee' => 'nullable|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
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
            'plan.required' => 'Please select a membership plan.',
            'plan.exists' => 'Selected plan does not exist.',
            'trainer.required' => 'Please select a trainer.',
            'trainer.exists' => 'Selected trainer does not exist.',
            'batch.required' => 'Batch name is required.',
            'batch.max' => 'Batch name must not exceed 50 characters.',
            'discount_type.required' => 'Please select a discount type.',
            'discount_type.in' => 'Invalid discount type.',
            'discount.numeric' => 'Discount must be a number.',
            'discount.min' => 'Discount cannot be negative.',
            'plan_price.required' => 'Plan price is required.',
            'plan_price.numeric' => 'Plan price must be a number.',
            'plan_price.min' => 'Plan price must be at least 1.',
            'final_price.required' => 'Final price is required.',
            'final_price.numeric' => 'Final price must be a number.',
            'final_price.min' => 'Final price cannot be negative.',
            'menberImg.image' => 'The uploaded file must be an image.',
            'menberImg.mimes' => 'Allowed image types are jpeg, png, jpg, gif, svg.',
            'menberImg.max' => 'Image size should not exceed 2MB.',
            'admission_fee.numeric' => 'Admission fee must be a number.',
            'admission_fee.min' => 'Admission fee cannot be negative.',
            'due_amount.required' => 'Due amount is required.',
            'due_amount.numeric' => 'Due amount must be a number.',
            'due_amount.min' => 'Due amount cannot be negative.',
        ]);


        DB::beginTransaction();

        try {
            $member = DB::table('members')->where('id', $id)->first();

            if (!$member) {
                return response()->json(['status' => 'error', 'message' => 'Member not found.']);
            }

            $filename = $member->image;

            if ($request->hasFile('memberImg')) {
                $image = $request->file('memberImg');
                $filename = uploadFile($image, 'memberProfilePicture', $id);
            }

            DB::table('members')->where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'joining_date' => $request->joining_date,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'image' => $filename,
                'status' => 'Active',
                'updated_at' => now(),
            ]);

            $plan = DB::table('menbership_plans')->where('id', $request->plan)->first();
            $duration_string = '+' . $plan->duration . ' ' . $plan->duration_type;
            $expiry_date = date('Y-m-d', strtotime($request->joining_date . ' ' . $duration_string));

            if($expiry_date < date('Y-m-d')){
                DB::rollBack();
                return response()->json(['status' => 'error','expiry_date' => 'expiry_date', 'message' => 'Invalid joining date as per plan duration.']);
            }

            DB::table('member_details')->updateOrInsert(
                ['member_id' => $id],
                [
                    'gym_id' => Auth::user()->id,
                    'plan_id' => $request->plan,
                    'trainer_id' => $request->trainer,
                    'joining_date' => $request->joining_date,
                    'expiry_date' => $expiry_date,
                    'batch' => $request->batch,
                    'admission_fee' => $request->admission_fee ?? 0,
                    'discount_type' => $request->discount_type,
                    'discount_inpute' => $request->discount,
                    'after_discount_price' => $request->final_price,
                    'plan_price' => $request->plan_price,
                    'due_amount' => $request->due_amount,
                    'payment_mode' => $request->paymentMode,
                    'updated_at' => now(),
                ]
            );

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
            ->join('member_details', 'members.id', '=', 'member_details.member_id')
            ->join('trainers', 'member_details.trainer_id', '=', 'trainers.id')
            ->join('menbership_plans', 'member_details.plan_id', '=', 'menbership_plans.id')
            ->where('members.gym_id', Auth::user()->id)
            ->where('members.id', $id)
            ->orderBy('members.created_at', 'desc')
            ->select('members.*', 'member_details.*','members.id as member_id', 'trainers.name as trainer_name', 'menbership_plans.name as plan_name')
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
            'plan_id' => $request->currentPlanId,
            'payment_mode' => $request->payment_mode,
            'amount' => $request->amount,
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
            'status' => 'required|in:Active,Inactive',
        ]);

        DB::table('members')->where('id', $request->member_id)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);
        return response()->json(['status' => 'success', 'message' => 'Member status updated successfully!']);
    }
}
