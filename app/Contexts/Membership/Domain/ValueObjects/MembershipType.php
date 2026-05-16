<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

final readonly class MembershipType
{
    public function __construct(
        private MembershipTypeId $id,
        private bool $isActive,
    ) {}

    public function id(): MembershipTypeId
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
