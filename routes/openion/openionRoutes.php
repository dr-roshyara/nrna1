<?php

use App\Http\Controllers\OpenionController;
//Dashboard
// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     // return Inertia::render('Dashboard');
//     return Inertia::render('Dashboard/MainDashboard');
// })->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', [OpenionController::class, 'index'])->name('dashboard');
