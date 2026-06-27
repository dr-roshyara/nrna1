<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\ValueObjects\CommitteeAssignmentId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\NominationType;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use DateTimeImmutable;
use DomainException;

/**
 * CommitteeAssignment Entity
 *
 * Owned entity of Committee aggregate representing a member's assignment to a committee.
 * Committee aggregate controls creation and lifecycle of assignments.
 *
 * Business Rules:
 * - One active assignment per member per committee (enforced at database level)
 * - Only ONE active assignment per role path per committee (enforced at database level)
 * - Left date must be after joined date
 * - Term end date must be after election date (if both present)
 * - Elected nominations require election date
 * - Active assignments have left_date = null
 *
 * Design Principles:
 * - Immutable except for state transitions (endAssignment(), reactivate())
 * - Business logic validates rules before state changes
 * - No persistence logic (owned by Committee aggregate)
 * - No framework dependencies
 */
final class CommitteeAssignment
{
    private CommitteeAssignmentId $id;
    private CommitteeId $committeeId;
    private MemberId $memberId;
    private RolePath $rolePath;
    private DateTimeImmutable $joinedDate;
    private ?DateTimeImmutable $leftDate;
    private bool $isActive;
    private ?DateTimeImmutable $electionDate;
    private ?DateTimeImmutable $termEndDate;
    private NominationType $nominationType;
    private ?TenantUserId $appointedByUserId;
    private ?string $notes;
    private array $metadata;

    /**
     * Private constructor - use factory methods
     */
    private function __construct(
        CommitteeAssignmentId $id,
        CommitteeId $committeeId,
        MemberId $memberId,
        RolePath $rolePath,
        DateTimeImmutable $joinedDate,
        ?DateTimeImmutable $leftDate,
        bool $isActive,
        ?DateTimeImmutable $electionDate,
        ?DateTimeImmutable $termEndDate,
        NominationType $nominationType,
        ?TenantUserId $appointedByUserId,
        ?string $notes,
        array $metadata
    ) {
        $this->id = $id;
        $this->committeeId = $committeeId;
        $this->memberId = $memberId;
        $this->rolePath = $rolePath;
        $this->joinedDate = $joinedDate;
        $this->leftDate = $leftDate;
        $this->isActive = $isActive;
        $this->electionDate = $electionDate;
        $this->termEndDate = $termEndDate;
        $this->nominationType = $nominationType;
        $this->appointedByUserId = $appointedByUserId;
        $this->notes = $notes;
        $this->metadata = $metadata;

        $this->validateState();
    }

    /**
     * Factory method for new committee assignment
     *
     * @param CommitteeAssignmentId $id Assignment identifier
     * @param CommitteeId $committeeId Owning committee
     * @param MemberId $memberId Assigned member
     * @param RolePath $rolePath Role hierarchy path
     * @param DateTimeImmutable $joinedDate Assignment start date
     * @param NominationType $nominationType How member was nominated
     * @param DateTimeImmutable|null $electionDate Election date (required if elected)
     * @param DateTimeImmutable|null $termEndDate Term end date
     * @param TenantUserId|null $appointedByUserId Who made the appointment
     * @param string|null $notes Assignment notes
     * @param array $metadata Additional metadata
     * @return self New active committee assignment
     *
     * @throws DomainException If business rules violated
     */
    public static function assign(
        CommitteeAssignmentId $id,
        CommitteeId $committeeId,
        MemberId $memberId,
        RolePath $rolePath,
        DateTimeImmutable $joinedDate,
        NominationType $nominationType,
        ?DateTimeImmutable $electionDate = null,
        ?DateTimeImmutable $termEndDate = null,
        ?TenantUserId $appointedByUserId = null,
        ?string $notes = null,
        array $metadata = []
    ): self {
        // Validate election date requirement
        if ($nominationType->requiresElectionDate() && $electionDate === null) {
            throw new DomainException('Election date is required for elected nominations');
        }

        // Validate term end date after election date if both present
        if ($electionDate !== null && $termEndDate !== null && $termEndDate < $electionDate) {
            throw new DomainException('Term end date must be after election date');
        }

        return new self(
            id: $id,
            committeeId: $committeeId,
            memberId: $memberId,
            rolePath: $rolePath,
            joinedDate: $joinedDate,
            leftDate: null,
            isActive: true,
            electionDate: $electionDate,
            termEndDate: $termEndDate,
            nominationType: $nominationType,
            appointedByUserId: $appointedByUserId,
            notes: $notes,
            metadata: $metadata
        );
    }

    /**
     * Factory method for reconstructing assignment from database (hydration only)
     *
     * Used by repository to rebuild assignment entities from persisted data.
     * No events fired - this is hydration only.
     *
     * @param CommitteeAssignmentId $id Assignment identifier
     * @param CommitteeId $committeeId Owning committee
     * @param MemberId $memberId Assigned member
     * @param RolePath $rolePath Role hierarchy path
     * @param DateTimeImmutable $joinedDate Assignment start date
     * @param NominationType $nominationType How member was nominated
     * @param DateTimeImmutable|null $electionDate Election date (if elected)
     * @param DateTimeImmutable|null $termEndDate Term end date
     * @param DateTimeImmutable|null $leftDate End date (null if active)
     * @param TenantUserId|null $appointedByUserId Who made the appointment
     * @param string|null $notes Assignment notes
     * @param array $metadata Additional metadata
     * @return self Reconstructed assignment
     */
    public static function reconstruct(
        CommitteeAssignmentId $id,
        CommitteeId $committeeId,
        MemberId $memberId,
        RolePath $rolePath,
        DateTimeImmutable $joinedDate,
        NominationType $nominationType,
        ?DateTimeImmutable $electionDate,
        ?DateTimeImmutable $termEndDate,
        ?DateTimeImmutable $leftDate,
        ?TenantUserId $appointedByUserId,
        ?string $notes,
        array $metadata = []
    ): self {
        $isActive = $leftDate === null;

        return new self(
            id: $id,
            committeeId: $committeeId,
            memberId: $memberId,
            rolePath: $rolePath,
            joinedDate: $joinedDate,
            leftDate: $leftDate,
            isActive: $isActive,
            electionDate: $electionDate,
            termEndDate: $termEndDate,
            nominationType: $nominationType,
            appointedByUserId: $appointedByUserId,
            notes: $notes,
            metadata: $metadata
        );
    }

    /**
     * End assignment (mark as inactive)
     *
     * @param DateTimeImmutable $leftDate Date assignment ended
     * @param string|null $notes Reason for ending assignment
     *
     * @throws DomainException If assignment already ended
     */
    public function endAssignment(DateTimeImmutable $leftDate, ?string $notes = null): void
    {
        if ($this->leftDate !== null) {
            throw new DomainException('Assignment already ended');
        }

        if ($leftDate < $this->joinedDate) {
            throw new DomainException('Left date cannot be before joined date');
        }

        $this->leftDate = $leftDate;
        $this->isActive = false;

        if ($notes !== null) {
            $this->notes = $notes;
        }
    }

    /**
     * Reactivate a previously ended assignment
     *
     * @param DateTimeImmutable $reactivatedDate Date of reactivation
     * @param string|null $notes Reason for reactivation
     *
     * @throws DomainException If assignment is already active
     */
    public function reactivate(DateTimeImmutable $reactivatedDate, ?string $notes = null): void
    {
        if ($this->isActive()) {
            throw new DomainException('Assignment is already active');
        }

        if ($this->leftDate === null) {
            throw new DomainException('Cannot reactivate assignment without left date');
        }

        if ($reactivatedDate < $this->leftDate) {
            throw new DomainException('Reactivated date cannot be before left date');
        }

        $this->leftDate = null;
        $this->isActive = true;
        $this->joinedDate = $reactivatedDate;

        if ($notes !== null) {
            $this->notes = $notes;
        }
    }

    /**
     * Update role path (e.g., promotion/demotion)
     *
     * @param RolePath $newRolePath New role hierarchy
     * @param string|null $notes Reason for role change
     */
    public function updateRole(RolePath $newRolePath, ?string $notes = null): void
    {
        $this->rolePath = $newRolePath;

        if ($notes !== null) {
            $this->notes = $notes;
        }
    }

    /**
     * Update term end date
     *
     * @param DateTimeImmutable|null $newTermEndDate New term end date (null to remove)
     *
     * @throws DomainException If term end date before election date
     */
    public function updateTermEndDate(?DateTimeImmutable $newTermEndDate): void
    {
        if ($newTermEndDate !== null && $this->electionDate !== null && $newTermEndDate < $this->electionDate) {
            throw new DomainException('Term end date cannot be before election date');
        }

        $this->termEndDate = $newTermEndDate;
    }

    /**
     * Update nomination type (e.g., appointed → elected)
     *
     * @param NominationType $newNominationType New nomination type
     * @param DateTimeImmutable|null $electionDate Election date (required if elected)
     *
     * @throws DomainException If election date missing for elected type
     */
    public function updateNominationType(NominationType $newNominationType, ?DateTimeImmutable $electionDate = null): void
    {
        if ($newNominationType->requiresElectionDate() && $electionDate === null) {
            throw new DomainException('Election date is required for elected nominations');
        }

        $this->nominationType = $newNominationType;

        if ($electionDate !== null) {
            $this->electionDate = $electionDate;
        }
    }

    /**
     * Validate entity state invariants
     *
     * @throws DomainException If business rules violated
     */
    private function validateState(): void
    {
        // Active assignments must have null left date
        if ($this->isActive && $this->leftDate !== null) {
            throw new DomainException('Active assignment cannot have left date');
        }

        // Inactive assignments must have left date
        if (!$this->isActive && $this->leftDate === null) {
            throw new DomainException('Inactive assignment must have left date');
        }

        // Left date must be after joined date
        if ($this->leftDate !== null && $this->leftDate < $this->joinedDate) {
            throw new DomainException('Left date cannot be before joined date');
        }

        // Term end date must be after election date if both present
        if ($this->electionDate !== null && $this->termEndDate !== null && $this->termEndDate < $this->electionDate) {
            throw new DomainException('Term end date cannot be before election date');
        }

        // Elected nominations require election date
        if ($this->nominationType->requiresElectionDate() && $this->electionDate === null) {
            throw new DomainException('Elected nominations require election date');
        }
    }

    // Accessor methods

    public function getId(): CommitteeAssignmentId
    {
        return $this->id;
    }

    public function getCommitteeId(): CommitteeId
    {
        return $this->committeeId;
    }

    public function getMemberId(): MemberId
    {
        return $this->memberId;
    }

    public function getRolePath(): RolePath
    {
        return $this->rolePath;
    }

    public function getJoinedDate(): DateTimeImmutable
    {
        return $this->joinedDate;
    }

    public function getLeftDate(): ?DateTimeImmutable
    {
        return $this->leftDate;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getElectionDate(): ?DateTimeImmutable
    {
        return $this->electionDate;
    }

    public function getTermEndDate(): ?DateTimeImmutable
    {
        return $this->termEndDate;
    }

    public function getNominationType(): NominationType
    {
        return $this->nominationType;
    }

    public function getAppointedByUserId(): ?TenantUserId
    {
        return $this->appointedByUserId;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Add metadata key-value pair
     */
    public function addMetadata(string $key, mixed $value): void
    {
        $this->metadata[$key] = $value;
    }

    /**
     * Remove metadata key
     */
    public function removeMetadata(string $key): void
    {
        unset($this->metadata[$key]);
    }

    /**
     * Check if assignment is expired (term ended)
     */
    public function isExpired(): bool
    {
        if ($this->termEndDate === null) {
            return false;
        }

        $now = new DateTimeImmutable();
        return $this->termEndDate < $now;
    }

    /**
     * Check if assignment is within term (for elected positions)
     */
    public function isWithinTerm(): bool
    {
        if ($this->termEndDate === null) {
            return true; // No term limit
        }

        $now = new DateTimeImmutable();
        return $now <= $this->termEndDate;
    }

    /**
     * Get assignment duration in days
     */
    public function getDurationDays(): int
    {
        $endDate = $this->leftDate ?? new DateTimeImmutable();
        $interval = $endDate->diff($this->joinedDate);
        return (int) $interval->format('%a');
    }
}