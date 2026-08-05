<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\Exception;

use App\Contexts\Election\Domain\ElectionId;
use DomainException;

/**
 * The determination names an election that the Election context cannot resolve within
 * its organisational scope (ARB round-2 ruling B/C).
 *
 * The Election context REACTS to existing elections; it never provisions or derives a
 * new aggregate to satisfy a determination. A cross-organisation determination lands
 * here too: it cannot reach an election owned by another organisation, so it resolves
 * to "unknown". No correction is emitted.
 */
final class CannotApplyDeterminationToUnknownElection extends DomainException
{
    public static function withId(ElectionId $id): self
    {
        return new self(sprintf(
            'No election "%s" exists in scope; a determination is never applied to an '
            . 'unknown election, nor may one be provisioned to satisfy it.',
            $id->toString(),
        ));
    }
}
