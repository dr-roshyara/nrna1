<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\MembershipSourceOfTruthPort;
use App\Contexts\Platform\Application\Published\RoutedOrganisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * M-2 (PBDIGIT-345 task 10; routing design §3.5, §3.7 L-3; D-160, D-219 (1); D-233 D-2): an organisation's membership
 * source is the ledger ONLY when it is the current routed organisation AND the registry routes it now (re-asked on
 * every call). Derived, no stored flag. Its ONLY true path (T-14 part 2, MembershipPlatformBoundaryTest). A registry
 * that cannot answer propagates: never read as "not a ledger".
 */
final class RegistryRoutedLedgerSource implements MembershipSourceOfTruthPort
{
    public function __construct(private readonly RoutedOrganisation $routed)
    {
    }

    public function isLedger(TenantId $organisation): bool
    {
        return $this->routed->isRegistryRouted($organisation);
    }
}
