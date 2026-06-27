<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

use App\Contexts\Geography\Application\Services\GeographyService;
use App\Contexts\Geography\Domain\Services\GeographyDomainService;
use App\Contexts\Membership\Domain\Services\GeographyResolverInterface;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;

/**
 * Geography Validation Adapter
 *
 * Anti-Corruption Layer between Membership and Geography contexts.
 * Delegates parsing to GeoReference VO and validation to GeographyDomainService.
 */
final class GeographyValidationAdapter implements GeographyResolverInterface
{
    public function __construct(
        private readonly GeographyDomainService $domainService,
        private readonly GeographyService $geographyService
    ) {}

    public function validate(?string $geoReference): ?GeoReference
    {
        if ($geoReference === null) {
            return null;
        }

        try {
            $geo = GeoReference::fromString($geoReference);
        } catch (\InvalidArgumentException) {
            return null;
        }

        $unitIds = $geo->getValidUnitIds();

        if (!empty($unitIds)) {
            if (!$this->domainService->validateHierarchy(strtoupper($geo->countryCode()), $unitIds)) {
                return null;
            }
        }

        return $geo;
    }

    public function resolveName(string $geoReference): ?string
    {
        try {
            $geo     = GeoReference::fromString($geoReference);
            $unitIds = $geo->getValidUnitIds();

            if (empty($unitIds)) {
                return null;
            }

            $data = $this->geographyService->getUnitWithAncestors((int) end($unitIds));
            return $data['full_path'] ?? null;
        } catch (\InvalidArgumentException) {
            return null;
        }
    }
}
