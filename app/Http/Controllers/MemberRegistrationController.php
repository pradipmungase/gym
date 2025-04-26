<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MemberRegistrationController extends Controller{

    public function index(Request $request){
        $gymId = $request->segment(2);
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
        return view('memberRegistration', compact('trainers', 'plans'));
    }

    public function store(Request $request, $gymId)
    {
        // Step 1: Initialize validation rules
        $validationRules = [
            'registration_name'         => 'required|string|max:255',
            'registration_email'        => 'required|email|max:255',
            'registration_mobile'       => 'required|digits:10',
            'registration_birth_date'   => 'required|date',
            'registration_gender'       => 'required|in:male,female',
            'registration_joining_date' => 'required|date',
            'registration_batch'        => 'required|string|max:50',
            'registration_trainer'      => 'nullable|integer|exists:trainers,id',
            'registration_plan'         => 'required|integer|exists:menbership_plans,id',
            'registration_plan_price'   => 'required|string',
            'registration_final_price'  => 'required|string',
            'registration_due_amount'   => 'required|string|min:1',
            'registration_discount'     => 'nullable|numeric',
            'registration_discount_type'=> 'nullable|string',
        ];

        // Step 2: Add dynamic validation rules
        if ($request->registration_admission_fee > 0) {
            $validationRules['registration_payment_mode'] = 'required|in:cash,phone pay,google pay,other';
        }

        if(isset($request->registration_payment_mode) && $request->registration_payment_mode != '') {
            $validationRules['registration_admission_fee'] = 'required|numeric';
        }

        // Step 3: Validate the data
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Step 4: Begin Transaction
            DB::beginTransaction();

            // Step 5: Fetch plan details to calculate expiry date
            $plan = DB::table('menbership_plans')->where('id', $request->registration_plan)->first();
            $expiry_date = $this->calculateExpiryDate($request->registration_joining_date, $plan->duration, $plan->duration_type);

            // Step 6: Validate expiry date
            if ($expiry_date <= date('Y-m-d')) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'expiry_date' => 'expiry_date', 'message' => 'Invalid joining date as per plan duration.']);
            }
            // Step 7: Insert into the database
            $insertId = DB::table('member_registration')->insertGetId([
                'gym_id'           => $gymId,
                'name'             => $request->registration_name,
                'email'            => $request->registration_email,
                'mobile_number'    => $request->registration_mobile,
                'birth_date'       => $request->registration_birth_date,
                'gender'           => $request->registration_gender,
                'joining_date'     => date('Y-m-d', strtotime($request->registration_joining_date)),
                'end_date'         => $expiry_date,
                'batch'            => $request->registration_batch,
                'trainer_id'       => $request->registration_trainer,
                'plan_id'          => $request->registration_plan,
                'plan_price'       => str_replace(',', '', $request->registration_plan_price),
                'plan_price'      => str_replace(',', '', $request->registration_final_price),
                'due_amount'       => str_replace(',', '', $request->registration_due_amount),
                'discount'         => $request->registration_discount,
                'admission_fee'    => $request->registration_admission_fee,
                'final_price_after_discount' => str_replace(',', '', $request->memberRequestFinalPrice),
                'payment_mode'     => $request->registration_payment_mode,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            if ($request->hasFile('memberImg')) {
                $image = $request->file('memberImg');
                $path = uploadFile($image, 'memberProfilePicture', $insertId);
                DB::table('member_registration')->where('id', $insertId)->update(['image' => $path]);
            }

            // Step 8: Commit the transaction
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Member registered successfully.',
            ]);

        } catch (\Exception $e) {
            // Step 9: Rollback if something fails
            DB::rollBack();

            Log::error('Member Registration Error: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function calculateExpiryDate($joining_date, $duration, $duration_type)
    {
        return date('Y-m-d', strtotime($joining_date . " +$duration $duration_type"));
    }

    public function memberRequest()
    {
        $gymId = Auth::user()->id;
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

        return view('admin.memberRequest.index', compact('plans', 'trainers'));
    }

    public function fetch(Request $request)
    {
        $gymId = Auth::user()->id;
        $query = $request->input('query');
        $genders = $request->input('genders', []);
        $status = $request->input('status');

        $memberRequestsQuery = DB::table('member_registration')
            ->join('menbership_plans', 'member_registration.plan_id', '=', 'menbership_plans.id')
            ->leftJoin('trainers', 'member_registration.trainer_id', '=', 'trainers.id')
            ->where('member_registration.gym_id', $gymId);

        if ($query) {
            $memberRequestsQuery->where(function ($q) use ($query) {
                $q->where('member_registration.name', 'like', "%$query%")
                ->orWhere('member_registration.mobile', 'like', "%$query%")
                ->orWhere('member_registration.email', 'like', "%$query%");
            });
        }

        if (!empty($genders) && !in_array('All', $genders)) {
            $memberRequestsQuery->whereIn('member_registration.gender', $genders);
        }

        if ($status) {
            $memberRequestsQuery->where('member_registration.status', $status);
        }

        $memberRequests = $memberRequestsQuery
            ->orderBy('member_registration.created_at', 'desc')
            ->select(
                'member_registration.*',
                'member_registration.id as member_id',
                'member_registration.status as member_status',
                'menbership_plans.name as plan_name',
                'menbership_plans.duration as duration',
                'menbership_plans.duration_type as duration_type',
                'trainers.name as trainer_name'
            )
            ->paginate(10);

        return view('admin.memberRequest.partials.memberRequest-table', compact('memberRequests'));
    }

    public function reject($id)
    {
        $member = DB::table('member_registration')->where('id', $id)->first();
        DB::table('member_registration')->where('id', $id)->update(['status' => 'rejected']);
        return response()->json(['status' => 'success', 'message' => 'Member request rejected successfully.']);
    }
}

