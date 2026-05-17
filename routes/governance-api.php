<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Governance\CommitteeMemberController;

/**
 * Governance API Routes
 *
 * Registered in RouteServiceProvider to bypass Inertia middleware
 * Prefix: /organisations/{organisation:slug}/api/governance
 * Middleware: web (sessions), auth, verified, ensure.organisation
 * NO Inertia rendering - returns pure JSON
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
    ]);
})->name('test-membership');

// TEST: Simple POST debug
Route::post('/test-post-debug', function (\App\Models\Organisation $organisation) {
    \Log::info('[DEBUG POST] ✓✓✓ TEST ROUTE HIT ✓✓✓', [
        'organisation_id' => $organisation->id,
        'user_id' => auth()->id(),
        'user_email' => auth()->user()?->email,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'POST route is working!',
        'user' => auth()->user()?->email,
        'organisation' => $organisation->slug,
    ]);
})->name('test-post-debug');

// Committee Member endpoints
Route::get('/committees/{committeeId}/members',
    [CommitteeMemberController::class, 'index']
)->name('committees.members.index');

Route::post('/committees/{committeeId}/members',
    [CommitteeMemberController::class, 'store']
)->name('committees.members.store');

Route::delete('/committees/{committeeId}/members/{memberId}',
    [CommitteeMemberController::class, 'destroy']
)->name('committees.members.destroy');
