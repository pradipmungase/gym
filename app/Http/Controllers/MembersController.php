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
        $members = DB::table('members')
            ->join('member_details', 'members.id', '=', 'member_details.member_id')
            ->where('members.gym_id', Auth::user()->id)
            ->orderBy('members.created_at', 'desc')
            ->select('members.*', 'member_details.*','members.id as member_id')
            ->paginate(10);

        return view('admin.members.partials.members-table', compact('members'))->render(); // returns only table partial
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
                'digits:10',
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
            'menberImg'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'admission_fee' => 'nullable|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
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
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/members'), $filename);
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
            $duration_string = '+' . $plan->duration;
            $expiry_date = date('Y-m-d', strtotime($request->joining_date . ' ' . $duration_string));

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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:members,email',
            'mobile' => 'required|digits:10|unique:members,mobile',
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
        ]);


        DB::beginTransaction();

        try {
            if ($request->hasFile('menberImg')) {
                $image = $request->file('menberImg');
                $filename = time().'_'.$image->getClientOriginalName();
                $image->move(public_path('uploads/members'), $filename);
            }
            $insertId = DB::table('members')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'joining_date' => $request->joining_date,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'image' => $filename ?? null,
                'gym_id' => Auth::user()->id,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            
            $plan = DB::table('menbership_plans')->where('id', $request->plan)->first();

            // Prepare duration string for strtotime (e.g., "+1 month", "+3 month", "+1 week")
            $duration_string = '+' . $plan->duration;

            // Subtract duration from joining date
            $expiry_date = date('Y-m-d', strtotime($request->joining_date . ' ' . $duration_string));


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

            // Generate QR code and update the path
            $qrCodePath = $this->generateQRCode($insertId);
            DB::table('members')->where('id', $insertId)->update([
                'qr_code_path' => $qrCodePath,
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

    private function generateQRCode($memberId)
    {
        $fileName = $memberId . '_' . time() . '.png';
        $userFolderPath = public_path('QRCodesImages/' . $memberId);

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
            ->where('members.gym_id', Auth::user()->id)
            ->where('members.id', $id)
            ->orderBy('members.created_at', 'desc')
            ->select('members.*', 'member_details.*','members.id as member_id')
            ->first();
        return view('admin.members.view', compact('member'));
    }

}
