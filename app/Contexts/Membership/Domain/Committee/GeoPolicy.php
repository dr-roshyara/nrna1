<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

enum GeoPolicy: string
{
    case NONE = 'none';
    case REQUIRED = 'required';
    case OPTIONAL = 'optional';

    public function requiresGeo(): bool
    {
        return $this === self::REQUIRED;
    }

    public function validate(?GeoScope $geoScope): void
    {
        if ($this === self::REQUIRED && $geoScope === null) {
            throw new \DomainException('GeoPolicy::REQUIRED requires a GeoScope');
        }
    }
}
