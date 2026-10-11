<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\DateOrdinal;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Membership\Infrastructure\Organisational\UnsupportedMembershipDatabaseDriver;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Database-specific invariants of the PostgreSQL adapter (Slice 1b contract §8, T2–T10).
 * PBDIGIT-260. Fresh organisations per test (no per-test transaction on PostgreSQL).
 */
final class PostgresMemberRepositoryInvariantsTest extends TestCase
{
    private int $now = 1_760_000_000_000_000;   // epoch µs

    private function repo(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), fn (): int => $this->now);
    }

    private function organisation(): TenantId
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'm1b', 'slug' => 'm1b-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);

        return TenantId::fromString($id);
    }

    private function admitted(PostgresMemberRepository $repo, TenantId $t, string $member, ?TimePoint $termUntil, int $at = 20_000): void
    {
        $m = $repo->create($t, MemberId::fromString($member), PersonRef::fromString("person-$member"))->member();
        $d = $m->decideAdmit(FactId::fromString("A-$member"), TimePoint::of($at), TimePoint::of($at), $termUntil, Provenance::decision('registrar', null, 'decl-fixture'));
        $this->assertSame(AppendOutcome::Appended, $repo->append($t, $m->id(), $d, IdempotencyKey::fromString("k-$member"), 'fp')->outcome());
    }

    public function test_t2_the_same_member_id_and_person_may_exist_in_two_tenants(): void
    {
        $repo = $this->repo();
        [$a, $b] = [$this->organisation(), $this->organisation()];

        $this->assertTrue($repo->create($a, MemberId::fromString('M1'), PersonRef::fromString('P1'))->isCreated());
        $this->assertTrue($repo->create($b, MemberId::fromString('M1'), PersonRef::fromString('P1'))->isCreated());
        $this->assertNotNull($repo->load($a, MemberId::fromString('M1')));
        $this->assertNotNull($repo->load($b, MemberId::fromString('M1')));
    }

    public function test_t3_the_database_rejects_a_fact_attached_to_another_tenants_member(): void
    {
        $repo = $this->repo();
        [$a, $b] = [$this->organisation(), $this->organisation()];
        $repo->create($a, MemberId::fromString('M1'), PersonRef::fromString('P1'));

        $this->expectException(QueryException::class);   // composite FK (organisation_id, membership_id)
        DB::table('org_membership_facts')->insert([
            'organisation_id' => $b->value(), 'membership_id' => 'M1', 'seq' => 1, 'fact_id' => 'X', 'kind' => 'admit',
            'effective_at' => '2026-01-01', 'initial_term' => 'none', 'recorded_at' => '2026-01-01 00:00:00+00', 'provenance' => '{}',
        ]);
    }

    public function test_t4_the_three_initial_term_states_round_trip_with_their_meaning(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $this->admitted($repo, $t, 'none', null);
        $this->admitted($repo, $t, 'until', TimePoint::of(20_010));
        $this->admitted($repo, $t, 'unlimited', TimePoint::infinity());

        $none = $repo->load($t, MemberId::fromString('none'))->resolve()->periods()[0];
        $until = $repo->load($t, MemberId::fromString('until'))->resolve()->periods()[0];
        $unlimited = $repo->load($t, MemberId::fromString('unlimited'))->resolve()->periods()[0];

        $this->assertTrue($none->isLapsed(TimePoint::of(20_000)), 'C-S0-2: no term ⇒ Lapsed');
        $this->assertTrue($until->isInForce(TimePoint::of(20_009)));
        $this->assertTrue($until->isLapsed(TimePoint::of(20_010)));
        $this->assertTrue($unlimited->isInForce(TimePoint::of(99_999)));
        foreach (['none', 'until', 'unlimited'] as $member) {
            $this->assertSame($member, DB::table('org_membership_facts')->where('organisation_id', $t->value())->where('membership_id', $member)->value('initial_term'), 'explicit stored state');
        }
    }

    public function test_t5_dates_instants_and_provenance_round_trip_exactly(): void
    {
        foreach ([0, -1, 1, 19_782, 20_454, -719_162, 2_932_896] as $ordinal) {   // 1970-01-01, 1969-12-31, …, 2024-02-29, 0001-01-01, 9999-12-31
            $this->assertSame($ordinal, DateOrdinal::fromDate(DateOrdinal::toDate(TimePoint::of($ordinal)))->ordinal(), "date ordinal $ordinal");
        }
        $this->assertSame('2024-02-29', DateOrdinal::toDate(TimePoint::of(19_782)));

        $repo = $this->repo();
        $t = $this->organisation();
        $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
        $evidence = AuthorityEvidence::of('user-7', 'application_role:admin', 'interim:org-role', 'v1', true, Instant::of(1_760_000_000_123_456), false);
        $provenance = Provenance::decision('user-7', $evidence, 'decl-9');
        $this->now = 1_760_000_001_000_001;
        $d = $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(19_782), TimePoint::of(19_782), TimePoint::of(20_147), $provenance, Instant::of(1_759_999_999_999_999));
        $repo->append($t, $m->id(), $d, IdempotencyKey::fromString('k'), 'fp');

        $fact = $repo->load($t, $m->id())->log()[0];
        $this->assertSame(1_760_000_001_000_001, $fact->recordedAt()->ordinal(), 'recordedAt µs');
        $this->assertSame(1_759_999_999_999_999, $fact->fact()->decidedAt()->ordinal(), 'decidedAt µs');
        $this->assertSame(19_782, $fact->fact()->effectiveAt()->ordinal());
        $this->assertSame(20_147, $fact->fact()->initialTermUntil()->ordinal());
        $this->assertEquals($provenance, $fact->fact()->provenance());
        $this->assertSame('2024-02-29', DB::table('org_membership_facts')->where('organisation_id', $t->value())->value(DB::raw('effective_at::text')));
    }

    public function test_t6_recorded_at_never_decreases_under_a_backward_clock(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $this->now = 2_000_000_000_000_000;
        $this->admitted($repo, $t, 'M1', TimePoint::infinity());
        $m = $repo->load($t, MemberId::fromString('M1'));
        $this->now = 1_000_000_000_000_000;
        $end = $m->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A-M1'), TimePoint::of(20_100), TimePoint::of(20_100), EndKind::fromString('resignation'), Provenance::decision('registrar'));

        $appended = $repo->append($t, $m->id(), $end, IdempotencyKey::fromString('k2'), 'fp2')->appended();

        $this->assertSame(2_000_000_000_000_000, $appended->recordedAt()->ordinal());
    }

    public function test_t7_a_failure_inside_append_rolls_everything_back(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
        // A raw row that occupies seq 1 forces the fact insert to fail after the version was advanced.
        DB::table('org_membership_facts')->insert([
            'organisation_id' => $t->value(), 'membership_id' => 'M1', 'seq' => 1, 'fact_id' => 'raw', 'kind' => 'admit',
            'effective_at' => '2026-01-01', 'initial_term' => 'none', 'recorded_at' => '2026-01-01 00:00:00+00', 'provenance' => '{"kind":"rule","actorOrSource":"x","declarationRef":null,"ruleRef":"x","ruleVersion":"1","importer":null,"note":null,"authorityEvidence":null}',
        ]);
        $d = $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(1), TimePoint::of(1), null, Provenance::decision('registrar', null, 'decl-fixture'));

        try {
            $repo->append($t, $m->id(), $d, IdempotencyKey::fromString('k1'), 'fp1');
            $this->fail('the append should have failed');
        } catch (QueryException) {
        }

        $this->assertSame(0, DB::table('org_memberships')->where('organisation_id', $t->value())->where('id', 'M1')->value('version'), 'version rolled back');
        $this->assertNull($repo->appliedCommand($t, IdempotencyKey::fromString('k1')), 'key not registered');
        $this->assertSame(1, DB::table('org_membership_facts')->where('organisation_id', $t->value())->count(), 'no fact added');
    }

    public function test_t8_a_duplicate_fact_id_at_the_database_is_an_infrastructure_error(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $this->admitted($repo, $t, 'M1', null);

        $this->expectException(QueryException::class);   // UNIQUE (organisation_id, membership_id, fact_id): not translated (B-1)
        DB::table('org_membership_facts')->insert([
            'organisation_id' => $t->value(), 'membership_id' => 'M1', 'seq' => 2, 'fact_id' => 'A-M1', 'kind' => 'admit',
            'effective_at' => '2026-01-01', 'initial_term' => 'none', 'recorded_at' => '2026-01-01 00:00:00+00', 'provenance' => '{}',
        ]);
    }

    public function test_t9_the_adapter_offers_no_update_or_delete(): void
    {
        $methods = array_map(static fn (\ReflectionMethod $m) => $m->getName(), (new \ReflectionClass(PostgresMemberRepository::class))->getMethods(\ReflectionMethod::IS_PUBLIC));

        $this->assertSame([], preg_grep('/^(update|delete|remove|save|replace|rewrite|truncate)/i', $methods));
    }

    public function test_t10_a_non_postgresql_connection_fails_fast(): void
    {
        config(['database.connections.membership_sqlite_probe' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']]);

        $this->expectException(UnsupportedMembershipDatabaseDriver::class);
        $this->expectExceptionMessage("requires PostgreSQL (DB-1); connection 'membership_sqlite_probe' uses 'sqlite'");
        new PostgresMemberRepository(DB::connection('membership_sqlite_probe'), fn (): int => 0);
    }
}
