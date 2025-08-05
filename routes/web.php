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
use Illuminate\Support\Facades\Auth;
//Models
use Inertia\Inertia;
use App\Models\Message;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\OpenionController;

use App\Http\Controllers\Election\ElectionController;

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



// Home route: If authenticated, show election dashboard. Else, show welcome page.
Route::get('/', [ElectionController::class, 'dashboard'])->name('electiondashboard');

// Route::get('/', function () {
//     if( auth()->user()!=null ){ return Inertia::render('Dashboard/MainDashboard'); }

//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', [ElectionController::class, 'dashboard'])->name('dashboard');

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


//create user database
/**
 * Herwe we write the routes related to user and voter
 */
//Route::middleware(['auth:sanctum', 'verified']) ->
Route::get('users',[UserController::class, 'store'])->name("user.store");


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
