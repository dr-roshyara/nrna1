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

        // Priority 1: Org-specific demo that HAS data (AUTO-CREATE if missing)
        if ($user->organisation_id !== null) {
            $orgDemo = (clone $query)
                ->where('organisation_id', $user->organisation_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->first(fn($e) => $this->electionHasData($e));

            // If no org-specific demo with data exists, AUTO-CREATE it
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

        // Priority 2: Platform-wide demo that HAS data
        $platformDemo = (clone $query)
            ->whereNull('organisation_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->first(fn($e) => $this->electionHasData($e));

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
     * Get the demo election for public (anonymous) access.
     *
     * Priority (with data validation):
     * 1️⃣ Election with slug 'demo-election-{org_slug}' that HAS posts + candidates
     * 2️⃣ Most recently created demo election for this org that HAS data
     * 3️⃣ Any demo election for this org (will auto-create if none exists)
     *
     * @return Election|null
     */
    public function getPublicDemoElection(): ?Election
    {
        $platformOrg = \App\Models\Organisation::getDefaultPlatform();

        if ($platformOrg) {
            // Priority 1: Try the specific slug that demo:setup creates
            $namedDemo = Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->where('organisation_id', $platformOrg->id)
                ->where('slug', 'demo-election-' . $platformOrg->slug)
                ->first();

            if ($namedDemo && $this->electionHasData($namedDemo)) {
                return $namedDemo;
            }

            // Priority 2: Most recent demo election for this org that has data
            $withData = Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->where('organisation_id', $platformOrg->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->first(fn($e) => $this->electionHasData($e));

            if ($withData) {
                return $withData;
            }

            // Priority 3: If named demo exists but is empty, populate it
            if ($namedDemo && !$this->electionHasData($namedDemo)) {
                $this->ensureElectionHasData($namedDemo);
                if ($this->electionHasData($namedDemo)) {
                    return $namedDemo;
                }
            }

            // Priority 4: Auto-create new one
            try {
                $orgDemo = app(DemoElectionCreationService::class)
                    ->createOrganisationDemoElection($platformOrg->id, $platformOrg);
                if ($orgDemo) {
                    return $orgDemo;
                }
            } catch (\Exception $e) {
                \Log::error('Failed to auto-create public demo election', ['error' => $e->getMessage()]);
            }
        }

        // Final fallback: any platform-wide demo
        return Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->whereNull('organisation_id')
            ->first();
    }

    /**
     * Check if an election actually has posts and candidates.
     */
    private function electionHasData(Election $election): bool
    {
        if ($election->posts_count > 0 && $election->candidates_count > 0) {
            return true;
        }

        $postCount = \App\Models\DemoPost::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->count();
        $candidateCount = \App\Models\DemoCandidacy::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->count();

        if ($postCount !== (int) $election->posts_count || $candidateCount !== (int) $election->candidates_count) {
            $election->withoutEvents(fn() => $election->update([
                'posts_count' => $postCount,
                'candidates_count' => $candidateCount,
            ]));
        }

        return $postCount > 0 && $candidateCount > 0;
    }

    /**
     * Ensure an election has at least basic demo data.
     */
    private function ensureElectionHasData(Election $election): void
    {
        $postCount = \App\Models\DemoPost::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->count();
        if ($postCount > 0) {
            return;
        }

        $orgId = $election->organisation_id;
        $posts = [
            ['name' => 'President', 'order' => 1, 'required' => 1],
            ['name' => 'Vice President', 'order' => 2, 'required' => 1],
            ['name' => 'General Secretary', 'order' => 3, 'required' => 1],
        ];
        $names = ['Alice Johnson', 'Bob Smith', 'Carol Williams'];

        foreach ($posts as $p) {
            $post = \App\Models\DemoPost::withoutGlobalScopes()->create([
                'election_id' => $election->id,
                'organisation_id' => $orgId,
                'name' => $p['name'],
                'required_number' => $p['required'],
                'position_order' => $p['order'],
                'is_national_wide' => true,
            ]);
            foreach ($names as $i => $name) {
                \App\Models\DemoCandidacy::withoutGlobalScopes()->create([
                    'post_id' => $post->id,
                    'election_id' => $election->id,
                    'organisation_id' => $orgId,
                    'candidacy_name' => $name,
                    'user_name' => $name,
                    'position_order' => $i + 1,
                ]);
            }
        }

        $election->withoutEvents(fn() => $election->update([
            'posts_count' => count($posts),
            'candidates_count' => count($posts) * count($names),
        ]));

        \Log::info('Auto-populated empty demo election', [
            'election_id' => $election->id,
            'posts' => count($posts),
            'candidates' => count($posts) * count($names),
        ]);
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
