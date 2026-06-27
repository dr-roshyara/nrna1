<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Fee\DTOs;

use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

final readonly class RecordFeePaymentCommand
{
    public function __construct(
        public readonly FeeId $feeId,
        public readonly TenantId $tenantId,
        public readonly string $paymentMethod,
        public readonly DateTimeImmutable $paidAt,
        public readonly ?string $transactionReference = null,
        public readonly ?string $recordedByUserId = null,
    ) {}
}
