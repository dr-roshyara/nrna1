<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The published atlas as known at one position p₀ (v3.11 §5). A port: the PostgreSQL adapter is G-4.
 * The validator never receives this directly; it reads only through DependencyRecordingReader (§5.2).
 */
interface PublishedAtlas
{
    public function p0(): int;

    /** The published claims of one slot, at positions ≤ p₀. */
    public function history(SlotKey $slot): SlotHistory;

    /**
     * The slots of a family (one kind, fixed key components) whose effective value at p₀ equals $value somewhere
     * within the interval.
     *
     * @param array<string, string> $fixed
     * @return list<SlotKey>
     */
    public function familySlots(ClaimKind $kind, array $fixed, string $value, ValidInterval $interval): array;
}
