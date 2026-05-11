<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Timeline;

final class PersistedAtSequencePolicy implements TimelineSequencePolicy
{
    public function compare(\DateTimeImmutable $a, \DateTimeImmutable $b): int
    {
        if ($a === $b || $a->format('c') === $b->format('c')) {
            return 0;
        }

        return $a < $b ? -1 : 1;
    }
}
