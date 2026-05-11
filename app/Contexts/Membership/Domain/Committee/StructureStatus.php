<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

enum StructureStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case DEPRECATED = 'deprecated';

    public function isDraft(): bool
    {
        return $this === self::DRAFT;
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isDeprecated(): bool
    {
        return $this === self::DEPRECATED;
    }
}
