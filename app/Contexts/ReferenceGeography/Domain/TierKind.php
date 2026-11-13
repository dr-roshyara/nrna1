<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The kind of a profile tier (v3.11 §13; D-83): a TERRITORIAL tier holds official units with identities; an
 * ADDRESS_COMPONENT tier is a position only, used to rank address precision, and holds no units or claims.
 */
enum TierKind: string
{
    case Territorial = 'TERRITORIAL';
    case AddressComponent = 'ADDRESS_COMPONENT';
}
