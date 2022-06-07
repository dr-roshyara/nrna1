<?php
use App\Http\Controllers\FeedController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SocialController;

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
