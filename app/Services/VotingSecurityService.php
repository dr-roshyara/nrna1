<?php

namespace App\Services;

use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Service for enforcing voting security rules
 *
 * Ensures one person = one vote and prevents multiple voting attempts
 */
class VotingSecurityService
{
    /**
     * Enforce one active slug per user rule
     *
     * This is the core security method that ensures no user can have
     * multiple active slugs at the same time
     */
    public function enforceOneActiveSlugPerUser(User $user): array
    {
        return DB::transaction(function () use ($user) {

            // Get all currently active slugs for this user
            $activeSlugs = VoterSlug::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('expires_at', '>', now())
                ->get();

            $result = [
                'user_id' => $user->id,
                'found_active_slugs' => $activeSlugs->count(),
                'deactivated_slugs' => [],
                'enforcement_needed' => $activeSlugs->count() > 1,
            ];

            // If more than 1 active slug exists, this is a security violation
            if ($activeSlugs->count() > 1) {
                Log::warning('Multiple active slugs detected for user - security enforcement triggered', [
                    'user_id' => $user->id,
                    'active_slugs_count' => $activeSlugs->count(),
                    'slug_ids' => $activeSlugs->pluck('id')->toArray(),
                    'enforcement_timestamp' => now(),
                ]);

                // Keep only the most recent slug, deactivate all others
                $mostRecentSlug = $activeSlugs->sortByDesc('created_at')->first();
                $slugsToDeactivate = $activeSlugs->where('id', '!=', $mostRecentSlug->id);

                foreach ($slugsToDeactivate as $slug) {
                    $slug->update([
                        'is_active' => false,
                        'step_meta' => array_merge($slug->step_meta ?? [], [
                            'deactivated_reason' => 'multiple_active_slugs_security_enforcement',
                            'deactivated_at' => now()->toISOString(),
                            'kept_slug_id' => $mostRecentSlug->id,
                        ])
                    ]);

                    $result['deactivated_slugs'][] = $slug->slug;
                }

                Log::info('Multiple active slugs resolved - kept most recent', [
                    'user_id' => $user->id,
                    'kept_slug' => $mostRecentSlug->slug,
                    'deactivated_count' => count($result['deactivated_slugs']),
                ]);
            }

            return $result;
        });
    }

    /**
     * Check if user can be issued a new voting slug
     *
     * Comprehensive eligibility check for voting security
     */
    public function canIssueVotingSlug(User $user): array
    {
        $result = [
            'can_issue' => false,
            'user_id' => $user->id,
            'reasons' => [],
            'current_status' => [],
        ];

        // 1. Basic voter eligibility
        if (!$user->is_voter) {
            $result['reasons'][] = 'user_not_registered_voter';
        }

        if (!$user->can_vote) {
            $result['reasons'][] = 'user_voting_permission_revoked';
        }

        if ($user->has_voted) {
            $result['reasons'][] = 'user_already_completed_voting';
        }

        // 2. Check for existing active slug
        $activeSlug = VoterSlug::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if ($activeSlug) {
            $result['reasons'][] = 'user_already_has_active_voting_slug';
            $result['current_status']['active_slug'] = $activeSlug->slug;
            $result['current_status']['expires_at'] = $activeSlug->expires_at;
            $result['current_status']['current_step'] = $activeSlug->current_step;
        }

        // 3. Check voting window (if applicable)
        if (!$this->isWithinVotingWindow()) {
            $result['reasons'][] = 'outside_voting_window';
        }

        // 4. Check for suspicious activity
        $recentSlugs = VoterSlug::where('user_id', $user->id)
            ->where('created_at', '>', now()->subHours(2))
            ->count();

        if ($recentSlugs > 5) { // More than 5 slugs in 2 hours is suspicious
            $result['reasons'][] = 'excessive_slug_generation_detected';
            Log::warning('Suspicious voting behavior detected', [
                'user_id' => $user->id,
                'recent_slugs_count' => $recentSlugs,
                'timeframe' => '2_hours',
            ]);
        }

        // Final determination
        $result['can_issue'] = empty($result['reasons']);

        return $result;
    }

    /**
     * Secure slug generation with comprehensive checks
     */
    public function secureSlugGeneration(User $user, string $reason = null): array
    {
        $eligibilityCheck = $this->canIssueVotingSlug($user);

        if (!$eligibilityCheck['can_issue']) {
            return [
                'success' => false,
                'slug' => null,
                'reasons' => $eligibilityCheck['reasons'],
                'message' => 'Cannot issue voting slug - security checks failed',
            ];
        }

        return DB::transaction(function () use ($user, $reason) {
            // Enforce one active slug rule
            $enforcement = $this->enforceOneActiveSlugPerUser($user);

            // Generate new slug
            $slugService = new VoterSlugService();
            $newSlug = $slugService->generateSlugForUser($user);

            // Add security metadata
            $newSlug->update([
                'step_meta' => array_merge($newSlug->step_meta ?? [], [
                    'security_checked' => true,
                    'generation_reason' => $reason ?? 'normal_voting_request',
                    'enforcement_applied' => $enforcement['enforcement_needed'],
                    'previous_slugs_deactivated' => count($enforcement['deactivated_slugs']),
                ])
            ]);

            Log::info('Secure slug generated successfully', [
                'user_id' => $user->id,
                'new_slug' => $newSlug->slug,
                'security_enforcement' => $enforcement['enforcement_needed'],
                'expires_at' => $newSlug->expires_at,
            ]);

            return [
                'success' => true,
                'slug' => $newSlug,
                'security_enforcement' => $enforcement,
                'message' => 'Voting slug generated successfully with security checks',
            ];
        });
    }

    /**
     * Complete voting security audit for a user
     */
    public function auditUserVotingSecurity(User $user): array
    {
        $audit = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'audit_timestamp' => now(),
            'security_status' => 'secure',
            'issues' => [],
            'slug_analysis' => [],
        ];

        // Get all slugs for this user
        $allSlugs = VoterSlug::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $audit['total_slugs'] = $allSlugs->count();

        // Check for multiple active slugs (security violation)
        $activeSlugs = $allSlugs->where('is_active', true)->where('expires_at', '>', now());
        if ($activeSlugs->count() > 1) {
            $audit['security_status'] = 'violation';
            $audit['issues'][] = [
                'type' => 'multiple_active_slugs',
                'severity' => 'high',
                'count' => $activeSlugs->count(),
                'slugs' => $activeSlugs->pluck('slug')->toArray(),
            ];
        }

        // Check for excessive slug generation
        $recentSlugs = $allSlugs->where('created_at', '>', now()->subHours(4));
        if ($recentSlugs->count() > 3) {
            $audit['security_status'] = $audit['security_status'] === 'violation' ? 'violation' : 'warning';
            $audit['issues'][] = [
                'type' => 'excessive_slug_generation',
                'severity' => 'medium',
                'count' => $recentSlugs->count(),
                'timeframe' => '4_hours',
            ];
        }

        // Analyze each slug
        foreach ($allSlugs as $slug) {
            $audit['slug_analysis'][] = [
                'slug' => $slug->slug,
                'created_at' => $slug->created_at,
                'expires_at' => $slug->expires_at,
                'is_active' => $slug->is_active,
                'is_expired' => $slug->isExpired(),
                'current_step' => $slug->current_step,
                'completed_voting' => $slug->current_step >= 5,
                'has_security_metadata' => isset($slug->step_meta['security_checked']),
            ];
        }

        return $audit;
    }

    /**
     * Emergency security lockdown for a user
     */
    public function emergencyLockdown(User $user, string $reason, array $adminDetails = []): bool
    {
        return DB::transaction(function () use ($user, $reason, $adminDetails) {
            // Get all active slugs for this user and update them individually
            $activeSlugs = VoterSlug::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

            $deactivatedCount = 0;
            foreach ($activeSlugs as $slug) {
                $slug->update([
                    'is_active' => false,
                    'step_meta' => array_merge($slug->step_meta ?? [], [
                        'emergency_lockdown' => true,
                        'lockdown_reason' => $reason,
                        'lockdown_timestamp' => now()->toISOString(),
                        'lockdown_admin' => $adminDetails['admin_name'] ?? 'system',
                    ])
                ]);
                $deactivatedCount++;
            }

            // Revoke voting permission
            $user->update(['can_vote' => false]);

            Log::critical('Emergency voting lockdown applied', [
                'user_id' => $user->id,
                'reason' => $reason,
                'admin' => $adminDetails['admin_name'] ?? 'system',
                'slugs_deactivated' => $deactivatedCount,
                'timestamp' => now(),
            ]);

            return true;
        });
    }

    /**
     * Check if we're within the voting window (implement based on your election schedule)
     */
    private function isWithinVotingWindow(): bool
    {
        // TODO: Implement based on your election configuration
        // For now, assume voting is always open
        return true;

        // Example implementation:
        // $votingStart = Carbon::parse(config('election.voting_start'));
        // $votingEnd = Carbon::parse(config('election.voting_end'));
        // return now()->between($votingStart, $votingEnd);
    }
}