<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\CorrectMembershipRecord;
use App\Contexts\Membership\Application\Organisational\CorrectMembershipRecordHandler;
use App\Contexts\Membership\Application\Organisational\RenewMembership;
use App\Contexts\Membership\Application\Organisational\RenewMembershipHandler;
use App\Contexts\Membership\Application\Organisational\RestoreMembership;
use App\Contexts\Membership\Application\Organisational\RestoreMembershipHandler;
use App\Contexts\Membership\Application\Organisational\SuspendMembership;
use App\Contexts\Membership\Application\Organisational\SuspendMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ScriptedLedgerClockReader;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * PBDIGIT-354 S2-5b on PostgreSQL: D-237 (a Renew's `until` corrected to a later date or to open-ended) and D-177 row
 * 14 (a Restore's date) are stored append-only, reloaded and published through C1; knownAt before the correction shows
 * the original. Migration 370000 / 300800: `correct_value_unlimited`, so an open-ended corrected value is stored
 * explicitly; a Correct row has exactly one of a date or that flag (CHECK).
 * Day ordinals: 20000 = 2024-10-04, 20050 = 2024-11-23, 20150 = 2025-03-03, 20200 = 2025-04-22.
 */
final class PostgresCorrectRenewAndRestoreTest extends MembershipPostgresTestCase
{
    private int $clock = 1_760_000_000_000_000;

    private function repo(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(fn (): int => $this->clock += 1_000));
    }

    private function organisation(): TenantId
    {
        $org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $org, 'name' => 's25b', 'slug' => 's25b-' . $org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $this->assertTrue((new AdmitMemberHandler($this->repo(), new FakeAuthority()))->handle(
            new AdmitMember($org, 'P1', 'M1', 'A1', 20_000, 20_000, 20_040, false, 'registrar-1', 'decl-fixture', null, 'k-admit'))->isAppended());

        return TenantId::fromString($org);
    }

    private function correct(TenantId $org, string $target, ?int $to, bool $openEnded = false, string $id = 'C1', int $decision = 20_060)
    {
        return (new CorrectMembershipRecordHandler($this->repo(), new FakeAuthority()))->handle(
            new CorrectMembershipRecord($org->value(), 'M1', $id, $target, $to, $decision, 'minutes:2024/12', 'registrar-1', null, "k-$id", $openEnded));
    }

    private function member(TenantId $org)
    {
        return $this->repo()->load($org, MemberId::fromString('M1'));
    }

    private function inForce(TenantId $org, int $at): bool
    {
        return $this->member($org)->resolve()->period(PeriodId::fromString('A1'))->isInForce(TimePoint::of($at));
    }

    public function test_d237_a_renewals_until_corrected_to_open_ended_is_stored_reloaded_and_published(): void
    {
        $org = $this->organisation();
        $this->assertTrue((new RenewMembershipHandler($this->repo(), new FakeAuthority()))->handle(
            new RenewMembership($org->value(), 'M1', 'R1', 20_050, 20_050, 20_150, false, 'treasurer-1', null, 'k-renew'))->isAppended());
        $before = $this->clock;
        $events = (int) DB::selectOne("SELECT count(*) AS n FROM outbox_events WHERE organisation_id = ? AND event_type = 'MembershipCoverageResolved'", [$org->value()])->n;

        $this->assertTrue($this->correct($org, 'R1', null, openEnded: true)->isAppended());

        $row = DB::table('org_membership_facts')->where('organisation_id', $org->value())->where('fact_id', 'C1')->first(['kind', 'effective_at', 'correct_value_unlimited']);
        $this->assertSame(['correct', null, true], [$row->kind, $row->effective_at, $row->correct_value_unlimited], 'stored explicitly as open-ended');
        $this->assertTrue($this->inForce($org, 1_000_000), 'reloaded: in force without end');
        $this->assertFalse($this->inForce($org, 20_045), 'D-242: the start is unchanged (the gap stays)');
        $this->assertFalse($this->member($org)->knownAt(Instant::of($before))->period(PeriodId::fromString('A1'))->isInForce(TimePoint::of(20_150)), 'as known before: until 2025-03-03');
        $this->assertSame($events + 1, (int) DB::selectOne("SELECT count(*) AS n FROM outbox_events WHERE organisation_id = ? AND event_type = 'MembershipCoverageResolved'", [$org->value()])->n, 'C1: inForce changed');
        $this->assertSame(['admit', 'renew', 'correct'], array_map(static fn ($r) => $r->kind, DB::select('SELECT kind FROM org_membership_facts WHERE organisation_id = ? ORDER BY seq', [$org->value()])), 'nothing rewritten');
    }

    public function test_d237_a_renewals_until_corrected_beyond_the_decision_date_and_refused_at_or_before_its_start(): void
    {
        $org = $this->organisation();
        (new RenewMembershipHandler($this->repo(), new FakeAuthority()))->handle(new RenewMembership($org->value(), 'M1', 'R1', 20_050, 20_050, 20_150, false, 'treasurer-1', null, 'k-renew'));

        $this->assertSame(RefusalReason::EmptySegment, $this->correct($org, 'R1', 20_050)->domainRefusal());
        $this->assertTrue($this->correct($org, 'R1', 20_200, id: 'C2', decision: 20_060)->isAppended(), 'until 2025-04-22, decided 2024-12-03');
        $this->assertSame('2025-04-22', DB::table('org_membership_facts')->where('organisation_id', $org->value())->where('fact_id', 'C2')->value('effective_at'));
        $this->assertTrue($this->inForce($org, 20_199));
        $this->assertFalse($this->inForce($org, 20_200));
    }

    public function test_row14_a_restores_date_is_corrected_and_a_suspensions_is_refused(): void
    {
        $org = $this->organisation();
        $this->assertTrue((new SuspendMembershipHandler($this->repo(), new FakeAuthority()))->handle(
            new SuspendMembership($org->value(), 'M1', 'S1', 20_010, 20_010, 20_030, 'board-1', null, 'k-s'))->isAppended());
        $this->assertTrue((new RestoreMembershipHandler($this->repo(), new FakeAuthority()))->handle(
            new RestoreMembership($org->value(), 'M1', 'T1', 'S1', 20_020, 20_020, 'board-1', null, 'k-t'))->isAppended());

        $this->assertTrue($this->correct($org, 'T1', 20_025)->isAppended());
        $this->assertTrue($this->member($org)->resolve()->isSuspended(TimePoint::of(20_024)));
        $this->assertFalse($this->member($org)->resolve()->isSuspended(TimePoint::of(20_025)));
        $this->assertSame(RefusalReason::OutsideRestriction, $this->correct($org, 'T1', 20_030, id: 'C2')->domainRefusal());
        $this->assertSame(RefusalReason::CorrectionOfSuspendNotPermitted, $this->correct($org, 'S1', 20_012, id: 'C3')->domainRefusal(), 'D-241');
    }

    public function test_the_database_keeps_a_correct_row_to_exactly_one_value(): void
    {
        $org = $this->organisation();
        $base = "INSERT INTO org_membership_facts (organisation_id, membership_id, seq, fact_id, kind, effective_at, target_fact_id, provenance, consequences, g2_version, recorded_at, correct_value_unlimited)
            VALUES (?, 'M1', 9, 'CX', 'correct', %s, 'A1', '{}', '[]', 1, now(), %s)";
        foreach (['both' => ["'2024-10-05'", 'true'], 'neither' => ['NULL', 'false']] as $why => [$date, $flag]) {
            try {
                DB::transaction(static fn () => DB::statement(sprintf($base, $date, $flag), [$org->value()]));
                $this->fail("accepted: $why");
            } catch (QueryException $e) {
                $this->assertStringContainsString('org_membership_facts_kind_check', $e->getMessage(), $why);
            }
        }
        try {
            DB::transaction(static fn () => DB::statement("INSERT INTO org_membership_facts (organisation_id, membership_id, seq, fact_id, kind, effective_at, initial_term, provenance, recorded_at, correct_value_unlimited)
                VALUES (?, 'M1', 9, 'AX', 'admit', '2024-10-05', 'unlimited', '{}', now(), true)", [$org->value()]));
            $this->fail('accepted: the flag on a non-correct row');
        } catch (QueryException $e) {
            $this->assertStringContainsString('org_membership_facts_correct_value_check', $e->getMessage());
        }
    }
}
