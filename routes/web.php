<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\TrainController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PassengerProfileController; 
use App\Http\Controllers\PaymentController; 
use App\Http\Controllers\TicketController; 


Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route accessible by everyone (train.index)
Route::get('/train', [TrainController::class, 'index'])->name('train.index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/stations', [TrainController::class, 'getStations']);



Route::get('/passenger/{id}/info', [PassengerController::class, 'getInfo'])->name('passenger.info');
Route::get('/passenger/{id}/infoTrain', [PassengerController::class, 'getInfoTrain'])->name('passenger.infoTrain');
Route::get('passenger/train-info', [PassengerController::class, 'traininfo'])->name('passenger.traininfo');
Route::get('autocomplete', [PassengerController::class, 'autocomplete'])->name('autocomplete');
Route::get('passenger/search', [PassengerController::class, 'search'])->name('passenger.search');
Route::get('passenger/searchForm', [PassengerController::class, 'searchForm'])->name('passenger.searchForm');


require __DIR__.'/auth.php';

require __DIR__.'/admin-auth.php';
