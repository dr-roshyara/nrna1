<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain;

/**
 * Marker for Adjudication domain events (readonly historical facts).
 * Transport envelope is added at the infrastructure/outbox layer (50-04 §1).
 */
interface DomainEvent
{
}
