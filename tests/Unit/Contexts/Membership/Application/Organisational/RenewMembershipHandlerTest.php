<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\ApplicationRefusal;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Application\Organisational\Ports\StorableValidTime;
use App\Contexts\Membership\Application\Organisational\RenewMembership;
use App\Contexts\Membership\Application\Organisational\RenewMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\Renew;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;

/**
 * PBDIGIT-354 S2-1: the RenewMembership use case (D-163, D-177 §4, §8) and the release-1 admission-term rule
 * (D-177 §3) at the command boundary. Authority is asked through AuthorityCheckPort for the act `renew`
 * (Governance's `renew-membership`), never taken from the caller.
 */
final class RenewMembershipHandlerTest extends TestCase
{
    private InMemoryMemberRepository $repo;
    /** @var object{asked: list<array{string, string, MembershipAct}>, grant: bool}&AuthorityCheckPort */
    private AuthorityCheckPort $authority;

    protected function setUp(): void
    {
        $clock = 100;
        $this->repo = new InMemoryMemberRepository(static function () use (&$clock): int {
            return $clock++;
        });
        $this->authority = new class implements AuthorityCheckPort {
            /** @var list<array{string, string, MembershipAct}> */
            public array $asked = [];
            public bool $grant = true;

            public function check(TenantId $tenant, string $actor, MembershipAct $act): AuthorityDecision
            {
                $this->asked[] = [$tenant->value(), $actor, $act];
                $evidence = AuthorityEvidence::of($actor, 'test-role', 'test:renew-authority', 'v1', $this->grant, Instant::of(count($this->asked)), false);

                return $this->grant ? AuthorityDecision::granted($evidence) : AuthorityDecision::denied($evidence);
            }
        };
        // admitted at 1 with the term [1, 10)
        (new AdmitMemberHandler($this->repo, $this->authority))->handle(
            new AdmitMember('O1', 'P1', 'M-P1', 'A1', 1, 1, 10, false, 'registrar-1', 'decl-fixture', null, 'k-admit'),
        );
        $this->authority->asked = [];
    }

    private function handler(): RenewMembershipHandler
    {
        return new RenewMembershipHandler($this->repo, $this->authority);
    }

    private static function renew(string $key, string $factId, int $at, ?int $until, bool $unlimited = false, string $member = 'M-P1', string $tenant = 'O1', ?int $decisionDate = null): RenewMembership
    {
        return new RenewMembership($tenant, $member, $factId, $at, $decisionDate ?? $at, $until, $unlimited, 'treasurer-1', null, $key);
    }

    private function member(string $tenant = 'O1')
    {
        return $this->repo->load(TenantId::fromString($tenant), MemberId::fromString('M-P1'));
    }

    public function test_a_renewal_is_recorded_and_extends_the_time_in_force(): void
    {
        $outcome = $this->handler()->handle(self::renew('k1', 'R1', 8, 20));

        $this->assertTrue($outcome->isAppended());
        $this->assertSame(2, $outcome->appended()->seq());
        $fact = $this->member()->log()[1]->fact();
        $this->assertInstanceOf(Renew::class, $fact);
        $this->assertTrue(PeriodId::fromString('A1')->equals($fact->period()), 'the period is derived, not supplied');
        $this->assertTrue($this->member()->resolve()->isMember(TimePoint::of(15)));
        $this->assertFalse($this->member()->resolve()->isMember(TimePoint::of(20)));
    }

    public function test_an_open_ended_renewal(): void
    {
        $this->assertTrue($this->handler()->handle(self::renew('k1', 'R1', 8, null, true))->isAppended());
        $this->assertTrue($this->member()->log()[1]->fact()->until()->isInfinite());
    }

    public function test_a_renewal_must_state_its_term(): void
    {
        $outcome = $this->handler()->handle(self::renew('k1', 'R1', 8, null, false));

        $this->assertSame(RefusalReason::RenewalTermRequired, $outcome->domainRefusal(), 'neither an end date nor open-ended: never read as unlimited');
        $this->assertSame(1, $this->member()->version());
    }

    public function test_authority_is_asked_for_the_renew_act_and_its_evidence_is_recorded(): void
    {
        $this->handler()->handle(self::renew('k1', 'R1', 8, 20));

        $this->assertSame([['O1', 'treasurer-1', MembershipAct::Renew]], $this->authority->asked);
        $provenance = $this->member()->log()[1]->fact()->provenance();
        $this->assertSame('treasurer-1', $provenance->actorOrSource());
        $this->assertSame('test:renew-authority', $provenance->authorityEvidence()->ruleRef());
    }

    public function test_without_authority_nothing_is_recorded(): void
    {
        $this->authority->grant = false;

        $outcome = $this->handler()->handle(self::renew('k1', 'R1', 8, 20));

        $this->assertSame(ApplicationRefusal::NotAuthorised, $outcome->applicationRefusal());
        $this->assertSame(1, $this->member()->version());
    }

    public function test_a_domain_refusal_is_passed_through(): void
    {
        $this->assertSame(RefusalReason::NoPeriodAt, $this->handler()->handle(self::renew('k1', 'R1', 0, 20))->domainRefusal());
        $this->assertSame(RefusalReason::EffectiveDateAfterDecisionDate, $this->handler()->handle(self::renew('k2', 'R2', 9, 20, false, 'M-P1', 'O1', 8))->domainRefusal());
        $this->assertSame(1, $this->member()->version());
    }

    public function test_an_unknown_member_is_refused(): void
    {
        $this->assertSame(ApplicationRefusal::MemberNotFound, $this->handler()->handle(self::renew('k1', 'R1', 8, 20, false, 'M-nobody'))->applicationRefusal());
    }

    public function test_a_member_of_another_organisation_is_not_found(): void
    {
        $this->assertSame(ApplicationRefusal::MemberNotFound, $this->handler()->handle(self::renew('k1', 'R1', 8, 20, false, 'M-P1', 'O2'))->applicationRefusal());
        $this->assertSame(1, $this->member()->version());
    }

    public function test_the_same_command_twice_records_once(): void
    {
        $first = $this->handler()->handle(self::renew('k1', 'R1', 8, 20));
        $second = $this->handler()->handle(self::renew('k1', 'R1', 8, 20));

        $this->assertSame($first->appended()->seq(), $second->appended()->seq());
        $this->assertSame(2, $this->member()->version());
    }

    public function test_the_same_key_with_a_different_command_is_refused(): void
    {
        $this->handler()->handle(self::renew('k1', 'R1', 8, 20));

        $this->assertSame(ApplicationRefusal::IdempotencyKeyConflict, $this->handler()->handle(self::renew('k1', 'R1', 8, 21))->applicationRefusal());
    }

    public function test_d177_3_a_release_1_admission_must_state_its_term(): void
    {
        $handler = new AdmitMemberHandler($this->repo, $this->authority);

        $outcome = $handler->handle(new AdmitMember('O1', 'P2', 'M-P2', 'A1', 1, 1, null, false, 'registrar-1', 'decl-fixture', null, 'k2'));

        $this->assertSame(RefusalReason::AdmissionTermRequired, $outcome->domainRefusal());
        $this->assertNull($this->repo->findByHolder(TenantId::fromString('O1'), PersonRef::fromString('P2')), 'nothing is created, not even an empty Member record');
        $this->assertTrue($handler->handle(new AdmitMember('O1', 'P2', 'M-P2', 'A1', 1, 1, null, true, 'registrar-1', 'decl-fixture', null, 'k3'))->isAppended(), 'open-ended is a stated term');
        $this->assertTrue($handler->handle(new AdmitMember('O1', 'P3', 'M-P3', 'A1', 1, 1, 9, false, 'registrar-1', 'decl-fixture', null, 'k4'))->isAppended(), 'a finite term');
    }

    public function test_a_renewal_is_storable_iff_both_its_dates_are(): void
    {
        $renew = static fn (int $at, ?int $until): Renew => new Renew(FactId::fromString('R'), PeriodId::fromString('A'), TimePoint::of($at),
            $until === null ? TimePoint::infinity() : TimePoint::of($until), Provenance::decision('x'));

        $this->assertTrue(StorableValidTime::admits($renew(8, 20)));
        $this->assertTrue(StorableValidTime::admits($renew(8, null)), '∞ is stored as open-ended');
        $this->assertFalse(StorableValidTime::admits($renew(StorableValidTime::FIRST - 1, 20)));
        $this->assertFalse(StorableValidTime::admits($renew(8, StorableValidTime::LAST + 1)));
    }
}
