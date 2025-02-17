<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\SuperAdminAuthController;

// Home Route
Route::get('/', function () {
    return view('welcome');
});

// Route accessible by everyone (train.index)
Route::get('/train', [TrainController::class, 'index'])->name('train.index');

// Auth Routes
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Super Admin Registration Routes
Route::get('/superadmin/register', [SuperAdminAuthController::class, 'showRegisterForm'])->name('superadmin.register');
Route::post('/superadmin/register', [SuperAdminAuthController::class, 'register'])->name('superadmin.register.submit');

// Super Admin Login Routes
Route::get('/superadmin/login', [SuperAdminAuthController::class, 'showLoginForm'])->name('superadmin.login');
Route::post('/superadmin/login', [SuperAdminAuthController::class, 'login'])->name('superadmin.login.submit');

// Super Admin Logout Route (Only in the public section)
Route::post('superadmin/logout', [SuperAdminAuthController::class, 'logout'])->name('superadmin.logout');

// Super Admin Protected Routes (Only accessible when logged in)
Route::middleware(['auth:superadmin'])->group(function () {
    Route::get('/superadmin/profile', [SuperAdminAuthController::class, 'profile'])->name('superadmin.profile');
    Route::get('/train/create', [TrainController::class, 'create'])->name('train.create');
    Route::post('/train', [TrainController::class, 'store'])->name('train.store');
    Route::get('/train/edit', [TrainController::class, 'showEditPage'])->name('train.edit.page');
    Route::post('/train/edit', [TrainController::class, 'loadTrainData'])->name('train.load');
    Route::get('/train/{train}/edit', [TrainController::class, 'edit'])->name('train.edit');
    Route::put('/train/{train}', [TrainController::class, 'update'])->name('train.update');
    Route::delete('/train/{train}', [TrainController::class, 'destroy'])->name('train.destroy');
    Route::get('/train/show', [TrainController::class, 'show'])->name('train.show');
});
