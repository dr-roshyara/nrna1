<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Application\Inbox;

/**
 * Thrown by a handler when the message's causal precondition has not been
 * processed yet (out-of-order arrival) — the wrapper PARKS the message for
 * re-drive instead of failing it (Blueprint §7 F4; 50-05 §3 "park, not fail").
 *
 * Owner: Shared Application
 * Layer: Application port (pure PHP)
 * Responsibility: signal "cause not yet here — park me"
 * Traceability: Blueprint §7 F4 · ADR-T4 · Matrix: Inbox
 */
final class CausalPreconditionMissing extends \RuntimeException
{
    public function __construct(private readonly string $reason)
    {
        parent::__construct(sprintf('Causal precondition missing: %s', $reason));
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
