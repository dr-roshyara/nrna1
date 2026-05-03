<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Fee\DTOs;

use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;

final readonly class WaiveFeeCommand
{
    public function __construct(
        public readonly FeeId $feeId,
        public readonly TenantId $tenantId,
        public readonly string $reason = '',
        public readonly ?string $waivedByUserId = null,
    ) {}
}
