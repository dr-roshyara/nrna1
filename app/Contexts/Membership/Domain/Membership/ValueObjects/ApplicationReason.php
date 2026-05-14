<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\ValueObjects;

enum ApplicationReason: string
{
    case RESIDENCE = 'residence';
    case EXCEPTION = 'exception';
    case MANUAL = 'manual';

    public function equals(self $other): bool
    {
        return $this === $other;
    }
}
