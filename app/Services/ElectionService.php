<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Cache;

class ElectionService
{
    /**
     * Check if election results are published
     * This is determined by the existence of a special permission
     */
    public static function areResultsPublished(): bool
    {
        return Cache::remember('election.results_published', 300, function () {
            try {
                return Permission::where('name', 'results-published-flag')->exists();
            } catch (\Exception $e) {
                // Table may not exist yet (e.g., during testing)
                return false;
            }
        });
    }

    /**
     * Publish election results
     * Creates a special permission that acts as a flag
     */
    public static function publishResults(): bool
    {
        try {
            Permission::firstOrCreate([
                'name' => 'results-published-flag',
                'guard_name' => 'web'
            ]);

            // Clear cache to immediately reflect changes
            Cache::forget('election.results_published');

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Unpublish election results (for testing/rollback)
     */
    public static function unpublishResults(): bool
    {
        try {
            Permission::where('name', 'results-published-flag')->delete();
            Cache::forget('election.results_published');

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if user can publish results
     */
    public static function canPublishResults($user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }

        return $user && $user->can('publish-election-results');
    }

    /**
     * Check if user can view results
     */
    public static function canViewResults($user = null): bool
    {
        // If results are published, everyone can view
        if (self::areResultsPublished()) {
            return true;
        }

        // Otherwise, only users with permission can view
        if (!$user) {
            $user = auth()->user();
        }

        return $user && $user->can('view-election-results');
    }

    /**
     * Check if voting has started (based on codes with voting_started_at)
     */
    public static function hasVotingStarted(): bool
    {
        return Cache::remember('election.voting_started', 300, function () {
            return \App\Models\Code::whereNotNull('voting_started_at')->exists();
        });
    }

    /**
     * Check if voting period is currently active
     * This now checks for a voting-period-active permission flag
     */
    public static function isVotingPeriodActive(): bool
    {
        return Cache::remember('election.voting_period_active', 300, function () {
            return Permission::where('name', 'voting-period-active-flag')->exists();
        });
    }

    /**
     * Start the voting period
     */
    public static function startVotingPeriod(): bool
    {
        try {
            Permission::firstOrCreate([
                'name' => 'voting-period-active-flag',
                'guard_name' => 'web'
            ]);

            // Clear cache to immediately reflect changes
            Cache::forget('election.voting_period_active');

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * End the voting period
     */
    public static function endVotingPeriod(): bool
    {
        try {
            // Remove voting period flag
            Permission::where('name', 'voting-period-active-flag')->delete();

            // Terminate all active voting sessions
            \App\Models\Code::where('can_vote_now', true)->update(['can_vote_now' => false]);

            // Clear cache
            Cache::forget('election.voting_period_active');

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if results should be visible based on voting period
     * Results are visible:
     * 1. Before voting starts (for trust/verification - should show zero)
     * 2. After voting ends (if results are published)
     * Results are hidden:
     * 1. During voting period (once voting has started but not ended)
     */
    public static function canViewResultsDuringVotingPeriod($user = null): bool
    {
        $hasVotingStarted = self::hasVotingStarted();
        $isVotingActive = self::isVotingPeriodActive();

        // If voting hasn't started yet, results can be viewed (should be zero)
        if (!$hasVotingStarted) {
            return true;
        }

        // If voting has started and is still active, hide results
        if ($hasVotingStarted && $isVotingActive) {
            return false;
        }

        // After voting period ends, check normal result publication rules
        return self::canViewResults($user);
    }

    /**
     * Get election status for frontend
     */
    public static function getElectionStatus(): array
    {
        return [
            'is_active' => config('election.is_active', true),
            'results_published' => self::areResultsPublished(),
            'voting_started' => self::hasVotingStarted(),
            'voting_period_active' => self::isVotingPeriodActive()
        ];
    }
}