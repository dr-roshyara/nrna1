<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Exception;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use DomainException;

/**
 * A determination already exists for the challenge — enforces logical uniqueness
 * (one determination per challenge). Raised by the application service.
 */
final class DeterminationAlreadyIssued extends DomainException
{
    public static function forChallenge(ChallengeRef $challengeRef): self
    {
        return new self(sprintf(
            'A determination has already been issued for challenge "%s".',
            $challengeRef->toString()
        ));
    }
}
