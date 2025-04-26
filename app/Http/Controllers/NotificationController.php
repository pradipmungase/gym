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


class NotificationController extends Controller{
    
    public function index()
    {
        $this->generateBirthdayNotifications();
        $this->generateMembershipExpiryNotifications();
    }

    public function generateBirthdayNotifications()
    {
        $today = Carbon::today();
        $todayMonthDay = $today->format('m-d');
        $todayDate = $today->toDateString();
        $nowTime = now()->toTimeString();
        $nowTimestamp = now();

        $notifications = [];

        // ==== Fetch active members having birthday today ====
        $members = DB::table('members')
            ->where('status', 'active')
            ->whereRaw("DATE_FORMAT(birth_date, '%m-%d') = ?", [$todayMonthDay])
            ->get();

        foreach ($members as $member) {
            $alreadyExists = DB::table('notifications')
                ->where('member_id', $member->id)
                ->where('type', 'member')
                ->whereDate('date', $todayDate)
                ->exists();

            if (!$alreadyExists) {
                $notifications[] = [
                    'gym_id' => $member->gym_id,
                    'title' => 'Happy Birthday!',
                    'description' => 'Wishing ' . $member->name . ' a wonderful birthday!',
                    'date' => $todayDate,
                    'time' => $nowTime,
                    'type' => 'member',
                    'member_id' => $member->id,
                    'trainer_id' => null,
                    'status' => 'unread',
                    'image' => $member->image,
                    'created_at' => $nowTimestamp,
                    'updated_at' => $nowTimestamp,
                ];
            }
        }

        // ==== Fetch active trainers having joining anniversary today ====
        $trainersForAnniversary = DB::table('trainers')
            ->where('status', 'active')
            ->whereRaw("DATE_FORMAT(joining_date, '%m-%d') = ?", [$todayMonthDay])
            ->get();

        foreach ($trainersForAnniversary as $trainer) {
            $alreadyExists = DB::table('notifications')
                ->where('trainer_id', $trainer->id)
                ->where('type', 'trainer')
                ->where('title', 'Happy Work Anniversary!')
                ->whereDate('date', $todayDate)
                ->exists();

            if (!$alreadyExists) {
                $notifications[] = [
                    'gym_id' => $trainer->gym_id,
                    'title' => 'Happy Work Anniversary!',
                    'description' => 'Celebrating ' . $trainer->name . '\'s work anniversary today!',
                    'date' => $todayDate,
                    'time' => $nowTime,
                    'type' => 'trainer',
                    'member_id' => null,
                    'trainer_id' => $trainer->id,
                    'status' => 'unread',
                    'image' => $trainer->image,
                    'created_at' => $nowTimestamp,
                    'updated_at' => $nowTimestamp,
                ];
            }
        }

        // ==== Fetch active trainers having birthday today ====
        $trainersForBirthday = DB::table('trainers')
            ->where('status', 'active')
            ->whereRaw("DATE_FORMAT(birth_date, '%m-%d') = ?", [$todayMonthDay])
            ->get();

        foreach ($trainersForBirthday as $trainer) {
            $alreadyExists = DB::table('notifications')
                ->where('trainer_id', $trainer->id)
                ->where('type', 'trainer')
                ->where('title', 'Happy Birthday!')
                ->whereDate('date', $todayDate)
                ->exists();

            if (!$alreadyExists) {
                $notifications[] = [
                    'gym_id' => $trainer->gym_id,
                    'title' => 'Happy Birthday!',
                    'description' => 'Wishing ' . $trainer->name . ' a wonderful birthday!',
                    'date' => $todayDate,
                    'time' => $nowTime,
                    'type' => 'trainer',
                    'member_id' => null,
                    'trainer_id' => $trainer->id,
                    'status' => 'unread',
                    'image' => $trainer->image,
                    'created_at' => $nowTimestamp,
                    'updated_at' => $nowTimestamp,
                ];
            }
        }

        // ==== Final Bulk Insert for all notifications ====
        if (!empty($notifications)) {
            DB::table('notifications')->insert($notifications);
        }
    }

    public function generateMembershipExpiryNotifications()
    {
        $today = Carbon::today()->toDateString();
        $nowTime = now()->toTimeString();
        $nowTimestamp = now();

        $memberships = DB::table('member_memberships')
            ->where('status', 'active') // only active memberships
            ->where('end_date', $today) // ending today
            ->get();

        $notifications = [];

        foreach ($memberships as $membership) {
            $alreadyExists = DB::table('notifications')
                ->where('member_id', $membership->member_id)
                ->where('type', 'membership_expiry')
                ->whereDate('date', $today)
                ->exists();

            if (!$alreadyExists) {
                // Fetch member info for name and image
                $member = DB::table('members')->where('id', $membership->member_id)->first();

                if ($member) {
                    $notifications[] = [
                        'gym_id' => $membership->gym_id,
                        'title' => 'Membership Expiry Alert!',
                        'description' => 'Hey ' . $member->name . ', your membership expires today. Please renew!',
                        'date' => $today,
                        'time' => $nowTime,
                        'type' => 'membership_expiry',
                        'member_id' => $member->id,
                        'trainer_id' => $membership->trainer_id,
                        'status' => 'unread',
                        'image' => $member->image,
                        'created_at' => $nowTimestamp,
                        'updated_at' => $nowTimestamp,
                    ];
                }
            }
        }

        // Bulk insert notifications
        if (!empty($notifications)) {
            DB::table('notifications')->insert($notifications);
        }
    }


}
