<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;

/**
 * D-209 closing step (a) (PBDIGIT-343 plan D-4): record the End of every scheduled resignation whose effective date
 * has started by $t, in the closing transaction on $db, with recordedAt = $t (D-204, D-205). Any failure must throw:
 * the close then rolls back and the boundary stays where it was. Release-1 slice 5 implements it; until then
 * NoDueScheduledResignations (no declarations exist).
 */
interface DueScheduledResignations
{
    public function executeDue(Connection $db, TenantId $organisation, Instant $t): void;
}
