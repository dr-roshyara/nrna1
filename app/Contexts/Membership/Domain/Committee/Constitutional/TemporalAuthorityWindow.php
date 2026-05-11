<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

final readonly class TemporalAuthorityWindow
{
    public function __construct(
        public \DateTimeImmutable $validFrom,
        public ?\DateTimeImmutable $validUntil,
    ) {
        if ($validUntil !== null && $validUntil < $validFrom) {
            throw new \InvalidArgumentException(
                'validUntil cannot be earlier than validFrom'
            );
        }
    }

    public function stateAt(\DateTimeImmutable $at): TemporalWindowState
    {
        if ($at < $this->validFrom) {
            return TemporalWindowState::PENDING;
        }

        if ($this->validUntil !== null && $at > $this->validUntil) {
            return TemporalWindowState::EXPIRED;
        }

        return TemporalWindowState::ACTIVE;
    }

    public function isActiveAt(\DateTimeImmutable $at): bool
    {
        return $this->stateAt($at) === TemporalWindowState::ACTIVE;
    }

    public function isExpiredAt(\DateTimeImmutable $at): bool
    {
        return $this->stateAt($at) === TemporalWindowState::EXPIRED;
    }

    public function isPendingAt(\DateTimeImmutable $at): bool
    {
        return $this->stateAt($at) === TemporalWindowState::PENDING;
    }
}
