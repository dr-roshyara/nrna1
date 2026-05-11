<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DomainException;

final class CommitteeHierarchyPolicy
{
    /**
     * Assert that a committee can attach to a parent.
     *
     * Enforces:
     * - INV-05: Committee cannot attach to itself
     * - INV-06: Attachment must not create a cycle
     *
     * @param CommitteeId $childId The committee being attached
     * @param CommitteeId $parentId The proposed parent
     * @param array $ancestors All ancestor committees of childId
     * @throws DomainException If attachment would violate invariants
     */
    public function assertCanAttach(
        CommitteeId $childId,
        CommitteeId $parentId,
        array $ancestors,
    ): void {
        if ($childId->equals($parentId)) {
            throw new DomainException(
                'Committee cannot attach to itself (INV-05)'
            );
        }

        foreach ($ancestors as $ancestor) {
            if ($ancestor->id()->equals($parentId)) {
                throw new DomainException(
                    "Cycle detected: {$parentId->value()} is already an ancestor (INV-06)"
                );
            }
        }
    }
}
