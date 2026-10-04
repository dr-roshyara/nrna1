<?php

namespace App\Contexts\Elections\Infrastructure\Queries;

/**
 * VotingEntitlementCacheKey — the single place that names the voter-standing cache key.
 *
 * Namespaced v3 (PBDIGIT-213): results cached under the earlier v2 key by the
 * superseded per-site predicates are never reused.
 */
final class VotingEntitlementCacheKey
{
    public static function for(string $userId, string $electionId): string
    {
        return "user.{$userId}.voter.v3.{$electionId}";
    }
}
