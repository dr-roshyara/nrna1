<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine;

final readonly class DoctrineProvenance
{
    public function __construct(
        public string $approvedBy,
        public \DateTimeImmutable $approvedAt,
        public ?string $supersedesDoctrineId,
        public ?string $supersededByDoctrineId,
        public string $reason,
    ) {}

    public function supersedes(): bool
    {
        return $this->supersedesDoctrineId !== null;
    }

    public function isSuperseded(): bool
    {
        return $this->supersededByDoctrineId !== null;
    }
}
