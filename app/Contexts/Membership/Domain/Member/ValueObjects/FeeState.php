<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Member\ValueObjects;

enum FeeState: string
{
    case UNPAID = 'unpaid';
    case PENDING = 'pending';
    case PAID = 'paid';
    case OVERDUE = 'overdue';
    case WAIVED = 'waived';

    public function isPaid(): bool
    {
        return $this === self::PAID || $this === self::WAIVED;
    }

    public function isUnpaid(): bool
    {
        return $this === self::UNPAID || $this === self::PENDING || $this === self::OVERDUE;
    }

    public static function default(): self
    {
        return self::UNPAID;
    }
}
