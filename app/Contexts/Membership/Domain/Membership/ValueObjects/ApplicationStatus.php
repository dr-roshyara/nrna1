<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\ValueObjects;

enum ApplicationStatus: string
{
    case SUBMITTED = 'submitted';
    case UNDER_REVIEW = 'under_review';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function canTransitionTo(self $newStatus): bool
    {
        return match ($this) {
            self::SUBMITTED => $newStatus === self::UNDER_REVIEW || $newStatus === self::APPROVED || $newStatus === self::REJECTED,
            self::UNDER_REVIEW => $newStatus === self::APPROVED || $newStatus === self::REJECTED,
            self::APPROVED => false,
            self::REJECTED => false,
        };
    }

    public function equals(self $other): bool
    {
        return $this === $other;
    }

    public function isActive(): bool
    {
        return $this === self::SUBMITTED || $this === self::UNDER_REVIEW;
    }
}
