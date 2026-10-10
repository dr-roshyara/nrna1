<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

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
        return $member->decideAdmit(FactId::fromString($factId), TimePoint::of($at), TimePoint::infinity(), Provenance::decision('registrar'));
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
        $end = $member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), EndKind::fromString('resignation'), Provenance::decision('registrar'));
        $repo->append($this->tenant(), $member->id(), $end, self::key('k2'), 'fp2');

        $log = $repo->load($this->tenant(), $member->id())->log();
        $this->assertSame([1, 2], array_map(static fn ($r) => $r->seq(), $log));
        $this->assertSame(['A1', 'E1'], array_map(static fn ($r) => $r->fact()->id()->value(), $log));
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

        $other = $member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), EndKind::fromString('resignation'), Provenance::decision('registrar'));
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
        $end = $member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), EndKind::fromString('resignation'), Provenance::decision('registrar'));
        $second = $repo->append($this->tenant(), $member->id(), $end, self::key('k2'), 'fp2');

        $this->assertGreaterThanOrEqual(500, $second->appended()->recordedAt()->ordinal());
    }

    public function test_appending_never_changes_earlier_records(): void
    {
        // R-1a-4
        $repo = $this->repo();
        $member = $this->created($repo);
        $repo->append($this->tenant(), $member->id(), self::admit($member, 'A1', 1), self::key('k1'), 'fp1');
        $before = $repo->load($this->tenant(), $member->id())->log();
        $member = $repo->load($this->tenant(), $member->id());
        $end = $member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), EndKind::fromString('resignation'), Provenance::decision('registrar'));
        $repo->append($this->tenant(), $member->id(), $end, self::key('k2'), 'fp2');

        $after = $repo->load($this->tenant(), $member->id())->log();
        $this->assertEquals($before[0], $after[0]);
    }

    // R-1a-6 (Option E): a finite valid time outside 0001-01-01 … 9999-12-31 is not storable.
    // Boundaries are literal ordinals on purpose: the contract is checked independently of StorableValidTime.

    private static function admitUntil(Member $member, string $factId, int $at, ?int $until): Decision
    {
        return $member->decideAdmit(FactId::fromString($factId), TimePoint::of($at), $until === null ? TimePoint::infinity() : TimePoint::of($until), Provenance::decision('registrar'));
    }

    private static function endAt(Member $member, string $factId, string $period, int $at): Decision
    {
        return $member->decideEnd(FactId::fromString($factId), PeriodId::fromString($period), TimePoint::of($at), EndKind::fromString('resignation'), Provenance::decision('registrar'));
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

    // PBDIGIT-264 F-3 (PO-adopted 2026-10-10, F3-1): the key lookup precedes every other check;
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
}
