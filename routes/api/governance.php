<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Governance\CommitteeMemberController;

/**
 * API Governance Routes
 *
 * These routes are PURE JSON API endpoints - NO Inertia middleware
 * Prefix: /organisations/{organisation:slug}/api/governance
 * Middleware: auth, verified, ensure.organisation
 */

// TEST: Check if user is authenticated
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

// TEST: Check organisation membership
Route::get('/test-membership', function (\App\Models\Organisation $organisation) {
    $user = auth()->user();
    $roles = $user->organisationRoles()
        ->where('organisation_id', $organisation->id)
        ->pluck('role')
        ->toArray();

    return response()->json([
        'user' => $user->email,
        'organisation' => $organisation->slug,
        'is_member' => count($roles) > 0,
        'roles' => $roles,
        'all_user_organisations' => $user->organisationRoles()
            ->with('organisation')
            ->get()
            ->map(fn($r) => ['org_id' => $r->organisation_id, 'org_slug' => $r->organisation->slug, 'role' => $r->role])
            ->toArray(),
    ]);
})->name('test-membership');

// TEST: Simple POST debug - bypass controller completely
Route::post('/test-post-debug', function (\App\Models\Organisation $organisation) {
    \Log::info('[DEBUG POST] ✓✓✓ TEST ROUTE HIT ✓✓✓', [
        'organisation_id' => $organisation->id,
        'organisation_slug' => $organisation->slug,
        'user_id' => auth()->id(),
        'user_email' => auth()->user()?->email,
        'timestamp' => now(),
    ]);

    $json = [
        'success' => true,
        'message' => 'POST route is working!',
        'user' => auth()->user()?->email,
        'organisation' => $organisation->slug,
    ];

    \Log::info('[DEBUG POST] Returning JSON response', $json);

    return response()->json($json, 200, ['Content-Type' => 'application/json']);
})->name('test-post-debug');

// Committee Member endpoints
Route::get('/committees/{committeeId}/members',
    [CommitteeMemberController::class, 'index']
)->name('committees.members.index');

Route::post('/committees/{committeeId}/members/test-debug',
    function (\App\Models\Organisation $organisation, $committeeId) {
        \Log::info('[DEBUG ROUTE] POST test route matched!', [
            'organisation_id' => $organisation->id,
            'committeeId' => $committeeId,
            'authenticated' => auth()->check(),
            'user' => auth()->user()?->email,
        ]);
        return response()->json([
            'message' => 'Debug route matched!',
            'org' => $organisation->id,
            'authenticated' => auth()->check(),
            'user' => auth()->user()?->email,
        ]);
    }
)->name('committees.members.test-debug');

Route::post('/committees/{committeeId}/members',
    [CommitteeMemberController::class, 'store']
)->name('committees.members.store');

Route::delete('/committees/{committeeId}/members/{memberId}',
    [CommitteeMemberController::class, 'destroy']
)->name('committees.members.destroy');
