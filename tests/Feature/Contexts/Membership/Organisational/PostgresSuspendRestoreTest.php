<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\RestoreMembership;
use App\Contexts\Membership\Application\Organisational\RestoreMembershipHandler;
use App\Contexts\Membership\Application\Organisational\SuspendMembership;
use App\Contexts\Membership\Application\Organisational\SuspendMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Restore;
use App\Contexts\Membership\Domain\Organisational\Suspend;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Support\ScriptedLedgerClockReader;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * PBDIGIT-354 S2-2 on PostgreSQL: Suspend and Restore (D-163, D-177) are stored, reloaded and published.
 * - both facts round-trip exactly (period / target, dates, an open-ended restriction);
 * - C1 is written in the same transaction when `suspended` changes, with coverage and `inForce` unchanged
 *   (D-177 §10, §12); a restriction that changes nothing writes no C1;
 * - the reloaded member answers `knownAt` as the in-memory one did;
 * - the database itself refuses a malformed suspend or restore row.
 * Day ordinals: 20000 = 2024-10-04, 20010 = 2024-10-14, 20020 = 2024-10-24, 20050 = 2024-11-23, 20365 = 2025-10-04.
 */
final class PostgresSuspendRestoreTest extends MembershipPostgresTestCase
{
    private string $org;
    private int $clock = 1_760_000_000_000_000;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $this->org, 'name' => 's2-2 suspend', 'slug' => 's22-' . $this->org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        // admitted 2024-10-04 with the term [2024-10-04, 2025-10-04)
        $outcome = (new AdmitMemberHandler($this->repository(), new FakeAuthority()))->handle(
            new AdmitMember($this->org, 'person-1', 'M1', 'A1', 20_000, 20_000, 20_365, false, 'registrar-1', 'decl-fixture', null, 'k-admit'),
        );
        $this->assertTrue($outcome->isAppended());
    }

    private function repository(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(fn (): int => $this->clock += 1_000));
    }

    private function suspend(string $key, string $factId, int $at, ?int $until = null)
    {
        return (new SuspendMembershipHandler($this->repository(), new FakeAuthority()))->handle(
            new SuspendMembership($this->org, 'M1', $factId, $at, $at, $until, 'board-secretary', null, $key),
        );
    }

    private function restore(string $key, string $factId, string $suspension, int $at)
    {
        return (new RestoreMembershipHandler($this->repository(), new FakeAuthority()))->handle(
            new RestoreMembership($this->org, 'M1', $factId, $suspension, $at, $at, 'board-secretary', null, $key),
        );
    }

    private function reloaded()
    {
        return $this->repository()->load(TenantId::fromString($this->org), MemberId::fromString('M1'));
    }

    /** C1 payloads by their own seq (created_at has second precision; the id is random) */
    private function c1(): array
    {
        $events = array_map(static fn ($r) => json_decode($r->payload, false, 512, JSON_THROW_ON_ERROR),
            DB::select("SELECT payload FROM outbox_events WHERE organisation_id = ? AND event_type = 'MembershipCoverageResolved'", [$this->org]));
        usort($events, static fn (object $x, object $y): int => $x->seq <=> $y->seq);

        return $events;
    }

    private static function intervals(array $set): array
    {
        return array_map(static fn ($i) => [$i->from, $i->to], $set);
    }

    public function test_a_suspension_and_its_restore_are_stored_and_reloaded_exactly(): void
    {
        $this->assertTrue($this->suspend('k1', 'S1', 20_010, 20_050)->isAppended());
        $this->assertTrue($this->restore('k2', 'R1', 'S1', 20_020)->isAppended());

        $member = $this->reloaded();
        [$suspend, $restore] = [$member->log()[1]->fact(), $member->log()[2]->fact()];
        $this->assertInstanceOf(Suspend::class, $suspend);
        $this->assertTrue(PeriodId::fromString('A1')->equals($suspend->period()));
        $this->assertSame([20_010, 20_050], [$suspend->effectiveAt()->ordinal(), $suspend->until()->ordinal()]);
        $this->assertInstanceOf(Restore::class, $restore);
        $this->assertTrue(FactId::fromString('S1')->equals($restore->suspension()));
        $this->assertSame(20_020, $restore->effectiveAt()->ordinal());
        $this->assertSame('board-secretary', $restore->provenance()->actorOrSource());
        $this->assertTrue($member->resolve()->isSuspended(TimePoint::of(20_015)));
        $this->assertFalse($member->resolve()->isSuspended(TimePoint::of(20_020)));
        $this->assertTrue($member->resolve()->isMember(TimePoint::of(20_015)), 'suspended, still a member');

        $rows = DB::select("SELECT fact_id, kind, effective_at::text AS e, target_fact_id AS t, restriction_until_kind AS k, restriction_until::text AS u, renew_until_kind AS rk
            FROM org_membership_facts WHERE organisation_id = ? AND kind IN ('suspend', 'restore') ORDER BY seq", [$this->org]);
        $this->assertSame([['S1', 'suspend', '2024-10-14', 'A1', 'until', '2024-11-23', null], ['R1', 'restore', '2024-10-24', 'S1', null, null, null]],
            array_map(static fn ($r) => [$r->fact_id, $r->kind, $r->e, $r->t, $r->k, $r->u, $r->rk], $rows));
    }

    public function test_an_open_ended_suspension_round_trips(): void
    {
        $this->assertTrue($this->suspend('k1', 'S1', 20_010)->isAppended());

        $this->assertTrue($this->reloaded()->log()[1]->fact()->until()->isInfinite());
        $row = DB::selectOne("SELECT restriction_until_kind AS k, restriction_until AS u FROM org_membership_facts WHERE organisation_id = ? AND fact_id = 'S1'", [$this->org]);
        $this->assertSame(['unlimited', null], [$row->k, $row->u]);
    }

    public function test_c1_carries_the_changed_suspended_intervals_and_nothing_else(): void
    {
        [$admitted] = $this->c1();
        $this->suspend('k1', 'S1', 20_010);
        $this->restore('k2', 'R1', 'S1', 20_020);

        [, $suspended, $restored] = $this->c1();
        $this->assertSame([['2024-10-14', null]], self::intervals($suspended->suspended), 'open-ended, as published at the suspension');
        $this->assertSame([['2024-10-14', '2024-10-24']], self::intervals($restored->suspended), 'ended by the restore');
        foreach ([$suspended, $restored] as $event) {
            $this->assertEquals($admitted->coverage, $event->coverage, 'D-177 §12: coverage unchanged');
            $this->assertEquals($admitted->inForce, $event->inForce, 'S2: the time in force unchanged');
        }
        $this->assertSame([], $admitted->suspended);
    }

    public function test_a_restriction_that_changes_nothing_writes_no_c1(): void
    {
        $this->suspend('k1', 'S1', 20_010);
        $this->assertTrue($this->suspend('k2', 'S2', 20_020, 20_050)->isAppended(), 'accepted: inside the open-ended S1');

        $this->assertCount(2, $this->c1(), 'the admission and the first suspension only');
    }

    public function test_the_reloaded_member_knows_what_was_known_when(): void
    {
        $this->suspend('k1', 'S1', 20_010);
        $suspendedAt = $this->reloaded()->log()[1]->recordedAt();
        $this->restore('k2', 'R1', 'S1', 20_020);

        $member = $this->reloaded();
        $this->assertTrue($member->knownAt($suspendedAt)->isSuspended(TimePoint::of(20_030)), 'before the restore was recorded: open-ended');
        $this->assertFalse($member->resolve()->isSuspended(TimePoint::of(20_030)));
        $this->assertFalse($member->knownAt(Instant::of($suspendedAt->ordinal() - 1))->isSuspended(TimePoint::of(20_015)), 'before the suspension was recorded');
    }

    /** @return iterable<string, array{string}> */
    public static function malformedRows(): iterable
    {
        $row = static fn (string $kind, string $columns, string $values): string => "INSERT INTO org_membership_facts (organisation_id, membership_id, seq, fact_id, kind, recorded_at, provenance, $columns)
            VALUES (?, 'M1', 2, 'X', '$kind', now(), '{}'::jsonb, $values)";

        yield 'suspend: no period' => [$row('suspend', 'effective_at, restriction_until_kind', "'2024-10-14', 'unlimited'")];
        yield 'suspend: no effective date' => [$row('suspend', 'target_fact_id, restriction_until_kind', "'A1', 'unlimited'")];
        yield 'suspend: no end stated' => [$row('suspend', 'target_fact_id, effective_at', "'A1', '2024-10-14'")];
        yield 'suspend: until without a date' => [$row('suspend', 'target_fact_id, effective_at, restriction_until_kind', "'A1', '2024-10-14', 'until'")];
        yield 'suspend: unlimited with a date' => [$row('suspend', 'target_fact_id, effective_at, restriction_until_kind, restriction_until', "'A1', '2024-10-14', 'unlimited', '2024-11-23'")];
        yield 'suspend: an empty restriction' => [$row('suspend', 'target_fact_id, effective_at, restriction_until_kind, restriction_until', "'A1', '2024-10-14', 'until', '2024-10-14'")];
        yield 'suspend: a renewal end on it' => [$row('suspend', 'target_fact_id, effective_at, restriction_until_kind, renew_until_kind', "'A1', '2024-10-14', 'unlimited', 'unlimited'")];
        yield 'restore: no target' => [$row('restore', 'effective_at', "'2024-10-24'")];
        yield 'restore: no effective date' => [$row('restore', 'target_fact_id', "'A1'")];
        yield 'restore: a restriction end on it' => [$row('restore', 'target_fact_id, effective_at, restriction_until_kind', "'A1', '2024-10-24', 'unlimited'")];
        yield 'end: a restriction end on it' => [$row('end', 'target_fact_id, effective_at, end_kind, restriction_until_kind', "'A1', '2024-10-24', 'resignation', 'unlimited'")];
    }

    #[DataProvider('malformedRows')]
    public function test_the_database_refuses_a_malformed_row(string $sql): void
    {
        try {
            DB::insert($sql, [$this->org]);
            $this->fail('the row was accepted');
        } catch (QueryException $e) {
            $this->assertSame('23514', $e->getCode(), 'a CHECK violation, not another error: ' . $e->getMessage());
        }
    }
}
