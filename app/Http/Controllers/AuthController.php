<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller{

    public function register(Request $request)
    {
        $request->validate([
            'gym_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'mobile' => [
                'required',
                'regex:/^[6-9]\d{9}$/',
                'unique:users,mobile'
            ],
            'password' => ['required', 'string', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
            ],
        ]);

        try {
            DB::beginTransaction();

            $user = DB::table('users')->insert([
                'gym_name' => $request->gym_name,
                'owner_name' => $request->owner_name,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user = User::where('mobile', $request->mobile)->first();
            $user->qr_code = $this->generateQRCodeForGym($user->id);
            $user->save();
            Auth::login($user);
            sendWelcomeWhatsappMessageToGymOwner($user);

            DB::commit();
            session()->flash('success', 'Welcome to GYM Manager!');
            return response()->json(['status' => 'success', 'message' => 'Welcome to GYM Manager!'], 200);

        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Registration failed. Please try again.'], 500);
        }
    }

    private function generateQRCodeForGym($gymId)
    {
        $fileName = $gymId . '_' . time() . '.png';
        $userFolderPath = public_path('uploads/QRCodesForGyms/' . $gymId);

        if (!File::exists($userFolderPath)) {
            File::makeDirectory($userFolderPath, 0755, true);
        }

        $filePath = $userFolderPath . '/' . $fileName;
        $qrText = route('memberRegistration', encrypt($gymId));
        $result = Builder::create()
            ->data($qrText)
            ->size(300)
            ->margin(10)
            ->build();

        file_put_contents($filePath, $result->getString());
        
        return 'uploads/QRCodesForGyms/' . $gymId . '/' . $fileName;
    }

    public function checkLogin(Request $request)
    {
        $request->validate([
            'mobile_for_login' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'mobile' => $request->input('mobile_for_login'),
            'password' => $request->input('password'),
        ];
        $remember = $request->has('remember'); // true if checkbox checked

        if (Auth::attempt($credentials, $remember)) {
            session()->flash('success', 'Welcome Back ' . Auth::user()->owner_name);
            return redirect()->intended('dashboard');
        }
        session()->flash('error', 'Invalid credentials');
        return back()->withErrors(['mobile_for_login' => 'Invalid credentials']);

    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string|digits:10|exists:users,mobile',
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Mobile number not found.'], 404);
        }

        sendForgotPasswordWhatsappMessage($user);
        return response()->json(['status' => 'success', 'message' => 'Password reset link sent to whatsapp.'], 200);
    }   

    public function resendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string|digits:10|exists:users,mobile',
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Mobile number not found.'], 404);
        }

        sendForgotPasswordWhatsappMessage($user);
        return response()->json(['status' => 'success', 'message' => 'OTP sent to whatsapp.'], 200);
    }   

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|digits:4',
        ]);
        $otp = session()->get('otp');

        if ($request->otp != $otp) {
            return response()->json(['status' => 'error', 'message' => 'Invalid OTP.'], 400);
        }
        $user = User::where('mobile', session()->get('mobile'))->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
        }
        $link = url('resetPassword').'/'.encrypt($user->id);
        session()->forget('otp');
        session()->forget('mobile');
        return response()->json(['status' => 'success', 'message' => 'OTP verified successfully.', 'link' => $link], 200);
    }       
    public function resetPassword($token)
    {
        $decryptedToken = decrypt($token);

        $user = User::where('id', $decryptedToken)->first();

        if (!$user) {
            echo "User not found.";
        }

        return view('auth.resetPassword', compact('user'));
    }       

    public function finalResetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
            ],
            'password_confirmation' => 'required|same:password',
        ]);


        $user = User::where('id', $request->id)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Password reset successfully.'], 200);
    }
}
