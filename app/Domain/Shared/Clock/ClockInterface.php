<?php

namespace App\Domain\Shared\Clock;

/**
 * ClockInterface — Observable Runtime Temporal Evidence
 *
 * Converts time from "ambient environment state" to "observable runtime evidence."
 *
 * Priority 3: Temporal Determinism Hardening
 *
 * CRITICAL INVARIANT:
 * Time MUST be externally injected, never ambient.
 * Frozen time MUST be replayable (same timestamp → same result).
 * No mutable temporal state.
 * No wall-clock dependency.
 *
 * This eliminates temporal entropy from replay-sensitive evaluation paths.
 */
interface ClockInterface
{
    /**
     * Get current time as immutable timestamp.
     *
     * Returns immutable timestamp for observation.
     * Never mutates internal state.
     * Always returns consistent value during single evaluation request
     * (frozen during test replay, system-driven in production).
     *
     * @return \DateTimeImmutable Current time (frozen in tests, system time in production)
     */
    public function now(): \DateTimeImmutable;
}
