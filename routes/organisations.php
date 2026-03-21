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

use App\Http\Controllers\Election\ElectionManagementController;
use App\Http\Controllers\ElectionOfficerController;
use App\Http\Controllers\ElectionOfficerInvitationController;
use App\Http\Controllers\ElectionVoterController;
use App\Http\Controllers\Organisations\VoterController;
use Illuminate\Support\Facades\Route;

// ── Invitation acceptance — public (signed middleware only, no auth required) ──
Route::prefix('organisations/{organisation:slug}')
    ->group(function () {
        Route::prefix('/election-officers')->name('organisations.election-officers.')->group(function () {
            Route::get('/invitation/{officer}/accept', [ElectionOfficerInvitationController::class, 'accept'])
                ->name('invitation.accept')
                ->middleware('signed');
        });
    });

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

        // ── Election Creation ──────────────────────────────────────────────────────
        Route::get('/elections/create', [ElectionManagementController::class, 'create'])->name('organisations.elections.create');
        Route::post('/elections',       [ElectionManagementController::class, 'store']) ->name('organisations.elections.store');

        // ── Election Officers ──────────────────────────────────────────────────────
        Route::prefix('/election-officers')->name('organisations.election-officers.')->group(function () {
            Route::get('/',                  [ElectionOfficerController::class, 'index'])   ->name('index');
            Route::post('/',                 [ElectionOfficerController::class, 'store'])   ->name('store');
            Route::post('/{officer}/accept', [ElectionOfficerController::class, 'accept']) ->name('accept');
            Route::delete('/{officer}',      [ElectionOfficerController::class, 'destroy'])->name('destroy');
        });

        // ── Election Voter Management (ElectionMembership — real elections only) ──
        Route::prefix('/elections/{election}')->group(function () {
            Route::get('/voters',                   [ElectionVoterController::class, 'index'])     ->name('elections.voters.index');
            Route::post('/voters',                  [ElectionVoterController::class, 'store'])     ->name('elections.voters.store');
            Route::post('/voters/bulk',             [ElectionVoterController::class, 'bulkStore']) ->name('elections.voters.bulk');
            Route::get('/voters/export',            [ElectionVoterController::class, 'export'])    ->name('elections.voters.export');
            Route::delete('/voters/{membership}',   [ElectionVoterController::class, 'destroy'])   ->name('elections.voters.destroy');
            Route::post('/voters/{membership}/approve', [ElectionVoterController::class, 'approve'])->name('elections.voters.approve');
            Route::post('/voters/{membership}/suspend', [ElectionVoterController::class, 'suspend'])->name('elections.voters.suspend');
        });
    });
