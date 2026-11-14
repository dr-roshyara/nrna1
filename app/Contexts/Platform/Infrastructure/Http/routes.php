<?php

declare(strict_types=1);

use App\Contexts\Platform\Infrastructure\Http\SelectOrganisationController;
use Illuminate\Support\Facades\Route;

/*
 * PBDIGIT-345 task 6 (E-1, shared hostname D-219 (2)): selecting an organisation after login. Loaded by
 * PlatformServiceProvider::boot through loadRoutesFrom, so `php artisan route:cache` includes it.
 * Organisation pages themselves use the `organisation.entry` middleware (never together with the legacy `tenant`).
 */
Route::middleware('web')->post('/platform/organisations/select', SelectOrganisationController::class)
    ->name('platform.organisations.select');
