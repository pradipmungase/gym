<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;    
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AnnouncementController;
use App\Models\User;
use App\Notifications\WebPushNotification;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MemberRegistrationController;

Route::post('/webpush', function (Request $request) {
    $user = Auth::user(); // Or get authenticated user
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }
    $user->updatePushSubscription(
        $request->input('endpoint'),
        $request->input('keys.p256dh'),
        $request->input('keys.auth')
    );
    return response()->json(['success' => true]);
});


// Logout route
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login')->with('success', 'You have been logged out.');
})->name('logout');


Route::view('/', 'Website');
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
});
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/checkLogin', [AuthController::class, 'checkLogin'])->name('checkLogin');
Route::view('/forgotPassword', 'auth.forgotPassword');
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::get('/resetPassword/{token}', [AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('/resetPassword', [AuthController::class, 'finalResetPassword'])->name('finalResetPassword');
Route::post('/resendOtp', [AuthController::class, 'resendOtp'])->name('resendOtp');
Route::post('/verifyOtp', [AuthController::class, 'verifyOtp'])->name('verifyOtp');
Route::match(['get', 'post'], '/markAttendanceByLatLong/{gym_id}/{member_id}', [AttendanceController::class, 'markAttendanceByLatLong'])->name('members.markAttendanceByLatLong');

Route::match(['get', 'post'], '/marketing', [MarketingController::class, 'index'])->name('marketing.index');
Route::match(['get'], '/genrateNotifications', [NotificationController::class, 'index'])->name('genrateNotifications');

Route::get('/memberRegistration/{id}', [MemberRegistrationController::class, 'index'])->name('memberRegistration');
Route::post('/memberRegistration/store/{id}', [MemberRegistrationController::class, 'store'])->name('memberRegistration.store');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/saveLatitudeAndLongitude', [DashboardController::class, 'saveLatitudeAndLongitude'])->name('saveLatitudeAndLongitude');

    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans/store', [PlanController::class, 'store'])->name('plans.store');
    Route::post('/plans/update', [PlanController::class, 'update'])->name('plans.update');
    Route::get('/plans/fetch', [PlanController::class, 'fetchPlans'])->name('plans.fetch');
    Route::get('/plans/view/{id}', [PlanController::class, 'view'])->name('plans.view');


    Route::get('/members', [MembersController::class, 'index'])->name('members.index');
    Route::post('/members/store', [MembersController::class, 'store'])->name('members.store');
    Route::post('/members/update', [MembersController::class, 'update'])->name('members.update');
    Route::get('/members/fetch', [MembersController::class, 'fetchMembers'])->name('members.fetch');
    Route::get('/members/view/{id}', [MembersController::class, 'view'])->name('members.view');  



    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/take', [AttendanceController::class, 'takeAttendance'])->name('attendance.take');
    Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
    Route::get('/attendance/fetch', [AttendanceController::class, 'fetchAttendance'])->name('attendance.fetch');


    Route::get('/trainer', [TrainerController::class, 'index'])->name('trainer.index');
    Route::post('/trainer/store', [TrainerController::class, 'store'])->name('trainer.store');
    Route::post('/trainer/update', [TrainerController::class, 'update'])->name('trainer.update');
    Route::get('/trainer/fetch', [TrainerController::class, 'fetchTrainers'])->name('trainer.fetch');
    Route::get('/trainer/view/{id}', [TrainerController::class, 'view'])->name('trainer.view');

    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses/store', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::post('/expenses/update', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::get('/expenses/fetch', [ExpenseController::class, 'fetchExpenses'])->name('expenses.fetch');
    Route::get('/expenses/view/{id}', [ExpenseController::class, 'view'])->name('expenses.view');

    Route::get('/announcement', [AnnouncementController::class, 'index'])->name('announcement.index');
    Route::post('/announcement/store', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::post('/announcement/update', [AnnouncementController::class, 'update'])->name('announcement.update');
    Route::get('/announcement/fetch', [AnnouncementController::class, 'fetchAnnouncement'])->name('announcement.fetch');
    Route::get('/announcement/view/{id}', [AnnouncementController::class, 'view'])->name('announcement.view');

    Route::view('/support', 'admin.support');
    Route::view('/permissions', 'admin.permissions');
    Route::view('/profile', 'admin.profile');
    Route::post('/updateProfilePicture', [DashboardController   ::class, 'updateProfilePicture'])->name('updateProfilePicture');
    Route::post('/search', [DashboardController::class, 'search'])->name('search');
    Route::delete('/members/delete/{id}', [MembersController::class, 'delete'])->name('members.delete');
    Route::post('/members/addPayment', [MembersController::class, 'addPayment'])->name('members.addPayment');

    Route::post('/members/updateStatus', [MembersController::class, 'updateStatus'])->name('members.updateStatus');
    Route::post('/members/addNote', [MembersController::class, 'addNote'])->name('members.addNote');
    Route::post('/members/changePlan', [MembersController::class, 'changePlan'])->name('members.changePlan');
    Route::post('/members/renewMembership', [MembersController::class, 'renewMembership'])->name('members.renewMembership');


    Route::get('/memberRequest', [MemberRegistrationController::class, 'memberRequest'])->name('memberRequest');
    Route::get('/memberRequest/fetch', [MemberRegistrationController::class, 'fetch'])->name('memberRequest.fetch');
    Route::post('/memberRequest/reject/{id}', [MemberRegistrationController::class, 'reject'])->name('memberRequest.reject');

    Route::get('/gymQRCode', [DashboardController::class, 'gymQRCode'])->name('gymQRCode');
    Route::post('/requestFeature/store', [DashboardController::class, 'storeRequestFeature'])->name('requestFeature.store');
    Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/change-password', [DashboardController::class, 'changePassword'])->name('profile.changePassword');
    Route::delete('/profile/delete-account', [DashboardController::class, 'deleteAccount'])->name('profile.deleteAccount');

});


