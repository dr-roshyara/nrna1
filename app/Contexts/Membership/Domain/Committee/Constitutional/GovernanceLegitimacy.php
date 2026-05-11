<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

enum GovernanceLegitimacy: string
{
    case LEGITIMATE = 'legitimate';
    case EXPIRED    = 'expired';
    case PENDING    = 'pending';
    case SUSPENDED  = 'suspended';
    case EMERGENCY  = 'emergency';
    case CARETAKER  = 'caretaker';
    case REVOKED    = 'revoked';

    public function isValid(): bool
    {
        return match ($this) {
            self::LEGITIMATE, self::EMERGENCY, self::CARETAKER => true,
            default => false,
        };
    }

    public function isTemporary(): bool
    {
        return match ($this) {
            self::EMERGENCY, self::CARETAKER => true,
            default => false,
        };
    }
}
