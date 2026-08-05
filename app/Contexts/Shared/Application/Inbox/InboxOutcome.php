<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Application\Inbox;

/**
 * Result vocabulary of one idempotent consumption attempt.
 *
 * Owner: Shared Application
 * Layer: Application port (pure PHP)
 * Responsibility: name the four terminal/interim consumption outcomes (Blueprint §7)
 * Traceability: Blueprint §6/§7 · ADR-T4 · Matrix: Inbox
 */
enum InboxOutcome
{
    case Processed;     // handler completed; row marked processed
    case Duplicate;     // (event_id, consumer_context) already processed — handler NOT invoked (F3)
    case Parked;        // causal precondition missing — re-driven later (F4)
    case DeadLettered;  // permanent failure — operator/governance action required (F9)
}
