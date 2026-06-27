<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

final readonly class GeoReference
{
    private function __construct(
        public ?string $region,
        public ?string $country,
        public GeoPath $geoPath,
        private bool $isLegacy,
    ) {}

    public static function fromString(string $value): self
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('GeoReference cannot be empty');
        }

        return str_contains($value, ':') ? self::parseComposite($value) : self::parseLegacy($value);
    }

    public static function fromSelections(array $selections): self
    {
        $region = $selections['region'] ?? null;
        $country = $selections['country'] ? strtoupper($selections['country']) : null;
        $geoUnits = $selections['geo'] ?? [];
        $geoPath = GeoPath::fromArray($geoUnits);

        return new self($region, $country, $geoPath, false);
    }

    private static function parseLegacy(string $value): self
    {
        $parts = explode('.', strtolower($value));
        if (strlen($parts[0]) !== 2) {
            throw new \InvalidArgumentException('Legacy format: first segment must be 2-char country code');
        }

        $country = strtoupper($parts[0]);
        $geoUnits = array_slice($parts, 1);
        $geoPath = GeoPath::fromArray($geoUnits);

        return new self(null, $country, $geoPath, true);
    }

    private static function parseComposite(string $value): self
    {
        $region = null;
        $country = null;
        $geoPath = GeoPath::empty();

        // Parse composite format more carefully
        // Format: region:value.country:value.geo:3.4.5 (where geo values are separated by .)
        // We need to parse segments separated by . but keep geo values together
        $geoStart = strpos($value, 'geo:');
        if ($geoStart !== false) {
            // Split everything before geo separately
            $beforeGeo = substr($value, 0, $geoStart - 1); // -1 to remove the trailing .
            $geoData = substr($value, $geoStart + 4); // +4 to skip 'geo:'
            
            // Parse region and country
            foreach (explode('.', $beforeGeo) as $segment) {
                if (!str_contains($segment, ':')) {
                    continue;
                }
                [$type, $val] = explode(':', $segment, 2);
                if ($type === 'region') {
                    $region = $val;
                } elseif ($type === 'country') {
                    $country = strtoupper($val);
                }
            }
            
            // Parse geo data
            $geoPath = GeoPath::fromArray(explode('.', $geoData));
        } else {
            // No geo data, just parse region and country
            foreach (explode('.', $value) as $segment) {
                if (!str_contains($segment, ':')) {
                    continue;
                }
                [$type, $val] = explode(':', $segment, 2);
                if ($type === 'region') {
                    $region = $val;
                } elseif ($type === 'country') {
                    $country = strtoupper($val);
                }
            }
        }

        return new self($region, $country, $geoPath, false);
    }

    public function toString(): string
    {
        if ($this->isLegacy) {
            $result = strtolower($this->country ?? '');
            if (!$this->geoPath->isEmpty()) {
                $result .= '.' . $this->geoPath->toString();
            }
            return $result;
        }

        $parts = [];
        if ($this->region) {
            $parts[] = "region:{$this->region}";
        }
        if ($this->country) {
            $parts[] = "country:{$this->country}";
        }
        if (!$this->geoPath->isEmpty()) {
            $parts[] = "geo:{$this->geoPath->toString()}";
        }

        return implode('.', $parts);
    }

    public function isLegacy(): bool
    {
        return $this->isLegacy;
    }

    public function getCountryCode(): ?string
    {
        return $this->country;
    }
}
