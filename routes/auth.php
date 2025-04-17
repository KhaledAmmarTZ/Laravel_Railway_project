<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\PassengerProfileController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;

use App\Http\Controllers\StationController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Actions\Logout;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login')
        ->name('login');

    Volt::route('register', 'auth.register')
        ->name('register');

    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');

});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
});
Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::get('profile', [PassengerProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/{id}/edit', [PassengerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/{id}', [PassengerProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/upload', [PassengerProfileController::class, 'upload'])->name('profile.upload');
    Route::get('passenger/availableCreate', [PassengerController::class, 'availableCreate'])->name('passenger.availableCreate');
    Route::post('passenger/availableCreate', [PassengerController::class, 'availableStore'])->name('passenger.availableStore');
    Route::get('passenger/traininfosubmission', [PassengerController::class, 'traininfosubmission'])->name('passenger.traininfosubmission');
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
Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
