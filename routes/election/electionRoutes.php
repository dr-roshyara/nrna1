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
use App\Http\Controllers\Election\PublisherAuthorizationController; 
use App\Http\Controllers\Admin\ElectionCommitteeController;
use App\Http\Controllers\Election\PublisherController;
use App\Http\Controllers\Election\VerificationController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Election\ElectionController;
use App\Http\Controllers\Admin\ElectionConfigController;

use App\Http\Controllers\Admin\ElectionAdminController;

/*
|--------------------------------------------------------------------------
| Election Administration Routes
|--------------------------------------------------------------------------
| These routes handle the CRUD operations for elections
*/


// ========================================
// ✅ ELECTION CONFIGURATION ROUTES (FIXED)
// ========================================
Route::middleware(['auth:sanctum', 'verified', 'role:Superadmin'])->prefix('admin')->group(function () {
    
    // Election Management Dashboard
    Route::get('/elections', [ElectionConfigController::class, 'index'])
        ->name('admin.elections.index');
    
    // Create New Election
    Route::get('/elections/create', [ElectionConfigController::class, 'create'])
        ->name('admin.elections.create');
    
    Route::post('/elections', [ElectionConfigController::class, 'store'])
        ->name('admin.elections.store');
    
    // View Election Details
    Route::get('/elections/{election}', [ElectionConfigController::class, 'show'])
        ->name('admin.elections.show');
    
    // Additional election management routes
    Route::put('/elections/{election}/timeline', [ElectionConfigController::class, 'updateTimeline'])
        ->name('admin.elections.timeline.update');
    
    Route::post('/elections/{election}/transition', [ElectionConfigController::class, 'transitionPhase'])
        ->name('admin.elections.transition');
        
    // Publisher management
    Route::get('/publishers', [PublisherController::class, 'index'])
        ->name('admin.publishers.index');
    
    Route::post('/publishers', [PublisherController::class, 'store'])
        ->name('admin.publishers.store');
    
    Route::put('/publishers/{publisher}', [PublisherController::class, 'update'])
        ->name('admin.publishers.update');
        
    // System monitoring
    Route::get('/system-status', [SystemController::class, 'status'])
        ->name('admin.system.status');
    
    Route::get('/logs', [LogController::class, 'index'])
        ->name('admin.logs.index');
});

// ========================================
// ELECTION DASHBOARD
// ========================================
Route::middleware(['auth:sanctum', 'verified'])->get('/election', function(){
    return Inertia::render('Dashboard/ElectionDashboard', []);
})->name('election.dashboard');

// ========================================
// VOTER MANAGEMENT
// ========================================
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Voter list and management
    Route::get('/voters/index', [VoterlistController::class, 'index'])->name('voters.index');
    Route::get('/voters', [VoterlistController::class, 'index'])->name('voters.index');
    Route::get('/voters/{id}', [VoterlistController::class, 'show'])->name('voters.show');
    
    // Committee member actions
    Route::post('/voters/{id}/approve', [VoterlistController::class, 'approveVoter'])->name('voters.approve');
    Route::post('/voters/{id}/reject', [VoterlistController::class, 'rejectVoter'])->name('voters.reject');
});

// ========================================
// CANDIDACY MANAGEMENT
// ========================================
Route::get('candidacy/create', [CandidacyController::class, 'create'])->name('candidacy.create');
Route::post('candidacies', [CandidacyController::class, 'store'])->name('candidacy.store');
Route::get('candidacies/index', [CandidacyController::class, 'index'])->name('candidacy.index');
Route::get('candidacy/update', [CandidacyController::class, 'update'])->name('candidacy.update');
Route::get('candidacies/assign', [CandidacyController::class, 'assign'])->name('candidacy.assign');

// ========================================
// VOTING PROCESS
// ========================================
Route::middleware(['auth:sanctum', 'verified', 'vote.eligibility'])->group(function () {
    // Code generation and agreement
    Route::get('/code/create', [CodeController::class, 'create'])->name('code.create');
    Route::post('/codes', [CodeController::class, 'store'])->name('code.store');
    Route::get('/vote/agreement', [CodeController::class, 'showAgreement'])->name('code.agreement');
    Route::post('/code/agreement', [CodeController::class, 'submitAgreement'])->name('code.agreement.submit');
    
    // Voting process
    Route::get('/vote/create', [VoteController::class, 'create'])->name('vote.create');
    Route::post('/vote/submit', [VoteController::class, 'first_submission'])->name('vote.submit');
    Route::get('/vote/cast', [VoteController::class, 'cast_vote'])->name('vote.cast');
    Route::post('/vote/submit_seleccted', [VoteController::class, 'second_submission'])->name('vote.submit_seleccted');
    Route::get('/vote/verify', [VoteController::class, 'verify'])->name('vote.verify');
    Route::post('/votes', [VoteController::class, 'store'])->name('vote.store');
});

// Vote verification routes (authenticated users)
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/vote/verify_to_show', [VoteController::class, 'verify_to_show'])->name('vote.verify_to_show');
    Route::post('/vote/submit_code_to_view_vote', [VoteController::class, 'submit_code_to_view_vote'])->name('vote.submit_code_to_view_vote');
    Route::post('/verify_final_vote', [VoteController::class, 'verify_final_vote'])->name('vote.verify_final_vote');
    Route::get('/votes/index', [VoteController::class, 'index'])->name('vote.index');
    Route::get('/vote/show/{vote_id}', [VoteController::class, 'show'])->name('vote.show');
});

// Vote denial route (public)
Route::get('/vote/denied', function() {
    return Inertia::render('Vote/VoteDenied', [
        'title_english' => 'Access Denied',
        'title_nepali' => 'पहुँच अस्वीकृत',
        'message_english' => 'Your voting access has been restricted.',
        'message_nepali' => 'तपाईंको मतदान पहुँच प्रतिबन्धित गरिएको छ।',
    ]);
})->name('vote.denied');

// ========================================
// PUBLISHER AUTHORIZATION
// ========================================
Route::middleware(['auth', 'publisher'])->group(function () {
    Route::get('/publisher/authorize', [PublisherAuthorizationController::class, 'index'])
        ->name('publisher.authorize.index');
    
    Route::post('/publisher/authorize', [PublisherAuthorizationController::class, 'submitAuthorization'])
        ->name('publisher.authorize.submit');
    
    Route::get('/publisher/status', [PublisherAuthorizationController::class, 'status'])
        ->name('publisher.status');
});

// ========================================
// RESULTS AND REPORTING
// ========================================
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/election/result', [ResultController::class, 'index'])->name('result.index');
    Route::get('/results', [ElectionResultController::class, 'index'])->name('election-result.index');
    Route::get('/result/index', [ElectionResultController::class, 'index'])->name('result.index');
});

// API routes for results
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/api/verify-results/{postId}', [ResultController::class, 'verifyResults']);
    Route::get('/api/statistical-verification/{postId}', [ResultController::class, 'statisticalVerification']);
    Route::get('/api/authorization-progress', [ElectionResultController::class, 'getAuthorizationProgress']);
    Route::get('/api/authorization-progress', [PublisherAuthorizationController::class, 'progress'])
        ->name('api.authorization.progress');
});

// ========================================
// DELEGATE VOTING (SEPARATE SYSTEM)
// ========================================
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/deligatecode/create', [DeligateCodeController::class, 'create'])->name('deligatecode.create');
    Route::post('/deligatecodes', [DeligateCodeController::class, 'store'])->name('deligatecode.store');
    Route::get('deligatecandidacies/index', [DeligateCandidacyController::class, 'index'])->name('deligatecandidacy.index');
    Route::get('deligatecandidacy/update', [DeligateCandidacyController::class, 'update'])->name('deligatecandidacy.update');
    Route::get('deligatevote/create', [DeligateVoteController::class, 'create'])->name('deligatevote.create');
    Route::post('/deligatevote/submit', [DeligateVoteController::class, 'first_submission'])->name('deligatevote.submit');
    Route::get('/deligatevote/verify', [DeligateVoteController::class, 'verify'])->name('deligatevote.verifiy');
    Route::post('/deligatevotes', [DeligateVoteController::class, 'store'])->name('deligatevote.store');
    Route::get('/deligatevotes/index', [DeligateVoteController::class, 'index'])->name('deligatevote.index');
    Route::get('/deligatevote/show', [DeligateVoteController::class, 'show'])->name('deligatevote.show');
    Route::get('/deligatevote/count', [DeligateVoteController::class, 'count'])->name('deligatevote.count');
    Route::get('/deligatevote/result', [DeligateVoteController::class, 'result'])->name('deligatevote.result');
});

// ======================================== 
// POSTS AND COMMITTEE
// ========================================
Route::get('posts/index', [PostController::class, 'index'])->name('post.index');
Route::get('posts/assign', [PostController::class, 'assign'])->name('post.assign');

Route::get('/election/committee', function () {
    return Inertia::render('Election/ElectionCommittee');
})->name('election.committee');

// Miscellaneous routes
Route::get('vote/thankyou', [VoteController::class, 'thankyou'])->name('vote.thankyou');

// Admin IP statistics (requires admin permission)
Route::middleware(['auth:sanctum', 'verified', 'role:Superadmin'])->group(function () {
    Route::get('/admin/ip-stats', [CodeController::class, 'getIPStatistics'])->name('admin.ip.stats');
});