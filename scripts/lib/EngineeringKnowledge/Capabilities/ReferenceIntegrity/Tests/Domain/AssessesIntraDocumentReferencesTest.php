<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionIdentifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionSequence;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\AssessesIntraDocumentReferences;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\ReferenceIntegrityContents;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\SectionReference;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\StepDefinition;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\StepReference;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;

/**
 * S2 — the domain service that applies DP-4 to a document's intra-document references.
 *
 * DP-4 (CAP-004): "A reference shall resolve to an existing target, or be classified as
 * evidence — never repaired on a guess." The discriminator is DP-4's OWN escape hatch:
 *   a reference whose target exists exactly once   → RESOLVED
 *   a reference whose target exists more than once → AMBIGUOUS / FAIL (AMD4 duplicates)
 *   a reference with no local target               → CLASSIFIED AS EVIDENCE, NOT-CHECKED
 *                                                    (cross-document `its §13` — never a
 *                                                    defect, so AMD5/AMD6 stay quiet)
 * A step reference resolves iff the mandated block defines the step; the AMD5 `step 5`
 * references dangle against a block defining `1 · 2 · 3 · 4` (DI-5).
 *
 * ⛔ This service owns EXECUTION only — DP-4 is the catalogue's policy, not this class's
 *    (PA: "Capabilities must execute policies. Capabilities must never own policies.").
 *
 * Fail-closed (D-2): nothing to evaluate is INCONCLUSIVE, never PASS — including the
 * case where EVERY § reference is classified as evidence and none resolves within the
 * document.
 */
final class AssessesIntraDocumentReferencesTest extends TestCase
{
    /**
     * @param  list<array{0: string, 1: int}>  $headingEntries  [number, line]
     * @param  list<array{0: string, 1: int}>  $sectionRefs     [number, line]
     * @param  list<array{0: string, 1: int}>  $stepRefs        [step, line]
     * @param  list<array{0: string, 1: int}>  $stepDefs        [step, line]
     */
    private function contents(
        array $headingEntries = [],
        array $sectionRefs = [],
        array $stepRefs = [],
        array $stepDefs = [],
    ): ReferenceIntegrityContents {
        $headings = DocumentSectionSequence::fromIdentifiers(array_map(
            static fn (array $e): DocumentSectionIdentifier =>
                DocumentSectionIdentifier::fromNumberLine($e[0], $e[1]),
            $headingEntries,
        ));

        return ReferenceIntegrityContents::of(
            $headings,
            array_map(static fn (array $e): SectionReference => SectionReference::fromNumberLine($e[0], $e[1]), $sectionRefs),
            array_map(static fn (array $e): StepReference => StepReference::fromStepLine($e[0], $e[1]), $stepRefs),
            array_map(static fn (array $e): StepDefinition => StepDefinition::fromStepLine($e[0], $e[1]), $stepDefs),
        );
    }

    private function assessor(): AssessesIntraDocumentReferences
    {
        return new AssessesIntraDocumentReferences();
    }

    /** ⛔ THE S2 RED BOUNDARY (plan §5, verbatim): tests assert MISSING for a `step 5`
     *  reference against a block defining `1..4` — the AMD5 DI-5 shape. */
    public function test_dangling_step_reference_is_fail(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                stepRefs: [['5', 546], ['5', 552]],
                stepDefs: [['1', 468], ['2', 469], ['3', 470], ['4', 471]],
            ),
            'fixture',
        );

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertFalse($result->isClean());
        self::assertStringContainsString('step 5', $result->evidence());
        self::assertStringContainsString('1 · 2 · 3 · 4', $result->evidence());
        self::assertStringContainsString('546', $result->evidence());
    }

    /** The AMD4 shape: `## 4.1` twice makes every `§4.1` reference ambiguous. */
    public function test_ambiguous_section_reference_is_fail(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                headingEntries: [['4.1', 349], ['4.2', 404], ['4.1', 436]],
                sectionRefs: [['4.1', 349], ['4.1', 436]],
            ),
            'fixture',
        );

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertStringContainsString('ambiguous', $result->evidence());
        self::assertStringContainsString('§4.1', $result->evidence());
        self::assertStringContainsString('349, 436', $result->evidence());
    }

    public function test_reference_resolving_to_a_single_heading_is_pass(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                headingEntries: [['4.1', 349]],
                sectionRefs: [['4.1', 349]],
            ),
            'fixture',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertTrue($result->isClean());
    }

    /** The AMD5/AMD6 `its §13` traceability shape: zero local headings is DP-4's
     *  "classified as evidence" branch — NOT a defect, and it never breaks a PASS. */
    public function test_reference_with_no_local_heading_is_classified_not_a_defect(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                headingEntries: [['4', 10], ['4.1', 11]],
                sectionRefs: [['4.1', 11], ['18', 150]],
            ),
            'fixture',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertStringContainsString('classified as cross-document evidence', $result->evidence());
    }

    /** D-2 / fail-closed: when EVERY § reference is classified as evidence and nothing
     *  resolves within the document, the checker validated nothing → INCONCLUSIVE. */
    public function test_all_references_classified_is_inconclusive(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                headingEntries: [['4', 10]],
                sectionRefs: [['18', 150], ['13', 160]],
            ),
            'fixture',
        );

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    /** D-2 / fail-closed: nothing to evaluate must be INCONCLUSIVE, never PASS. */
    public function test_a_document_with_no_references_is_inconclusive(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(headingEntries: [['4', 10]]),
            'fixture',
        );

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    /** A step reference resolves when the mandated block defines it (AMD6's quiet row). */
    public function test_step_reference_resolving_against_mandated_block_is_pass(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                sectionRefs: [['4', 10]],
                stepRefs: [['3', 851], ['5', 1041]],
                stepDefs: [['1', 600], ['1b', 601], ['2', 602], ['3', 603], ['4', 604], ['5', 605]],
            ),
            'fixture',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertStringContainsString('2 step reference(s) resolve', $result->evidence());
    }

    /** The AMD4 side is quiet on steps: its only `step 3` references resolve. */
    public function test_step_reference_can_resolve_even_without_any_section_heading(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                stepRefs: [['3', 398]],
                stepDefs: [['1', 412], ['2', 413], ['3', 414], ['4', 415]],
            ),
            'fixture',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
    }

    /** DP-5 / AP-8: every outcome states what it checked. */
    public function test_every_verdict_carries_evidence(): void
    {
        $assessor = $this->assessor();

        $outcomes = [
            $assessor->validate($this->contents(stepRefs: [['5', 1]], stepDefs: [['1', 1]]), 'fixture'),
            $assessor->validate($this->contents(headingEntries: [['4.1', 1], ['4.1', 2]], sectionRefs: [['4.1', 1]]), 'fixture'),
            $assessor->validate($this->contents(headingEntries: [['4.1', 1]], sectionRefs: [['4.1', 1]]), 'fixture'),
            $assessor->validate($this->contents(headingEntries: [['4', 1]]), 'fixture'),
        ];

        foreach ($outcomes as $outcome) {
            self::assertNotSame('', $outcome->evidence(), "no evidence for {$outcome->verdict()->value}");
        }
    }
}
