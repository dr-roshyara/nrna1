<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\DTOs;

use JsonSerializable;

final readonly class GovernanceGeoUnitResponse implements JsonSerializable
{
    /** @param array<GovernanceGeoUnitResponse>|null $children */
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public array $nameLocal,
        public int $adminLevel,
        public string $adminType,
        public ?int $parentId,
        public ?string $path,
        public int $childrenCount,
        public bool $isActive,
        public string $countryCode,
        public ?int $governanceLevel,
        public ?string $governanceCommitteeName,
        public ?string $governanceCommitteeCode,
        public int $depth = 0,
        public ?array $children = null,
    ) {}

    public static function fromEloquentRow(\stdClass $row): self
    {
        $path = $row->path ?? null;
        $depth = $path !== null && $path !== '/'
            ? count(array_filter(explode('/', trim($path, '/'))))
            : 0;

        $nameLocal = null;
        if (isset($row->name_local)) {
            $nameLocal = is_string($row->name_local) ? json_decode($row->name_local, true) : (array) $row->name_local;
        }

        return new self(
            id: (int) $row->id,
            code: $row->code ?? '',
            name: $row->name ?? '',
            nameLocal: $nameLocal ?? [],
            adminLevel: (int) $row->admin_level,
            adminType: $row->admin_type ?? '',
            parentId: $row->parent_id !== null ? (int) $row->parent_id : null,
            path: $path,
            childrenCount: (int) ($row->children_count ?? 0),
            isActive: (bool) ($row->is_active ?? true),
            countryCode: $row->country_code ?? '',
            governanceLevel: $row->governance_level ?? null,
            governanceCommitteeName: $row->governance_committee_name ?? null,
            governanceCommitteeCode: $row->governance_committee_code ?? null,
            depth: $depth,
        );
    }

    public function hasChildren(): bool
    {
        return $this->children !== null && $this->children !== [];
    }

    public function withChildren(array $children): self
    {
        return new self(
            id: $this->id,
            code: $this->code,
            name: $this->name,
            nameLocal: $this->nameLocal,
            adminLevel: $this->adminLevel,
            adminType: $this->adminType,
            parentId: $this->parentId,
            path: $this->path,
            childrenCount: $this->childrenCount,
            isActive: $this->isActive,
            countryCode: $this->countryCode,
            governanceLevel: $this->governanceLevel,
            governanceCommitteeName: $this->governanceCommitteeName,
            governanceCommitteeCode: $this->governanceCommitteeCode,
            depth: $this->depth,
            children: $children,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id'                         => $this->id,
            'code'                       => $this->code,
            'name'                       => $this->name,
            'name_local'                 => $this->nameLocal,
            'admin_level'                => $this->adminLevel,
            'admin_type'                 => $this->adminType,
            'parent_id'                  => $this->parentId,
            'path'                       => $this->path,
            'children_count'             => $this->childrenCount,
            'is_active'                  => $this->isActive,
            'country_code'               => $this->countryCode,
            'governance_level'           => $this->governanceLevel,
            'governance_committee_name'  => $this->governanceCommitteeName,
            'governance_committee_code'  => $this->governanceCommitteeCode,
            'depth'                      => $this->depth,
            'children'                   => $this->children !== null
                ? array_map(fn (self $child) => $child->jsonSerialize(), $this->children)
                : null,
        ];
    }

    public function toArray(): array
    {
        return $this->jsonSerialize();
    }
}
