<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Fee\ValueObjects;

use DateTimeImmutable;

final readonly class PaymentDetails
{
    public function __construct(
        public string $method,                          // bank_transfer|cash|card
        public DateTimeImmutable $paidAt,
        public ?string $transactionReference = null,
        public ?string $recordedByUserId = null,
    ) {
        if (!in_array($method, ['bank_transfer', 'cash', 'card'], true)) {
            throw new \DomainException("Invalid payment method: {$method}");
        }
    }
}
