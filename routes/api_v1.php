<?php

use App\Http\Controllers\Api\Governance\CommitteeMemberController;
use Illuminate\Support\Facades\Route;

/**
 * Internal API v1 Routes
 *
 * ⚠️ CRITICAL: Do NOT apply any prefix here!
 * bootstrap/app.php already handles the 'api/v1' prefix globally.
 *
 * Middleware stack (applied in bootstrap/app.php):
 * ['web', 'json.api', 'tenant.api', 'auth', 'verified']
 *
 * Result: Routes become /api/v1/governance/... with proper middleware pipeline
 */

// ── Test/Debug Endpoints ────────────────────────────────────────────────
Route::get('/test-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user() ? [
            'id' => auth()->user()->id,
            'email' => auth()->user()->email,
            'name' => auth()->user()->name,
        ] : null,
        'session_id' => session()->getId(),
        'timestamp' => now(),
    ]);
})->name('test-auth');

Route::post('/test-post-debug', function () {
    \Log::info('[DEBUG POST] ✓✓✓ API v1 TEST ROUTE HIT ✓✓✓', [
        'user_id' => auth()->id(),
        'user_email' => auth()->user()?->email,
        'tenant_header' => request()->header('X-Organisation-ID'),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'API v1 POST route is working correctly!',
        'user' => auth()->user()?->email,
        'organisation_id' => request()->header('X-Organisation-ID'),
    ]);
})->name('test-post-debug');

// ── Committee Member Management ────────────────────────────────────────────
// ✅ DIRECT ROUTES (no nested prefix - bootstrap/app.php already handles 'api/v1')
Route::get('/governance/committees/{committeeId}/members', [CommitteeMemberController::class, 'index'])
    ->name('governance.committees.members.index');

Route::post('/governance/committees/{committeeId}/members', [CommitteeMemberController::class, 'store'])
    ->name('governance.committees.members.store');

Route::delete('/governance/committees/{committeeId}/members/{memberId}', [CommitteeMemberController::class, 'destroy'])
    ->name('governance.committees.members.destroy');
