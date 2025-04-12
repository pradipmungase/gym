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
    
    public function index()
    {
        $plans = DB::table('menbership_plans')->where('gym_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('admin.menbers.index', compact('plans')); // just loads view with empty or initial content
    }

    public function fetchMenbers(Request $request)
    {
        $menbers = DB::table('members')->where('gym_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.menbers.partials.menber-table', compact('menbers'))->render(); // returns only table partial
    }

    public function update(Request $request)
    {
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'duration'  => 'required|string', // adjust type as per your DB schema
            'price'     => 'required|numeric|min:1',
            'plan_id'   => 'required|exists:menbership_plans,id',
        ]);

        DB::table('menbership_plans')->where('id', $request->input('plan_id'))->update([
            'name' => $request->input('plan_name'),
            'duration'  => $request->input('duration'),
            'price'     => $request->input('price'),    
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Plan updated successfully']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'mobile' => 'required|digits:10|unique:members,mobile',
            'joining_date' => 'required|date',
            'gender' => 'required|string',
            'age' => 'required|integer',
            'plan' => 'required|exists:menbership_plans,id',
            'discount_type' => 'required|string',
            'discount' => 'required|numeric',
            'final_price' => 'required|numeric|min:1',
            'plan_price' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();

        try {
            $insertId = DB::table('members')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'joining_date' => $request->joining_date,
                'gender' => $request->gender,
                'age' => $request->age,
                'plan_id' => $request->plan,
                'discount_type' => $request->discount_type,
                'gym_id' => Auth::user()->id,
                'status' => 'Active',
                'discount_amount' => $request->discount,
                'final_price' => $request->final_price,
                'plan_price' => $request->plan_price,
            ]);

            // Generate QR code and update the path
            $qrCodePath = $this->generateQRCode($insertId);
            DB::table('members')->where('id', $insertId)->update([
                'qr_code_path' => $qrCodePath,
            ]);

            // Send WhatsApp message
            $this->sendWhatsAppMessage($request->mobile, $request->name, $qrCodePath);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Member added successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to add member. Please try again.', 'error' => $e->getMessage()]);
        }
    }

    private function generateQRCode($memberId)
    {
        $fileName = $memberId . '_' . time() . '.png';
        $userFolderPath = public_path('QRCodesImages/' . $memberId);

        if (!File::exists($userFolderPath)) {
            File::makeDirectory($userFolderPath, 0755, true);
        }

        $filePath = $userFolderPath . '/' . $fileName;

        $qrText = $memberId;
        $result = Builder::create()
            ->data($qrText)
            ->size(300)
            ->margin(10)
            ->build();

        file_put_contents($filePath, $result->getString());

        return $filePath;
    }

    private function sendWhatsAppMessage($mobile, $name, $imagePath)
    {
        $mobile = '7028143227';
        $gymName = Auth::user()->gym_name;
        $base64Image = base64_encode(file_get_contents($imagePath));

        // Prepare your custom message
        $message = "👋 Hello $name,\n\nWelcome to *$gymName*! 🏋️‍♂️\nHere is your QR Code for daily attendance. 📲\n\nMake sure to scan it every day when you visit! ✅";

        // Send the message via WhatsApp gateway (e.g. using a local server or 3rd party API)
        Http::post('http://localhost:3000/send-message', [
            'number' => '91' . $mobile,
            'message' => $message,
            'image' => $base64Image,
        ]);
    }


}
