<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\RenewMembership;
use App\Contexts\Membership\Application\Organisational\RenewMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Renew;
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
 * PBDIGIT-354 S2-1 on PostgreSQL: a Renew (D-163, D-177) is stored, reloaded and published.
 * - the fact round-trips exactly (period, both dates, open-ended);
 * - C1 is written in the same transaction when `inForce` changes, with `coverage` unchanged (D-177 §10, §12);
 *   a renewal that changes nothing in force writes no C1;
 * - the database itself refuses a malformed renew row (the kind CHECK).
 * Day ordinals are days since 1970-01-01: 20000 = 2024-10-04, 20100 = 2025-01-12, 20465 = 2026-01-12.
 */
final class PostgresRenewTest extends MembershipPostgresTestCase
{
    private string $org;
    private int $clock = 1_760_000_000_000_000;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $this->org, 'name' => 's2-1 renew', 'slug' => 's21-' . $this->org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        // admitted 2024-10-04 with the term [2024-10-04, 2025-01-12)
        $outcome = (new AdmitMemberHandler($this->repository(), new FakeAuthority()))->handle(
            new AdmitMember($this->org, 'person-1', 'M1', 'A1', 20_000, 20_000, 20_100, false, 'registrar-1', 'decl-fixture', null, 'k-admit'),
        );
        $this->assertTrue($outcome->isAppended());
    }

    private function repository(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(fn (): int => $this->clock += 1_000));
    }

    private function renew(string $key, string $factId, int $at, ?int $until, bool $unlimited = false)
    {
        return (new RenewMembershipHandler($this->repository(), new FakeAuthority()))->handle(
            new RenewMembership($this->org, 'M1', $factId, $at, $at, $until, $unlimited, 'treasurer-1', null, $key),
        );
    }

    private function reloaded()
    {
        return $this->repository()->load(TenantId::fromString($this->org), MemberId::fromString('M1'));
    }

    /**
     * The C1 payloads of this organisation, ordered by their own `seq`. Not by created_at: it has second precision
     * and the row id is random, so two events written in one second have no stable order there.
     *
     * @return list<object>
     */
    private function c1(): array
    {
        $events = array_map(static fn ($r) => json_decode($r->payload, false, 512, JSON_THROW_ON_ERROR),
            DB::select("SELECT payload FROM outbox_events WHERE organisation_id = ? AND event_type = 'MembershipCoverageResolved'", [$this->org]));
        usort($events, static fn (object $x, object $y): int => $x->seq <=> $y->seq);

        return $events;
    }

    public function test_a_renewal_is_stored_reloaded_and_extends_the_time_in_force(): void
    {
        $outcome = $this->renew('k1', 'R1', 20_090, 20_465);

        $this->assertTrue($outcome->isAppended());
        $member = $this->reloaded();
        $fact = $member->log()[1]->fact();
        $this->assertInstanceOf(Renew::class, $fact);
        $this->assertTrue(PeriodId::fromString('A1')->equals($fact->period()));
        $this->assertSame([20_090, 20_465], [$fact->effectiveAt()->ordinal(), $fact->until()->ordinal()]);
        $this->assertSame('treasurer-1', $fact->provenance()->actorOrSource());
        $this->assertTrue($member->resolve()->isMember(TimePoint::of(20_300)), 'in force inside the renewed term');
        $this->assertFalse($member->resolve()->isMember(TimePoint::of(20_465)));

        $row = DB::selectOne("SELECT kind, effective_at::text AS effective_at, target_fact_id, renew_until_kind, renew_until::text AS renew_until, initial_term, end_kind
            FROM org_membership_facts WHERE organisation_id = ? AND fact_id = 'R1'", [$this->org]);
        $this->assertSame(['renew', '2025-01-02', 'A1', 'until', '2026-01-12', null, null],
            [$row->kind, $row->effective_at, $row->target_fact_id, $row->renew_until_kind, $row->renew_until, $row->initial_term, $row->end_kind]);
    }

    public function test_an_open_ended_renewal_round_trips(): void
    {
        $this->assertTrue($this->renew('k1', 'R1', 20_090, null, true)->isAppended());

        $fact = $this->reloaded()->log()[1]->fact();
        $this->assertTrue($fact->until()->isInfinite());
        $row = DB::selectOne("SELECT renew_until_kind, renew_until FROM org_membership_facts WHERE organisation_id = ? AND fact_id = 'R1'", [$this->org]);
        $this->assertSame(['unlimited', null], [$row->renew_until_kind, $row->renew_until]);
    }

    public function test_c1_carries_the_changed_in_force_and_the_unchanged_coverage(): void
    {
        $before = $this->c1();
        $this->assertCount(1, $before, 'the admission wrote one C1');

        $this->renew('k1', 'R1', 20_090, 20_465);

        $events = $this->c1();
        $this->assertCount(2, $events, 'the renewal wrote one more, in its own transaction');
        $c1 = $events[1];
        $this->assertSame(2, $c1->seq);
        $this->assertEquals($before[0]->coverage, $c1->coverage, 'D-177 §12: a Renew never changes coverage');
        $this->assertSame([['2024-10-04', '2026-01-12']], array_map(static fn ($i) => [$i->from, $i->to], $c1->inForce), 'the two segments are one interval');
        $this->assertSame([['2024-10-04', '2025-01-12']], array_map(static fn ($i) => [$i->from, $i->to], $before[0]->inForce));
        $this->assertSame([], $c1->suspended);
    }

    public function test_a_renewal_inside_the_existing_term_writes_no_c1(): void
    {
        $this->assertTrue($this->renew('k1', 'R1', 20_010, 20_050)->isAppended(), 'accepted: segments are a set (row 7)');

        $this->assertCount(1, $this->c1(), 'nothing in force changed, so nothing is published');
        $this->assertSame(2, $this->reloaded()->version());
    }

    /** @return iterable<string, array{string}> */
    public static function malformedRenewRows(): iterable
    {
        $row = static fn (string $columns, string $values): string => "INSERT INTO org_membership_facts (organisation_id, membership_id, seq, fact_id, kind, recorded_at, provenance, $columns)
            VALUES (?, 'M1', 2, 'RX', 'renew', now(), '{}'::jsonb, $values)";

        yield 'no period' => [$row('effective_at, renew_until_kind, renew_until', "'2025-01-02', 'until', '2026-01-12'")];
        yield 'no effective date' => [$row('target_fact_id, renew_until_kind, renew_until', "'A1', 'until', '2026-01-12'")];
        yield 'no term stated' => [$row('target_fact_id, effective_at', "'A1', '2025-01-02'")];
        yield 'until without a date' => [$row('target_fact_id, effective_at, renew_until_kind', "'A1', '2025-01-02', 'until'")];
        yield 'unlimited with a date' => [$row('target_fact_id, effective_at, renew_until_kind, renew_until', "'A1', '2025-01-02', 'unlimited', '2026-01-12'")];
        yield 'an empty segment' => [$row('target_fact_id, effective_at, renew_until_kind, renew_until', "'A1', '2025-01-02', 'until', '2025-01-02'")];
        yield 'an end kind on a renewal' => [$row('target_fact_id, effective_at, renew_until_kind, renew_until, end_kind', "'A1', '2025-01-02', 'until', '2026-01-12', 'resignation'")];
    }

    #[DataProvider('malformedRenewRows')]
    public function test_the_database_refuses_a_malformed_renew_row(string $sql): void
    {
        try {
            DB::insert($sql, [$this->org]);
            $this->fail('the row was accepted');
        } catch (QueryException $e) {
            $this->assertSame('23514', $e->getCode(), 'a CHECK violation, not another error: ' . $e->getMessage());
        }
    }

    public function test_the_renew_columns_are_empty_on_every_other_kind(): void
    {
        try {
            DB::insert("INSERT INTO org_membership_facts (organisation_id, membership_id, seq, fact_id, kind, effective_at, target_fact_id, end_kind, recorded_at, provenance, renew_until_kind)
                VALUES (?, 'M1', 2, 'EX', 'end', '2025-01-02', 'A1', 'resignation', now(), '{}'::jsonb, 'unlimited')", [$this->org]);
            $this->fail('the row was accepted');
        } catch (QueryException $e) {
            $this->assertSame('23514', $e->getCode(), $e->getMessage());
        }
    }
}
