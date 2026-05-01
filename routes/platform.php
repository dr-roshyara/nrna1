<?php

use App\Http\Controllers\Admin\AdminElectionController;
use App\Http\Controllers\Admin\PlatformDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'platform_admin'])
    ->prefix('platform')
    ->name('platform.')
    ->group(function () {

        Route::get('/dashboard', [PlatformDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/elections/pending', [AdminElectionController::class, 'pending'])
            ->name('elections.pending');

        Route::get('/elections/all', [AdminElectionController::class, 'all'])
            ->name('elections.all');

        Route::post('/elections/{election}/approve', [AdminElectionController::class, 'approve'])
            ->name('elections.approve');

        Route::post('/elections/{election}/reject', [AdminElectionController::class, 'reject'])
            ->name('elections.reject');

        Route::get('/elections/{election}/audit/{folder}/voters/{file}', [AdminElectionController::class, 'downloadAuditFile'])
            ->name('elections.audit.download');
    });
