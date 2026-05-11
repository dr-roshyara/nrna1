<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Services;

use App\Contexts\Membership\Domain\Committee\Context\GeographicScope;

final class GeographicScopeRelationService
{
    public static function canOperate(GeographicScope $actor, GeographicScope $target): bool
    {
        if ($actor->level === 'national') {
            return true;
        }

        if ($actor->level === $target->level && $actor->code === $target->code) {
            return true;
        }

        return false;
    }
}
