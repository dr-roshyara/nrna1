<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Authority\Events;

use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityAssignmentId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

final readonly class AuthorityRevoked
{
    private function __construct(
        private AuthorityAssignmentId $assignmentId,
        private TenantId $tenantId,
        private MemberId $revokedBy,
        private string $reason,
        private DateTimeImmutable $revokedAt,
    ) {}

    public static function from(
        AuthorityAssignmentId $assignmentId,
        TenantId $tenantId,
        MemberId $revokedBy,
        string $reason,
        DateTimeImmutable $revokedAt,
    ): self {
        return new self($assignmentId, $tenantId, $revokedBy, $reason, $revokedAt);
    }

    public function assignmentId(): AuthorityAssignmentId
    {
        return $this->assignmentId;
    }

    public function tenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function revokedBy(): MemberId
    {
        return $this->revokedBy;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function revokedAt(): DateTimeImmutable
    {
        return $this->revokedAt;
    }
}
