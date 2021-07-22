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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

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
Route::get('users',[UserController::class, 'store']);
//show posts 
Route::get('posts/index', [PostController::class, 'index'])->name('post.index');

//notices
Route::get('notices/index', [NoticeController::class, 'index'])->name('notice.index');
//get resources
Route::get('/get/{filename}', [MakeurlController::class, 'getfile']); 
     //candidates
Route::get('candidacy/create', [CandidacyController::class, 'create'])->name('candidacy.create');
Route::post('candidacies', [CandidacyController::class, 'store'])->name('candidacy.store');
Route::get('candidacies/index', [CandidacyController::class, 'index'])->name('candidacy.index');

//messages
Route::get('/message', function (){
    return inertia('Message', [ 
        'messages'=>Message::all()
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])
->get('/messages/index', [MessageController::class, 'index'])->name('messages.index');
// Route::post('messages',[MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages', [SmsController::class, 'create']);

/**
 * Role
 */
Route::group(['middleware' => ['auth']], function() {
    Route::get('assignements/index', ['AssignmentController::class', 'index'])->name('role.index');

}); //end of Role 
