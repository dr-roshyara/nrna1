<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Epoch;

final readonly class InstitutionalEpoch
{
    public function __construct(
        public string $epochId,
        public string $name,
        public \DateTimeImmutable $startedAt,
        public ?\DateTimeImmutable $endedAt,
        public ?string $constitutionReference,
    ) {}

    public function isActiveAt(\DateTimeImmutable $at): bool
    {
        if ($at < $this->startedAt) {
            return false;
        }

        if ($this->endedAt !== null && $at > $this->endedAt) {
            return false;
        }

        return true;
    }

    public function isOpen(): bool
    {
        return $this->endedAt === null;
    }
}
