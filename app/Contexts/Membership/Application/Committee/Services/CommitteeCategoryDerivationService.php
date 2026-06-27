<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Services;

use App\Contexts\Membership\Domain\Committee\Policies\CommitteeClassificationPolicy;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeCategory;

/**
 * CommitteeCategoryDerivationService
 *
 * Application service that encapsulates the derivation of CommitteeCategory
 * from either an explicit type string (e.g., 'youth', 'women', 'central') or
 * a geo unit ID (via projection building + classification mapping).
 *
 * This prevents the controller from becoming a "domain decision broker" by
 * resolving domain services directly via service locator.
 *
 * Priority:
 *   1. Explicit type (for wing types: youth/women/student)
 *   2. Derive from geo unit's admin level
 *   3. Default to CENTRAL
 */
final readonly class CommitteeCategoryDerivationService
{
    public function __construct(
        private GeoSemanticProjectionBuilder $projectionBuilder,
        private CommitteeClassificationPolicy $classificationPolicy,
    ) {}

    public function derive(?string $explicitType, ?int $geoUnitId): CommitteeCategory
    {
        // Explicit type takes priority (for wing types: youth/women/student)
        if ($explicitType !== null) {
            return CommitteeCategory::from($explicitType);
        }

        // Derive from geo unit when available
        if ($geoUnitId !== null) {
            $projection = $this->projectionBuilder->build($geoUnitId);
            if ($projection !== null) {
                return $this->classificationPolicy->mapAdminLevelToCategory($projection->adminLevel);
            }
        }

        // Default to central when neither type nor geo is provided
        return CommitteeCategory::CENTRAL;
    }
}
