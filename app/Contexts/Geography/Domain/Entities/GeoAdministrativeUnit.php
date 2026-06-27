<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Entities;

use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Contexts\Geography\Domain\ValueObjects\GeoPath;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Geography\Domain\ValueObjects\GeographyLevel;
use App\Contexts\Geography\Domain\ValueObjects\GeographicCode;
use App\Contexts\Geography\Domain\ValueObjects\LocalizedName;
use DomainException;

/**
 * GeoAdministrativeUnit Aggregate Root
 *
 * Represents a geographic administrative unit in the hierarchy.
 * Each GeoUnit is an independent aggregate root. Cross-unit
 * hierarchy rules (cycle detection, level ordering) are enforced
 * via GeographyHierarchyDomainService, not within the aggregate.
 *
 * Transaction boundary: single geo unit mutation.
 *
 * Business rules (GEO-INV):
 * 1. Cannot be own parent (GEO-INV-01)
 * 2. Level must be within 0-10 range (enforced by GeographyLevel)
 * 3. Child level must be lower (higher number) than parent level (GEO-INV-02)
 * 4. Path must contain full ancestor chain matching parent hierarchy (GEO-INV-03)
 * 5. Country code must match parent's country (GEO-INV-04)
 * 6. Temporal ranges must be consistent with parent (GEO-INV-05)
 * 7. Version field for optimistic locking (GEO-INV-06)
 */
class GeoAdministrativeUnit
{
    /**
     * @param GeoUnitId $id Unique identifier
     * @param CountryCode $countryCode ISO country code
     * @param GeographyLevel $level Administrative level (0-10)
     * @param GeoUnitId|null $parentId Immediate parent unit ID
     * @param GeoPath $path Materialized hierarchy path
     * @param LocalizedName $name Multilingual unit name
     * @param GeographicCode|null $officialCode Official geographic code
     * @param int $version Optimistic locking version (starts at 1)
     * @param \DateTimeImmutable|null $validFrom When this unit became valid
     * @param \DateTimeImmutable|null $validTo When this unit ceased to be valid
     */
    public function __construct(
        private GeoUnitId $id,
        private CountryCode $countryCode,
        private GeographyLevel $level,
        private ?GeoUnitId $parentId,
        private GeoPath $path,
        private LocalizedName $name,
        private ?GeographicCode $officialCode = null,
        private int $version = 1,
        private ?\DateTimeImmutable $validFrom = null,
        private ?\DateTimeImmutable $validTo = null,
    ) {
        $this->validate();
    }

    /**
     * Factory method to create root unit (level 1, no parent)
     */
    public static function createRoot(
        GeoUnitId $id,
        CountryCode $countryCode,
        LocalizedName $name,
        ?GeographicCode $officialCode = null,
        int $version = 1,
        ?\DateTimeImmutable $validFrom = null,
        ?\DateTimeImmutable $validTo = null
    ): self {
        return new self(
            $id,
            $countryCode,
            GeographyLevel::fromInt(1),
            null,
            GeoPath::fromIds([$id->toInt()]),
            $name,
            $officialCode,
            $version,
            $validFrom,
            $validTo
        );
    }

    /**
     * Factory method to create child unit
     *
     * @param GeoUnitId $id Child unit ID
     * @param CountryCode $countryCode ISO country code
     * @param GeographyLevel $level Child level (must be > parent level)
     * @param GeoAdministrativeUnit $parent Parent unit
     * @param LocalizedName $name Child unit name
     * @param GeographicCode|null $officialCode Official code
     */
    public static function createChild(
        GeoUnitId $id,
        CountryCode $countryCode,
        GeographyLevel $level,
        self $parent,
        LocalizedName $name,
        ?GeographicCode $officialCode = null
    ): self {
        // GEO-INV-02: Child level must be higher (more granular) than parent level
        if (!$level->isHigherThan($parent->level)) {
            throw new DomainException(
                "Child level {$level->toInt()} must be higher than parent level {$parent->level->toInt()}"
            );
        }

        // Validate same country
        if (!$countryCode->equals($parent->countryCode)) {
            throw new DomainException(
                "Child country {$countryCode->toString()} must match parent country {$parent->countryCode->toString()}"
            );
        }

        // Build path: parent path + child ID
        $childPath = $parent->path->toString() . '.' . $id->toInt();

        return new self(
            $id,
            $countryCode,
            $level,
            $parent->id,
            GeoPath::fromString($childPath),
            $name,
            $officialCode
        );
    }

    /**
     * Validate entity invariants
     */
    private function validate(): void
    {
        // GEO-INV-01: Cannot be own parent
        if ($this->parentId !== null && $this->parentId->equals($this->id)) {
            throw new DomainException('Unit cannot be its own parent');
        }

        // GEO-INV-03: Path must match parent hierarchy
        $this->validatePathMatchesParent();

        // GEO-INV-05: Temporal range consistency
        if ($this->validFrom !== null && $this->validTo !== null && $this->validFrom > $this->validTo) {
            throw new DomainException(
                'valid_from must be before or equal to valid_to'
            );
        }
    }

    /**
     * Validate that path correctly represents parent hierarchy
     */
    private function validatePathMatchesParent(): void
    {
        if ($this->parentId === null) {
            // Root unit: path should be just its ID
            $expectedPath = (string) $this->id->toInt();
            if ($this->path->toString() !== $expectedPath) {
                throw new DomainException(
                    "Root unit path should be '{$expectedPath}', got '{$this->path}'"
                );
            }
        } else {
            // Child unit: path should end with its ID and contain parent path
            $pathIds = $this->path->getLevelIds();
            $lastId = end($pathIds);

            if ($lastId !== $this->id->toInt()) {
                throw new DomainException(
                    "Path should end with unit ID {$this->id->toInt()}, ends with {$lastId}"
                );
            }

            // Check parent ID is in path (second to last if immediate child)
            $parentInPath = false;
            foreach ($pathIds as $pathId) {
                if ($pathId === $this->parentId->toInt()) {
                    $parentInPath = true;
                    break;
                }
            }

            if (!$parentInPath) {
                throw new DomainException(
                    "Path should contain parent ID {$this->parentId->toInt()}"
                );
            }
        }
    }

    /**
     * Change parent unit
     * Updates path and validates new hierarchy
     */
    public function changeParent(?GeoUnitId $newParentId): void
    {
        if ($newParentId !== null && $newParentId->equals($this->id)) {
            throw new DomainException('Unit cannot be its own parent');
        }

        $this->parentId = $newParentId;

        // Note: Path update requires recalculating based on new parent hierarchy
        // This should be done by GeographyPathService or repository
        // For now, we just change parent, path must be updated separately
    }

    /**
     * Update path (should match parent hierarchy)
     */
    public function updatePath(GeoPath $newPath): void
    {
        $this->path = $newPath;
        $this->validatePathMatchesParent();
    }

    /**
     * Update unit name
     */
    public function updateName(LocalizedName $newName): void
    {
        $this->name = $newName;
    }

    /**
     * Update official code
     */
    public function updateOfficialCode(?GeographicCode $newCode): void
    {
        $this->officialCode = $newCode;
    }

    /**
     * Check if this unit is ancestor of another unit
     */
    public function isAncestorOf(self $other): bool
    {
        return $other->path->isDescendantOf($this->path);
    }

    /**
     * Check if this unit is descendant of another unit
     */
    public function isDescendantOf(self $other): bool
    {
        return $this->path->isDescendantOf($other->path);
    }

    /**
     * Check if this unit is root (level 1, no parent)
     */
    public function isRoot(): bool
    {
        return $this->level->isRoot() && $this->parentId === null;
    }

    /**
     * Get depth in hierarchy (path depth)
     */
    public function getDepth(): int
    {
        return $this->path->getDepth();
    }

    /**
     * Getters (Aggregate Roots can expose their state)
     */
    public function getId(): GeoUnitId
    {
        return $this->id;
    }

    public function getCountryCode(): CountryCode
    {
        return $this->countryCode;
    }

    public function getLevel(): GeographyLevel
    {
        return $this->level;
    }

    public function getParentId(): ?GeoUnitId
    {
        return $this->parentId;
    }

    public function getPath(): GeoPath
    {
        return $this->path;
    }

    public function getName(): LocalizedName
    {
        return $this->name;
    }

    public function getOfficialCode(): ?GeographicCode
    {
        return $this->officialCode;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Increment optimistic locking version.
     */
    public function incrementVersion(): void
    {
        $this->version++;
    }

    public function getValidFrom(): ?\DateTimeImmutable
    {
        return $this->validFrom;
    }

    public function getValidTo(): ?\DateTimeImmutable
    {
        return $this->validTo;
    }

    /**
     * Get English name (convenience method)
     */
    public function getEnglishName(): ?string
    {
        return $this->name->getEnglish();
    }

    /**
     * Get name with fallback
     */
    public function getNameWithFallback(array $preferredLanguages = ['en', 'np']): string
    {
        return $this->name->getWithFallback($preferredLanguages);
    }

    /**
     * Check if this unit is active at the given point in time.
     * A unit is active if valid_from ≤ now ≤ valid_to (or boundaries are null).
     */
    public function isActiveAt(\DateTimeImmutable $now): bool
    {
        if ($this->validFrom !== null && $this->validFrom > $now) {
            return false;
        }

        if ($this->validTo !== null && $this->validTo < $now) {
            return false;
        }

        return true;
    }

}