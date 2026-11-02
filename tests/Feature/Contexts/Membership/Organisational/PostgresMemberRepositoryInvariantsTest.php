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
use App\Contexts\Membership\Infrastructure\Organisational\AppendInsideTransactionRefused;
use App\Contexts\Membership\Infrastructure\Organisational\DateOrdinal;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Membership\Infrastructure\Organisational\UnsupportedMembershipDatabaseDriver;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Database-specific invariants of the PostgreSQL adapter (Slice 1b contract §8, T2–T10).
 * PBDIGIT-260. Fresh organisations per test (no per-test transaction on PostgreSQL).
 */
final class PostgresMemberRepositoryInvariantsTest extends MembershipPostgresTestCase
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

    // ── PBDIGIT-264 F-5 (Part 2 plan step 2; PR disposition N1, 2026-10-09) ──

    /** @return array{0: PostgresMemberRepository, 1: TenantId, 2: \App\Contexts\Membership\Domain\Organisational\Member, 3: \App\Contexts\Membership\Domain\Organisational\Decision} */
    private function freshAdmission(): array
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
        $d = $m->decideAdmit(FactId::fromString('A-1'), TimePoint::of(20_000), TimePoint::of(20_000), null, Provenance::decision('registrar', null, 'decl-fixture'));

        return [$repo, $t, $m, $d];
    }

    public function test_f5_an_append_inside_an_open_transaction_is_refused_before_any_statement_and_writes_nothing(): void
    {
        // N1: "Appended" must mean durably committed, and SET TRANSACTION must be the transaction's first statement.
        [$repo, $t, $m, $d] = $this->freshAdmission();
        $statements = [];

        DB::beginTransaction();
        try {
            DB::listen(function ($query) use (&$statements): void { $statements[] = $query->sql; });
            try {
                $repo->append($t, $m->id(), $d, IdempotencyKey::fromString('k-nested'), 'fp');
                $this->fail('an append inside an open transaction must be refused');
            } catch (AppendInsideTransactionRefused) {
                $this->assertSame([], $statements, 'refused before any statement');
            }
        } finally {
            DB::rollBack();
        }

        $this->assertSame(0, DB::table('org_membership_facts')->where('organisation_id', $t->value())->count());
        $this->assertSame(0, DB::table('org_membership_commands')->where('organisation_id', $t->value())->count());
    }

    public function test_f5_read_committed_is_enforced_as_the_first_statement_of_an_append(): void
    {
        [$repo, $t, $m, $d] = $this->freshAdmission();
        $statements = [];
        DB::listen(function ($query) use (&$statements): void { $statements[] = $query->sql; });

        $this->assertSame(AppendOutcome::Appended, $repo->append($t, $m->id(), $d, IdempotencyKey::fromString('k-rc'), 'fp')->outcome());
        $this->assertSame('SET TRANSACTION ISOLATION LEVEL READ COMMITTED', $statements[0] ?? null, 'the adapter enforces its isolation assumption');
    }

    // ── PBDIGIT-264 F-4b (Part 2 plan step 3; PR disposition 2026-10-09 + §10.2 migration requirements) ──

    private const F4B_MIGRATION = 'database/migrations/2026_11_02_180000_normalise_org_membership_commands.php';

    /** @return list<string> */
    private static function commandColumns(): array
    {
        return array_map(static fn ($r) => $r->column_name, DB::select(
            "SELECT column_name FROM information_schema.columns WHERE table_name = 'org_membership_commands' ORDER BY ordinal_position",
        ));
    }

    private static function f4bMigration(): object
    {
        return require base_path(self::F4B_MIGRATION);
    }

    /** @return array<string, array{string, string, int, int}> key → [memberId, factId, seq, recordedAt µs], as appliedCommand answers */
    private function answers(PostgresMemberRepository $repo, TenantId $t, array $keys): array
    {
        $out = [];
        foreach ($keys as $key) {
            $a = $repo->appliedCommand($t, IdempotencyKey::fromString($key))->appended();
            $out[$key] = [$a->memberId()->value(), $a->factId()->value(), $a->seq(), $a->recordedAt()->ordinal()];
        }

        return $out;
    }

    public function test_f4b_the_commands_table_holds_no_copy_of_the_fact(): void
    {
        $columns = self::commandColumns();

        $this->assertNotContains('fact_id', $columns, 'derivable from the fact (F-4b)');
        $this->assertNotContains('recorded_at', $columns, 'derivable from the fact (F-4b)');
        $this->assertSame(['organisation_id', 'idempotency_key', 'fingerprint', 'membership_id', 'seq'], $columns);
    }

    public function test_f4b_a_command_naming_a_fact_that_does_not_exist_is_rejected(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $this->admitted($repo, $t, 'M1', null);

        try {
            DB::table('org_membership_commands')->insert([
                'organisation_id' => $t->value(), 'idempotency_key' => 'k-orphan', 'fingerprint' => 'fp', 'membership_id' => 'M1', 'seq' => 99,
            ]);
            $this->fail('an orphan command row must be rejected');
        } catch (QueryException $e) {
            $this->assertSame('23503', $e->errorInfo[0] ?? null, 'foreign_key_violation (commands → facts)');
        }
    }

    public function test_f4b_applied_command_answers_come_from_the_fact(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $this->admitted($repo, $t, 'M1', null);
        $fact = DB::table('org_membership_facts')->where('organisation_id', $t->value())->where('membership_id', 'M1')
            ->select(['fact_id', 'seq', DB::raw(\App\Contexts\Membership\Infrastructure\Organisational\InstantMicros::selectExpression('recorded_at') . ' AS recorded_us')])->first();

        $this->assertSame(['k-M1' => ['M1', $fact->fact_id, (int) $fact->seq, (int) $fact->recorded_us]], $this->answers($repo, $t, ['k-M1']));
    }

    public function test_f4b_the_migration_round_trips_on_a_populated_schema(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $this->admitted($repo, $t, 'M1', null);
        $this->admitted($repo, $t, 'M2', TimePoint::of(20_100));
        $before = $this->answers($repo, $t, ['k-M1', 'k-M2']);
        $migration = self::f4bMigration();

        try {
            DB::transaction(fn () => $migration->down());
            $this->assertContains('fact_id', self::commandColumns(), 'down() restores the columns');
            $backfilled = DB::table('org_membership_commands as c')
                ->join('org_membership_facts as f', fn ($j) => $j->on('f.organisation_id', '=', 'c.organisation_id')->on('f.membership_id', '=', 'c.membership_id')->on('f.seq', '=', 'c.seq'))
                ->where('c.organisation_id', $t->value())
                ->whereRaw('c.fact_id = f.fact_id AND c.recorded_at = f.recorded_at')->count();
            $this->assertSame(2, $backfilled, 'down() backfills the exact values');
        } finally {
            DB::transaction(fn () => $migration->up());
        }

        $this->assertNotContains('fact_id', self::commandColumns());
        $this->assertSame($before, $this->answers($repo, $t, ['k-M1', 'k-M2']), 'up → down → up preserves every answer');
        $this->assertSame(2, DB::table('org_membership_commands')->where('organisation_id', $t->value())->count(), 'and every row');
    }

    public static function copiedValueMismatches(): array
    {
        // Review finding R-4: each copied column is checked on its own.
        return [
            'fact_id differs'          => ["fact_id = 'NOT-THE-FACT'"],
            'only recorded_at differs' => ["recorded_at = recorded_at + interval '1 microsecond'"],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('copiedValueMismatches')]
    public function test_f4b_the_migration_aborts_on_a_copied_value_that_disagrees_with_the_fact(string $mismatch): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $this->admitted($repo, $t, 'M1', null);
        $migration = self::f4bMigration();

        DB::transaction(fn () => $migration->down());
        try {
            DB::update("UPDATE org_membership_commands SET {$mismatch} WHERE organisation_id = ?", [$t->value()]);
            try {
                DB::transaction(fn () => $migration->up());
                $this->fail('up() must abort on a mismatch (§10.2)');
            } catch (\RuntimeException $e) {
                $this->assertStringContainsString('mismatch', $e->getMessage());
            }
            $this->assertContains('fact_id', self::commandColumns(), 'the aborted up() changed nothing');
        } finally {
            // Restore both copied values from the fact, then re-apply up() so later tests see the migrated schema.
            DB::update(<<<'SQL'
                UPDATE org_membership_commands c
                   SET fact_id = f.fact_id, recorded_at = f.recorded_at
                  FROM org_membership_facts f
                 WHERE f.organisation_id = c.organisation_id AND f.membership_id = c.membership_id AND f.seq = c.seq
                   AND c.organisation_id = ?
                SQL, [$t->value()]);
            DB::transaction(fn () => $migration->up());
        }
        $this->assertNotContains('fact_id', self::commandColumns());
    }
}
