<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Timeline;

interface TimelineSequencePolicy
{
    /**
     * Compare two timestamps for ordering.
     *
     * @return int -1 if $a comes before $b, 0 if equal, 1 if $a comes after $b
     */
    public function compare(\DateTimeImmutable $a, \DateTimeImmutable $b): int;
}
