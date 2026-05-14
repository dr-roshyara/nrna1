<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\UseCases\StoreCommittee;

final readonly class StoreCommitteeCommand
{
    public function __construct(
        public string $name,
        public string $code,
        public int $governanceLevel,
        public int $geoUnitId,
        public string $tenantId,
    ) {}
}
