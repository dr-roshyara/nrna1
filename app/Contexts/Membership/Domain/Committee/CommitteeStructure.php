<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Events\CommitteeStructureDefined;
use App\Contexts\Membership\Domain\Events\CommitteeStructureActivated;
use App\Contexts\Membership\Domain\Committee\Exceptions\CannotEvolveNonActiveStructure;
use App\Contexts\Shared\Domain\TenantAggregateRoot;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class CommitteeStructure extends TenantAggregateRoot
{
    private CommitteeStructureId $id;
    protected TenantId $tenantId;
    private string $name;
    private int $version = 1;
    private StructureStatus $status = StructureStatus::DRAFT;
    /** @var CommitteeLevel[] */
    private array $levels = [];
    private ?CommitteeStructureId $parentStructureId = null;
    private ?string $activatedBy = null;
    private ?\DateTimeImmutable $activatedAt = null;
    private ?string $activationReason = null;
    private ?array $activationMetadata = null;
    private ?\DateTimeImmutable $effectiveFrom = null;
    private ?\DateTimeImmutable $effectiveUntil = null;

    private function __construct(
        CommitteeStructureId $id,
        TenantId $tenantId,
        string $name,
        array $levels
    ) {
        $this->id = $id;
        $this->tenantId = $tenantId;
        $this->name = $name;
        $this->levels = $levels;

        $this->assertValidHierarchy();
        $this->recordEvent(new CommitteeStructureDefined($id, $tenantId));
    }

    public static function define(
        CommitteeStructureId $id,
        TenantId $tenantId,
        string $name,
        array $levels
    ): self {
        return new self($id, $tenantId, $name, $levels);
    }

    public static function reconstruct(
        CommitteeStructureId $id,
        TenantId $tenantId,
        string $name,
        int $version,
        StructureStatus $status,
        array $levels,
        ?CommitteeStructureId $parentStructureId = null,
        ?string $activatedBy = null,
        ?\DateTimeImmutable $activatedAt = null,
        ?string $activationReason = null,
        ?array $activationMetadata = null,
        ?\DateTimeImmutable $effectiveFrom = null,
        ?\DateTimeImmutable $effectiveUntil = null
    ): self {
        $instance = new self($id, $tenantId, $name, $levels);
        $instance->version = $version;
        $instance->status = $status;
        $instance->parentStructureId = $parentStructureId;
        $instance->activatedBy = $activatedBy;
        $instance->activatedAt = $activatedAt;
        $instance->activationReason = $activationReason;
        $instance->activationMetadata = $activationMetadata;
        $instance->effectiveFrom = $effectiveFrom;
        $instance->effectiveUntil = $effectiveUntil;
        $instance->pullEvents();  // Clear events from constructor
        return $instance;
    }

    public function getId(): CommitteeStructureId
    {
        return $this->id;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function version(): int
    {
        return $this->version;
    }

    public function status(): StructureStatus
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return $this->status === StructureStatus::ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status === StructureStatus::DRAFT;
    }

    public function isDeprecated(): bool
    {
        return $this->status === StructureStatus::DEPRECATED;
    }

    public function activatedBy(): ?string
    {
        return $this->activatedBy;
    }

    public function activatedAt(): ?\DateTimeImmutable
    {
        return $this->activatedAt;
    }

    public function activationReason(): ?string
    {
        return $this->activationReason;
    }

    public function activationMetadata(): ?array
    {
        return $this->activationMetadata;
    }

    public function parentStructureId(): ?CommitteeStructureId
    {
        return $this->parentStructureId;
    }

    public function effectiveFrom(): ?\DateTimeImmutable
    {
        return $this->effectiveFrom;
    }

    public function effectiveUntil(): ?\DateTimeImmutable
    {
        return $this->effectiveUntil;
    }

    public function deprecate(): void
    {
        if ($this->status !== StructureStatus::ACTIVE) {
            throw new \DomainException('Only ACTIVE structures can be deprecated');
        }

        $this->status = StructureStatus::DEPRECATED;
    }

    public function assertCanCreateCommittee(int $levelIndex): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('Can only create committees against ACTIVE structures');
        }

        $level = $this->getLevel($levelIndex); // throws if not found
        $level->geoPolicy->validate($level->geoScope);
    }

    public function levels(): array
    {
        return $this->levels;
    }

    public function getLevel(int $index): CommitteeLevel
    {
        foreach ($this->levels as $level) {
            if ($level->index === $index) {
                return $level;
            }
        }

        throw new \DomainException("Level index {$index} not found");
    }

    public function activate(
        string $activatedBy,
        ?string $reason = null,
        array $metadata = []
    ): void {
        if ($this->status !== StructureStatus::DRAFT) {
            throw new \DomainException('Only DRAFT structures can be activated');
        }

        if (empty($this->levels)) {
            throw new \DomainException('Cannot activate structure without levels');
        }

        $this->status = StructureStatus::ACTIVE;
        $this->activatedBy = $activatedBy;
        $this->activatedAt = new \DateTimeImmutable();
        $this->activationReason = $reason;
        $this->activationMetadata = !empty($metadata) ? $metadata : null;

        $this->recordEvent(new CommitteeStructureActivated(
            $this->id,
            $this->tenantId,
            count($this->levels)
        ));
    }

    public function evolve(array $newLevels): self
    {
        if (!$this->isActive()) {
            throw CannotEvolveNonActiveStructure::becauseNotActive($this->id, $this->status);
        }

        // Deprecate current
        $this->status = StructureStatus::DEPRECATED;

        // Create new version as DRAFT with lineage established
        $evolved = new self(
            CommitteeStructureId::generate(),
            $this->tenantId,
            $this->name,
            $newLevels
        );
        $evolved->version = $this->version + 1;
        $evolved->parentStructureId = $this->id;  // Establish lineage

        return $evolved;
    }

    private function assertValidHierarchy(): void
    {
        if (empty($this->levels)) {
            throw new \DomainException('Structure must have at least one level');
        }

        // Check for duplicate indexes
        $indexes = [];
        foreach ($this->levels as $level) {
            if (in_array($level->index, $indexes, true)) {
                throw new \DomainException('Duplicate level indexes detected');
            }
            $indexes[] = $level->index;
        }

        // Check for contiguity
        sort($indexes);
        for ($i = 0; $i < count($indexes); $i++) {
            if ($indexes[$i] !== $i + 1) {
                throw new \DomainException('Level indexes must be contiguous (1, 2, 3, ...)');
            }
        }

        // Max 10 levels
        if (count($this->levels) > 10) {
            throw new \DomainException('Maximum 10 levels allowed');
        }
    }
}
