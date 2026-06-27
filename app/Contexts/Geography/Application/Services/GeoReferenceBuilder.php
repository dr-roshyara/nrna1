<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\Services;

use App\Contexts\Geography\Domain\ValueObjects\GeoReference;
use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;

final class GeoReferenceBuilder
{
    /**
     * Build a canonical GeoReference from selections, validating against structure.
     *
     * @param array $selections {
     *   region?: string,
     *   country?: string,
     *   geo?: array<int>
     * }
     * @param GeographicStructure $structure The organisation's geographic structure
     * @return GeoReference The canonical geographic reference
     * @throws \DomainException If required levels are missing
     */
    public function build(array $selections, GeographicStructure $structure): GeoReference
    {
        // Validate required levels are present
        foreach ($structure->getLevels() as $level) {
            if (!$level->required) {
                continue;
            }

            $key = match ($level->type) {
                'region' => 'region',
                'country' => 'country',
                'geo_unit' => 'geo',
                default => null,
            };

            if ($key && empty($selections[$key])) {
                throw new \DomainException("Required level '{$level->label}' ({$level->type}) not selected");
            }
        }

        return GeoReference::fromSelections($selections);
    }
}
