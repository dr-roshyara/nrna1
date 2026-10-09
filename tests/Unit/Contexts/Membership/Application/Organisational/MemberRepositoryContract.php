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
}
