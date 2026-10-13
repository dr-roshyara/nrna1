<?php

namespace App\Services\Voting;

use App\Models\Code;
use App\Models\Election;
use Illuminate\Support\Facades\Log;

/**
 * The one reading of the per-IP vote limit (PBDIGIT-278 item 8, Human PO 2026-10-13). Used the same way at start
 * (ElectionVotingController), code entry (CodeController) and verification (VoteController::vote_post_check).
 *
 * - Counting (the existing intent): completed votes from this IP in this election, from codes.client_ip with
 *   has_voted. (voter_slugs.step_1_ip, used by the start step before, is never written for real elections.)
 * - Limit: a whitelisted IP has none; an enabled election restriction (ip_restriction_enabled +
 *   ip_restriction_max_per_ip) wins; otherwise MAX_USE_IP_ADDRESS (config app.max_use_clientIP); otherwise 10.
 * - MAX_USE_IP_ADDRESS: not set or empty → 10; a positive whole number → exactly that (1 stays 1); anything else is
 *   invalid → ignored (10) with a warning that names the reason and type, never the value.
 *
 * The separate cast-time constitutional limit (trust policy, maxVotesPerIp) is not this rule and is unchanged.
 */
final class IpVoteLimit
{
    public const DEFAULT_LIMIT = 10;

    public static function globalLimit(mixed $raw): int
    {
        if ($raw === null || $raw === '') {
            return self::DEFAULT_LIMIT;
        }

        $value = match (true) {
            is_int($raw) => $raw,
            is_string($raw) && preg_match('/^-?[1-9][0-9]*$|^0$/', $raw) === 1 => (int) $raw,
            default => null,
        };
        if ($value === null) {
            return self::invalid('not_integer', $raw);
        }
        if ($value <= 0) {
            return self::invalid('non_positive', $raw);
        }

        return $value;
    }

    public static function configuredGlobalLimit(): int
    {
        return self::globalLimit(config('app.max_use_clientIP'));
    }

    /** The limit for this IP in this election, or null when the IP is whitelisted (no limit). */
    public static function limitFor(Election $election, string $ip): ?int
    {
        if ($election->ip_whitelist && $election->isIpWhitelisted($ip)) {
            return null;
        }
        $electionMax = $election->ip_restriction_max_per_ip;
        if ($election->isIpRestricted() && is_numeric($electionMax) && (int) $electionMax > 0) {
            return (int) $electionMax;
        }

        return self::configuredGlobalLimit();
    }

    /** Completed votes from this IP in this election. */
    public static function votesFrom(Election $election, string $ip): int
    {
        return Code::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('client_ip', $ip)
            ->where('has_voted', true)
            ->count();
    }

    /** The readable refusal when the limit is reached, else null. */
    public static function refusal(Election $election, string $ip): ?string
    {
        $limit = self::limitFor($election, $ip);
        if ($limit === null || self::votesFrom($election, $ip) < $limit) {
            return null;
        }
        Log::warning('IP vote limit reached', ['election_id' => $election->id, 'limit' => $limit]);

        return self::refusalMessage($limit);
    }

    public static function refusalMessage(int $limit): string
    {
        return "The maximum of {$limit} vote(s) from your network has been reached.";
    }

    private static function invalid(string $reason, mixed $raw): int
    {
        Log::warning('max_use_ip_address_invalid', ['reason' => $reason, 'type' => get_debug_type($raw)]);

        return self::DEFAULT_LIMIT;
    }
}
