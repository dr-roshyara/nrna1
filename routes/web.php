<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
//controllers
use App\Http\Controllers\PostController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\MakeurlController;
use App\Http\Controllers\SitemapController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedController;

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

use App\Http\Controllers\Election\ElectionController;
use App\Models\VoterSlug;

// Role-based dashboard controllers (NEW)
use App\Http\Controllers\RoleSelectionController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CommissionDashboardController;
use App\Http\Controllers\VoterDashboardController;
use App\Http\Controllers\WelcomeDashboardController;

// Note: VoterSlug route binding is now in App\Providers\RouteServiceProvider

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
// Auth::routes();

Route::get('/storage/images/{filename}', function ($filename)
{
    $path = storage_path('images/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Home route: If authenticated, show election dashboard. Else, show welcome page.
Route::get('/',
[ElectionController::class, 'dashboard'])
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
Route::get('/election/demo/start', [ElectionController::class, 'startDemo'])
    ->middleware('auth')
    ->name('election.demo.start');

// Pricing page
Route::get('/pricing', function () {
    return Inertia::render('Pricing');
})->name('pricing');

// Route::get('/', function () {
//     if( auth()->user()!=null ){ return Inertia::render('Dashboard/MainDashboard'); }

//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', [ElectionController::class, 'dashboard'])
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
Route::get('/email/verify', function () {
    return inertia('Auth/VerifyEmail');
})->middleware('auth')->name('verification.notice');

// Email verification route for signed URL from email
Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard/roles')->with('verified', true);
})->middleware('auth')->name('verification.verify');


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
    Route::prefix('dashboard/admin')->middleware(['role:admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // Commission dashboard (requires commission role)
    Route::prefix('dashboard/commission')->middleware(['role:commission'])->group(function () {
        Route::get('/', [CommissionDashboardController::class, 'index'])->name('commission.dashboard');
    });

    // Voter dashboard (requires voter role)
    Route::prefix('vote')->middleware(['role:voter'])->group(function () {
        Route::get('/', [VoterDashboardController::class, 'index'])->name('vote.dashboard');
    });
});
