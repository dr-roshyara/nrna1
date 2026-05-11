<?php

use App\Contexts\Geography\Http\Controllers\CountryController;
use App\Contexts\Geography\Http\Controllers\AdministrativeUnitController;
use App\Http\Controllers\Geography\GeographicLevelController;
use App\Http\Controllers\Geography\OrganisationGeographyController;
use App\Http\Controllers\Geography\RegionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:100,60'])->prefix('geography')->group(function () {
    Route::get('/countries', [CountryController::class, 'index'])->name('geo.countries.index');
    Route::get('/countries/{code}/hierarchy', [CountryController::class, 'hierarchy'])->name('geo.countries.hierarchy');
    Route::get('/countries/{code}/level/{level}', [CountryController::class, 'level'])->name('geo.countries.level');
    Route::get('/countries/{code}/flat', [CountryController::class, 'flat'])->name('geo.countries.flat');
    Route::get('/units/{id}/children', [AdministrativeUnitController::class, 'children'])->name('geo.units.children');
    Route::get('/levels/{countryCode}', [GeographicLevelController::class, 'defaults'])->name('geo.levels.defaults');
    Route::get('/levels/{countryCode}/available', [GeographicLevelController::class, 'available'])->name('geo.levels.available');
    Route::get('/regions', [RegionController::class, 'index'])->name('geo.regions.index');
    Route::get('/regions/{code}/countries', [RegionController::class, 'countries'])->name('geo.regions.countries');
});

// Organisation setup — authenticated, for create form
Route::middleware(['auth'])->prefix('organisation-geography')->group(function () {
    Route::get('/countries',                      [OrganisationGeographyController::class, 'countries']);
    Route::get('/continents',                     [OrganisationGeographyController::class, 'continents']);
    Route::get('/countries/{countryCode}/regions',[OrganisationGeographyController::class, 'regionsByCountry']);
});
