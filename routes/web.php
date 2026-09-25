<?php

use App\Http\Controllers\AdminAuthController;
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
Route::get('/cv', [HomeController::class, 'cv'])->name('cv');
Route::get('/book', [BookingController::class, 'create'])->name('booking.create');
// Old URL: one canonical booking page avoids duplicate content in search.
Route::permanentRedirect('/booking', '/book')->name('book');
Route::post('/book', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('bookings.store');

// SEO: robots.txt and sitemap.xml are generated dynamically so they always
// reflect the current APP_URL (useful once the production domain is set).
Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Disallow: /admin',
        'Disallow: /api',
        'Disallow: /payments',
        '',
        'Sitemap: '.url('/sitemap.xml'),
    ];

    return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
})->name('robots');

Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
        ['loc' => route('booking.create'), 'changefreq' => 'weekly', 'priority' => '0.9'],
        ['loc' => route('policies'), 'changefreq' => 'monthly', 'priority' => '0.5'],
    ];

    $xml = view('sitemap', compact('urls'))->render();

    return response($xml, 200)->header('Content-Type', 'text/xml');
})->name('sitemap');

// Payment Routes (public for booking flow)
Route::get('/payments/{appointment}', [PaymentController::class, 'show'])->name('payments.show');
Route::post('/payments/{appointment}/process', [PaymentController::class, 'process'])->name('payments.process');

// Admin Routes (protected)
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/agenda', [AdminController::class, 'agenda'])->name('agenda');
    Route::put('/password', [AdminAuthController::class, 'updatePassword'])->middleware('throttle:5,1')->name('password.update');
    Route::patch('/appointments/{id}/status', [AdminController::class, 'updateStatus'])->name('appointments.updateStatus');
    Route::patch('/appointments/{appointment}/balance', [AdminController::class, 'updateBalanceStatus'])->name('appointments.updateBalance');
    Route::patch('/appointments/{appointment}/reschedule', [AdminController::class, 'reschedule'])->name('appointments.reschedule');
    Route::patch('/payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments.index');
    // Old payments list: deposits now live in the bookings list.
    Route::redirect('/payments', 'admin/appointments?filter=deposit')->name('payments.index');
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Bookings created/edited/deleted from the agenda modals (whole group at once)
    Route::post('/bookings', [AdminController::class, 'storeBooking'])->name('bookings.store');
    Route::put('/bookings/{appointment}', [AdminController::class, 'updateBooking'])->name('bookings.update');
    Route::delete('/bookings/{appointment}', [AdminController::class, 'destroyBooking'])->name('bookings.destroy');

    // Services: listed and edited with modals on one page
    Route::patch('/services/{service}/price', [ServiceController::class, 'updatePrice'])->name('services.updatePrice');
    Route::resource('services', ServiceController::class)->only(['index', 'store', 'update', 'destroy']);

    // Open hours: managed from the agenda modals (index just redirects there)
    Route::post('/available-dates/bulk', [AvailableDateController::class, 'bulkStore'])->name('available-dates.bulk');
    Route::resource('available-dates', AvailableDateController::class)->only(['index', 'store', 'update', 'destroy']);
});

// Studio login: password, then a 6-digit code sent by email (2FA)
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:10,1')->name('admin.login.post');
Route::get('/admin/login/verify', [AdminAuthController::class, 'showVerifyForm'])->name('admin.login.verify');
Route::post('/admin/login/verify', [AdminAuthController::class, 'verify'])->middleware('throttle:10,1')->name('admin.login.verify.post');
Route::post('/admin/login/resend', [AdminAuthController::class, 'resend'])->middleware('throttle:3,1')->name('admin.login.resend');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// API Routes for booking
Route::get('/api/appointments/available', [BookingController::class, 'getAvailableSlots'])->middleware('throttle:60,1')->name('api.appointments.available');
