<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Fee\Services;

use App\Contexts\Membership\Domain\Fee\Fee;
use App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;

final class PaymentPolicy
{
    public function assertCanRecord(
        Fee $fee,
        TenantId $tenantId,
        ?string $transactionReference,
        FeeRepositoryInterface $feeRepository
    ): void {
        // Validate fee belongs to the requesting tenant
        if (!$fee->getTenantId()->equals($tenantId)) {
            throw new \DomainException('Fee does not belong to this tenant');
        }

        // Validate fee is in a payable state
        if (!$fee->getStatus()->isPending() && !$fee->getStatus()->isOverdue()) {
            throw new \DomainException('Fee cannot be paid in its current state');
        }

        // Validate transaction reference uniqueness (if provided)
        if ($transactionReference !== null) {
            $existing = $feeRepository->findByTransactionReference($transactionReference, $tenantId);
            if ($existing !== null) {
                throw new \DomainException('Payment already recorded for this reference');
            }
        }
    }
}
