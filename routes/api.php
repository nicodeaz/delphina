<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Routes for Nail Art Studio
Route::middleware('auth:sanctum')->group(function () {
    // Services
    Route::apiResource('services', ServiceController::class)->only(['index', 'show']);

    // Appointments
    Route::get('appointments/available', [AppointmentController::class, 'availableSlots']);
    Route::apiResource('appointments', AppointmentController::class)->except(['update']);
    Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);

    // Admin routes
    Route::middleware('api.admin')->prefix('admin')->group(function () {
        Route::get('appointments', [AppointmentController::class, 'adminIndex']);
        Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'adminUpdateStatus']);
    });
});