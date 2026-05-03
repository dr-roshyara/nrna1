<?php

declare(strict_types=1);

namespace App\Contexts\Finance\Events;

/**
 * Finance projection event — immutable contract for fee payment integration
 *
 * This event drives the Finance read model (Income projection).
 * It is consumed by the Finance context to maintain a denormalized view
 * optimized for financial reporting and reconciliation.
 *
 * CRITICAL: This event is frozen — no business logic should interpret it.
 * Finance context must treat this as data to project, never as a directive.
 */
final readonly class FeePaidProjection
{
    public function __construct(
        public string $eventId,
        public string $organisationId,
        public string $aggregateId,  // FeeId
        public string $memberId,
        public array $payload,
        public \DateTimeInterface $occurredAt,
    ) {}

    public function getAmount(): string
    {
        return (string) ($this->payload['amount'] ?? '0');
    }

    public function getCurrency(): string
    {
        return (string) ($this->payload['currency'] ?? 'EUR');
    }

    public function getPaymentMethod(): string
    {
        return (string) ($this->payload['method'] ?? 'unknown');
    }

    public function getTransactionReference(): ?string
    {
        return $this->payload['transactionReference'] ?? null;
    }

    public function getRecordedByUserId(): ?string
    {
        return $this->payload['recordedByUserId'] ?? null;
    }
}
