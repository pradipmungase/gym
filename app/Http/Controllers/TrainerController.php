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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

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
            'name'            => 'required|string|max:255',
            'email'           => 'required|string|email|unique:trainers,email',
            'phone'           => [
                'required',
                'regex:/^[6-9]\d{9}$/',
                Rule::unique('trainers', 'phone'),
            ],
            'gender'          => 'required|string',
            'address'         => 'required|string',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'joining_date'    => 'required|date',
            'monthly_salary'  => 'required|numeric|min:1',
            'birth_date'   => 'required|date|before:today',
        ], [
            'name.required'            => 'Trainer name is required.',
            'name.max'                 => 'Name should not exceed 255 characters.',
            'email.required'           => 'Email is required.',
            'email.email'              => 'Enter a valid email address.',
            'email.unique'             => 'This email is already taken.',
            'phone.required'           => 'Phone number is required.',
            'phone.regex'              => 'Enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
            'phone.unique'             => 'This phone number is already in use.',
            'gender.required'          => 'Please select a gender.',
            'address.required'         => 'Address is required.',
            'image.image'              => 'The file must be an image.',
            'image.mimes'              => 'Allowed image types are jpeg, png, jpg, gif, svg.',
            'image.max'                => 'Image size must not exceed 2MB.',
            'joining_date.required'    => 'Joining date is required.',
            'joining_date.date'        => 'Enter a valid joining date.',
            'monthly_salary.required'  => 'Monthly salary is required.',
            'monthly_salary.numeric'   => 'Monthly salary must be a number.',
            'monthly_salary.min'       => 'Monthly salary must be at least 1.',
            'birth_date.required'   => 'Date of birth is required.',
            'birth_date.date'       => 'Enter a valid date of birth.',
        ]);

        DB::beginTransaction();

        try {
            $gymId = Auth::id();

            $trainerId = DB::table('trainers')->insertGetId([
                'name'            => $request->input('name'),
                'email'           => strtolower($request->input('email')),
                'phone'           => $request->input('phone'),
                'gender'          => $request->input('gender'),
                'address'         => $request->input('address'),
                'image'           => null,
                'joining_date'    => $request->input('joining_date'),
                'monthly_salary'  => $request->input('monthly_salary'),
                'birth_date'      => $request->input('birth_date'),
                'gym_id'          => $gymId,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = uploadFile($image, 'trainersProfilePicture', $trainerId); // you already have this method
                DB::table('trainers')->where('id', $trainerId)->update(['image' => $path]);
            }

            Cache::forget("trainers_gym_{$gymId}");
            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Trainer added successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Trainer Add Error: '.$e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Something went wrong while adding the trainer.']);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'trainer_id' => ['required', Rule::exists('trainers', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', Rule::unique('trainers', 'email')->ignore($request->input('trainer_id'))],
            'phone' => ['required', 'regex:/^[6-9]\d{9}$/', Rule::unique('trainers', 'phone')->ignore($request->input('trainer_id'))],
            'gender' => ['required', 'string'],
            'address' => ['required', 'string'],
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'joining_date' => ['required', 'date'],
            'monthly_salary' => ['required', 'numeric', 'min:1'],
            'birth_date' => ['required', 'date', 'before:today'],
        ]);

        try {
            $trainerId = $request->input('trainer_id');

            $updateData = [
                'name' => $request->input('name'),
                'email' => strtolower($request->input('email')),
                'phone' => $request->input('phone'),
                'gender' => $request->input('gender'),
                'address' => $request->input('address'),
                'joining_date' => $request->input('joining_date'),
                'monthly_salary' => $request->input('monthly_salary'),
                'birth_date' => $request->input('birth_date'),
                'updated_at' => now(),
            ];

            if ($request->hasFile('editTrainerImage')) {
                $image = $request->file('editTrainerImage');
                $path = uploadFile($image, 'trainersProfilePicture', $trainerId);
                $updateData['image'] = $path;
            }

            DB::table('trainers')->where('id', $trainerId)->update($updateData);

            // Clear cache
            $gymId = Auth::id();
            Cache::forget("trainers_gym_{$gymId}");

            return response()->json([
                'status' => 'success',
                'message' => 'Trainer updated successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Trainer update failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }


    public function view($id)
    {
        $id = decrypt($id);
        $trainer = DB::table('trainers')->where('id', $id)->first();

        $trainerMembers = DB::table('trainers')
            ->where('id', $id)
            ->get();
        
        $members = DB::table('members')
            ->join('member_memberships', 'members.id', '=', 'member_memberships.member_id')
            ->where('trainer_id', $id)
            ->get();

        return view('admin.trainers.view', compact('trainer','trainerMembers','members'));
    }


}
