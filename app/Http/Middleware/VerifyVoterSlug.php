<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\VoterSlug;
use Illuminate\Support\Facades\Log;

class VerifyVoterSlug
{
    /**
     * VERIFICATION LEVEL 1: Existence & Ownership
     *
     * Checks:
     * 1. Does the slug exist in database?
     * 2. Does it belong to the authenticated user?
     * 3. Is it still active?
     */
    public function handle($request, Closure $next)
    {
        $slugParam = $request->route('vslug');

        Log::info('🔍 [VerifyVoterSlug] Starting verification', [
            'slug' => $slugParam,
            'user_id' => auth()->id(),
        ]);

        // Load voter slug with essential relationships (optimized for performance)
        // Uses selective columns for faster queries
        // Bypass global tenant scope since we validate tenant context after loading
        $voterSlug = VoterSlug::withoutGlobalScopes()
            ->withEssentialRelations()
            ->where('slug', $slugParam)
            ->first();

        // CHECK 1: Does slug exist?
        if (!$voterSlug) {
            Log::warning('❌ [VerifyVoterSlug] Slug not found', [
                'slug' => $slugParam,
            ]);
            abort(404, 'Voting session not found');
        }

        // CHECK 2: Does slug belong to authenticated user?
        if ($voterSlug->user_id !== auth()->id()) {
            Log::warning('❌ [VerifyVoterSlug] Slug belongs to different user', [
                'slug_id' => $voterSlug->id,
                'slug_user_id' => $voterSlug->user_id,
                'auth_user_id' => auth()->id(),
            ]);
            abort(403, 'This voting session does not belong to you');
        }

        // CHECK 3: Is slug still active?
        if (!$voterSlug->is_active) {
            Log::warning('❌ [VerifyVoterSlug] Slug is inactive', [
                'slug_id' => $voterSlug->id,
                'is_active' => $voterSlug->is_active,
            ]);
            abort(403, 'This voting session has been deactivated');
        }

        // Store verified slug in request for subsequent middleware
        $request->attributes->set('voter_slug', $voterSlug);

        Log::info('✅ [VerifyVoterSlug] Verification passed', [
            'slug_id' => $voterSlug->id,
            'user_id' => $voterSlug->user_id,
            'election_id' => $voterSlug->election_id,
        ]);

        return $next($request);
    }
}
