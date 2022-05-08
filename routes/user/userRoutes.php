<?php
use App\Http\Controllers\FeedController;


/***
 *
 * feed
 */
Route::get('/feed', [FeedController::class, 'create'])->name('feed');
// Route::get('/feed',  [FeedController::class, 'create'])->name('feed');
