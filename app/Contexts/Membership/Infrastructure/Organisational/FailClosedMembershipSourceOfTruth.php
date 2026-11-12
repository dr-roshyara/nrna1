<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\MembershipSourceOfTruthPort;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/** Until slice 2 stores the gate C-1 flag (set "ledger" at joining, D-160), no organisation is a ledger organisation. */
final class FailClosedMembershipSourceOfTruth implements MembershipSourceOfTruthPort
{
    public function isLedger(TenantId $organisation): bool
    {
        return false;
    }
}
