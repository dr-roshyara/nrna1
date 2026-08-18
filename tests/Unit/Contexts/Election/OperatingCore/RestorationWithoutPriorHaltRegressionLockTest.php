<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCore;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Condition\ElectionOperationalStatus;
use App\Contexts\Election\Domain\OperatingCore\Condition\HaltedAtGate;
use App\Contexts\Election\Domain\OperatingCore\Condition\OperationalCondition;
use App\Contexts\Election\Domain\OperatingCore\Exception\ExpiryConsequencePreconditionNotMet;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Policy\ExpiryConsequence;
use App\Contexts\Election\Domain\OperatingCore\Policy\ResumptionTarget;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PolicyBinding;
use App\Contexts\Election\Domain\OperatingCore\Recovery\RecoveryProcess;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionNamedType;

/**
 * `EM-DOM-001` · `H-2` — the `w8` REGRESSION LOCK for decision `D2`.
 *
 * `D2` (PO/ARB, 2026-08-18), as a clarification of ADR-2 §6 and not an amendment:
 * *"Restoration and Resumption are distinct domain concepts. The w8 case represents
 * Restoration without a prior halt. It therefore has a known causal origin but has
 * no resumption target."*
 *
 * This test encodes exactly that meaning and nothing beyond it. Every assertion here
 * is satisfied by the frozen domain core as committed — ⚠️ **this is a REGRESSION
 * LOCK, not a RED**, and the `H-2` authorization record says so in terms: the `w8`
 * behaviour "ALREADY PASSES on the frozen domain", so a fail-by-absence RED is
 * impossible without inventing a semantic rule, which `D2` forbids.
 *
 * ⛔ WHAT THIS TEST DELIBERATELY DOES NOT ASSERT
 *  - it selects NO representation for the halt-absent path: no nullability rule, no
 *    sentinel, no `UnknownGate`, no new enum value, no replacement type (`D2` selects
 *    none, and `BND-3` is deferred by `D4`);
 *  - it makes no claim about `ElectionRestored`'s non-nullable `GateDesignation`
 *    (ADR-2 §5f — an open representational question, not a locked behaviour);
 *  - it asserts nothing about lifecycle PHASE (`BND-1`, deferred by `D3`), nothing
 *    about persistence or adapters (act C), and nothing about Application handlers
 *    (act D); the `w8` fixture is consulted only for the operational SHAPE it pins;
 *  - it does not claim the domain forbids obtaining a resumption target by other
 *    means. It pins the narrower executable fact: P-7 is the domain's only producer
 *    of one, and it admits no absent halt by signature.
 *
 * FAIL-FIRST EVIDENCE — mutation demonstration in a throwaway export, per the `H-2`
 * authorization record's sanctioned route (the Act-B step-④ export technique,
 * `67a09e11`). The working tree (`app/`, `tests/`, `phpunit.xml`, `composer.*`, its own
 * `vendor/`) was exported to a scratch directory outside the repository. The lock first
 * ran GREEN there — `OK (3 tests, 21 assertions)` — proving the export is self-contained.
 * Then, in the COPY ONLY, `ElectionOperationalStatus::restored()` was mutated to
 * FABRICATE a halt where none was retained (`$this->haltedAtGate ?? new HaltedAtGate(...)`)
 * — the exact fabrication ADR-2 §6(b) forbids — making the halt-absent path
 * indistinguishable from the halted one. Verbatim failure output inside that export:
 *
 *   FF.                                                                 3 / 3 (100%)
 *
 *   There were 2 failures:
 *
 *   1) Tests\Unit\Contexts\Election\OperatingCore\RestorationWithoutPriorHaltRegressionLockTest
 *      ::test_restoration_without_a_prior_halt_neither_acquires_nor_fabricates_a_halt
 *   D2: the w8 case is Restoration WITHOUT a prior halt; restoration must not manufacture one.
 *   Failed asserting that true is false.
 *
 *   2) Tests\Unit\Contexts\Election\OperatingCore\RestorationWithoutPriorHaltRegressionLockTest
 *      ::test_the_halt_absent_path_has_no_resumption_target_while_the_halted_path_still_has_one
 *   Nothing exists to hand to P-7: the halt-absent restoration has no resumption target.
 *   Failed asserting that App\Contexts\Election\Domain\OperatingCore\Condition\HaltedAtGate Object #543 (
 *       'gate' => App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation Enum #424 (First, 'first'),
 *       'haltedAt' => App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant Object #542 (
 *           'epochSeconds' => 0,
 *       ),
 *   ) is null.
 *
 *   FAILURES!
 *   Tests: 3, Assertions: 16, Failures: 2, Deprecations: 3.
 *
 * (The 3 deprecations are PHP 8.5 `SplObjectStorage` notices raised inside
 * `vendor/sebastian/recursion-context` while PHPUnit RENDERS the fabricated
 * `HaltedAtGate` into the failure message. They are an artifact of the mutant's
 * failure output, not of this lock: the unmutated run reports none.)
 *
 * The export was then DISCARDED. ⛔ Nothing under `app/` was touched: the frozen core
 * remains the Act-B baseline (`1f4b4c5f` plus one added file, `Port/RecordedOperationalStatus.php`).
 *
 * The unmutated tree passes all three — which is what a regression lock is for.
 *
 * Traceability: `D2` (verbatim, `2026-08-18-EM-DOM-001-decision-recording-surface.md`)
 * · ADR-2 §6(a)/(b)/(h), §5a, §5e · `H-2` authorization
 * (`2026-08-19-EM-DOM-H2-w8-regression-lock-authorization.md`) · `EM-GOV-059(a)/(b)/(c)`
 * · P-7 · Act-B baseline `1f4b4c5f` · `w8` pin
 * (`FillCommitteeSeatHandlerRedTest::test_w8_…`) · PBDIGIT-68.
 */
final class RestorationWithoutPriorHaltRegressionLockTest extends TestCase
{
    private function at(int $epoch): RecordedInstant
    {
        return RecordedInstant::fromEpochSeconds($epoch);
    }

    /**
     * `w8`'s operational shape: the election was never HALTED. Its gate became
     * Unachievable by vacancy arithmetic, so it became INOPERATIVE with no halt to
     * retain — and restoration must return it to Operative WITHOUT acquiring one.
     */
    public function test_restoration_without_a_prior_halt_neither_acquires_nor_fabricates_a_halt(): void
    {
        $neverHalted = ElectionOperationalStatus::operative();
        $this->assertFalse($neverHalted->isHalted());
        $this->assertNull($neverHalted->haltedAtGate());

        $inoperative = $neverHalted->becameInoperative();
        $this->assertSame(OperationalCondition::Inoperative, $inoperative->condition());
        $this->assertFalse(
            $inoperative->isHalted(),
            'EM-GOV-059(b) RETAINS the halt where one exists; on the halt-absent path there is nothing to retain and nothing is acquired.'
        );
        $this->assertNull($inoperative->haltedAtGate());

        $restored = $inoperative->restored();
        $this->assertSame(
            OperationalCondition::Operative,
            $restored->condition(),
            'EM-GOV-059(c): restoration returns the election to its prior condition.'
        );
        $this->assertFalse(
            $restored->isHalted(),
            'D2: the w8 case is Restoration WITHOUT a prior halt; restoration must not manufacture one.'
        );
        $this->assertNull(
            $restored->haltedAtGate(),
            'ADR-2 §6(b): a fabricated HaltedAtGate, a sentinel GateDesignation and an "unknown" value used merely to fill a field are each forbidden.'
        );
    }

    /**
     * `D2`'s consequence — "no resumption target" — and the discrimination it rests on.
     * P-7 `ResumptionTarget` is the domain's only producer of a resumption target and
     * its sole parameter is a NON-NULLABLE `HaltedAtGate`; a status whose
     * `haltedAtGate()` is null therefore has nothing to supply it, while the halted
     * path is unaffected. Restoration and Resumption stay distinct.
     */
    public function test_the_halt_absent_path_has_no_resumption_target_while_the_halted_path_still_has_one(): void
    {
        $parameters = (new ReflectionMethod(ResumptionTarget::class, 'resolve'))->getParameters();
        $this->assertCount(1, $parameters, 'P-7 resolves a resumption target from the halt fact, and from nothing else.');
        $parameterType = $parameters[0]->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $parameterType);
        $this->assertSame(HaltedAtGate::class, $parameterType->getName());
        $this->assertFalse(
            $parameterType->allowsNull(),
            'D2: "no resumption target" holds because P-7 admits no absent halt — never merely because a caller declines to ask.'
        );

        $haltAbsent = ElectionOperationalStatus::operative()->becameInoperative()->restored();
        $this->assertNull(
            $haltAbsent->haltedAtGate(),
            'Nothing exists to hand to P-7: the halt-absent restoration has no resumption target.'
        );

        $halted = ElectionOperationalStatus::operativeHalted(new HaltedAtGate(GateDesignation::Second, $this->at(1_000)))
            ->becameInoperative()
            ->restored();
        $this->assertTrue($halted->isHalted());
        $this->assertSame(
            GateDesignation::Second,
            ResumptionTarget::resolve($halted->haltedAtGate()),
            'EM-GOV-059(c): where a halt WAS retained, restoration returns to that unresolved gate.'
        );

        $this->assertNotSame(
            $halted->isHalted(),
            $haltAbsent->isHalted(),
            'D2: Restoration and Resumption are distinct. Were these two paths indistinguishable, w8 would silently acquire a resumption target no halt of its own justifies.'
        );
    }

    /**
     * `D2`'s other half — the halt-absent restoration RETAINS its known causal origin.
     * "No prior halt" is not "no `RecoveryProcess`": the `w8` pin seeds a
     * `CommitteeRestoration` period (ADR-2 §5a, corrected 2026-08-18). That origin is
     * known, and it is not interchangeable with halt provenance.
     */
    public function test_the_halt_absent_restoration_retains_its_known_causal_origin(): void
    {
        $electionId = ElectionId::fromString('election-1');

        $origin = RecoveryProcess::start(
            $electionId,
            PeriodKind::CommitteeRestoration,
            PolicyBinding::of('service-policy-v1', 20),
            $this->at(20),
        );

        $this->assertSame($electionId, $origin->electionId());
        $this->assertSame(
            PeriodKind::CommitteeRestoration,
            $origin->kind(),
            'D2: the w8 restoration has a KNOWN causal origin — a Committee-restoration period.'
        );
        $this->assertNotSame(
            PeriodKind::HaltedElectionRecovery,
            $origin->kind(),
            'No HaltedElectionRecovery period exists on this path, hence no halt-derived gate (ADR-2 §5a).'
        );

        // The two kinds are never merged (EM-GOV-059(a)): the halted-recovery consequence
        // refuses this origin on kind alone — even when a halt is supplied to it.
        $this->expectException(ExpiryConsequencePreconditionNotMet::class);
        ExpiryConsequence::onHaltedRecoveryExpiry(
            $electionId,
            new HaltedAtGate(GateDesignation::First, $this->at(0)),
            $origin,
            $this->at(100),
            false,
            OperationalCondition::Operative,
        );
    }
}
