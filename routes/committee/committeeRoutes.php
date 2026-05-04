<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\Committee\CommitteeDashboardController;
use App\Http\Controllers\Committee\CommitteeManagementController;
use App\Http\Controllers\Committee\CommitteeMemberController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/committee/{committeeId}/dashboard', [CommitteeDashboardController::class, 'show'])
        ->name('committee.dashboard');

    Route::get('/activities', [CommitteeController::class, 'activities'])->name('activities');
    Route::get('/calendar', [CommitteeController::class, 'showCalendar'])->name('calendar');
});

Route::middleware(['auth', 'verified'])->prefix('organisations/{organisation}')->group(function () {
    Route::patch('/committees/{committeeId}', [CommitteeManagementController::class, 'update'])->name('committees.update');
    Route::delete('/committees/{committeeId}/members/{assignmentId}', [CommitteeMemberController::class, 'remove'])->name('committees.members.remove');
});
