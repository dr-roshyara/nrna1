<?php

namespace App\Infrastructure\Shared\Clock;

use App\Domain\Shared\Clock\ClockInterface;

/**
 * FrozenClock — Test Temporal Evidence (Deterministic Replay)
 *
 * Freezes time at a specific instant for replay verification.
 * Used in tests to ensure identical timestamp throughout evaluation.
 *
 * Priority 3: Test Clock Implementation (Replay Determinism)
 *
 * CRITICAL: Frozen time proves:
 * - Same evidence + same frozen instant → same evaluation outcome
 * - Temporal calculations are reproducible
 * - No hidden wall-clock entropy in replay
 */
final class FrozenClock implements ClockInterface
{
    private \DateTimeImmutable $frozenTime;

    /**
     * @param string|\DateTimeImmutable $time Time to freeze (string or immutable datetime)
     */
    public function __construct(\DateTimeImmutable|string $time)
    {
        if (is_string($time)) {
            $this->frozenTime = new \DateTimeImmutable($time, new \DateTimeZone('UTC'));
        } else {
            $this->frozenTime = $time;
        }
    }

    public function now(): \DateTimeImmutable
    {
        return $this->frozenTime;
    }

    /**
     * Create FrozenClock with specific ISO 8601 timestamp.
     *
     * Usage: FrozenClock::at('2026-05-27T14:30:00Z')
     */
    public static function at(string $iso8601): self
    {
        return new self($iso8601);
    }

    /**
     * Create FrozenClock with current system time (snapshot frozen).
     *
     * Usage: FrozenClock::atNow() in tests for "freeze the moment now"
     */
    public static function atNow(): self
    {
        return new self(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
    }
}
