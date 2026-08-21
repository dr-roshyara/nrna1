<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\AssessesVocabularyIntegrity;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\ConfusableIdentifier;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\TermOccurrence;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\VocabularyContents;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;

/**
 * S3 — the domain service that applies DP-3 to a document's vocabulary.
 *
 * DP-3 (CAP-003): "A governed term shall carry one meaning per context; where overloaded,
 * it shall be qualified." The discriminator is LIVE usage WITHOUT qualification:
 *   a retired term used live                 → defect (DI-2 — AMD4's `Phase 2b`)
 *   a retired term CITED (quoted, `§N Phase 2b`,
 *     `Traceability (`)                       → qualified, not a defect
 *   a confusable glyph pair (`CASE B`/`CASE β`)
 *     that collides and is NOT declared       → defect (DI-7 — AMD5's case)
 *   the same pair when the document carries
 *     its own collision finding (`DI-7`)      → qualified, not a defect (AMD6's quiet row)
 *
 * ⛔ This service owns EXECUTION only — DP-3 is the catalogue's policy, not this class's
 *    (PA: "Capabilities must execute policies. Capabilities must never own policies.").
 *
 * Fail-closed (D-2): nothing to evaluate is INCONCLUSIVE, never PASS.
 */
final class AssessesVocabularyIntegrityTest extends TestCase
{
    private function assessor(): AssessesVocabularyIntegrity
    {
        return new AssessesVocabularyIntegrity();
    }

    /**
     * @param  list<TermOccurrence>           $stale
     * @param  list<ConfusableIdentifier>     $identifiers
     */
    private function contents(
        array $stale = [],
        array $identifiers = [],
        bool $collisionDeclared = false,
    ): VocabularyContents {
        return VocabularyContents::of($stale, $identifiers, $collisionDeclared);
    }

    /** ⛔ THE S3 RED BOUNDARY (plan §5, verbatim): tests assert an unqualified-homonym
     *  verdict on a fixture carrying BOTH tokens — `CASE B` and `CASE β`, no declaration. */
    public function test_unqualified_confusable_pair_is_fail(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                identifiers: [
                    ConfusableIdentifier::of('CASE', 'B', 1),
                    ConfusableIdentifier::of('CASE', 'β', 2),
                ],
            ),
            'fixture.md',
        );

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertFalse($result->isClean());
        self::assertStringContainsString("'CASE B'", $result->evidence());
        self::assertStringContainsString("'CASE β'", $result->evidence());
        self::assertStringContainsString('no declaration', $result->evidence());
    }

    /** The AMD6 quiet shape: the same pair, but the document DECLARES the collision. */
    public function test_confusable_pair_with_a_declaration_is_pass(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                identifiers: [
                    ConfusableIdentifier::of('CASE', 'B', 1),
                    ConfusableIdentifier::of('CASE', 'β', 2),
                ],
                collisionDeclared: true,
            ),
            'fixture.md',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertTrue($result->isClean());
    }

    /** A pair in DIFFERENT families never collides (`CASE β` vs `STEP B`). */
    public function test_identifiers_in_different_families_do_not_collide(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                identifiers: [
                    ConfusableIdentifier::of('STEP', 'B', 1),
                    ConfusableIdentifier::of('CASE', 'β', 2),
                ],
            ),
            'fixture.md',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
    }

    /** AMD4's confusable side: only Latin identifiers, no Greek — no collision. */
    public function test_latin_only_identifiers_are_no_collision(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                identifiers: [
                    ConfusableIdentifier::of('CASE', 'A', 10),
                    ConfusableIdentifier::of('CASE', 'B', 12),
                ],
            ),
            'fixture.md',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertStringContainsString('no confusable collision', $result->evidence());
    }

    /** The AMD4 DI-2 shape: a retired term used LIVE without qualification. */
    public function test_live_retired_term_is_fail(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                stale: [TermOccurrence::of('Phase 2b', 469, false)],
            ),
            'fixture.md',
        );

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertStringContainsString('Phase 2b', $result->evidence());
        self::assertStringContainsString('469', $result->evidence());
    }

    /** A CITED occurrence is qualified — the AMD5/AMD6 quiet shape on DI-2. */
    public function test_cited_retired_term_is_not_a_defect(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                stale: [
                    TermOccurrence::of('Phase 2b', 123, true),
                    TermOccurrence::of('Phase 2b', 581, true),
                ],
            ),
            'fixture.md',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertStringContainsString('2 cited', $result->evidence());
    }

    /** D-2 / fail-closed: nothing to evaluate must be INCONCLUSIVE, never PASS. */
    public function test_a_document_with_no_vocabulary_is_inconclusive(): void
    {
        $result = $this->assessor()->validate($this->contents(), 'fixture.md');

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    /** DP-5 / AP-8: every outcome states what it checked. */
    public function test_every_verdict_carries_evidence(): void
    {
        $assessor = $this->assessor();

        $outcomes = [
            $assessor->validate(
                $this->contents(identifiers: [
                    ConfusableIdentifier::of('CASE', 'B', 1),
                    ConfusableIdentifier::of('CASE', 'β', 2),
                ]),
                'fixture.md',
            ),
            $assessor->validate(
                $this->contents(stale: [TermOccurrence::of('Phase 2b', 5, false)]),
                'fixture.md',
            ),
            $assessor->validate(
                $this->contents(
                    stale: [TermOccurrence::of('Phase 2b', 5, true)],
                    identifiers: [ConfusableIdentifier::of('CASE', 'A', 1)],
                ),
                'fixture.md',
            ),
            $assessor->validate($this->contents(), 'fixture.md'),
        ];

        foreach ($outcomes as $outcome) {
            self::assertNotSame('', $outcome->evidence(), "no evidence for {$outcome->verdict()->value}");
        }
    }
}
