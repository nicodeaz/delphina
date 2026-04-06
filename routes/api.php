<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API Routes (no authentication required)
Route::get('appointments/available', [AppointmentController::class, 'availableSlots'])->name('appointments.available');
Route::get('appointments/next-available-dates', [AppointmentController::class, 'nextAvailableDates'])->name('appointments.next-available-dates');
Route::apiResource('services', ServiceController::class)->only(['index', 'show']);

// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Appointments
    Route::apiResource('appointments', AppointmentController::class)->except(['update']);
    Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);

    // Admin routes
    Route::middleware('api.admin')->prefix('admin')->group(function () {
        Route::get('appointments', [AppointmentController::class, 'adminIndex']);
        Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'adminUpdateStatus']);
    });
});