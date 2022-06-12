<?php
use App\Http\Controllers\FeedController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

Route::middleware(['auth:sanctum', 'verified'])
        ->get('/users/index', [UserController::class, 'index'])->name('users.index');


/**
 * For profile photos create routes
 * Profile photso are in <storage/profile-photos>
 */

Route::get('profile-photos/{filename}', function ($filename)
{
    $path = public_path('profile-photos/' . $filename);
    dd($path);
    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    // $type = File::mimeType($path);

    $response = Response::make($file, 200);
    // $response->header("Content-Type", $type);

    return $response;
});

/***
 *
 * feed
 */
Route::get('/user/{profile}', [UserController::class, 'show'])->name('user.show');

Route::get('/user/{id}/edit',        [UserController::class, 'edit'])->name('edit');
Route::get('/profile/edit',     [UserController::class, 'editProfile'])->name('user.editProfile');
Route::post('/images/store',    [ImageController::class, 'store'])->name('image.store');
Route::post('/avatar/upload',   [ImageController::class, 'avatarUpload'])->name('avatar.upload');

// Profile Information...
Route::put('/users/update/{id}', [UserController::class, 'update'])
        ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        ->name('user.update');

Route::get('/feed', [UserController::class, 'create'])->name('feed');

//facebook routes
Route::get('/login/facebook', [SocialController::class, 'facebookRedirect'])->name('login.facebook-redirect');
Route::get('/login/facebook/callback', [SocialController::class, 'loginWithFacebook']);
