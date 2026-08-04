<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\Identifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\IdentifierSeries;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\AssessesIdentifierIntegrity;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\SeriesContents;
use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;

/**
 * CAP-001 — Engineering Knowledge Validation: Identifier Integrity.
 * Domain keystones: the four emittable verdicts.
 *
 * Constraints encoded (each cited, none invented):
 *   PMR-10  an identifier must be checked for collision BEFORE it is minted
 *   AP-4    per-kind, register(ns)-scoped identity; collisions prevented by register
 *           discipline, NOT by a central authority → contents are an INPUT
 *   AP-7    criteria used here, owned elsewhere
 *   DR-4    criteria are read-only
 *   AP-8    only the closed verdict vocabulary crosses
 *   AP-1    a verdict is an OUTPUT, never a decision
 *   M4      the register(ns) is the namespace unit
 */
final class AssessesIdentifierIntegrityTest extends TestCase
{
    private function governed(string $prefix, array $minted, array $cited = []): SeriesContents
    {
        return SeriesContents::governed(
            new IdentifierSeries($prefix),
            array_map(static fn (string $v): Identifier => Identifier::fromString($v), $minted),
            array_map(static fn (string $v): Identifier => Identifier::fromString($v), $cited),
        );
    }

    public function test_identifier_free_in_its_series_is_pass(): void
    {
        $result = (new AssessesIdentifierIntegrity())->validate(
            Identifier::fromString('R-72'),
            $this->governed('R', ['R-64', 'R-65', 'R-66']),
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertTrue($result->isClean());
    }

    public function test_identifier_already_minted_is_fail(): void
    {
        $result = (new AssessesIdentifierIntegrity())->validate(
            Identifier::fromString('R-65'),
            $this->governed('R', ['R-64', 'R-65', 'R-66']),
        );

        self::assertSame(Verdict::FAIL, $result->verdict());
        self::assertStringContainsString('R-65', $result->evidence());
        self::assertFalse($result->isClean());
    }

    /**
     * The REALIZED third escape: R-65..R-71 circulated as unminted governance conclusions
     * while R-65/R-66 were minted for slice 7C (2026-08-01/02).
     */
    public function test_identifier_cited_but_unminted_is_warn(): void
    {
        $result = (new AssessesIdentifierIntegrity())->validate(
            Identifier::fromString('R-70'),
            $this->governed('R', ['R-64', 'R-65', 'R-66'], ['R-70', 'R-71']),
        );

        self::assertSame(Verdict::WARN, $result->verdict());
    }

    public function test_a_minted_identifier_that_is_also_cited_is_fail_not_warn(): void
    {
        $result = (new AssessesIdentifierIntegrity())->validate(
            Identifier::fromString('R-65'),
            $this->governed('R', ['R-65'], ['R-65']),
        );

        self::assertSame(Verdict::FAIL, $result->verdict());
    }

    public function test_ungoverned_series_is_inconclusive(): void
    {
        $result = (new AssessesIdentifierIntegrity())->validate(
            Identifier::fromString('ZZ-1'),
            SeriesContents::ungoverned(new IdentifierSeries('ZZ')),
        );

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
    }

    /** Fail-closed: absence of evidence is never PASS. */
    public function test_ungoverned_series_never_returns_pass(): void
    {
        $result = (new AssessesIdentifierIntegrity())->validate(
            Identifier::fromString('ZZ-999'),
            SeriesContents::ungoverned(new IdentifierSeries('ZZ')),
        );

        self::assertNotSame(Verdict::PASS, $result->verdict());
    }

    public function test_emittable_vocabulary_is_exactly_the_four_mechanical_verdicts(): void
    {
        self::assertSame(
            ['PASS', 'FAIL', 'WARN', 'INCONCLUSIVE'],
            array_map(static fn (Verdict $v): string => $v->value, Verdict::emittable()),
        );
    }

    /** AP-8 / DR-8: review-and-certification verdicts must not be emitted mechanically. */
    public function test_review_only_verdicts_cannot_be_emitted(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Assessment::of(Verdict::CERTIFIED, 'should be impossible');
    }

    public function test_review_only_verdicts_are_absent_from_the_emittable_set(): void
    {
        $emittable = array_map(static fn (Verdict $v): string => $v->value, Verdict::emittable());

        self::assertNotContains('PASS AFTER CORRECTION', $emittable);
        self::assertNotContains('EMERGENT', $emittable);
        self::assertNotContains('CERTIFIED', $emittable);
    }

    /** M4: the register(ns) is the namespace unit — cross-series validation is a category error. */
    public function test_identifier_from_a_different_series_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new AssessesIdentifierIntegrity())->validate(
            Identifier::fromString('ES-005'),
            $this->governed('R', ['R-64']),
        );
    }

    /** Every result carries evidence — an assessment states what it checked. */
    public function test_every_verdict_carries_evidence(): void
    {
        $service = new AssessesIdentifierIntegrity();

        foreach (['R-72', 'R-65', 'R-70'] as $candidate) {
            $result = $service->validate(
                Identifier::fromString($candidate),
                $this->governed('R', ['R-65'], ['R-70']),
            );

            self::assertNotSame('', $result->evidence(), "no evidence for {$candidate}");
        }
    }
}
