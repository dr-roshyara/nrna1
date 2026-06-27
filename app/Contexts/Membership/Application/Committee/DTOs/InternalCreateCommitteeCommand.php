<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\DTOs;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeCategory;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * InternalCreateCommitteeCommand
 *
 * Typed application command DTO replacing raw arrays in the committee creation use case.
 * Carries data from controller to use case boundary.
 *
 * The committeeCategory field maps to the CommitteeCategory enum, providing type-safe
 * vocabulary for committee classification. The CommitteePolicyResolver maps this
 * to the correct CommitteeType, CommitteeStructure, and CommitteeLevel.
 */
final readonly class InternalCreateCommitteeCommand
{
    public function __construct(
        public TenantId $tenantId,
        public string $committeeCode,
        public string $committeeName,
        public CommitteeCategory $committeeCategory,
        public ?string $countryCode = null,
        public ?string $regionCode = null,
        public ?string $geoReference = null,
        public ?int $geoUnitId = null,
    ) {}
}
