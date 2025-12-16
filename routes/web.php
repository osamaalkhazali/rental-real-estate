<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\LeasePaymentController;
use App\Http\Controllers\WaterServiceController;
use App\Http\Controllers\WaterReadingController;
use App\Http\Controllers\ElectricServiceController;
use App\Http\Controllers\ElectricReadingController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\Reports\ApartmentRoiController;
use App\Http\Controllers\PrivateFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Apartments
    Route::resource('apartments', ApartmentController::class);

    // Leases
    Route::resource('leases', LeaseController::class);

    // Lease Payments
    Route::resource('lease-payments', LeasePaymentController::class);

    // Water Services
    Route::resource('water-services', WaterServiceController::class);

    // Water Readings
    Route::resource('water-readings', WaterReadingController::class);

    // Electric Services
    Route::resource('electric-services', ElectricServiceController::class);

    // Electric Readings
    Route::resource('electric-readings', ElectricReadingController::class);

    // Expenses
    Route::resource('expenses', ExpenseController::class);

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/apartments-roi', [ApartmentRoiController::class, 'index'])->name('apartments.roi');
    });
    // Private file serving
    Route::get('/files/{path}', [PrivateFileController::class, 'show'])
        ->where('path', '.*')
        ->name('private.file');
    Route::get('/files/view/{path}', [PrivateFileController::class, 'stream'])
        ->where('path', '.*')
        ->name('private.file.view');
});

require __DIR__.'/auth.php';
