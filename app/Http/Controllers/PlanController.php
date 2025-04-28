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
use Illuminate\Support\Facades\Cache;

class PlanController extends Controller{
    
    public function index()
    {
        return view('admin.plans.index'); // just loads view with empty or initial content
    }

    public function fetchPlans(Request $request)
    {
        $plans = DB::table('menbership_plans')->where('gym_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.plans.partials.plan-table', compact('plans'))->render(); // returns only table partial
    }

    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'plan_name'     => 'required|string|max:255',
            'duration'      => 'required|numeric',
            'duration_type' => 'required|string',
            'price'         => 'required|numeric|min:1',
        ]);

        try {
            DB::beginTransaction(); // Start transaction

            $gymId = Auth::id();

            DB::table('menbership_plans')->insert([
                'name'          => $request->input('plan_name'),
                'duration'      => $request->input('duration'),
                'duration_type' => $request->input('duration_type'),
                'price'         => $request->input('price'),
                'gym_id'        => $gymId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // Clear cached plans for the gym
            Cache::forget("plans_gym_{$gymId}");

            DB::commit(); // Commit transaction

            return response()->json(['message' => 'Plan added successfully'], 201);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error

            // Optional: Log the error
            \Log::error('Error adding plan: '.$e->getMessage());

            return response()->json([
                'message' => 'Something went wrong while adding the plan.',
                'error'   => $e->getMessage() // Remove this line in production
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'plan_name'     => 'required|string|max:255',
            'duration'      => 'required|numeric',
            'duration_type' => 'required|string',
            'price'         => 'required|numeric|min:1',
            'plan_id'       => 'required|exists:menbership_plans,id',
        ]);

        try {
            DB::beginTransaction();

            $planId = $request->input('plan_id');
            $gymId = Auth::id();

            DB::table('menbership_plans')->where('id', $planId)->update([
                'name'          => $request->input('plan_name'),
                'duration'      => $request->input('duration'),
                'duration_type' => $request->input('duration_type'),
                'price'         => $request->input('price'),
                'updated_at'    => now(),
            ]);

            Cache::forget("plans_gym_{$gymId}");

            DB::commit();

            return response()->json(['message' => 'Plan updated successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Optional: log the error
            \Log::error('Error updating plan: '.$e->getMessage());

            return response()->json([
                'message' => 'Something went wrong while updating the plan.',
                'error'   => $e->getMessage() // You can hide this in production
            ], 500);
        }
    }


    public function view($id)
    {
        $id = decrypt($id);
        $plan = DB::table('menbership_plans')->where('id', $id)->first();
        $members = DB::table('members')
            ->join('member_memberships', 'members.id', '=', 'member_memberships.member_id')
            ->where('member_memberships.plan_id', $id)
            ->where('member_memberships.status', 'active')
            ->get();
        return view('admin.plans.view', compact('plan', 'members'));
    }
}
