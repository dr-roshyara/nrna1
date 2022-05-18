<?php
use App\Http\Controllers\FeedController;
use App\Http\Controllers\UserController;


/***
 *
 * feed
 */
Route::get('/{profile}', [UserController::class, 'show'])->name('user.show');

Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('user.editProfile');
// Profile Information...

    Route::put('/users/update/{id}', [UserController::class, 'update'])
        ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        ->name('user.update');

Route::get('/feed', [UserController::class, 'create'])->name('feed');
