<?php

declare(strict_types=1);

namespace App\Domain\Voting\ValueObject;

final readonly class QuorumDefinition
{
    private int $threshold;

    public function __construct(int $threshold)
    {
        if ($threshold <= 0) {
            throw new \InvalidArgumentException('Quorum threshold must be positive');
        }

        $this->threshold = $threshold;
    }

    public function threshold(): int
    {
        return $this->threshold;
    }

    public function isMet(int $participationCount): bool
    {
        return $participationCount >= $this->threshold;
    }
}
