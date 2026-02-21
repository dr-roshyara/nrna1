<?php

namespace App\Services;

use App\Models\Election;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * DemoElectionResolver - Resolve correct demo election for a user
 *
 * Implements PRIORITY-BASED selection:
 * 1️⃣ Demo election with user's organisation_id (if exists)
 * 2️⃣ Platform-wide demo (organisation_id = null)
 *
 * Used by VoterSlugService and DemoCodeController to ensure consistent
 * election selection across the application.
 */
class DemoElectionResolver
{
    /**
     * Get the correct demo election for a user
     *
     * @param User $user
     * @return Election|null
     */
    public function getDemoElectionForUser(User $user): ?Election
    {
        \Log::info('🎯 [DemoElectionResolver] Finding demo election', [
            'user_id' => $user->id,
            'user_org_id' => $user->organisation_id,
        ]);

        $query = Election::withoutGlobalScopes()->where('type', 'demo');

        // Priority 1: Org-specific demo
        if ($user->organisation_id !== null) {
            $orgDemo = (clone $query)
                ->where('organisation_id', $user->organisation_id)
                ->first();

            if ($orgDemo) {
                \Log::info('✅ Found org-specific demo election', [
                    'user_id' => $user->id,
                    'user_org_id' => $user->organisation_id,
                    'election_id' => $orgDemo->id,
                ]);
                return $orgDemo;
            }

            \Log::info('⚠️ No org-specific demo, falling back to platform demo', [
                'user_id' => $user->id,
                'user_org_id' => $user->organisation_id,
            ]);
        }

        // Priority 2: Platform-wide demo
        $platformDemo = (clone $query)->whereNull('organisation_id')->first();

        if ($platformDemo) {
            \Log::info('✅ Using platform-wide demo election', [
                'user_id' => $user->id,
                'user_org_id' => $user->organisation_id ?? 'null',
                'election_id' => $platformDemo->id,
            ]);
            return $platformDemo;
        }

        \Log::error('❌ No demo elections found at all', [
            'user_id' => $user->id,
            'user_org_id' => $user->organisation_id,
        ]);

        return null;
    }

    /**
     * Validate that an election is appropriate for a user
     *
     * @param User $user
     * @param Election $election
     * @return bool
     */
    public function isElectionValidForUser(User $user, Election $election): bool
    {
        if ($election->type !== 'demo') {
            return false;
        }

        if ($user->organisation_id !== null) {
            return $election->organisation_id === $user->organisation_id
                || $election->organisation_id === null;
        }

        return $election->organisation_id === null;
    }
}
