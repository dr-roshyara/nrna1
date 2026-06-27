<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

/**
 * Committee Member Role Updated Domain Event
 *
 * Raised when a member's role is updated within a committee assignment.
 * Contains all necessary data for committee membership tracking and audit trail.
 *
 * Following CommitteeFormed.php pattern:
 * - Uses primitives for serialization
 * - Consistent with existing event patterns
 * - Includes all necessary data for event subscribers
 *
 * Properties:
 * - committeeId: The ID of the committee (string)
 * - tenantId: The tenant to which the committee belongs (string)
 * - memberId: The ID of the member whose role was updated (string)
 * - assignmentId: The ID of the committee assignment record (string)
 * - oldRolePath: The previous role hierarchy path (e.g., '1.1.1')
 * - newRolePath: The new role hierarchy path (e.g., '1.1.2')
 * - notes: Optional reason for role change
 */
final class CommitteeMemberRoleUpdated extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $committeeId,
        public readonly string $tenantId,
        public readonly string $memberId,
        public readonly string $assignmentId,
        public readonly string $oldRolePath,
        public readonly string $newRolePath,
        public readonly ?string $notes
    ) {
        parent::__construct();
    }

    public static function eventName(): string
    {
        return 'committee.member_role_updated';
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
            'old_role_path' => $this->oldRolePath,
            'new_role_path' => $this->newRolePath,
            'notes' => $this->notes,
            'event_type' => self::eventName(),
        ];
    }
}