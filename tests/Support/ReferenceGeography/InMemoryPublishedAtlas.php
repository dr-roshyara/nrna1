<?php

declare(strict_types=1);

namespace Tests\Support\ReferenceGeography;

use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\PublishedAtlas;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;

/** TEST ONLY: the published atlas at p₀, from a list of published claims (the PostgreSQL adapter is G-4). */
final class InMemoryPublishedAtlas implements PublishedAtlas
{
    /** @param list<PublishedClaim> $published */
    public function __construct(private readonly int $p0, private readonly array $published)
    {
    }

    public function p0(): int
    {
        return $this->p0;
    }

    public function history(SlotKey $slot): SlotHistory
    {
        $h = SlotHistory::for($slot);
        foreach ($this->published as $p) {
            if ($p->position <= $this->p0 && $p->claim->slot->equals($slot)) {
                $h = $h->with($p->claim, $p->position);
            }
        }

        return $h;
    }

    public function familySlots(ClaimKind $kind, array $fixed, string $value, ValidInterval $interval): array
    {
        $slots = [];
        foreach ($this->published as $p) {
            $slot = $p->claim->slot;
            if ($p->position > $this->p0 || $slot->kind() !== $kind) {
                continue;
            }
            foreach ($fixed as $label => $expected) {
                if ($slot->component($label) !== $expected) {
                    continue 2;
                }
            }
            $slots[$slot->encoded()] = $slot;
        }
        $members = [];
        foreach ($slots as $slot) {
            if (BeforeImage::of($this->history($slot), $interval, $this->p0)->hadValue($value, $interval)) {
                $members[] = $slot;
            }
        }

        return $members;
    }
}
