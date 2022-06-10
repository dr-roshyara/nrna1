<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
//controllers
use App\Http\Controllers\PostController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\MakeurlController;

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


Route::get('/', function () {
    //defile title
    // dd(auth()->user());
    if( auth()->user()!=null ){
         return Inertia::render('Dashboard/MainDashboard');
    }

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    // return Inertia::render('Dashboard');
    return Inertia::render('Dashboard/MainDashboard');
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
Route::get('users',[UserController::class, 'store'])->name("user.store");


//show posts
Route::get('posts/index', [PostController::class, 'index'])->name('post.index');
Route::get('posts/assign', [PostController::class, 'assign'])->name('post.assign');

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
