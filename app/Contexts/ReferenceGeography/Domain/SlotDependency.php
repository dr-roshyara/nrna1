<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** "This slot over this interval" (v3.11 §5.1): invalidated by ANY claim in the slot over an overlapping interval. */
final readonly class SlotDependency implements Dependency
{
    public function __construct(public SlotKey $slot, public ValidInterval $interval)
    {
    }

    public function isInvalidatedBy(PublishedClaim $published): bool
    {
        return $published->claim->slot->equals($this->slot) && $published->claim->interval->overlaps($this->interval);
    }

    public function describe(): string
    {
        return 'slot|' . $this->slot->encoded() . '|' . $this->interval->from->value() . '|' . ($this->interval->to?->value() ?? '');
    }
}
