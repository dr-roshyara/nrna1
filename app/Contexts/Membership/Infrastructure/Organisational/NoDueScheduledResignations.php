<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;

/** Until release-1 slice 5 builds scheduled resignations (D-204), there is nothing due. */
final class NoDueScheduledResignations implements DueScheduledResignations
{
    public function executeDue(Connection $db, TenantId $organisation, Instant $t): void
    {
    }
}
