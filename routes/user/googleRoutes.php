<?php
use App\Http\Controllers\GoogleAccountController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\GoogleWebhookController;
use App\Http\Controllers\EventController;

Route::name('google.index')->get('google', [GoogleAccountController::class, 'index']);
Route::get('/google/oauth', [GoogleAccountController::class, 'store'])->name('google.auth');
// Route::name('google.destroy')->delete('googles/{googleAccount}', [GoogleAccountController::class, 'destroy']);
 Route::post('google/webhook', GoogleWebhookController::class)->name('google.webhook');
//view events
    Route::get('/event', [EventController::class, 'index'])->name('event.index');

    //Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    // Route::get('auth/google/callback', [GoogleController::class, 'handleCallback']);
  Route::group(['middleware' => ['guest']], function() {
        // /**
        //  * Register Routes
        //  */
        // Route::get('/register', 'RegisterController@show')->name('register.show');
        // Route::post('/register', 'RegisterController@register')->name('register.perform');

        // /**
        //  * Login Routes
        //  */
        // Route::get('/login', 'LoginController@show')->name('login.show');
        // Route::post('/login', 'LoginController@login')->name('login.perform');

        /* Google Social Login */
        Route::get('/login/google', [GoogleLoginController::class, 'redirect'])->name('login.google-redirect');
        Route::get('/login/google/callback', [GoogleLoginController::class, 'callback'])->name('login.google-callback');

    });
