<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Services\BrevoService;

function sendAdminInvitationEmail($data) {
    try {
        $brevoService = new BrevoService();
        $subject = "Admin Account Invitation";
        $mailContent = "
            <p>Dear {$data['firstName']} {$data['lastName']},</p>
            <p>You have been invited to join <strong>Uplifty</strong> as an admin.</p>
            <p>Your login details are as follows:</p>
            <p>Email: {$data['email']}</p>
            <p>Password: {$data['password']}</p>
            <p>Please <a href='" . URL::to('/login') . "'>click here</a> to log in and change your password after your first login.</p>
            <p>Thank you,</p>
        ";
        
        $templatePath = resource_path('views/emails/defaultMailTemplate.html');
        $content = file_get_contents($templatePath);
        $content = str_replace(['{{ subject }}', '{{ mailContent }}'], [$subject, $mailContent], $content);
        
        $emailSent = $brevoService->sendEmail($data['email'], $data['firstName'] . ' ' . $data['lastName'], $subject, $content);
        
        if (!$emailSent) {
            throw new \Exception('Failed to send admin invitation email');
        }
    } catch (\Exception $e) {
        throw new \Exception('Failed to send admin invitation email: ' . $e->getMessage());
    }
}

function logAdminAddActivity($userId) {
    try {
        $userDetails = getUserDetails($userId);

        $activityData = [
            'action' => 'create',
            'table_name' => 'users',
            'table_pk_id' => $userId,
            'from' => 'Web', 
            'action_by' => Auth::user()->id,
            'new_data' => json_encode($userDetails),
            'created_at' => now(),
            'updated_at' => now()
        ];

        if (!DB::table('activity_logs')->insert($activityData)) {
            throw new \Exception('Failed to log activity');
        }

        return $userDetails;
    } catch (\Exception $e) {
        throw new \Exception('Failed to log admin add activity: ' . $e->getMessage());
    }
}

function logAdminEditOldActivity($userId) {
    try {
        $userDetails = getUserDetails($userId);

        $activityData = [
            'action' => 'update',
            'table_name' => 'users',
            'table_pk_id' => $userId,
            'from' => 'Web',
            'action_by' => Auth::user()->id,
            'old_data' => json_encode($userDetails),
            'created_at' => now(),
            'updated_at' => now()
        ];

        $activityId = DB::table('activity_logs')->insertGetId($activityData);

        if (!$activityId) {
            throw new \Exception('Failed to log activity');
        }

        return $activityId;
    } catch (\Exception $e) {
        throw new \Exception('Failed to log admin edit old activity: ' . $e->getMessage());
    }
}

function logAdminEditNewActivity($userId, $activityId) {
    try {
        $userDetails = getUserDetails($userId);

        $updated = DB::table('activity_logs')
            ->where('activity_id', $activityId)
            ->update([
                'new_data' => json_encode($userDetails),
                'updated_at' => now()
            ]);

        if (!$updated) {
            throw new \Exception('Failed to log activity');
        }
    } catch (\Exception $e) {
        throw new \Exception('Failed to log admin edit new activity: ' . $e->getMessage());
    }
}

function getUserDetails($userId) {
    $userDetails = DB::table('users')
        ->where('users.id', $userId)
        ->select(
            'users.id',
            'users.first_name', 
            'users.last_name',
            'users.email',
            'users.user_type'
        )
        ->get()
        ->map(function($user) use ($userId) {
            $menuPermissions = DB::table('users_menu_permissions')
                ->join('menu', 'users_menu_permissions.menu_id', '=', 'menu.menu_id')
                ->where('user_id', $userId)
                ->select('menu.menu_name', 'users_menu_permissions.permissions')
                ->get();
                
            $user->menu_details = $menuPermissions;
            return $user;
        });

    if (!$userDetails) {
        throw new \Exception('User details not found');
    }
    return $userDetails;
}



function getCounselorsDetails($userId) {
    $userDetails = DB::table('users')
        ->where('users.id', $userId)
        ->get();

    if (!$userDetails) {
        throw new \Exception('User details not found');
    }
    return $userDetails;
}


function logCounselorsAddActivity($userId) {
    try {
        $userDetails = getCounselorsDetails($userId);

        $activityData = [
            'action' => 'create',
            'table_name' => 'users',
            'table_pk_id' => $userId,
            'from' => 'Web', 
            'action_by' => Auth::user()->id,
            'new_data' => json_encode($userDetails),
            'created_at' => now(),
            'updated_at' => now()
        ];

        if (!DB::table('activity_logs')->insert($activityData)) {
            throw new \Exception('Failed to log activity');
        }

        return $userDetails;
    } catch (\Exception $e) {
        throw new \Exception('Failed to log admin add activity: ' . $e->getMessage());
    }
}