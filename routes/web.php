<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
//controllers
use App\Http\Controllers\PostController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\MakeurlController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\RobotsController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\Api\DemoSetupController;
use App\Http\Controllers\NewsletterUnsubscribeController;

//voting
use App\Http\Controllers\CandidacyController;
use App\Http\Controllers\VoterlistController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\MessageController;
// use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\DeligateCandidacyController;
use App\Http\Controllers\DeligateVoteController;
use App\Http\Controllers\DeligateCodeController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Auth;
//Models
use Inertia\Inertia;
use App\Models\Message;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\OpenionController;

use App\Http\Controllers\ElectionController;
use App\Http\Controllers\Election\ElectionManagementController;
use App\Models\VoterSlug;
use App\Models\DemoVoterSlug;

// Role-based dashboard controllers (NEW)
use App\Http\Controllers\RoleSelectionController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CommissionDashboardController;
use App\Http\Controllers\VoterDashboardController;
use App\Http\Controllers\WelcomeDashboardController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\Organisations\MemberImportController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Import\OrganisationUserImportController;
use App\Http\Controllers\VoterInvitationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Route Model Binding
 * Register implicit model binding for VoterSlug
 */
// 🔥 DIGITAL OCEAN FIX: Enhanced route binding with retry logic
Route::bind('vslug', function (string $value) {
    \Log::info('🔍 Route binding lookup', ['vslug' => $value, 'connection' => \DB::connection()->getName()]);

    // Try with retry for Digital Ocean replication lag
    $voterSlug = null;
    $attempts = 0;
    $maxAttempts = 3;

    while (!$voterSlug && $attempts < $maxAttempts) {
        if ($attempts > 0) {
            \Log::info('🔄 Retry attempt ' . ($attempts + 1), ['vslug' => $value]);
            sleep(1); // Wait 1 second between retries
            \DB::reconnect('mysql'); // Fresh connection
        }

        // Try DemoVoterSlug first (for demo elections)
        $voterSlug = DemoVoterSlug::on('mysql')
            ->withoutGlobalScopes()
            ->where('slug', $value)
            ->first();

        if (!$voterSlug) {
            // Try VoterSlug as fallback (for regular elections)
            $voterSlug = VoterSlug::on('mysql')
                ->withoutGlobalScopes()
                ->where('slug', $value)
                ->first();
        }

        $attempts++;
    }

    if (!$voterSlug) {
        \Log::error('❌ Voter slug not found after ' . $maxAttempts . ' attempts', [
            'slug' => $value,
            'attempts' => $attempts,
            'session_data' => session()->all()
        ]);

        // Check if it's in session as fallback
        $sessionSlug = session('last_created_voter_slug');
        if ($sessionSlug === $value) {
            \Log::info('⚠️ Slug found in session but not in DB - possible replication lag', [
                'slug' => $value
            ]);
            // Let them try again by redirecting back
            abort(403, 'System is initializing. Please try again in 2 seconds.');
        }

        abort(403, 'Invalid voting link');
    }

    \Log::info('✅ Route binding successful', [
        'slug' => $value,
        'type' => get_class($voterSlug),
        'attempts' => $attempts
    ]);

    return $voterSlug;
});

// Auth::routes();

// ── Serve files from storage/app/public/* via /storage/* ──
Route::get('/storage/{path?}', function ($path = null) {
    if (!$path) {
        abort(404, 'File not found');
    }

    // Prevent directory traversal attacks
    $safe_path = str_replace('..', '', $path);
    if ($safe_path !== $path) {
        abort(403, 'Access denied');
    }

    $full_path = storage_path('app/public/' . $safe_path);

    if (!File::exists($full_path) || !is_file($full_path)) {
        abort(404, 'File not found: ' . $safe_path);
    }

    $file = File::get($full_path);
    $type = File::mimeType($full_path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('path', '.*');

// Newsletter unsubscribe (public — no auth required)
Route::get('/unsubscribe/{token}', [NewsletterUnsubscribeController::class, 'unsubscribe'])
    ->name('newsletter.unsubscribe');

// Newsletter user guide (public — no auth required, optional org context)
Route::get('/newsletter-guide/{organisationSlug?}', function ($organisationSlug = null) {
    $org = null;
    if ($organisationSlug) {
        $org = \App\Models\Organisation::where('slug', $organisationSlug)->first();
    }

    return Inertia::render('Guides/NewsletterGuide', [
        'organisation'       => $org ? $org->only('id', 'name', 'slug') : null,
        'usesFullMembership' => $org?->uses_full_membership ?? null,
    ]);
})->name('guides.newsletter-guide');

// SEO Routes
// Sitemap Index (aggregates all sitemaps)
Route::get('/sitemap.xml', [SitemapController::class, 'sitemapIndex'])->name('sitemap.index');

// Individual Sitemaps
Route::get('/sitemap/main.xml', [SitemapController::class, 'index'])->name('sitemap.main');
Route::get('/sitemap/organisations.xml', [SitemapController::class, 'organisations'])->name('sitemap.organisations');
Route::get('/sitemap/elections.xml', [SitemapController::class, 'elections'])->name('sitemap.elections');
Route::get('/sitemap/results.xml', [SitemapController::class, 'results'])->name('sitemap.results');

// robots.txt
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots.txt');

// Custom Authentication Routes (overrides Fortify)
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'show'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'store']);

    // Register routes
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'show'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store']);

    // Password reset routes
    Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.reset.store');

    // Voter invitation routes (public — no auth required)
    Route::get('/invitation/{token}', [VoterInvitationController::class, 'showSetPassword'])
        ->name('invitation.show-set-password');
    Route::post('/invitation/{token}', [VoterInvitationController::class, 'setPassword'])
        ->name('invitation.store-password')
        ->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'destroy'])->name('logout');

    // Password update route
    Route::put('/user/password', [\App\Http\Controllers\Auth\PasswordUpdateController::class, 'update'])->name('password.update');

    // Account deletion route
    Route::delete('/user', [\App\Http\Controllers\Auth\DeleteAccountController::class, 'delete'])->name('user.delete');

    // Profile routes (placeholder for Jetstream compatibility)
    Route::get('/user/profile', function () {
        return Inertia::render('Profile/Show');
    })->name('profile.show');

    Route::get('/api-tokens', function () {
        return Inertia::render('ApiTokens/Index');
    })->name('api-tokens.index');

    Route::get('/teams/create', function () {
        return Inertia::render('Teams/Create');
    })->name('teams.create');
});

// Home route: If authenticated, show election dashboard. Else, show welcome page.
Route::get('/',
[ElectionManagementController::class, 'dashboard'])
->name('electiondashboard')
->middleware('no.cache');

// Voting start page
Route::get('/voting', function () {
    return Inertia::render('VotingStart');
})->name('voting.start');

// Voting election page
Route::get('/voting/election', function () {
    return Inertia::render('VotingElection');
})->name('voting.election');

// Election selection - when multiple elections are active
Route::get('/election/select', [ElectionController::class, 'selectElection'])
    ->middleware('auth')
    ->name('election.select');

// Demo election start - bypass voter checks
Route::get('/election/demo/start', [ElectionManagementController::class, 'startDemo'])
    ->middleware('auth')
    ->name('election.demo.start');

// Demo election selection - list multiple demo elections
Route::get('/election/demo/list', [ElectionManagementController::class, 'listDemoElections'])
    ->middleware('auth')
    ->name('election.demo.list');

// Start a specific demo election
Route::post('/election/demo/select', [ElectionManagementController::class, 'startSpecificDemo'])
    ->middleware('auth')
    ->name('election.demo.select');

// Pricing page
Route::get('/pricing', function () {
    return Inertia::render('Pricing');
})->name('pricing');

// About page
Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

// FAQ page
Route::get('/faq', function () {
    return Inertia::render('FAQ');
})->name('faq');

// Election settings tutorial — public, no auth required
Route::get('/help/election-setup', function () {
    return Inertia::render('Tutorials/ElectionSettings');
})->name('tutorials.election-settings');

// Voter verification tutorial — public, no auth required
Route::get('/help/voters-verification_guide', function () {
    return Inertia::render('Tutorials/VotersManagement');
})->name('tutorials.voters-verification-guide');

// Membership modes tutorial — public, no auth required
Route::get('/help/membership-modes', function () {
    return Inertia::render('Tutorials/MembershipModes');
})->name('tutorials.membership-modes');

// Security page
Route::get('/security', [App\Http\Controllers\SecurityPageController::class, 'show'])->name('security');

// Voting Security page
Route::get('/voting/security', [App\Http\Controllers\VotingSecurityPageController::class, 'show'])->name('voting.security');

// SEO landing pages — keyword-targeted routes
Route::get('/digitale-online-wahlen-fuer-verein', function () {
    return Inertia\Inertia::render('Marketing/Vereinswahlen', [
        'canonical' => config('app.url') . '/digitale-online-wahlen-fuer-verein',
    ]);
})->name('vereinswahlen.landing');

Route::get('/wahlen/vereine', function () {
    return Inertia\Inertia::render('Marketing/Vereinswahlen', [
        'canonical' => config('app.url') . '/wahlen/vereine',
    ]);
})->name('wahlen.vereine');

Route::get('/wahlen/hybrid', function () {
    return Inertia\Inertia::render('Marketing/Hybrid', [
        'canonical' => config('app.url') . '/wahlen/hybrid',
    ]);
})->name('wahlen.hybrid');

Route::get('/wahlen/sicherheit', function () {
    return Inertia\Inertia::render('Marketing/Sicherheit', [
        'canonical' => config('app.url') . '/wahlen/sicherheit',
    ]);
})->name('wahlen.sicherheit');

// Route::get('/', function () {
//     if( auth()->user()!=null ){ return Inertia::render('Dashboard/MainDashboard'); }

//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', [ElectionManagementController::class, 'dashboard'])
        ->name('dashboard')
        ->middleware('no.cache');
// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     $authUser =null;
//     if(Auth::check()){
//         $authUser =Auth::user();

//     }
//     return Inertia::render('Dashboard/ElectionDashboard',[
//         'authUser'=>$authUser,
//     ]);
//     // dd($authUser);
//     return Inertia::render('Dashboard/MainDashboard',[
//         'authUser'=>$authUser,
//     ]);
// })->name('dashboard');




/****
 * user
 *
*/
//user registratration
Route::get('/email/verify', [\App\Http\Controllers\Auth\VerificationController::class, 'show'])
    ->middleware('auth')
    ->name('verification.notice');

// Email resend route (when user clicks "Resend Email" button)
Route::post('/email/verification-notification', [\App\Http\Controllers\Auth\VerificationController::class, 'send'])
    ->middleware('auth')
    ->name('verification.send');

// Email verification route for signed URL from email
Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Auth\VerificationController::class, 'verify'])
    ->middleware('auth')
    ->name('verification.verify');


//create user database
/**
 * Herwe we write the routes related to user and voter
 */
//Route::middleware(['auth:sanctum', 'verified']) ->
// COMMENTED OUT: This route conflicts with /users/index and calls a CSV import function via GET
// If you need to import users from CSV, use an artisan command instead
// Route::get('users',[UserController::class, 'store'])->name("user.store");


//notices
Route::get('notices/index', [NoticeController::class, 'index'])->name('notice.index');
//get resources
Route::get('/get/{filename}', [MakeurlController::class, 'getfile']);
//candidates


//messages
/**
 * Write messages
 */
Route::get('/message', function (){
    return inertia('Message', [
        'messages'=>Message::all()
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/messages/index', [MessageController::class, 'index'])->name('messages.index');
// Route::post('messages',[MessageController::class, 'store'])->name('messages.store');
Route::middleware(['auth:sanctum', 'verified']) ->post('/messages', [SmsController::class, 'create']);


/**
 * Role
 */
Route::group(['middleware' => ['auth']], function() {
    // Route::get('assignements/index', ['AssignmentController::class', 'index'])->name('role.index');



    Route::get('timeline', function (){
        return Inertia::render('Timeline/TimelineIndex', [
            //   'user' => $user,

            ]);
    });
}); //end of Role


 /***
  *
  * test
  */
  Route::middleware(['auth:sanctum', 'verified']) ->get('/test', [TestController::class, 'index'])->name('test.index');

  //Students
  Route::group([], __DIR__.'/acadamy/acadamyRoutes.php');
 //Security
  Route::group([], __DIR__.'/security/security_routes.php');
   

//User
Route::group([], __DIR__.'/user/userRoutes.php');
Route::group([], __DIR__.'/user/googleRoutes.php');
//election
Route::group([], __DIR__.'/election/electionRoutes.php');

//Openion
// Route::group([], __DIR__.'/openion/openionRoutes.php');
Route::group([], __DIR__.'/committee/committeeRoutes.php');
// Route::group([], __DIR__.'/openion/openionRoutes.php');

Route::group([], __DIR__.'/openion/openionRoutes.php');
//finance
Route::group([], __DIR__.'/finance/financeRoutes.php');
//election


// ============================================================================
// NEW: ROLE-BASED DASHBOARD SYSTEM (Phase 1 & 2)
// ============================================================================
Route::middleware(['auth'])->group(function () {

    // Welcome dashboard (first-time users / onboarding)
    Route::get('/dashboard/welcome', [WelcomeDashboardController::class, 'index'])
         ->name('dashboard.welcome');

    // Role selection dashboard (entry point for multi-role users)
    Route::get('/dashboard/roles', [RoleSelectionController::class, 'index'])
         ->name('role.selection');

    // Role switching
    Route::post('/switch-role/{role}', [RoleSelectionController::class, 'switchRole'])
         ->name('role.switch');

    // Admin dashboard (requires admin role)
    Route::prefix('dashboard/admin')->middleware(['dashboard.role:admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // Commission dashboard (requires commission role)
    Route::prefix('dashboard/commission')->middleware(['dashboard.role:commission'])->group(function () {
        Route::get('/', [CommissionDashboardController::class, 'index'])->name('commission.dashboard');
    });

    // Voter dashboard (requires voter role)
    Route::prefix('vote')->middleware(['dashboard.role:voter'])->group(function () {
        Route::get('/', [VoterDashboardController::class, 'index'])->name('vote.dashboard');
    });

    // organisation management routes
    Route::get('/my-organisations', [OrganisationController::class, 'index'])
         ->name('organisations.index');
    Route::get('/my-organisations/create', [OrganisationController::class, 'create'])
         ->name('organisations.create');
    Route::post('/organisations', [OrganisationController::class, 'store'])
         ->name('organisations.store');

    // Organisation-scoped routes (require membership verification)
    Route::middleware('ensure.organisation')->group(function () {
        Route::get('/organisations/{slug}', [OrganisationController::class, 'show'])
             ->name('organisations.show');
        Route::get('/organisations/{slug}/members/import', [MemberImportController::class, 'create'])
             ->name('organisations.members.import');
        Route::get('/organisations/{slug}/members/import/tutorial', [MemberImportController::class, 'tutorial'])
             ->name('organisations.members.import.tutorial');
        Route::get('/organisations/{slug}/members/import/template', [MemberImportController::class, 'template'])
             ->name('organisations.members.import.template');
        Route::post('/organisations/{slug}/members/import', [MemberImportController::class, 'store'])
             ->name('organisations.members.import.store');
        Route::get('/organisations/{slug}/members/import/{jobId}/status', [MemberImportController::class, 'status'])
             ->name('organisations.members.import.status');
    });

    // Excel import/export routes (use {organisation:slug} for slug-based model binding)
    Route::middleware(['auth', 'verified', 'ensure.organisation'])
        ->prefix('organisations/{organisation:slug}/users/import')
        ->name('organisations.users.import.')
        ->controller(OrganisationUserImportController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/template', 'template')->name('template');
            Route::post('/preview', 'preview')->name('preview');
            Route::post('/process', 'process')->name('process');
        });

    Route::get('/organisations/{organisation:slug}/users/export', [OrganisationUserImportController::class, 'export'])
        ->name('organisations.users.export')
        ->middleware(['auth', 'verified', 'ensure.organisation']);

    // Demo setup API endpoint
    Route::post('/api/organisations/{organisation}/demo-setup', [DemoSetupController::class, 'setup'])
         ->name('api.organisations.demo-setup');
});

// ============================================================================
// organisation-SCOPED ROUTES (Phase 4 - Voters List)
// ============================================================================
// These routes are automatically prefixed with /organisations/{slug}
// and include EnsureOrganization middleware for security
require __DIR__.'/organisations.php';

// ============================================================================
// STATIC PAGES (Terms of Service & Privacy Policy)
// ============================================================================
Route::get('/terms-of-service', function () {
    return Inertia::render('Legal/TermsOfService');
})->name('terms.show');

Route::get('/privacy-policy', function () {
    return Inertia::render('Legal/PrivacyPolicy');
})->name('policy.show');

// ============================================================================
// DIAGNOSTIC ROUTES (Development/Testing Only)
// ============================================================================
Route::get('/test/email', function () {
    try {
        // Test 1: Check mail config
        $config = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'username' => config('mail.mailers.smtp.username'),
            'from_address' => config('mail.from.address'),
            'queue_driver' => config('queue.default'),
        ];

        \Log::info('📧 Email Configuration Test', $config);

        // Test 2: Send a test email
        \Illuminate\Support\Facades\Mail::raw('This is a test email from Public Digit.', function ($message) {
            $message->to('roshyara@gmail.com')
                ->subject('Public Digit - Email Configuration Test');
        });

        \Log::info('✅ Test email sent successfully');

        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully to roshyara@gmail.com!',
            'config' => $config,
            'check_logs' => 'Check storage/logs/laravel.log for details'
        ]);
    } catch (\Exception $e) {
        \Log::error('❌ Email configuration test failed', [
            'error' => $e->getMessage(),
            'exception_class' => get_class($e),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'type' => get_class($e),
            'check_logs' => 'Check storage/logs/laravel.log for full trace'
        ], 500);
    }
})->name('test.email');

// Test route for email diagnostics
Route::get('/test-email', function () {
    $user = \App\Models\User::first();
    if (!$user) {
        return 'No user found';
    }
    
    try {
        $result = $user->notify(new \App\Notifications\SendFirstVerificationCode($user, 'TEST123'));
        return 'Email sent! Result: ' . ($result ? 'true' : 'false');
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});


// Diagnostic endpoint - check demo election setup
Route::get('/diagnostic/demo', [App\Http\Controllers\DiagnosticController::class, 'diagnoseDemo'])
    ->middleware('auth')
    ->name('diagnostic.demo');
