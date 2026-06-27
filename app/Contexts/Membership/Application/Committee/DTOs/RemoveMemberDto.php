<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\DTOs;

use App\Contexts\Membership\Domain\ValueObjects\CommitteeAssignmentId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final readonly class RemoveMemberDto
{
    public function __construct(
        public CommitteeId $committeeId,
        public TenantId $tenantId,
        public CommitteeAssignmentId $assignmentId,
        public ?string $notes = null,
    ) {}
}
