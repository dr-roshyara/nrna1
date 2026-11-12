<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/** Gate C-1 (D-160): is the organisation's authoritative membership store the ledger? Fail closed when unknown. */
interface MembershipSourceOfTruthPort
{
    public function isLedger(TenantId $organisation): bool;
}
