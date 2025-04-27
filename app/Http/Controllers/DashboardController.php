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
use Illuminate\Support\Facades\Crypt;



class DashboardController extends Controller{
    

    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function saveLatitudeAndLongitude(Request $request)
    {
        try {
            $user = Auth::user();
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->save();
            return response()->json(['success' => true, 'message' => 'Latitude and longitude saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateProfilePicture(Request $request)
    {
        try {
            $user = Auth::user();

            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $path = uploadFile($file, 'gymOwnerProfilePicture', $user->id);
                $user->profile_picture = $path;
                $user->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Profile picture updated successfully',
                    'image_url' => asset($user->profile_picture),
                ]);
            }
            return response()->json(['success' => false, 'message' => 'No image uploaded']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        if (!$keyword) {
            return response()->json([]);
        }

        $members = DB::table('members')
            ->where('name', 'like', '%' . $keyword . '%')
            ->orWhere('mobile', 'like', '%' . $keyword . '%')
            ->select('id', 'name', 'image','mobile')
            ->limit(5)
            ->get();

        $trainers = DB::table('trainers')
            ->where('name', 'like', '%' . $keyword . '%')
            ->orWhere('phone', 'like', '%' . $keyword . '%')
            ->select('id', 'name', 'image','phone')
            ->limit(5)
            ->get();

        $members = $members->map(function ($member) {
            $member->encrypted_id = Crypt::encrypt($member->id);
            return $member;
        });

        $trainers = $trainers->map(function ($trainer) {
            $trainer->encrypted_id = Crypt::encrypt($trainer->id);
            return $trainer;
        });

        return response()->json([
            'members' => $members,
            'trainers' => $trainers,
        ]);
    }

    public function gymQRCode()
    {
        return view('admin.gymQRCode');
    }

    public function storeRequestFeature(Request $request)
    {
        $request->validate([
            'feature_name' => 'required',
            'description' => 'required',
        ]);

        $trainers = DB::table('request_feature')->insert([
            'gym_id' => Auth::user()->id,
            'feature_name' => $request->feature_name,
            'description' => $request->description,
            'status' => 'requested',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Your request has been sent successfully');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'gymName' => ['required', 'string', 'max:255'],
            'ownerName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns  ', 'max:255','unique:users,email,'.Auth::user()->id],
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/','unique:users,mobile,'.Auth::user()->id],
            'gymAddress' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();
        $user->gym_name = $request->gymName;
        $user->owner_name = $request->ownerName;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->gym_address = $request->gymAddress;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // at least one lowercase
                'regex:/[A-Z]/',      // at least one uppercase
                'regex:/[0-9]/',      // at least one digit
            ],
            'confirmNewPassword' => 'required|same:newPassword',
        ]);

        $user = Auth::user();

        // Check if old password matches
        if (!Hash::check($request->currentPassword, $user->password)) {
            return back()->withErrors(['currentPassword' => 'Current password is incorrect']);
        }

        // Update password
        $user->password = Hash::make($request->newPassword);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully');
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();
        $user->delete(); // Soft delete or force delete based on your app setup

        auth()->logout();

        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }

}
