<?php

namespace App\Application\Election\Capabilities;

use App\Domain\Election\Enum\ElectionLifecycleState;

final readonly class ElectionCapabilitySnapshot
{
    /** @param array<string, ElectionCapabilityEntry> $capabilities */
    public function __construct(
        public ElectionLifecycleState $lifecycleState,
        public array $capabilities = [],
        public bool $isSuspended = false,
        public CapabilityTrace $trace = new CapabilityTrace(),
    ) {}

    public function can(string $action): bool
    {
        return $this->capabilities[$action]?->allowed ?? false;
    }

    public function denialReason(string $action): ?CapabilityDenialReason
    {
        return $this->capabilities[$action]?->denialReason ?? null;
    }

    public function denialDetail(string $action): ?string
    {
        return $this->capabilities[$action]?->denialDetail ?? null;
    }

    public function denialSeverity(string $action): ?CapabilitySeverity
    {
        return $this->capabilities[$action]?->severity ?? null;
    }
}
