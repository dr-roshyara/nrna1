<?php

namespace App\Services;

use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling voter recovery scenarios
 *
 * When voters have issues during voting (network problems, browser crashes, etc.)
 * and the election committee decides they should be allowed to vote again.
 */
class VoterRecoveryService
{
    protected VoterSlugService $slugService;

    public function __construct(VoterSlugService $slugService)
    {
        $this->slugService = $slugService;
    }

    /**
     * Allow a voter to vote again by generating a new slug
     *
     * This handles the scenario where:
     * 1. Voter had an expired/problematic slug
     * 2. Election committee approves re-voting
     * 3. New fresh slug needed for clean voting process
     */
    public function allowRevote(User $user, string $reason = null, array $adminDetails = []): VoterSlug
    {
        Log::info('Voter recovery initiated', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'reason' => $reason,
            'admin_user' => $adminDetails['admin_name'] ?? 'system',
            'timestamp' => now(),
        ]);

        // Get all existing slugs for audit
        $existingSlugs = VoterSlug::where('user_id', $user->id)->get();

        // Log existing slug status for audit trail
        foreach ($existingSlugs as $existingSlug) {
            Log::info('Existing slug status during recovery', [
                'user_id' => $user->id,
                'slug' => $existingSlug->slug,
                'was_active' => $existingSlug->is_active,
                'was_expired' => $existingSlug->isExpired(),
                'was_at_step' => $existingSlug->current_step,
                'step_meta' => $existingSlug->step_meta,
            ]);
        }

        // Generate new slug (this automatically deactivates old ones)
        $newSlug = $this->slugService->generateSlugForUser($user);

        // Add recovery metadata
        $newSlug->update([
            'step_meta' => [
                'recovery_reason' => $reason,
                'recovery_timestamp' => now()->toISOString(),
                'admin_approved_by' => $adminDetails['admin_name'] ?? 'system',
                'previous_slugs_count' => $existingSlugs->count(),
                'is_recovery_slug' => true,
            ]
        ]);

        Log::info('Voter recovery completed', [
            'user_id' => $user->id,
            'new_slug' => $newSlug->slug,
            'expires_at' => $newSlug->expires_at,
            'recovery_successful' => true,
        ]);

        return $newSlug;
    }

    /**
     * Get voter recovery history for admin review
     */
    public function getRecoveryHistory(User $user): array
    {
        $slugs = VoterSlug::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $history = [];
        foreach ($slugs as $slug) {
            $history[] = [
                'slug' => $slug->slug,
                'created_at' => $slug->created_at,
                'expires_at' => $slug->expires_at,
                'is_active' => $slug->is_active,
                'is_expired' => $slug->isExpired(),
                'current_step' => $slug->current_step,
                'step_meta' => $slug->step_meta,
                'is_recovery' => isset($slug->step_meta['is_recovery_slug']),
                'recovery_reason' => $slug->step_meta['recovery_reason'] ?? null,
            ];
        }

        return $history;
    }

    /**
     * Check if user is eligible for recovery
     */
    public function canUserRecover(User $user): bool
    {
        // Business rules for recovery eligibility

        // 1. User must be a valid voter
        if (!$user->is_voter || !$user->can_vote) {
            return false;
        }

        // 2. User must not have successfully completed voting
        if ($user->has_voted) {
            return false; // Already voted successfully
        }

        // 3. Check for recent recovery attempts (prevent abuse)
        $recentRecoveries = VoterSlug::where('user_id', $user->id)
            ->whereJsonContains('step_meta->is_recovery_slug', true)
            ->where('created_at', '>', now()->subHours(2)) // Within last 2 hours
            ->count();

        if ($recentRecoveries >= 3) {
            return false; // Too many recovery attempts
        }

        return true;
    }

    /**
     * Get all users who might need recovery (for admin dashboard)
     */
    public function getUsersNeedingRecovery(): array
    {
        // Find users with expired slugs who haven't completed voting
        return User::whereHas('voterSlugs', function ($query) {
                $query->where('expires_at', '<', now())
                      ->where('current_step', '>', 1) // Started voting
                      ->where('current_step', '<', 5); // But didn't finish
            })
            ->where('is_voter', true)
            ->where('can_vote', true)
            ->where('has_voted', false)
            ->with(['voterSlugs' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->get()
            ->map(function ($user) {
                $lastSlug = $user->voterSlugs->first();
                return [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'last_attempt' => $lastSlug ? $lastSlug->created_at : null,
                    'was_at_step' => $lastSlug ? $lastSlug->current_step : null,
                    'slug_expired' => $lastSlug ? $lastSlug->isExpired() : null,
                    'needs_recovery' => true,
                ];
            })
            ->toArray();
    }
}