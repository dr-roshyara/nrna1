<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability;

use App\Contexts\Membership\Domain\Committee\Exceptions\CapabilityDeniedException;

final readonly class CapabilityEvaluation
{
    public function __construct(
        public bool $allowed,
        public string $reason,
        public array $failedChecks = [],
        public ?CapabilityDecisionTrace $trace = null,
    ) {}

    public static function allow(string $reason): self
    {
        return new self(allowed: true, reason: $reason);
    }

    public static function deny(string $reason, array $checks = []): self
    {
        return new self(allowed: false, reason: $reason, failedChecks: $checks);
    }

    public function withTrace(?CapabilityDecisionTrace $trace): self
    {
        return new self($this->allowed, $this->reason, $this->failedChecks, $trace);
    }

    public function assertAllowed(): void
    {
        if (!$this->allowed) {
            throw new CapabilityDeniedException($this->reason);
        }
    }
}
