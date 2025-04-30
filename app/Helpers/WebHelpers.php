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

    foreach ($notifications as $notification) {
        $createdAt = Carbon::parse($notification->created_at);
        $minutes = $createdAt->diffInMinutes();
        $hours = $createdAt->diffInHours();
        $days = $createdAt->diffInDays();

        // Set relative time
        if ($minutes < 60) {
            $notification->relative_time = $minutes . 'm ago';
        } elseif ($hours < 24) {
            $notification->relative_time = $hours . 'h ago';
        } else {
            $notification->relative_time = $days . 'd ago';
        }

        // Update title if type is "today_overall_summary"
        if ($notification->type === 'today_overall_summary') {
            if ($createdAt->isToday()) {
                $notification->title = "Today's Overall Summary";
            } elseif ($createdAt->isYesterday()) {
                $notification->title = "Yesterday's Overall Summary";
            } else {
                $notification->title = $createdAt->format('d M Y') . ' Overall Summary';
            }
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
            $base64Image = base64_encode(file_get_contents(public_path($image)));
            $payload['imageBase64'] = $base64Image;
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

function sendWelcomeWhatsappMessageToGymOwner($user)
{
    $mobile = $user->mobile;
    $gymName = Auth::user()->gym_name ?? 'Your Gym';
    $message = "👋 Hey $user->owner_name,\n\nWelcome to *$gymName*! 🏋️‍♂️\nWe're excited to have you on board! 💪\n\nIf you have any questions or need help, feel free to contact us at *7028143227*. 📞";
    sendWhatsappMessage($mobile, $message, $user->qr_code, $type = 'member_registration');
}


function sendWhatsappNotificationToGymUser($gymId, $description)
{
    $gym = DB::table('users')->where('id', $gymId)->first();
    $mobile = $gym->mobile;
    $message = "👋 Hey $gym->owner_name,\n\n$description";
    sendWhatsappMessage($mobile, $message, $image = null, $type = 'daily_summary');
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
        throw $e;
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


function sendWhatsAppMessageForMembberRequstToGymOwner($name, $gymId)
{
    $ownerDetails = DB::table('users')->where('id', $gymId)->first();
    $mobile = $ownerDetails->mobile;
    $message = "Hi " . $ownerDetails->owner_name . ",\n\nYour have received a new member registration request.\n\nName: $name\n\nThanks!";
    sendWhatsappMessage($mobile, $message, $image = null, $type = 'member_registration_request');
}


function sendWhatsAppMessageForMemberPlanChange($memberId, $oldPlanId, $newPlanId, $newPlanPrice, $newPlanPriceAfterDiscount, $joiningDate, $expiryDate,$newDueAmount)
{
    $memberDetails = DB::table('members')->where('id', $memberId)->first();

    if (!$memberDetails) return;

    $mobile = $memberDetails->mobile;
    $memberName = $memberDetails->name ?? 'Member';
    $gymName = Auth::user()->gym_name ?? 'Your Gym';

    $formattedJoinDate = Carbon::parse($joiningDate)->format('d M Y');
    $formattedExpiryDate = Carbon::parse($expiryDate)->format('d M Y');

    $oldPlan = DB::table('menbership_plans')->where('id', $oldPlanId)->first();
    $newPlan = DB::table('menbership_plans')->where('id', $newPlanId)->first();

    $oldPlanName = $oldPlan->name ?? 'Previous Plan';
    $newPlanName = $newPlan->name ?? 'New Plan';

    $message = "🎉 *Membership Plan Updated!* 🎉\n\n"
            . "Hi *$memberName*,\n\n"
            . "Your membership at *$gymName* has been successfully updated. Here are the details:\n\n"
            . "🗓️ *Start Date:* $formattedJoinDate\n"
            . "📅 *Expiry Date:* $formattedExpiryDate\n"
            . "🔁 *Previous Plan:* $oldPlanName\n"
            . "✅ *New Plan:* $newPlanName\n"
            . "💰 *Plan Price:* ₹$newPlanPrice\n"
            . "🎁 *Discounted Price:* ₹$newPlanPriceAfterDiscount\n"
            . "💸 *Due Amount:* ₹$newDueAmount\n\n"
            . "We’re excited to continue supporting your fitness journey! 💪\n\n"
            . "Regards,\n*$gymName* Team";

    sendWhatsappMessage($mobile, $message, null, 'member_plan_change');
}

function sendWhatsAppMessageForMemberRenewal($memberId, $newPlanPrice, $newPlanPriceAfterDiscount, $newDueAmount,$expiryDate)
{
    $memberDetails = DB::table('members')->where('id', $memberId)->first();
    $mobile = $memberDetails->mobile;
    $memberName = $memberDetails->name ?? 'Member';
    $gymName = Auth::user()->gym_name ?? 'Your Gym';
    $formattedExpiryDate = Carbon::parse($expiryDate)->format('d M Y');

    $message = "🎉 *Membership Renewed!* 🎉\n\n"
            . "Hi *$memberName*,\n\n"
            . "Your membership at *$gymName* has been successfully renewed. Here are the details:\n\n"
            . "📅 *Expiry Date:* $formattedExpiryDate\n"
            . "💰 *Plan Price:* ₹$newPlanPrice\n"
            . "🎁 *Discounted Price:* ₹$newPlanPriceAfterDiscount\n"
            . "💸 *Due Amount:* ₹$newDueAmount\n\n"
            . "We’re excited to continue supporting your fitness journey! 💪\n\n"
            . "Regards,\n*$gymName* Team";

    sendWhatsappMessage($mobile, $message, null, 'member_renewal');
}




























function test() {
    // Your closing prices (newest first - reversed from your example)
$closingPrices = [
    812.55, 817.35, 798.65, 813.4, 813.45, 822.4, 816.7, 797.45, 771.75, 763.5,
    753.85, 742.2, 768.6, 746.9, 767.45, 779.2, 775.95, 771.7, 771.5, 772.3,
    764, 772.85, 780.8, 753.2, 749.55, 745.1, 736.7, 723.15, 727.85, 723.05,
    729.85, 728.9, 732.75, 732.05, 730.35, 716.05, 695.3, 688.8, 703.9, 710.9,
    716.4, 722, 729.7, 727.3, 725.8, 727.7, 722.15, 727.65, 733.15, 731.1,
    736.8, 737.2, 752.25, 766.05, 779.2, 760.95, 766, 772.9, 762.6, 758.45,
    752.4, 749.2, 744.15, 745.9, 753.45, 759.05, 779.25, 764.1, 766.3, 753.7,
    748.15, 729.5, 743.25, 760.45, 771.15, 778.75, 776.4, 793.4, 801.2, 793.2,
    794.95, 788.3, 799.65, 812.45, 812.05, 821.15, 812, 832.8, 838.15, 850.55,
    860.95, 861.55, 853.7, 861.6, 867.5, 858.05, 863.65, 865.45, 859.7, 853.95,
    836.4, 838.95, 838.85, 834.1, 839.4, 844.45, 816.05, 780.75, 803, 814.3,
    804.25, 808.65, 826.7, 847.65, 843.15, 859.6, 854.8, 849.2, 829.85, 821.2,
    820.2, 822.45, 832.7, 792.05, 780.95, 794.55, 786, 790.4, 813.95, 820.4,
    811.05, 805.45, 804.65, 805.15, 799.75, 797.1, 797.4, 781.45, 770.65,
    796.65, 794.1, 796.95, 787.9, 802.65, 801.85, 793.1, 798.25, 801.85,
    781.7, 789.95, 792.75, 782.9, 785.55, 790.85, 787.75, 768.6, 782.65,
    784.25, 782.5, 818.75, 816.5, 824.8, 822.15, 815.6, 814.5, 809.4, 815.9,
    815.05, 815.35, 820.3, 815.55, 820.3, 813.7, 812.1, 803, 797.55, 812.6,
    824.3, 808.05, 808.65, 797.7, 811.65, 847.85, 862.65, 872.4, 872.8, 871.6,
    862.45, 848.5, 852, 863.9, 876.8, 889.35, 893.55, 880.7, 881.35, 859.7,
    856.7, 849, 861.3, 856.25, 859.75, 839.3, 839.95, 826.15, 841.95, 848.95,
    844, 845.35, 842.25, 832.7, 836.3, 843.75, 852.6, 844.9, 839.2, 843.9,
    839.1, 835.55, 831.8, 829.95, 816.95, 789.75, 775.2, 905.65, 830.35,
    825.85, 822.65, 831.15, 833.7, 828.6, 832.1, 818.75, 830.65, 821, 817.85,
    811.95, 820.3, 818.2, 808.8, 817.35, 819.8, 810.8, 801.9, 807.8, 831.45,
    830.05
];

    // Reverse to chronological order (oldest first)
    $chronologicalPrices = array_reverse($closingPrices);

    $rsiValues = calculateRSIWithClosingPrices($chronologicalPrices);



$rsiValues = [
    ['close' => 818.75, 'rsi' => 44.88],
    ['close' => 832.1, 'rsi' => 51.23],
    ['close' => 828.6, 'rsi' => 49.62],
    ['close' => 833.7, 'rsi' => 51.99],
    ['close' => 831.15, 'rsi' => 50.71],
    ['close' => 822.65, 'rsi' => 46.57],
    ['close' => 825.85, 'rsi' => 48.28],
    ['close' => 830.35, 'rsi' => 50.67],
    ['close' => 905.65, 'rsi' => 73.1], // Sell signal
    ['close' => 775.2, 'rsi' => 39.55], // Buy signal
    ['close' => 789.75, 'rsi' => 42.71],
];

// Initial capital
$capital = 100000;
$initialCapital = $capital;

// Dynamic holding days (you can change this value)
$maxHoldingDays = 10;

$inPosition = false;
$buyPrice = 0;
$quantity = 0;
$holdingDays = 0;
$totalProfit = 0;

foreach ($rsiValues as $index => $data) {
    $rsi = $data['rsi'];
    $price = $data['close'];

    if (!$inPosition && $rsi < 40) {
        // BUY
        $buyPrice = $price;
        $quantity = floor($capital / $buyPrice);
        $capital -= $quantity * $buyPrice;
        $inPosition = true;
        $holdingDays = 0;
        echo "Day $index: BUY at ₹$buyPrice, Qty: $quantity<br>";
    } elseif ($inPosition) {
        $holdingDays++;

        // SELL condition: RSI > 65 OR holding period exceeded
        if ($rsi > 65 || $holdingDays >= $maxHoldingDays) {
            $sellPrice = $price;
            $profit = ($sellPrice - $buyPrice) * $quantity;
            $capital += $quantity * $sellPrice;
            $totalProfit += $profit;
            echo "Day $index: SELL at ₹$sellPrice, Qty: $quantity, Holding: $holdingDays days, Profit: ₹" . round($profit, 2) . "<br>";
            $inPosition = false;
            $quantity = 0;
            $buyPrice = 0;
            $holdingDays = 0;
        }
    }
}

// Final forced exit if still holding
if ($inPosition) {
    $lastClose = end($rsiValues)['close'];
    $profit = ($lastClose - $buyPrice) * $quantity;
    $capital += $quantity * $lastClose;
    $totalProfit += $profit;
    echo "Final SELL at ₹$lastClose, Qty: $quantity, Holding: $holdingDays days, Profit: ₹" . round($profit, 2) . "<br>";
}

$returnPercent = ($totalProfit / $initialCapital) * 100;

echo "<br><strong>Total Profit:</strong> ₹" . round($totalProfit, 2);
echo "<br><strong>Return Percentage:</strong> " . round($returnPercent, 2) . "%";


    exit;
}

function calculateRSIWithClosingPrices($closingPrices, $period = 14) {
    if (count($closingPrices) < $period + 1) {
        return null; // Not enough data points
    }

    $result = [];
    $gains = [];
    $losses = [];

    // Calculate daily changes
    for ($i = 1; $i < count($closingPrices); $i++) {
        $change = $closingPrices[$i] - $closingPrices[$i - 1];
        $gains[] = max($change, 0);
        $losses[] = abs(min($change, 0));
    }

    // Initial averages (simple moving average)
    $avgGain = array_sum(array_slice($gains, 0, $period)) / $period;
    $avgLoss = array_sum(array_slice($losses, 0, $period)) / $period;

    // First RSI value
    $rs = ($avgLoss == 0) ? INF : ($avgGain / $avgLoss);
    $rsi = 100 - (100 / (1 + $rs));
    $result[] = [
        'close' => $closingPrices[$period],
        'rsi' => round($rsi, 2)
    ];

    // Subsequent values with Wilder's smoothing
    for ($i = $period; $i < count($gains); $i++) {
        $currentGain = $gains[$i];
        $currentLoss = $losses[$i];

        // Wilder's smoothing formula
        $avgGain = (($avgGain * ($period - 1)) + $currentGain) / $period;
        $avgLoss = (($avgLoss * ($period - 1)) + $currentLoss) / $period;

        $rs = ($avgLoss == 0) ? INF : ($avgGain / $avgLoss);
        $rsi = 100 - (100 / (1 + $rs));
        
        $result[] = [
            'close' => $closingPrices[$i + 1], // +1 because changes start from index 1
            'rsi' => round($rsi, 2)
        ];
    }

    return $result;
}

