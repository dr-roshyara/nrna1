<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\AssociationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;

final readonly class CommitteeAssociation
{
    public function __construct(
        public AssociationId $associationId,
        public MemberId $memberId,
        public CommitteeId $committeeId,
        public ApplicationReason $associationType,
        public \DateTimeImmutable $associatedAt,
        public MembershipStatus $status,
    ) {
    }

    /**
     * Create new association with generated identity
     */
    public static function create(
        MemberId $memberId,
        CommitteeId $committeeId,
        ApplicationReason $associationType,
        \DateTimeImmutable $associatedAt,
        MembershipStatus $status,
    ): self {
        return new self(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: $associationType,
            associatedAt: $associatedAt,
            status: $status,
        );
    }
}
