<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Shared\Identity;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId as CanonicalCommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId as LegacyCommitteeId;

/**
 * CommitteeId Identity Bridge
 *
 * Temporary coexistence layer for dual CommitteeId types during controlled migration.
 *
 * CRITICAL: This is a VALIDATION bridge, not permanent code.
 * It enables testing whether the canonical type can safely replace legacy usage.
 *
 * After validation, this bridge will be deleted and:
 * - all consumers will use CanonicalCommitteeId directly
 * - LegacyCommitteeId will be removed from the codebase
 *
 * Usage:
 * - Never import both CommitteeId types in same file
 * - Always normalize at repository boundaries
 * - Bridge should be hidden inside repository implementations only
 */
final class CommitteeIdBridge
{
    /**
     * Convert legacy or canonical CommitteeId to canonical form.
     *
     * Safe to call with either type — returns canonical.
     */
    public static function toCanonical(LegacyCommitteeId|CanonicalCommitteeId $id): CanonicalCommitteeId
    {
        if ($id instanceof CanonicalCommitteeId) {
            return $id;
        }

        return CanonicalCommitteeId::fromString((string) $id);
    }

    /**
     * Convert canonical to legacy CommitteeId.
     *
     * Used at repository boundaries where legacy types still exist.
     * Temporary — will be removed after migration.
     */
    public static function toLegacy(CanonicalCommitteeId|LegacyCommitteeId $id): LegacyCommitteeId
    {
        if ($id instanceof LegacyCommitteeId) {
            return $id;
        }

        return new LegacyCommitteeId($id->value());
    }
}
