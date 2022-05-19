<?php
use App\Http\Controllers\FeedController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;


/***
 *
 * feed
 */
Route::get('/{profile}', [UserController::class, 'show'])->name('user.show');

Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('user.editProfile');
Route::post('/images/store',  [ImageController::class, 'store'])->name('image.store');
// Profile Information...

    Route::put('/users/update/{id}', [UserController::class, 'update'])
        ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        ->name('user.update');

Route::get('/feed', [UserController::class, 'create'])->name('feed');
