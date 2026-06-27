<?php

namespace App\Application\Election\Capabilities;

use App\Domain\Election\Enum\CapabilityOutcome;

final readonly class CapabilityTraceEntry
{
    public function __construct(
        public string $policyName,
        public CapabilityOutcome $outcome,
        public ?CapabilityDenialReason $reason = null,
        public ?string $detail = null,
    ) {
        if ($outcome === CapabilityOutcome::ShortCircuit && $reason === null) {
            throw new \InvalidArgumentException('Short circuit requires denial reason');
        }
    }

    public static function granted(string $policyName): self
    {
        return new self($policyName, CapabilityOutcome::Granted);
    }

    public static function abstained(string $policyName): self
    {
        return new self($policyName, CapabilityOutcome::Abstained);
    }

    public static function denied(string $policyName, CapabilityDenialReason $reason, ?string $detail = null): self
    {
        return new self($policyName, CapabilityOutcome::Denied, $reason, $detail);
    }

    public static function shortCircuit(string $policyName, CapabilityDenialReason $reason, ?string $detail = null): self
    {
        return new self($policyName, CapabilityOutcome::ShortCircuit, $reason, $detail);
    }

    public function isDenied(): bool
    {
        return $this->outcome === CapabilityOutcome::Denied;
    }

    public function isGranted(): bool
    {
        return $this->outcome === CapabilityOutcome::Granted;
    }

    public function isAbstained(): bool
    {
        return $this->outcome === CapabilityOutcome::Abstained;
    }

    public function isShortCircuit(): bool
    {
        return $this->outcome === CapabilityOutcome::ShortCircuit;
    }

    public function isTerminal(): bool
    {
        return $this->isDenied() || $this->isShortCircuit();
    }
}
