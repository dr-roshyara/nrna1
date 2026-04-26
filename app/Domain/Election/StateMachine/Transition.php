<?php

namespace App\Domain\Election\StateMachine;

use InvalidArgumentException;

final class Transition
{
    public readonly string $actorId;

    public function __construct(
        public readonly string $action,
        string|int|null $actorId,
        public readonly ?string $reason = null,
        public readonly TransitionTrigger $trigger = TransitionTrigger::MANUAL,
        public readonly array $metadata = []
    ) {
        // FIX 5: Empty action invariant
        if (trim($action) === '') {
            throw new InvalidArgumentException('Transition action cannot be empty.');
        }

        // FIX: actorId always cast to string — handles auth()->id() (int) and 'system' (string)
        $this->actorId = (string) ($actorId ?? 'system');
    }

    // MANUAL factory — used by controllers and domain methods
    public static function manual(
        string $action,
        string|int $actorId,
        ?string $reason = null,
        array $metadata = []
    ): self {
        return new self($action, $actorId, $reason, TransitionTrigger::MANUAL, $metadata);
    }

    // AUTOMATIC factory — used by scheduler/cron (actorId becomes 'system')
    public static function automatic(
        string $action,
        TransitionTrigger $trigger = TransitionTrigger::TIME,
        ?string $reason = null,
        array $metadata = []
    ): self {
        return new self($action, 'system', $reason, $trigger, $metadata);
    }

    // GRACE_PERIOD factory — shorthand for automatic with grace_period trigger
    public static function gracePeriod(string $action, ?string $reason = null, array $metadata = []): self
    {
        return self::automatic($action, TransitionTrigger::GRACE_PERIOD, $reason, $metadata);
    }

    // Returns new instance with additional metadata (immutability preserved)
    public function withMetadata(string $key, mixed $value): self
    {
        return new self(
            action: $this->action,
            actorId: $this->actorId,
            reason: $this->reason,
            trigger: $this->trigger,
            metadata: array_merge($this->metadata, [$key => $value])
        );
    }

    public function getMetadata(string $key, mixed $default = null): mixed
    {
        return $this->metadata[$key] ?? $default;
    }

    public function isSystemTriggered(): bool
    {
        return $this->actorId === 'system';
    }
}
