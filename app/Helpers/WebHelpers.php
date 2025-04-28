<?php
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Notifications\WebPushNotification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str; 




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

function uploadFile($file, $folderName, $id)
{
    if (!$file || !$file->isValid()) {
        return false;
    }

    // Create a unique filename
    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

    // Set the upload path: public/uploads/{folderName}/{id}
    $uploadPath = public_path("uploads/{$folderName}/{$id}");

    // Create the folder if it doesn't exist
    if (!File::isDirectory($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true, true);
    }

    // Move the uploaded file
    $file->move($uploadPath, $filename);

    // Return the file path relative to public
    $file_path = "uploads/{$folderName}/{$id}/{$filename}";

    return $file_path;
}

function getNotification() {
    $notifications = DB::table('notifications')
        ->where('gym_id', Auth::user()->id)
        ->orderBy('id', 'desc')
        ->limit(5)
        ->get();

    // Append relative time to each notification in a shorter format
    foreach ($notifications as $notification) {
        $createdAt = Carbon::parse($notification->created_at);
        $minutes = $createdAt->diffInMinutes();
        $hours = $createdAt->diffInHours();
        $days = $createdAt->diffInDays();

        if ($minutes < 60) {
            $notification->relative_time = $minutes . 'm ago';
        } elseif ($hours < 24) {
            $notification->relative_time = $hours . 'h ago';
        } else {
            $notification->relative_time = $days . 'd ago';
        }
    }

    return $notifications;
}

function sendPushNotificationToAllUsers($title, $body){
    $users = User::all();
    foreach($users as $user){
        if ($user->pushSubscriptions()->exists()) {
            $user->notify(new WebPushNotification([
                'title' => $title,
                'body' => $body,
                'url' => url('/admin/dashboard'),
                'action_text' => 'View',
            ]));
        }
    }
}

function sendPushNotificationToGymUsers($gymId, $title, $body){
    $user = User::where('id', $gymId)->first();
    if ($user->pushSubscriptions()->exists()) {
        $user->notify(new WebPushNotification([
            'title' => $title,
            'body' => $body,    
            'url' => url('/admin/dashboard'),
            'action_text' => 'View',
        ]));
    }
}





// Whatapp message functions
function sendWhatsappMessage($mobile, $message, $image = null, $type = 'general')
{
    $mobile = '7028143227';
    $responseContent = '';
    $status = 'failed';

    try {
        $payload = [
            'number' => '91' . $mobile,
            'message' => $message,
        ];

        if (!empty($image)) {
            $payload['image'] = $image;
        }

        $response = Http::post('http://localhost:3000/send-message', $payload);

        if ($response->successful()) {
            $responseContent = $response->body();
            $status = 'success';
        } else {
            $responseContent = $response->body(); // failed response
        }
    } catch (\Exception $e) {
        Log::error('WhatsApp Message Failed: ' . $e->getMessage());
        $responseContent = $e->getMessage();
    }

    // Log every attempt
    addWhatsappLog($mobile, $message, $image, $type, $responseContent, $status);
}

function addWhatsappLog($mobile, $message, $image = null, $type, $responseContent = '', $status = 'failed')
{
    $data = [
        'mobile' => $mobile,
        'message' => $message,
        'image' => $image ?? '',
        'type' => $type,
        'status' => $status,
        'response' => $responseContent,
        'created_at' => now(),
        'updated_at' => now(),
    ];

    DB::table('whatsapp_mess_tracking')->insert($data);
}

function sendForgotPasswordWhatsappMessage($user)
{
    $mobile = $user->mobile;
    $otp = rand(1000, 9999);
    session(['otp' => $otp]);
    session(['mobile' => $mobile]);
    $message = "Hi {$user->owner_name},\nYour OTP for resetting password is:\n$otp\n\nIgnore if not requested.";
    sendWhatsappMessage($mobile, $message, $image = null, $type = 'forgot_password');
}

function sendWelcomeWhatsappMessage($user)
{
    $mobile = $user->mobile;
    $gymName = Auth::user()->gym_name ?? 'Your Gym';
    $message = "👋 Hey $user->owner_name,\n\nWelcome to *$gymName*! 🏋️‍♂️\nWe're excited to have you on board! 💪\n\nIf you have any questions or need help, feel free to contact us at *7028143227*. 📞";
    $base64Image = base64_encode(file_get_contents(public_path($user->qr_code)));
    sendWhatsappMessage($mobile, $message, $image = null, $type = 'member_registration');
}

function sendAnnouncement($for, $title, $description, $date)
{
    try {
        $gymName = Auth::user()->gym_name ?? 'Your Gym';
        $formattedDate = Carbon::parse($date)->format('d M, Y');

        if ($for == 'all' || $for == 'members') {
            $members = DB::table('members')->where('gym_id', Auth::user()->id)->get();
            foreach ($members as $member) {
                if (!empty($member->mobile)) {
                    $mobile = $member->mobile;
                    $message = "👋 Hello $member->name,\n\n$title\n\n$description\n\n$formattedDate\n\n*$gymName*";
                    sendWhatsappMessage($mobile, $message, $image = null, $type = 'announcement');
                }
            }
        }

        if ($for == 'all' || $for == 'trainers') {
            $trainers = DB::table('trainers')->where('gym_id', Auth::user()->id)->get();
            foreach ($trainers as $trainer) {
                if (!empty($trainer->mobile)) {
                    $mobile = $trainer->mobile;
                    $message = "👋 Hello $trainer->name,\n\n$title\n\n$description\n\n$formattedDate\n\n*$gymName*";
                    sendWhatsappMessage($mobile, $message, $image = null, $type = 'announcement');
                }
            }
        }

    } catch (\Exception $e) {
        Log::error('WhatsApp Announcement Message Failed: ' . $e->getMessage());
        // Don't throw error to caller
    }
}

function sendWhatsAppMessageForAttendanceMarked($member)
{
    $mobile = $member->mobile;
    $gymName = Auth::user()->gym_name ?? 'Your Gym';
    $formattedDate = Carbon::now()->format('d M Y');
    $formattedTime = Carbon::now()->format('h:i A');
    $message = "👋 Hello $member->name,\n\nYour attendance has been *marked successfully* for today 📅 *$formattedDate* at 🕒 *$formattedTime*.\n\nKeep up the great work at *$gymName*! 💪\n\nSee you tomorrow! 😊";
    sendWhatsappMessage($mobile, $message, $image = null, $type = 'attendance_marked');
}

function sendWhatsAppMessageForMemberRegistration($mobile, $name, $imagePath)
{
    $gymName = Auth::user()->gym_name ?? 'Your Gym';
    $base64Image = base64_encode(file_get_contents(public_path($imagePath)));
    $message = "👋 Hello $name,\n\nWelcome to *$gymName*! 🏋️‍♂️\nHere is your QR Code for daily attendance. 📲\n\nMake sure to scan it every day when you visit! ✅";
    sendWhatsappMessage($mobile, $message, $imagePath, $type = 'member_registration');
}

function sendMarketingWhatsapp($whatsappNumber, $outputdata)
{
    try {
        $mobile = $whatsappNumber;
        $link = "https://yourdomain.com/login";
        $message = "Hi {$outputdata['owner_name']},\n\nManage your gym easily using our web portal:\n{$link}\n\nThanks!";
        return Http::post('http://localhost:3000/send-message', [
            'number' => '91' . $mobile,
            'message' => $message,
        ]);
    } catch (\Exception $e) {
        return $e->getMessage();    
    }
}

function sendWhatsAppMessageForMemberPayment($mobile, $name, $paid_amount, $payment_mode, $due_amount, $payment_date)
{
    $gymName = Auth::user()->gym_name ?? 'Your Gym';

    $formattedDate = Carbon::parse($payment_date)->format('d M Y');
    $formattedTime = Carbon::parse($payment_date)->format('h:i A');
    $paymentMode = $payment_mode ?? 'Cash';

    $message = "👋 Hi {$name},\n\n"
        . "💰 Your payment of ₹{$paid_amount} has been successfully received.\n\n"
        . "📅 Date: {$formattedDate}\n"
        . "⏰ Time: {$formattedTime}\n"
        . "💳 Payment Mode: {$paymentMode}\n"
        . "💸 Due Amount Remaining: ₹{$due_amount}\n\n"
        . "If you have any questions, feel free to contact us at 📞 *7028143227*.\n\n"
        . "🏋️‍♂️ *{$gymName}*";

    // Call the helper function
    sendWhatsappMessage($mobile, $message, $image = null, $type = 'member_payment');
}
  

function sendRequestFeatureWhatsappMessage($feature_name, $description)
{
    $mobile = Auth::user()->mobile;
    $message = "Hi " . auth()->user()->owner_name . ",\n\nYour new feature request has been submitted.\n\nOur team will work on it and update you as soon as possible.\n\nThanks!";
    sendWhatsappMessage($mobile, $message, $image = null, $type = 'request_feature');
}
