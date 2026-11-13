<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;

/**
 * The read side's access to PUBLISHED claims only (G-5a). Every method is bounded by a position p ≤ the committed one;
 * positions ≤ p are committed, gap-free and immutable (G-4a), so separate calls at one p are consistent (PO-5).
 */
interface GeographyReadSource
{
    public function committedPosition(): int;

    /** The highest position whose recordedAt ≤ τ (0 if none). Only meaningful once closed through τ. */
    public function positionAtTime(string $tau): int;

    public function publication(int $position): ?PublicationRecord;

    /** @return list<ReferenceUnitId> units with any published existence claim at positions ≤ p */
    public function unitsWithExistence(int $p): array;

    public function slotHistory(SlotKey $slot, int $p): SlotHistory;

    /** @return list<SlotHistory> every published slot whose subject is the unit, at positions ≤ p */
    public function subjectSlots(ReferenceUnitId $unit, int $p): array;

    /** @return array<string, SlotHistory> DISPUTE histories on the unit's claims, keyed by target claim id */
    public function disputesForSubject(ReferenceUnitId $unit, int $p): array;

    /** @return list<SlotHistory> every published slot of the kind, at positions ≤ p (G-5b: names and codes) */
    public function slotsOfKind(ClaimKind $kind, int $p): array;

    /** @return list<ReferenceUnitId> units whose containment in the scheme ever named the parent, at positions ≤ p */
    public function containedIn(ReferenceUnitId $parent, string $scheme, int $p): array;
}
