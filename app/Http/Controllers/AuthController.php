<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;


class AuthController extends Controller{

    public function register(Request $request)
    {
        $request->validate([
            'gym_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'mobile' => 'required|string|digits:10|unique:users,mobile',
            'password' => ['required', 'string', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                // ->uncompromised() // checks if password has been exposed in data leaks
            ],
        ]);

        try {
            DB::beginTransaction();

            DB::table('users')->insert([
                'gym_name' => $request->gym_name,
                'owner_name' => $request->owner_name,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user = User::where('mobile', $request->mobile)->first();

            Auth::login($user);

            DB::commit();

            session()->flash('success', 'Gym registered successfully!');
            return response()->json(['status' => 'success', 'message' => 'Gym registered successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Registration failed. Please try again.'], 500);
        }
    }
    public function checkLogin(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('mobile', 'password');
        $remember = $request->has('remember'); // true if checkbox checked

        if (Auth::attempt($credentials, $remember)) {
            session()->flash('success', 'Welcome Back ' . Auth::user()->owner_name);
            return redirect()->intended('dashboard');
        }
        return back()->withErrors(['email' => 'Invalid credentials']);

    }
}
