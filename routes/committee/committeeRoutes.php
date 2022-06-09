<?php

use App\Http\Controllers\CommitteeController;
Route::get('/activities', [CommitteeController::class, 'activities'])->name('activities');
