<?php

declare(strict_types=1);

namespace App\Contexts\Governance\API\V1\Responses;

final readonly class CommitteeHierarchyResponse implements \JsonSerializable
{
    /** @var CommitteeHierarchyResponse[] */
    public array $children;

    public function __construct(
        public string $id,
        public string $name,
        public int $level,
        public ?string $parentId,
        public int $childrenCount,
        array $children,
        public CommitteeGovernanceResponse $governance,
    ) {
        $this->children = $children;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'parentId' => $this->parentId,
            'childrenCount' => $this->childrenCount,
            'governance' => $this->governance->jsonSerialize(),
            'children' => array_map(fn (self $c) => $c->jsonSerialize(), $this->children),
        ];
    }
}
