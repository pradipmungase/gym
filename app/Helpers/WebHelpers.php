<?php
use Illuminate\Support\Carbon;


function sendWhatsAppMessageForAttendanceMarked($member)
{
    $mobile = '7028143227';

    $gymName = Auth::user()->gym_name;
    $formattedDate = Carbon::now()->format('d M Y'); // e.g., 14 Apr 2025
    $formattedTime = Carbon::now()->format('h:i A'); // e.g., 03:45 PM

    $message = "👋 Hello $member->name,\n\nYour attendance has been *marked successfully* for today 📅 *$formattedDate* at 🕒 *$formattedTime*.\n\nKeep up the great work at *$gymName*! 💪\n\nSee you tomorrow! 😊";

    return Http::post('http://localhost:3000/send-message', [
        'number' => '91' . $mobile,
        'message' => $message,
    ]);
}


function sendWhatsAppMessageForMenberRegistration($mobile, $name, $imagePath)
{
    $mobile = '7028143227';
    $gymName = Auth::user()->gym_name;
    $base64Image = base64_encode(file_get_contents($imagePath));

    // Prepare your custom message
    $message = "👋 Hello $name,\n\nWelcome to *$gymName*! 🏋️‍♂️\nHere is your QR Code for daily attendance. 📲\n\nMake sure to scan it every day when you visit! ✅";

    // Send the message via WhatsApp gateway (e.g. using a local server or 3rd party API)
    return Http::post('http://localhost:3000/send-message', [
        'number' => '91' . $mobile,
        'message' => $message,
        'image' => $base64Image,
    ]);
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