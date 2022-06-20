<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenionController;

////Dashboard
// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     // return Inertia::render('Dashboard');
//     return Inertia::render('Dashboard/MainDashboard');
// })->name('dashboard');

// Route::middleware(['auth:sanctum', 'verified'])
// ->get('/dashboard', [OpenionController::class, 'index'])->name('dashboard');
// Route::middleware(['auth:sanctum', 'verified'])
// ->get('/openions', [OpenionController::class, 'index'])
// ->name('openions.index');
Route::get('/openions', [OpenionController::class, 'index'])
        ->name('openions.index');

Route::get('/openions/{user_id}', [OpenionController::class, 'userOpenions'])
        ->name('user.openions');
Route::middleware(['auth:sanctum', 'verified'])
    ->post('/openions', [OpenionController::class, 'store'])
      ->name('openions.store');

Route::middleware(['auth:sanctum', 'verified'])
    ->post('/openion/destroy', [OpenionController::class, 'destroy'])
      ->name('openion.destroy');
