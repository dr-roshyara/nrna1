<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Command;

use App\Contexts\Contestation\Domain\Challenge\ContestedOutcomeRef;
use App\Contexts\Contestation\Domain\Challenge\RaiserStandingRef;
use App\Contexts\Contestation\Domain\Challenge\SubmittedContent;

/**
 * The intent to raise a challenge. Value objects only — the Application layer
 * never accepts arrays (house Rule 4). Time is NOT a parameter: the service owns
 * it through the injected clock, so callers cannot forge the raise time.
 */
final readonly class RaiseChallengeCommand
{
    public function __construct(
        public RaiserStandingRef $raiser,
        public ContestedOutcomeRef $contestedOutcome,
        public SubmittedContent $content,
    ) {
    }
}
