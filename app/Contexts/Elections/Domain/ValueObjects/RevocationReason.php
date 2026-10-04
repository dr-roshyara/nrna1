<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * RevocationReason — why identity evidence lost its CURRENT admission effect
 * (ADR_20261004_1549 §2.2 inv. 9: the historical fact is not deleted).
 */
enum RevocationReason: string
{
    /** A two-person suspension was confirmed (D5): re-verification is required. */
    case SuspensionConfirmed = 'suspension_confirmed';

    /** The Chief or Deputy Chief withdrew the evidence directly. */
    case RevokedByOfficer = 'revoked_by_officer';
}
