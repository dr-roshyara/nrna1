<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Application\Inbox;

/**
 * Contract a consuming bounded context implements to receive events
 * idempotently through the inbox wrapper (never invoked directly by the relay).
 *
 * Owner: Shared Application (contract) — implementations owned by consuming contexts
 * Layer: Application port (pure PHP — NOT Infrastructure, NOT Domain)
 * Responsibility: define WHO consumes (consumerContext), WHAT (eventTypes), HOW (handle)
 * Traceability: Blueprint §6 · ADR-T4 · D-03 (dedupe key includes consumerContext) · Matrix: Inbox
 *
 * Exception classification contract (Blueprint §8 — binding for implementations):
 *  - throw {@see CausalPreconditionMissing}  → wrapper PARKS the message (out-of-order, §7 F4)
 *  - throw an exception implementing {@see IdempotentReplay} → wrapper marks Processed (already done, F8)
 *  - throw an exception implementing {@see PermanentInboxFailure} → wrapper dead-letters, NEVER retries (F9)
 *  - any other Throwable → wrapper rolls back leaving NO trace; relay redelivery retries (transient, F5)
 */
interface InboxHandler
{
    /** Consumer identity for the dedupe key (event_id, consumer_context). */
    public function consumerContext(): string;

    /**
     * Canonical event type names this handler consumes (Catalog v1.0 names).
     *
     * @return list<string>
     */
    public function eventTypes(): array;

    public function handle(InboxMessage $message): void;
}
