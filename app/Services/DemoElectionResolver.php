<?php

namespace App\Services;

use App\Models\Election;
use App\Models\Organisation;
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
     * Priority:
     * 1️⃣ Org-specific demo (auto-creates if missing)
     * 2️⃣ Platform-wide demo (fallback)
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

        // Priority 1: Org-specific demo (AUTO-CREATE if missing)
        if ($user->organisation_id !== null) {
            $orgDemo = (clone $query)
                ->where('organisation_id', $user->organisation_id)
                ->first();

            // If no org-specific demo exists, AUTO-CREATE it
            if (!$orgDemo) {
                $organisation = Organisation::find($user->organisation_id);
                if ($organisation) {
                    \Log::info('🔨 Auto-creating org-specific demo election', [
                        'user_id' => $user->id,
                        'organisation_id' => $user->organisation_id,
                        'organisation_name' => $organisation->name,
                    ]);

                    try {
                        $orgDemo = app(DemoElectionCreationService::class)
                            ->createOrganisationDemoElection($user->organisation_id, $organisation);

                        \Log::info('✅ Auto-created org-specific demo election', [
                            'user_id' => $user->id,
                            'organisation_id' => $user->organisation_id,
                            'election_id' => $orgDemo->id,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('❌ Failed to auto-create org-specific demo', [
                            'user_id' => $user->id,
                            'organisation_id' => $user->organisation_id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            if ($orgDemo) {
                \Log::info('✅ Using org-specific demo election', [
                    'user_id' => $user->id,
                    'user_org_id' => $user->organisation_id,
                    'election_id' => $orgDemo->id,
                    'auto_created' => $orgDemo->created_at->greaterThan(now()->subSeconds(10)),
                ]);
                return $orgDemo;
            }

            \Log::info('⚠️ No org-specific demo and auto-creation failed, falling back to platform demo', [
                'user_id' => $user->id,
                'user_org_id' => $user->organisation_id,
            ]);
        }

        // Priority 2: Platform-wide demo (fallback)
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
