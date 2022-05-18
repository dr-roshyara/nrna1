<?php
use App\Http\Controllers\FeedController;
use App\Http\Controllers\UserController;


/***
 *
 * feed
 */
Route::get('/{profile}', [UserController::class, 'show'])->name('show');
Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
Route::get('/feed', [UserController::class, 'create'])->name('feed');
