<?php

namespace App\Services\GeoLocation\ValueObjects;

final readonly class Location
{
    public function __construct(
        public ?string $countryCode,
        public ?string $countryName,
        public ?string $region,
        public ?string $city,
        public ?string $postalCode,
        public ?float $latitude,
        public ?float $longitude,
        public ?string $timezone,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'country_code' => $this->countryCode,
            'country_name' => $this->countryName,
            'region' => $this->region,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'lat' => $this->latitude,
            'lon' => $this->longitude,
            'timezone' => $this->timezone,
        ], fn($v) => $v !== null);
    }

    public static function fromIpApiResponse(array $data): ?self
    {
        if (($data['status'] ?? '') !== 'success') {
            return null;
        }

        return new self(
            countryCode: $data['countryCode'] ?? null,
            countryName: $data['country'] ?? null,
            region: $data['regionName'] ?? null,
            city: $data['city'] ?? null,
            postalCode: $data['zip'] ?? null,
            latitude: $data['lat'] ?? null,
            longitude: $data['lon'] ?? null,
            timezone: $data['timezone'] ?? null,
        );
    }
}
