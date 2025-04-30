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

class AnnouncementController extends Controller{
    
    public function index()
    {
        return view('admin.announcement.index'); // just loads view with empty or initial content
    }

    public function fetchAnnouncement(Request $request)
    {
        $announcements = DB::table('announcements')->where('gym_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.announcement.partials.announcement-table', compact('announcements'))->render(); // returns only table partial
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description'  => 'required|string|max:255',
            'date'     => 'required|date',
            'for'     => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            DB::table('announcements')->insert([
                'title' => $request->input('title'),
                'description'  => $request->input('description'),
                'date'     => $request->input('date'),
                'for'     => $request->input('for'),
                'gym_id'    => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            sendAnnouncement(
                $request->input('for'),
                $request->input('title'),
                $request->input('description'),
                $request->input('date')
            );

            DB::commit();

            return response()->json(['message' => 'Announcement added successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing announcement: '.$e->getMessage());

            return response()->json(['error' => 'Something went wrong. Please try again later.'], 500);
        }
    }


    public function update(Request $request)
    {
        $request->validate([
            'editTitle' => 'required|string|max:255',
            'editDescription'  => 'required|string|max:255',
            'editDate'     => 'required|date',
            'editFor'   => 'required|string|max:255',
            'announcement_id'   => 'required|exists:announcement,id',
        ]);

        DB::table('announcements')->where('id', $request->input('announcement_id'))->update([
            'title' => $request->input('editTitle'),
            'description'  => $request->input('editDescription'),
            'date'     => $request->input('editDate'),    
            'for'   => $request->input('editFor'),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Announcement updated successfully']);
    }

    public function view($id)
    {
        $id = decrypt($id);
        $announcement = DB::table('announcements')->where('id', $id)->first();
        return view('admin.announcement.view', compact('announcement'));
    }
}
