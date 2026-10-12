<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Exceptions\Voting\VoterSlugIssuanceConflict;
use App\Exceptions\Voting\VoterSlugLifetimeMisconfigured;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\Voting\VoterSlugIssuance;
use App\Services\Voting\VoterSlugIssuer;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LogicException;
use PDOException;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-274 S3a / S3b: the voter-slug issuer on real PostgreSQL (plan sections 5 and 5.1).
 *
 * Runs only through phpunit.pbdigit274.xml on `pbdigit274_isolated_test`. Data is COMMITTED (the issuer refuses
 * to run inside a transaction); each test uses its own organisation, election and voters and deletes them
 * afterwards. Entitlement is a stand-in port (Entitled / NotActive): the entitlement rules themselves are tested
 * elsewhere; here only the issuer's reaction to them is the subject.
 *
 * S3b cases marked SIMULATION inject the error through a test seam; they prove the retry logic, not real
 * concurrency (that is G6).
 */
final class VoterSlugIssuerTest extends IsolatedCommittedDatabaseTestCase
{
    private Organisation $org;
    private Election $election;
    private User $voter;

    /** @var list<string> */
    private array $organisations = [];
    /** @var list<string> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = $this->organisation();
        $this->election = $this->electionOf($this->org);
        $this->voter = $this->user();
    }

    protected function tearDown(): void
    {
        // Hard delete of this test's own fixtures on the isolated database; FK cascades remove their
        // elections, codes, voter slugs and steps.
        DB::table('organisations')->whereIn('id', $this->organisations)->delete();
        DB::table('users')->whereIn('id', $this->users)->delete();
        parent::tearDown();
    }

    // ── fixtures ──────────────────────────────────────────────────────────

    private function organisation(): Organisation
    {
        $org = Organisation::factory()->tenant()->create();
        $this->organisations[] = $org->id;

        return $org;
    }

    private function electionOf(Organisation $org): Election
    {
        return Election::factory()->forOrganisation($org)->real()->create();
    }

    private function user(): User
    {
        $user = User::factory()->create();
        $this->users[] = $user->id;

        return $user;
    }

    /** A slug row inserted directly (no model events), so expired rows stay exactly as given. */
    private function slugRow(array $attributes = []): string
    {
        $id = (string) Str::uuid();
        DB::table('voter_slugs')->insert($attributes + [
            'id' => $id,
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
            'user_id' => $this->voter->id,
            'slug' => Str::random(32),
            'is_active' => true,
            'has_voted' => false,
            'status' => 'active',
            'current_step' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'expires_at' => now()->addMinutes(30),
        ]);

        return $id;
    }

    /** Inserted directly (CodeFactory dereferences the platform organisation unconditionally). */
    private function codeRow(bool $hasVoted): void
    {
        DB::table('codes')->insert([
            'id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'code1' => (string) random_int(100000, 999999),
            'has_voted' => $hasVoted, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function issuer(VotingEntitlementReason $standing = VotingEntitlementReason::Entitled): VoterSlugIssuer
    {
        return new VoterSlugIssuer($this->entitlement($standing));
    }

    private function entitlement(VotingEntitlementReason $standing): VotingEntitlementQuery
    {
        return new class ($standing) implements VotingEntitlementQuery {
            public function __construct(private VotingEntitlementReason $standing) {}
            public function standingOf(string $userId, string $electionId): VotingEntitlementReason { return $this->standing; }
            public function decide(string $userId, string $electionId): VotingEntitlementReason { return $this->standing; }
        };
    }

    /** @return list<array<string, mixed>> every slug row of the given pair, soft-deleted included */
    private function rowsOf(?string $userId = null, ?string $electionId = null): array
    {
        return array_map(fn ($r) => (array) $r, DB::table('voter_slugs')
            ->where('user_id', $userId ?? $this->voter->id)->where('election_id', $electionId ?? $this->election->id)
            ->orderBy('created_at')->orderBy('id')->get()->all());
    }

    private function current(): array
    {
        return array_values(array_filter($this->rowsOf(), fn ($r) => $r['deleted_at'] === null));
    }

    private static function uniqueViolation(string $constraint): UniqueConstraintViolationException
    {
        $message = 'duplicate key value violates unique constraint "'.$constraint.'"';
        $pdo = new PDOException($message, 23505);
        $pdo->errorInfo = ['23505', 7, $message];

        return new UniqueConstraintViolationException('pgsql', 'insert into "voter_slugs" (simulated)', [], $pdo);
    }

    // ── S3a: plan 5.1 ─────────────────────────────────────────────────────

    public function test_no_link_yet_issues_exactly_one(): void
    {
        $result = $this->issuer()->issue($this->voter, $this->election);

        $this->assertSame(VoterSlugIssuance::ISSUED, $result->outcome);
        $this->assertCount(1, $this->rowsOf());
        $row = $this->current()[0];
        $this->assertSame($result->slug->id, $row['id']);
        $this->assertTrue((bool) $row['is_active']);
        $this->assertSame('active', $row['status']);
        $minutes = (int) config('voting.time_in_minutes');
        $this->assertEqualsWithDelta(now()->addMinutes($minutes)->timestamp, strtotime($row['expires_at']), 5, 'expires_at = now + T');
    }

    public function test_a_valid_link_is_reused_unchanged(): void
    {
        $id = $this->slugRow();
        $before = $this->rowsOf();

        $result = $this->issuer()->issue($this->voter, $this->election);

        $this->assertSame(VoterSlugIssuance::REUSED, $result->outcome);
        $this->assertSame($id, $result->slug->id);
        $this->assertSame($before, $this->rowsOf(), 'reuse writes nothing (L-4)');
    }

    public function test_an_expired_link_is_replaced_and_history_and_steps_are_kept(): void
    {
        $old = $this->slugRow(['created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);
        $oldString = DB::table('voter_slugs')->where('id', $old)->value('slug');
        DB::table('voter_slug_steps')->insert([
            'id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'voter_slug_id' => $old,
            'election_id' => $this->election->id, 'step' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $result = $this->issuer()->issue($this->voter, $this->election);

        $this->assertSame(VoterSlugIssuance::ISSUED, $result->outcome);
        $this->assertNotSame($old, $result->slug->id);
        $this->assertNotSame($oldString, $result->slug->slug);
        $retired = (array) DB::table('voter_slugs')->where('id', $old)->first();
        $this->assertNotNull($retired['deleted_at'], 'old link soft-deleted, kept as history');
        $this->assertFalse((bool) $retired['is_active']);
        $this->assertSame('expired', $retired['status']);
        $this->assertSame(1, DB::table('voter_slug_steps')->where('voter_slug_id', $old)->count(), 'steps kept');
        $this->assertCount(1, $this->current());
    }

    public function test_a_deactivated_link_is_replaced(): void
    {
        $old = $this->slugRow(['is_active' => false]);

        $result = $this->issuer()->issue($this->voter, $this->election);

        $this->assertSame(VoterSlugIssuance::ISSUED, $result->outcome);
        $this->assertNotSame($old, $result->slug->id);
        $this->assertCount(2, $this->rowsOf());
        $this->assertCount(1, $this->current());
    }

    public function test_voted_according_to_the_code_row_refuses_without_writing(): void
    {
        $this->slugRow();
        $this->codeRow(hasVoted: true);
        $before = $this->rowsOf();

        $result = $this->issuer()->issue($this->voter, $this->election);

        $this->assertSame(VoterSlugIssuance::ALREADY_VOTED, $result->outcome);
        $this->assertFalse($result->hasLink());
        $this->assertSame($before, $this->rowsOf());
    }

    public function test_a_voted_soft_deleted_slug_refuses_without_writing(): void
    {
        $this->slugRow(['has_voted' => true, 'status' => 'voted', 'deleted_at' => now()->subMinute()]);
        $before = $this->rowsOf();

        $result = $this->issuer()->issue($this->voter, $this->election);

        $this->assertSame(VoterSlugIssuance::ALREADY_VOTED, $result->outcome);
        $this->assertSame($before, $this->rowsOf());
    }

    public function test_a_voted_current_slug_refuses_without_writing(): void
    {
        $this->slugRow(['has_voted' => true, 'status' => 'voted']);
        $before = $this->rowsOf();

        $this->assertSame(VoterSlugIssuance::ALREADY_VOTED, $this->issuer()->issue($this->voter, $this->election)->outcome);
        $this->assertSame($before, $this->rowsOf());
    }

    public function test_the_retrieved_hook_writes_nothing_on_a_refusal(): void
    {
        // Expired but still marked active: loading it WITH model events would mark it expired.
        $id = $this->slugRow(['created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);
        $this->codeRow(hasVoted: true);

        $this->assertSame(VoterSlugIssuance::ALREADY_VOTED, $this->issuer()->issue($this->voter, $this->election)->outcome);

        $row = (array) DB::table('voter_slugs')->where('id', $id)->first();
        $this->assertTrue((bool) $row['is_active']);
        $this->assertSame('active', $row['status']);
    }

    public function test_lost_entitlement_retires_the_current_link_and_refuses(): void
    {
        $id = $this->slugRow();

        $result = $this->issuer(VotingEntitlementReason::NotActive)->issue($this->voter, $this->election);

        $this->assertSame(VoterSlugIssuance::NOT_ENTITLED, $result->outcome);
        $this->assertSame(VotingEntitlementReason::NotActive, $result->refusal);
        $row = (array) DB::table('voter_slugs')->where('id', $id)->first();
        $this->assertNotNull($row['deleted_at'], 'the retirement is committed (L-8)');
        $this->assertCount(0, $this->current());
    }

    public function test_not_entitled_without_a_link_writes_nothing(): void
    {
        $result = $this->issuer(VotingEntitlementReason::NotRegistered)->issue($this->voter, $this->election);

        $this->assertSame(VoterSlugIssuance::NOT_ENTITLED, $result->outcome);
        $this->assertSame([], $this->rowsOf());
    }

    public function test_other_voters_and_elections_are_untouched(): void
    {
        $otherVoter = $this->user();
        $otherElection = $this->electionOf($this->org);
        $this->slugRow(['user_id' => $otherVoter->id, 'created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);
        $this->slugRow(['election_id' => $otherElection->id, 'created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);
        $otherVoterBefore = $this->rowsOf($otherVoter->id);
        $otherElectionBefore = $this->rowsOf(null, $otherElection->id);

        $this->issuer()->issue($this->voter, $this->election);

        $this->assertSame($otherVoterBefore, $this->rowsOf($otherVoter->id));
        $this->assertSame($otherElectionBefore, $this->rowsOf(null, $otherElection->id));
    }

    public function test_an_invalid_lifetime_issues_nothing(): void
    {
        config(['voting.time_in_minutes' => 0]);
        try {
            $this->issuer()->issue($this->voter, $this->election);
            $this->fail('expected VoterSlugLifetimeMisconfigured');
        } catch (VoterSlugLifetimeMisconfigured) {
        }
        $this->assertSame([], $this->rowsOf());
    }

    public function test_it_refuses_to_run_inside_a_transaction(): void
    {
        $this->expectException(LogicException::class);
        DB::transaction(fn () => $this->issuer()->issue($this->voter, $this->election));
    }

    // ── S3b: unique conflicts ─────────────────────────────────────────────

    public function test_a_real_slug_string_collision_retries_with_a_new_string(): void
    {
        $otherVoter = $this->user();
        $this->slugRow(['user_id' => $otherVoter->id, 'slug' => 'TAKEN-STRING-0000000000000000000']);

        $issuer = new class ($this->entitlement(VotingEntitlementReason::Entitled)) extends VoterSlugIssuer {
            public int $calls = 0;
            protected function newSlugString(): string
            {
                return ++$this->calls === 1 ? 'TAKEN-STRING-0000000000000000000' : parent::newSlugString();
            }
        };

        $result = $issuer->issue($this->voter, $this->election);

        $this->assertSame(VoterSlugIssuance::ISSUED, $result->outcome);
        $this->assertSame(2, $issuer->calls);
        $this->assertNotSame('TAKEN-STRING-0000000000000000000', $result->slug->slug);
        $this->assertCount(1, $this->current());
    }

    public function test_a_persistent_slug_string_collision_gives_up_after_three_attempts(): void
    {
        $otherVoter = $this->user();
        $this->slugRow(['user_id' => $otherVoter->id, 'slug' => 'TAKEN-STRING-1111111111111111111']);

        $issuer = new class ($this->entitlement(VotingEntitlementReason::Entitled)) extends VoterSlugIssuer {
            public int $calls = 0;
            protected function newSlugString(): string
            {
                $this->calls++;

                return 'TAKEN-STRING-1111111111111111111';
            }
        };

        try {
            $issuer->issue($this->voter, $this->election);
            $this->fail('expected VoterSlugIssuanceConflict');
        } catch (VoterSlugIssuanceConflict $e) {
            $this->assertSame(VoterSlugIssuer::CONFLICT_SLUG_STRING, $e->kind());
        }
        $this->assertSame(3, $issuer->calls);
        $this->assertSame([], $this->rowsOf(), 'no link was issued');
    }

    /** SIMULATION: a concurrent issuer winning the active-slug index is injected, not raced (G6 does that). */
    public function test_simulated_active_slug_conflict_is_decided_again_once(): void
    {
        $old = $this->slugRow(['created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);
        $issuer = new class ($this->entitlement(VotingEntitlementReason::Entitled)) extends VoterSlugIssuer {
            public int $calls = 0;
            protected function beforeInsert(): void
            {
                if (++$this->calls === 1) {
                    throw VoterSlugIssuerTest::injected('voter_slugs_active_election_user_unique');
                }
            }
        };

        $result = $issuer->issue($this->voter, $this->election);

        $this->assertSame(VoterSlugIssuance::ISSUED, $result->outcome);
        $this->assertSame(2, $issuer->calls);
        $this->assertNotNull(DB::table('voter_slugs')->where('id', $old)->value('deleted_at'), 'retired once, in the attempt that committed');
        $this->assertCount(1, $this->current());
    }

    /** SIMULATION. */
    public function test_simulated_repeated_active_slug_conflict_gives_up_without_a_link(): void
    {
        $old = $this->slugRow(['created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);
        $before = $this->rowsOf();
        $issuer = new class ($this->entitlement(VotingEntitlementReason::Entitled)) extends VoterSlugIssuer {
            public int $calls = 0;
            protected function beforeInsert(): void
            {
                $this->calls++;
                throw VoterSlugIssuerTest::injected('voter_slugs_active_election_user_unique');
            }
        };

        try {
            $issuer->issue($this->voter, $this->election);
            $this->fail('expected VoterSlugIssuanceConflict');
        } catch (VoterSlugIssuanceConflict $e) {
            $this->assertSame(VoterSlugIssuer::CONFLICT_ACTIVE_SLUG, $e->kind());
        }
        $this->assertSame(2, $issuer->calls);
        $this->assertSame($before, $this->rowsOf(), 'both attempts rolled back, including the retirement');
        $this->assertNull(DB::table('voter_slugs')->where('id', $old)->value('deleted_at'));
    }

    /** SIMULATION. */
    public function test_simulated_unknown_constraint_is_not_retried(): void
    {
        $issuer = new class ($this->entitlement(VotingEntitlementReason::Entitled)) extends VoterSlugIssuer {
            public int $calls = 0;
            protected function beforeInsert(): void
            {
                $this->calls++;
                throw VoterSlugIssuerTest::injected('some_other_unique');
            }
        };

        try {
            $issuer->issue($this->voter, $this->election);
            $this->fail('expected VoterSlugIssuanceConflict');
        } catch (VoterSlugIssuanceConflict $e) {
            $this->assertSame(VoterSlugIssuer::CONFLICT_UNKNOWN, $e->kind());
        }
        $this->assertSame(1, $issuer->calls);
        $this->assertSame([], $this->rowsOf());
    }

    /** Used by the simulation subclasses above. */
    public static function injected(string $constraint): UniqueConstraintViolationException
    {
        return self::uniqueViolation($constraint);
    }
}
