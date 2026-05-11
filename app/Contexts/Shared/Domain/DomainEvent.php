<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Domain;

use DateTimeImmutable;

interface DomainEvent
{
    public function aggregateId(): string;

    public function occurredAt(): DateTimeImmutable;

    public function eventVersion(): int;

    public function correlationId(): ?string;

    public function causationId(): ?string;
}
