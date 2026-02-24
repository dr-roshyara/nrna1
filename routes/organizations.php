<?php

/**
 * Organization-Scoped Routes
 *
 * All routes in this file are automatically prefixed with /organizations/{slug}
 * and protected by:
 * - 'auth' middleware (user must be logged in)
 * - 'verified' middleware (email must be verified)
 * - 'ensure.organization' middleware (user must be member of organization)
 *
 * The organization slug comes from the URL and is validated by the middleware,
 * which also stores the organization object in the request for controller use.
 *
 * SECURITY:
 * - Every route has automatic organization membership validation
 * - Specific role requirements are handled by authorization policies in controllers
 * - No cross-organization data access is possible
 */

use App\Http\Controllers\Organizations\VoterController;
use Illuminate\Support\Facades\Route;

Route::prefix('organizations/{organization:slug}')
    ->middleware(['auth', 'verified', 'ensure.organization'])
    ->group(function () {
        /**
         * VOTER MANAGEMENT ROUTES
         *
         * These routes allow organization members to view, approve, and manage voters.
         * Commission members can approve/suspend voters.
         * Regular members can only view voters.
         */

        // List voters for the organization
        Route::get('/voters', [VoterController::class, 'index'])
            ->name('organizations.voters.index');

        // Approve a single voter (requires commission membership)
        Route::post('/voters/{voter}/approve', [VoterController::class, 'approve'])
            ->name('organizations.voters.approve');

        // Suspend a voter (revoke voting rights, requires commission membership)
        Route::post('/voters/{voter}/suspend', [VoterController::class, 'suspend'])
            ->name('organizations.voters.suspend');

        // Bulk approve multiple voters (requires commission membership)
        Route::post('/voters/bulk-approve', [VoterController::class, 'bulkApprove'])
            ->name('organizations.voters.bulk-approve');

        // Bulk suspend multiple voters (requires commission membership)
        Route::post('/voters/bulk-suspend', [VoterController::class, 'bulkSuspend'])
            ->name('organizations.voters.bulk-suspend');
    });
