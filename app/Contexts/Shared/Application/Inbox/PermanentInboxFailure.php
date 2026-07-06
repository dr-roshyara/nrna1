<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Application\Inbox;

/**
 * Marker interface: a context exception implementing this means retrying can
 * NEVER succeed (constitutional guard rejection, refs unresolvable after park
 * deadline) — the wrapper dead-letters immediately with operator/governance
 * alert and NEVER auto-retries (Blueprint §7 F9 / §8).
 *
 * Owner: Shared Application (marker) — adopted by consuming contexts' exceptions
 * Layer: Application port (pure PHP, no methods — pure marker)
 * Responsibility: classify "permanent → dead-letter, escalate"
 * Traceability: Blueprint §7 F9, §8 · ADR-T4 · CI-1..CI-5 escalation path · Matrix: Inbox
 */
interface PermanentInboxFailure
{
}
