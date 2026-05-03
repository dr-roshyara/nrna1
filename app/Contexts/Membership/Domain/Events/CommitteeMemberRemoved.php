<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

/**
 * Committee Member Removed Domain Event
 *
 * Raised when a member is removed from a committee assignment.
 * Contains all necessary data for committee membership tracking.
 *
 * Following CommitteeFormed.php pattern:
 * - Uses primitives for serialization
 * - Consistent with existing event patterns
 * - Includes all necessary data for event subscribers
 *
 * Properties:
 * - committeeId: The ID of the committee (string)
 * - tenantId: The tenant to which the committee belongs (string)
 * - memberId: The ID of the member removed (string)
 * - assignmentId: The ID of the committee assignment record (string)
 * - rolePath: The role hierarchy path at time of removal (e.g., '1.1.1')
 * - leftDate: Date when assignment ended (ISO 8601 string)
 * - notes: Optional reason for removal
 */
final class CommitteeMemberRemoved extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $committeeId,
        public readonly string $tenantId,
        public readonly string $memberId,
        public readonly string $assignmentId,
        public readonly string $rolePath,
        public readonly string $leftDate,
        public readonly ?string $notes
    ) {
        parent::__construct();
    }

    public static function eventName(): string
    {
        return 'committee.member_removed';
    }

    /**
     * Get event metadata for auditing
     */
    public function metadata(): array
    {
        return [
            'committee_id' => $this->committeeId,
            'tenant_id' => $this->tenantId,
            'member_id' => $this->memberId,
            'assignment_id' => $this->assignmentId,
            'role_path' => $this->rolePath,
            'left_date' => $this->leftDate,
            'notes' => $this->notes,
            'event_type' => self::eventName(),
        ];
    }
}