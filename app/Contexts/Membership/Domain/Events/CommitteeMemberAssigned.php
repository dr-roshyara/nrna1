<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

/**
 * Committee Member Assigned Domain Event
 *
 * Raised when a member is assigned to a committee role.
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
 * - memberId: The ID of the member assigned (string)
 * - assignmentId: The ID of the committee assignment record (string)
 * - rolePath: The role hierarchy path (e.g., '1.1.1')
 * - nominationType: How member was nominated (elected, appointed, by_acclamation)
 * - electionDate: Optional election date (ISO 8601 string, null if not elected)
 * - appointedByUserId: Optional user ID who made the appointment
 */
final class CommitteeMemberAssigned extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $committeeId,
        public readonly string $tenantId,
        public readonly string $memberId,
        public readonly string $assignmentId,
        public readonly string $rolePath,
        public readonly string $nominationType,
        public readonly ?string $electionDate,
        public readonly ?string $appointedByUserId
    ) {
        parent::__construct();
    }

    public static function eventName(): string
    {
        return 'committee.member_assigned';
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
            'nomination_type' => $this->nominationType,
            'election_date' => $this->electionDate,
            'appointed_by_user_id' => $this->appointedByUserId,
            'event_type' => self::eventName(),
        ];
    }
}