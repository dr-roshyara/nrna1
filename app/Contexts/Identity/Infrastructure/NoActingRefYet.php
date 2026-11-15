<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\SessionActorRefs;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * PBDIGIT-349 I-2: the PRODUCTION answer to "which R does the signed-in session hold in O?" until R-1 exists (I-4).
 * Bindings come only from redemption (I-3, D-113), so there is no answer yet: every claim operation is refused
 * (fail closed). Replace it with Identity's R-1 adapter in I-4; never with an e-mail or legacy-table lookup (D-160).
 */
final class NoActingRefYet implements SessionActorRefs
{
    public function actingRef(TenantId $organisation): never
    {
        throw new IdentityUnavailable('R-1 (the session\'s own PersonRef in the organisation) is not available before PBDIGIT-349 I-4.');
    }
}
