<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\VoterSlug;
use App\Models\DemoVoterSlug;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Voting\InvalidVoterSlugException;
use App\Exceptions\Voting\SlugOwnershipException;

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

        Log::emergency('🔍 VERIFY VOTER SLUG - START', [
            'slug_param' => $slugParam,
            'url' => $request->fullUrl(),
            'user_id' => auth()->id(),
        ]);

        // Handle both model-bound and string slug parameters
        // First, try to find a real VoterSlug
        $voterSlug = $slugParam instanceof VoterSlug
            ? $slugParam
            : ($slugParam instanceof DemoVoterSlug
                ? $slugParam
                : VoterSlug::withoutGlobalScopes()
                    ->where('slug', $slugParam)
                    ->first());

        Log::emergency('VoterSlug search result', ['found' => (bool)$voterSlug]);

        // If not found, try DemoVoterSlug (for demo election flows)
        if (!$voterSlug && !($slugParam instanceof VoterSlug) && !($slugParam instanceof DemoVoterSlug)) {
            Log::emergency('Searching in DemoVoterSlug...');
            $voterSlug = DemoVoterSlug::withoutGlobalScopes()
                ->where('slug', $slugParam)
                ->first();

            if ($voterSlug) {
                Log::emergency('✅ Found in DemoVoterSlug!', ['id' => $voterSlug->id]);
            } else {
                Log::emergency('❌ NOT found in DemoVoterSlug!');
            }
        }

        // CHECK 1: Does slug exist?
        if (!$voterSlug) {
            Log::warning('❌ [VerifyVoterSlug] Slug not found', [
                'slug' => $slugParam,
            ]);
            throw new InvalidVoterSlugException('Voting session not found', [
                'slug' => $slugParam,
            ]);
        }

        // CHECK 2: Does slug belong to authenticated user?
        if ($voterSlug->user_id !== auth()->id()) {
            Log::warning('❌ [VerifyVoterSlug] Slug belongs to different user', [
                'slug_id' => $voterSlug->id,
                'slug_user_id' => $voterSlug->user_id,
                'auth_user_id' => auth()->id(),
            ]);
            throw new SlugOwnershipException('This voting session does not belong to you', [
                'slug_id' => $voterSlug->id,
                'slug_user_id' => $voterSlug->user_id,
                'auth_user_id' => auth()->id(),
            ]);
        }

        // CHECK 3: Is slug still active?
        if (!$voterSlug->is_active) {
            Log::warning('❌ [VerifyVoterSlug] Slug is inactive', [
                'slug_id' => $voterSlug->id,
                'is_active' => $voterSlug->is_active,
            ]);
            throw new InvalidVoterSlugException('This voting session has been deactivated', [
                'slug_id' => $voterSlug->id,
                'is_active' => $voterSlug->is_active,
            ]);
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
