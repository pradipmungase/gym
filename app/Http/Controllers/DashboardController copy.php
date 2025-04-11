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

class DashboardController extends Controller{
    
    public function qrCode()
    {
        $qrText = 'Final output by Pradip Mungase';
        $fileName = 'qr_' . time() . '.png';
        $filePath = public_path('qr-codes/' . $fileName);

        // Make sure the folder exists
        if (!File::exists(public_path('qr-codes'))) {
            File::makeDirectory(public_path('qr-codes'), 0755, true);
        }

        // Build and save QR code
        $result = Builder::create()
            ->data($qrText)
            ->size(300)
            ->margin(10)
            ->build();

        file_put_contents($filePath, $result->getString());

        return response()->json([
            'message' => 'QR Code created successfully!',
            'url' => asset('qr-codes/' . $fileName)
        ]);
    }

    public function checkAccess()
    {


        $distance = $this->haversineGreatCircleDistance(
            19.8705152, 75.3270784,
            19.8705152, 75.32
        );

        // Check if user is within 100 meters (0.1 km)
        if ($distance <= 0.1) {
            return response('Access granted. You are at the show location.', 200);
        }

        return response('Access denied. You are not at the show location.', 403);
    }
    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
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
}
