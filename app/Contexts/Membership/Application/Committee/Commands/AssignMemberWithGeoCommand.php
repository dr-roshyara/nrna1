<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Commands;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\NominationType;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

/**
 * Command to assign a member to a committee with geographic eligibility validation.
 */
final readonly class AssignMemberWithGeoCommand
{
    public function __construct(
        public CommitteeId $committeeId,
        public TenantId $tenantId,
        public MemberId $memberId,
        public RolePath $rolePath,
        public NominationType $nominationType,
        public ?DateTimeImmutable $electionDate = null,
        public ?DateTimeImmutable $termEndDate = null,
        public ?TenantUserId $appointedByUserId = null,
        public ?string $notes = null,
        public array $metadata = [],
    ) {}
}
