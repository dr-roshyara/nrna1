<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\Services;

use App\Contexts\Geography\Domain\ValueObjects\GeoReference;

final class GeoReferenceParser
{
    /**
     * Parse a geographic reference string in either legacy or composite format.
     *
     * @param string $value Legacy format: "np.3.15.234" or composite: "region:europe.country:DE.geo:3.15"
     * @return GeoReference The parsed reference
     * @throws \InvalidArgumentException If the string is invalid or empty
     */
    public function parse(string $value): GeoReference
    {
        return GeoReference::fromString($value);
    }
}
