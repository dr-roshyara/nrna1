<?php
use App\Http\Controllers\GoogleAccountController;
use App\Http\Controllers\GoogleWebhookController;
use App\Http\Controllers\EventController;

Route::name('google.index')->get('google', [GoogleAccountController::class, 'index']);
Route::get('/google/oauth', [GoogleAccountController::class, 'store'])->name('google.auth');
// Route::name('google.destroy')->delete('googles/{googleAccount}', [GoogleAccountController::class, 'destroy']);
 Route::post('google/webhook', GoogleWebhookController::class)->name('google.webhook');
//view events
    Route::get('/event', [EventController::class, 'index'])->name('event.index');

// Google and Facebook sign-in were removed (PBDIGIT-340, Principal decision P-I5, 2026-11-09).
// The routes above are the Google Calendar integration, not sign-in.
