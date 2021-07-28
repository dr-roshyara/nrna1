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
use \App\Http\Controllers\SmsController;
use \App\Http\Controllers\MessageController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\VoteController; 
use App\Http\Controllers\CodeController;
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
 
//user 
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
    Route::middleware(['auth:sanctum', 'verified']) ->get('/vote/verify', [VoteController::class, 'verify'])->name('vote.verify');
    Route::middleware(['auth:sanctum', 'verified']) ->post('/votes', [VoteController::class, 'store'])->name('vote.store'); 
   
    
   Route::middleware(['auth:sanctum', 'verified']) ->get('/votes/index', [VoteController::class, 'index'])->name('vote.index');
   Route::middleware(['auth:sanctum', 'verified']) ->get('/vote/show', [VoteController::class, 'show'])->name('vote.show');

//});   
/**
 * Role
 */
Route::group(['middleware' => ['auth']], function() {
    Route::get('assignements/index', ['AssignmentController::class', 'index'])->name('role.index');

}); //end of Role 
