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
use Illuminate\Support\Facades\Validator;

class MarketingController extends Controller{
    
    public function index(Request $request)
    {
        if($request->isMethod('post')){
            $validator = Validator::make($request->all(), [
                'whatsappNumber' => 'required|digits:10|unique:marketing,whatsappNumber',
            ]);
            if ($validator->fails()) {
                return redirect()->route('marketing.index')->with('error', 'Whatsapp number already exists');
            }
            DB::beginTransaction();
            // Extract input data
            $string = $request->scoreCode;
            $start = '<script id="__NEXT_DATA__" type="application/json">';
            $end = 'scriptLoader":[]}';
            $mid = 'scriptLoader":[]}';
            // Find the positions of the start and end markers
            $startPos = strpos($string, $start);
            $endPos = strpos($string, $end);
            if ($startPos === false || $endPos === false) {
                $start = '<script id="__NEXT_DATA__" type="application/json">';
                $end = '"}}]]}';
                $mid = '"}}]]}';
                $type = 2;
                $startPos = strpos($string, $start);
                $endPos = strpos($string, $end);
                if ($startPos === false || $endPos === false) {
                    throw new \Exception("Start or end string not found in the input!");
                }
            }

            // Extract and decode JSON data
            $startPos += strlen($start);
            $jsonstr = substr($string, $startPos, $endPos - $startPos) . $mid;
            $data = json_decode($jsonstr, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("JSON decode error: " . json_last_error_msg());
            }

        
            $mobile = $data['props']['pageProps']['cmpinfoData']['mobile'] ?? $data['props']['pageProps']['cmpinfoData']['VNumber'] ?? null;
            $whatsappNumber = $request->whatsappNumber;

            $outputdata = [
                'name' => !empty($data['props']['pageProps']['results']['results']['name']) ? $data['props']['pageProps']['results']['results']['name'] : null,
                'pincode' => !empty($data['props']['pageProps']['results']['results']['pincode']) ? $data['props']['pageProps']['results']['results']['pincode'] : null,
                'address' => !empty($data['props']['pageProps']['results']['results']['address']) ? $data['props']['pageProps']['results']['results']['address'] : null,
                'owner_name' => !empty($data['props']['pageProps']['results']['results']['contactperson']) ? $data['props']['pageProps']['results']['results']['contactperson'] : null,
                'mobile' => $mobile,
                'email' => !empty($data['props']['pageProps']['results']['results']['email']) ? $data['props']['pageProps']['results']['results']['email'] : null,
                'whatsappNumber' => $whatsappNumber
            ];
            DB::table('marketing')->insert($outputdata);
            $response = sendMarketingWhatsapp($whatsappNumber, $outputdata);
            if($response->successful()){
                DB::commit();
                return redirect()->route('marketing.index')->with('success', 'Data inserted successfully');
            }else{
                DB::rollBack();
                return redirect()->route('marketing.index')->with('error', 'Failed to send WhatsApp message');
            }
        }else{
            return view('admin.marketing.index'); // just loads view with empty or initial content
        }
    }

}
