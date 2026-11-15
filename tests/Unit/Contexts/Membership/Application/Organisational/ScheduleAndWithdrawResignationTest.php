<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\ApplicationRefusal;
use App\Contexts\Membership\Application\Organisational\DeclarationOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest;
use App\Contexts\Membership\Application\Organisational\Ports\DeclarationWriteOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Application\Organisational\ScheduleResignation;
use App\Contexts\Membership\Application\Organisational\ScheduleResignationHandler;
use App\Contexts\Membership\Application\Organisational\WithdrawScheduledResignation;
use App\Contexts\Membership\Application\Organisational\WithdrawScheduledResignationHandler;
use App\Contexts\Membership\Domain\Organisational\AuthorityBasis;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\AuthorityOutcome;
use App\Contexts\Membership\Domain\Organisational\CompetenceKind;
use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignationState;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;
use Tests\Doubles\InMemoryScheduledResignationStore;

/**
 * PBDIGIT-354 S2-4b: schedule and withdraw a resignation (D-201, D-204; D-208 §2 `record-resignation`, both shapes;
 * GC-1, GC-2, GC-12; M-TDD-01 §5.1–§5.2; D-238 order).
 *
 * - **Schedule:** asks `record-resignation` with the declared (later) effective date; records a declaration, **no ledger
 *   fact** (the membership is unchanged, MI-4). One scheduled declaration per period.
 * - **Withdraw:** asks `record-resignation` in its withdraw shape: the subject, the period and **the declaration's
 *   reference** (`declarationRef`), no effective date. Only while scheduled and before its date.
 * - **D-238 order:** a boundary refusal before anyone is asked; not authorised / undetermined before "not found", so no
 *   existence leak; `ownAct` only when the actor is the member.
 * Day ordinals. P1 admitted at 1 (period A1, term [1, 100)).
 */
final class ScheduleAndWithdrawResignationTest extends TestCase
{
    private InMemoryMemberRepository $repo;
    private InMemoryScheduledResignationStore $store;
    /** @var object{requests: list<AuthorityRequest>, outcome: AuthorityOutcome, kind: CompetenceKind}&AuthorityCheckPort */
    private AuthorityCheckPort $authority;

    protected function setUp(): void
    {
        $clock = 100;
        $this->repo = new InMemoryMemberRepository(static function () use (&$clock): int {
            return $clock++;
        });
        $this->store = new InMemoryScheduledResignationStore($this->repo);
        $this->authority = new class implements AuthorityCheckPort {
            /** @var list<AuthorityRequest> */
            public array $requests = [];
            public AuthorityOutcome $outcome = AuthorityOutcome::Authorised;
            public CompetenceKind $kind = CompetenceKind::WorkedOut;

            public function check(TenantId $tenant, AuthorityRequest $request): AuthorityDecision
            {
                $this->requests[] = $request;
                $ok = $this->outcome === AuthorityOutcome::Authorised;

                return AuthorityDecision::of(AuthorityEvidence::of($request->actor, 'test-capacity', 'test:s24b', 'v1', $ok, Instant::of(count($this->requests)), true,
                    AuthorityBasis::of($this->outcome, $ok ? null : 'test reason', $request->at, $ok ? $this->kind : null, null, [])));
            }
        };
        (new AdmitMemberHandler($this->repo, $this->authority))->handle(new AdmitMember('O1', 'P1', 'M-P1', 'A1', 1, 1, 100, false, 'registrar-1', 'decl-fixture', null, 'k-admit'));
        $this->authority->requests = [];
    }

    private function schedule(string $actor = 'P1', int $on = 10, int $from = 30, string $key = 'k-s1', string $id = 'D1', string $member = 'M-P1'): DeclarationOutcome
    {
        return (new ScheduleResignationHandler($this->repo, $this->store, $this->authority))->handle(new ScheduleResignation('O1', $member, $id, $on, $from, $actor, 5_000, $key));
    }

    private function withdraw(string $actor = 'P1', int $on = 20, string $key = 'k-w1', string $id = 'D1', string $member = 'M-P1'): DeclarationOutcome
    {
        return (new WithdrawScheduledResignationHandler($this->repo, $this->store, $this->authority))->handle(new WithdrawScheduledResignation('O1', $member, $id, $on, $actor, $key));
    }

    private function stored(string $id = 'D1')
    {
        return $this->store->find(TenantId::fromString('O1'), MemberId::fromString('M-P1'), DeclarationId::fromString($id));
    }

    public function test_the_member_schedules_their_resignation_and_nothing_changes_in_the_membership(): void
    {
        $this->authority->kind = CompetenceKind::OwnAct;
        $before = $this->repo->load(TenantId::fromString('O1'), MemberId::fromString('M-P1'));

        $outcome = $this->schedule();

        $this->assertTrue($outcome->isRecorded());
        $this->assertSame(ScheduledResignationState::Scheduled, $outcome->declaration()->state());
        $asked = $this->authority->requests[0];
        $this->assertSame([MembershipAct::RecordResignation, 'P1', 'P1', 'A1', 10, 30, null, 'k-s1'],
            [$asked->act, $asked->actor, $asked->subject?->value(), $asked->period?->value(), $asked->at->ordinal(), $asked->declaredEffectiveAt?->ordinal(), $asked->declarationRef, $asked->requestId]);
        $this->assertSame('P1', $this->stored()->provenance()->actorOrSource());
        $this->assertSame(CompetenceKind::OwnAct, $this->stored()->provenance()->authorityEvidence()?->basis()?->competenceKind(), 'the answer is kept for the execution');
        $this->assertSame(5_000, $this->stored()->decidedAt()?->ordinal());
        $after = $this->repo->load(TenantId::fromString('O1'), MemberId::fromString('M-P1'));
        $this->assertSame($before->version(), $after->version(), 'no ledger fact (D-204): no End before execution');
        $this->assertTrue($before->resolve()->coverage()->equals($after->resolve()->coverage()));
    }

    public function test_a_recorder_schedules_with_their_own_authority_and_own_act_authorises_only_the_member(): void
    {
        $this->assertTrue($this->schedule('registrar-1')->isRecorded());

        $this->authority->kind = CompetenceKind::OwnAct;
        $this->assertSame(ApplicationRefusal::OwnActNotApplicable, $this->schedule('registrar-2', key: 'k-s2', id: 'D2')->applicationRefusal());
    }

    public function test_the_same_day_is_refused_before_anyone_is_asked(): void
    {
        $this->assertSame(RefusalReason::ScheduledDateNotAfterDeclaration, $this->schedule(on: 10, from: 10)->domainRefusal(), 'the same day is resign now (S2-3)');
        $this->assertSame([], $this->authority->requests);
    }

    public function test_not_authorised_or_undetermined_records_nothing_and_comes_before_member_not_found(): void
    {
        $this->authority->outcome = AuthorityOutcome::NotAuthorised;
        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->schedule()->applicationRefusal());
        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->schedule(member: 'M-NOBODY', key: 'k-s2')->applicationRefusal(), 'no existence leak');
        $this->assertNull(end($this->authority->requests)->subject, 'an unknown member is asked about as no one');

        $this->authority->outcome = AuthorityOutcome::Undetermined;
        $this->assertSame(ApplicationRefusal::AuthorityUndetermined, $this->schedule(key: 'k-s3')->applicationRefusal());
        $this->assertNull($this->stored());

        $this->authority->outcome = AuthorityOutcome::Authorised;
        $this->assertSame(ApplicationRefusal::MemberNotFound, $this->schedule(member: 'M-NOBODY', key: 'k-s4')->applicationRefusal());
    }

    public function test_one_scheduled_declaration_per_period(): void
    {
        $this->assertTrue($this->schedule()->isRecorded());

        $this->assertSame(RefusalReason::ScheduledResignationAlreadyPending, $this->schedule(on: 11, from: 40, key: 'k-s2', id: 'D2')->domainRefusal());
        $this->assertNull($this->stored('D2'));
    }

    public function test_a_repeated_request_returns_the_same_declaration_and_a_reused_key_is_refused(): void
    {
        $first = $this->schedule();

        $again = $this->schedule();
        $this->assertTrue($again->isRecorded());
        $this->assertEquals($first->declaration(), $again->declaration());
        $this->assertCount(1, $this->authority->requests, 'answered from the key; nobody is asked again');
        $this->assertSame(ApplicationRefusal::IdempotencyKeyConflict, $this->schedule(from: 31)->applicationRefusal(), 'the same key, another command');
        $this->assertCount(1, $this->store->forMember(TenantId::fromString('O1'), MemberId::fromString('M-P1')));
    }

    public function test_the_member_withdraws_before_the_date_in_the_withdraw_shape(): void
    {
        $this->schedule();
        $this->authority->requests = [];
        $this->authority->kind = CompetenceKind::OwnAct;

        $outcome = $this->withdraw();

        $this->assertTrue($outcome->isRecorded());
        $this->assertSame(ScheduledResignationState::Withdrawn, $this->stored()->state());
        $asked = $this->authority->requests[0];
        $this->assertSame([MembershipAct::RecordResignation, 'P1', 'P1', 'A1', 20, null, 'D1', 'k-w1'],
            [$asked->act, $asked->actor, $asked->subject?->value(), $asked->period?->value(), $asked->at->ordinal(), $asked->declaredEffectiveAt?->ordinal(), $asked->declarationRef, $asked->requestId],
            'D-208 §2 withdraw shape: the declaration reference, no effective date');
        $this->assertSame(['P1', 20], [$this->stored()->withdrawnBy()?->actorOrSource(), $this->stored()->withdrawnOn()?->ordinal()]);
        $this->assertSame([10, 30], [$this->stored()->declarationDate()->ordinal(), $this->stored()->effectiveDate()->ordinal()], 'the declaration stays in the history');
    }

    public function test_a_recorder_may_withdraw_and_own_act_authorises_only_the_member(): void
    {
        $this->schedule();
        $this->authority->kind = CompetenceKind::OwnAct;
        $this->assertSame(ApplicationRefusal::OwnActNotApplicable, $this->withdraw('registrar-1')->applicationRefusal());

        $this->authority->kind = CompetenceKind::WorkedOut;
        $this->assertTrue($this->withdraw('registrar-1', key: 'k-w2')->isRecorded(), 'GC-12: the member or a recorder');
    }

    public function test_withdrawal_once_the_date_has_started_is_refused_and_a_withdrawal_is_final(): void
    {
        $this->schedule();

        $this->assertSame(RefusalReason::WithdrawalAfterEffectiveDate, $this->withdraw(on: 30)->domainRefusal(), 'D-201: before its effective date');
        $this->assertSame(ScheduledResignationState::Scheduled, $this->stored()->state());
        $this->assertTrue($this->withdraw(on: 29, key: 'k-w2')->isRecorded());
        $this->assertSame(RefusalReason::DeclarationNotScheduled, $this->withdraw(on: 29, key: 'k-w3')->domainRefusal());
    }

    public function test_an_unknown_declaration_is_not_revealed_to_an_unauthorised_actor(): void
    {
        $this->schedule();
        $this->authority->outcome = AuthorityOutcome::NotAuthorised;
        $this->assertSame(ApplicationRefusal::NotAuthorised, $this->withdraw(id: 'D-NONE')->applicationRefusal());
        $this->assertNull(end($this->authority->requests)->period, 'nothing about it is told to the authority either');

        $this->authority->outcome = AuthorityOutcome::Authorised;
        $this->assertSame(ApplicationRefusal::DeclarationNotFound, $this->withdraw(id: 'D-NONE', key: 'k-w2')->applicationRefusal());
    }

    public function test_after_a_withdrawal_a_new_resignation_can_be_scheduled_and_a_repeated_withdrawal_is_answered_from_its_key(): void
    {
        $this->schedule();
        $this->assertTrue($this->withdraw()->isRecorded());
        $this->assertTrue($this->withdraw()->isRecorded(), 'the same request again: the same answer');
        $this->assertSame(ApplicationRefusal::IdempotencyKeyConflict, $this->withdraw(on: 21)->applicationRefusal());

        $this->assertTrue($this->schedule(on: 22, from: 50, key: 'k-s2', id: 'D2')->isRecorded());
        $this->assertSame(['withdrawn', 'scheduled'], array_map(static fn ($d) => $d->state()->value, $this->store->forMember(TenantId::fromString('O1'), MemberId::fromString('M-P1'))), 'the full history');
    }

    public function test_s2_4d_the_store_decides_withdrawal_at_the_organisations_own_date_and_its_refusal_is_returned(): void
    {
        $this->schedule();
        $this->store->organisationToday = TimePoint::of(30);   // the organisation's date at the ledger reading

        $this->assertSame(RefusalReason::WithdrawalAfterEffectiveDate, $this->withdraw(on: 20)->domainRefusal(), 'the stated 20th does not count: it is already the 30th there');
        $this->assertSame(ScheduledResignationState::Scheduled, $this->stored()->state());
        $this->assertNull($this->store->appliedRequest(TenantId::fromString('O1'), \App\Contexts\Membership\Application\Organisational\IdempotencyKey::fromString('k-w1')), 'a refused withdrawal registers no key');

        $this->store->organisationToday = TimePoint::of(29);
        $this->assertTrue($this->withdraw(on: 20, key: 'k-w2')->isRecorded());
    }

    public function test_a_write_conflict_is_decided_again_from_a_fresh_read(): void
    {
        $this->store->conflictNext = 1;

        $this->assertTrue($this->schedule()->isRecorded());
        $this->assertCount(2, $this->authority->requests, 'decided again, asked again (R-1a-2: never reuse)');

        $this->store->conflictNext = PHP_INT_MAX;
        $this->assertSame(ApplicationRefusal::ConcurrentModification, $this->withdraw()->applicationRefusal());
        $this->assertSame(ScheduledResignationState::Scheduled, $this->stored()->state());
    }

    public function test_the_store_outcomes_are_all_mapped(): void
    {
        $this->assertSame(['written', 'already_applied', 'idempotency_key_conflict', 'conflict', 'member_not_found'], array_map(static fn ($c) => $c->value, DeclarationWriteOutcome::cases()));
    }

    public function test_the_withdraw_shape_is_validated_by_the_request(): void
    {
        $at = TimePoint::of(20);
        new AuthorityRequest(MembershipAct::RecordResignation, 'P1', null, PeriodId::fromString('A1'), $at, null, 'k', null, 'D1');
        foreach ([
            'another act' => fn () => new AuthorityRequest(MembershipAct::Renew,'P1', null, null, $at, null, 'k', null, 'D1'),
            'with an effective date' => fn () => new AuthorityRequest(MembershipAct::RecordResignation, 'P1', null, null, $at, TimePoint::of(30), 'k', null, 'D1'),
            'empty' => fn () => new AuthorityRequest(MembershipAct::RecordResignation, 'P1', null, null, $at, null, 'k', null, ' '),
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
