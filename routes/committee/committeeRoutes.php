<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommitteeController;
Route::get('/activities', [CommitteeController::class, 'activities'])->name('activities');
Route::get('/calendar', [CommitteeController::class, 'showCalendar'])->name('calendar');
