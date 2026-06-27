<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Domain;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Shared\Domain\Events\AbstractDomainEvent;

abstract class TenantAggregateRoot
{
    protected TenantId $tenantId;

    private array $domainEvents = [];

    protected function recordEvent(AbstractDomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function pullEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }
}
