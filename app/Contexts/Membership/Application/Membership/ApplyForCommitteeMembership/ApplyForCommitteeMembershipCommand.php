<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership;

use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final readonly class ApplyForCommitteeMembershipCommand
{
    public function __construct(
        public TenantId $tenantId,
        public string $memberId,
        public string $committeeId,
        public ApplicationReason $reason,
        public ?string $exceptionJustification,
        public GeoPathChain $memberGeoPath,
        public GeoPathChain $committeeGeoPath,
    ) {
    }
}
