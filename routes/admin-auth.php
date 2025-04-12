<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('admin.register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AdminLoginController::class, 'create'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('admin.password.request'); 

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('admin.password.email'); 

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('admin.password.reset'); 

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('admin.password.store'); 
});


Route::prefix('admin')->middleware('auth:admin')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::get('/dashboard',[TrainController::class, 'showtrain'])->name('admin.dashboard');
    
    Route::get('/profiles', function () {
        return view('admin.profiles');
    })->name('admin.profiles');

    Route::get('/profiles/edit', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/profiles/edit', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/profiles/edit', [AdminProfileController::class, 'destroy'])->name('admin.profile.destroy');
    

    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('admin.verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('admin.verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('admin.verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('admin.password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('admin.password.update');

    Route::post('logout', [AdminLoginController::class, 'destroy'])
        ->name('admin.logout');

    // Train Routes (Admin Only)
    Route::get('/stations', [TrainController::class, 'getStations']);
    Route::resource('station', StationController::class);

    Route::get('/train/create', [TrainController::class, 'create'])->name('train.create');
    Route::post('/train', [TrainController::class, 'store'])->name('train.store');
    Route::get('/train/edit', [TrainController::class, 'showEditPage'])->name('train.edit.page');
    Route::post('/train/edit', [TrainController::class, 'loadTrainData'])->name('train.load');
    Route::get('/train/edit/{trainid}', [TrainController::class, 'edit'])->name('train.edit');
    Route::put('/train/update/{trainid}', [TrainController::class, 'update'])->name('train.update');
    Route::delete('/train/{train}', [TrainController::class, 'destroy'])->name('train.destroy');
    Route::get('/train/show', [TrainController::class, 'show'])->name('train.show');
    Route::get('/train/data/{id}', [TrainController::class, 'viewTrainData'])->name('train.data');
    Route::get('/train/{id}/download-pdf', [PdfController::class, 'downloadTrainPdf'])
    ->middleware('auth:admin')
    ->name('train.pdf');

    Route::get('/pdf/train/{id}', [PdfController::class, 'generatePdf'])->name('pdf.generate');
});
