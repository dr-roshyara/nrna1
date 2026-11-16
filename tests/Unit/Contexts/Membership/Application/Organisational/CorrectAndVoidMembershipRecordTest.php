<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\ApplicationRefusal;
use App\Contexts\Membership\Application\Organisational\CommandOutcome;
use App\Contexts\Membership\Application\Organisational\CorrectMembershipRecord;
use App\Contexts\Membership\Application\Organisational\CorrectMembershipRecordHandler;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Application\Organisational\RenewMembership;
use App\Contexts\Membership\Application\Organisational\RenewMembershipHandler;
use App\Contexts\Membership\Application\Organisational\ResignMembership;
use App\Contexts\Membership\Application\Organisational\ResignMembershipHandler;
use App\Contexts\Membership\Application\Organisational\VoidMembershipRecord;
use App\Contexts\Membership\Application\Organisational\VoidMembershipRecordHandler;
use App\Contexts\Membership\Domain\Organisational\AuthorityBasis;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\AuthorityOutcome;
use App\Contexts\Membership\Domain\Organisational\CompetenceKind;
use App\Contexts\Membership\Domain\Organisational\Correct;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Domain\Organisational\VoidFact;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;

/**
 * PBDIGIT-354 S2-5a: Correct and Void as use cases with recording authority and evidence (D-206; D-208 §2
 * `correct-membership-record`, `void-membership-record`; GC-14; D-238 order). The ratified algebra (C-1, C-2) is
 * unchanged: a Correct replaces the date of an Admit or End (D-COR-3), a Void records that a fact never reflected
 * reality; both are NEW facts, the original stays in the log.
 *
 * - Evidence is required: refused at the boundary, before anyone is asked (as a death, GC-4 / GC-14).
 * - Request shapes (D-208 §2): Correct = (fact ref, corrected value, evidence ref); Void = (fact ref, evidence ref).
 * - Not authorised / Undetermined before "not found" (no existence leak); `ownAct` authorises nothing here.
 * - Not in S2-5a: a Correct of a Renew (D-237, S2-5b) or of a Suspend / Restore (row 14, S2-5b; Q-6) stays refused.
 * Day ordinals. P1 admitted at 1 (period A1, term [1, 100)); resigned at 40 (E1).
 */
final class CorrectAndVoidMembershipRecordTest extends TestCase
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

                return AuthorityDecision::of(AuthorityEvidence::of($request->actor, 'recording-office', 'test:s25a', 'v1', $ok, Instant::of(count($this->requests)), true,
                    AuthorityBasis::of($this->outcome, $ok ? null : 'test reason', $request->at, $ok ? $this->kind : null, null, [])));
            }
        };
        (new AdmitMemberHandler($this->repo, $this->authority))->handle(new AdmitMember('O1', 'P1', 'M-P1', 'A1', 1, 1, 100, false, 'registrar-1', 'decl-fixture', null, 'k-admit'));
        (new ResignMembershipHandler($this->repo, $this->authority))->handle(new ResignMembership('O1', 'M-P1', 'E1', 40, 'P1', null, 'k-resign'));
        $this->authority->requests = [];
    }

    private function correct(string $target = 'E1', int $to = 45, ?string $evidence = 'minutes:2024/12', string $key = 'k-c1', string $id = 'C1', string $member = 'M-P1', string $actor = 'registrar-1'): CommandOutcome
    {
        return (new CorrectMembershipRecordHandler($this->repo, $this->authority))->handle(new CorrectMembershipRecord('O1', $member, $id, $target, $to, 50, $evidence, $actor, null, $key));
    }

    private function void(string $target = 'E1', ?string $evidence = 'minutes:2024/12', string $key = 'k-v1', string $id = 'V1', string $member = 'M-P1'): CommandOutcome
    {
        return (new VoidMembershipRecordHandler($this->repo, $this->authority))->handle(new VoidMembershipRecord('O1', $member, $id, $target, 50, $evidence, 'registrar-1', null, $key));
    }

    private function member()
    {
        return $this->repo->load(TenantId::fromString('O1'), MemberId::fromString('M-P1'));
    }

    public function test_a_recorder_corrects_an_end_date_with_evidence_as_a_new_fact(): void
    {
        $outcome = $this->correct();

        $this->assertTrue($outcome->isAppended());
        $asked = $this->authority->requests[0];
        $this->assertSame([MembershipAct::CorrectMembershipRecord, 'registrar-1', 'P1', null, 50, null, 'minutes:2024/12', 'E1', 45],
            [$asked->act, $asked->actor, $asked->subject?->value(), $asked->period, $asked->at->ordinal(), $asked->declaredEffectiveAt, $asked->evidenceRef, $asked->factRef, $asked->correctedValue?->ordinal()],
            'D-208 §2: (fact ref, corrected value, evidence ref)');
        $log = $this->member()->log();
        $this->assertCount(3, $log, 'Admit, End, Correct: the End is not rewritten');
        $correct = $log[2]->fact();
        $this->assertInstanceOf(Correct::class, $correct);
        $this->assertSame(['E1', 45, 'registrar-1', 'minutes:2024/12', 'recording-office'],
            [$correct->target()->value(), $correct->value()->ordinal(), $correct->provenance()->actorOrSource(), $correct->provenance()->declarationRef(), $correct->provenance()->authorityEvidence()?->capacity()],
            'recorded from evidence: recorder, authority answer, evidence reference (D-148 / D-159)');
        $this->assertSame(40, $log[1]->fact()->effectiveAt()->ordinal(), 'the original End keeps its date');
        $this->assertTrue($this->member()->resolve()->isMember(TimePoint::of(44)));
        $this->assertFalse($this->member()->resolve()->isMember(TimePoint::of(45)));
    }

    public function test_a_recorder_voids_a_fact_with_evidence_as_a_new_fact(): void
    {
        $outcome = $this->void();

        $this->assertTrue($outcome->isAppended());
        $asked = $this->authority->requests[0];
        $this->assertSame([MembershipAct::VoidMembershipRecord, 'P1', 'minutes:2024/12', 'E1', null],
            [$asked->act, $asked->subject?->value(), $asked->evidenceRef, $asked->factRef, $asked->correctedValue], 'D-208 §2: (fact ref, evidence ref)');
        $log = $this->member()->log();
        $this->assertCount(3, $log);
        $this->assertInstanceOf(VoidFact::class, $log[2]->fact());
        $this->assertSame('minutes:2024/12', $log[2]->fact()->provenance()->declarationRef());
        $this->assertTrue($this->member()->resolve()->isMember(TimePoint::of(60)), 'the End never reflected reality: the period is open again');
    }

    public function test_evidence_is_required_before_anyone_is_asked(): void
    {
        foreach ([null, '', '  '] as $missing) {
            $this->assertSame(RefusalReason::CorrectionEvidenceRequired, $this->correct(evidence: $missing)->domainRefusal());
            $this->assertSame(RefusalReason::CorrectionEvidenceRequired, $this->void(evidence: $missing)->domainRefusal());
        }
        $this->assertSame([], $this->authority->requests);
        $this->assertCount(2, $this->member()->log());
    }

    public function test_not_authorised_and_undetermined_change_nothing_and_come_before_not_found(): void
    {
        $this->authority->outcome = AuthorityOutcome::NotAuthorised;
        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->correct()->applicationRefusal());
        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->void(member: 'M-NOBODY', key: 'k-v2')->applicationRefusal(), 'no existence leak');
        $this->assertNull(end($this->authority->requests)->subject);

        $this->authority->outcome = AuthorityOutcome::Undetermined;
        $this->assertSame(ApplicationRefusal::AuthorityUndetermined, $this->correct(key: 'k-c2')->applicationRefusal(), 'D-206: rules silent → nothing is corrected');
        $this->assertSame(ApplicationRefusal::AuthorityUndetermined, $this->void(key: 'k-v3')->applicationRefusal());
        $this->assertCount(2, $this->member()->log());

        $this->authority->outcome = AuthorityOutcome::Authorised;
        $this->assertSame(ApplicationRefusal::MemberNotFound, $this->correct(member: 'M-NOBODY', key: 'k-c3')->applicationRefusal());
        $this->assertSame(RefusalReason::TargetNotFound, $this->correct(target: 'E-NONE', key: 'k-c4')->domainRefusal(), 'an unknown fact: told only after authorisation');
    }

    public function test_own_act_authorises_no_correction_and_no_void(): void
    {
        $this->authority->kind = CompetenceKind::OwnAct;

        $this->assertSame(ApplicationRefusal::OwnActNotApplicable, $this->correct(actor: 'P1')->applicationRefusal(), 'a member does not correct the record (D-203)');
        $this->assertSame(ApplicationRefusal::OwnActNotApplicable, $this->void()->applicationRefusal());
    }

    public function test_a_correction_of_a_correction_resolves_to_the_latest_value_and_keeps_the_chain(): void
    {
        $this->correct(to: 45);
        $this->assertTrue($this->correct(target: 'C1', to: 44, key: 'k-c2', id: 'C2')->isAppended());

        $this->assertCount(4, $this->member()->log(), 'End, its Correct and the Correct of the Correct all stay');
        $this->assertTrue($this->member()->resolve()->isMember(TimePoint::of(43)));
        $this->assertFalse($this->member()->resolve()->isMember(TimePoint::of(44)));
    }

    public function test_a_repeated_request_is_answered_from_its_key_and_a_reused_key_is_refused(): void
    {
        $first = $this->correct();

        $this->assertEquals($first->appended(), $this->correct()->appended());
        $this->assertCount(1, $this->authority->requests, 'not asked again');
        $this->assertSame(ApplicationRefusal::IdempotencyKeyConflict, $this->correct(to: 46)->applicationRefusal());
        $this->assertCount(3, $this->member()->log());
    }

    public function test_s2_5b_a_renewals_until_is_corrected_to_a_date_or_to_open_ended_d237(): void
    {
        $this->assertTrue((new RenewMembershipHandler($this->repo, $this->authority))->handle(new RenewMembership('O1', 'M-P1', 'R1', 20, 20, 150, false, 'registrar-1', null, 'k-renew'))->isAppended(), 'renewed on day 20');

        $this->assertTrue($this->correct(target: 'R1', to: 160, key: 'k-c-r')->isAppended(), 'until 160, after the decision date 50: D-237 (2)');
        $outcome = (new CorrectMembershipRecordHandler($this->repo, $this->authority))->handle(
            new CorrectMembershipRecord('O1', 'M-P1', 'C-OPEN', 'R1', null, 50, 'minutes:2024/12', 'registrar-1', null, 'k-c-open', openEnded: true));
        $this->assertTrue($outcome->isAppended(), 'open-ended');
        $this->assertTrue(end($this->authority->requests)->correctedValue?->isInfinite(), 'the request names the corrected value: ∞');
        $this->assertSame(RefusalReason::CorrectedValueRequired, (new CorrectMembershipRecordHandler($this->repo, $this->authority))->handle(
            new CorrectMembershipRecord('O1', 'M-P1', 'C-BAD', 'R1', 170, 50, 'minutes:2024/12', 'registrar-1', null, 'k-c-bad', openEnded: true))->domainRefusal(), 'a date AND open-ended: refused at the boundary');
        $this->assertSame(RefusalReason::CorrectedValueRequired, (new CorrectMembershipRecordHandler($this->repo, $this->authority))->handle(
            new CorrectMembershipRecord('O1', 'M-P1', 'C-NONE', 'R1', null, 50, 'minutes:2024/12', 'registrar-1', null, 'k-c-none'))->domainRefusal(), 'neither');
    }

    public function test_the_request_shapes_are_validated(): void
    {
        $at = TimePoint::of(50);
        new AuthorityRequest(MembershipAct::CorrectMembershipRecord, 'r', null, null, $at, null, 'k', 'ev', null, 'E1', TimePoint::of(45));
        new AuthorityRequest(MembershipAct::VoidMembershipRecord, 'r', null, null, $at, null, 'k', 'ev', null, 'E1');
        foreach ([
            'correct without evidence' => fn () => new AuthorityRequest(MembershipAct::CorrectMembershipRecord, 'r', null, null, $at, null, 'k', null, null, 'E1', TimePoint::of(45)),
            'correct without a fact' => fn () => new AuthorityRequest(MembershipAct::CorrectMembershipRecord, 'r', null, null, $at, null, 'k', 'ev', null, null, TimePoint::of(45)),
            'correct without a value' => fn () => new AuthorityRequest(MembershipAct::CorrectMembershipRecord, 'r', null, null, $at, null, 'k', 'ev', null, 'E1'),
            'void without evidence' => fn () => new AuthorityRequest(MembershipAct::VoidMembershipRecord, 'r', null, null, $at, null, 'k', null, null, 'E1'),
            'void with a value' => fn () => new AuthorityRequest(MembershipAct::VoidMembershipRecord, 'r', null, null, $at, null, 'k', 'ev', null, 'E1', TimePoint::of(45)),
            'a fact ref on another act' => fn () => new AuthorityRequest(MembershipAct::Renew, 'r', null, null, $at, null, 'k', null, null, 'E1'),
            'a value on another act' => fn () => new AuthorityRequest(MembershipAct::Renew, 'r', null, null, $at, null, 'k', null, null, null, TimePoint::of(45)),
            'evidence on another act' => fn () => new AuthorityRequest(MembershipAct::Renew, 'r', null, null, $at, null, 'k', 'ev'),
        ] as $why => $build) {
            try {
                $build();
                $this->fail("accepted: {$why}");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }
}
