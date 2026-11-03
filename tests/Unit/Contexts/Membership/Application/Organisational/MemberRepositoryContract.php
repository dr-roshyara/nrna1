<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Domain\Organisational\Correct;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\C2Histories;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * Reusable contract for every MemberRepositoryPort adapter (Slice 1a contract §3–§4).
 * Used by the in-memory reference adapter (Slice 1a) and the PostgreSQL adapter
 * (Slice 1b; PBDIGIT-260), unchanged. A trait, so that each adapter's test can have
 * its own base class (B-3). PBDIGIT-259.
 */
trait MemberRepositoryContract
{
    /** @param \Closure(): int $clock returns the recording instant ordinal */
    abstract protected function repository(\Closure $clock): MemberRepositoryPort;

    /** The tenant for a label ('O1', 'O2'); a database adapter may need a real organisation. */
    abstract protected function tenantFor(string $label): TenantId;

    private int $now = 100;

    private function repo(): MemberRepositoryPort
    {
        return $this->repository(fn (): int => $this->now);
    }

    private function tenant(string $t = 'O1'): TenantId
    {
        return $this->tenantFor($t);
    }

    private static function admit(Member $member, string $factId, int $at): Decision
    {
        return $member->decideAdmit(FactId::fromString($factId), TimePoint::of($at), TimePoint::of($at), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture'));
    }

    private static function key(string $k): IdempotencyKey
    {
        return IdempotencyKey::fromString($k);
    }

    private function created(MemberRepositoryPort $repo, string $member = 'M1', string $person = 'P1', string $tenant = 'O1'): Member
    {
        $result = $repo->create($this->tenant($tenant), MemberId::fromString($member), PersonRef::fromString($person));
        $this->assertTrue($result->isCreated());

        return $result->member();
    }

    public function test_a_created_member_can_be_loaded_and_found_by_holder(): void
    {
        $repo = $this->repo();
        $member = $this->created($repo);

        $this->assertSame(0, $member->version());
        $this->assertSame('P1', $repo->load($this->tenant(), MemberId::fromString('M1'))?->holder()->value());
        $this->assertSame('M1', $repo->findByHolder($this->tenant(), PersonRef::fromString('P1'))?->id()->value());
    }

    public function test_an_unknown_member_is_not_found(): void
    {
        $repo = $this->repo();

        $this->assertNull($repo->load($this->tenant(), MemberId::fromString('nope')));
        $this->assertNull($repo->findByHolder($this->tenant(), PersonRef::fromString('nobody')));
    }

    public function test_one_member_per_person_and_organisation(): void
    {
        // INV-M: the second create for the same holder is told who exists.
        $repo = $this->repo();
        $this->created($repo, 'M1', 'P1');

        $second = $repo->create($this->tenant(), MemberId::fromString('M2'), PersonRef::fromString('P1'));

        $this->assertFalse($second->isCreated());
        $this->assertSame('M1', $second->existingMemberId()->value());
        $this->assertNull($repo->load($this->tenant(), MemberId::fromString('M2')));
    }

    public function test_the_same_person_may_be_a_member_of_another_organisation(): void
    {
        $repo = $this->repo();
        $this->created($repo, 'M1', 'P1', 'O1');

        $this->assertTrue($repo->create($this->tenant('O2'), MemberId::fromString('M9'), PersonRef::fromString('P1'))->isCreated());
    }

    public function test_tenant_isolation(): void
    {
        $repo = $this->repo();
        $member = $this->created($repo, 'M1', 'P1', 'O1');

        $this->assertNull($repo->load($this->tenant('O2'), MemberId::fromString('M1')));
        $this->assertNull($repo->findByHolder($this->tenant('O2'), PersonRef::fromString('P1')));
        $this->assertSame(AppendOutcome::MemberNotFound, $repo->append($this->tenant('O2'), MemberId::fromString('M1'), self::admit($member, 'A1', 1), self::key('k1'), 'fp')->outcome());
        $this->assertSame(0, $repo->load($this->tenant('O1'), MemberId::fromString('M1'))->version());
    }

    public function test_an_accepted_decision_is_appended_at_the_next_seq(): void
    {
        $repo = $this->repo();
        $member = $this->created($repo);

        $result = $repo->append($this->tenant(), $member->id(), self::admit($member, 'A1', 1), self::key('k1'), 'fp1');

        $this->assertSame(AppendOutcome::Appended, $result->outcome());
        $this->assertSame(1, $result->appended()->seq());
        $reloaded = $repo->load($this->tenant(), $member->id());
        $this->assertSame(1, $reloaded->version());
        $this->assertSame('A1', $reloaded->log()[0]->fact()->id()->value());
        $this->assertTrue($reloaded->resolve()->isMember(TimePoint::of(5)));
    }

    public function test_facts_keep_their_order_and_seq_is_contiguous(): void
    {
        $repo = $this->repo();
        $member = $this->created($repo);
        $repo->append($this->tenant(), $member->id(), self::admit($member, 'A1', 1), self::key('k1'), 'fp1');
        $member = $repo->load($this->tenant(), $member->id());
        $end = $member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), TimePoint::of(5), EndKind::fromString('resignation'), Provenance::decision('registrar'));
        $repo->append($this->tenant(), $member->id(), $end, self::key('k2'), 'fp2');

        $log = $repo->load($this->tenant(), $member->id())->log();
        $this->assertSame([1, 2], array_map(static fn ($r) => $r->seq(), $log));
        $this->assertSame(['A1', 'E1'], array_map(static fn ($r) => $r->fact()->id()->value(), $log));
    }

    public function test_every_key_is_answered_with_its_own_fact_not_only_the_first(): void
    {
        // PBDIGIT-264 Part 2 review condition C1 (finding R-1): the answer for a key must be the fact that key
        // appended, also after seq 1. Pins the seq term of the PostgreSQL commands → facts join (F-4b).
        $repo = $this->repo();
        $member = $this->created($repo);
        $admitted = $repo->append($this->tenant(), $member->id(), self::admit($member, 'A1', 1), self::key('k1'), 'fp1')->appended();
        $member = $repo->load($this->tenant(), $member->id());
        $end = $member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), TimePoint::of(5), EndKind::fromString('resignation'), Provenance::decision('registrar'));
        $ended = $repo->append($this->tenant(), $member->id(), $end, self::key('k2'), 'fp2')->appended();

        $this->assertEquals($ended, $repo->appliedCommand($this->tenant(), self::key('k2'))->appended(), 'the End key answers with the End');
        $this->assertSame(['E1', 2], [$ended->factId()->value(), $ended->seq()]);
        $this->assertEquals($admitted, $repo->appliedCommand($this->tenant(), self::key('k1'))->appended(), 'the Admit key still answers with the Admit');
    }

    public function test_a_decision_based_on_a_stale_version_is_not_appended(): void
    {
        // R-1a-1 / R-1a-2: a stale decision cannot be reused.
        $repo = $this->repo();
        $member = $this->created($repo);
        $stale = self::admit($member, 'A1', 1);
        $repo->append($this->tenant(), $member->id(), self::admit($member, 'A0', 0), self::key('k0'), 'fp0');

        $result = $repo->append($this->tenant(), $member->id(), $stale, self::key('k1'), 'fp1');

        $this->assertSame(AppendOutcome::ConcurrencyConflict, $result->outcome());
        $this->assertSame(1, $repo->load($this->tenant(), $member->id())->version());
    }

    public function test_a_refused_decision_cannot_be_appended(): void
    {
        $repo = $this->repo();
        $member = $this->created($repo);

        $this->expectException(\InvalidArgumentException::class);
        $repo->append($this->tenant(), $member->id(), Decision::refused(RefusalReason::DuplicateFactId), self::key('k1'), 'fp1');
    }

    public function test_a_retried_key_returns_the_original_outcome_and_appends_nothing(): void
    {
        // IDEM-1 / IDEM-3
        $repo = $this->repo();
        $member = $this->created($repo);
        $first = $repo->append($this->tenant(), $member->id(), self::admit($member, 'A1', 1), self::key('k1'), 'fp1');

        $retry = $repo->append($this->tenant(), $member->id(), self::admit($member, 'A1', 1), self::key('k1'), 'fp1');

        $this->assertSame(AppendOutcome::AlreadyApplied, $retry->outcome());
        $this->assertEquals($first->appended(), $retry->appended());
        $this->assertSame(1, $repo->load($this->tenant(), $member->id())->version());
        $this->assertEquals([$first->appended(), 'fp1'], [$repo->appliedCommand($this->tenant(), self::key('k1'))->appended(), $repo->appliedCommand($this->tenant(), self::key('k1'))->fingerprint()]);
    }

    public function test_a_key_reused_for_a_different_command_is_refused(): void
    {
        // IDEM-2
        $repo = $this->repo();
        $member = $this->created($repo);
        $repo->append($this->tenant(), $member->id(), self::admit($member, 'A1', 1), self::key('k1'), 'fp1');
        $member = $repo->load($this->tenant(), $member->id());

        $other = $member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), TimePoint::of(5), EndKind::fromString('resignation'), Provenance::decision('registrar'));
        $this->assertSame(AppendOutcome::IdempotencyKeyConflict, $repo->append($this->tenant(), $member->id(), $other, self::key('k1'), 'fp-other')->outcome());
        $this->assertSame(1, $repo->load($this->tenant(), $member->id())->version());
    }

    public function test_idempotency_keys_are_tenant_scoped_and_unknown_keys_have_no_outcome(): void
    {
        $repo = $this->repo();
        $a = $this->created($repo, 'M1', 'P1', 'O1');
        $b = $this->created($repo, 'M2', 'P1', 'O2');
        $repo->append($this->tenant('O1'), $a->id(), self::admit($a, 'A1', 1), self::key('k1'), 'fp1');

        $this->assertNull($repo->appliedCommand($this->tenant('O2'), self::key('k1')));
        $this->assertSame(AppendOutcome::Appended, $repo->append($this->tenant('O2'), $b->id(), self::admit($b, 'A1', 1), self::key('k1'), 'fp1')->outcome());
    }

    public function test_recorded_at_never_decreases_even_if_the_clock_goes_back(): void
    {
        // R-1a-3
        $repo = $this->repo();
        $member = $this->created($repo);
        $this->now = 500;
        $repo->append($this->tenant(), $member->id(), self::admit($member, 'A1', 1), self::key('k1'), 'fp1');
        $member = $repo->load($this->tenant(), $member->id());
        $this->now = 300;
        $end = $member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), TimePoint::of(5), EndKind::fromString('resignation'), Provenance::decision('registrar'));
        $second = $repo->append($this->tenant(), $member->id(), $end, self::key('k2'), 'fp2');

        $this->assertGreaterThanOrEqual(500, $second->appended()->recordedAt()->ordinal());
    }

    // COR-4 step 3 (PBDIGIT-329, Q-2, V-5): recordedAt strictly increases per Member, even with a frozen or backward
    // clock, so KnownAt can observe the state between any two facts.
    public function test_recorded_at_strictly_increases_even_with_a_frozen_or_backward_clock(): void
    {
        $repo = $this->repo();
        $member = $this->created($repo);
        $this->now = 500;
        $instants = [];
        foreach ([500, 500, 300] as $k => $clock) {
            $this->now = $clock;
            $decision = $k === 0
                ? self::admit($member, 'A1', 1)
                : $member->decideCorrect(FactId::fromString("C$k"), FactId::fromString('A1'), TimePoint::of(1 + $k), TimePoint::of(10), Provenance::decision('registrar'));
            $instants[] = $repo->append($this->tenant(), $member->id(), $decision, self::key("k$k"), "fp$k")->appended()->recordedAt()->ordinal();
            $member = $repo->load($this->tenant(), $member->id());
        }

        $this->assertSame([500, 501, 502], $instants);
        $this->assertSame([500, 501, 502], array_map(static fn ($r): int => $r->recordedAt()->ordinal(), $member->log()));
    }

    /**
     * COR-4 (PBDIGIT-329, AC-1): every fact kind round-trips identically, including a Correct with consequences on two
     * periods (COR-2 review F-1 history) and Annul / Void of a Correct. Facts, seq, recordedAt and resolve() equal.
     */
    public function test_every_fact_kind_round_trips_identically(): void
    {
        $repo = $this->repo();
        $member = $this->created($repo);
        $p = Provenance::decision('registrar');
        $steps = [
            fn (Member $m) => self::admit($m, 'A0', 0),
            fn (Member $m) => $m->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A0'), TimePoint::of(2), TimePoint::of(2), EndKind::fromString('resignation'), $p),
            fn (Member $m) => self::admit($m, 'A1', 9),
            fn (Member $m) => $m->decideCorrect(FactId::fromString('C3'), FactId::fromString('A1'), TimePoint::of(4), TimePoint::of(9), $p, Instant::of(901)),
            fn (Member $m) => $m->decideEnd(FactId::fromString('E4'), PeriodId::fromString('A1'), TimePoint::of(6), TimePoint::of(9), EndKind::fromString('resignation'), $p),
            fn (Member $m) => self::admit($m, 'A2', 8),
            fn (Member $m) => $m->decideAnnul(FactId::fromString('X6'), FactId::fromString('C3'), $p, Instant::of(902)),
            fn (Member $m) => $m->decideCorrect(FactId::fromString('C7'), FactId::fromString('A2'), TimePoint::of(2), TimePoint::of(9), $p),
            fn (Member $m) => $m->decideCorrect(FactId::fromString('C8'), FactId::fromString('A1'), TimePoint::of(1), TimePoint::of(9), $p),
            fn (Member $m) => $m->decideVoid(FactId::fromString('V9'), FactId::fromString('X6'), $p),
        ];
        $expected = [];
        foreach ($steps as $k => $step) {
            $decision = $step($member);
            $this->assertTrue($decision->isAccepted(), "step $k: " . ($decision->refusal()?->value ?? ''));
            $expected[] = $decision->fact();
            $repo->append($this->tenant(), $member->id(), $decision, self::key("k$k"), "fp$k");
            $member = $repo->load($this->tenant(), $member->id());
        }

        $twoPeriods = $member->log()[8]->fact();
        $this->assertInstanceOf(Correct::class, $twoPeriods);
        $this->assertCount(2, $twoPeriods->consequences(), 'the two-period Correct keeps both consequences');
        $this->assertEquals($expected, array_map(static fn ($r) => $r->fact(), $member->log()), 'every fact as decided');
        $this->assertSame(range(1, count($steps)), array_map(static fn ($r): int => $r->seq(), $member->log()));
        $rebuilt = Member::fromLog($member->id(), $member->holder(), $member->organisation(), ...$member->log());
        $this->assertEquals($rebuilt->resolve(), $member->resolve());
    }

    // COR-4 (PBDIGIT-329): appliedCommand answers a key of every new kind with that fact (F-4b join unchanged).
    public function test_applied_command_answers_correction_facts_with_their_own_fact(): void
    {
        $repo = $this->repo();
        $member = $this->created($repo);
        $p = Provenance::decision('registrar');
        $repo->append($this->tenant(), $member->id(), self::admit($member, 'A1', 1), self::key('k1'), 'fp1');
        foreach ([
            'k2' => fn (Member $m) => $m->decideCorrect(FactId::fromString('C2'), FactId::fromString('A1'), TimePoint::of(0), TimePoint::of(9), $p),
            'k3' => fn (Member $m) => $m->decideAnnul(FactId::fromString('X3'), FactId::fromString('C2'), $p, Instant::of(900)),
            'k4' => fn (Member $m) => $m->decideVoid(FactId::fromString('V4'), FactId::fromString('X3'), $p),
        ] as $key => $decide) {
            $member = $repo->load($this->tenant(), $member->id());
            $appended = $repo->append($this->tenant(), $member->id(), $decide($member), self::key($key), "fp-$key")->appended();

            $answer = $repo->appliedCommand($this->tenant(), self::key($key));
            $this->assertNotNull($answer, $key);
            $this->assertSame($appended->factId()->value(), $answer->appended()->factId()->value(), $key);
            $this->assertSame($appended->seq(), $answer->appended()->seq(), $key);
            $this->assertSame($appended->recordedAt()->ordinal(), $answer->appended()->recordedAt()->ordinal(), $key);
        }
    }

    /** COR-4 step 7: which bounded histories the reload differential checks: every n-th (1 = all). */
    protected function reloadDifferentialStride(): int
    {
        return 1;
    }

    /**
     * COR-4 step 7 (PBDIGIT-329, AC-8): every C-2 history the kernel accepts (H = 5, L <= 4; `C2Histories`) is
     * replayed through the repository, one decision at a time on the freshly loaded Member, then reloaded. Facts,
     * seq and resolve() must equal the in-memory original: no fact kind, consequence or order is lost or changed by
     * storage. Status of a pass: VERIFIED WITHIN BOUNDS (and, with a stride, within the sample).
     */
    public function test_every_bounded_c2_history_survives_storage_and_reload_unchanged(): void
    {
        $repo = $this->repo();
        $tenant = $this->tenant();
        $n = 0;
        $checked = 0;
        foreach (C2Histories::accepted(5, 4) as [$log, $ids, $original]) {
            if ($log === [] || $n++ % $this->reloadDifferentialStride() !== 0) {
                continue;
            }
            $memberId = MemberId::fromString("D$n");
            $repo->create($tenant, $memberId, PersonRef::fromString("P-D$n"));
            $replayedIds = [];
            foreach ($log as $k => $f) {
                $view = MemberHistory::start();
                $view->member = $repo->load($tenant, $memberId);
                [$id, $decision] = C2Histories::decide($view, $f, $replayedIds, $k);
                $this->assertTrue($decision->isAccepted(), "D$n step $k: " . ($decision->refusal()?->value ?? ''));
                $this->assertSame(AppendOutcome::Appended, $repo->append($tenant, $memberId, $decision, self::key("D$n-$k"), "fp-D$n-$k")->outcome());
                $replayedIds[] = $id;
            }

            $loaded = $repo->load($tenant, $memberId);
            $this->assertEquals(
                array_map(static fn ($r) => [$r->seq(), $r->fact()], $original->member->log()),
                array_map(static fn ($r) => [$r->seq(), $r->fact()], $loaded->log()),
                "D$n: facts and seq after reload ($ids[0]…)",
            );
            $this->assertEquals($original->member->resolve(), $loaded->resolve(), "D$n: resolve() after reload");
            $checked++;
        }
        $this->assertGreaterThan(0, $checked);
    }

    public function test_appending_never_changes_earlier_records(): void
    {
        // R-1a-4
        $repo = $this->repo();
        $member = $this->created($repo);
        $repo->append($this->tenant(), $member->id(), self::admit($member, 'A1', 1), self::key('k1'), 'fp1');
        $before = $repo->load($this->tenant(), $member->id())->log();
        $member = $repo->load($this->tenant(), $member->id());
        $end = $member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), TimePoint::of(5), EndKind::fromString('resignation'), Provenance::decision('registrar'));
        $repo->append($this->tenant(), $member->id(), $end, self::key('k2'), 'fp2');

        $after = $repo->load($this->tenant(), $member->id())->log();
        $this->assertEquals($before[0], $after[0]);
    }

    // R-1a-6 (Option E): a finite valid time outside 0001-01-01 … 9999-12-31 is not storable.
    // Boundaries are literal ordinals on purpose: the contract is checked independently of StorableValidTime.

    private static function admitUntil(Member $member, string $factId, int $at, ?int $until): Decision
    {
        return $member->decideAdmit(FactId::fromString($factId), TimePoint::of($at), TimePoint::of($at), $until === null ? TimePoint::infinity() : TimePoint::of($until), Provenance::decision('registrar', null, 'decl-fixture'));
    }

    private static function endAt(Member $member, string $factId, string $period, int $at): Decision
    {
        return $member->decideEnd(FactId::fromString($factId), PeriodId::fromString($period), TimePoint::of($at), TimePoint::of($at), EndKind::fromString('resignation'), Provenance::decision('registrar'));
    }

    /** @return array<string, array{int, ?int}> effectiveAt, finite initialTermUntil (null = unlimited) */
    public static function unstorableAdmissions(): array
    {
        return [
            'E1a effectiveAt one day before 0001-01-01' => [-719_163, null],
            'E1b effectiveAt one day after 9999-12-31' => [2_932_897, null],
            'E1c initial term until one day after 9999-12-31' => [0, 2_932_897],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('unstorableAdmissions')]
    public function test_an_admission_with_an_unstorable_valid_time_is_not_appended(int $at, ?int $until): void
    {
        // E1a–c
        $repo = $this->repo();
        $member = $this->created($repo);

        $result = $repo->append($this->tenant(), $member->id(), self::admitUntil($member, 'A1', $at, $until), self::key('k1'), 'fp1');

        $this->assertSame('valid_time_not_storable', $result->outcome()->value);
        $this->assertSame(0, $repo->load($this->tenant(), $member->id())->version());
        $this->assertSame([], $repo->load($this->tenant(), $member->id())->log());
        $this->assertNull($repo->appliedCommand($this->tenant(), self::key('k1')));
    }

    public function test_an_end_with_an_unstorable_valid_time_is_not_appended(): void
    {
        // E1d
        $repo = $this->repo();
        $member = $this->created($repo);
        $repo->append($this->tenant(), $member->id(), self::admitUntil($member, 'A1', 0, null), self::key('k1'), 'fp1');
        $member = $repo->load($this->tenant(), $member->id());

        $result = $repo->append($this->tenant(), $member->id(), self::endAt($member, 'E1', 'A1', 2_932_897), self::key('k2'), 'fp2');

        $this->assertSame('valid_time_not_storable', $result->outcome()->value);
        $this->assertSame(1, $repo->load($this->tenant(), $member->id())->version());
        $this->assertCount(1, $repo->load($this->tenant(), $member->id())->log());
        $this->assertNull($repo->appliedCommand($this->tenant(), self::key('k2')));
    }

    /** @return array<string, array{int, ?int}> */
    public static function storableBoundaryAdmissions(): array
    {
        return [
            'E2a effectiveAt 0001-01-01' => [-719_162, null],
            'E2b effectiveAt 9999-12-31' => [2_932_896, null],
            'E2c initial term until 9999-12-31' => [0, 2_932_896],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('storableBoundaryAdmissions')]
    public function test_an_admission_at_a_storable_boundary_is_appended_and_read_back(int $at, ?int $until): void
    {
        // E2a–c
        $repo = $this->repo();
        $member = $this->created($repo);

        $result = $repo->append($this->tenant(), $member->id(), self::admitUntil($member, 'A1', $at, $until), self::key('k1'), 'fp1');

        $this->assertSame(AppendOutcome::Appended, $result->outcome());
        $fact = $repo->load($this->tenant(), $member->id())->log()[0]->fact();
        $this->assertSame($at, $fact->effectiveAt()->ordinal());
        $this->assertSame($until, $fact->initialTermUntil()->isInfinite() ? null : $fact->initialTermUntil()->ordinal());
    }

    /** @return array<string, array{int}> */
    public static function unstorableCorrections(): array
    {
        return [
            'V-13a corrected to one day before 0001-01-01' => [-719_163],
            'V-13b corrected to one day after 9999-12-31' => [2_932_897],
        ];
    }

    // COR-4 step 2 (PBDIGIT-329, Q-7, V-13): a Correct whose value cannot be stored is not appended (R-1a-6).
    #[\PHPUnit\Framework\Attributes\DataProvider('unstorableCorrections')]
    public function test_a_correction_with_an_unstorable_value_is_not_appended(int $value): void
    {
        $repo = $this->repo();
        $member = $this->created($repo);
        $repo->append($this->tenant(), $member->id(), self::admitUntil($member, 'A1', 0, null), self::key('k1'), 'fp1');
        $member = $repo->load($this->tenant(), $member->id());

        $correct = $member->decideCorrect(FactId::fromString('C1'), FactId::fromString('A1'), TimePoint::of($value), TimePoint::of(max($value, 0)), Provenance::decision('registrar'));
        $this->assertTrue($correct->isAccepted(), 'the kernel accepts it; only storage cannot hold it');

        $result = $repo->append($this->tenant(), $member->id(), $correct, self::key('k2'), 'fp2');

        $this->assertSame('valid_time_not_storable', $result->outcome()->value);
        $this->assertSame(1, $repo->load($this->tenant(), $member->id())->version());
        $this->assertNull($repo->appliedCommand($this->tenant(), self::key('k2')));
    }

    public function test_an_end_at_the_last_storable_date_is_appended_and_read_back(): void
    {
        // E2d
        $repo = $this->repo();
        $member = $this->created($repo);
        $repo->append($this->tenant(), $member->id(), self::admitUntil($member, 'A1', 0, null), self::key('k1'), 'fp1');
        $member = $repo->load($this->tenant(), $member->id());

        $result = $repo->append($this->tenant(), $member->id(), self::endAt($member, 'E1', 'A1', 2_932_896), self::key('k2'), 'fp2');

        $this->assertSame(AppendOutcome::Appended, $result->outcome());
        $this->assertSame(2_932_896, $repo->load($this->tenant(), $member->id())->log()[1]->fact()->effectiveAt()->ordinal());
    }

    public function test_a_reused_key_is_answered_before_the_valid_time_is_checked(): void
    {
        // E3: IDEM-2 precedes R-1a-6 (Slice 1a contract §7, F-3 clause).
        $repo = $this->repo();
        $member = $this->created($repo);
        $repo->append($this->tenant(), $member->id(), self::admitUntil($member, 'A1', 1, null), self::key('k1'), 'fp1');
        $member = $repo->load($this->tenant(), $member->id());

        $result = $repo->append($this->tenant(), $member->id(), self::endAt($member, 'E1', 'A1', 2_932_897), self::key('k1'), 'fp-other');

        $this->assertSame(AppendOutcome::IdempotencyKeyConflict, $result->outcome());
        $this->assertSame(1, $repo->load($this->tenant(), $member->id())->version());
    }

    public function test_a_known_key_with_a_matching_fingerprint_is_answered_without_examining_the_new_decision(): void
    {
        // E4: IDEM-1 / IDEM-3 precede R-1a-6. At the port the fingerprint is opaque (§3: "key seen with the
        // same fingerprint"); the new decision deliberately differs, so this is not a genuinely identical
        // command. It shows that the new decision's valid time is never examined. The genuine replay of a
        // legacy record is E4-L (InMemoryMemberRepositoryTest; PostgreSQL variant in Part 2).
        $repo = $this->repo();
        $member = $this->created($repo);
        $first = $repo->append($this->tenant(), $member->id(), self::admitUntil($member, 'A1', 1, null), self::key('k1'), 'fp1');
        $member = $repo->load($this->tenant(), $member->id());

        $retry = $repo->append($this->tenant(), $member->id(), self::endAt($member, 'E1', 'A1', 2_932_897), self::key('k1'), 'fp1');

        $this->assertSame(AppendOutcome::AlreadyApplied, $retry->outcome());
        $this->assertEquals($first->appended(), $retry->appended());
        $this->assertSame(1, $repo->load($this->tenant(), $member->id())->version());
    }

    public function test_an_unstorable_valid_time_takes_precedence_over_a_stale_version(): void
    {
        // E5: R-1a-6 is checked before the version comparison.
        $repo = $this->repo();
        $member = $this->created($repo);
        $stale = self::admitUntil($member, 'A1', 2_932_897, null);
        $repo->append($this->tenant(), $member->id(), self::admitUntil($member, 'A0', 0, null), self::key('k0'), 'fp0');

        $result = $repo->append($this->tenant(), $member->id(), $stale, self::key('k1'), 'fp1');

        $this->assertSame('valid_time_not_storable', $result->outcome()->value);
        $this->assertSame(1, $repo->load($this->tenant(), $member->id())->version());
    }

    // PBDIGIT-264 F-3 (PO-adopted 2026-10-10, F3-1; refined by K-1): the key lookup precedes every other outcome-producing check, after the accepted-decision precondition (F3d);
    // a MemberId collision with another holder is an infrastructure failure with no state change.

    public function test_creating_a_member_id_that_belongs_to_another_holder_fails_without_changing_state(): void
    {
        // F3a
        $repo = $this->repo();
        $other = $this->created($repo, 'M1', 'P1', 'O2');
        $member = $this->created($repo, 'M1', 'P1', 'O1');
        $repo->append($this->tenant('O1'), $member->id(), self::admitUntil($member, 'A1', 1, null), self::key('k1'), 'fp1');
        $before = $repo->load($this->tenant('O1'), $member->id());

        // Captured, not asserted inside try: PHPUnit's own failure is a RuntimeException too.
        $failure = null;
        try {
            $repo->create($this->tenant('O1'), MemberId::fromString('M1'), PersonRef::fromString('P2'));
        } catch (\RuntimeException $e) {
            $failure = $e;   // F-3 (a): a caller defect surfaced as an infrastructure failure, not a port outcome
        }
        $this->assertInstanceOf(\RuntimeException::class, $failure, 'expected an infrastructure failure for a MemberId collision');

        $after = $repo->load($this->tenant('O1'), $member->id());
        $this->assertSame('P1', $after->holder()->value(), 'the holder is unchanged');
        $this->assertSame($before->version(), $after->version());
        $this->assertEquals($before->log(), $after->log());
        $this->assertNull($repo->findByHolder($this->tenant('O1'), PersonRef::fromString('P2')), 'the second holder is not mapped');
        $this->assertSame('M1', $repo->findByHolder($this->tenant('O1'), PersonRef::fromString('P1'))?->id()->value());
        $this->assertEquals($other, $repo->load($this->tenant('O2'), MemberId::fromString('M1')), 'the other tenant is unchanged');
    }

    public function test_a_known_key_is_answered_before_the_member_is_looked_up(): void
    {
        // F3b-1: an unknown MemberId with a used key and the same fingerprint gets the original outcome.
        $repo = $this->repo();
        $member = $this->created($repo);
        $first = $repo->append($this->tenant(), $member->id(), self::admitUntil($member, 'A1', 1, null), self::key('k1'), 'fp1');
        $member = $repo->load($this->tenant(), $member->id());

        $result = $repo->append($this->tenant(), MemberId::fromString('M9'), self::endAt($member, 'E1', 'A1', 5), self::key('k1'), 'fp1');

        $this->assertSame(AppendOutcome::AlreadyApplied, $result->outcome());
        $this->assertEquals($first->appended(), $result->appended());
        $this->assertCount(1, $repo->load($this->tenant(), $member->id())->log());
        $this->assertNull($repo->load($this->tenant(), MemberId::fromString('M9')));
    }

    public function test_a_reused_key_conflict_is_answered_before_the_member_is_looked_up(): void
    {
        // F3b-2: an unknown MemberId with a used key and another fingerprint is a key conflict.
        $repo = $this->repo();
        $member = $this->created($repo);
        $repo->append($this->tenant(), $member->id(), self::admitUntil($member, 'A1', 1, null), self::key('k1'), 'fp1');
        $member = $repo->load($this->tenant(), $member->id());

        $result = $repo->append($this->tenant(), MemberId::fromString('M9'), self::endAt($member, 'E1', 'A1', 5), self::key('k1'), 'fp-other');

        $this->assertSame(AppendOutcome::IdempotencyKeyConflict, $result->outcome());
        $this->assertSame(1, $repo->load($this->tenant(), $member->id())->version());
        $this->assertNull($repo->load($this->tenant(), MemberId::fromString('M9')));
    }

    public function test_an_unstorable_valid_time_is_answered_before_the_member_is_looked_up(): void
    {
        // F3c (R-1a-6 universal after F-3, F3-3): unknown MemberId, unused key, unstorable valid time.
        $repo = $this->repo();
        $ghost = Member::fromLog(MemberId::fromString('M9'), PersonRef::fromString('P9'), $this->tenant());

        $result = $repo->append($this->tenant(), $ghost->id(), self::admitUntil($ghost, 'A1', 2_932_897, null), self::key('k9'), 'fp9');

        $this->assertSame('valid_time_not_storable', $result->outcome()->value);
        $this->assertNull($repo->load($this->tenant(), MemberId::fromString('M9')));
        $this->assertNull($repo->appliedCommand($this->tenant(), self::key('k9')));
    }

    public function test_a_refused_decision_is_rejected_even_under_a_used_key(): void
    {
        // F3d (K-1): the accepted-decision precondition is call validation and precedes the key lookup.
        $repo = $this->repo();
        $member = $this->created($repo);
        $first = $repo->append($this->tenant(), $member->id(), self::admitUntil($member, 'A1', 1, null), self::key('k1'), 'fp1');

        foreach (['fp1', 'fp-other'] as $fingerprint) {
            $failure = null;
            try {
                $repo->append($this->tenant(), $member->id(), Decision::refused(RefusalReason::DuplicateFactId), self::key('k1'), $fingerprint);
            } catch (\InvalidArgumentException $e) {
                $failure = $e;
            }
            $this->assertInstanceOf(\InvalidArgumentException::class, $failure, "refused decision under a used key ($fingerprint)");
        }
        $this->assertSame(1, $repo->load($this->tenant(), $member->id())->version());
        $this->assertCount(1, $repo->load($this->tenant(), $member->id())->log());
        $applied = $repo->appliedCommand($this->tenant(), self::key('k1'));
        $this->assertEquals([$first->appended(), 'fp1'], [$applied->appended(), $applied->fingerprint()]);
    }
}
