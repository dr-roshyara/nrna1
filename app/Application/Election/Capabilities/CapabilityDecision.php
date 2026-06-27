<?php

namespace App\Application\Election\Capabilities;

use App\Domain\Election\Enum\CapabilityOutcome;

final readonly class CapabilityDecision
{
    private function __construct(
        private CapabilityOutcome $outcome,
        public ?CapabilityDenialReason $reason = null,
        public ?string $detail = null,
        public CapabilitySeverity $severity = CapabilitySeverity::HardBlock,
    ) {}

    public static function authorized(): self
    {
        return new self(CapabilityOutcome::Granted);
    }

    public static function abstain(): self
    {
        return new self(CapabilityOutcome::Abstained);
    }

    public static function prohibited(
        CapabilityDenialReason $reason,
        ?string $detail = null,
        CapabilitySeverity $severity = CapabilitySeverity::HardBlock,
    ): self {
        return new self(CapabilityOutcome::Denied, $reason, $detail, $severity);
    }

    public static function shortCircuit(CapabilityDenialReason $reason, ?string $detail = null): self
    {
        return new self(CapabilityOutcome::ShortCircuit, $reason, $detail, CapabilitySeverity::HardBlock);
    }

    public function allows(): bool
    {
        return $this->reason === null;
    }

    public function denies(): bool
    {
        return $this->reason !== null;
    }

    public function isAbstain(): bool
    {
        return $this->outcome === CapabilityOutcome::Abstained;
    }

    // Legacy aliases for backward compatibility during transition
    public static function grant(): self
    {
        return self::authorized();
    }

    public static function deny(
        CapabilityDenialReason $reason,
        ?string $detail = null,
        CapabilitySeverity $severity = CapabilitySeverity::HardBlock,
    ): self {
        return self::prohibited($reason, $detail, $severity);
    }

    public function isShortCircuit(): bool
    {
        return $this->reason !== null && $this->severity === CapabilitySeverity::HardBlock;
    }
}
