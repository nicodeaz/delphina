<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AvailableDateController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/policies', [HomeController::class, 'policies'])->name('policies');
Route::get('/book', [BookingController::class, 'create'])->name('book');
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/book', [BookingController::class, 'store'])->name('bookings.store');

// Payment Routes (public for booking flow)
Route::get('/payments/{appointment}', [PaymentController::class, 'show'])->name('payments.show');
Route::post('/payments/{appointment}/process', [PaymentController::class, 'process'])->name('payments.process');

// Admin Routes (protected)
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::patch('/appointments/{id}/status', [AdminController::class, 'updateStatus'])->name('appointments.updateStatus');
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments.index');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Services Management
    Route::resource('services', ServiceController::class);

    // Available Dates Management
    Route::resource('available-dates', AvailableDateController::class);
});

// Auth routes for admin only (simplified)
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// API Routes for booking
Route::get('/api/appointments/available', [BookingController::class, 'getAvailableSlots'])->name('api.appointments.available');