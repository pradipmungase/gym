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
use Carbon\Carbon;


class AttendanceController extends Controller{
    
    public function index()
    {
        return view('admin.attendance.index'); // just loads view with empty or initial content
    }

    public function takeAttendance()
    {
        return view('admin.attendance.take'); // just loads view with empty or initial content
    }

    public function fetchAttendance(Request $request)
    {
        $attendance = DB::table('attendance')
            ->join('members', 'attendance.member_id', '=', 'members.id')
            ->where('attendance.gym_id', Auth::user()->id)
            ->whereDate('attendance.date', date('Y-m-d'))
            ->select(
                'attendance.*',
                'members.name as member_name',
                'members.email as member_email',
                'members.image as member_image'
            )
            ->orderBy('attendance.date', 'desc')
            ->orderBy('attendance.time', 'desc')
            ->paginate(10);
        return view('admin.attendance.partials.attendance-table', compact('attendance'))->render();
    }

    public function markAttendance(Request $request)
    {
        $memberId = $request->input('id');
        $gymId = Auth::user()->id;

        // Check if member exists and belongs to the current gym
        $member = DB::table('members')
            ->where('id', $memberId)
            ->where('gym_id', $gymId)
            ->first();

        if (!$member) {
            return response()->json([
                'message' => 'User not found',
                'status' => 'error'
            ]);
        }

        $today = Carbon::today()->toDateString();

        // Check if attendance already marked for today
        $alreadyMarked = DB::table('attendance')
            ->where('member_id', $memberId)
            ->where('gym_id', $gymId)
            ->where('date', $today)
            ->exists();

        if ($alreadyMarked) {
            return response()->json([
                'message' => 'Attendance already marked',
                'status' => 'already_marked',
                'data' => ['name' => $member->name]
            ]);
        }

        // Mark attendance
        DB::table('attendance')->insert([
            'member_id' => $memberId,
            'gym_id' => $gymId,
            'date' => $today,
            'time' => Carbon::now()->toTimeString(),
            'status' => 'present',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = sendWhatsAppMessageForAttendanceMarked($member);

        return response()->json([
            'message' => 'Attendance marked successfully',
            'status' => 'success',
            'response' => $response,
            'data' => ['name' => $member->name]
        ]);
    }



    public function markAttendanceByLatLong(Request $request, $gym_id, $member_id)
    {
        $gym = DB::table('users')->where('id', $gym_id)->first();
        $member = DB::table('members')->where('id', $member_id)->first();
        if ($request->isMethod('get')) {
            return view('takeAttendanceByLatLong', compact('gym', 'gym_id', 'member_id', 'member'));
        }

        if(!$gym || !$member){
            return response()->json(['status' => 'error', 'message' => 'Gym or Member not found.']);
        }   
        // Handle POST request
        $userLat = $request->input('latitude');
        $userLng = $request->input('longitude');

        $gymLat = 19.8540341;
        $gymLng = 75.3353875;

        // Calculate distance (in kilometers)
        $distance = haversineGreatCircleDistance($userLat, $userLng, $gymLat, $gymLng);

        if ($distance <= 0.1) {
            // Check if attendance already marked
            $today = Carbon::today()->toDateString();
            $attendance = DB::table('attendance')
                ->where('member_id', $member_id)
                ->where('gym_id', $gym_id)
                ->where('date', $today)
                ->first();

            if ($attendance) {
                return response()->json([
                    'status' => 'already_marked',
                    'message' => 'Attendance already marked.',
                    'member_name' => $member->name ?? 'Member'
                ]);
            }

            // Mark attendance
            DB::table('attendance')->insert([
                'member_id' => $member_id,
                'gym_id' => $gym_id,
                'date' => $today,
                'time' => Carbon::now()->toTimeString(),
                'status' => 'present',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $response = sendWhatsAppMessageForAttendanceMarked($member);

            return response()->json([
                'status' => 'success',
                'message' => 'Attendance marked successfully.',
                'member_name' => $member->name ?? 'Member',
                'response' => $response
            ]);
        }
        return response()->json(['status' => 'location_error', 'message' => 'You are not at the gym location.']);
    }



}
