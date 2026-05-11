<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Graph;

enum DelegationType: string
{
    case AUTHORITY = 'authority';
    case OVERRIDE = 'override';
    case TEMPORARY = 'temporary';

    public function authorityWeight(): int
    {
        return match ($this) {
            self::AUTHORITY => 80,
            self::OVERRIDE => 90,
            self::TEMPORARY => 70,
        };
    }
}
