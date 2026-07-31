<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Service;

use App\Contexts\Contestation\Application\Command\RaiseChallengeCommand;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\Exception\IllegalChallengeTransition;

/**
 * Application coordinator for the Contestation raise path (NOT a domain service).
 * Orchestration only: load, delegate to the aggregate, persist, and — at routing
 * only — publish. It evaluates no business rule; the `Challenge` state machine
 * stays sovereign and its guards are the only gate.
 *
 * **TP-2 — Contestation REQUESTS, never CREATES an adjudication.** `route()`
 * publishes `ChallengeRouted`; whether an adjudication opens is Adjudication's
 * decision (PM-1), reached through the messaging platform. Nothing here reaches
 * across the boundary.
 *
 * Traceability: roadmap §WP-5 · TP-2 · ADR-T21 · ADR-MP-06 · ADR-T1.
 */
interface ContestationService
{
    /** Raise a challenge. Business process origin — publishes nothing. */
    public function raise(RaiseChallengeCommand $command): ChallengeId;

    /** @throws IllegalChallengeTransition */
    public function admit(ChallengeId $id): void;

    /**
     * Route an admitted challenge to the constitutional authority — the correction
     * loop's head trigger, and the INTEGRATION CONVERSATION ORIGIN.
     *
     * @throws IllegalChallengeTransition
     */
    public function route(ChallengeId $id, string $routedTo): void;
}
