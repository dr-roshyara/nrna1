<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Outbox;

/**
 * Port for storing domain events in the outbox.
 *
 * Allows infrastructure-independent event persistence.
 * Implementations store events for async processing by EventProcessor.
 */
interface OutboxWriterInterface
{
    /**
     * Store a domain event in the outbox for eventual processing.
     *
     * @param object $event Domain event to store
     * @param string $tenantId Tenant context for multi-tenancy
     */
    public function store(object $event, string $tenantId): void;
}
