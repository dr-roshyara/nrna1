<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\ApplicationRefusal;
use App\Contexts\Membership\Application\Organisational\EndMembership;
use App\Contexts\Membership\Application\Organisational\EndMembershipHandler;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Application\Organisational\RecordDeath;
use App\Contexts\Membership\Application\Organisational\RecordDeathHandler;
use App\Contexts\Membership\Application\Organisational\ResignMembership;
use App\Contexts\Membership\Application\Organisational\ResignMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\AuthorityBasis;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\AuthorityOutcome;
use App\Contexts\Membership\Domain\Organisational\CompetenceKind;
use App\Contexts\Membership\Domain\Organisational\End;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;

/**
 * PBDIGIT-354 S2-3b: resign now (self-service, or by a recorder) and record a death (D-194, D-201, D-203; accepted
 * D-208 §2 `record-resignation`, `record-death`, C-1a, GC-1 … GC-4; M-TDD-01 §5.1), on the S2-3a contract (D-238).
 *
 * - **Resign now:** the member's declaration takes effect on its date (D-201, no stated date → at once). The period is
 *   the member's current one at that date; the act asked is `record-resignation`, with the declared effective date.
 *   `ownAct` authorises it only when the actor IS the member (GC-1).
 * - **Record a death:** an evidence reference is required (GC-4: refused without evidence), before anyone is asked; the
 *   act is `record-death`, evaluated for the recording date (D-208 §2); the reference is kept on the fact.
 */
final class ResignAndRecordDeathTest extends TestCase
{
    private InMemoryMemberRepository $repo;
    /** @var object{requests: list<AuthorityRequest>, outcome: AuthorityOutcome, kind: CompetenceKind}&AuthorityCheckPort */
    private AuthorityCheckPort $authority;

    protected function setUp(): void
    {
        $clock = 100;
        $this->repo = new InMemoryMemberRepository(static function () use (&$clock): int {
            return $clock++;
        });
        $this->authority = new class implements AuthorityCheckPort {
            /** @var list<AuthorityRequest> */
            public array $requests = [];
            public AuthorityOutcome $outcome = AuthorityOutcome::Authorised;
            public CompetenceKind $kind = CompetenceKind::WorkedOut;

            public function check(TenantId $tenant, AuthorityRequest $request): AuthorityDecision
            {
                $this->requests[] = $request;
                $ok = $this->outcome === AuthorityOutcome::Authorised;

                return AuthorityDecision::of(AuthorityEvidence::of($request->actor, 'test-capacity', 'test:s23b', 'v1', $ok, Instant::of(count($this->requests)), true,
                    AuthorityBasis::of($this->outcome, $ok ? null : 'test reason', $request->at, $ok ? $this->kind : null, null, [])));
            }
        };
        // P1 admitted at 1, term [1, 100)
        (new AdmitMemberHandler($this->repo, $this->authority))->handle(new AdmitMember('O1', 'P1', 'M-P1', 'A1', 1, 1, 100, false, 'registrar-1', 'decl-fixture', null, 'k-admit'));
        $this->authority->requests = [];
    }

    private function resign(string $actor, int $on = 40, string $key = 'k1', string $member = 'M-P1', string $tenant = 'O1')
    {
        return (new ResignMembershipHandler($this->repo, $this->authority))->handle(new ResignMembership($tenant, $member, 'E1', $on, $actor, null, $key));
    }

    private function death(?string $evidence = 'death-certificate:2026/481', int $died = 40, int $recorded = 45, string $actor = 'registrar-1', string $key = 'k1', string $member = 'M-P1')
    {
        return (new RecordDeathHandler($this->repo, $this->authority))->handle(new RecordDeath('O1', $member, 'E1', $died, $recorded, $evidence, $actor, null, $key));
    }

    private function member()
    {
        return $this->repo->load(TenantId::fromString('O1'), MemberId::fromString('M-P1'));
    }

    public function test_a_member_resigns_by_their_own_act(): void
    {
        $this->authority->kind = CompetenceKind::OwnAct;

        $outcome = $this->resign('P1');

        $this->assertTrue($outcome->isAppended());
        $end = $this->member()->log()[1]->fact();
        $this->assertInstanceOf(End::class, $end);
        $this->assertSame(['resignation', 40, 'A1'], [$end->kind()->value(), $end->effectiveAt()->ordinal(), $end->period()->value()], 'effective on its declaration date, in the current period');
        $this->assertFalse($this->member()->resolve()->isMember(TimePoint::of(40)));
        $this->assertSame(CompetenceKind::OwnAct, $end->provenance()->authorityEvidence()->basis()->competenceKind());
        $r = $this->authority->requests[0];
        $this->assertSame([MembershipAct::RecordResignation, 'P1', 'P1', 'A1', 40, 40, 'k1', null],
            [$r->act, $r->actor, $r->subject?->value(), $r->period?->value(), $r->at->ordinal(), $r->declaredEffectiveAt?->ordinal(), $r->requestId, $r->evidenceRef]);
    }

    public function test_own_act_never_lets_one_person_resign_for_another(): void
    {
        $this->authority->kind = CompetenceKind::OwnAct;

        $this->assertSame(ApplicationRefusal::OwnActNotApplicable, $this->resign('P2')->applicationRefusal());
        $this->assertSame(1, $this->member()->version());
    }

    public function test_a_recorder_records_the_resignation_with_their_own_authority(): void
    {
        $this->assertTrue($this->resign('registrar-1')->isAppended());

        $this->assertSame(CompetenceKind::WorkedOut, $this->member()->log()[1]->fact()->provenance()->authorityEvidence()->basis()->competenceKind());
        $this->assertSame('registrar-1', $this->member()->log()[1]->fact()->provenance()->actorOrSource());
    }

    public function test_a_lapsed_membership_can_be_resigned_and_an_ended_one_cannot(): void
    {
        $this->assertTrue($this->resign('registrar-1', 150)->isAppended(), 'lapsed from 100 is not ended (INV-3): resigning ends it');
        $this->assertSame(RefusalReason::PeriodNotFound, $this->resign('registrar-1', 160, 'k2')->domainRefusal(), 'no current period after the End');
        $this->assertNull($this->authority->requests[1]->period, 'no period to name');
        $this->assertSame(RefusalReason::PeriodNotFound, $this->resign('registrar-1', 0, 'k3')->domainRefusal(), 'before the admission');
    }

    public function test_refusals_record_nothing_and_undetermined_is_not_no(): void
    {
        $this->authority->outcome = AuthorityOutcome::NotAuthorised;
        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->resign('registrar-1', 40, 'k1')->applicationRefusal());
        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->death(key: 'k2')->applicationRefusal());

        $this->authority->outcome = AuthorityOutcome::Undetermined;
        $this->assertSame(ApplicationRefusal::AuthorityUndetermined, $this->resign('registrar-1', 40, 'k3')->applicationRefusal());
        $this->assertSame(ApplicationRefusal::AuthorityUndetermined, $this->death(key: 'k4')->applicationRefusal(), 'GC-4: no recording authority → the fact waits');

        $this->assertSame(1, $this->member()->version());
    }

    public function test_an_unauthorised_actor_learns_nothing_about_whether_a_member_exists(): void
    {
        $this->authority->outcome = AuthorityOutcome::NotAuthorised;

        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->resign('registrar-1', 40, 'k1', 'M-nobody')->applicationRefusal());
        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->death(key: 'k2', member: 'M-nobody')->applicationRefusal());
    }

    public function test_another_organisations_member_is_not_found(): void
    {
        $this->assertSame(ApplicationRefusal::MemberNotFound, $this->resign('registrar-1', 40, 'k1', 'M-P1', 'O2')->applicationRefusal());
        $this->assertSame(1, $this->member()->version());
    }

    public function test_the_same_command_twice_records_once(): void
    {
        $first = $this->resign('registrar-1');
        $second = $this->resign('registrar-1');

        $this->assertSame($first->appended()->seq(), $second->appended()->seq());
        $this->assertSame(ApplicationRefusal::IdempotencyKeyConflict, $this->resign('registrar-1', 41)->applicationRefusal());
        $this->assertSame(2, $this->member()->version());
    }

    public function test_a_death_is_recorded_with_its_evidence_under_record_death(): void
    {
        $outcome = $this->death();

        $this->assertTrue($outcome->isAppended());
        $end = $this->member()->log()[1]->fact();
        $this->assertSame(['death', 40], [$end->kind()->value(), $end->effectiveAt()->ordinal()]);
        $this->assertSame('death-certificate:2026/481', $end->provenance()->declarationRef(), 'the evidence the record rests on');
        $r = $this->authority->requests[0];
        $this->assertSame([MembershipAct::RecordDeath, 'P1', 'A1', 45, null, 'death-certificate:2026/481'],
            [$r->act, $r->subject?->value(), $r->period?->value(), $r->at->ordinal(), $r->declaredEffectiveAt, $r->evidenceRef], 'evaluated for the recording date');
    }

    public function test_a_death_without_evidence_is_refused_before_anyone_is_asked(): void
    {
        foreach ([null, '', '   '] as $i => $evidence) {
            $this->assertSame(RefusalReason::DeathEvidenceRequired, $this->death($evidence, key: "k$i")->domainRefusal(), var_export($evidence, true));
        }
        $this->assertSame([], $this->authority->requests);
        $this->assertSame(1, $this->member()->version());
    }

    public function test_a_death_cannot_be_recorded_before_it_happened_or_by_own_act(): void
    {
        $this->assertSame(RefusalReason::EffectiveDateAfterDecisionDate, $this->death(died: 50, recorded: 45)->domainRefusal(), 'recorded before the date of death');

        $this->authority->kind = CompetenceKind::OwnAct;
        $this->assertSame(ApplicationRefusal::OwnActNotApplicable, $this->death(actor: 'P1', key: 'k2')->applicationRefusal(), 'ownAct is for record-resignation only');
        $this->assertSame(1, $this->member()->version());
    }

    public function test_end_membership_cannot_record_a_death_without_evidence(): void
    {
        // the generic End command carries no evidence reference: a death must go through RecordDeath (GC-4)
        $outcome = (new EndMembershipHandler($this->repo, $this->authority))->handle(new EndMembership('O1', 'M-P1', 'E1', 'A1', 40, 45, 'death', 'registrar-1', null, 'k1'));

        $this->assertSame(RefusalReason::DeathEvidenceRequired, $outcome->domainRefusal());
        $this->assertSame([], $this->authority->requests);
    }

    public function test_only_a_death_carries_an_evidence_reference_and_it_must_carry_one(): void
    {
        $at = TimePoint::of(1);
        $this->assertSame('x', (new AuthorityRequest(MembershipAct::RecordDeath, 'a', PersonRef::fromString('P1'), PeriodId::fromString('A1'), $at, null, 'r', 'x'))->evidenceRef);

        foreach ([[MembershipAct::RecordDeath, null], [MembershipAct::RecordDeath, ' '], [MembershipAct::RecordResignation, 'x'], [MembershipAct::Renew, 'x']] as [$act, $evidence]) {
            try {
                new AuthorityRequest($act, 'a', null, null, $at, null, 'r', $evidence);
                $this->fail($act->value . ' with ' . var_export($evidence, true));
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }
}
