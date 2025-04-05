<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\StationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('routes', RouteController::class);
Route::get('/routes/{route}/edit', [RouteController::class, 'edit'])->name('routes.edit');
Route::put('/routes/{route}', [RouteController::class, 'update'])->name('routes.update');

Route::get('/stations', [TrainController::class, 'getStations']);
Route::get('/train_route',function (){
    return view('train.train_route');
});
Route::get('/trains', function () {
    $trains = \App\Models\Train::all(['trainname']);  // Fetch only the train name
    return response()->json($trains);
});

Route::get('/user', [UserController::class, 'showProfile']);



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
