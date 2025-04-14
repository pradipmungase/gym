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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class TrainerController extends Controller{
    
    public function index()
    {
        return view('admin.trainers.index'); // just loads view with empty or initial content
    }

    public function fetchTrainers(Request $request)
    {
        $trainers = DB::table('trainers')->where('gym_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.trainers.partials.trainer-table', compact('trainers'))->render();
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email'  => 'required|string|unique:trainers,email', // adjust type as per your DB schema
            'phone'     => 'required|numeric|min:1|unique:trainers,phone',
            'gender'     => 'required|string',
            'address'     => 'required|string',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'joining_date'     => 'required|date',
            'monthly_salary'     => 'required|numeric|min:1',
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('trainers'), $imageName);
            $imagePath = 'trainers/' . $imageName;
        }


        DB::table('trainers')->insert([
            'name' => $request->input('name'),
            'email'  => $request->input('email'),
            'phone'     => $request->input('phone'),
            'gender'     => $request->input('gender'),
            'address'     => $request->input('address'),
            'image'     => $imagePath ?? null,
            'joining_date'     => $request->input('joining_date'),
            'monthly_salary'     => $request->input('monthly_salary'),
            'gym_id'    => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Trainer added successfully']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email'  => 'required|string|unique:trainers,email,' . $request->input('trainer_id'), // adjust type as per your DB schema
            'phone'     => 'required|numeric|min:1|unique:trainers,phone,' . $request->input('trainer_id'),
            'gender'     => 'required|string',
            'address'     => 'required|string',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'joining_date'     => 'required|date',
            'monthly_salary'     => 'required|numeric|min:1',
            'trainer_id'   => 'required|exists:trainers,id',
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('trainers'), $imageName);
            $imagePath = 'trainers/' . $imageName;
        }

        DB::table('trainers')->where('id', $request->input('trainer_id'))->update([
            'name' => $request->input('name'),
            'email'  => $request->input('email'),
            'phone'     => $request->input('phone'),
            'gender'     => $request->input('gender'),
            'address'     => $request->input('address'),
            'image'     => $imagePath ?? null,
            'joining_date'     => $request->input('joining_date'),
            'monthly_salary'     => $request->input('monthly_salary'),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Trainer updated successfully']);
    }

    public function view($id)
    {
        $id = decrypt($id);
        $trainer = DB::table('trainers')->where('id', $id)->first();
        return view('admin.trainers.view', compact('trainer'));
    }


}
