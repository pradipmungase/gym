<?php

namespace App\Helpers\ApisHelper;

use App\Services\BrevoService;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;    


class ApisHelper
{
    static function customerRegistrationSendOTP($user, $otp)
    {
        // Prepare email content
        $subject = "Your OTP Code";
        $mailContent = "<p>Your OTP code is:</p><p><strong>{$otp}</strong></p>";

        // Load and process email template
        $templatePath = resource_path('views/emails/defaultMailTemplate.html');
        $content = file_exists($templatePath) ? file_get_contents($templatePath) : $mailContent;
        $content = str_replace('{{ subject }}', $subject, $content);
        $content = str_replace('{{ mailContent }}', $mailContent, $content);

        // Send email via Brevo service
        $brevoService = new BrevoService();
        $res = $brevoService->sendEmail($user->email, $user->first_name, $subject, $content);
        if (!$res) {
            throw new \Exception("Failed to send OTP email");
        }
    }

    static function generateUniqueUsernames($firstName) {
        $suggestedUsernames = [];
        $attempts = 0;

        // Remove special characters and spaces, keeping only letters and numbers
        $cleanFirstName = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($firstName));

        while (count($suggestedUsernames) < 5 && $attempts < 15) {
            $username = $cleanFirstName . rand(100, 999);
            
            if (!User::where('username', $username)->exists()) {
                $suggestedUsernames[] = $username;
            }
            
            $attempts++;
        }

        return $suggestedUsernames;
    }

    public static function uploadImage(UploadedFile $image, $folder)
    {
        $userId = Auth::id(); // Getting the authenticated user's ID
        $destinationPath = public_path($folder . '/' . $userId);

        // Ensure the directory exists
        if (!File::exists($destinationPath)) {
            if (!File::makeDirectory($destinationPath, 0777, true, true)) {
                throw new Exception('Failed to create directory.');
            }
        }

        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move($destinationPath, $imageName);

        return $folder . '/' . $userId . '/' . $imageName; // Returning the image path
    }

}