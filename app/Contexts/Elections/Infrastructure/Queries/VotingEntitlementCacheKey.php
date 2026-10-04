<?php

namespace App\Contexts\Elections\Infrastructure\Queries;

use App\Models\Election;

/**
 * VotingEntitlementCacheKey — the single place that names the voter-standing cache key.
 *
 * user.{u}.voter.v3.{e}.{policyFingerprint} (PBDIGIT-213 C1 + C3, ADR §2.5):
 * - v3: results cached under the superseded per-site predicates (v2) are never reused;
 * - policyFingerprint: derived from the election's stored admission policy, so a policy
 *   change makes every earlier standing unreachable — deterministic invalidation
 *   without enumerating voters (the default cache store has no tags).
 *
 * Evidence and membership changes forget the single (u, e) key under the CURRENT
 * fingerprint (forgetCurrent).
 */
final class VotingEntitlementCacheKey
{
    public static function for(string $userId, string $electionId, string $policyFingerprint): string
    {
        return "user.{$userId}.voter.v3.{$electionId}.{$policyFingerprint}";
    }

    public static function current(string $userId, string $electionId): string
    {
        return self::for($userId, $electionId, self::policyFingerprintOf($electionId));
    }

    public static function forgetCurrent(string $userId, string $electionId): void
    {
        \Illuminate\Support\Facades\Cache::forget(self::current($userId, $electionId));
    }

    public static function policyFingerprintOf(string $electionId): string
    {
        $stored = Election::withoutGlobalScope('tenant')->whereKey($electionId)->value('voter_admission_policy');

        return substr(md5(is_string($stored) ? $stored : json_encode($stored)), 0, 12);
    }
}
