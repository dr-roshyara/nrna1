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

use App\Http\Controllers\CandidacyApplicationController;
use App\Http\Controllers\Election\CandidacyManagementController;
use App\Http\Controllers\Election\ElectionManagementController;
use App\Http\Controllers\Election\PostManagementController;
use App\Http\Controllers\ElectionOfficerController;
use App\Http\Controllers\ElectionOfficerInvitationController;
use App\Http\Controllers\ElectionVoterController;
use App\Http\Controllers\OrganisationController;
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
        // ── Organisation Hub Pages ─────────────────────────────────────────────────
        Route::get('/voter-hub',           [OrganisationController::class, 'voterHub'])          ->name('organisations.voter-hub');
        Route::get('/election-commission', [OrganisationController::class, 'electionCommission'])->name('organisations.election-commission');

        // ── Candidacy Applications (voter self-service) ────────────────────────────
        Route::post('/candidacy/apply', [CandidacyApplicationController::class, 'store'])->name('organisations.candidacy.apply');

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
        Route::prefix('/elections/{election:slug}')->group(function () {
            Route::get('/voters',                   [ElectionVoterController::class, 'index'])     ->name('elections.voters.index');
            Route::post('/voters',                  [ElectionVoterController::class, 'store'])     ->name('elections.voters.store');
            Route::post('/voters/bulk',             [ElectionVoterController::class, 'bulkStore']) ->name('elections.voters.bulk');
            Route::get('/voters/export',            [ElectionVoterController::class, 'export'])    ->name('elections.voters.export');
            Route::delete('/voters/{membership}',   [ElectionVoterController::class, 'destroy'])   ->name('elections.voters.destroy');
            Route::post('/voters/{membership}/approve',             [ElectionVoterController::class, 'approve'])           ->name('elections.voters.approve');
            Route::post('/voters/{membership}/suspend',             [ElectionVoterController::class, 'suspend'])           ->name('elections.voters.suspend');
            Route::post('/voters/{membership}/propose-suspension',  [ElectionVoterController::class, 'proposeSuspension']) ->name('elections.voters.propose-suspension');
            Route::post('/voters/{membership}/confirm-suspension',  [ElectionVoterController::class, 'confirmSuspension']) ->name('elections.voters.confirm-suspension');
            Route::post('/voters/{membership}/cancel-proposal',     [ElectionVoterController::class, 'cancelProposal'])    ->name('elections.voters.cancel-proposal');

            // ── Posts management (positions within an election) ────────────────────
            Route::get('/posts',              [PostManagementController::class, 'index'])  ->name('organisations.elections.posts.index');
            Route::post('/posts',             [PostManagementController::class, 'store'])  ->name('organisations.elections.posts.store');
            Route::patch('/posts/{post}',     [PostManagementController::class, 'update']) ->name('organisations.elections.posts.update');
            Route::delete('/posts/{post}',    [PostManagementController::class, 'destroy'])->name('organisations.elections.posts.destroy');

            // ── Candidacies management (candidates per post) ───────────────────────
            Route::post('/posts/{post}/candidacies',               [CandidacyManagementController::class, 'store'])  ->name('organisations.elections.candidacies.store');
            Route::patch('/posts/{post}/candidacies/{candidacy}', [CandidacyManagementController::class, 'update']) ->name('organisations.elections.candidacies.update');
            Route::delete('/posts/{post}/candidacies/{candidacy}',[CandidacyManagementController::class, 'destroy'])->name('organisations.elections.candidacies.destroy');
        });
    });
