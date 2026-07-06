<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Outbox;

use App\Contexts\Membership\Domain\Fee\Events\FeePaid;
use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;
use DateTimeImmutable;

/**
 * Context-owned hydrator for FeePaid — migrated verbatim from the relay's
 * former hardcoded hydrateFeePaid() branch (Push B step 5); the wire contract
 * is unchanged. Membership owns this because Membership produces FeePaid.
 *
 * Traceability — Blueprint: Push B §6, §16 step 5 · ADR: ADR-T3, ADR-T5 ·
 * Matrix: Push B — Relay Registry (G-1) · Context: Membership (Infrastructure).
 */
final class FeePaidHydrator implements EventHydrator
{
    public function eventType(): string
    {
        return 'FeePaid';
    }

    public function hydrate(array $payload): object
    {
        return new FeePaid(
            feeId: FeeId::fromString($this->required($payload, 'feeId')),
            memberId: MemberId::fromString($this->required($payload, 'memberId')),
            tenantId: TenantId::fromString($this->required($payload, 'tenantId')),
            amount: $this->required($payload, 'amount'),
            paymentMethod: $this->required($payload, 'paymentMethod'),
            paidAt: new DateTimeImmutable($this->required($payload, 'paidAt')),
            transactionReference: $this->optional($payload, 'transactionReference'),
            recordedByUserId: $this->optional($payload, 'recordedByUserId'),
            currency: $this->optional($payload, 'currency') ?? 'EUR',
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function required(array $payload, string $field): string
    {
        $value = $payload[$field] ?? null;

        if (is_int($value) || is_float($value)) {
            $value = (string) $value;
        }

        if (!is_string($value) || $value === '') {
            throw new \InvalidArgumentException(sprintf(
                'FeePaid payload is missing required field "%s".',
                $field,
            ));
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function optional(array $payload, string $field): ?string
    {
        $value = $payload[$field] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }
}
