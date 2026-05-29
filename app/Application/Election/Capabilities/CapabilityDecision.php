<?php

namespace App\Application\Election\Capabilities;

final readonly class CapabilityDecision
{
    private function __construct(
        public ?CapabilityDenialReason $reason = null,
        public ?string $detail = null,
        public CapabilitySeverity $severity = CapabilitySeverity::HardBlock,
    ) {}

    public static function authorized(): self
    {
        return new self();
    }

    public static function abstain(): self
    {
        return new self();
    }

    public static function prohibited(
        CapabilityDenialReason $reason,
        ?string $detail = null,
        CapabilitySeverity $severity = CapabilitySeverity::HardBlock,
    ): self {
        return new self($reason, $detail, $severity);
    }

    public static function shortCircuit(CapabilityDenialReason $reason, ?string $detail = null): self
    {
        return new self($reason, $detail, CapabilitySeverity::HardBlock);
    }

    public function allows(): bool
    {
        return $this->reason === null;
    }

    public function denies(): bool
    {
        return $this->reason !== null;
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
