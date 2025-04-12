<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;    
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\MenberController;
use App\Http\Controllers\PlanController;

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
    Route::get('/menberIndex', [MenberController::class, 'menberIndex'])->name('menberIndex');
    Route::post('/plans/store', [PlanController::class, 'store'])->name('plans.store');
    Route::post('/plans/update', [PlanController::class, 'update'])->name('plans.update');
    Route::get('/plans/fetch', [PlanController::class, 'fetchPlans'])->name('plans.fetch');
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
});

