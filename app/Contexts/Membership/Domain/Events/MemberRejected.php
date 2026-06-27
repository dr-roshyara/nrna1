<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

/**
 * Member Rejected Domain Event
 *
 * Raised when a pending member is rejected by an admin.
 * Contains rejection reason for audit trail.
 *
 * Properties:
 * - memberId: The ID of the member who was rejected
 * - tenantId: The tenant to which the member belongs
 * - rejectedByUserId: The admin user ID who performed the rejection
 * - reason: The reason for rejection (required for compliance)
 * - rejectedAt: Timestamp when rejection occurred
 */
final class MemberRejected extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $memberId,
        public readonly string $tenantId,
        public readonly string $rejectedByUserId,
        public readonly string $reason,
        public readonly \DateTimeImmutable $rejectedAt
    ) {
        parent::__construct();
    }

    public static function eventName(): string
    {
        return 'member.rejected';
    }

    /**
     * Get event metadata for auditing
     */
    public function metadata(): array
    {
        return [
            'member_id' => $this->memberId,
            'tenant_id' => $this->tenantId,
            'rejected_by' => $this->rejectedByUserId,
            'reason' => $this->reason,
            'rejected_at' => $this->rejectedAt->format(\DateTimeInterface::ATOM),
            'event_type' => self::eventName(),
        ];
    }
}
