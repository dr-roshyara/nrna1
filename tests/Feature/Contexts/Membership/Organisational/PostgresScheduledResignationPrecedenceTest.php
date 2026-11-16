<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Application\Organisational\RecordDeath;
use App\Contexts\Membership\Application\Organisational\RecordDeathHandler;
use App\Contexts\Membership\Application\Organisational\ResignMembership;
use App\Contexts\Membership\Application\Organisational\ResignMembershipHandler;
use App\Contexts\Membership\Application\Organisational\ScheduleResignation;
use App\Contexts\Membership\Application\Organisational\ScheduleResignationHandler;
use App\Contexts\Membership\Application\Organisational\WithdrawScheduledResignation;
use App\Contexts\Membership\Application\Organisational\WithdrawScheduledResignationHandler;
use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignationState;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockCloser;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresScheduledResignationStore;
use App\Contexts\Membership\Infrastructure\Organisational\ScheduledResignationExecutor;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\FixedOffsetOrganisationCalendar;
use Tests\Support\ScriptedLedgerClockReader;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * PBDIGIT-354 S2-4d-Q5, **D-240** (Q-5 Option A): while a scheduled resignation is `scheduled`, Membership refuses any
 * other Membership End of that period effective ON OR AFTER its date: "the scheduled resignation takes effect first".
 *
 * - Enforced at the ledger append (PostgresMemberRepository), under the organisation's clock-row lock and the
 *   membership row lock, so it binds EVERY End path: resign now, the death-related End (`RecordDeath`, which records
 *   `End(death)`), an exclusion End (the S2-6 push path is not built: proved on the append itself), and anything later.
 * - Nothing is written on a refusal: no End, no key, no version change; the declaration stays `scheduled` and the
 *   confirming step then records it on the member's date.
 * - An End strictly BEFORE the date keeps the MI-3 behaviour (the declaration is superseded).
 * - A person's death as a FACT is not a Membership End; no such operation exists in Membership (PBDIGIT-356). What is
 *   refused here is only the Membership End.
 * Day ordinals: 20000 = 2024-10-04, 20025 = 2024-10-29, 20030 = 2024-11-03 (the scheduled date), 20035 = 2024-11-08.
 */
final class PostgresScheduledResignationPrecedenceTest extends MembershipPostgresTestCase
{
    private static function at(int $day, int $hour = 0): int
    {
        return ($day * 86_400 + $hour * 3_600) * 1_000_000;
    }

    private function members(int $reading): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => $reading));
    }

    private function store(): PostgresScheduledResignationStore
    {
        return new PostgresScheduledResignationStore(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => self::at(20_020, 12)), new FixedOffsetOrganisationCalendar(0));
    }

    /** M1 admitted (recorded 2024-10-04 09:00), D1 scheduled for 3 November. */
    private function organisation(): TenantId
    {
        $org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $org, 'name' => 'd240', 'slug' => 'd240-' . $org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $this->assertTrue((new AdmitMemberHandler($this->members(self::at(20_000, 9)), new FakeAuthority()))->handle(
            new AdmitMember($org, 'P1', 'M1', 'A1', 20_000, 20_000, 20_365, false, 'registrar-1', 'decl-fixture', null, 'k-admit'))->isAppended());
        $this->assertTrue((new ScheduleResignationHandler($this->members(0), $this->store(), new FakeAuthority()))->handle(
            new ScheduleResignation($org, 'M1', 'D1', 20_010, 20_030, 'P1', null, 'k-schedule'))->isRecorded());

        return TenantId::fromString($org);
    }

    private function resignNow(TenantId $org, int $on, string $key = 'k-now')
    {
        return (new ResignMembershipHandler($this->members(self::at(20_035, 9)), new FakeAuthority()))->handle(new ResignMembership($org->value(), 'M1', "E-$key", $on, 'P1', null, $key));
    }

    private function deathEnd(TenantId $org, int $dateOfDeath, string $key = 'k-death')
    {
        return (new RecordDeathHandler($this->members(self::at(20_035, 9)), new FakeAuthority()))->handle(
            new RecordDeath($org->value(), 'M1', "E-$key", $dateOfDeath, 20_035, 'death-certificate:2024/88', 'registrar-1', null, $key));
    }

    /** @return array{0: list<string>, 1: int, 2: string} End fact ids, the membership version, the declaration state */
    private function ledger(TenantId $org): array
    {
        $ends = array_map(static fn ($r) => $r->fact_id . '@' . $r->effective_at, DB::select("SELECT fact_id, effective_at FROM org_membership_facts WHERE organisation_id = ? AND kind = 'end' ORDER BY seq", [$org->value()]));
        $version = (int) DB::table('org_memberships')->where('organisation_id', $org->value())->value('version');
        $state = $this->store()->find($org, MemberId::fromString('M1'), DeclarationId::fromString('D1'))->state()->value;

        return [$ends, $version, $state];
    }

    private function assertRefusedAndUntouched(TenantId $org, $outcome, string $why): void
    {
        $this->assertSame(RefusalReason::ScheduledResignationTakesEffectFirst, $outcome->domainRefusal(), "$why: the scheduled resignation takes effect first");
        $this->assertSame([[], 1, 'scheduled'], $this->ledger($org), "$why: no End, no version change, the declaration still pending");
    }

    public function test_d240_resign_now_on_or_after_the_scheduled_date_is_refused(): void
    {
        $org = $this->organisation();

        $this->assertRefusedAndUntouched($org, $this->resignNow($org, 20_030, 'k-on'), 'resign now on 3 November');
        $this->assertRefusedAndUntouched($org, $this->resignNow($org, 20_035, 'k-after'), 'resign now on 8 November');
        $this->assertSame(0, DB::table('org_membership_commands')->where('organisation_id', $org->value())->whereIn('idempotency_key', ['k-on', 'k-after'])->count(), 'a refusal registers no key');
    }

    public function test_d240_a_death_related_membership_end_on_or_after_the_scheduled_date_is_refused(): void
    {
        $org = $this->organisation();

        $this->assertRefusedAndUntouched($org, $this->deathEnd($org, 20_030, 'k-d-on'), 'End(death) on 3 November');
        $this->assertRefusedAndUntouched($org, $this->deathEnd($org, 20_032, 'k-d-after'), 'End(death) on 5 November');
    }

    public function test_d240_an_exclusion_end_on_or_after_the_scheduled_date_is_refused_at_the_append(): void
    {
        $org = $this->organisation();
        $repo = $this->members(self::at(20_035, 9));

        foreach ([20_030, 20_035] as $i => $effective) {
            $member = $repo->load($org, MemberId::fromString('M1'));
            $decision = $member->decideEnd(FactId::fromString("X$i"), PeriodId::fromString('A1'), TimePoint::of($effective), TimePoint::of(20_035),
                EndKind::fromString(EndKind::EXCLUSION), Provenance::decision('governance:resolution-part:R7/2'));
            $this->assertTrue($decision->isAccepted(), 'the kernel alone would accept it');

            $result = $repo->append($org, MemberId::fromString('M1'), $decision, IdempotencyKey::fromString("k-x$i"), "fp-x$i");

            $this->assertSame(AppendOutcome::RefusedByDomain, $result->outcome(), "exclusion effective $effective");
            $this->assertSame(RefusalReason::ScheduledResignationTakesEffectFirst, $result->refusal());
            $this->assertSame([[], 1, 'scheduled'], $this->ledger($org));
        }
    }

    public function test_d240_after_the_refusal_the_confirming_step_records_the_resignation_on_the_members_date(): void
    {
        $org = $this->organisation();
        $this->resignNow($org, 20_035);
        $this->deathEnd($org, 20_032);

        $closed = (new LedgerClockCloser(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => self::at(20_036, 1)),
            new ScheduledResignationExecutor(new FixedOffsetOrganisationCalendar(0))))->closeOrRead($org);

        $this->assertSame(self::at(20_036, 1), $closed?->ordinal());
        $this->assertSame([['scheduled-resignation:D1@2024-11-03'], 2, 'executed'], $this->ledger($org), 'exactly one End, on the declared date');
        $this->assertSame(RefusalReason::PeriodNotFound, $this->deathEnd($org, 20_040, 'k-death-later')->domainRefusal(), 'afterwards there is no period to end: no second End');
        $this->assertCount(1, $this->ledger($org)[0]);
    }

    public function test_d240_an_end_strictly_before_the_scheduled_date_keeps_the_earlier_ending_rule(): void
    {
        $org = $this->organisation();

        $this->assertTrue($this->resignNow($org, 20_025)->isAppended(), 'effective 29 October: allowed');
        (new LedgerClockCloser(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => self::at(20_036, 1)),
            new ScheduledResignationExecutor(new FixedOffsetOrganisationCalendar(0))))->closeOrRead($org);

        $this->assertSame([['E-k-now@2024-10-29'], 2, 'superseded'], $this->ledger($org), 'MI-3 unchanged');
    }

    public function test_d240_applies_to_the_declarations_own_period_only(): void
    {
        $org = $this->organisation();
        $this->assertTrue($this->resignNow($org, 20_025, 'k-a1-ends')->isAppended(), 'A1 ends on 29 October; D1 (A1) not yet superseded');
        $this->assertTrue((new AdmitMemberHandler($this->members(self::at(20_035, 10)), new FakeAuthority()))->handle(
            new AdmitMember($org->value(), 'P1', 'M1', 'A2', 20_026, 20_026, 20_365, false, 'registrar-1', 'decl-fixture', null, 'k-readmit'))->isAppended(), 're-admitted: period A2');

        $outcome = (new ResignMembershipHandler($this->members(self::at(20_035, 11)), new FakeAuthority()))->handle(new ResignMembership($org->value(), 'M1', 'E-a2', 20_035, 'P1', null, 'k-a2-ends'));

        $this->assertTrue($outcome->isAppended(), "A2's End is not A1's declaration's business: " . ($outcome->domainRefusal()?->value ?? ''));
    }

    public function test_d240_a_declaration_that_is_no_longer_pending_refuses_nothing(): void
    {
        $org = $this->organisation();
        $this->assertTrue((new WithdrawScheduledResignationHandler($this->members(0), $this->store(), new FakeAuthority()))->handle(
            new WithdrawScheduledResignation($org->value(), 'M1', 'D1', 20_020, 'P1', 'k-w'))->isRecorded());

        $this->assertTrue($this->resignNow($org, 20_035)->isAppended(), 'withdrawn: a later resignation is an ordinary one');
        $this->assertSame([['E-k-now@2024-11-08'], 2, 'withdrawn'], $this->ledger($org));
    }
}
