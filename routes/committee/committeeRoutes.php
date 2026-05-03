<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\Committee\CommitteeDashboardController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/committee/{committeeId}/dashboard', [CommitteeDashboardController::class, 'show'])
        ->name('committee.dashboard');

    Route::get('/activities', [CommitteeController::class, 'activities'])->name('activities');
    Route::get('/calendar', [CommitteeController::class, 'showCalendar'])->name('calendar');
});
