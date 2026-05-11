<?php

declare(strict_types=1);

namespace App\Contexts\Governance\API\V1\Responses;

final readonly class CommitteeChildrenResponse implements \JsonSerializable
{
    /** @var CommitteeSummaryResponse[] */
    public array $children;

    public function __construct(
        public CommitteeSummaryResponse $committee,
        array $children,
        public int $total,
    ) {
        $this->children = $children;
    }

    public function jsonSerialize(): array
    {
        return [
            'committee' => $this->committee->jsonSerialize(),
            'children' => array_map(fn (CommitteeSummaryResponse $c) => $c->jsonSerialize(), $this->children),
            'total' => $this->total,
        ];
    }
}
