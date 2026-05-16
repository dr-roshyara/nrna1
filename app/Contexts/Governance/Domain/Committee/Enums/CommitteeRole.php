<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee\Enums;

enum CommitteeRole: string
{
    case CHAIR = 'chair';
    case DEPUTY = 'deputy';
    case MEMBER = 'member';
    case OBSERVER = 'observer';

    public function label(): string
    {
        return match ($this) {
            self::CHAIR => 'Chair',
            self::DEPUTY => 'Deputy',
            self::MEMBER => 'Member',
            self::OBSERVER => 'Observer',
        };
    }

    public function isLeadership(): bool
    {
        return in_array($this, [self::CHAIR, self::DEPUTY]);
    }
}
