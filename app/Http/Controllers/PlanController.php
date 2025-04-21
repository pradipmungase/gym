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
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'duration'  => 'required|numeric',
            'duration_type' => 'required|string',
            'price'     => 'required|numeric|min:1',
        ]);


        DB::table('menbership_plans')->insert([
            'name' => $request->input('plan_name'),
            'duration'  => $request->input('duration'),
            'duration_type' => $request->input('duration_type'),
            'price'     => $request->input('price'),
            'gym_id'    => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Plan added successfully']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'duration'  => 'required|numeric',
            'duration_type' => 'required|string',
            'price'     => 'required|numeric|min:1',
            'plan_id'   => 'required|exists:menbership_plans,id',
        ]);

        DB::table('menbership_plans')->where('id', $request->input('plan_id'))->update([
            'name' => $request->input('plan_name'),
            'duration'  => $request->input('duration'),
            'duration_type' => $request->input('duration_type'),
            'price'     => $request->input('price'),    
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Plan updated successfully']);
    }

    public function view($id)
    {
        $id = decrypt($id);
        $plan = DB::table('menbership_plans')->where('id', $id)->first();
        return view('admin.plans.view', compact('plan'));
    }
}
