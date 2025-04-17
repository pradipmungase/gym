<?php
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Notifications\WebPushNotification;


function sendWhatsAppMessageForAttendanceMarked($member)
{
    try {
        $mobile = '7028143227'; // Replace with dynamic: $member->mobile ?? fallback

        $gymName = Auth::user()->gym_name ?? 'Your Gym';
        $formattedDate = Carbon::now()->format('d M Y');
        $formattedTime = Carbon::now()->format('h:i A');

        $message = "👋 Hello $member->name,\n\nYour attendance has been *marked successfully* for today 📅 *$formattedDate* at 🕒 *$formattedTime*.\n\nKeep up the great work at *$gymName*! 💪\n\nSee you tomorrow! 😊";

        Http::post('http://localhost:3000/send-message', [
            'number' => '91' . $mobile,
            'message' => $message,
        ]);
    } catch (\Exception $e) {
        Log::error('WhatsApp Attendance Message Failed: ' . $e->getMessage());
        // Don't throw error to caller
    }
}

function sendWhatsAppMessageForMemberRegistration($mobile, $name, $imagePath)
{
    try {
        $mobile = '7028143227';
        $gymName = Auth::user()->gym_name ?? 'Your Gym';
        $base64Image = base64_encode(file_get_contents($imagePath));

        $message = "👋 Hello $name,\n\nWelcome to *$gymName*! 🏋️‍♂️\nHere is your QR Code for daily attendance. 📲\n\nMake sure to scan it every day when you visit! ✅";

        Http::post('http://localhost:3000/send-message', [
            'number' => '91' . $mobile,
            'message' => $message,
            'image' => $base64Image,
        ]);
    } catch (\Exception $e) {
        Log::error('WhatsApp Registration Message Failed: ' . $e->getMessage());
        // Silent fail to avoid breaking the registration flow
    }
}


function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
{
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
}


function sendWelcomeEmail($user)
{
    try {
        $mobile = '7028143227'; // Replace with dynamic: $member->mobile ?? fallback

        $gymName = Auth::user()->gym_name ?? 'Your Gym';
        $formattedDate = Carbon::now()->format('d M Y');
        $formattedTime = Carbon::now()->format('h:i A');

        $message = "👋 Hey $user->owner_name,\n\nWelcome to *$gymName*! 🏋️‍♂️\nWe're excited to have you on board! 💪\n\nIf you have any questions or need help, feel free to contact us at *7028143227*. 📞";


        Http::post('http://localhost:3000/send-message', [
            'number' => '91' . $mobile,
            'message' => $message,
        ]);
    } catch (\Exception $e) {
        Log::error('WhatsApp Attendance Message Failed: ' . $e->getMessage());
        // Don't throw error to caller
    }
}

function sendForgotPasswordWhatsappMessage($user)
{
    try {
        $mobile = '7028143227'; // Replace with dynamic: $member->mobile ?? fallback
        $otp = rand(1000, 9999);
        session(['otp' => $otp]);
        session(['mobile' => $user->mobile]);

        $message = "Hi {$user->owner_name},\nYou OTP for resetting password is:\n$otp\n\nIgnore if not requested.";

        Http::post('http://localhost:3000/send-message', [
            'number' => '91' . $mobile,
            'message' => $message,
        ]);
    } catch (\Exception $e) {
        Log::error('WhatsApp Attendance Message Failed: ' . $e->getMessage());
        // Don't throw error to caller
    }
}


function sendAnnouncement($for, $title, $description, $date)
{
    try {
        $defaultMobile = '7028143227';
        $gymName = Auth::user()->gym_name ?? 'Your Gym';
        if($for == 'all'){
            $members = DB::table('members')->where('gym_id', Auth::user()->id)->get();
            foreach($members as $member){
                $message = "👋 Hello $member->name,\n\n$title\n\n$description\n\n$date\n\n$gymName";
                Http::post('http://localhost:3000/send-message', [
                    'number' => '91' . $defaultMobile,
                    'message' => $message,
                ]);
            }
            $trainers = DB::table('trainers')->where('gym_id', Auth::user()->id)->get();
            foreach($trainers as $trainer){
                $message = "👋 Hello $trainer->name,\n\n$title\n\n$description\n\n$date\n\n$gymName";
                Http::post('http://localhost:3000/send-message', [
                    'number' => '91' . $defaultMobile,
                    'message' => $message,
                ]);
            }

        }elseif($for == 'members'){
            $members = DB::table('members')->where('gym_id', Auth::user()->id)->get();
            foreach($members as $member){
                $message = "👋 Hello $member->name,\n\n$title\n\n$description\n\n$date\n\n$gymName";
                Http::post('http://localhost:3000/send-message', [
                    'number' => '91' . $defaultMobile,
                    'message' => $message,
                ]);
            }
        }elseif($for == 'trainers'){    
            $trainers = DB::table('trainers')->where('gym_id', Auth::user()->id)->get();
            foreach($trainers as $trainer){
                $message = "👋 Hello $trainer->name,\n\n$title\n\n$description\n\n$date\n\n$gymName ";
                Http::post('http://localhost:3000/send-message', [
                    'number' => '91' . $defaultMobile,
                    'message' => $message,
                ]);
            }
        }
    } catch (\Exception $e) {
        Log::error('WhatsApp Announcement Message Failed: ' . $e->getMessage());
        // Don't throw error to caller
    }
}       


function sendPushNotification(){
        
    $user = User::find(2); // The specific user you want to notify

    if ($user->pushSubscriptions()->exists()) {
        $user->notify(new WebPushNotification([
            'title' => 'Personalized Notification',
            'body' => 'Hello ' . $user->name . ', this is your custom message!',
            'url' => url('/profile/' . $user->id),
            'action_text' => 'View Profile',
            'user_id' => $user->id,
            'custom_data' => [
                'order_id' => 123456
            ]
        ]));
        
        return response()->json(['message' => 'Dynamic notification sent to user']);
    }
}