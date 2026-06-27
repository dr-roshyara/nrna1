<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Fee\Events;

use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

final readonly class FeePaid
{
    public function __construct(
        private FeeId $feeId,
        private MemberId $memberId,
        private TenantId $tenantId,
        private string $amount,
        private string $paymentMethod,
        private DateTimeImmutable $paidAt,
        private ?string $transactionReference = null,
        private ?string $recordedByUserId = null,
        private string $currency = 'EUR',
    ) {}

    public function getFeeId(): FeeId
    {
        return $this->feeId;
    }

    public function getMemberId(): MemberId
    {
        return $this->memberId;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getPaidAt(): DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function getTransactionReference(): ?string
    {
        return $this->transactionReference;
    }

    public function getRecordedByUserId(): ?string
    {
        return $this->recordedByUserId;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
