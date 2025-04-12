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

class AttendanceController extends Controller{
    
    public function index()
    {
        return view('admin.attendance.index'); // just loads view with empty or initial content
    }

    public function takeAttendance()
    {
        return view('admin.attendance.take'); // just loads view with empty or initial content
    }
    public function markAttendance(Request $request)
    {
        $id = $request->input('id');
        $user = DB::table('members')->where('id', $id)->where('gym_id', Auth::user()->id)->first();
        if($user){
            $data =['name' => $user->name];
            $attendance = DB::table('attendance')->where('member_id', $id)->where('date', date('Y-m-d'))->where('gym_id', Auth::user()->id)->first();
            if($attendance){
                return response()->json(['message' => 'Attendance already marked','status' => 'already_marked', 'data' => $data]);
            }else{
                $attendance = DB::table('attendance')->insert([
                    'member_id' => $id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => 'present',
                    'gym_id' => Auth::user()->id
                ]);
                return response()->json(['message' => 'Attendance marked successfully','status' => 'success', 'data' => $data]);
            }
        }else{
            return response()->json(['message' => 'User not found','status' => 'error']);
        }
        
    }


}
