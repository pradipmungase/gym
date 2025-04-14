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
use App\Http\Controllers\TrainerController;

// Logout route
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login')->with('success', 'You have been logged out.');
})->name('logout');


Route::view('/', 'Website');
Route::view('/login', 'auth.login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/checkLogin', [AuthController::class, 'checkLogin']);

Route::match(['get', 'post'], '/markAttendanceByLatLong/{gym_id}/{member_id}', [AttendanceController::class, 'markAttendanceByLatLong'])->name('members.markAttendanceByLatLong');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans/store', [PlanController::class, 'store'])->name('plans.store');
    Route::post('/plans/update', [PlanController::class, 'update'])->name('plans.update');
    Route::get('/plans/fetch', [PlanController::class, 'fetchPlans'])->name('plans.fetch');
    Route::get('/plans/view/{id}', [PlanController::class, 'view'])->name('plans.view');


    Route::get('/menbers', [MenberController::class, 'index'])->name('menbers.index');
    Route::post('/menbers/store', [MenberController::class, 'store'])->name('menbers.store');
    Route::post('/menbers/update', [MenberController::class, 'update'])->name('menbers.update');
    Route::get('/menbers/fetch', [MenberController::class, 'fetchMenbers'])->name('menbers.fetch');
    Route::get('/menbers/view/{id}', [MenberController::class, 'view'])->name('menbers.view');  



    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/take', [AttendanceController::class, 'takeAttendance'])->name('attendance.take');
    Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
    Route::get('/attendance/fetch', [AttendanceController::class, 'fetchAttendance'])->name('attendance.fetch');


    Route::get('/trainer', [TrainerController::class, 'index'])->name('trainer.index');
    Route::post('/trainer/store', [TrainerController::class, 'store'])->name('trainer.store');
    Route::post('/trainer/update', [TrainerController::class, 'update'])->name('trainer.update');
    Route::get('/trainer/fetch', [TrainerController::class, 'fetchTrainers'])->name('trainer.fetch');
    Route::get('/trainer/view/{id}', [TrainerController::class, 'view'])->name('trainer.view');
});

