<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Authority\ValueObjects;

enum DelegationType: string
{
    case AUTHORITY = 'AUTHORITY';
    case OVERRIDE  = 'OVERRIDE';
    case TEMPORARY = 'TEMPORARY';

    public function priority(): int
    {
        return match($this) {
            self::AUTHORITY => 80,
            self::OVERRIDE  => 70,
            self::TEMPORARY => 60,
        };
    }
}
