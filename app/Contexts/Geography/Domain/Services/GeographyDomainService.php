<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Services;

use App\Contexts\Geography\Domain\Repositories\GeoUnitRepositoryInterface;

final class GeographyDomainService
{
    public function __construct(
        private readonly GeoUnitRepositoryInterface $geoUnitRepository
    ) {}

    /**
     * Validates that the given unit IDs form a valid, connected hierarchy
     * for the given country. Returns false if any ID does not exist or
     * is not a proper ancestor/descendant of the next.
     */
    public function validateHierarchy(string $countryCode, array $unitIds): bool
    {
        if (empty($unitIds)) {
            return true;
        }

        foreach ($unitIds as $id) {
            if (!$this->geoUnitRepository->exists($id, $countryCode)) {
                return false;
            }
        }

        // Verify parent-child chain
        for ($i = 1; $i < count($unitIds); $i++) {
            $unit = $this->geoUnitRepository->findById($unitIds[$i]);
            if ($unit === null || $unit->parentId() !== $unitIds[$i - 1]) {
                return false;
            }
        }

        return true;
    }
}
