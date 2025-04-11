<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApisController\ApiAuthController;
use Illuminate\Support\Facades\Response;


Route::post('/customerRegistration', [ApiAuthController::class, 'customerRegistration']);
Route::post('/counsellorRegistration', [ApiAuthController::class, 'counsellorRegistration']);
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/checkOTP', [ApiAuthController::class, 'checkOTP']);
    Route::get('/getUserName', [ApiAuthController::class, 'getUserName']);
    Route::post('/checkUniqueUserName', [ApiAuthController::class, 'checkUniqueUserName']);
    Route::post('/saveUserName', [ApiAuthController::class, 'saveUserName']);
    Route::post('/saveProfilePicture', [ApiAuthController::class, 'saveProfilePicture']);
    Route::get('/reSentOTP', [ApiAuthController::class, 'reSentOTP']);

    Route::get('/removeProfilePicture', [ApiAuthController::class, 'removeProfilePicture']);
    Route::post('/saveCounsellorProfessionalDetails', [ApiAuthController::class, 'saveCounsellorProfessionalDetails']);
    Route::get('/deleteProfile', [ApiAuthController::class, 'deleteProfile']);
    Route::get('/getUserDetails', [ApiAuthController::class, 'getUserDetails']);
    Route::post('/updateFullNameAndUserName', [ApiAuthController::class, 'updateFullNameAndUserName']);
    Route::post('/updateCounsellorProfessionalDetails', [ApiAuthController::class, 'updateCounsellorProfessionalDetails']);
});

