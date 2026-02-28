<?php

/**
 * organisation-Scoped Routes
 *
 * All routes in this file are automatically prefixed with /organisations/{slug}
 * and protected by:
 * - 'auth' middleware (user must be logged in)
 * - 'verified' middleware (email must be verified)
 * - 'ensure.organisation' middleware (user must be member of organisation)
 *
 * The organisation slug comes from the URL and is validated by the middleware,
 * which also stores the organisation object in the request for controller use.
 *
 * SECURITY:
 * - Every route has automatic organisation membership validation
 * - Specific role requirements are handled by authorization policies in controllers
 * - No cross-organisation data access is possible
 */

use App\Http\Controllers\Organizations\VoterController;
use Illuminate\Support\Facades\Route;

Route::prefix('organisations/{organisation:slug}')
    ->middleware(['auth', 'verified', 'ensure.organisation'])
    ->group(function () {
        /**
         * VOTER MANAGEMENT ROUTES
         *
         * These routes allow organisation members to view, approve, and manage voters.
         * Commission members can approve/suspend voters.
         * Regular members can only view voters.
         */

        // List voters for the organisation
        Route::get('/voters', [VoterController::class, 'index'])
            ->name('organisations.voters.index');

        // Approve a single voter (requires commission membership)
        Route::post('/voters/{voter}/approve', [VoterController::class, 'approve'])
            ->name('organisations.voters.approve');

        // Suspend a voter (revoke voting rights, requires commission membership)
        Route::post('/voters/{voter}/suspend', [VoterController::class, 'suspend'])
            ->name('organisations.voters.suspend');

        // Bulk approve multiple voters (requires commission membership)
        Route::post('/voters/bulk-approve', [VoterController::class, 'bulkApprove'])
            ->name('organisations.voters.bulk-approve');

        // Bulk suspend multiple voters (requires commission membership)
        Route::post('/voters/bulk-suspend', [VoterController::class, 'bulkSuspend'])
            ->name('organisations.voters.bulk-suspend');
    });
