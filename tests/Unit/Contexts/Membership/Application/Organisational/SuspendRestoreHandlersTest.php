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
use App\Contexts\Membership\Application\Organisational\RestoreMembership;
use App\Contexts\Membership\Application\Organisational\RestoreMembershipHandler;
use App\Contexts\Membership\Application\Organisational\SuspendMembership;
use App\Contexts\Membership\Application\Organisational\SuspendMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\Restore;
use App\Contexts\Membership\Domain\Organisational\Suspend;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;

/**
 * PBDIGIT-354 S2-2: the SuspendMembership and RestoreMembership use cases (D-163, D-177 §4, §8; pull shape: an office
 * holder's act). Authority is asked through AuthorityCheckPort for `suspend` / `restore` (Governance's
 * `suspend-membership` / `restore-membership`) on every attempt; never taken from the caller.
 */
final class SuspendRestoreHandlersTest extends TestCase
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
                $evidence = AuthorityEvidence::of($actor, 'test-role', 'test:suspend-authority', 'v1', $this->grant, Instant::of(count($this->asked)), false);

                return $this->grant ? AuthorityDecision::granted($evidence) : AuthorityDecision::denied($evidence);
            }
        };
        // admitted at 1 with the term [1, 100)
        (new AdmitMemberHandler($this->repo, $this->authority))->handle(
            new AdmitMember('O1', 'P1', 'M-P1', 'A1', 1, 1, 100, false, 'registrar-1', 'decl-fixture', null, 'k-admit'),
        );
        $this->authority->asked = [];
    }

    private function suspend(string $key, string $factId, int $at, ?int $until = null, string $member = 'M-P1', string $tenant = 'O1', ?int $decisionDate = null)
    {
        return (new SuspendMembershipHandler($this->repo, $this->authority))->handle(
            new SuspendMembership($tenant, $member, $factId, $at, $decisionDate ?? $at, $until, 'board-secretary', null, $key),
        );
    }

    private function restore(string $key, string $factId, string $suspension, int $at, string $member = 'M-P1', string $tenant = 'O1')
    {
        return (new RestoreMembershipHandler($this->repo, $this->authority))->handle(
            new RestoreMembership($tenant, $member, $factId, $suspension, $at, $at, 'board-secretary', null, $key),
        );
    }

    private function member(string $tenant = 'O1')
    {
        return $this->repo->load(TenantId::fromString($tenant), MemberId::fromString('M-P1'));
    }

    public function test_a_suspension_is_recorded_and_restricts_without_ending_membership(): void
    {
        $outcome = $this->suspend('k1', 'S1', 10, 20);

        $this->assertTrue($outcome->isAppended());
        $fact = $this->member()->log()[1]->fact();
        $this->assertInstanceOf(Suspend::class, $fact);
        $this->assertTrue(PeriodId::fromString('A1')->equals($fact->period()), 'the period is derived, not supplied');
        $this->assertTrue($this->member()->resolve()->isSuspended(TimePoint::of(15)));
        $this->assertTrue($this->member()->resolve()->isMember(TimePoint::of(15)), 'still a member');
    }

    public function test_a_suspension_without_an_end_date_runs_until_restored(): void
    {
        $this->assertTrue($this->suspend('k1', 'S1', 10)->isAppended());
        $this->assertTrue($this->member()->log()[1]->fact()->until()->isInfinite(), 'D-177 §4: `until` is optional');
        $this->assertTrue($this->member()->resolve()->isSuspended(TimePoint::of(99)));
    }

    public function test_a_restore_ends_the_named_suspension(): void
    {
        $this->suspend('k1', 'S1', 10);

        $outcome = $this->restore('k2', 'R1', 'S1', 15);

        $this->assertTrue($outcome->isAppended());
        $fact = $this->member()->log()[2]->fact();
        $this->assertInstanceOf(Restore::class, $fact);
        $this->assertTrue(FactId::fromString('S1')->equals($fact->suspension()));
        $this->assertFalse($this->member()->resolve()->isSuspended(TimePoint::of(16)));
        $this->assertTrue($this->member()->resolve()->isSuspended(TimePoint::of(14)), 'the restriction up to the restore stands');
    }

    public function test_authority_is_asked_for_each_act_and_its_evidence_is_recorded(): void
    {
        $this->assertTrue($this->suspend('k1', 'S1', 10)->isAppended());
        $this->assertTrue($this->restore('k2', 'R1', 'S1', 15)->isAppended());

        $this->assertSame([['O1', 'board-secretary', MembershipAct::Suspend], ['O1', 'board-secretary', MembershipAct::Restore]], $this->authority->asked);
        foreach ([1, 2] as $seq) {
            $provenance = $this->member()->log()[$seq]->fact()->provenance();
            $this->assertSame('board-secretary', $provenance->actorOrSource());
            $this->assertSame('test:suspend-authority', $provenance->authorityEvidence()->ruleRef());
        }
    }

    public function test_without_authority_nothing_is_recorded(): void
    {
        $this->authority->grant = false;

        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->suspend('k1', 'S1', 10)->applicationRefusal());
        $this->assertSame(1, $this->member()->version());

        $this->authority->grant = true;
        $this->suspend('k2', 'S1', 10);
        $this->authority->grant = false;
        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->restore('k3', 'R1', 'S1', 15)->applicationRefusal());
        $this->assertSame(2, $this->member()->version());
    }

    public function test_domain_refusals_are_passed_through_and_nothing_is_recorded(): void
    {
        $this->assertSame(RefusalReason::NotInForce, $this->suspend('k1', 'S1', 150)->domainRefusal(), 'lapsed from 100');
        $this->assertSame(RefusalReason::EmptyRestriction, $this->suspend('k2', 'S2', 10, 10)->domainRefusal());
        $this->assertSame(RefusalReason::EffectiveDateAfterDecisionDate, $this->suspend('k3', 'S3', 10, null, 'M-P1', 'O1', 9)->domainRefusal());
        $this->suspend('k4', 'S4', 10, 20);
        $this->assertSame(RefusalReason::OutsideRestriction, $this->restore('k5', 'R1', 'S4', 25)->domainRefusal());
        $this->assertSame(RefusalReason::TargetNotFound, $this->restore('k6', 'R2', 'NOPE', 15)->domainRefusal());
        $this->restore('k7', 'R3', 'S4', 15);
        $this->assertSame(RefusalReason::AlreadyRestored, $this->restore('k8', 'R4', 'S4', 16)->domainRefusal());
        $this->assertSame(3, $this->member()->version(), 'admission, one suspension, one restore');
    }

    public function test_an_unknown_member_or_another_organisations_member_is_not_found(): void
    {
        $this->assertSame(ApplicationRefusal::MemberNotFound, $this->suspend('k1', 'S1', 10, null, 'M-nobody')->applicationRefusal());
        $this->assertSame(ApplicationRefusal::MemberNotFound, $this->suspend('k2', 'S1', 10, null, 'M-P1', 'O2')->applicationRefusal());
        $this->assertSame(ApplicationRefusal::MemberNotFound, $this->restore('k3', 'R1', 'S1', 15, 'M-P1', 'O2')->applicationRefusal());
        $this->assertSame(1, $this->member()->version());
    }

    public function test_the_same_command_twice_records_once_and_a_reused_key_is_refused(): void
    {
        $first = $this->suspend('k1', 'S1', 10);
        $second = $this->suspend('k1', 'S1', 10);

        $this->assertSame($first->appended()->seq(), $second->appended()->seq());
        $this->assertSame(ApplicationRefusal::IdempotencyKeyConflict, $this->suspend('k1', 'S1', 11)->applicationRefusal());
        $this->assertSame(ApplicationRefusal::IdempotencyKeyConflict, $this->restore('k1', 'R1', 'S1', 15)->applicationRefusal(), 'the key is per organisation, across acts');
        $this->assertSame(2, $this->member()->version());
    }

    public function test_both_facts_are_storable_iff_their_dates_are(): void
    {
        $suspend = static fn (int $at, ?int $until): Suspend => new Suspend(FactId::fromString('S'), PeriodId::fromString('A'), TimePoint::of($at),
            $until === null ? TimePoint::infinity() : TimePoint::of($until), Provenance::decision('x'));
        $restore = static fn (int $at): Restore => new Restore(FactId::fromString('R'), FactId::fromString('S'), TimePoint::of($at), Provenance::decision('x'));

        $this->assertTrue(StorableValidTime::admits($suspend(8, 20)));
        $this->assertTrue(StorableValidTime::admits($suspend(8, null)));
        $this->assertFalse(StorableValidTime::admits($suspend(StorableValidTime::FIRST - 1, 20)));
        $this->assertFalse(StorableValidTime::admits($suspend(8, StorableValidTime::LAST + 1)));
        $this->assertTrue(StorableValidTime::admits($restore(8)));
        $this->assertFalse(StorableValidTime::admits($restore(StorableValidTime::LAST + 1)));
    }
}
