<?php

declare(strict_types=1);

namespace App\Contexts\Governance\API\V1\Responses;

final readonly class CommitteeSummaryResponse implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $name,
        public int $level,
        public string $type,
        public string $operationalState,
        public bool $canAct,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'type' => $this->type,
            'operationalState' => $this->operationalState,
            'canAct' => $this->canAct,
        ];
    }
}
