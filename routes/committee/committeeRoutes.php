<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\Committee\CommitteeDashboardController;
use App\Http\Controllers\Committee\CommitteeManagementController;
use App\Http\Controllers\Committee\CommitteeMemberController;
use App\Http\Controllers\Committee\MemberSearchController;

// Public routes (no authentication required)
Route::get('/tutorial', [CommitteeManagementController::class, 'tutorial'])
    ->name('committees.tutorial');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/activities', [CommitteeController::class, 'activities'])->name('activities');
    Route::get('/calendar', [CommitteeController::class, 'showCalendar'])->name('calendar');
});

Route::middleware(['auth', 'verified'])->prefix('organisations/{organisation}')->group(function () {
    // Cascader Config (API endpoint)
    Route::get('/api/geography/cascader-config', [CommitteeManagementController::class, 'cascaderConfig'])
        ->name('committee.geography.config');

    // Committee Code Validation (API endpoint)
    Route::get('/api/committees/check-code/{code}', [CommitteeManagementController::class, 'checkCodeExists'])
        ->name('committee.check-code');

    // Committee List & Dashboard
    Route::get('/committees', [CommitteeManagementController::class, 'index'])
        ->name('committees.index');
    Route::get('/committees/{committee}/dashboard', [CommitteeDashboardController::class, 'show'])
        ->name('committee.dashboard');

    // Committee Management
    Route::get('/committees/create', [CommitteeManagementController::class, 'create'])
        ->name('committees.create');
    Route::post('/committees', [CommitteeManagementController::class, 'store'])
        ->name('committees.store');
    Route::get('/committees/{committee}/edit', [CommitteeManagementController::class, 'edit'])
        ->name('committees.edit');
    Route::patch('/committees/{committee}', [CommitteeManagementController::class, 'update'])
        ->name('committees.update');

    // Committee Members
    Route::post('/committees/{committee}/members', [CommitteeMemberController::class, 'assign'])
        ->name('committees.members.assign')
        ->middleware(['throttle:20,60']);
    Route::delete('/committees/{committee}/members/{assignmentId}', [CommitteeMemberController::class, 'remove'])
        ->name('committees.members.remove')
        ->middleware(['throttle:20,60']);

    // Member Search
    Route::get('/members/search', [MemberSearchController::class, 'index'])
        ->name('members.search');
});
