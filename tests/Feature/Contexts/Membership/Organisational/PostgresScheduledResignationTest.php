<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\ApplicationRefusal;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\DeclarationWriteOutcome;
use App\Contexts\Membership\Application\Organisational\ScheduleResignation;
use App\Contexts\Membership\Application\Organisational\ScheduleResignationHandler;
use App\Contexts\Membership\Application\Organisational\WithdrawScheduledResignation;
use App\Contexts\Membership\Application\Organisational\WithdrawScheduledResignationHandler;
use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignationState;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresScheduledResignationStore;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ScriptedLedgerClockReader;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * PBDIGIT-354 S2-4b on PostgreSQL: scheduled resignation declarations are stored outside the ledger
 * (`org_membership_scheduled_resignations`, D-204), with their own idempotency keys
 * (`org_membership_declaration_commands`).
 * - Scheduling writes no ledger fact and no C1 event (MI-4).
 * - **The database itself keeps the history:** one `scheduled` declaration per period (partial unique index);
 *   declared fields never change; a final declaration never changes; nothing is deleted (trigger); each state's
 *   fields are consistent (CHECK).
 * Day ordinals: 20000 = 2024-10-04, 20010 = 2024-10-14, 20020 = 2024-10-24, 20030 = 2024-11-03.
 */
final class PostgresScheduledResignationTest extends MembershipPostgresTestCase
{
    private const TABLE = 'org_membership_scheduled_resignations';
    private int $clock = 1_760_000_000_000_000;

    private function members(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(fn (): int => $this->clock += 1_000));
    }

    private function store(): PostgresScheduledResignationStore
    {
        return new PostgresScheduledResignationStore(DB::connection());
    }

    private function organisationWithMember(): string
    {
        $org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $org, 'name' => 's2-4b', 'slug' => 's24b-' . $org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $this->assertTrue((new AdmitMemberHandler($this->members(), new FakeAuthority()))->handle(
            new AdmitMember($org, 'P1', 'M1', 'A1', 20_000, 20_000, 20_365, false, 'registrar-1', 'decl-fixture', null, 'k-admit'),
        )->isAppended());

        return $org;
    }

    private function schedule(string $org, string $id = 'D1', int $on = 20_010, int $from = 20_030, string $key = 'k-s1')
    {
        return (new ScheduleResignationHandler($this->members(), $this->store(), new FakeAuthority()))->handle(new ScheduleResignation($org, 'M1', $id, $on, $from, 'P1', 1_728_000_000_000_000, $key));
    }

    private function withdraw(string $org, string $actor = 'registrar-1', int $on = 20_020, string $key = 'k-w1')
    {
        return (new WithdrawScheduledResignationHandler($this->members(), $this->store(), new FakeAuthority()))->handle(new WithdrawScheduledResignation($org, 'M1', 'D1', $on, $actor, $key));
    }

    private function refused(string $sql, string $reason, array $bindings = []): void
    {
        try {
            DB::transaction(static fn () => DB::statement($sql, $bindings));
            $this->fail("accepted: {$sql}");
        } catch (QueryException $e) {
            $this->assertStringContainsString($reason, $e->getMessage());
        }
    }

    public function test_a_scheduled_resignation_is_stored_and_reloaded_and_writes_no_ledger_fact(): void
    {
        $org = $this->organisationWithMember();
        $facts = (int) DB::selectOne('SELECT count(*) AS n FROM org_membership_facts WHERE organisation_id = ?', [$org])->n;
        $events = (int) DB::selectOne('SELECT count(*) AS n FROM outbox_events WHERE organisation_id = ?', [$org])->n;

        $this->assertTrue($this->schedule($org)->isRecorded());

        $d = $this->store()->find(TenantId::fromString($org), MemberId::fromString('M1'), DeclarationId::fromString('D1'));
        $this->assertSame([ScheduledResignationState::Scheduled, 'A1', 20_010, 20_030, 'P1', 1_728_000_000_000_000],
            [$d->state(), $d->period()->value(), $d->declarationDate()->ordinal(), $d->effectiveDate()->ordinal(), $d->provenance()->actorOrSource(), $d->decidedAt()?->ordinal()]);
        $this->assertSame('test:fake-authority', $d->provenance()->authorityEvidence()?->ruleRef(), 'the answer kept for the execution');
        $this->assertSame($facts, (int) DB::selectOne('SELECT count(*) AS n FROM org_membership_facts WHERE organisation_id = ?', [$org])->n, 'D-204: not a ledger fact');
        $this->assertSame($events, (int) DB::selectOne('SELECT count(*) AS n FROM outbox_events WHERE organisation_id = ?', [$org])->n, 'MI-4: C1 shows nothing');
        $row = DB::table(self::TABLE)->where('organisation_id', $org)->first();
        $this->assertSame(['2024-10-14', '2024-11-03'], [$row->declaration_date, $row->effective_date]);
    }

    public function test_a_withdrawal_is_stored_with_who_and_when_and_the_history_is_kept(): void
    {
        $org = $this->organisationWithMember();
        $this->schedule($org);

        $this->assertTrue($this->withdraw($org)->isRecorded());
        $this->assertTrue($this->schedule($org, 'D2', 20_021, 20_040, 'k-s2')->isRecorded(), 'a new one after the withdrawal');

        $all = $this->store()->forMember(TenantId::fromString($org), MemberId::fromString('M1'));
        $this->assertSame(['D1:withdrawn', 'D2:scheduled'], array_map(static fn ($d) => $d->id()->value() . ':' . $d->state()->value, $all));
        $this->assertSame(['registrar-1', 20_020, 20_030], [$all[0]->withdrawnBy()?->actorOrSource(), $all[0]->withdrawnOn()?->ordinal(), $all[0]->effectiveDate()->ordinal()]);
        $this->assertNotNull(DB::table(self::TABLE)->where('organisation_id', $org)->where('declaration_id', 'D1')->value('changed_at'));
    }

    public function test_requests_are_idempotent_and_a_reused_key_is_refused(): void
    {
        $org = $this->organisationWithMember();
        $first = $this->schedule($org);

        $again = $this->schedule($org);
        $this->assertTrue($again->isRecorded(), 'answered from its key, not refused as a second pending one');
        $this->assertEquals($first->declaration(), $again->declaration());
        $this->assertSame(ApplicationRefusal::IdempotencyKeyConflict, $this->schedule($org, from: 20_031)->applicationRefusal());
        $this->assertTrue($this->withdraw($org)->isRecorded());
        $this->assertTrue($this->withdraw($org)->isRecorded());
        $this->assertSame(ApplicationRefusal::IdempotencyKeyConflict, $this->withdraw($org, on: 20_021)->applicationRefusal());
        $this->assertSame(1, DB::table(self::TABLE)->where('organisation_id', $org)->count());
        $this->assertSame(2, DB::table('org_membership_declaration_commands')->where('organisation_id', $org)->count());
    }

    public function test_the_store_compares_the_membership_version_and_the_declaration_state(): void
    {
        $org = $this->organisationWithMember();
        $tenant = TenantId::fromString($org);
        $member = $this->members()->load($tenant, MemberId::fromString('M1'));
        $d = \App\Contexts\Membership\Domain\Organisational\ScheduledResignation::schedule(DeclarationId::fromString('D1'), $member->resolve(), [], TimePoint::of(20_010), TimePoint::of(20_030), Provenance::decision('P1'), null);

        $this->assertSame(DeclarationWriteOutcome::Conflict, $this->store()->record($tenant, MemberId::fromString('M1'), $member->version() - 1, $d, IdempotencyKey::fromString('k1'), 'f'), 'a stale version');
        $this->assertSame(DeclarationWriteOutcome::MemberNotFound, $this->store()->record($tenant, MemberId::fromString('M-NONE'), 0, $d, IdempotencyKey::fromString('k2'), 'f'));
        $this->assertSame(DeclarationWriteOutcome::Written, $this->store()->record($tenant, MemberId::fromString('M1'), $member->version(), $d, IdempotencyKey::fromString('k3'), 'f'));
        $this->assertSame(DeclarationWriteOutcome::Conflict, $this->store()->record($tenant, MemberId::fromString('M1'), $member->version(), $d, IdempotencyKey::fromString('k4'), 'f'), 'the id is taken');
        $second = \App\Contexts\Membership\Domain\Organisational\ScheduledResignation::schedule(DeclarationId::fromString('D2'), $member->resolve(), [], TimePoint::of(20_011), TimePoint::of(20_040), Provenance::decision('P1'), null);
        $this->assertSame(DeclarationWriteOutcome::Conflict, $this->store()->record($tenant, MemberId::fromString('M1'), $member->version(), $second, IdempotencyKey::fromString('k4b'), 'f'),
            'a second pending declaration for the period, decided without seeing the first: the index answers (a race; S2-4d)');
        $this->assertNull($this->store()->appliedRequest($tenant, IdempotencyKey::fromString('k4b')), 'nothing written, not even the key');

        $withdrawn = $d->withdraw(TimePoint::of(20_020), Provenance::decision('P1'));
        $this->assertSame(DeclarationWriteOutcome::Written, $this->store()->transition($tenant, MemberId::fromString('M1'), $withdrawn, IdempotencyKey::fromString('k5'), 'f'));
        $this->assertSame(DeclarationWriteOutcome::Conflict, $this->store()->transition($tenant, MemberId::fromString('M1'), $d->supersede('x'), IdempotencyKey::fromString('k6'), 'f'), 'no longer scheduled: compare-and-set (MI-2)');
        $this->assertSame(ScheduledResignationState::Withdrawn, $this->store()->find($tenant, MemberId::fromString('M1'), DeclarationId::fromString('D1'))->state());
        $this->assertSame(DeclarationWriteOutcome::IdempotencyKeyConflict, $this->store()->transition($tenant, MemberId::fromString('M1'), $withdrawn, IdempotencyKey::fromString('k5'), 'g'));
    }

    public function test_the_database_allows_one_scheduled_declaration_per_period(): void
    {
        $org = $this->organisationWithMember();
        $this->schedule($org);

        $this->refused("INSERT INTO org_membership_scheduled_resignations (organisation_id, membership_id, declaration_id, period_fact_id, declaration_date, effective_date, provenance, recorded_at, state)
            VALUES (?, 'M1', 'D2', 'A1', '2024-10-15', '2024-11-04', '{}', now(), 'scheduled')", 'org_membership_scheduled_resignations_one_pending', [$org]);
    }

    public function test_the_database_keeps_the_history(): void
    {
        $org = $this->organisationWithMember();
        $this->schedule($org);

        $this->refused("UPDATE org_membership_scheduled_resignations SET effective_date = '2024-11-05' WHERE organisation_id = ?", 'SCHEDULED_RESIGNATION_DECLARED_FIELDS', [$org]);
        $this->refused("UPDATE org_membership_scheduled_resignations SET provenance = '{}' WHERE organisation_id = ?", 'SCHEDULED_RESIGNATION_DECLARED_FIELDS', [$org]);
        $this->refused('DELETE FROM org_membership_scheduled_resignations WHERE organisation_id = ?', 'SCHEDULED_RESIGNATION_HISTORY', [$org]);
        $this->assertTrue($this->withdraw($org)->isRecorded());
        $this->refused("UPDATE org_membership_scheduled_resignations SET state = 'scheduled', withdrawn_on = NULL, withdrawn_by = NULL, changed_at = NULL WHERE organisation_id = ?", 'SCHEDULED_RESIGNATION_FINAL', [$org]);
        $this->refused("UPDATE org_membership_declaration_commands SET fingerprint = 'x' WHERE organisation_id = ?", 'SCHEDULED_RESIGNATION_HISTORY', [$org]);
        $this->refused('DELETE FROM org_membership_declaration_commands WHERE organisation_id = ?', 'SCHEDULED_RESIGNATION_HISTORY', [$org]);
        $this->assertSame(1, DB::table(self::TABLE)->where('organisation_id', $org)->count());
    }

    public function test_the_database_refuses_inconsistent_declarations(): void
    {
        $org = $this->organisationWithMember();
        $insert = "INSERT INTO org_membership_scheduled_resignations (organisation_id, membership_id, declaration_id, period_fact_id, declaration_date, effective_date, provenance, recorded_at, state, withdrawn_on, withdrawn_by, executed_fact_id, superseded_because, changed_at)
            VALUES (?, 'M1', ?, 'A1', ?, ?, '{}', now(), ?, ?, ?, ?, ?, ?)";
        $rows = [
            'not after the declaration' => [['X1', '2024-11-03', '2024-11-03', 'scheduled', null, null, null, null, null], 'org_membership_scheduled_resignations_dates_check'],
            'scheduled with a withdrawal' => [['X2', '2024-10-14', '2024-11-03', 'scheduled', '2024-10-20', '{}', null, null, null], 'org_membership_scheduled_resignations_state_check'],
            'withdrawn without its actor' => [['X3', '2024-10-14', '2024-11-03', 'withdrawn', '2024-10-20', null, null, null, '2024-10-20'], 'org_membership_scheduled_resignations_state_check'],
            'withdrawn on its date' => [['X4', '2024-10-14', '2024-11-03', 'withdrawn', '2024-11-03', '{}', null, null, '2024-11-03'], 'org_membership_scheduled_resignations_state_check'],
            'superseded without a reason' => [['X5', '2024-10-14', '2024-11-03', 'superseded', null, null, null, null, '2024-10-20'], 'org_membership_scheduled_resignations_state_check'],
            'an unknown state' => [['X6', '2024-10-14', '2024-11-03', 'cancelled', null, null, null, null, '2024-10-20'], 'org_membership_scheduled_resignations_state_check'],
            'executed as no fact of this member' => [['X7', '2024-10-14', '2024-11-03', 'executed', null, null, 'E-NONE', null, '2024-10-20'], 'org_membership_scheduled_resignations_executed_fk'],
            'for no period of this member' => [['X8', '2024-10-14', '2024-11-03', 'scheduled', null, null, null, null, null], null],
        ];
        foreach ($rows as $why => [$values, $reason]) {
            if ($reason === null) {
                $this->refused(str_replace("'A1'", "'A-NONE'", $insert), 'org_membership_scheduled_resignations_period_fk', [$org, ...$values]);
                continue;
            }
            $this->refused($insert, $reason, [$org, ...$values]);
        }
        // No such member: the membership and the period keys both fail; which one PostgreSQL reports first is its choice.
        $this->refused(str_replace("'M1'", "'M-NONE'", $insert), 'violates foreign key constraint "org_membership_scheduled_resignations_', [$org, 'X9', '2024-10-14', '2024-11-03', 'scheduled', null, null, null, null, null]);
        $this->assertSame(0, DB::table(self::TABLE)->where('organisation_id', $org)->count());
    }
}
