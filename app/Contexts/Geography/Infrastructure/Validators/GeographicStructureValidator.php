<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Infrastructure\Validators;

use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;
use Illuminate\Support\Facades\DB;

final class GeographicStructureValidator
{
    public function assertValidForCountry(GeographicStructure $structure, string $countryCode): void
    {
        $maxDbLevel = DB::table('geo_administrative_units')
            ->where('country_code', strtoupper($countryCode))
            ->max('admin_level');

        if (!$maxDbLevel) {
            throw new \DomainException("No geography data found for country '{$countryCode}'");
        }

        foreach ($structure->getLevels() as $level) {
            if ($level->dbLevel > $maxDbLevel) {
                throw new \DomainException(
                    "db_level {$level->dbLevel} exceeds available levels (max {$maxDbLevel}) for country '{$countryCode}'"
                );
            }
        }
    }
}
