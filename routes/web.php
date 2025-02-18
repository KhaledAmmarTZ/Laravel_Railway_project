<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route accessible by everyone (train.index)
Route::get('/train', [TrainController::class, 'index'])->name('train.index');




require __DIR__.'/auth.php';

require __DIR__.'/admin-auth.php';
