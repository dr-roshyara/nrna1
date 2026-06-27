<?php

namespace App\Application\Election\Capabilities;

final readonly class ElectionCapabilityEntry
{
    public function __construct(
        public bool $allowed,
        public ?CapabilityDenialReason $denialReason = null,
        public ?string $denialDetail = null,
        public ?CapabilitySeverity $severity = null,
        public ?string $requiredRole = null,
        public string $lifecycleState = '',
    ) {}

    public static function allowed(
        ?string $requiredRole = null,
        string $lifecycleState = '',
    ): self {
        return new self(
            true,
            null,
            null,
            null,
            $requiredRole,
            $lifecycleState,
        );
    }

    public static function denied(
        CapabilityDenialReason $reason,
        ?string $detail = null,
        CapabilitySeverity $severity = CapabilitySeverity::HardBlock,
        string $lifecycleState = '',
    ): self {
        return new self(
            false,
            $reason,
            $detail,
            $severity,
            null,
            $lifecycleState,
        );
    }

    public function isDenied(): bool
    {
        return !$this->allowed;
    }
}
