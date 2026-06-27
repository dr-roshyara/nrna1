<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

/**
 * Member Approved Domain Event
 *
 * Raised when a pending member is approved by an admin.
 * Contains audit trail information for compliance.
 *
 * Properties:
 * - memberId: The ID of the member who was approved
 * - tenantId: The tenant to which the member belongs
 * - approvedByUserId: The admin user ID who performed the approval
 * - approvedAt: Timestamp when approval occurred
 */
final class MemberApproved extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $memberId,
        public readonly string $tenantId,
        public readonly string $approvedByUserId,
        public readonly \DateTimeImmutable $approvedAt
    ) {
        parent::__construct();
    }

    public static function eventName(): string
    {
        return 'member.approved';
    }

    /**
     * Get event metadata for auditing
     */
    public function metadata(): array
    {
        return [
            'member_id' => $this->memberId,
            'tenant_id' => $this->tenantId,
            'approved_by' => $this->approvedByUserId,
            'approved_at' => $this->approvedAt->format(\DateTimeInterface::ATOM),
            'event_type' => self::eventName(),
        ];
    }
}
