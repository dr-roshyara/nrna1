<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Exceptions;

use DomainException;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\StructureStatus;

/**
 * CannotEvolveNonActiveStructure
 *
 * Thrown when attempting to evolve a governance structure that is not ACTIVE.
 *
 * Governance Invariant G-006:
 * Only ACTIVE structures may be evolved to DRAFT.
 * Deprecated structures are terminal in the governance timeline.
 *
 * This enforces linear governance evolution:
 *
 * v1 ACTIVE → evolve() → v2 DRAFT
 *  ↓ activate()
 * v2 ACTIVE → evolve() → v3 DRAFT
 *
 * NOT:
 *
 * v1 DEPRECATED → evolve() → ???  ❌ Illegal
 */
final class CannotEvolveNonActiveStructure extends DomainException
{
    public static function becauseNotActive(
        CommitteeStructureId $id,
        StructureStatus $currentStatus
    ): self {
        return new self(
            sprintf(
                'Cannot evolve structure %s. Only ACTIVE structures can be evolved. Current status: %s',
                $id->value(),
                $currentStatus->value
            )
        );
    }

    public static function becauseDraft(CommitteeStructureId $id): self
    {
        return new self(
            sprintf(
                'Cannot evolve DRAFT structure %s. It must be activated first.',
                $id->value()
            )
        );
    }

    public static function becauseDeprecated(CommitteeStructureId $id): self
    {
        return new self(
            sprintf(
                'Cannot evolve DEPRECATED structure %s. Deprecated structures are terminal and cannot evolve.',
                $id->value()
            )
        );
    }
}
