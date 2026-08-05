<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Application\Inbox;

/**
 * Marker interface: a context exception implementing this means the work was
 * ALREADY DONE (e.g. aggregate guard rejected a replayed transition) — the
 * wrapper marks the message Processed and never retries (Blueprint §8 / F8).
 *
 * Owner: Shared Application (marker) — adopted by consuming contexts' exceptions
 * Layer: Application port (pure PHP, no methods — pure marker)
 * Responsibility: classify "already done → ack"
 * Traceability: Blueprint §8 · ADR-T4 · Matrix: Inbox
 */
interface IdempotentReplay
{
}
