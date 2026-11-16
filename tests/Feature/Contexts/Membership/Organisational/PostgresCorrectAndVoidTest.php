<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\CorrectMembershipRecord;
use App\Contexts\Membership\Application\Organisational\CorrectMembershipRecordHandler;
use App\Contexts\Membership\Application\Organisational\ResignMembership;
use App\Contexts\Membership\Application\Organisational\ResignMembershipHandler;
use App\Contexts\Membership\Application\Organisational\ScheduleResignation;
use App\Contexts\Membership\Application\Organisational\ScheduleResignationHandler;
use App\Contexts\Membership\Application\Organisational\VoidMembershipRecord;
use App\Contexts\Membership\Application\Organisational\VoidMembershipRecordHandler;
use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
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
 * PBDIGIT-354 S2-5a on PostgreSQL: Correct and Void as use cases (D-206, D-208 §2), stored append-only and reloaded,
 * published through C1; and **D-240 for a corrected End**: a Correct whose chain ends in an End is a competing End at
 * its CORRECTED date, refused at the append when that date is on or after a pending scheduled resignation's date.
 * Day ordinals: 20000 = 2024-10-04, 20020 = 2024-10-24, 20025 = 2024-10-29, 20030 = 2024-11-03 (scheduled), 20035 = 2024-11-08.
 */
final class PostgresCorrectAndVoidTest extends MembershipPostgresTestCase
{
    private int $minute = 0;

    private static function at(int $day, int $hour = 0, int $minute = 0): int
    {
        return ($day * 86_400 + $hour * 3_600 + $minute * 60) * 1_000_000;
    }

    /** a repository whose next record time is 2024-11-08 09:mm, advancing per call */
    private function members(): PostgresMemberRepository
    {
        $reading = self::at(20_035, 9, $this->minute++);

        return new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => $reading));
    }

    private function store(): PostgresScheduledResignationStore
    {
        return new PostgresScheduledResignationStore(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => self::at(20_020, 12)), new FixedOffsetOrganisationCalendar(0));
    }

    /** M1 admitted 2024-10-04 (recorded then); optionally D1 scheduled for 3 November and an earlier End E1. */
    private function organisation(bool $scheduled = false, ?int $earlierEnd = null): TenantId
    {
        $org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $org, 'name' => 's25a', 'slug' => 's25a-' . $org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $admit = self::at(20_000, 9);
        $this->assertTrue((new AdmitMemberHandler(new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => $admit)), new FakeAuthority()))->handle(
            new AdmitMember($org, 'P1', 'M1', 'A1', 20_000, 20_000, 20_365, false, 'registrar-1', 'decl-fixture', null, 'k-admit'))->isAppended());
        if ($scheduled) {
            $this->assertTrue((new ScheduleResignationHandler($this->members(), $this->store(), new FakeAuthority()))->handle(
                new ScheduleResignation($org, 'M1', 'D1', 20_010, 20_030, 'P1', null, 'k-schedule'))->isRecorded());
        }
        if ($earlierEnd !== null) {
            $this->assertTrue((new ResignMembershipHandler($this->members(), new FakeAuthority()))->handle(
                new ResignMembership($org, 'M1', 'E1', $earlierEnd, 'P1', null, 'k-e1'))->isAppended(), 'an earlier ending, allowed (MI-3)');
        }

        return TenantId::fromString($org);
    }

    private function correct(TenantId $org, int $to, string $target = 'E1', string $key = 'k-c', string $id = 'C1')
    {
        return (new CorrectMembershipRecordHandler($this->members(), new FakeAuthority()))->handle(
            new CorrectMembershipRecord($org->value(), 'M1', $id, $target, $to, 20_035, 'minutes:2024/12', 'registrar-1', null, $key));
    }

    private function void(TenantId $org, string $target = 'E1', string $key = 'k-v')
    {
        return (new VoidMembershipRecordHandler($this->members(), new FakeAuthority()))->handle(
            new VoidMembershipRecord($org->value(), 'M1', 'V1', $target, 20_035, 'minutes:2024/12', 'registrar-1', null, $key));
    }

    /** @return list<string> kind:fact_id@effective (Correct: its value) */
    private function log(TenantId $org): array
    {
        return array_map(static fn ($r) => "{$r->kind}:{$r->fact_id}" . ($r->effective_at === null ? '' : "@{$r->effective_at}"),
            DB::select('SELECT kind, fact_id, effective_at FROM org_membership_facts WHERE organisation_id = ? ORDER BY seq', [$org->value()]));
    }

    private function declarationState(TenantId $org): string
    {
        return $this->store()->find($org, MemberId::fromString('M1'), DeclarationId::fromString('D1'))->state()->value;
    }

    public function test_a_correction_and_a_void_are_stored_append_only_reloaded_and_published(): void
    {
        $org = $this->organisation(earlierEnd: 20_025);
        $events = (int) DB::selectOne("SELECT count(*) AS n FROM outbox_events WHERE organisation_id = ? AND event_type = 'MembershipCoverageResolved'", [$org->value()])->n;

        $this->assertTrue($this->correct($org, 20_027)->isAppended());
        $this->assertTrue($this->void($org, 'C1')->isAppended());

        $this->assertSame(['admit:A1@2024-10-04', 'end:E1@2024-10-29', 'correct:C1@2024-10-31', 'void:V1'], $this->log($org), 'nothing rewritten');
        $p = json_decode((string) DB::table('org_membership_facts')->where('organisation_id', $org->value())->where('fact_id', 'C1')->value('provenance'), true);
        $this->assertSame(['registrar-1', 'minutes:2024/12'], [$p['actorOrSource'], $p['declarationRef']], 'recorded from evidence');
        $member = $this->members()->load($org, MemberId::fromString('M1'));
        $this->assertFalse($member->resolve()->isMember(TimePoint::of(20_026)), 'the Correct voided: the original End (29 October) applies again');
        $this->assertSame($events + 2, (int) DB::selectOne("SELECT count(*) AS n FROM outbox_events WHERE organisation_id = ? AND event_type = 'MembershipCoverageResolved'", [$org->value()])->n, 'C1: both changed coverage');
    }

    public function test_d240_a_correction_moving_an_end_onto_or_after_a_pending_scheduled_date_is_refused(): void
    {
        $org = $this->organisation(scheduled: true, earlierEnd: 20_025);   // D1 still `scheduled`: not yet superseded

        foreach ([20_030 => 'k-on', 20_035 => 'k-after'] as $to => $key) {
            $this->assertSame(RefusalReason::ScheduledResignationTakesEffectFirst, $this->correct($org, $to, key: $key)->domainRefusal(), "corrected to $to");
        }
        $this->assertSame(['admit:A1@2024-10-04', 'end:E1@2024-10-29'], $this->log($org), 'no Correct written');
        $this->assertSame('scheduled', $this->declarationState($org), 'not executed, withdrawn or superseded by the attempt');
    }

    public function test_d240_a_correction_of_a_correction_is_judged_by_its_end(): void
    {
        $org = $this->organisation(scheduled: true, earlierEnd: 20_025);
        $this->assertTrue($this->correct($org, 20_027)->isAppended(), 'still before the date: allowed');

        $this->assertSame(RefusalReason::ScheduledResignationTakesEffectFirst, $this->correct($org, 20_031, 'C1', 'k-c2', 'C2')->domainRefusal(), 'the chain ends in E1: its corrected date counts');
        $this->assertTrue($this->correct($org, 20_026, 'C1', 'k-c3', 'C3')->isAppended());
    }

    public function test_d240_does_not_touch_a_correction_before_the_date_or_of_an_admission_or_a_void(): void
    {
        $org = $this->organisation(scheduled: true, earlierEnd: 20_025);

        $this->assertTrue($this->correct($org, 20_020)->isAppended(), 'earlier still: MI-3 unchanged');
        $this->assertTrue($this->correct($org, 20_001, 'A1', 'k-ca', 'CA')->isAppended(), 'an Admit is not an End');
        $this->assertTrue($this->void($org, 'E1')->isAppended(), 'a Void reopens the period: not a competing End');
        $this->assertSame('scheduled', $this->declarationState($org));

        (new LedgerClockCloser(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => self::at(20_036, 1)),
            new ScheduledResignationExecutor(new FixedOffsetOrganisationCalendar(0))))->closeOrRead($org);
        $this->assertSame('executed', $this->declarationState($org), 'the period open again: the resignation is recorded on its date');
        $this->assertContains('end:scheduled-resignation:D1@2024-11-03', $this->log($org));
    }
}
