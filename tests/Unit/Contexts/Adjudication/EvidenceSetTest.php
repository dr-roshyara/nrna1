<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication;

use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use PHPUnit\Framework\TestCase;

/**
 * EvidenceSet — the considered-evidence-set carrier (WP-1, ADR-T22).
 *
 * Enters through artifact №5's frozen deferral path; its parent invariant is
 * INV-4's rider (R-4-expanded): the set of evidence considered at issuance is
 * fixed at the act of issuing and can never diverge from what is announced.
 * A fixation record fails loud: no empty set, no blank refs, no duplicates.
 *
 * Traceability: ADR-T22 · EPIC-004F (deferral) · EPIC-004E INV-4 rider ·
 * EPIC-004K §11 · roadmap WP-1.
 */
final class EvidenceSetTest extends TestCase
{
    public function test_carries_the_refs_it_was_fixed_with(): void
    {
        $set = EvidenceSet::fromRefs('envelope-sha256-abc', 'envelope-sha256-def');

        $this->assertSame(['envelope-sha256-abc', 'envelope-sha256-def'], $set->toArray());
        $this->assertSame(2, $set->count());
    }

    public function test_rejects_an_empty_set(): void
    {
        // A record of consideration that considered nothing is not a record.
        $this->expectException(\InvalidArgumentException::class);

        EvidenceSet::fromRefs();
    }

    public function test_rejects_blank_refs(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        EvidenceSet::fromRefs('envelope-sha256-abc', '  ');
    }

    public function test_rejects_duplicate_refs(): void
    {
        // A set; a duplicate signals an upstream defect — fail loud, never dedupe silently.
        $this->expectException(\InvalidArgumentException::class);

        EvidenceSet::fromRefs('envelope-sha256-abc', 'envelope-sha256-abc');
    }

    public function test_is_immutable_from_the_outside(): void
    {
        $set = EvidenceSet::fromRefs('envelope-sha256-abc');

        $exported = $set->toArray();
        $exported[] = 'tampered-ref';

        $this->assertSame(['envelope-sha256-abc'], $set->toArray());
    }
}
