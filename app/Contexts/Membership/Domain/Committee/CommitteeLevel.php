<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

final readonly class CommitteeLevel
{
    private function __construct(
        public int $index,
        public ?string $code,
        public string $name,
        public GeoPolicy $geoPolicy,
        public ?GeoScope $geoScope,
        public array $roleLimits,
        public int $minMembershipYears,
        public ?array $ageRange,
        public ?string $genderRequirement,
    ) {}

    public static function create(
        int $index,
        ?string $code,
        string $name,
        GeoPolicy $geoPolicy,
        ?GeoScope $geoScope,
        array $roleLimits,
        int $minMembershipYears,
        ?array $ageRange,
        ?string $genderRequirement,
    ): self {
        if ($index < 1 || $index > 10) {
            throw new \InvalidArgumentException('Level index must be between 1 and 10');
        }

        if ($geoPolicy === GeoPolicy::REQUIRED && $geoScope === null) {
            throw new \DomainException('Level with REQUIRED geo policy must have a GeoScope');
        }

        return new self(
            index: $index,
            code: $code,
            name: $name,
            geoPolicy: $geoPolicy,
            geoScope: $geoScope,
            roleLimits: $roleLimits,
            minMembershipYears: $minMembershipYears,
            ageRange: $ageRange,
            genderRequirement: $genderRequirement,
        );
    }

    public static function fromArray(array $data): self
    {
        $geoPolicy = GeoPolicy::tryFrom($data['geo_policy'] ?? 'none');
        if ($geoPolicy === null) {
            throw new \InvalidArgumentException('Invalid geo_policy value');
        }

        $geoScope = null;
        if (isset($data['geo_scope']) && $data['geo_scope'] !== null) {
            $geoScope = new GeoScope($data['geo_scope']);
        }

        return self::create(
            index: (int) $data['index'],
            code: $data['code'] ?? null,
            name: (string) $data['name'],
            geoPolicy: $geoPolicy,
            geoScope: $geoScope,
            roleLimits: $data['role_limits'] ?? [],
            minMembershipYears: (int) ($data['min_membership_years'] ?? 0),
            ageRange: $data['age_range'] ?? null,
            genderRequirement: $data['gender_requirement'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'index' => $this->index,
            'code' => $this->code,
            'name' => $this->name,
            'geo_policy' => $this->geoPolicy->value,
            'geo_scope' => $this->geoScope?->code,
            'role_limits' => $this->roleLimits,
            'min_membership_years' => $this->minMembershipYears,
            'age_range' => $this->ageRange,
            'gender_requirement' => $this->genderRequirement,
        ];
    }

    public function levelCode(): ?string
    {
        return $this->code;
    }
}
