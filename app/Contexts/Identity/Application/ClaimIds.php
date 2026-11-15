<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

/** Claim identifiers are UUIDs; anything else names no claim (refused as unknown, never passed to the store). */
final class ClaimIds
{
    public static function isWellFormed(string $claimId): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $claimId) === 1;
    }
}
