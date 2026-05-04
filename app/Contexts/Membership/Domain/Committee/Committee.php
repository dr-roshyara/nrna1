<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\Strategies\CommitteeStructure;
use App\Contexts\Membership\Domain\Events\CommitteeFormed;
use App\Contexts\Membership\Domain\Events\CommitteeMemberAssigned;
use App\Contexts\Membership\Domain\Events\CommitteeMemberRemoved;
use App\Contexts\Membership\Domain\Events\CommitteeMemberRoleUpdated;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeAssignmentId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeStatus;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\NominationType;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use App\Contexts\Shared\Domain\TenantAggregateRoot;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use DomainException;

/**
 * Committee Aggregate Root
 *
 * Represents a political party committee with geographic and organizational rules.
 * Committee owns CommitteeAssignment entities and uses Strategy Pattern for business rules.
 *
 * Business Rules:
 * 1. Central committees MUST NOT have operational geography
 * 2. Geographic committees MUST have operational geography
 * 3. Youth/Women/Student wings CAN have optional geography
 * 4. Committee covers geography: Central covers all, Geographic covers within hierarchy
 * 5. Tenant isolation: Committee belongs to specific tenant
 *
 * Political Party Committee Types in Nepal:
 * - Central Committee: National level, no geography
 * - Province Committee: Province level (np.1, np.2, etc.)
 * - District Committee: District level (np.1.12, np.2.15, etc.)
 * - Ward Committee: Ward level (np.1.12.123, etc.)
 * - Youth Wing: Age 18-35, geography optional
 * - Women Wing: Women only, geography optional
 * - Student Wing: Students only, geography optional
 * - Diaspora Committee: Foreign country geography
 */
final class Committee extends TenantAggregateRoot
{
    private CommitteeId $id;
    private CommitteeType $type;
    private CommitteeName $name;
    private string $code;
    private ?GeoReference $operationalGeo;
    private CommitteeStructure $structure;
    private CommitteeStatus $status;

    /** @var array<CommitteeAssignment> Committee assignments owned by this aggregate */
    private array $assignments = [];

    /**
     * Private constructor for factory methods
     */
    private function __construct(TenantId $tenantId)
    {
        $this->tenantId = $tenantId;
    }

    /**
     * Factory method - Committee controls its own creation
     *
     * @param TenantId $tenantId Tenant identifier
     * @param CommitteeType $type Committee type
     * @param CommitteeName $name Committee name
     * @param string $code Committee code (human-readable identifier)
     * @param GeoReference|null $operationalGeo Operational geography (null for central committees)
     * @param CommitteeStructure $structure Committee structure strategy
     * @return self
     *
     * @throws DomainException If geography rules violated
     */
    public static function form(
        TenantId $tenantId,
        CommitteeType $type,
        CommitteeName $name,
        string $code,
        ?GeoReference $operationalGeo,
        CommitteeStructure $structure
    ): self {
        // Validate that strategy matches committee type
        if (!$structure->getSupportedType()->equals($type)) {
            throw new DomainException(
                sprintf(
                    'Committee structure "%s" does not support committee type "%s"',
                    get_class($structure),
                    $type->value()
                )
            );
        }

        // Validate geography rules
        if ($type->isCentral() && $operationalGeo !== null) {
            throw new DomainException('Central committees cannot have operational geography');
        }

        if ($type->isGeographic() && $operationalGeo === null) {
            throw new DomainException('Geographic committees require operational geography');
        }

        // Validate geography requirement based on structure
        if ($structure->requiresGeography() && $operationalGeo === null) {
            throw new DomainException(
                sprintf(
                    'Committee type "%s" requires operational geography',
                    $type->value()
                )
            );
        }

        $committee = new self($tenantId);
        $committee->id = CommitteeId::generate();
        $committee->type = $type;
        $committee->name = $name;
        $committee->code = $code;
        $committee->operationalGeo = $operationalGeo;
        $committee->structure = $structure;
        $committee->status = CommitteeStatus::active();

        // Record domain event CommitteeFormed
        $committee->recordEvent(new CommitteeFormed(
            committeeId: $committee->id->value(),
            tenantId: $tenantId->value(),
            type: $type->value(),
            name: $name->value(),
            operationalGeo: $operationalGeo?->value()
        ));

        return $committee;
    }

    /**
     * Reconstruct a Committee from persistence (used by repository)
     *
     * Does NOT record domain events since this is hydration from storage,
     * not a business event.
     *
     * @internal Used by EloquentCommitteeRepository only
     */
    public static function reconstruct(
        CommitteeId $id,
        TenantId $tenantId,
        CommitteeType $type,
        CommitteeName $name,
        string $code,
        ?GeoReference $operationalGeo,
        CommitteeStatus $status,
        ?CommitteeStructure $structure = null,
        array $assignments = []
    ): self {
        if ($structure === null) {
            throw new \LogicException('CommitteeStructure must be provided to reconstruct()');
        }

        $committee = new self($tenantId);
        $committee->id = $id;
        $committee->type = $type;
        $committee->name = $name;
        $committee->code = $code;
        $committee->operationalGeo = $operationalGeo;
        $committee->status = $status;
        $committee->structure = $structure;
        $committee->assignments = $assignments;

        return $committee;
    }

    /**
     * Check if committee covers given geography
     *
     * Business Rules:
     * - Central committees cover ALL geography (including null)
     * - Geographic committees cover geography within their operational area
     * - Youth/Women/Student wings with geography follow geographic rules
     * - Youth/Women/Student wings without geography follow central rules
     *
     * @param GeoReference|null $memberGeo Member's geography reference
     * @return bool True if committee covers this geography
     */
    public function coversGeography(?GeoReference $memberGeo): bool
    {
        // Central committees cover all geography
        if ($this->type->isCentral()) {
            return true;
        }

        // Wings without geography should cover all (like central)
        if ($this->operationalGeo === null) {
            // Check if this is a wing (youth, women, student, diaspora)
            if ($this->type->isWing()) {
                return true;
            }
            // Other committee types without geography cannot cover anything
            return false;
        }

        // No geography for member? Only central committees and wings without geography cover null geography
        if ($memberGeo === null) {
            return false;
        }

        // Check if member geography is within committee's operational geography
        return $memberGeo->isWithinOrEqual($this->operationalGeo);
    }

    /**
     * Get committee ID
     */
    public function getId(): CommitteeId
    {
        return $this->id;
    }

    /**
     * Get committee type
     */
    public function getType(): CommitteeType
    {
        return $this->type;
    }

    /**
     * Get committee type (alias)
     */
    public function type(): CommitteeType
    {
        return $this->type;
    }

    /**
     * Get committee name
     */
    public function getName(): CommitteeName
    {
        return $this->name;
    }

    /**
     * Get committee code
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * Get operational geography (nullable)
     */
    public function getOperationalGeo(): ?GeoReference
    {
        return $this->operationalGeo;
    }

    /**
     * Get operational geo reference (alias for getOperationalGeo)
     */
    public function getOperationalGeoReference(): ?GeoReference
    {
        return $this->operationalGeo;
    }

    /**
     * Get committee structure strategy
     */
    public function getStructure(): CommitteeStructure
    {
        return $this->structure;
    }

    /**
     * Get committee status
     */
    public function getStatus(): CommitteeStatus
    {
        return $this->status;
    }

    /**
     * Assign a member to this committee
     *
     * Business Rules:
     * - Committee must be active
     * - Role path must be allowed by committee structure
     * - Only one active assignment per member per committee
     * - Role limits must be respected (e.g., only one chairperson)
     * - Member geography must be covered by committee's operational geography
     *   (Central committees cover all, geographic committees cover within hierarchy,
     *   Wings without geography cover all, Wings with geography follow geographic rules)
     *
     * @param MemberId $memberId Member to assign
     * @param GeoReference|null $memberGeography Member's residential geography (nullable)
     * @param RolePath $rolePath Role hierarchy path
     * @param NominationType $nominationType How member was nominated
     * @param DateTimeImmutable|null $electionDate Election date (required if elected)
     * @param DateTimeImmutable|null $termEndDate Term end date
     * @param TenantUserId|null $appointedByUserId Who made the appointment
     * @param string|null $notes Assignment notes
     * @param array $metadata Additional metadata
     * @return CommitteeAssignment Created assignment entity
     *
     * @throws DomainException If business rules violated
     */
    public function assignMember(
        MemberId $memberId,
        RolePath $rolePath,
        NominationType $nominationType,
        ?GeoReference $memberGeography = null,
        ?DateTimeImmutable $electionDate = null,
        ?DateTimeImmutable $termEndDate = null,
        ?TenantUserId $appointedByUserId = null,
        ?string $notes = null,
        array $metadata = []
    ): CommitteeAssignment {
        // 1. Validate committee is active (CHEAP)
        if (!$this->status->isActive()) {
            throw new DomainException('Cannot assign members to inactive committee');
        }

        // 2. Check for existing active assignment (CHEAP - prevents duplicates)
        if ($this->hasActiveAssignmentForMember($memberId)) {
            throw new DomainException('Member already has an active assignment in this committee');
        }

        // 3. Validate role path with committee structure (CHEAP)
        if (!$this->structure->canAssignRole($rolePath->value())) {
            throw new DomainException(
                sprintf(
                    'Role path "%s" is not allowed for %s committee',
                    $rolePath->value(),
                    $this->type->value()
                )
            );
        }

        // 4. Check role limits using structure strategy (CHEAP)
        $this->validateRoleLimits($rolePath);

        // 5. Validate member geography is covered (EXPENSIVE - do LAST)
        if ($memberGeography !== null && !$this->coversGeography($memberGeography)) {
            throw new DomainException(
                sprintf(
                    'Committee "%s" does not cover member geography',
                    $this->name->value()
                )
            );
        }

        // 6. Handle null geography case
        if ($memberGeography === null) {
            // Only central committees can assign members without geography
            if (!$this->type->isCentral() && $this->operationalGeo !== null) {
                throw new DomainException(
                    'Member without geography cannot be assigned to geographic committee'
                );
            }
        }

        // Create new assignment
        $assignment = CommitteeAssignment::assign(
            id: CommitteeAssignmentId::generate(),
            committeeId: $this->id,
            memberId: $memberId,
            rolePath: $rolePath,
            joinedDate: new DateTimeImmutable(),
            nominationType: $nominationType,
            electionDate: $electionDate,
            termEndDate: $termEndDate,
            appointedByUserId: $appointedByUserId,
            notes: $notes,
            metadata: $metadata
        );

        $this->assignments[] = $assignment;

        // Record domain event CommitteeMemberAssigned
        $this->recordEvent(new CommitteeMemberAssigned(
            committeeId: $this->id->value(),
            tenantId: $this->getTenantId()->value(),
            memberId: $memberId->value(),
            assignmentId: $assignment->getId()->value(),
            rolePath: $rolePath->value(),
            nominationType: $nominationType->value(),
            electionDate: $electionDate?->format('Y-m-d'),
            appointedByUserId: $appointedByUserId?->value()
        ));

        return $assignment;
    }

    /**
     * Get all committee assignments
     *
     * @return array<CommitteeAssignment> All assignments (active and inactive)
     */
    public function getAssignments(): array
    {
        return $this->assignments;
    }

    /**
     * Get active committee assignments
     *
     * @return array<CommitteeAssignment> Only active assignments
     */
    public function getActiveAssignments(): array
    {
        return array_filter(
            $this->assignments,
            fn(CommitteeAssignment $assignment) => $assignment->isActive()
        );
    }

    /**
     * Get assignment by ID
     *
     * @param CommitteeAssignmentId $assignmentId Assignment identifier
     * @return CommitteeAssignment|null Found assignment or null
     */
    public function getAssignment(CommitteeAssignmentId $assignmentId): ?CommitteeAssignment
    {
        foreach ($this->assignments as $assignment) {
            if ($assignment->getId()->equals($assignmentId)) {
                return $assignment;
            }
        }

        return null;
    }

    /**
     * Get active assignment for member
     *
     * @param MemberId $memberId Member identifier
     * @return CommitteeAssignment|null Active assignment or null
     */
    public function getActiveAssignmentForMember(MemberId $memberId): ?CommitteeAssignment
    {
        foreach ($this->assignments as $assignment) {
            if ($assignment->getMemberId()->equals($memberId) && $assignment->isActive()) {
                return $assignment;
            }
        }

        return null;
    }

    /**
     * Check if member has active assignment in this committee
     *
     * @param MemberId $memberId Member identifier
     * @return bool True if member has active assignment
     */
    public function hasActiveAssignmentForMember(MemberId $memberId): bool
    {
        return $this->getActiveAssignmentForMember($memberId) !== null;
    }

    /**
     * Remove member assignment (end assignment)
     *
     * @param MemberId $memberId Member identifier
     * @param DateTimeImmutable $leftDate Date assignment ended
     * @param string|null $notes Reason for ending assignment
     *
     * @throws DomainException If no active assignment found
     */
    public function removeMember(
        MemberId $memberId,
        DateTimeImmutable $leftDate,
        ?string $notes = null
    ): void {
        $assignment = $this->getActiveAssignmentForMember($memberId);
        if ($assignment === null) {
            throw new DomainException('Member does not have an active assignment in this committee');
        }

        $assignment->endAssignment($leftDate, $notes);

        // Record domain event CommitteeMemberRemoved
        $this->recordEvent(new CommitteeMemberRemoved(
            committeeId: $this->id->value(),
            tenantId: $this->getTenantId()->value(),
            memberId: $memberId->value(),
            assignmentId: $assignment->getId()->value(),
            rolePath: $assignment->getRolePath()->value(),
            leftDate: $leftDate->format('Y-m-d'),
            notes: $notes
        ));
    }

    /**
     * Update member's role in committee
     *
     * @param MemberId $memberId Member identifier
     * @param RolePath $newRolePath New role hierarchy
     * @param string|null $notes Reason for role change
     *
     * @throws DomainException If no active assignment found
     */
    public function updateMemberRole(
        MemberId $memberId,
        RolePath $newRolePath,
        ?string $notes = null
    ): void {
        $assignment = $this->getActiveAssignmentForMember($memberId);
        if ($assignment === null) {
            throw new DomainException('Member does not have an active assignment in this committee');
        }

        // Validate new role path with committee structure
        if (!$this->structure->canAssignRole($newRolePath)) {
            throw new DomainException(
                sprintf(
                    'Role path "%s" is not allowed for %s committee',
                    $newRolePath->value(),
                    $this->type->value()
                )
            );
        }

        $oldRolePath = $assignment->getRolePath();
        $assignment->updateRole($newRolePath, $notes);

        // Record domain event CommitteeMemberRoleUpdated
        $this->recordEvent(new CommitteeMemberRoleUpdated(
            committeeId: $this->id->value(),
            tenantId: $this->getTenantId()->value(),
            memberId: $memberId->value(),
            assignmentId: $assignment->getId()->value(),
            oldRolePath: $oldRolePath->value(),
            newRolePath: $newRolePath->value(),
            notes: $notes
        ));
    }

    /**
     * End assignment by ID
     *
     * @param CommitteeAssignmentId $assignmentId Assignment identifier
     * @param DateTimeImmutable $leftDate Date assignment ended
     * @param string|null $notes Reason for ending assignment
     *
     * @throws DomainException If assignment not found or already ended
     */
    public function endAssignment(
        CommitteeAssignmentId $assignmentId,
        DateTimeImmutable $leftDate,
        ?string $notes = null
    ): void {
        $assignment = $this->getAssignment($assignmentId);
        if ($assignment === null) {
            throw new DomainException('Assignment not found');
        }

        $assignment->endAssignment($leftDate, $notes);
    }

    /**
     * Validate role limits using committee structure
     *
     * @param RolePath $rolePath Role path to check
     * @throws DomainException If role limit exceeded
     */
    private function validateRoleLimits(RolePath $rolePath): void
    {
        $limits = $this->structure->getRoleLimits();

        // Check for exact match
        if (isset($limits[$rolePath->value()])) {
            $currentCount = $this->countActiveAssignmentsForRole($rolePath);
            if ($currentCount >= $limits[$rolePath->value()]) {
                throw new DomainException(
                    sprintf('Role limit exceeded for path "%s"', $rolePath->value())
                );
            }
        }

        // Check for pattern match (e.g., '1.*')
        foreach ($limits as $pattern => $limit) {
            if (str_ends_with($pattern, '.*') && $limit !== null) {
                $prefix = substr($pattern, 0, -2);
                if (str_starts_with($rolePath->value(), $prefix)) {
                    $currentCount = $this->countActiveAssignmentsForPattern($pattern);
                    if ($currentCount >= $limit) {
                        throw new DomainException(
                            sprintf('Pattern role limit exceeded for "%s"', $pattern)
                        );
                    }
                }
            }
        }
    }

    /**
     * Count active assignments for specific role path
     */
    private function countActiveAssignmentsForRole(RolePath $rolePath): int
    {
        $count = 0;
        foreach ($this->assignments as $assignment) {
            if ($assignment->isActive() && $assignment->getRolePath()->equals($rolePath)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Count active assignments for pattern (e.g., '1.*')
     */
    private function countActiveAssignmentsForPattern(string $pattern): int
    {
        $prefix = substr($pattern, 0, -2); // Remove '.*'
        $count = 0;
        foreach ($this->assignments as $assignment) {
            if ($assignment->isActive() && str_starts_with($assignment->getRolePath()->value(), $prefix)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Factory method for creating central committees (TDD convenience)
     *
     * @param CommitteeId $id Committee ID
     * @param TenantId $tenantId Tenant ID
     * @param string $name Committee name
     * @param string $code Committee code
     * @param GeoReference|null $geoReference Optional geography (must be null for central)
     * @return self
     * @throws DomainException If geography is provided
     */
    public static function createCentral(
        CommitteeId $id,
        TenantId $tenantId,
        string $name,
        string $code,
        ?GeoReference $geoReference = null
    ): self {
        if ($geoReference !== null) {
            throw new DomainException('Central committees must not have an operational geography');
        }

        $structure = new \App\Contexts\Membership\Domain\Committee\Strategies\CentralCommitteeStructure();

        $committee = new self($tenantId);
        $committee->id = $id;
        $committee->type = CommitteeType::central();
        $committee->name = CommitteeName::fromString($name);
        $committee->code = $code;
        $committee->operationalGeo = null;
        $committee->structure = $structure;
        $committee->status = CommitteeStatus::active();

        $committee->recordEvent(new CommitteeFormed(
            committeeId: $id->value(),
            tenantId: $tenantId->value(),
            type: CommitteeType::central()->value(),
            name: $name,
            operationalGeo: null
        ));

        return $committee;
    }

    /**
     * Factory method for creating geographic committees (TDD convenience)
     *
     * @param CommitteeId $id Committee ID
     * @param TenantId $tenantId Tenant ID
     * @param string $name Committee name
     * @param string $code Committee code
     * @param CommitteeType $type Committee type
     * @param GeoReference|null $geoReference Operational geography (required for geographic committees)
     * @param string $wing Wing identifier (not used for geographic committees)
     * @return self
     * @throws DomainException If geography is missing
     */
    public static function createForGeography(
        CommitteeId $id,
        TenantId $tenantId,
        string $name,
        string $code,
        CommitteeType $type,
        ?GeoReference $geoReference,
        string $wing
    ): self {
        if ($geoReference === null) {
            throw new DomainException('Geographic committees must have an operational geography');
        }

        $structure = match ($type->value()) {
            'province' => new \App\Contexts\Membership\Domain\Committee\Strategies\GeographicCommitteeStructure(),
            'district' => new \App\Contexts\Membership\Domain\Committee\Strategies\GeographicCommitteeStructure(),
            'ward' => new \App\Contexts\Membership\Domain\Committee\Strategies\GeographicCommitteeStructure(),
            default => throw new DomainException("Unsupported geographic committee type: {$type->value()}")
        };

        $committee = new self($tenantId);
        $committee->id = $id;
        $committee->type = $type;
        $committee->name = CommitteeName::fromString($name);
        $committee->code = $code;
        $committee->operationalGeo = $geoReference;
        $committee->structure = $structure;
        $committee->status = CommitteeStatus::active();

        $committee->recordEvent(new CommitteeFormed(
            committeeId: $id->value(),
            tenantId: $tenantId->value(),
            type: $type->value(),
            name: $name,
            operationalGeo: $geoReference->value()
        ));

        return $committee;
    }

    /**
     * Get role limits for this committee from its structure strategy
     *
     * @return array<string, int|null> Role limits
     */
    public function roleLimits(): array
    {
        return $this->structure->getRoleLimits();
    }

    /**
     * Update committee name
     *
     * @param CommitteeName $name New committee name
     */
    public function updateName(CommitteeName $name): void
    {
        $this->name = $name;
    }

    /**
     * Update committee status
     *
     * @param CommitteeStatus $status New committee status
     */
    public function updateStatus(CommitteeStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * Check if committee is in a valid state
     *
     * @return bool True if committee is valid
     */
    public function isValid(): bool
    {
        return $this->status->isActive()
            && $this->id !== null
            && $this->type !== null
            && $this->name !== null
            && $this->structure !== null;
    }

    /**
     * Note: belongsToTenant() is inherited from TenantAggregateRoot
     */
}