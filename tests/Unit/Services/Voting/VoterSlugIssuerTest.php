<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Voting;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Exceptions\Voting\VoterSlugIssuanceConflict;
use App\Exceptions\Voting\VoterSlugLifetimeMisconfigured;
use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\Voting\VoterSlugIssuance;
use App\Services\Voting\VoterSlugIssuer;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use LogicException;
use PDOException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeTest;

/**
 * PBDIGIT-274 S3: the database-free parts of the voter-slug issuer (plan section 5): classification of
 * PostgreSQL unique violations, the calling contract, refusals before any query, and the outcome object.
 * `pgsql` points at a non-existent socket and every test asserts that no query ran. The database behaviour is
 * tested in tests/Isolated/Pbdigit274/Database/VoterSlugIssuerTest.php.
 */
final class VoterSlugIssuerTest extends BaseTestCase implements DatabaseFreeTest
{
    use CreatesApplication;

    private int $queries = 0;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'pgsql',
            'database.connections.pgsql.host' => '/nonexistent-issuer-test-socket-dir',
            'database.connections.pgsql.url' => null,
        ]);
        DB::purge('pgsql');
        DB::listen(function () {
            $this->queries++;
        });
    }

    private function entitlement(VotingEntitlementReason $reason = VotingEntitlementReason::Entitled): VotingEntitlementQuery
    {
        return new class ($reason) implements VotingEntitlementQuery {
            public function __construct(private VotingEntitlementReason $reason) {}
            public function standingOf(string $userId, string $electionId): VotingEntitlementReason { return $this->reason; }
            public function decide(string $userId, string $electionId): VotingEntitlementReason { return $this->reason; }
        };
    }

    private function violation(string $driverMessage): UniqueConstraintViolationException
    {
        $pdo = new PDOException($driverMessage, 23505);
        $pdo->errorInfo = ['23505', 7, $driverMessage];

        return new UniqueConstraintViolationException('pgsql', 'insert into "voter_slugs" ...', [], $pdo);
    }

    private function election(string $type = 'real'): Election
    {
        return (new Election())->forceFill(['id' => 'eeeeeeee-eeee-4eee-8eee-eeeeeeeeeeee', 'organisation_id' => 'o1', 'type' => $type]);
    }

    /** Built without its constructor: User's HasAuditFields inspects the schema on construction (a query). */
    private function voter(): User
    {
        $user = (new \ReflectionClass(User::class))->newInstanceWithoutConstructor();
        $user->setRawAttributes(['id' => 'uuuuuuuu-uuuu-4uuu-8uuu-uuuuuuuuuuuu']);

        return $user;
    }

    // ── classification of unique violations (plan 5, PgUniqueViolationShapeTest) ──

    public static function violations(): array
    {
        return [
            'slug string'  => ['duplicate key value violates unique constraint "voter_slugs_slug_unique"', VoterSlugIssuer::CONFLICT_SLUG_STRING],
            'active index' => ['duplicate key value violates unique constraint "voter_slugs_active_election_user_unique"', VoterSlugIssuer::CONFLICT_ACTIVE_SLUG],
            'other rule'   => ['duplicate key value violates unique constraint "codes_election_id_user_id_unique"', VoterSlugIssuer::CONFLICT_UNKNOWN],
            'no name'      => ['duplicate key value', VoterSlugIssuer::CONFLICT_UNKNOWN],
        ];
    }

    #[DataProvider('violations')]
    public function test_unique_violations_are_classified_by_constraint_name(string $message, string $expected): void
    {
        $this->assertSame($expected, VoterSlugIssuer::classify($this->violation($message)));
        $this->assertSame(0, $this->queries);
    }

    // ── refusals before any query ─────────────────────────────────────────

    public function test_it_refuses_to_run_inside_an_open_transaction(): void
    {
        $issuer = new class ($this->entitlement()) extends VoterSlugIssuer {
            protected function transactionLevel(): int { return 1; }
        };

        $this->expectException(LogicException::class);
        try {
            $issuer->issue($this->voter(), $this->election());
        } finally {
            $this->assertSame(0, $this->queries);
        }
    }

    public function test_it_serves_real_elections_only(): void
    {
        $this->expectException(LogicException::class);
        try {
            (new VoterSlugIssuer($this->entitlement()))->issue($this->voter(), $this->election('demo'));
        } finally {
            $this->assertSame(0, $this->queries);
        }
    }

    public function test_an_invalid_lifetime_issues_nothing(): void
    {
        config(['voting.time_in_minutes' => '20min']);

        $this->expectException(VoterSlugLifetimeMisconfigured::class);
        try {
            (new VoterSlugIssuer($this->entitlement()))->issue($this->voter(), $this->election());
        } finally {
            $this->assertSame(0, $this->queries, 'refused before any database work');
        }
    }

    // ── outcomes ──────────────────────────────────────────────────────────

    public function test_outcomes_with_a_link(): void
    {
        $slug = (new VoterSlug())->forceFill(['id' => 's1', 'slug' => 'abc']);

        $reused = VoterSlugIssuance::reused($slug);
        $issued = VoterSlugIssuance::issued($slug);

        $this->assertSame(VoterSlugIssuance::REUSED, $reused->outcome);
        $this->assertSame(VoterSlugIssuance::ISSUED, $issued->outcome);
        $this->assertSame($slug, $reused->slug);
        $this->assertTrue($reused->hasLink());
        $this->assertTrue($issued->hasLink());
        $this->assertNull($issued->refusal);
    }

    public function test_outcomes_without_a_link(): void
    {
        $voted = VoterSlugIssuance::alreadyVoted();
        $notEntitled = VoterSlugIssuance::notEntitled(VotingEntitlementReason::NotActive);

        $this->assertSame(VoterSlugIssuance::ALREADY_VOTED, $voted->outcome);
        $this->assertSame(VoterSlugIssuance::NOT_ENTITLED, $notEntitled->outcome);
        $this->assertFalse($voted->hasLink());
        $this->assertFalse($notEntitled->hasLink());
        $this->assertNull($notEntitled->slug);
        $this->assertSame(VotingEntitlementReason::NotActive, $notEntitled->refusal);
    }

    public function test_the_conflict_keeps_its_cause_and_never_a_link(): void
    {
        $cause = $this->violation('duplicate key value violates unique constraint "x"');
        $conflict = new VoterSlugIssuanceConflict(VoterSlugIssuer::CONFLICT_UNKNOWN, $cause);

        $this->assertSame($cause, $conflict->cause());
        $this->assertSame(VoterSlugIssuer::CONFLICT_UNKNOWN, $conflict->kind());
        $this->assertSame(['kind' => VoterSlugIssuer::CONFLICT_UNKNOWN], $conflict->getContext());
        $this->assertFalse(property_exists($conflict, 'slug'), 'a conflict never carries a link');
    }
}
