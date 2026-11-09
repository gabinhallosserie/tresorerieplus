<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarFeedController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/methode', 'pages.method')->name('method');
Route::view('/rejoindre-le-reseau', 'pages.network')->name('network');
Route::view('/mentions-legales', 'pages.legal')->name('legal');
Route::view('/confidentialite', 'pages.privacy')->name('privacy');

Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,10')->name('contact.store');

Route::get('/rendez-vous', [BookingController::class, 'index'])->name('booking.index');
Route::get('/rendez-vous/reserver', [BookingController::class, 'create'])->name('booking.create');
Route::post('/rendez-vous', [BookingController::class, 'store'])->middleware('throttle:5,10')->name('booking.store');
Route::get('/rendez-vous/{token}', [BookingController::class, 'show'])->name('booking.show');
Route::post('/rendez-vous/{token}/annuler', [BookingController::class, 'cancel'])->name('booking.cancel');

Route::get('/agenda/{token}.ics', CalendarFeedController::class)->name('calendar.feed');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/connexion', [Admin\LoginController::class, 'create'])->middleware('guest')->name('login');
    Route::post('/connexion', [Admin\LoginController::class, 'store'])->middleware(['guest', 'throttle:5,1'])->name('login.store');

    Route::middleware('auth')->group(function () {
        Route::post('/deconnexion', [Admin\LoginController::class, 'destroy'])->name('logout');

        Route::get('/', [Admin\AppointmentController::class, 'index'])->name('dashboard');
        Route::get('/rendez-vous/{appointment}', [Admin\AppointmentController::class, 'show'])->name('appointments.show');
        Route::patch('/rendez-vous/{appointment}', [Admin\AppointmentController::class, 'update'])->name('appointments.update');
        Route::post('/rendez-vous/{appointment}/annuler', [Admin\AppointmentController::class, 'cancel'])->name('appointments.cancel');

        Route::get('/disponibilites', [Admin\AvailabilityController::class, 'edit'])->name('availability.edit');
        Route::put('/disponibilites', [Admin\AvailabilityController::class, 'update'])->name('availability.update');
        Route::post('/fermetures', [Admin\AvailabilityController::class, 'storeClosedPeriod'])->name('closed-periods.store');
        Route::delete('/fermetures/{closedPeriod}', [Admin\AvailabilityController::class, 'destroyClosedPeriod'])->name('closed-periods.destroy');

        Route::get('/messages', [Admin\ContactMessageController::class, 'index'])->name('messages.index');
        Route::post('/messages/{contactMessage}/lu', [Admin\ContactMessageController::class, 'markAsRead'])->name('messages.read');
    });
});
