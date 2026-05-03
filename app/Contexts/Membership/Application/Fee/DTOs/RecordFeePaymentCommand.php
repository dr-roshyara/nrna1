<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Fee\DTOs;

use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;

final readonly class RecordFeePaymentCommand
{
    public function __construct(
        private FeeId $feeId,
        private TenantId $tenantId,
        private string $paymentMethod = 'bank_transfer'
    ) {}

    public function getFeeId(): FeeId
    {
        return $this->feeId;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }
}
