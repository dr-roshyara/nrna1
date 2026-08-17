<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\OperatingCore\Event\NonCanonicalEventName;
use App\Contexts\Election\Domain\OperatingCore\Exception\CommitteeTooSmall;
use App\Contexts\Election\Domain\OperatingCore\Port\HistoryKind;

/**
 * EM-IMPL-002 RED — UC-5 `RecordCommitteeConstitution` (A-3: INCLUDED by the
 * boundary act; UC-6 remains OUT — construction fixture only).
 *
 * RESPONSIBILITY TABLE (G-2):
 *  receive command                   → Application (this handler) — like UC-3 an
 *                                      entry point for the FUTURE D-1 adapter;
 *                                      no internal caller exists (EM-GOV-026/028)
 *  authenticate/authorize the caller → expressly NOT here — constitution facts
 *                                      arrive only from the external authority
 *  decide election meaning (I-1,
 *  duplicate seats, I-3 denominator) → Domain (frozen AG-1)
 *  record fact / refusal             → ProtocolAppend (Lifecycle history)
 *  persist                           → Infrastructure — later increment
 *
 * OPEN POINT, NAMED (not invented): the frozen domain core defines NO recorded-fact
 * event class for the constitution act (`ElectionCommittee::constitute()` returns
 * the aggregate, not a fact), and the domain is frozen. The proposal calls the
 * fact "constitution fact (placeholder — EM-OPEN-045)" without a type. This suite
 * therefore pins only what the record determines: exactly ONE Lifecycle fact entry
 * is appended, it is a `DomainEvent` marked `NonCanonicalEventName`, and it is not
 * a refusal. WHICH type carries it is a Phase-2/Governance decision to be made at
 * review — never silently.
 */
final class RecordCommitteeConstitutionHandlerRedTest extends OperatingCoreApplicationTestCase
{
    /** I-1/I-3: the constitution creates AG-1 with the denominator fixed forever; the act is recorded once, in the Lifecycle history. */
    public function test_uc5_constitution_creates_the_committee_and_appends_one_lifecycle_fact(): void
    {
        $this->instants->setNowEpoch(1_000);
        $this->recordConstitutionHandler()->handle($this->constitutionCommand('s1', 's2', 's3'));

        $committee = $this->committees->find($this->electionId());
        $this->assertNotNull($committee, 'AG-1 exists after the recorded constitution.');
        $this->assertSame(3, $committee->constitutedSize(), 'I-3: the denominator is fixed at constitution (EM-GOV-057).');

        $this->assertCount(1, $this->protocol->eventEntries(), 'Exactly one constitution fact is appended (EM-GOV-005).');
        $entry = $this->protocol->eventEntries()[0];
        $this->assertSame(HistoryKind::Lifecycle, $entry->kind, 'Constitution is lifecycle history, never a progression decision (P-2H).');
        $this->assertFalse($entry->isRefusal());
        $this->assertInstanceOf(DomainEvent::class, $entry->event);
        $this->assertInstanceOf(NonCanonicalEventName::class, $entry->event, 'D-7/EM-OPEN-045: the fact\'s type name carries placeholder standing.');
    }

    /** Q-2 (UC-5, verbatim from the grant): "same fact exists → return existing result; never a second constitution". */
    public function test_q2_duplicate_constitution_returns_the_existing_result_and_never_a_second_fact(): void
    {
        $existing = $this->seedCommittee('s1', 's2', 's3');

        $this->instants->setNowEpoch(2_000);
        $this->recordConstitutionHandler()->handle($this->constitutionCommand('s1', 's2', 's3'));

        $this->assertCount(0, $this->protocol->eventEntries(), 'Q-2: never a second constitution fact.');
        $this->assertSame($existing, $this->committees->find($this->electionId()), 'The existing committee IS the result — constitution happens ONCE (EM-GOV-026/056).');
    }

    /** A-5 taxonomy: a constitution request refused by adopted rule EM-GOV-033 (size < 3) is a MATERIAL refusal — recorded with its reason; no aggregate exists. */
    public function test_a_too_small_constitution_is_a_recorded_refusal(): void
    {
        $this->instants->setNowEpoch(1_000);
        $this->toleratingDomainRefusal(fn () => $this->recordConstitutionHandler()->handle($this->constitutionCommand('s1', 's2')));

        $this->assertNull($this->committees->find($this->electionId()), 'G-4: the handler cannot bypass the domain — no committee exists.');
        $this->assertCount(0, $this->protocol->eventEntries(), 'Q-3: a refused command appends no fact.');
        $this->assertCount(1, $this->protocol->refusals(), 'The refusal is recorded (F-PROTO-1 property 6).');
        $this->assertSame(
            CommitteeTooSmall::withSize(2)->getMessage(),
            $this->protocol->refusals()[0]->reason,
            'The refusal carries the EM-GOV-033 business reason.'
        );
    }
}
