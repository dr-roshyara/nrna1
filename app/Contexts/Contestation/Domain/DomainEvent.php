<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain;

/**
 * Marker for Contestation domain events (readonly historical facts).
 * Full transport envelope (EventId/Correlation/Causation/SchemaVersion) is added
 * at the infrastructure/outbox layer — not in the domain (Round 50-04 §1).
 */
interface DomainEvent
{
}
