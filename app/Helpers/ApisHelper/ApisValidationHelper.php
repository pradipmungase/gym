<?php

namespace App\Helpers\ApisHelper;

use Illuminate\Http\Request;

class ApisValidationHelper
{
    static function validatecustomerRegistration(Request $request)
    {
        return $request->validate([
            'full_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email:rfc,dns|max:255|lowercase',
        ], [
            'full_name.regex' => 'Full name should only contain letters and spaces',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'Email already exists',   
        ]);
    }

    static function validatecounsellorRegistration(Request $request)
    {
        return $request->validate([
            'full_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email:rfc,dns|max:255|lowercase',
            'mobile_no' => 'required|string|max:10|regex:/^[0-9]+$/',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'full_name.regex' => 'Full name should only contain letters and spaces',
            'email.email' => 'Please provide a valid email address',
            'mobile_no.regex' => 'Phone number should only contain numbers',
            'mobile_no.max' => 'Phone number should be 10 digits',
            'profile_picture.max' => 'Profile picture should be less than 2MB',
        ]);
    }

    static function validateCounsellorProfessionalDetails(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'year_of_experience' => 'required|string|max:255',
            'bio' => 'nullable|string|max:255',
            'specialization' => 'required|string|max:255',
            'certification_and_license' => 'required|string|max:255',
            'language_spoken' => 'required|string|max:255',
            'session_price' => 'required|numeric|min:0',
        ]);
    }

    static function updateCounsellorProfessionalDetails(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'year_of_experience' => 'required|string|max:255',
            'bio' => 'nullable|string|max:255',
            'specialization' => 'required|string|max:255',
            'certification_and_license' => 'required|string|max:255',
            'language_spoken' => 'required|string|max:255',
            'session_price' => 'required|numeric|min:0',
        ]);
    }
}