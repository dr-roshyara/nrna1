<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * ElectionCacheService — Tenant-isolated cache key management
 *
 * Responsibility:
 * - Generate cache keys with organisation_id prefix for tenancy isolation
 * - Clear all voter-related cache keys atomically
 * - Handle legacy cache key transition (both old and new formats)
 *
 * This service owns all cache key formatting for election voter data.
 * Single source of truth prevents key mismatches.
 *
 * Migration note: Legacy keys use only election_id.
 * New keys use organisation_id prefix for multi-tenant isolation.
 * Both are forgotten during transition period.
 */
final class ElectionCacheService
{
    /**
     * Generate a tenant-isolated cache key for election data
     *
     * Format: org.{organisation_id}.election.{election_id}.{suffix}
     * Ensures no cross-tenant cache collisions.
     */
    public static function keyFor(string $organisationId, string $electionId, string $suffix): string
    {
        return "org.{$organisationId}.election.{$electionId}.{$suffix}";
    }

    /**
     * Forget all voter-related cache keys for an election
     *
     * Clears:
     * - voter_count
     * - voter_stats
     * - eligible_voters
     *
     * Also clears legacy format keys for transition period.
     * Called from handlers after voter mutations (outside transaction).
     */
    public static function forgetVoterKeys(string $organisationId, string $electionId): void
    {
        // New tenant-isolated keys
        Cache::forget(self::keyFor($organisationId, $electionId, 'voter_count'));
        Cache::forget(self::keyFor($organisationId, $electionId, 'voter_stats'));
        Cache::forget(self::keyFor($organisationId, $electionId, 'eligible_voters'));

        // Legacy keys (kept during transition, self-expire at 5-min TTL)
        Cache::forget("election.{$electionId}.voter_count");
        Cache::forget("election.{$electionId}.voter_stats");
    }
}
