<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;    
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\MenberController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\AttendanceController;

// Logout route
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login')->with('success', 'You have been logged out.');
})->name('logout');


Route::view('/', 'Website');
Route::view('/login', 'auth.login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/checkLogin', [AuthController::class, 'checkLogin']);



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans/store', [PlanController::class, 'store'])->name('plans.store');
    Route::post('/plans/update', [PlanController::class, 'update'])->name('plans.update');
    Route::get('/plans/fetch', [PlanController::class, 'fetchPlans'])->name('plans.fetch');



    Route::get('/menbers', [MenberController::class, 'index'])->name('menbers.index');
    Route::post('/menbers/store', [MenberController::class, 'store'])->name('menbers.store');
    Route::post('/menbers/update', [MenberController::class, 'update'])->name('menbers.update');
    Route::get('/menbers/fetch', [MenberController::class, 'fetchMenbers'])->name('menbers.fetch');


    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/take', [AttendanceController::class, 'takeAttendance'])->name('attendance.take');
    Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
});

