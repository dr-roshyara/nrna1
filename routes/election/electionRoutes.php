<?php
use Inertia\Inertia;
use App\Http\Controllers\CandidacyController;
use App\Http\Controllers\VoterlistController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\DeligateCandidacyController;
use App\Http\Controllers\DeligateVoteController;
use App\Http\Controllers\DeligateCodeController;
use App\Http\Controllers\Election\ElectionResultController;

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublisherAuthorizationController;
use App\Http\Controllers\Admin\ElectionCommitteeController;

Route::middleware(['auth:sanctum', 'verified']) ->get('/election', function(){
    return Inertia::render('Dashboard/ElectionDashboard', [

    ]);
})->name('election.dashboard');

//voters
Route::middleware(['auth:sanctum', 'verified'])
        ->get('/voters/index', [VoterlistController::class, 'index'])->name('voters.index');

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
   Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility'])->get('/code/create', [CodeController::class, 'create'])->name('code.create');
   Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->post('/codes', [CodeController::class, 'store'])->name('code.store');
   Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->get('/vote/agreement', [CodeController::class, 'showAgreement'])->name('code.agreement');
   Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->post('/code/agreement', [CodeController::class, 'submitAgreement'])->name('code.agreement.submit');
   
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
      Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->get('/vote/create', [VoteController::class, 'create'])->name('vote.create');

    //submit I accept sh
       Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->post('/vote/submit', [VoteController::class, 'first_submission'])->name('vote.submit');
  
    //After successful open the vote ballet now 
       Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->get('/vote/cast', [VoteController::class, 'cast_vote'])->name('vote.cast');
    //submit the vote with selected candidates 
      Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility'])  ->post('/vote/submit_seleccted', [VoteController::class, 'second_submission'])->name('vote.submit_seleccted');
    
    //verify
       Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->get('/vote/verify', [VoteController::class, 'verify'])->name('vote.verify');

       Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility']) ->post('/votes', [VoteController::class, 'store'])->name('vote.store');

    //
    Route::middleware(['auth:sanctum', 'verified'])->get('/vote/verify_to_show', [VoteController::class, 'verify_to_show'])->name('vote.verify_to_show');

    Route::middleware(['auth:sanctum', 'verified'])->post('/vote/submit_code_to_view_vote', [VoteController::class, 'submit_code_to_view_vote'])->name('vote.submit_code_to_view_vote');

    Route::middleware(['auth:sanctum', 'verified']) ->post('/verify_final_vote' , [VoteController::class, 'verify_final_vote'])->name('vote.verify_final_vote');

   Route::middleware(['auth:sanctum', 'verified'])->get('/votes/index', [VoteController::class, 'index'])->name('vote.index');
//    Route::middleware(['auth:sanctum', 'verified'])->get('/vote/show', [VoteController::class, 'show'])->name('vote.show');
   Route::middleware(['auth:sanctum', 'verified'])->get('/vote/show/{vote_id}', [VoteController::class, 'show'])->name('vote.show');

   Route::middleware(['auth:sanctum', 'verified']) ->get('/election/result', [ResultController  ::class, 'index'])->name('result.index');

// Vote verification and display routes
   Route::middleware(['auth:sanctum', 'verified']) ->get('/results', [ElectionResultController  ::class, 'index'])->name('election-result.index');


// Voter management routes (require authentication and committee member permissions)
 
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    
    // Voter list and management (use names that match your Vue component)
    Route::get('/voters', [VoterlistController::class, 'index'])->name('voters.index');
    Route::get('/voters/{id}', [VoterlistController::class, 'show'])->name('voters.show');
    
    // Committee member actions (these route names match your Vue component calls)
    Route::post('/voters/{id}/approve', [VoterlistController::class, 'approveVoter'])->name('voters.approve');
    Route::post('/voters/{id}/reject', [VoterlistController::class, 'rejectVoter'])->name('voters.reject');
});

/*************************************************************************************** */
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/election/result', [ResultController::class, 'index'])->name('result.index');
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

// Result publication routes
Route::get('/result/index', [ElectionResultController::class, 'index'])->name('result.index');
Route::get('/api/authorization-progress', [ElectionResultController::class, 'getAuthorizationProgress']);
 
// API routes for real-time updates

// Admin routes (committee only)
Route::middleware(['auth', 'role:election-committee'])->group(function () {
    Route::post('/admin/verify-results', [ElectionResultController::class, 'verifyResults']);
    Route::post('/admin/emergency-publish', [ElectionResultController::class, 'emergencyPublish']);
});


// // ========================================
// // 1. PUBLIC ROUTES (No middleware)
// // ========================================
// Route::get('/', [ElectionController::class, 'dashboard'])->name('electiondashboard');
// Route::get('/result/index', [ElectionResultController::class, 'index'])->name('result.index');

// // ========================================
// // 2. AUTHENTICATED ROUTES (Basic auth required)
// // ========================================
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [ElectionController::class, 'dashboard'])->name('dashboard');
//     Route::get('/profile', [UserController::class, 'profile'])->name('profile');
// });

// // ========================================
// // 3. VOTER ROUTES (Role: voter)
// // ========================================
// Route::middleware(['auth', 'role:voter'])->group(function () {
//     Route::get('/vote/create', [VoteController::class, 'create'])->name('vote.create');
//     Route::post('/vote/store', [VoteController::class, 'store'])->name('vote.store');
//     Route::get('/vote/verify_to_show', [VoteController::class, 'verifyToShow'])->name('vote.verify_to_show');
// });


//publisher : 
// ========================================
// 4. PUBLISHER ROUTES (Custom publisher middleware)
// ========================================
Route::middleware(['auth', 'publisher'])->group(function () {
    Route::get('/publisher/authorize', [PublisherAuthorizationController::class, 'index'])
        ->name('publisher.authorize.index');
    
    Route::post('/publisher/authorize', [PublisherAuthorizationController::class, 'authorize'])
        ->name('publisher.authorize.submit');
    
    Route::get('/publisher/status', [PublisherAuthorizationController::class, 'status'])
        ->name('publisher.status');
});

// Real-time progress API
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/api/authorization-progress', [PublisherAuthorizationController::class, 'progress'])
        ->name('api.authorization.progress');
});

// Committee routes
Route::middleware(['auth:sanctum', 'verified', 'role:election-committee,super-admin'])->group(function () {
    Route::post('/committee/election/start-sealing', [PublisherAuthorizationController::class, 'startSealing'])
        ->name('committee.election.start-sealing');
});
// Publisher authorization routes


// ========================================
// 5. ELECTION COMMITTEE ROUTES (Role: election-committee)
// ========================================
Route::middleware(['auth', 'role:election-committee,super-admin'])->prefix('admin')->group(function () {
    // Result verification and publishing
    Route::post('/verify-results', [ElectionResultController::class, 'verifyResults'])
        ->name('admin.verify.results');
    
    Route::post('/emergency-publish', [ElectionResultController::class, 'emergencyPublish'])
        ->name('admin.emergency.publish');
    
    // Publisher management
    Route::get('/publishers', [PublisherController::class, 'index'])
        ->name('admin.publishers.index');
    
    Route::post('/publishers', [PublisherController::class, 'store'])
        ->name('admin.publishers.store');
    
    Route::put('/publishers/{publisher}', [PublisherController::class, 'update'])
        ->name('admin.publishers.update');
});
// ========================================
// 6. VERIFICATION COMMITTEE ROUTES (Role: verification-committee)
// ========================================
Route::middleware(['auth', 'role:verification-committee,election-committee,super-admin'])->group(function () {
    Route::get('/verification/dashboard', [VerificationController::class, 'dashboard'])
        ->name('verification.dashboard');
    
    Route::post('/verification/approve', [VerificationController::class, 'approve'])
        ->name('verification.approve');
});
// ========================================
// 7. PERMISSION-BASED ROUTES (Using permissions instead of roles)
// ========================================
Route::middleware(['auth', 'permission:manage-publishers'])->group(function () {
    Route::delete('/admin/publishers/{publisher}', [PublisherController::class, 'destroy'])
        ->name('admin.publishers.destroy');
    
    Route::post('/admin/publishers/{publisher}/reset-password', [PublisherController::class, 'resetPassword'])
        ->name('admin.publishers.reset_password');
});

Route::middleware(['auth', 'permission:emergency-publish'])->group(function () {
    Route::post('/admin/force-publish', [ElectionResultController::class, 'forcePublish'])
        ->name('admin.force.publish');
});

// ========================================
// 8. MULTIPLE ROLE OPTIONS (User needs ANY of these roles)
// ========================================
Route::middleware(['auth', 'role:election-committee,super-admin,technical-admin'])->group(function () {
    Route::get('/admin/system-status', [SystemController::class, 'status'])
        ->name('admin.system.status');
    
    Route::get('/admin/logs', [LogController::class, 'index'])
        ->name('admin.logs.index');
});
// ========================================
// 9. API ROUTES WITH ROLE PROTECTION
// ========================================
Route::middleware(['auth:sanctum', 'role:election-committee'])->prefix('api')->group(function () {
    Route::get('/authorization-progress', [ElectionResultController::class, 'getAuthorizationProgress']);
    Route::post('/start-authorization', [ElectionController::class, 'startAuthorization']);
});

// ========================================
// 10. COMPLEX MIDDLEWARE COMBINATIONS
// ========================================

// Route that requires BOTH a role AND a permission
Route::middleware(['auth', 'role:election-committee', 'permission:manage-elections'])->group(function () {
    Route::post('/admin/elections', [ElectionController::class, 'store'])
        ->name('admin.elections.store');
});

// Route with custom logic in controller (alternative approach)
// Route::middleware(['auth'])->group(function () {
//     Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
//         ->name('admin.dashboard')
//         ->middleware(function ($request, $next) {
//             // Custom authorization logic
//             if (!$request->user()->hasAnyRole(['election-committee', 'super-admin'])) {
//                 abort(403, 'Access denied');
//             }
//             return $next($request);
//         });
// });

// ========================================
// 11. CONDITIONAL ROUTES BASED ON SETTINGS
// ========================================
Route::middleware(['auth'])->group(function () {
    // Only show this route if emergency publishing is enabled
    Route::get('/admin/emergency-controls', function () {
        if (!\App\Models\Setting::isEnabled('emergency_controls_enabled')) {
            abort(404);
        }
        return view('admin.emergency-controls');
    })->middleware('role:super-admin');
});

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

