<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\DTOs;

use JsonSerializable;

/**
 * Hierarchical geographic unit response with recursive children.
 *
 * Constructed by GeoHierarchyBuilder from a flat list of GeoUnitResponse DTOs.
 * Children are pre-linked in a single O(n) pass — no lazy loading, no recursion.
 */
final readonly class GeoHierarchyResponse implements JsonSerializable
{
    /** @param array<GeoHierarchyResponse> $children */
    public function __construct(
        public int $id,
        public string $countryCode,
        public int $adminLevel,
        public string $adminType,
        public ?int $parentId,
        public ?string $code,
        public array $name,
        public bool $isActive,
        public ?string $validFrom,
        public ?string $validTo,
        public array $children = [],
    ) {}

    public function hasChildren(): bool
    {
        return $this->children !== [];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'country_code' => $this->countryCode,
            'admin_level' => $this->adminLevel,
            'admin_type' => $this->adminType,
            'parent_id' => $this->parentId,
            'code' => $this->code,
            'name' => $this->name,
            'is_active' => $this->isActive,
            'valid_from' => $this->validFrom,
            'valid_to' => $this->validTo,
            'children' => array_map(fn (self $child) => $child->jsonSerialize(), $this->children),
        ];
    }
}
