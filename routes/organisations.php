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
use App\Http\Controllers\Election\VoterImportController;
use App\Http\Controllers\Election\VoterVerificationController;
use App\Http\Controllers\Election\CandidacyReviewController;
use App\Http\Controllers\Election\ElectionManagementController;
use App\Http\Controllers\Election\PostManagementController;
use App\Http\Controllers\ElectionOfficerController;
use App\Http\Controllers\ElectionOfficerInvitationController;
use App\Http\Controllers\ElectionVoterController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OrganisationSettingsController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\Membership\MembershipApplicationController;
use App\Http\Controllers\Membership\MembershipDashboardController;
use App\Http\Controllers\Membership\MembershipFeeController;
use App\Http\Controllers\Membership\MembershipRenewalController;
use App\Http\Controllers\Membership\MembershipTypeController;
use App\Http\Controllers\Membership\OrganisationParticipantController;
use App\Http\Controllers\Membership\OrganisationRoleController;
use App\Http\Controllers\Membership\ParticipantImportController;
use App\Http\Controllers\Membership\OrganisationNewsletterController;
use App\Http\Controllers\Membership\ParticipantInvitationController;
use App\Http\Controllers\Membership\PublicMembershipApplicationController;
use App\Http\Controllers\Organisation\OrganisationMemberInvitationController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\Contribution\ContributionController;
use Illuminate\Support\Facades\Route;

// ── Public membership application — no auth required ──
Route::get('/organisations/{organisation:slug}/join',
    [PublicMembershipApplicationController::class, 'create'])
    ->name('organisations.join');

Route::post('/organisations/{organisation:slug}/join',
    [PublicMembershipApplicationController::class, 'store'])
    ->name('organisations.join.store');

// ── Invitation acceptance — no auth required (handles guest redirect internally) ──
Route::get('/invitations/{token}', [OrganisationMemberInvitationController::class, 'accept'])
    ->name('organisations.invitations.accept');

Route::get('/participant-invitations/{token}', [ParticipantInvitationController::class, 'accept'])
    ->name('organisations.participant-invitations.accept')
    ->middleware(['auth']);

Route::prefix('organisations/{organisation:slug}')
    ->group(function () {
        Route::prefix('/election-officers')->name('organisations.election-officers.')->group(function () {
            Route::get('/invitation/{officer}/accept', [ElectionOfficerInvitationController::class, 'accept'])
                ->name('invitation.accept')
                ->middleware('signed');
        });
    });

// ── Public Membership Application Routes (auth required, NO org membership required) ──
// Non-members must be able to apply to join an organisation.
Route::prefix('organisations/{organisation:slug}/membership')
    ->name('organisations.membership.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/apply',  [MembershipApplicationController::class, 'create'])->name('apply');
        Route::post('/apply', [MembershipApplicationController::class, 'store']) ->name('apply.store');
    });

Route::prefix('organisations/{organisation:slug}')
    ->middleware(['auth', 'verified', 'ensure.organisation'])
    ->group(function () {
        // ── Organisation Hub Pages ─────────────────────────────────────────────────
        Route::get('/voter-hub',           [OrganisationController::class, 'voterHub'])          ->name('organisations.voter-hub');
        Route::get('/election-commission', [OrganisationController::class, 'electionCommission'])->name('organisations.election-commission');

        // ── Organisation Settings ────────────────────────────────────────────────────
        Route::get('/settings',                                           [OrganisationSettingsController::class, 'index'])               ->name('organisations.settings.index');
        Route::patch('/settings/membership-mode',                         [OrganisationSettingsController::class, 'updateMembershipMode'])->name('organisations.settings.update-membership-mode');

        // ── Candidacy Applications (voter self-service) ────────────────────────────
        Route::get('/candidacy/apply', [CandidacyApplicationController::class, 'create'])->name('organisations.candidacy.create');
        Route::post('/candidacy/apply', [CandidacyApplicationController::class, 'store']) ->name('organisations.candidacy.apply');
        Route::get('/candidacy/list',  [CandidacyApplicationController::class, 'index']) ->name('organisations.candidacy.list');

        // ── Membership Types management (owner only) ──────────────────────────────
        Route::prefix('/membership-types')->name('organisations.membership-types.')->group(function () {
            Route::get('/',                            [MembershipTypeController::class, 'index'])  ->name('index');
            Route::post('/',                           [MembershipTypeController::class, 'store'])  ->name('store');
            Route::put('/{membershipType}',            [MembershipTypeController::class, 'update']) ->name('update');
            Route::delete('/{membershipType}',         [MembershipTypeController::class, 'destroy'])->name('destroy');
        });

        // ── Membership Dashboard ───────────────────────────────────────────────────
        Route::get('/membership', [MembershipDashboardController::class, 'index'])
            ->name('organisations.membership.dashboard');

        // ── Membership Applications management (admin/commission only) ──────────────
        Route::prefix('/membership')->name('organisations.membership.')->group(function () {
            Route::get('/applications', [MembershipApplicationController::class, 'index'])
                ->name('applications.index');

            Route::get('/applications/{application}', [MembershipApplicationController::class, 'show'])
                ->name('applications.show');

            Route::patch('/applications/{application}/approve', [MembershipApplicationController::class, 'approve'])
                ->name('applications.approve');

            Route::patch('/applications/{application}/reject', [MembershipApplicationController::class, 'reject'])
                ->name('applications.reject');

            // ── OrganisationParticipant management (staff/guest/election_committee) ──
            Route::get('/participants',                      [OrganisationParticipantController::class, 'index'])  ->name('participants.index');
            Route::post('/participants',                     [OrganisationParticipantController::class, 'store'])  ->name('participants.store');
            Route::delete('/participants/{participant}',     [OrganisationParticipantController::class, 'destroy'])->name('participants.destroy');

            // ── Organisation Roles — view roles, promote to formal member ──────────
            Route::get('/roles',                    [OrganisationRoleController::class, 'index'])        ->name('roles.index');
            Route::post('/roles/add-member',        [OrganisationRoleController::class, 'addMember'])    ->name('roles.add-member');
            Route::post('/roles/assign-officer',    [OrganisationRoleController::class, 'assignOfficer'])->name('roles.assign-officer');
            Route::post('/roles/remove-officer',    [OrganisationRoleController::class, 'removeOfficer'])->name('roles.remove-officer');

            // ── Participant bulk import ────────────────────────────────────────
            Route::prefix('/participants')->name('participants.')->group(function () {
                Route::get('/import',          [ParticipantImportController::class, 'create'])  ->name('import.create');
                Route::get('/import/template', [ParticipantImportController::class, 'template'])->name('import.template');
                Route::post('/import/preview', [ParticipantImportController::class, 'preview']) ->name('import.preview');
                Route::post('/import',         [ParticipantImportController::class, 'import'])  ->name('import');
            });

            // ── Participant invitations (admin/owner only) ─────────────────────
            Route::prefix('/participant-invitations')->name('participant-invitations.')->group(function () {
                Route::get('/',                             [ParticipantInvitationController::class, 'index'])  ->name('index');
                Route::post('/',                            [ParticipantInvitationController::class, 'store'])  ->name('store');
                Route::delete('/{invitation}',              [ParticipantInvitationController::class, 'destroy'])->name('destroy');
            });

            // ── Newsletters (admin/owner only) ────────────────────────────────
            Route::prefix('/newsletters')->name('newsletters.')->group(function () {
                Route::get('/',                          [OrganisationNewsletterController::class, 'index'])           ->name('index');
                Route::get('/create',                    [OrganisationNewsletterController::class, 'create'])          ->name('create');
                Route::post('/',                         [OrganisationNewsletterController::class, 'store'])           ->name('store');
                Route::get('/{newsletter}',              [OrganisationNewsletterController::class, 'show'])            ->name('show');
                Route::get('/{newsletter}/edit',         [OrganisationNewsletterController::class, 'edit'])            ->name('edit');
                Route::put('/{newsletter}',              [OrganisationNewsletterController::class, 'update'])          ->name('update');
                Route::get('/{newsletter}/preview',      [OrganisationNewsletterController::class, 'previewRecipients'])->name('preview');
                Route::post('/{newsletter}/attachments',              [OrganisationNewsletterController::class, 'storeAttachment'])   ->name('attachments.store');
                Route::delete('/{newsletter}/attachments/{attachment}', [OrganisationNewsletterController::class, 'destroyAttachment'])->name('attachments.destroy');
                Route::patch('/{newsletter}/send',       [OrganisationNewsletterController::class, 'send'])            ->name('send');
                Route::patch('/{newsletter}/cancel',     [OrganisationNewsletterController::class, 'cancel'])          ->name('cancel');
                Route::delete('/{newsletter}',           [OrganisationNewsletterController::class, 'destroy'])         ->name('destroy');
            });
        });

        // ── Participants List (everyone with a platform role) ─────────────────────
        Route::get('/participants',        [ParticipantController::class, 'index'])  ->name('organisations.participants.index');
        Route::get('/participants/export', [ParticipantController::class, 'export']) ->name('organisations.participants.export');

        // ── Members List (formal paid members only) ───────────────────────────────
        Route::get('/members',        [MemberController::class, 'index'])  ->name('organisations.members.index');
        Route::get('/members/export', [MemberController::class, 'export']) ->name('organisations.members.export');
        Route::patch('/members/{member}/mark-paid', [MemberController::class, 'markPaid']) ->name('organisations.members.mark-paid');

        // ── Membership Fee & Renewal Management ──────────────────────────────────
        Route::prefix('/members/{member}')->name('organisations.members.')->group(function () {
            Route::get('/fees',             [MembershipFeeController::class,     'index']) ->name('fees.index');
            Route::post('/fees/{fee}/pay',  [MembershipFeeController::class,     'pay'])   ->name('fees.pay');
            Route::post('/fees/{fee}/waive',[MembershipFeeController::class,     'waive']) ->name('fees.waive');
            Route::get('/finance',          [MemberController::class,            'finance']) ->name('finance');
            Route::post('/renew',           [MembershipRenewalController::class, 'store']) ->name('renew');
        });

        // ── Contributions ─────────────────────────────────────────────────────────
        Route::prefix('contributions')->name('organisations.contributions.')->group(function () {
            Route::get('/', [ContributionController::class, 'index'])->name('index');
            Route::get('/create', [ContributionController::class, 'create'])->name('create');
            Route::post('/', [ContributionController::class, 'store'])->name('store');
            Route::get('/{contribution}', [ContributionController::class, 'show'])->name('show');
        });

        Route::get('/leaderboard', [ContributionController::class, 'leaderboard'])->name('organisations.leaderboard');

        // ── Member Invitations ────────────────────────────────────────────────────
        Route::prefix('/members')->name('organisations.members.')->group(function () {
            Route::get('/invite',              [OrganisationMemberInvitationController::class, 'index'])  ->name('invite');
            Route::post('/invite',             [OrganisationMemberInvitationController::class, 'store'])  ->name('invite.store');
            Route::delete('/invitations/{invitation}', [OrganisationMemberInvitationController::class, 'destroy'])->name('invitations.destroy');
        });

        // ── Elections ─────────────────────────────────────────────────────────────
        Route::get('/elections',        [ElectionManagementController::class, 'listForOrganisation']) ->name('organisations.elections.index');
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
            // ── Public candidacy application page (voter-facing, per election) ────
            Route::get('/candidacy/apply', [CandidacyApplicationController::class, 'applyForm'])
                ->name('organisations.elections.candidacy.apply');
            // ── Voter bulk import ──────────────────────────────────────────────
            Route::prefix('/voters')->name('elections.voters.')->group(function () {
                Route::get('/import',          [VoterImportController::class, 'create'])  ->name('import.create');
                Route::get('/import/tutorial', [VoterImportController::class, 'tutorial'])->name('import.tutorial');
                Route::get('/import/template', [VoterImportController::class, 'template'])->name('import.template');
                Route::post('/import/preview', [VoterImportController::class, 'preview']) ->name('import.preview');
                Route::post('/import',         [VoterImportController::class, 'import'])  ->name('import');
            });

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

            // ── Voter verification (IP & device fingerprint) ──────────────────────
            Route::post('/voters/verify',                           [VoterVerificationController::class, 'store'])  ->name('elections.voters.verify');
            Route::delete('/voters/{verification}/revoke',          [VoterVerificationController::class, 'revoke']) ->name('elections.voters.verification.revoke');

            // ── Voter-facing positions page (read-only) ───────────────────────────
            Route::get('/positions',          [OrganisationController::class, 'voterPosts'])->name('organisations.elections.positions');

            // ── Voter-facing candidates page (positions & candidates) ──────────────
            Route::get('/candidates',         [OrganisationController::class, 'voterCandidates'])->name('organisations.elections.candidates');

            // ── Posts management (positions within an election) ────────────────────
            Route::get('/posts',              [PostManagementController::class, 'index'])  ->name('organisations.elections.posts.index');
            Route::post('/posts',             [PostManagementController::class, 'store'])  ->name('organisations.elections.posts.store');
            Route::patch('/posts/{post}',     [PostManagementController::class, 'update']) ->name('organisations.elections.posts.update');
            Route::delete('/posts/{post}',    [PostManagementController::class, 'destroy'])->name('organisations.elections.posts.destroy');

            // ── Candidacies management (candidates per post) ───────────────────────
            Route::get('/candidacies',                             [CandidacyManagementController::class, 'index'])  ->name('organisations.elections.candidacies.index');
            Route::post('/posts/{post}/candidacies',               [CandidacyManagementController::class, 'store'])  ->name('organisations.elections.candidacies.store');
            Route::patch('/posts/{post}/candidacies/{candidacy}', [CandidacyManagementController::class, 'update']) ->name('organisations.elections.candidacies.update');
            Route::delete('/posts/{post}/candidacies/{candidacy}',[CandidacyManagementController::class, 'destroy'])->name('organisations.elections.candidacies.destroy');

            // ── Candidacy application review (officer-only) ────────────────────────
            Route::get('/candidacy/applications',                        [CandidacyReviewController::class, 'index'])  ->name('organisations.elections.candidacy.applications');
            Route::patch('/candidacy/applications/{application}',        [CandidacyReviewController::class, 'review']) ->name('organisations.elections.candidacy.review');
        });
    });
