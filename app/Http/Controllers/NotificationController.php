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
        $this->generateBirthdayAndAnniversaryNotifications();
        $this->generateMembershipExpiryNotifications();
        $this->generateTodaySummaryNotifications();
    }

    public function generateBirthdayAndAnniversaryNotifications()
    {
        $today = Carbon::today();
        $todayMonthDay = $today->format('m-d');
        $todayDate = $today->toDateString();
        $nowTime = now()->toTimeString();
        $nowTimestamp = now();

        $notifications = [];

        // ==== 1. Member Birthdays ====
        $members = DB::table('members')
            ->join('users', 'members.gym_id', '=', 'users.id')
            ->select('members.*', 'users.gym_name')
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
                // Notification
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

                // WhatsApp Message
                $message = "🎉 *Happy Birthday {$member->name}!* 🎉\n\n"
                        . "Wishing you a fantastic day filled with joy and a year full of success and good health! 🥳\n\n"
                        . "- *Team {$member->gym_name}*";
                sendWhatsappMessage($member->mobile, $message, null, 'birthday_member');
            }
        }

        // ==== 2. Trainer Work Anniversary ====
        $trainersForAnniversary = DB::table('trainers')
            ->join('users', 'trainers.gym_id', '=', 'users.id')
            ->select('trainers.*', 'users.gym_name')
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
                // Notification
                $notifications[] = [
                    'gym_id' => $trainer->gym_id,
                    'title' => 'Happy Work Anniversary!',
                    'description' => "Celebrating {$trainer->name}'s work anniversary today!",
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

                // WhatsApp Message
                $message = "🎉 *Happy Work Anniversary {$trainer->name}!* 🎉\n\n"
                        . "Thank you for your hard work and dedication. We’re proud to have you on the team! 💪\n\n"
                        . "- *Team {$trainer->gym_name}*";
                sendWhatsappMessage($trainer->phone, $message, null, 'anniversary_trainer');
            }
        }

        // ==== 3. Trainer Birthdays ====
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
                // Notification
                $notifications[] = [
                    'gym_id' => $trainer->gym_id,
                    'title' => 'Happy Birthday!',
                    'description' => "Wishing {$trainer->name} a wonderful birthday!",
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

                // WhatsApp Message
                $message = "🎉 *Happy Birthday {$trainer->name}!* 🎉\n\n"
                        . "May your day be filled with happiness and your year with success! 🥳\n\n"
                        . "- *Team {$trainer->gym_name}*";
                sendWhatsappMessage($trainer->mobile, $message, null, 'birthday_trainer');
            }
        }

        // ==== Final Bulk Insert ====
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

    public function generateTodaySummaryNotifications()
    {
        $today = Carbon::today()->toDateString();
        $nowTime = now()->toTimeString();
        $nowTimestamp = now();

        // Fetch all gyms (assuming gyms are those who have active members)
        $gymIds = DB::table('users')
            ->pluck('id')
            ->unique();

        // Start a transaction to ensure atomicity
        DB::beginTransaction();

        try {
            foreach ($gymIds as $gymId) {

                // Check if a notification already exists for the gym today to avoid duplicates
                $existingNotification = DB::table('notifications')
                    ->where('gym_id', $gymId)
                    ->whereDate('date', $today)
                    ->where('type', 'today_overall_summary')
                    ->exists();

                if ($existingNotification) {
                    // Skip this gym if a notification already exists
                    continue;
                }

                // 1. Count today's new members for this gym
                $newMembersCount = DB::table('members')
                    ->where('status', 'active')
                    ->where('gym_id', $gymId)
                    ->whereDate('created_at', $today)
                    ->count();

                // 2. Count today's membership expiries for this gym
                $expiringMembershipsCount = DB::table('member_memberships')
                    ->where('gym_id', $gymId)
                    ->where('status', 'active')
                    ->whereDate('end_date', $today)
                    ->count();

                // 3. Count today's payments for this gym
                $paymentsAmount = DB::table('member_payments')
                    ->where('gym_id', $gymId)
                    ->whereDate('payment_date', $today)
                    ->sum('amount_paid');

                // If all counts are zero, skip this gym
                if ($newMembersCount == 0 && $expiringMembershipsCount == 0 && $paymentsAmount == 0) {
                    // continue;
                }

                // Create summary message
                $description = "Today's Summary:\n";
                $description .= "🧑‍🤝‍🧑 New Members: {$newMembersCount}\n";
                $description .= "📅 Memberships Expired: {$expiringMembershipsCount}\n";
                $description .= "💰 Payments Collected: ₹{$paymentsAmount}";

                // Insert notification for this gym
                DB::table('notifications')->insert([
                    'gym_id' => $gymId,
                    'title' => 'Today\'s Overall Summary',
                    'description' => $description,
                    'date' => $today,
                    'time' => $nowTime,
                    'type' => 'today_overall_summary',
                    'member_id' => null,
                    'trainer_id' => null,
                    'status' => 'unread',
                    'image' => null,
                    'created_at' => $nowTimestamp,
                    'updated_at' => $nowTimestamp,
                ]);

                // Send push notification to all users of this gym
                sendPushNotificationToGymUsers($gymId, 'Today\'s Summary', $description);
                sendWhatsappNotificationToGymUser($gymId, $description);
            }

            // Commit transaction if everything is successful
            DB::commit();

        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();

            // Log the error or handle it in a way that suits your application
            \Log::error('Error generating today summary notifications: ' . $e->getMessage());
        }
    }

}
