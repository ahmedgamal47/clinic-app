<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\UserController;

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
    Route::resource('users', UserController::class);
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Patient routes
    Route::resource('patients', PatientController::class);

    // Session routes (nested under patients)
    Route::resource('patients.sessions', SessionController::class)->parameters([
        'sessions' => 'session'
    ]);

    // Medication routes
    Route::resource('medications', MedicationController::class);
});

require __DIR__.'/auth.php';
