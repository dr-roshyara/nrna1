<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Application\Inbox;

/**
 * Immutable carrier of ONE delivered event crossing the inbox port.
 *
 * Owner: Shared Application
 * Layer: Application port (pure PHP — NOT Infrastructure, NOT Domain)
 * Responsibility: transport the event identity + payload to a consumer handler
 * Traceability: Blueprint §6/§14 · ADR-T4 · D-06 (tenant/correlation propagation) · Matrix: Inbox
 */
final readonly class InboxMessage
{
    /**
     * @param array<string, mixed> $payload decoded event payload
     */
    public function __construct(
        public string $eventId,
        public string $eventType,
        public array $payload,
        public string $organisationId,
        public ?string $correlationId = null,
        public ?string $causationId = null,
    ) {
    }
}
