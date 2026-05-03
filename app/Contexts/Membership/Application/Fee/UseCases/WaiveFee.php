<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Fee\UseCases;

use App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface;
use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;

final class WaiveFee
{
    public function __construct(private FeeRepositoryInterface $feeRepository) {}

    public function execute(FeeId $feeId, TenantId $tenantId): void
    {
        $fee = $this->feeRepository->find($feeId, $tenantId);

        if (!$fee) {
            throw new \Exception("Fee not found: {$feeId->toString()}");
        }

        $fee->waive();

        $this->feeRepository->save($fee, $tenantId);
    }
}
