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
use App\Contexts\Membership\Application\Organisational\RenewMembership;
use App\Contexts\Membership\Application\Organisational\RenewMembershipHandler;
use App\Contexts\Membership\Application\Organisational\RestoreMembership;
use App\Contexts\Membership\Application\Organisational\RestoreMembershipHandler;
use App\Contexts\Membership\Application\Organisational\SuspendMembership;
use App\Contexts\Membership\Application\Organisational\SuspendMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\AuthorityBasis;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\AuthorityOutcome;
use App\Contexts\Membership\Domain\Organisational\CompetenceKind;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;

/**
 * PBDIGIT-354 S2-3a (D-238): Membership's authority contract aligned with the accepted AuthorityCheck (D-208 §1–§2,
 * C-1a; D-203).
 * - the REQUEST carries act, actor, subject (the member's PersonRef), period, decision date, declared effective date
 *   (resignation) and a request id;
 * - the ANSWER distinguishes Authorised · NotAuthorised · Undetermined, and its basis is recorded on the fact;
 * - ownAct is accepted only for record-resignation with actor = subject;
 * - End asks record-resignation / record-death by kind; a pull exclusion is refused (push only);
 * - an unauthorised actor learns nothing about whether a member exists.
 */
final class AuthorityContractTest extends TestCase
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
                $authorised = $this->outcome === AuthorityOutcome::Authorised;
                $basis = AuthorityBasis::of($this->outcome, $authorised ? null : 'test reason', $request->at, $authorised ? $this->kind : null, 'resolution:7/part:1', ['sha256:e1']);

                return AuthorityDecision::of(AuthorityEvidence::of($request->actor, 'test-capacity', 'test:contract', 'v1', $authorised, Instant::of(count($this->requests)), true, $basis));
            }
        };
        $this->admit();
        $this->authority->requests = [];
    }

    private function admit(string $person = 'P1', string $key = 'k-admit')
    {
        return (new AdmitMemberHandler($this->repo, $this->authority))->handle(
            new AdmitMember('O1', $person, 'M-' . $person, 'A1', 1, 1, 100, false, 'registrar-1', 'decl-fixture', null, $key),
        );
    }

    private function end(string $kind, string $key = 'k-end', string $actor = 'registrar-1', string $member = 'M-P1')
    {
        return (new EndMembershipHandler($this->repo, $this->authority))->handle(new EndMembership('O1', $member, 'E1', 'A1', 50, 50, $kind, $actor, null, $key));
    }

    private function member()
    {
        return $this->repo->load(TenantId::fromString('O1'), MemberId::fromString('M-P1'));
    }

    public function test_every_request_carries_the_act_actor_subject_period_decision_date_and_request_id(): void
    {
        $this->admit('P9', 'k-a');
        (new RenewMembershipHandler($this->repo, $this->authority))->handle(new RenewMembership('O1', 'M-P9', 'N1', 50, 52, 200, false, 'treasurer-1', null, 'k-r'));
        (new SuspendMembershipHandler($this->repo, $this->authority))->handle(new SuspendMembership('O1', 'M-P9', 'S1', 60, 61, null, 'board-1', null, 'k-s'));
        (new RestoreMembershipHandler($this->repo, $this->authority))->handle(new RestoreMembership('O1', 'M-P9', 'R1', 'S1', 70, 71, 'board-1', null, 'k-t'));
        $this->end('resignation', 'k-e', 'registrar-1', 'M-P9');

        $shape = static fn (AuthorityRequest $r): array => [$r->act, $r->actor, $r->subject?->value(), $r->period?->value(), $r->at->ordinal(), $r->declaredEffectiveAt?->ordinal(), $r->requestId];
        $this->assertSame([
            [MembershipAct::Admit, 'registrar-1', 'P9', null, 1, null, 'k-a'],
            [MembershipAct::Renew, 'treasurer-1', 'P9', null, 52, null, 'k-r'],
            [MembershipAct::Suspend, 'board-1', 'P9', null, 61, null, 'k-s'],
            [MembershipAct::Restore, 'board-1', 'P9', null, 71, null, 'k-t'],
            [MembershipAct::RecordResignation, 'registrar-1', 'P9', 'A1', 50, 50, 'k-e'],
        ], array_map($shape, $this->authority->requests));
    }

    public function test_the_act_kinds_are_governances_names(): void
    {
        $this->assertSame(['admit-member', 'record-resignation', 'record-death', 'renew-membership', 'suspend-membership', 'restore-membership',
            'correct-membership-record', 'void-membership-record'],   // S2-5a: D-208 §2's names, exactly
            array_map(static fn (MembershipAct $a) => $a->value, MembershipAct::cases()));
    }

    public function test_end_asks_the_act_of_its_kind(): void
    {
        $this->assertTrue($this->end('resignation')->isAppended());
        $this->assertSame(MembershipAct::RecordResignation, $this->authority->requests[0]->act);

        // S2-3b (GC-4): a death needs its evidence reference, which this command does not carry: refused before asking.
        // The death path (record-death, no declared date) is RecordDeath: ResignAndRecordDeathTest.
        $this->assertSame(RefusalReason::DeathEvidenceRequired, $this->end('death', 'k2')->domainRefusal());
        $this->assertCount(1, $this->authority->requests);
    }

    public function test_a_pull_exclusion_is_refused_without_asking_anyone(): void
    {
        $outcome = $this->end('exclusion');

        $this->assertSame(RefusalReason::ExclusionIsPushOnly, $outcome->domainRefusal());
        $this->assertSame([], $this->authority->requests, 'exclude-member is pushed by Governance (S2-6); no office exercises it');
        $this->assertSame(1, $this->member()->version());
    }

    public function test_legacy_and_unknown_end_kinds_are_refused_before_asking(): void
    {
        $this->assertSame(RefusalReason::LegacyEndRequiresAttestedHistory, $this->end('legacy', 'k1')->domainRefusal());
        $this->assertSame(RefusalReason::EndKindNotRecognised, $this->end('expulsion', 'k2')->domainRefusal());
        $this->assertSame([], $this->authority->requests);
    }

    public function test_undetermined_is_not_not_authorised_and_records_nothing(): void
    {
        $this->authority->outcome = AuthorityOutcome::Undetermined;
        $this->assertSame(ApplicationRefusal::AuthorityUndetermined, $this->end('resignation', 'k1')->applicationRefusal());

        $this->authority->outcome = AuthorityOutcome::NotAuthorised;
        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->end('resignation', 'k2')->applicationRefusal());

        $this->assertSame(1, $this->member()->version());
    }

    public function test_the_basis_is_recorded_on_the_fact(): void
    {
        $this->end('resignation');

        $basis = $this->member()->log()[1]->fact()->provenance()->authorityEvidence()->basis();
        $this->assertSame(AuthorityOutcome::Authorised, $basis->outcome());
        $this->assertSame(CompetenceKind::WorkedOut, $basis->competenceKind());
        $this->assertSame(50, $basis->evaluatedFor()->ordinal());
        $this->assertSame('resolution:7/part:1', $basis->decisionRef());
        $this->assertSame(['sha256:e1'], $basis->evidence());
    }

    public function test_own_act_is_accepted_only_for_a_resignation_by_the_member_concerned(): void
    {
        $this->authority->kind = CompetenceKind::OwnAct;

        $this->assertSame(ApplicationRefusal::OwnActNotApplicable, $this->end('resignation', 'k1', 'registrar-1')->applicationRefusal(), 'actor is not the subject');
        // (ownAct refused for record-death: ResignAndRecordDeathTest, through RecordDeath, since S2-3b)
        $this->assertSame(ApplicationRefusal::OwnActNotApplicable,
            (new RenewMembershipHandler($this->repo, $this->authority))->handle(new RenewMembership('O1', 'M-P1', 'N1', 50, 50, 200, false, 'P1', null, 'k3'))->applicationRefusal());
        $this->assertSame(1, $this->member()->version(), 'nothing recorded');

        $this->assertTrue($this->end('resignation', 'k4', 'P1')->isAppended(), 'the member resigning: actor = subject');
        $this->assertSame(CompetenceKind::OwnAct, $this->member()->log()[1]->fact()->provenance()->authorityEvidence()->basis()->competenceKind());
    }

    public function test_an_unauthorised_actor_learns_nothing_about_whether_a_member_exists(): void
    {
        $this->authority->outcome = AuthorityOutcome::NotAuthorised;

        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->end('resignation', 'k1', 'registrar-1', 'M-nobody')->applicationRefusal());
        $this->assertNull($this->authority->requests[0]->subject, 'no subject to name, and still not "member not found"');

        $this->authority->outcome = AuthorityOutcome::Authorised;
        $this->assertSame(ApplicationRefusal::MemberNotFound, $this->end('resignation', 'k2', 'registrar-1', 'M-nobody')->applicationRefusal());
    }

    public function test_a_refused_admission_still_creates_no_member_record(): void
    {
        $this->authority->outcome = AuthorityOutcome::NotAuthorised;

        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->admit('P2', 'k1')->applicationRefusal());
        $this->assertNull($this->repo->findByHolder(TenantId::fromString('O1'), PersonRef::fromString('P2')));
    }

    public function test_an_answer_whose_result_and_outcome_disagree_cannot_be_constructed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        AuthorityEvidence::of('a', 'c', 'r', 'v1', true, Instant::of(1), true,
            AuthorityBasis::of(AuthorityOutcome::Undetermined, 'rules silent', TimePoint::of(1), null, null, []));
    }

    public function test_historical_evidence_without_a_basis_counts_by_its_result(): void
    {
        $decision = AuthorityDecision::of(AuthorityEvidence::of('a', 'c', 'r', 'v1', false, Instant::of(1), false));

        $this->assertSame(AuthorityOutcome::NotAuthorised, $decision->outcome(), 'no basis: a false result is a refusal, never Undetermined');
        $this->assertTrue(AuthorityDecision::of(AuthorityEvidence::of('a', 'c', 'r', 'v1', true, Instant::of(1), false))->isAuthorised());
    }
}
