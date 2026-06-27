<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Authority\Events;

use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityAssignmentId;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationScope;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationType;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

final readonly class AuthorityDelegated
{
    private function __construct(
        private AuthorityAssignmentId $assignmentId,
        private TenantId $tenantId,
        private CommitteeId $fromCommitteeId,
        private CommitteeId $toCommitteeId,
        private DelegationType $type,
        private DelegationScope $scope,
        private DateTimeImmutable $validFrom,
        private ?DateTimeImmutable $validUntil,
        private DateTimeImmutable $delegatedAt,
    ) {}

    public static function from(
        AuthorityAssignmentId $assignmentId,
        TenantId $tenantId,
        CommitteeId $fromCommitteeId,
        CommitteeId $toCommitteeId,
        DelegationType $type,
        DelegationScope $scope,
        DateTimeImmutable $validFrom,
        ?DateTimeImmutable $validUntil,
        DateTimeImmutable $delegatedAt,
    ): self {
        return new self(
            $assignmentId,
            $tenantId,
            $fromCommitteeId,
            $toCommitteeId,
            $type,
            $scope,
            $validFrom,
            $validUntil,
            $delegatedAt,
        );
    }

    public function assignmentId(): AuthorityAssignmentId
    {
        return $this->assignmentId;
    }

    public function tenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function fromCommitteeId(): CommitteeId
    {
        return $this->fromCommitteeId;
    }

    public function toCommitteeId(): CommitteeId
    {
        return $this->toCommitteeId;
    }

    public function type(): DelegationType
    {
        return $this->type;
    }

    public function scope(): DelegationScope
    {
        return $this->scope;
    }

    public function validFrom(): DateTimeImmutable
    {
        return $this->validFrom;
    }

    public function validUntil(): ?DateTimeImmutable
    {
        return $this->validUntil;
    }

    public function delegatedAt(): DateTimeImmutable
    {
        return $this->delegatedAt;
    }
}
