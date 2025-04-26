<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class MemberRegistrationController extends Controller{

    public function index(Request $request){
        $gymId = $request->segment(2);
        $plans = Cache::remember("plans_gym_{$gymId}", 60 * 60, function () use ($gymId) {
            return DB::table('menbership_plans')
                    // ->where('gym_id', $gymId)
                    ->orderByDesc('created_at')
                    ->get();
        });

        $trainers = Cache::remember("trainers_gym_{$gymId}", 60 * 60, function () use ($gymId) {
            return DB::table('trainers')
                    // ->where('gym_id', $gymId)
                    ->orderByDesc('created_at')
                    ->get();
        });
        return view('memberRegistration', compact('trainers', 'plans'));
    }

public function store(Request $request)
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
        'registration_trainer'      => 'required|integer|exists:trainers,id',
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
        DB::table('member_registration')->insert([
            'name'             => $request->registration_name,
            'email'            => $request->registration_email,
            'mobile_number'           => $request->registration_mobile,
            'birth_date'       => $request->registration_birth_date,
            'gender'           => $request->registration_gender,
            'joining_date'     => $request->registration_joining_date,
            'batch'            => $request->registration_batch,
            'trainer_id'       => $request->registration_trainer,
            'plan_id'          => $request->registration_plan,
            'plan_price'       => str_replace(',', '', $request->registration_plan_price),
            'plan_price'      => str_replace(',', '', $request->registration_final_price),
            'due_amount'       => str_replace(',', '', $request->registration_due_amount),
            'discount'         => $request->registration_discount,
            'admission_fee'    => $request->registration_admission_fee,
            'payment_mode'     => $request->registration_payment_mode,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

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
            'message' => 'Something went wrong. Please try again.',
        ], 500);
    }
}
    private function calculateExpiryDate($joining_date, $duration, $duration_type)
    {
        return date('Y-m-d', strtotime($joining_date . " +$duration $duration_type"));
    }

}
