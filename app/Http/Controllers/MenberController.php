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

class MenberController extends Controller{
    
    public function menberIndex()
    {
        return view('admin.menber.index');
    }

    public function addMenber()
    {
        return view('admin.menber.add');
    }

    public function addMenberPOST(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'joining_date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            // Insert user and get ID
            $insertId = DB::table('users')->insertGetId([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'joining_date' => $request->joining_date,
            ]);

            // Set QR code filename
            $fileName = $insertId . '_' . time() . '.png';

            // Create user-specific folder
            $userFolderPath = public_path('QRCodesImages/' . $insertId);
            if (!File::exists($userFolderPath)) {
                File::makeDirectory($userFolderPath, 0755, true);
            }

            // Set full file path inside user-specific folder
            $filePath = $userFolderPath . '/' . $fileName;

            // Build and save QR code
            $qrText = $insertId;
            $result = Builder::create()
                ->data($qrText)
                ->size(300)
                ->margin(10)
                ->build();

            file_put_contents($filePath, $result->getString());

            // Send image as base64
            $base64Image = base64_encode(file_get_contents($filePath));
            $response = Http::post('http://localhost:3000/send-message', [
                'number' => '91' . $request->mobile,
                'message' => 'Hello ' . $request->first_name . ' ' . $request->last_name . ', here is your image!',
                'image' => $base64Image
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Member added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add member. Please try again.');
        }
    }

}
