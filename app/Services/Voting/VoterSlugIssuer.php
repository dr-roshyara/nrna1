<?php

namespace App\Services\Voting;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Infrastructure\Queries\EloquentVotingEntitlementQuery;
use App\Exceptions\Voting\VoterSlugIssuanceConflict;
use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Database\Connection;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LogicException;

/**
 * Issues, reuses or replaces a voter's voting link for a REAL election (PBDIGIT-274, plan section 5; S3).
 *
 * Business rule: an entitled voter holds exactly one current, valid link. A valid link is reused unchanged;
 * an expired or deactivated one is retired (soft-deleted, history and steps kept) and replaced by a new row;
 * a voter who has voted, or is no longer entitled, gets no link.
 *
 * - Call it OUTSIDE any transaction (LogicException otherwise): each attempt is its own transaction, so a
 *   retirement is committed before a refusal is returned.
 * - Each attempt locks the voter's `codes` row (the cast's participation update writes the same row, so issuer
 *   and cast serialise there), then the current slug row. Reads run without model events, so the VoterSlug
 *   `retrieved` hook cannot write on refusal paths.
 * - Unique violations are classified by constraint name: a slug-string collision gets a new string (at most
 *   three attempts); an active-slug collision (a concurrent issuer won) is decided again once; anything else is
 *   a VoterSlugIssuanceConflict. Concurrency safety is NOT claimed until G6 passes.
 * - Entitlement must be FRESH: build it with self::fresh() (the container's VotingEntitlementQuery is the
 *   cached one).
 *
 * Not wired to any caller yet (S4/S5). Demo elections keep their own flow (decision D1).
 */
class VoterSlugIssuer
{
    public const CONFLICT_SLUG_STRING = 'slug_string';
    public const CONFLICT_ACTIVE_SLUG = 'active_slug';
    public const CONFLICT_UNKNOWN = 'unknown';

    private const SLUG_STRING_ATTEMPTS = 3;
    private const ACTIVE_SLUG_REDECISIONS = 1;

    public function __construct(private readonly VotingEntitlementQuery $entitlement)
    {
    }

    /** The issuer with the uncached entitlement query (plan section 5, step 4). */
    public static function fresh(): self
    {
        return new self(app(EloquentVotingEntitlementQuery::class));
    }

    public function issue(User $voter, Election $election): VoterSlugIssuance
    {
        if ($this->transactionLevel() !== 0) {
            throw new LogicException('VoterSlugIssuer::issue() must be called outside any database transaction.');
        }
        if (trim((string) $election->type) !== 'real') {
            throw new LogicException('VoterSlugIssuer serves real elections only; demo elections keep their own flow.');
        }
        $lifetime = VoterSlugLifetime::fromConfig(config('voting.time_in_minutes'));   // misconfigured → nothing issued

        $slugAttempts = 0;
        $redecisions = 0;
        while (true) {
            try {
                return $this->connection()->transaction(fn () => $this->decide($voter, $election, $lifetime));
            } catch (UniqueConstraintViolationException $e) {
                $kind = self::classify($e);
                if ($kind === self::CONFLICT_SLUG_STRING && ++$slugAttempts < self::SLUG_STRING_ATTEMPTS) {
                    continue;
                }
                if ($kind === self::CONFLICT_ACTIVE_SLUG && $redecisions++ < self::ACTIVE_SLUG_REDECISIONS) {
                    continue;
                }
                throw new VoterSlugIssuanceConflict($kind, $e);
            }
        }
    }

    /** Which unique rule a PostgreSQL 23505 violated, read from the driver message (PgUniqueViolationShapeTest). */
    public static function classify(UniqueConstraintViolationException $e): string
    {
        $previous = $e->getPrevious();
        $message = (string) (($previous instanceof \PDOException ? ($previous->errorInfo[2] ?? null) : null) ?? $e->getMessage());

        return match (true) {
            str_contains($message, '"voter_slugs_slug_unique"') => self::CONFLICT_SLUG_STRING,
            str_contains($message, '"voter_slugs_active_election_user_unique"') => self::CONFLICT_ACTIVE_SLUG,
            default => self::CONFLICT_UNKNOWN,
        };
    }

    /** One attempt, inside one transaction (plan section 5, steps 1–7). */
    private function decide(User $voter, Election $election, VoterSlugLifetime $lifetime): VoterSlugIssuance
    {
        $db = $this->connection();
        $now = now();

        // 1. Lock the voter's code row (absent before the code step: then no cast can run concurrently).
        $code = $db->table('codes')
            ->where('election_id', $election->id)->where('user_id', $voter->id)
            ->lockForUpdate()->first();

        // 2. Lock the current slug row, read without model events.
        $current = VoterSlug::withoutEvents(fn () => VoterSlug::withoutGlobalScope('tenant')
            ->where('election_id', $election->id)->where('user_id', $voter->id)
            ->lockForUpdate()->first());
        $this->afterLocks();

        // 3. Voted (authoritative code row, or any slug row incl. soft-deleted): no link, nothing written.
        $votedSlug = $db->table('voter_slugs')
            ->where('election_id', $election->id)->where('user_id', $voter->id)->where('has_voted', true)
            ->exists();
        if (($code !== null && (bool) $code->has_voted) || $votedSlug) {
            return VoterSlugIssuance::alreadyVoted();
        }

        // 4. Fresh entitlement; not entitled → retire the current link (committed with this attempt).
        $standing = $this->entitlement->decide((string) $voter->id, (string) $election->id);
        if (! $standing->isEntitled()) {
            if ($current !== null) {
                $this->retire($current, $now);
            }

            return VoterSlugIssuance::notEntitled($standing);
        }

        // 5. A valid current link is reused unchanged.
        if ($current !== null && $lifetime->isReusable($current, $now)) {
            return VoterSlugIssuance::reused($current);
        }

        // 6. Otherwise retire the old link (history kept) and insert a new one.
        if ($current !== null) {
            $this->retire($current, $now);
        }
        $this->beforeInsert();

        $slug = new VoterSlug();
        $slug->forceFill([
            'id' => (string) Str::uuid(),
            'organisation_id' => $election->organisation_id,
            'election_id' => $election->id,
            'user_id' => $voter->id,
            'slug' => $this->newSlugString(),
            'expires_at' => $now->copy()->addMinutes($lifetime->minutes()),
            'is_active' => true,
            'can_vote_now' => true,
            'has_voted' => false,
            'status' => 'active',
            'current_step' => 1,
        ]);
        $slug->setConnection($db->getName())->save();

        return VoterSlugIssuance::issued($slug);
    }

    /** Soft-delete and deactivate, without model events; the row and its steps stay as history. */
    private function retire(VoterSlug $slug, \Carbon\CarbonInterface $now): void
    {
        VoterSlug::withoutGlobalScope('tenant')->whereKey($slug->id)->update([
            'is_active' => false,
            'can_vote_now' => false,
            'status' => 'expired',
            'deleted_at' => $now,
        ]);
    }

    private function connection(): Connection
    {
        return DB::connection();
    }

    /** Seam for the calling-contract test. */
    protected function transactionLevel(): int
    {
        return $this->connection()->transactionLevel();
    }

    /** Seam for the slug-collision test; production: 32 random characters, as ElectionVotingController::start. */
    protected function newSlugString(): string
    {
        return Str::random(32);
    }

    /** Seam for the S3b conflict simulation and the G6 pause point. No-op in production; not reachable from any input. */
    protected function beforeInsert(): void
    {
    }

    /**
     * G6 pause point (decision D3): runs while this attempt holds the codes-row and slug-row locks. No-op in
     * production; only a test subclass (tests/Isolated/Pbdigit274/Concurrency/g6-worker.php) overrides it.
     */
    protected function afterLocks(): void
    {
    }
}
