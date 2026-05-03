<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\DTOs;

use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\NominationType;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

final readonly class AssignMemberDto
{
    public function __construct(
        public CommitteeId $committeeId,
        public TenantId $tenantId,
        public MemberId $memberId,
        public RolePath $rolePath,
        public NominationType $nominationType,
        public ?GeoReference $memberGeography = null,
        public ?DateTimeImmutable $electionDate = null,
        public ?DateTimeImmutable $termEndDate = null,
        public ?TenantUserId $appointedByUserId = null,
        public ?string $notes = null,
        public array $metadata = []
    ) {}
}
