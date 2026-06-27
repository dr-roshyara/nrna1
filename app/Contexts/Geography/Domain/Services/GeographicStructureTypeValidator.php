<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Services;

use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;

final class GeographicStructureTypeValidator
{
    private const TYPE_ORDER = ['static' => 0, 'region' => 1, 'country' => 2, 'geo_unit' => 3];

    public function validate(GeographicStructure $structure): void
    {
        $levels = $structure->getLevels();

        // Check sequential indices starting at 1
        $indices = array_map(fn($l) => $l->index, $levels);
        for ($i = 0; $i < count($indices); $i++) {
            if ($indices[$i] !== $i + 1) {
                throw new \DomainException('Level indices must be sequential starting at 1');
            }
        }

        // Track type names for error messages
        $typeNames = array_flip(self::TYPE_ORDER);
        $previousType = null;
        $previousOrder = -1;
        $hasStatic = false;

        foreach ($levels as $level) {
            // Validate static type only at index 1
            if ($level->type === 'static') {
                if ($level->index !== 1) {
                    throw new \DomainException('static type must be at index 1');
                }
                $hasStatic = true;
            }

            // Validate geo_unit has db_level
            if ($level->type === 'geo_unit' && $level->dbLevel === null) {
                throw new \DomainException('geo_unit type must have non-null dbLevel');
            }

            // Get type order
            if (!isset(self::TYPE_ORDER[$level->type])) {
                throw new \DomainException("Unknown type: {$level->type}");
            }

            $currentOrder = self::TYPE_ORDER[$level->type];

            // Check type ordering (no reversal)
            if ($currentOrder < $previousOrder) {
                $prevTypeName = $typeNames[$previousOrder];
                throw new \DomainException("{$level->type} type cannot appear before {$prevTypeName} type");
            }

            $previousType = $level->type;
            $previousOrder = $currentOrder;
        }
    }
}
