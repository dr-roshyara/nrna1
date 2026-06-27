<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\DTOs;

final readonly class GeoUnitQueryFilter
{
    public function __construct(
        public ?string $search = null,
        public ?int $level = null,
        public ?string $country = null,
    ) {}

    /**
     * Create from HTTP request parameters.
     */
    public static function fromRequest(array $params): self
    {
        return new self(
            search:  isset($params['search']) && $params['search'] !== '' ? (string) $params['search'] : null,
            level:   isset($params['level']) && $params['level'] !== '' ? (int) $params['level'] : null,
            country: isset($params['country']) && $params['country'] !== '' ? strtoupper((string) $params['country']) : null,
        );
    }

    /**
     * Return non-null filter criteria for query building.
     */
    public function toCriteria(): array
    {
        $criteria = [];

        if ($this->search !== null) {
            $criteria['search'] = $this->search;
        }

        if ($this->level !== null) {
            $criteria['level'] = $this->level;
        }

        if ($this->country !== null) {
            $criteria['country'] = $this->country;
        }

        return $criteria;
    }

    public function hasFilters(): bool
    {
        return $this->search !== null || $this->level !== null || $this->country !== null;
    }
}
