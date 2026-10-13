<?php

namespace App\Services\Voting;

use App\Domain\Election\Security\IpVoteGroup;
use App\Models\Code;
use App\Models\Election;
use App\Models\User;
use App\Services\ElectionAuditService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * The one reading of the per-IP vote limit (PBDIGIT-278 item 8, Human PO 2026-10-13). Used the same way at start
 * (ElectionVotingController), code entry (CodeController), verification (VoteController::vote_post_check) and, under
 * a per-network lock, at the vote itself (VoteController::store via castRefusal, PBDIGIT-288).
 *
 * - Counting: completed votes from this IP's group in this election, from codes.client_ip with has_voted. The cast
 *   records the IP at the vote there (PBDIGIT-288 item 3); IPv6 counts by /64 (item 4, IpVoteGroup).
 *   (voter_slugs.step_1_ip, used by the start step before, is never written for real elections.)
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

    public const LOCK_NAMESPACE = 'ip_vote_limit';

    public const BUSY_MESSAGE = 'Another vote from your network is being recorded. Please try again in a moment.';

    public static function globalLimit(mixed $raw): int
    {
        [$value, $invalidReason] = self::parseGlobal($raw);
        if ($invalidReason !== null) {
            return self::invalid($invalidReason, $raw);
        }

        return $value ?? self::DEFAULT_LIMIT;
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

    /**
     * Completed votes from this IP's group in this election (PBDIGIT-288 item 4): an IPv4 address on its own, an IPv6
     * address with its /64 network (voting.ipv6_vote_group_prefix), an unreadable value by exact match.
     */
    public static function votesFrom(Election $election, string $ip): int
    {
        $voted = Code::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('has_voted', true);

        if (($ipv4 = IpVoteGroup::spellingsOfIpv4($ip)) !== null) {
            return $voted->whereIn('client_ip', $ipv4)->count();
        }
        if (@inet_pton($ip) === false) {
            return $voted->where('client_ip', $ip)->count();
        }

        $prefix = self::ipv6Prefix();
        $group = IpVoteGroup::of($ip, $prefix);

        return $voted->where('client_ip', 'like', '%:%')->pluck('client_ip')
            ->filter(fn ($stored) => is_string($stored) && IpVoteGroup::of($stored, $prefix) === $group)
            ->count();
    }

    /**
     * At the vote (PBDIGIT-288 items 1–2), inside the cast transaction: wait for this election's lock on the IP's
     * group, then the same refusal as the earlier steps. The lock is a PostgreSQL transaction-level advisory lock, so
     * it is held until the cast commits or rolls back: votes from one network are counted and recorded one at a
     * time. If the lock is not free within voting.ip_lock_timeout_ms the vote is refused safely (BUSY_MESSAGE) and
     * may be retried. A whitelisted IP has no limit and takes no lock.
     */
    public static function castRefusal(Election $election, string $ip): ?string
    {
        if (self::limitFor($election, $ip) === null) {
            return null;
        }
        if (! self::lockNetwork($election, $ip)) {
            Log::warning('IP vote limit: network busy at the vote', ['election_id' => $election->id]);

            return self::BUSY_MESSAGE;
        }

        return self::refusal($election, $ip);
    }

    /** The advisory-lock key for this election and the IP's group (the namespace is LOCK_NAMESPACE). */
    public static function lockKey(Election $election, string $ip): string
    {
        return $election->id.'|'.IpVoteGroup::of($ip, self::ipv6Prefix());
    }

    private static function lockNetwork(Election $election, string $ip): bool
    {
        if (DB::transactionLevel() === 0) {
            throw new \LogicException('The per-IP vote lock must be taken inside the cast transaction.');
        }
        if (DB::getDriverName() !== 'pgsql') {
            return true; // the platform runs on PostgreSQL; other drivers count without the lock
        }

        $previous = DB::selectOne("select current_setting('lock_timeout') as value")->value;
        $timeout = max(1, (int) config('voting.ip_lock_timeout_ms', 5000));
        DB::select("select set_config('lock_timeout', ?, true)", [$timeout.'ms']);
        try {
            DB::select('select pg_advisory_xact_lock(hashtext(?), hashtext(?))', [self::LOCK_NAMESPACE, self::lockKey($election, $ip)]);
        } catch (QueryException $e) {
            if ($e->getCode() === '55P03') { // lock_not_available: the caller refuses and rolls back
                return false;
            }
            throw $e;
        }
        DB::select("select set_config('lock_timeout', ?, true)", [$previous]);

        return true;
    }

    private static function ipv6Prefix(): int
    {
        $prefix = config('voting.ipv6_vote_group_prefix', IpVoteGroup::DEFAULT_IPV6_PREFIX);

        return is_numeric($prefix) ? (int) $prefix : IpVoteGroup::DEFAULT_IPV6_PREFIX;
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

    /**
     * Where the limit for this IP comes from (PBDIGIT-288 item 5): 'whitelist' (no limit), 'election' (the enabled
     * election restriction), 'env' (a valid MAX_USE_IP_ADDRESS) or 'default' (10). Mirrors limitFor().
     */
    public static function limitSource(Election $election, string $ip): string
    {
        if (self::limitFor($election, $ip) === null) {
            return 'whitelist';
        }
        $electionMax = $election->ip_restriction_max_per_ip;
        if ($election->isIpRestricted() && is_numeric($electionMax) && (int) $electionMax > 0) {
            return 'election';
        }
        [$value, $invalidReason] = self::parseGlobal(config('app.max_use_clientIP'));

        return $value !== null && $invalidReason === null ? 'env' : 'default';
    }

    /**
     * The admins' record of a refusal (PBDIGIT-288 item 5): one `ip_blocked` audit entry at every refusal site, with
     * the limit that applied and its source. $reason is 'limit_exceeded' or 'network_busy'; $step is 'start',
     * 'code_entry', 'verification' or 'vote'. The IP is passed to the audit service as before (its retention is
     * PBDIGIT-288 item 6); it is never put into the metadata.
     */
    public static function recordRefusal(Election $election, ?User $user, string $step, string $ip, string $reason = 'limit_exceeded'): void
    {
        app(ElectionAuditService::class)->log(
            election: $election,
            event: 'ip_blocked',
            user: $user,
            category: 'voters',
            ip: $ip,
            metadata: [
                'reason' => $reason,
                'step' => $step,
                'max' => self::limitFor($election, $ip),
                'limit_source' => self::limitSource($election, $ip),
            ]
        );
    }

    /** @return array{0: ?int, 1: ?string} [the value or null when not set, the invalid reason or null] */
    private static function parseGlobal(mixed $raw): array
    {
        if ($raw === null || $raw === '') {
            return [null, null];
        }

        $value = match (true) {
            is_int($raw) => $raw,
            is_string($raw) && preg_match('/^-?[1-9][0-9]*$|^0$/', $raw) === 1 => (int) $raw,
            default => null,
        };
        if ($value === null) {
            return [null, 'not_integer'];
        }
        if ($value <= 0) {
            return [null, 'non_positive'];
        }

        return [$value, null];
    }

    private static function invalid(string $reason, mixed $raw): int
    {
        Log::warning('max_use_ip_address_invalid', ['reason' => $reason, 'type' => get_debug_type($raw)]);

        return self::DEFAULT_LIMIT;
    }
}
