<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthUserMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Definisi route untuk aplikasi Tapak Lampung.
|
*/

use App\Http\Controllers\CulinaryController;

// Public
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/destinations/{id}', [PageController::class, 'showDestination'])->name('destinations.show');
Route::get('/search', [PageController::class, 'search'])->name('search');

// Kuliner & Restoran (public - view only)
Route::get('/kuliner/{id}', [CulinaryController::class, 'show'])->name('culinary.show');
Route::get('/restoran/{id}', [CulinaryController::class, 'restaurant'])->name('culinary.restaurant');

// Trip Booking & Kuliner Booking (protected - harus login)
Route::middleware(AuthUserMiddleware::class)->group(function () {
    // Trip
    Route::get('/trips/{id}/book', [TripController::class, 'book'])->name('trips.book');
    Route::post('/trips/{id}/book', [TripController::class, 'store'])->name('trips.store');
    Route::get('/booking/success', [TripController::class, 'success'])->name('trips.success');
    Route::get('/booking/invoice', [TripController::class, 'invoice'])->name('booking.invoice');

    // Kuliner
    Route::get('/restoran/{id}/book', [CulinaryController::class, 'bookForm'])->name('culinary.book');
    Route::post('/restoran/{id}/book', [CulinaryController::class, 'bookStore'])->name('culinary.book.store');
    Route::get('/kuliner/booking/invoice', [CulinaryController::class, 'invoice'])->name('culinary.invoice');
});

// Auth
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Admin Auth
Route::get('/admin/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('admin.logout');

// Admin (protected)
Route::middleware(AdminMiddleware::class)->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Wisata (Destinations)
    Route::resource('destinations', \App\Http\Controllers\Admin\DestinationController::class);

    // Kuliner
    Route::resource('culinaries', \App\Http\Controllers\Admin\CulinaryController::class);

    // Open Trip
    Route::resource('trips', \App\Http\Controllers\Admin\TripController::class);

    // Bookings
    Route::get('bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{booking}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::patch('bookings/{booking}/confirm', [\App\Http\Controllers\Admin\BookingController::class, 'confirm'])->name('bookings.confirm');
});
