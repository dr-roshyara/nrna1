<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Approval\ValueObjects;

enum ApprovalStatus: string
{
    case PENDING  = 'PENDING';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case EXPIRED  = 'EXPIRED';

    public function isTerminal(): bool
    {
        return match($this) {
            self::PENDING  => false,
            self::APPROVED, self::REJECTED, self::EXPIRED => true,
        };
    }
}
