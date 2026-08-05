<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Process\Exception;

use App\Contexts\Adjudication\Application\Process\AdjudicationProcessStatus;
use DomainException;

/**
 * A conduct step was attempted that the process's current state does not admit.
 *
 * Mirrors the aggregate's discipline (`IllegalDeterminationTransition`): the
 * attempt throws, and nothing changes. Because process state is immutable, the
 * no-mutation guarantee is structural rather than merely asserted.
 *
 * Traceability: EPIC-004K §6 (transitions + guards).
 */
final class IllegalProcessTransition extends DomainException
{
    public static function from(AdjudicationProcessStatus $current, string $step): self
    {
        return new self(sprintf(
            'Cannot %s an adjudication process in state "%s".',
            $step,
            $current->value,
        ));
    }
}
