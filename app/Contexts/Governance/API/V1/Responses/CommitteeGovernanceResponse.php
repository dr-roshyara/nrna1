<?php

declare(strict_types=1);

namespace App\Contexts\Governance\API\V1\Responses;

final readonly class CommitteeGovernanceResponse implements \JsonSerializable
{
    public function __construct(
        public string $operationalState,
        public string $temporalState,
        public string $legitimacy,
        public bool $canAct,
        public bool $isFullyOperational,
        public ?string $termStart,
        public ?string $termEnd,
        public ?string $evaluatedAt,
        public string $projectionGeneration,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'operationalState' => $this->operationalState,
            'temporalState' => $this->temporalState,
            'legitimacy' => $this->legitimacy,
            'canAct' => $this->canAct,
            'isFullyOperational' => $this->isFullyOperational,
            'termStart' => $this->termStart,
            'termEnd' => $this->termEnd,
            'evaluatedAt' => $this->evaluatedAt,
            'projectionGeneration' => $this->projectionGeneration,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            operationalState: $data['operationalState'] ?? 'UNKNOWN',
            temporalState: $data['temporalState'] ?? 'UNKNOWN',
            legitimacy: $data['legitimacy'] ?? 'UNKNOWN',
            canAct: (bool) ($data['canAct'] ?? false),
            isFullyOperational: (bool) ($data['isFullyOperational'] ?? false),
            termStart: $data['termStart'] ?? null,
            termEnd: $data['termEnd'] ?? null,
            evaluatedAt: $data['evaluatedAt'] ?? null,
            projectionGeneration: $data['projectionGeneration'] ?? '',
        );
    }
}
