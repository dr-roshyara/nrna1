<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
//controllers
use App\Http\Controllers\PostController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\MakeurlController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CandidacyController;
use App\Http\Controllers\VoterlistController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\MessageController;
// use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\VoteController; 
use App\Http\Controllers\CodeController;
use App\Http\Controllers\DeligateCandidacyController;
use App\Http\Controllers\DeligateVoteController;
use App\Http\Controllers\DeligateCodeController;
//Models
use Inertia\Inertia;
use App\Models\Message;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


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

/**
 * For profile photos create routes
 * Profile photso are in <storage/profile-photos>
 */

Route::get('storage/profile-photos/{filename}', function ($filename)
{
    $path = public_path('profile-photos/' . $filename); 
    // dd($path);
    if (!File::exists($path)) {
        abort(404); 
    }
 
    $file = File::get($path);
    $type = File::mimeType($path);
 
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
 
    return $response;
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


//Dashboard 
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
//voters 
Route::middleware(['auth:sanctum', 'verified']) 
        ->get('/voters/index', [VoterlistController::class, 'index'])->name('voters.index');
 
/****
 * user 
 * 
*/
//user registratration 
Route::get('/email/verify', function () {
    return inertia('Auth/VerifyEmail'); 
})->middleware('auth')->name('verification.notice');

Route::middleware(['auth:sanctum', 'verified']) 
        ->get('/users/index', [UserController::class, 'index'])->name('users.index');

//create user database 
/**
 * Herwe we write the routes related to user and voter 
 */
//Route::middleware(['auth:sanctum', 'verified']) ->
Route::get('users',[UserController::class, 'store']);


//show posts 
Route::get('posts/index', [PostController::class, 'index'])->name('post.index');

//notices
Route::get('notices/index', [NoticeController::class, 'index'])->name('notice.index');
//get resources
Route::get('/get/{filename}', [MakeurlController::class, 'getfile']); 
//candidates
/**
 * All candidates
 */
Route::get('candidacy/create', [CandidacyController::class, 'create'])->name('candidacy.create');
Route::post('candidacies', [CandidacyController::class, 'store'])->name('candidacy.store');
Route::get('candidacies/index', [CandidacyController::class, 'index'])->name('candidacy.index');
Route::get('candidacy/update', [CandidacyController::class, 'update'])->name('candidacy.update');
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
 * Routes related to vote 
 */
//Route::group(['middleware' => 'auth:sanctum', 'verified'], function(){   
// Vote   
//code creation  
   Route::middleware(['auth:sanctum', 'verified']) ->get('/code/create', [CodeController::class, 'create'])->name('code.create');                   
   Route::middleware(['auth:sanctum', 'verified']) ->post('/codes', [CodeController::class, 'store'])->name('code.store');
    
   Route::middleware(['auth:sanctum', 'verified']) ->get('/vote/create', [VoteController::class, 'create'])->name('vote.create'); 
    Route::middleware(['auth:sanctum', 'verified']) ->post('/vote/submit', [VoteController::class, 'first_submission'])->name('vote.submit');
    Route::middleware(['auth:sanctum', 'verified']) ->get('/vote/verify', [VoteController::class, 'verify'])->name('vote.verfiy');
    Route::middleware(['auth:sanctum', 'verified']) ->post('/votes', [VoteController::class, 'store'])->name('vote.store'); 
   
    
   Route::middleware(['auth:sanctum', 'verified']) ->get('/votes/index', [VoteController::class, 'index'])->name('vote.index');
   Route::middleware(['auth:sanctum', 'verified']) ->get('/vote/show', [VoteController::class, 'show'])->name('vote.show');

//});   
/**
 * Role
 */
Route::group(['middleware' => ['auth']], function() {
    // Route::get('assignements/index', ['AssignmentController::class', 'index'])->name('role.index'); 

}); //end of Role 

//election result 
Route::get('vote/thankyou', [VoteController::Class , 'thankyou'])->name('vote.thankyou');

Route::get('timeline', function (){
     return Inertia::render('Timeline/TimelineIndex', [
        //   'user' => $user,
 
        ]); 
}); 
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
 Route::middleware(['auth:sanctum', 'verified']) ->get('/deligatevote/verify', [DeligateVoteController::class, 'verify'])->name('deligatevote.verfiy');
 Route::middleware(['auth:sanctum', 'verified']) ->post('/deligatevotes', [DeligateVoteController::class, 'store'])->name('deligatevote.store');  
 Route::middleware(['auth:sanctum', 'verified']) ->get('/deligatevotes/index', [DeligateVoteController::class, 'index'])->name('deligatevote.index');
 Route::middleware(['auth:sanctum', 'verified']) ->get('/deligatevote/show', [DeligateVoteController::class, 'show'])->name('deligatevote.show');

/**
 * Deligate Vote Count 
 * 
 */
 Route::middleware(['auth:sanctum', 'verified']) ->get('/deligatevote/count', [DeligateVoteController::class, 'count'])->name('deligatevote.count');
 Route::middleware(['auth:sanctum', 'verified']) ->get('/deligatevote/result', [DeligateVoteController::class, 'result'])->name('deligatevote.result');
 
