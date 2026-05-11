<?php

declare(strict_types=1);

use App\Contexts\Governance\API\V1\Controllers\GovernanceCommitteeController;
use App\Contexts\Governance\API\V1\Controllers\GovernanceHealthController;
use App\Contexts\Governance\API\V1\Controllers\GovernanceHierarchyController;
use Illuminate\Support\Facades\Route;

Route::prefix('governance')->middleware('throttle:governance')->group(function () {
    Route::get('/hierarchy', [GovernanceHierarchyController::class, 'hierarchy']);
    Route::get('/committees/{id}', [GovernanceCommitteeController::class, 'show']);
    Route::get('/committees/{id}/children', [GovernanceCommitteeController::class, 'children']);
    Route::get('/committees/{id}/governance', [GovernanceCommitteeController::class, 'governance']);
    Route::get('/health/projections', [GovernanceHealthController::class, 'projections']);
});
