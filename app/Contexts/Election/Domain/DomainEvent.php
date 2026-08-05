<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain;

/**
 * Marker for Election domain events (readonly historical facts). Carries no
 * voter↔vote linkage (ADR-T11) and no other context's concepts (e.g. no ChallengeId).
 */
interface DomainEvent
{
}
