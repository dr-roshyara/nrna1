<?php

declare(strict_types=1);

namespace App\Contexts\Governance\API\V1\Responses;

final readonly class GovernanceHealthResponse implements \JsonSerializable
{
    public function __construct(
        public string $status,
        public string $projectionGeneration,
        public ?string $rebuiltAt,
        public int $staleCommitteeCount,
        public int $totalCommittees,
        public int $projectionAgeMs = 0,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status,
            'projectionGeneration' => $this->projectionGeneration,
            'rebuiltAt' => $this->rebuiltAt,
            'staleCommitteeCount' => $this->staleCommitteeCount,
            'totalCommittees' => $this->totalCommittees,
            'projectionAgeMs' => $this->projectionAgeMs,
        ];
    }
}
