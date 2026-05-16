<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Application\DTOs;

use App\Contexts\Membership\Domain\Application\ApplicationId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use Carbon\CarbonImmutable;

final readonly class ApproveMembershipApplicationCommand
{
    public function __construct(
        public ApplicationId $applicationId,
        public TenantId $tenantId,
        public string $userId,
        public string $organisationUserId,
        public string $name,
        public string $email,
        public ?string $phone,
        public CommitteeId $committeeId,
        public MembershipTypeId $membershipTypeId,
        public float $membershipFeeAmount,
        public CarbonImmutable $feeDueDate
    ) {}
}
