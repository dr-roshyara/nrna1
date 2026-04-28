<?php
use Inertia\Inertia;
use App\Http\Controllers\CandidacyController;
use App\Http\Controllers\VoterlistController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\Demo\DemoVoteController;
use App\Http\Controllers\Demo\DemoResultController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\Demo\DemoCodeController;
use App\Http\Controllers\DeligateCandidacyController;
use App\Http\Controllers\DeligateVoteController;
use App\Http\Controllers\DeligateCodeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Election\ElectionManagementController;
use App\Http\Controllers\Election\ElectionSettingsController;
use App\Http\Controllers\ElectionController as VotingElectionController;
use App\Http\Controllers\VoterSlugController;
use App\Http\Controllers\Admin\VotingSecurityController;
use App\Http\Controllers\HasVotedController;
use App\Services\ElectionService;
use App\Http\Controllers\ElectionVotingController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PRIMARY ELECTION PAGE (slug-based, shareable, bookmarkable)
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/elections/{slug}',        [ElectionVotingController::class, 'show']) ->name('elections.show');
    Route::post('/elections/{slug}/start', [ElectionVotingController::class, 'start'])->name('elections.start');
});

// Legacy: session-based /election → redirects to /elections/{slug}
Route::middleware(['auth', 'verified'])->get('/election', [ElectionManagementController::class, 'dashboard'])->name('election.dashboard');

// ============================================================
// NEW: Election Selection Routes (Phase 2c)
// ============================================================
// Show election selection page (manual selection UI)
Route::middleware(['auth:sanctum', 'verified'])->get('/election/select', [VotingElectionController::class, 'selectElection'])->name('election.select');

// Store selected election
Route::middleware(['auth:sanctum', 'verified'])->post('/election/select', [VotingElectionController::class, 'storeElection'])->name('election.store');


// Voter slug generation - start voting process
Route::middleware(['auth:sanctum', 'verified'])->get('/voter/start', [VoterSlugController::class, 'start'])->name('voter.start');
Route::middleware(['auth:sanctum', 'verified'])->post('/voter/restart', [VoterSlugController::class, 'restart'])->name('voter.restart');

// Direct voting access - automatically generates slug for user
Route::middleware(['auth:sanctum', 'verified'])->get('/vote', function () {
    $user = auth()->user();

    // Check basic eligibility
    if (!$user->can_vote || $user->has_voted) {
        return redirect()->route('election.dashboard')->with('error', 'You are not eligible to vote at this time.');
    }

    try {
        $slugService = new \App\Services\VoterSlugService();
        $slug = $slugService->getOrCreateActiveSlug($user);

        // Redirect to the slug-based voting flow
        return redirect()->route('slug.code.create', ['vslug' => $slug->slug]);
    } catch (\Exception $e) {
        \Log::error('Failed to auto-generate slug for direct voting', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);

        return redirect()->route('election.dashboard')->with('error', 'Unable to start voting. Please try again.');
    }
})->name('vote.direct');

//voters (moved to unified voters section below)

// ✅ IMPORTANT: Add these exact routes
Route::middleware(['auth:sanctum', 'verified'])
        ->post('/voters/{id}/approve', [VoterlistController::class, 'approveVoter'])->name('voters.approve');

Route::middleware(['auth:sanctum', 'verified'])
        ->post('/voters/{id}/reject', [VoterlistController::class, 'rejectVoter'])->name('voters.reject');        
/**
 * All candidates
 */
Route::get('candidacy/create', [CandidacyController::class, 'create'])->name('candidacy.create');
Route::post('candidacies', [CandidacyController::class, 'store'])->name('candidacy.store');
Route::get('candidacies/index', [CandidacyController::class, 'index'])->name('candidacy.index');
Route::get('candidacy/update', [CandidacyController::class, 'update'])->name('candidacy.update');
Route::get('candidacies/assign', [CandidacyController::class, 'assign'])->name('candidacy.assign');

/**
 * Routes related to vote
 */
//Route::group(['middleware' => 'auth:sanctum', 'verified'], function(){
// Vote
//code creation
//    Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility'])->get('/code/create', [CodeController::class, 'create'])->name('code.create');
//    Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->post('/codes', [CodeController::class, 'store'])->name('code.store');
//    Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->get('/vote/agreement', [CodeController::class, 'showAgreement'])->name('code.agreement');
//    Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->post('/code/agreement', [CodeController::class, 'submitAgreement'])->name('code.agreement.submit');
   
  // Vote denial route
    Route::get('/vote/denied', function() {
        // This route can be used for direct access to denial page if needed
        return Inertia::render('Vote/VoteDenied', [
            'title_english' => 'Access Denied',
            'title_nepali' => 'पहुँच अस्वीकृत',
            'message_english' => 'Your voting access has been restricted.',
            'message_nepali' => 'तपाईंको मतदान पहुँच प्रतिबन्धित गरिएको छ।',
        ]);
    })->name('vote.denied');
    
        // IP statistics route (for debugging/admin)
        Route::get('/admin/ip-stats', [CodeController::class, 'getIPStatistics'])
                ->name('admin.ip.stats')
                ->middleware('can:admin'); // Add appropriate permission middleware
       

    //it actually created Agreement create i accept. 
    //   Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->get('/vote/create', [VoteController::class, 'create'])->name('vote.create');

    // DEPRECATED: Legacy voting routes - all redirect to slug-based voting for security
    Route::middleware(['auth:sanctum',
         'verified',
        'web', 
        'auth', 
        'election',
        'ensure.election.voter' 
        ])->group(function () {
        // Redirect all legacy voting attempts to slug-based system
        Route::get('/vote/create', function () {
            return redirect()->route('vote.direct');
        })->name('vote.create');

        Route::post('/vote/submit', function () {
            return redirect()->route('vote.direct');
        })->name('vote.submit');

        Route::post('/vote/submit_seleccted', function () {
            return redirect()->route('vote.direct');
        })->name('vote.submit_seleccted');

        Route::get('/vote/verify', function () {
            return redirect()->route('vote.direct');
        })->name('vote.verify');
    });

       Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->post('/votes', [VoteController::class, 'store'])->name('vote.store');

    //
    Route::middleware(['auth:sanctum', 'verified'])->get('/vote/verify_to_show', [VoteController::class, 'verify_to_show'])->name('vote.verify_to_show');

    Route::middleware(['auth:sanctum', 'verified'])->post('/vote/submit_code_to_view_vote', [VoteController::class, 'submit_code_to_view_vote'])->name('vote.submit_code_to_view_vote');

    Route::middleware(['auth:sanctum', 'verified']) ->post('/verify_final_vote' , [VoteController::class, 'verify_final_vote'])->name('vote.verify_final_vote');

   Route::middleware(['auth:sanctum', 'verified'])->get('/votes/index', [VoteController::class, 'index'])->name('vote.index');
//    Route::middleware(['auth:sanctum', 'verified'])->get('/vote/show', [VoteController::class, 'show'])->name('vote.show');
   Route::middleware(['auth:sanctum', 'verified'])->get('/vote/show/{vote_id}', [VoteController::class, 'show'])->name('vote.show');
   Route::middleware(['auth:sanctum', 'verified'])->get('/vote/download-pdf/{vote_id}', [VoteController::class, 'downloadVotePDF'])->name('vote.download-pdf');
   Route::middleware(['auth:sanctum', 'verified'])->post('/vote/confirm-correct', [VoteController::class, 'confirmCorrect'])->name('vote.confirm-correct');

   // Election-specific result routes (scoped by election slug)
   Route::middleware(['auth:sanctum', 'verified'])->get('/election/{election:slug}/result', [ResultController::class, 'index'])->name('result.index');
   Route::middleware(['auth:sanctum', 'verified'])->get('/election/{election:slug}/result/download-pdf', [ResultController::class, 'downloadPDF'])->name('result.download.pdf');

   // Has voted - view all members who have voted
   Route::middleware(['auth:sanctum', 'verified'])->get('/election/hasvoted', [HasVotedController::class, 'index'])->name('hasvoted.index');

// Vote verification and display routes


// Voter management routes (require authentication and committee member permissions)
 
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Voter list and management (use names that match your Vue component)
    Route::get('/voters', [VoterlistController::class, 'index'])->name('voters.index');
    Route::get('/voters/{id}', [VoterlistController::class, 'show'])->name('voters.show')->where('id', '[0-9]+');

    // Committee member actions (these route names match your Vue component calls)
    Route::post('/voters/{id}/approve', [VoterlistController::class, 'approveVoter'])->name('voters.approve')->where('id', '[0-9]+');
    Route::post('/voters/{id}/reject', [VoterlistController::class, 'rejectVoter'])->name('voters.reject')->where('id', '[0-9]+');
});

/*************************************************************************************** */
// Additional result routes (verification endpoints - always available for committee members)
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/api/verify-results/{postId}', [ResultController::class, 'verifyResults']);
    Route::get('/api/statistical-verification/{postId}', [ResultController::class, 'statisticalVerification']);
});

/*************************************************************************************** */

//election result
Route::get('vote/thankyou', [VoteController::Class , 'thankyou'])->name('vote.thankyou');
/**
 *
 * Deligate Candidacies
 *
 *  */
Route::middleware(['auth:sanctum', 'verified']) ->get('deligatecandidacy/update', [DeligateCandidacyController::class, 'update'])->name('deligatecandidacy.update');


/***
 * Create here deligate Routes
 *
 */
 Route::middleware(['auth:sanctum', 'verified']) ->get('/deligatecode/create', [DeligateCodeController::class, 'create'])->name('deligatecode.create');
 Route::middleware(['auth:sanctum', 'verified']) ->post('/deligatecodes', [DeligateCodeController::class, 'store'])->name('deligatecode.store');
 Route::middleware(['auth:sanctum', 'verified']) ->get('deligatecandidacies/index', [DeligateCandidacyController::class, 'index'])->name('deligatecandidacy.index');
 Route::middleware(['auth:sanctum', 'verified']) ->get('deligatevote/create', [DeligateVoteController::class, 'create'])->name('deligatevote.create');
 Route::middleware(['auth:sanctum', 'verified']) ->post('/deligatevote/submit', [DeligateVoteController::class, 'first_submission'])->name('deligatevote.submit');
 Route::middleware(['auth:sanctum', 'verified']) ->get('/deligatevote/verify', [DeligateVoteController::class, 'verify'])->name('deligatevote.verifiy');
 Route::middleware(['auth:sanctum', 'verified']) ->post('/deligatevotes', [DeligateVoteController::class, 'store'])->name('deligatevote.store');
 Route::middleware(['auth:sanctum', 'verified']) ->get('/deligatevotes/index', [DeligateVoteController::class, 'index'])->name('deligatevote.index');
 Route::middleware(['auth:sanctum', 'verified']) ->get('/deligatevote/show', [DeligateVoteController::class, 'show'])->name('deligatevote.show');

// election committe
Route::get('/election/committee', function () {
                    return Inertia::render('Election/ElectionCommittee');
                })
                ->name('election.committee');
   
 /**
 * Deligate Vote Count
 *
 */
 Route::middleware(['auth:sanctum', 'verified']) ->get('/deligatevote/count', [DeligateVoteController::class, 'count'])->name('deligatevote.count');
 Route::middleware(['auth:sanctum', 'verified']) ->get('/deligatevote/result', [DeligateVoteController::class, 'result'])->name('deligatevote.result');




//show posts
Route::get('posts/index', [PostController::class, 'index'])->name('post.index');
Route::get('posts/assign', [PostController::class, 'assign'])->name('post.assign');

// Election Management & Viewboard Routes
// Using explicit {election:slug} to trigger implicit model binding
// The Election model's resolveRouteBinding() bypasses global scopes
Route::middleware(['auth', 'verified', \App\Http\Middleware\TenantContext::class])
    ->prefix('/elections/{election:slug}')
    ->group(function () {
        // Management dashboard — chief or deputy only
        // 🔥 TEMPORARY: Authorization moved to controller to debug route binding
        Route::get('/management', [ElectionManagementController::class, 'index'])
            ->name('elections.management');

        Route::get('/status', [ElectionManagementController::class, 'status'])
            ->name('elections.status')
            ->can('manageSettings', 'election');

        // Viewboard — any active officer
        Route::get('/viewboard', [ElectionManagementController::class, 'viewboard'])
            ->name('elections.viewboard')
            ->can('viewResults', 'election');

        // Publish / unpublish results — chief only
        Route::post('/publish', [ElectionManagementController::class, 'publish'])
            ->name('elections.publish')
            ->can('publishResults', 'election');

        Route::post('/unpublish', [ElectionManagementController::class, 'unpublish'])
            ->name('elections.unpublish')
            ->can('publishResults', 'election');

        // Voting period control — chief or deputy
        Route::post('/open-voting', [ElectionManagementController::class, 'openVoting'])
            ->name('elections.open-voting')
            ->can('manageSettings', 'election');

        Route::post('/close-voting', [ElectionManagementController::class, 'closeVoting'])
            ->name('elections.close-voting')
            ->can('manageSettings', 'election');

        Route::post('/lock-voting', [ElectionManagementController::class, 'lockVoting'])
            ->name('elections.lock-voting')
            ->can('manageSettings', 'election');

        // Election approval workflow — officer action
        Route::get('/submit-for-approval', [ElectionManagementController::class, 'showSubmitForApproval'])
            ->name('elections.submit-for-approval.show')
            ->can('manageSettings', 'election');

        Route::post('/submit-for-approval', [ElectionManagementController::class, 'submitForApproval'])
            ->name('elections.submit-for-approval')
            ->can('manageSettings', 'election');

        // Bulk voter management — chief or deputy
        Route::post('/bulk-approve-voters', [ElectionManagementController::class, 'bulkApproveVoters'])
            ->name('elections.bulk-approve-voters')
            ->can('manageVoters', 'election');

        Route::post('/bulk-disapprove-voters', [ElectionManagementController::class, 'bulkDisapproveVoters'])
            ->name('elections.bulk-disapprove-voters')
            ->can('manageVoters', 'election');

        // Activate a planned election — chief or deputy
        Route::post('/activate', [ElectionManagementController::class, 'activate'])
            ->name('elections.activate');

        // Update election dates — chief or deputy
        Route::patch('/update-dates', [ElectionManagementController::class, 'updateDates'])
            ->name('elections.update-dates')
            ->can('manageSettings', 'election');

        // Upload organisation logo — chief or deputy
        Route::post('/upload-logo', [ElectionManagementController::class, 'uploadLogo'])
            ->name('elections.upload-logo')
            ->can('manageSettings', 'election');

        // ── Election Settings ─────────────────────────────────────────────────────
        Route::get('/settings', [ElectionSettingsController::class, 'edit'])
            ->name('elections.settings.edit')
            ->can('manageSettings', 'election');

        Route::patch('/settings', [ElectionSettingsController::class, 'update'])
            ->name('elections.settings.update')
            ->can('manageSettings', 'election');

        // ── Timeline View (Read-only - always accessible for audit trail) ────────────
        Route::get('/timeline-view', [ElectionManagementController::class, 'timelineView'])
            ->name('elections.timeline-view')
            ->can('manageSettings', 'election');

        // ── Timeline Edit (Administration + Nomination phases only) ──────────────────
        Route::middleware(['election.state:configure_election'])->group(function () {
            Route::get('/timeline', [ElectionManagementController::class, 'timeline'])
                ->name('elections.timeline')
                ->can('manageSettings', 'election');

            Route::patch('/timeline', [ElectionManagementController::class, 'updateTimeline'])
                ->name('elections.update-timeline')
                ->can('manageSettings', 'election');
        });
    });

// Test routes for voter slug system (Phase 1)
Route::middleware(['auth'])->group(function () {
    Route::get('/test/generate-slug', function () {
        $user = auth()->user();
        $slugService = new \App\Services\VoterSlugService();
        $slug = $slugService->generateSlugForUser($user);

        return response()->json([
            'success' => true,
            'slug' => $slug->slug,
            'expires_at' => $slug->expires_at,
            'test_link' => route('test.voter.page', ['vslug' => $slug->slug])
        ]);
    })->name('test.generate-slug');
});

Route::prefix('v/{vslug}')->middleware([\Illuminate\Routing\Middleware\SubstituteBindings::class, 'voter.slug.window'])->group(function () {
    Route::get('test', function (\Illuminate\Http\Request $request) {
        $voter = $request->attributes->get('voter');
        $voterSlug = $request->attributes->get('voter_slug');

        return response()->json([
            'success' => true,
            'message' => 'Voter slug validation working!',
            'voter_id' => $voter->id,
            'voter_name' => $voter->name,
            'slug_expires_at' => $voterSlug->expires_at,
            'time_remaining' => $voterSlug->expires_at->diffForHumans()
        ]);
    })->name('test.voter.page');
});

// Step-based voter workflow routes (test routes for development)
// Route::prefix('v/{vslug}')->middleware(['voter.slug.window', 'voter.step.order'])->group(function () {
//     // Step 1: Code creation
//     Route::get('code/create', function (\Illuminate\Http\Request $request) {
//         $voter = $request->attributes->get('voter');
//         $voterSlug = $request->attributes->get('voter_slug');

//         return response()->json([
//             'step' => 1,
//             'message' => 'Step 1: Code creation page',
//             'current_step' => $voterSlug->current_step,
//             'voter_id' => $voter->id
//         ]);
//     })->name('voter.code.create');

//     // Step 2: Agreement
//     Route::get('agreement', function (\Illuminate\Http\Request $request) {
//         $voter = $request->attributes->get('voter');
//         $voterSlug = $request->attributes->get('voter_slug');

//         return response()->json([
//             'step' => 2,
//             'message' => 'Step 2: Agreement page',
//             'current_step' => $voterSlug->current_step,
//             'voter_id' => $voter->id
//         ]);
//     })->name('voter.agreement');

//     // Step 3: Vote creation
//     Route::get('vote/create', function (\Illuminate\Http\Request $request) {
//         $voter = $request->attributes->get('voter');
//         $voterSlug = $request->attributes->get('voter_slug');

//         return response()->json([
//             'step' => 3,
//             'message' => 'Step 3: Vote creation page',
//             'current_step' => $voterSlug->current_step,
//             'voter_id' => $voter->id
//         ]);
//     })->name('voter.vote.create');

//     // Step 4: Vote verification
//     Route::get('vote/verify', function (\Illuminate\Http\Request $request) {
//         $voter = $request->attributes->get('voter');
//         $voterSlug = $request->attributes->get('voter_slug');

//         return response()->json([
//             'step' => 4,
//             'message' => 'Step 4: Vote verification page',
//             'current_step' => $voterSlug->current_step,
//             'voter_id' => $voter->id
//         ]);
//     })->name('voter.vote.verify');

//     // Step 5: Vote submission
//     Route::get('vote/submit', function (\Illuminate\Http\Request $request) {
//         $voter = $request->attributes->get('voter');
//         $voterSlug = $request->attributes->get('voter_slug');

//         return response()->json([
//             'step' => 5,
//             'message' => 'Step 5: Vote submission page',
//             'current_step' => $voterSlug->current_step,
//             'voter_id' => $voter->id
//         ]);
//     })->name('voter.vote.submit');
// });

// ============================================================================
// STEP 1: CODE CREATION (Allow expired voter slugs - CodeController extends them)
// ============================================================================
// NOTE: Does NOT include 'voter.slug.window' middleware because expired slugs
// should be allowed here so CodeController can extend them. The CodeController
// regenerates the code and extends the voter slug window.
// Middleware chain:
// 1. SubstituteBindings - Route model binding
// 2. voter.slug.verify - Verify slug exists, belongs to user, is active
// 3. voter.slug.consistency - Validate election exists and org consistency
// 4. ensure.election.voter - Check voter membership
// 5. vote.organisation - Organisation security
Route::prefix('v/{vslug}')->middleware([\Illuminate\Routing\Middleware\SubstituteBindings::class, 'voter.slug.verify', 'voter.slug.consistency', 'ensure.election.voter', 'vote.organisation'])->group(function () {
    Route::get('code/create', [CodeController::class, 'create'])->name('slug.code.create');
    Route::post('code', [CodeController::class, 'store'])->name('slug.code.store');
});

// ============================================================================
// STEPS 2-5: FULL VOTING WORKFLOW (with expiration enforcement)
// ============================================================================
// Middleware chain:
// 1. SubstituteBindings - Route model binding
// 2. voter.slug.verify - Verify slug exists, belongs to user, is active
// 3. voter.slug.window - Check expiration ← NOW enforced here
// 4. voter.slug.consistency - Validate election exists and org consistency
// 5. voter.step.order - Ensure step progression
// 6. vote.eligibility - Check voting rights
// 7. validate.voting.ip - IP restrictions (if enabled)
// 8. vote.organisation - Organisation security
Route::prefix('v/{vslug}')->middleware([\Illuminate\Routing\Middleware\SubstituteBindings::class, 'voter.slug.verify', 'voter.slug.window', 'voter.slug.consistency', 'ensure.election.voter', 'voter.step.order', 'vote.eligibility', 'validate.voting.ip', 'vote.organisation', 'throttle:10,1'])->group(function () {

    // Step 2: Agreement (using existing CodeController)
    Route::get('vote/agreement', [CodeController::class, 'showAgreement'])->name('slug.code.agreement');
    Route::post('code/agreement', [CodeController::class, 'submitAgreement'])->name('slug.code.agreement.submit');

    // Step 3: Vote creation (using existing VoteController)
    Route::get('vote/create', [VoteController::class, 'create'])->name('slug.vote.create');
    Route::post('vote/submit', [VoteController::class, 'first_submission'])->name('slug.vote.submit');

    // Step 4: Vote verification (using existing VoteController)
    Route::get('vote/verify', [VoteController::class, 'verify'])->name('slug.vote.verify');
    Route::post('vote/verify', [VoteController::class, 'store'])->name('slug.vote.store');

    // Step 5: Final submission page
    Route::get('vote/complete', function (\Illuminate\Http\Request $request) {
        $voter = $request->attributes->get('voter');
        $voterSlug = $request->attributes->get('voter_slug');

        return Inertia::render('Vote/Complete', [
            'voter' => $voter,
            'slug' => $voterSlug->slug
        ]);
    })->name('slug.vote.complete');
});

// ============================================================================
// DEMO ELECTION WORKFLOW ROUTES - IDENTICAL to Real Voting but with Demo Models
// ============================================================================
// Demo elections have the SAME workflow as real voting:
// - No IP restrictions (allows testing from same IP)
// - Allows multiple test votes
// - Uses demo models (DemoCode, DemoVote, DemoCandidate, DemoPost)
// - Same validation, same steps, same UI feeling

// Non-slug demo code routes (for backward compatibility and convenience)
Route::middleware(['auth:sanctum', 'verified', 'election', 'vote.organisation', 'election.demo'])->group(function () {
    Route::get('demo/code/create', [DemoCodeController::class, 'create'])->name('demo-code.create');
    Route::post('demo/codes', [DemoCodeController::class, 'store'])->name('demo-code.store');
    Route::get('demo/code/agreement', [DemoCodeController::class, 'showAgreement'])->name('demo-code.agreement');
    Route::post('demo/code/agreement', [DemoCodeController::class, 'submitAgreement'])->name('demo-code.agreement.submit');

    Route::get('demo/vote/create', [DemoVoteController::class, 'create'])->name('demo-vote.create');
    Route::post('demo/vote/submit', [DemoVoteController::class, 'first_submission'])->name('demo-vote.submit');
    Route::get('demo/vote/verify', [DemoVoteController::class, 'verify'])->name('demo-vote.verify');
    Route::post('demo/vote/final', [DemoVoteController::class, 'store'])->name('demo-vote.store');
      
   Route::post('/demo/vote/submit-code', [DemoVoteController::class, 'submitCodeToViewVote'])
        ->name('demo.vote.submit_code_to_view_vote');
   Route::get('/demo-vote/show/{vote_id}', [DemoVoteController::class, 'show'])->name('demo.vote.show');
 
    Route::get('demo/vote/thank-you', [DemoVoteController::class, 'thankYou'])->name('demo-vote.thank-you');
    Route::get('demo/vote/verify-show', [DemoVoteController::class, 'demo_verify_to_show'])->name('demo-vote.verify_to_show');
});

// Slug-based demo election routes
// Same middleware chain as real voting for consistency
Route::prefix('v/{vslug}')->middleware(['auth:sanctum', 'verified', \Illuminate\Routing\Middleware\SubstituteBindings::class, 'voter.slug.verify', 'voter.slug.window', 'voter.slug.consistency', 'voting.code.window', 'voter.step.order', 'vote.eligibility', 'vote.organisation'])->group(function () {
    // Demo elections: IDENTICAL workflow to real voting
    Route::middleware(['election.demo'])->group(function () {
        // ============ CODE VERIFICATION STEPS ============
        // STEP 1: Show code entry form
        Route::get('demo-code/create', [DemoCodeController::class, 'create'])->name('slug.demo-code.create');

        // STEP 1.5: Verify the entered code
        Route::post('demo-code', [DemoCodeController::class, 'store'])->name('slug.demo-code.store');

        // STEP 2: Show agreement/consent page
        Route::get('demo-code/agreement', [DemoCodeController::class, 'showAgreement'])->name('slug.demo-code.agreement');

        // STEP 2.5: Submit agreement and proceed
        Route::post('demo-code/agreement', [DemoCodeController::class, 'submitAgreement'])->name('slug.demo-code.agreement.submit');

        // ============ VOTING STEPS ============
        // STEP 3: Show voting form with posts and candidates
        Route::get('demo-vote/create', [DemoVoteController::class, 'create'])->name('slug.demo-vote.create');

        // STEP 4: Submit votes (first submission with validation)
        Route::post('demo-vote/submit', [DemoVoteController::class, 'first_submission'])->name('slug.demo-vote.submit');

        // STEP 5: Verify votes before final submission
        Route::get('demo-vote/verify', [DemoVoteController::class, 'verify'])->name('slug.demo-vote.verify');

        // STEP 6: Final vote storage
        Route::post('demo-vote/final', [DemoVoteController::class, 'store'])->name('slug.demo-vote.store');
        // STEP 7: Thank you page
        Route::get('demo-vote/thank-you', [DemoVoteController::class, 'thankYou'])->name('slug.demo-vote.thank-you');

        // STEP 7.5: Verify/show page (after vote is saved)
        Route::get('demo-vote/verify-show', [DemoVoteController::class, 'demo_verify_to_show'])->name('slug.demo-vote.verify_to_show');
    });
});

// ============================================================================
// PUBLIC DEMO: Full 5-step voting without login (anonymous visitors)
// No auth middleware — identity is tracked via PublicDemoSession (session_token).
// ============================================================================
Route::prefix('public-demo')->name('public-demo.')->group(function () {
    // Tutorial / Guide — MUST be before {publicDemoSession} to avoid slug conflict
    Route::get('/guide', [\App\Http\Controllers\Demo\PublicDemoController::class, 'guide'])->name('guide');

    // Entry point — creates or reuses a PublicDemoSession, redirects to Step 1
    Route::get('/start', [\App\Http\Controllers\Demo\PublicDemoController::class, 'start'])->name('start');

    // Step 1: Code entry (code displayed on screen)
    Route::get('/{publicDemoSession}/code', [\App\Http\Controllers\Demo\PublicDemoController::class, 'codeShow'])->name('code.show');
    Route::post('/{publicDemoSession}/code', [\App\Http\Controllers\Demo\PublicDemoController::class, 'codeVerify'])->name('code.verify');

    // Step 2: Agreement
    Route::get('/{publicDemoSession}/agreement', [\App\Http\Controllers\Demo\PublicDemoController::class, 'agreementShow'])->name('agreement.show');
    Route::post('/{publicDemoSession}/agreement', [\App\Http\Controllers\Demo\PublicDemoController::class, 'agreementSubmit'])->name('agreement.submit');

    // Step 3: Ballot selection
    Route::get('/{publicDemoSession}/vote', [\App\Http\Controllers\Demo\PublicDemoController::class, 'voteShow'])->name('vote.show');
    Route::post('/{publicDemoSession}/vote', [\App\Http\Controllers\Demo\PublicDemoController::class, 'voteSubmit'])->name('vote.submit');

    // Step 4: Review & confirm
    Route::get('/{publicDemoSession}/verify', [\App\Http\Controllers\Demo\PublicDemoController::class, 'verifyShow'])->name('verify.show');
    Route::post('/{publicDemoSession}/verify', [\App\Http\Controllers\Demo\PublicDemoController::class, 'verifyConfirm'])->name('verify.confirm');

    // Step 5: Thank you
    Route::get('/{publicDemoSession}/thank-you', [\App\Http\Controllers\Demo\PublicDemoController::class, 'thankYou'])->name('thankyou');

    // Result: Enter receipt hash to view voted candidates
    Route::get('/{publicDemoSession}/result', [\App\Http\Controllers\Demo\PublicDemoController::class, 'resultShow'])->name('result');
    Route::post('/{publicDemoSession}/result', [\App\Http\Controllers\Demo\PublicDemoController::class, 'resultVerify'])->name('result.verify');
});

// ============================================================================
// DEMO RESULTS PAGES - MODE 1 (Global) & MODE 2 (Organisation)
// ============================================================================
// MODE 1: Global demo results (organisation_id = NULL) - Public demo view
Route::middleware(['auth:sanctum', 'verified'])
    ->get('/demo/global/result', [DemoResultController::class, 'indexGlobal'])
    ->name('demo-result.global');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/demo/global/result/download-pdf', [DemoResultController::class, 'downloadGlobalPDF'])
    ->name('demo-result.global.download-pdf');

// MODE 2: Organisation-scoped demo results (organisation_id = X)
Route::middleware(['auth:sanctum', 'verified'])
    ->get('/demo/result', [DemoResultController::class, 'index'])
    ->name('demo-result.index');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/demo/result/download-pdf', [DemoResultController::class, 'downloadPDF'])
    ->name('demo-result.download-pdf');

// Verification endpoints for demo results
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/api/demo/verify-results/{postId}', [DemoResultController::class, 'verifyResults']);
    Route::get('/api/demo/statistical-verification/{postId}', [DemoResultController::class, 'statisticalVerification']);
});

// Admin routes for election approval workflow
Route::prefix('admin')->middleware(['auth:sanctum', 'verified'])->group(function () {
    // Pending elections list
    Route::get('/elections/pending', [\App\Http\Controllers\Admin\AdminElectionController::class, 'pending'])->name('admin.elections.pending');

    // Approve an election
    Route::post('/elections/{election:slug}/approve', [\App\Http\Controllers\Admin\AdminElectionController::class, 'approve'])->name('admin.elections.approve');

    // Reject an election
    Route::post('/elections/{election:slug}/reject', [\App\Http\Controllers\Admin\AdminElectionController::class, 'reject'])->name('admin.elections.reject');
});

// Admin routes for election committee
Route::prefix('admin')->middleware(['auth:sanctum', 'verified', 'committee.member'])->group(function () {
    // Voting security dashboard
    Route::get('voting-security', [VotingSecurityController::class, 'dashboard'])->name('admin.voting.security.dashboard');

    // Security violations
    Route::get('voting-security/violations', [VotingSecurityController::class, 'violations'])->name('admin.voting.security.violations');

    // User security audit
    Route::get('voting-security/audit/{user}', [VotingSecurityController::class, 'auditUser'])->name('admin.voting.security.audit');

    // Security enforcement
    Route::post('voting-security/enforce/{user}', [VotingSecurityController::class, 'enforceSecurity'])->name('admin.voting.security.enforce');

    // Recovery slug generation (NEW)
    Route::post('voting-security/recovery/{user}', [VotingSecurityController::class, 'generateRecoverySlug'])->name('admin.voting.security.recovery');

    // Emergency lockdown
    Route::post('voting-security/lockdown/{user}', [VotingSecurityController::class, 'emergencyLockdown'])->name('admin.voting.security.lockdown');

    // Real-time monitoring
    Route::get('voting-security/monitor', [VotingSecurityController::class, 'monitoringData'])->name('admin.voting.security.monitor');

    // Security report
    Route::get('voting-security/report', [VotingSecurityController::class, 'generateReport'])->name('admin.voting.security.report');
});

// DEBUG: Test with TenantContext middleware applied
Route::get('/elections/{election:slug}/debug-tenant', function (\App\Models\Election $election) {
    $user = auth()->user();
    return response()->json([
        'user_id' => $user->id,
        'user_email' => $user->email,
        'user_organisation_id' => $user->organisation_id,
        'session_organisation_id' => session('current_organisation_id'),
        'election_id' => $election->id,
        'election_slug' => $election->slug,
        'election_state' => $election->state,
        'election_org_id' => $election->organisation_id,
        'can_manage' => $user->can('manageSettings', $election),
    ]);
})->middleware(['auth', 'verified', \App\Http\Middleware\TenantContext::class])->name('election.debug-tenant');

// DEBUG: Direct authorization test
Route::get('/elections/{election:slug}/auth-test', function (\App\Models\Election $election) {
    $user = auth()->user();
    if (!$user) {
        return response()->json(['error' => 'Not authenticated'], 401);
    }

    return response()->json([
        'user_id' => $user->id,
        'user_email' => $user->email,
        'election_id' => $election->id,
        'election_slug' => $election->slug,
        'election_state' => $election->state,
        'can_manage' => $user->can('manageSettings', $election),
        'allows_action' => $election->allowsAction('manage_settings'),
        'is_chief' => \App\Models\ElectionOfficer::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('role', 'chief')
            ->where('status', 'active')
            ->exists(),
        'is_org_owner' => \App\Models\UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $election->organisation_id)
            ->where('role', 'owner')
            ->exists(),
    ]);
})->middleware(['auth', 'verified'])->name('election.auth-test');
