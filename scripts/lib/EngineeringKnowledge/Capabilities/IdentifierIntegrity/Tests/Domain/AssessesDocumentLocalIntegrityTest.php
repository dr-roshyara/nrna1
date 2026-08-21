<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\AssessesDocumentLocalIntegrity;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionIdentifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionSequence;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;

/**
 * S1 — the domain service that applies DP-1 to a document-local register.
 *
 * DP-1 (CAP-001): "Every identifier shall be unique within its register(ns), and
 * checked before minting." Here the register is THE DOCUMENT and the identifiers are
 * its numbered section headings. The two defect classes this service detects are the
 * two halves of the historical DI-1 finding (AMD4, `0a2fa71d`):
 *   1. duplicate section identifiers   — `## 4.1` twice, `## 4.2` twice
 *   2. non-monotonic section ordering  — `4.1 · 4.3 · 4.4 · 4.2 · 4.0 · 4.1 · 4.2`
 *
 * ⛔ This service owns EXECUTION only — DP-1 is the catalogue's policy, not this
 *    class's (PA: "Capabilities must execute policies. Capabilities must never own
 *    policies."). It creates no identifier, no register, no numbering scheme.
 *
 * Fail-closed (D-2): a document with NO numbered headings cannot be evaluated, so it
 * returns INCONCLUSIVE, never PASS — absence of evidence is not PASS.
 */
final class AssessesDocumentLocalIntegrityTest extends TestCase
{
    /** @param list<array{0: string, 1: int}> $entries [number, line] in document order */
    private function sequence(array $entries): DocumentSectionSequence
    {
        return DocumentSectionSequence::fromIdentifiers(
            array_map(
                static fn (array $e): DocumentSectionIdentifier =>
                    DocumentSectionIdentifier::fromNumberLine($e[0], $e[1]),
                $entries,
            ),
        );
    }

    private function assessor(): AssessesDocumentLocalIntegrity
    {
        return new AssessesDocumentLocalIntegrity();
    }

    /** The AMD4 shape, with the real line numbers from `0a2fa71d`. */
    public function test_duplicate_section_identifiers_are_fail(): void
    {
        $result = $this->assessor()->validate(
            $this->sequence([
                ['4.1', 349],
                ['4.2', 404],
                ['4.3', 390],
                ['4.4', 398],
                ['4.0', 430],
                ['4.1', 436],
                ['4.2', 442],
            ]),
            'fixture',
        );

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertFalse($result->isClean());
        self::assertStringContainsString('4.1', $result->evidence());
        self::assertStringContainsString('4.2', $result->evidence());
        self::assertStringContainsString('349', $result->evidence());
        self::assertStringContainsString('436', $result->evidence());
        self::assertStringContainsString('404', $result->evidence());
        self::assertStringContainsString('442', $result->evidence());
    }

    public function test_non_monotonic_section_order_is_fail(): void
    {
        $result = $this->assessor()->validate(
            $this->sequence([
                ['4.1', 349],
                ['4.3', 390],
                ['4.4', 398],
                ['4.2', 404],
                ['4.0', 430],
                ['4.1', 436],
                ['4.2', 442],
            ]),
            'fixture',
        );

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertStringContainsString('non-monotonic', $result->evidence());
        self::assertStringContainsString('4.4', $result->evidence());
        self::assertStringContainsString('4.2', $result->evidence());
    }

    /** The repaired shape: `0a2fa71d` DI-1 was fixed by AMD5; AMD6 keeps it fixed. */
    public function test_unique_monotonic_sequence_is_pass(): void
    {
        $result = $this->assessor()->validate(
            $this->sequence([
                ['4.0', 30],
                ['4.1', 31],
                ['4.2', 32],
                ['4.3', 33],
                ['4.4', 34],
                ['4.5', 35],
                ['4.6', 36],
                ['4.7', 37],
            ]),
            'fixture',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertTrue($result->isClean());
    }

    /** A parent heading precedes its children — `## 4` then `## 4.0` is correct order. */
    public function test_prefix_hierarchy_is_monotonic(): void
    {
        $result = $this->assessor()->validate(
            $this->sequence([
                ['4', 1],
                ['4.0', 2],
                ['4.1', 3],
            ]),
            'fixture',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
    }

    /** Returning to a parent after a child is a violation: 4.1 … then 4. */
    public function test_descent_into_a_parent_is_a_violation(): void
    {
        $result = $this->assessor()->validate(
            $this->sequence([
                ['4', 1],
                ['4.1', 2],
                ['4', 3],
            ]),
            'fixture',
        );

        self::assertSame(Verdict::FAIL, $result->verdict());
    }

    /** D-2 / fail-closed: nothing to evaluate must be INCONCLUSIVE, never PASS. */
    public function test_a_document_with_no_numbered_headings_is_inconclusive(): void
    {
        $result = $this->assessor()->validate(
            $this->sequence([]),
            'fixture',
        );

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    /** DP-5 / AP-8: every outcome states what it checked. */
    public function test_every_verdict_carries_evidence(): void
    {
        $assessor = $this->assessor();

        $outcomes = [
            $assessor->validate($this->sequence([['4.1', 1], ['4.1', 2]]), 'fixture'),
            $assessor->validate($this->sequence([['4.4', 1], ['4.2', 2]]), 'fixture'),
            $assessor->validate($this->sequence([['4.0', 1], ['4.1', 2]]), 'fixture'),
            $assessor->validate($this->sequence([]), 'fixture'),
        ];

        foreach ($outcomes as $outcome) {
            self::assertNotSame('', $outcome->evidence(), "no evidence for {$outcome->verdict()->value}");
        }
    }
}
