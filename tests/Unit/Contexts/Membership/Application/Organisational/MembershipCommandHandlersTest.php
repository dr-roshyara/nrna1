<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\ApplicationRefusal;
use App\Contexts\Membership\Application\Organisational\EndMembership;
use App\Contexts\Membership\Application\Organisational\EndMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * Slice 1a contract §1–§6: the Admit / End application handlers. PBDIGIT-259.
 */
final class MembershipCommandHandlersTest extends TestCase
{
    private InMemoryMemberRepository $repo;
    private FakeAuthority $authority;

    protected function setUp(): void
    {
        $this->repo = new InMemoryMemberRepository(static fn (): int => 100);
        $this->authority = new FakeAuthority();
    }

    private function admitHandler(): AdmitMemberHandler
    {
        return new AdmitMemberHandler($this->repo, $this->authority);
    }

    private function endHandler(): EndMembershipHandler
    {
        return new EndMembershipHandler($this->repo, $this->authority);
    }

    private static function admit(string $key, string $factId, int $at, string $person = 'P1', string $tenant = 'O1'): AdmitMember
    {
        return new AdmitMember($tenant, $person, 'M-' . $person, $factId, $at, null, true, 'registrar-1', null, null, $key);
    }

    private static function end(string $key, string $factId, string $period, int $at, string $member = 'M-P1', string $tenant = 'O1'): EndMembership
    {
        return new EndMembership($tenant, $member, $factId, $period, $at, 'resignation', 'registrar-1', null, $key);
    }

    private function member(string $id = 'M-P1', string $tenant = 'O1')
    {
        return $this->repo->load(TenantId::fromString($tenant), MemberId::fromString($id));
    }

    public function test_admitting_a_new_person_creates_the_member_and_records_the_admission(): void
    {
        $outcome = $this->admitHandler()->handle(self::admit('k1', 'A1', 1));

        $this->assertTrue($outcome->isAppended());
        $this->assertSame(1, $outcome->appended()->seq());
        $this->assertSame('M-P1', $outcome->appended()->memberId()->value());
        $this->assertTrue($this->member()->resolve()->isMember(TimePoint::of(3)));
    }

    public function test_re_entry_is_recorded_on_the_same_member(): void
    {
        $this->admitHandler()->handle(self::admit('k1', 'A1', 1));
        $this->endHandler()->handle(self::end('k2', 'E1', 'A1', 5));

        $outcome = $this->admitHandler()->handle(new AdmitMember('O1', 'P1', 'M-other', 'A2', 7, null, true, 'registrar-1', null, null, 'k3'));

        $this->assertSame('M-P1', $outcome->appended()->memberId()->value(), 'the existing Member is used; the suggested new id is ignored');
        $this->assertSame(3, $outcome->appended()->seq());
        $this->assertCount(2, $this->member()->resolve()->periods());
    }

    public function test_a_domain_refusal_is_passed_through_and_nothing_is_recorded(): void
    {
        $this->admitHandler()->handle(self::admit('k1', 'A1', 1));

        $outcome = $this->admitHandler()->handle(self::admit('k2', 'A2', 3));

        $this->assertSame(RefusalReason::OverlappingMembershipPeriods, $outcome->domainRefusal());
        $this->assertSame(1, $this->member()->version());
    }

    public function test_an_unauthorised_admission_creates_nothing(): void
    {
        $this->authority = new FakeAuthority(0);

        $outcome = $this->admitHandler()->handle(self::admit('k1', 'A1', 1));

        $this->assertSame(ApplicationRefusal::NotAuthorised, $outcome->applicationRefusal());
        $this->assertNull($this->repo->findByHolder(TenantId::fromString('O1'), PersonRef::fromString('P1')));
    }

    public function test_the_recorded_fact_carries_the_authority_evidence(): void
    {
        $this->admitHandler()->handle(self::admit('k1', 'A1', 1));

        $provenance = $this->member()->log()[0]->fact()->provenance();
        $this->assertSame('registrar-1', $provenance->actorOrSource());
        $this->assertSame('test:fake-authority', $provenance->authorityEvidence()->ruleRef());
    }

    public function test_ending_a_membership(): void
    {
        $this->admitHandler()->handle(self::admit('k1', 'A1', 1));

        $outcome = $this->endHandler()->handle(self::end('k2', 'E1', 'A1', 5));

        $this->assertTrue($outcome->isAppended());
        $this->assertFalse($this->member()->resolve()->isMember(TimePoint::of(6)));
    }

    public function test_ending_an_unknown_member_is_refused(): void
    {
        $this->assertSame(ApplicationRefusal::MemberNotFound, $this->endHandler()->handle(self::end('k1', 'E1', 'A1', 5, 'nobody'))->applicationRefusal());
    }

    public function test_an_end_from_another_tenant_does_not_reach_the_member(): void
    {
        $this->admitHandler()->handle(self::admit('k1', 'A1', 1));

        $outcome = $this->endHandler()->handle(self::end('k2', 'E1', 'A1', 5, 'M-P1', 'O2'));

        $this->assertSame(ApplicationRefusal::MemberNotFound, $outcome->applicationRefusal());
        $this->assertSame(1, $this->member()->version());
    }

    public function test_a_retried_command_returns_the_original_outcome_without_deciding_again(): void
    {
        // IDEM-1: checked before decide, so the retry is not mistaken for a DuplicateFactId (INV-FID).
        $first = $this->admitHandler()->handle(self::admit('k1', 'A1', 1));
        $callsAfterFirst = $this->authority->calls;

        $retry = $this->admitHandler()->handle(self::admit('k1', 'A1', 1));

        $this->assertEquals($first->appended(), $retry->appended());
        $this->assertSame($callsAfterFirst, $this->authority->calls, 'no second decision');
        $this->assertSame(1, $this->member()->version());
    }

    public function test_the_same_fact_id_under_a_new_key_is_a_duplicate_fact_id(): void
    {
        // INV-FID: a genuine duplicate, not a retry.
        $this->admitHandler()->handle(self::admit('k1', 'A1', 1));
        $this->endHandler()->handle(self::end('k2', 'E1', 'A1', 5));

        $this->assertSame(RefusalReason::DuplicateFactId, $this->admitHandler()->handle(self::admit('k3', 'A1', 9))->domainRefusal());
    }

    public function test_a_key_reused_for_a_different_command_is_refused(): void
    {
        // IDEM-2
        $this->admitHandler()->handle(self::admit('k1', 'A1', 1));

        $this->assertSame(ApplicationRefusal::IdempotencyKeyConflict, $this->endHandler()->handle(self::end('k1', 'E1', 'A1', 5))->applicationRefusal());
        $this->assertSame(1, $this->member()->version());
    }

    public function test_a_refused_command_may_be_retried_with_the_same_key_once_it_becomes_valid(): void
    {
        // IDEM-4: refusals are not remembered.
        $this->admitHandler()->handle(self::admit('k1', 'A1', 1));
        $this->assertFalse($this->admitHandler()->handle(self::admit('k2', 'A2', 7))->isAppended());
        $this->endHandler()->handle(self::end('k3', 'E1', 'A1', 5));

        $this->assertTrue($this->admitHandler()->handle(self::admit('k2', 'A2', 7))->isAppended());
    }

    public function test_an_unstorable_valid_time_is_an_application_refusal_without_retry(): void
    {
        // E7 (R-1a-6): a technical refusal, not a domain refusal and not a concurrency conflict.
        $outcome = $this->admitHandler()->handle(self::admit('k1', 'A1', 2_932_897));

        $this->assertFalse($outcome->isAppended());
        $this->assertSame('valid_time_not_storable', $outcome->applicationRefusal()?->value);
        $this->assertNull($outcome->domainRefusal());
        $this->assertSame(1, $this->repo->appendCalls, 'not retried');
        $this->assertSame(0, $this->member()->version());
    }

    public function test_a_reused_key_is_refused_before_an_unstorable_valid_time_is_considered(): void
    {
        // E8: the application's IDEM pre-check precedes R-1a-6; append is not reached.
        $this->admitHandler()->handle(self::admit('k1', 'A1', 1));
        $appendCalls = $this->repo->appendCalls;

        $outcome = $this->admitHandler()->handle(self::admit('k1', 'A2', 2_932_897));

        $this->assertSame(ApplicationRefusal::IdempotencyKeyConflict, $outcome->applicationRefusal());
        $this->assertSame($appendCalls, $this->repo->appendCalls);
        $this->assertSame(1, $this->member()->version());
    }
}
