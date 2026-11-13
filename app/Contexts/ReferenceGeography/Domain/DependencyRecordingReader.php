<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The validator's only view of the published atlas (v3.11 §5.2 "the validator may read published state only through
 * dependency-recording queries"). Every read records its dependency at p₀; repeated reads are recorded once.
 */
final class DependencyRecordingReader
{
    /** @var array<string, Dependency> by canonical description */
    private array $recorded = [];

    public function __construct(private readonly PublishedAtlas $atlas)
    {
    }

    public function p0(): int
    {
        return $this->atlas->p0();
    }

    /** One slot over an interval: records a slot dependency. */
    public function history(SlotKey $slot, ValidInterval $interval): SlotHistory
    {
        $this->record(new SlotDependency($slot, $interval));

        return $this->atlas->history($slot);
    }

    /**
     * A family over an interval: records a family dependency (gains and losses).
     *
     * @param array<string, string> $fixed
     * @return list<SlotKey>
     */
    public function family(ClaimKind $kind, array $fixed, string $value, ValidInterval $interval): array
    {
        $this->record(FamilyDependency::of($kind, $fixed, $value, $interval));

        return $this->atlas->familySlots($kind, $fixed, $value, $interval);
    }

    public function dependencies(): DependencySet
    {
        return new DependencySet($this->atlas->p0(), array_values($this->recorded));
    }

    private function record(Dependency $dependency): void
    {
        $this->recorded[$dependency->describe()] = $dependency;
    }
}
