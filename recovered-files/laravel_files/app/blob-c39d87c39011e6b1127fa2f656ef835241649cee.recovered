<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenionController;
use App\Http\Controllers\Api\DemoSetupController;
use App\Http\Controllers\LocaleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/**
 *
 *
 */
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});

Route::post('/login', [AuthController::class, 'login']);

// Locale management (available to all users, no authentication required)
Route::post('/set-locale', [LocaleController::class, 'setLocale'])->name('api.set-locale');
Route::get('/get-locale', [LocaleController::class, 'getLocale'])->name('api.get-locale');

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::get('/openions/search', [OpenionController::class, 'search'])
        ->name('openions.search');

    // Demo Setup API Endpoint
    Route::post('/organisations/{organisation}/demo-setup', [DemoSetupController::class, 'setup'])
        ->name('api.organisations.demo-setup');
});
