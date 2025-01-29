<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainController;

// Home Route
Route::get('/', function () {
    return view('welcome');
});

// Routes for Train Management
Route::get('/train/create', [TrainController::class, 'create'])->name('train.create');
Route::post('/train', [TrainController::class, 'store'])->name('train.store');
Route::get('/train', [TrainController::class, 'index'])->name('train.index');

// Show the page with all trains for editing
Route::get('/train/edit', [TrainController::class, 'showEditPage'])->name('train.edit.page');

// Load train data to edit form based on the selected train
Route::post('/train/edit', [TrainController::class, 'loadTrainData'])->name('train.load'); 

// Route to edit a specific train by ID
Route::get('/train/{id}/edit', [TrainController::class, 'edit'])->name('train.edit');

// Route to update the specific train by ID
Route::put('/train/{id}', [TrainController::class, 'update'])->name('train.update'); 

// Route to delete the specific train by ID
Route::delete('/train/{id}', [TrainController::class, 'destroy'])->name('train.destroy');

// Additional Route for Home (could be a dashboard or welcome page)
Route::get('/home', function () {
    return view('home');
});
