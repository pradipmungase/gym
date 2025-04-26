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
        sendPushNotification();
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
}
