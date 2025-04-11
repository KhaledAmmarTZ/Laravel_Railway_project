<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {

    Route::get('passenger.index', function () {
        return view('passenger.index');
    })->name('passenger.index');
    
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
        
    // passenger
    Route::get('/passenger/{id}/info', [PassengerController::class, 'getInfo'])->name('passenger.info');
Route::get('/passenger/{id}/infoTrain', [PassengerController::class, 'getInfoTrain'])->name('passenger.infoTrain');
Route::get('passenger/train-info', [PassengerController::class, 'traininfo'])->name('passenger.traininfo');
Route::get('passenger/traininfosubmission', [PassengerController::class, 'traininfosubmission'])->name('passenger.traininfosubmission');
Route::get('autocomplete', [PassengerController::class, 'autocomplete'])->name('autocomplete');
Route::get('passenger/search', [PassengerController::class, 'search'])->name('passenger.search');
Route::get('passenger/searchForm', [PassengerController::class, 'searchForm'])->name('passenger.searchForm');

    
    Route::get('passenger/availableCreate', [PassengerController::class, 'availableCreate'])->name('passenger.availableCreate');
    Route::post('passenger/availableCreate', [PassengerController::class, 'availableStore'])->name('passenger.availableStore');
    Route::resource('passenger', PassengerController::class);
    Route::match(['get', 'post'], 'payment/{pnr}/index', [PaymentController::class, 'showPaymentPage'])->name('payment.index');
    Route::post('payment', [PaymentController::class, 'charge'])->name('payment.charge');
    Route::get('/ticket/index', [TicketController::class, 'index'])->name('ticket.index');
    Route::get('/ticket/{pnr}', [TicketController::class, 'show'])->name('ticket.show');
    Route::get('/ticket/{pnr}/download', [TicketController::class, 'download'])->name('ticket.download');
    Route::patch('/ticket/{pnr}/cancel', [TicketController::class, 'cancel'])->name('ticket.cancel');
    Route::match(['get', 'post'], 'payment/{pnr}/index', [PaymentController::class, 'showPaymentPage'])->name('payment.index');
    Route::match(['get', 'post'], 'payment', [PaymentController::class, 'charge'])->name('payment.charge');
    Route::post('/cancel-booking', [PassengerController::class, 'cancelBooking'])->name('cancel.booking');
});
