<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\DTOs;

use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final readonly class CreateCommitteeCommand
{
    public function __construct(
        public TenantId $tenantId,
        public CommitteeType $type,
        public string $name,
        public string $code,
        public ?GeoReference $geoReference = null
    ) {}
}
