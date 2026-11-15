<?php

declare(strict_types=1);

use App\Http\Controllers\ReferenceGeography\GeographyExplorerController;
use Illuminate\Support\Facades\Route;

/*
 * PBDIGIT-355 (frontend M1): the read-only Reference Geography explorer, for signed-in users. GET only: no route here
 * may create, change, approve or publish a Geography fact. Included from routes/web.php (the `web` group).
 */
Route::middleware(['auth'])->prefix('geography')->name('geography.')->group(function (): void {
    Route::get('/', [GeographyExplorerController::class, 'entry'])->name('entry');
    Route::get('/k/{position}', [GeographyExplorerController::class, 'explorer'])->whereNumber('position')->name('explorer');
    Route::get('/k/{position}/places/{unitId}', [GeographyExplorerController::class, 'place'])
        ->whereNumber('position')->whereUuid('unitId')->name('place');
});
