<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Process;

use App\Contexts\Adjudication\Application\Port\AdjudicationDurations;
use App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore;
use App\Contexts\Adjudication\Application\Port\EventOutbox;
use App\Contexts\Adjudication\Application\Port\IdentityGenerator;
use App\Contexts\Adjudication\Application\Port\RequestsDeterminationIssuance;
use App\Contexts\Adjudication\Application\Process\Exception\LateDecisionOnExpiredAdjudication;
use App\Contexts\Adjudication\Domain\Events\AdjudicationExpired;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Domain\Shared\Clock\ClockInterface;

/**
 * The Adjudication Process Manager — the HEAD of the constitutional correction
 * loop, and only the head (ADR-T8).
 *
 * It conducts one routed challenge's adjudication to exactly one conclusion. From
 * `DeterminationIssued` onward the loop is pure event choreography: this class
 * neither observes nor coordinates the Election/Contestation reactions. It is not
 * a saga and performs no compensation.
 *
 * The authority DECIDES; this manager RECEIVES (K1 · Q-1 · ADR-T23). It never
 * computes legitimacy or sufficiency, and never validates a delegation — that is
 * Governance's, solely.
 *
 * IDEMPOTENT BY DESIGN: transports deliver at least once, so every entry point
 * is safe to replay — a duplicate request opens no second process, and a decision
 * for an already-concluded process is a no-op, never a second conclusion. (The
 * house PM precedent: `GovernanceApprovalProcessManager`'s terminal guard.)
 *
 * WP-2 SCOPE: the conduct and its store. Transport wiring is WP-4, the published
 * loop-head event is WP-3, timer execution is WP-6 — nothing here registers a
 * handler or publishes a message.
 *
 * Traceability: EPIC-004K §3 (PM-1..PM-8), §6, §11 · roadmap §WP-2.
 */
final class AdjudicationProcessManager
{
    public function __construct(
        private readonly AdjudicationProcessStore $store,
        private readonly ClockInterface $clock,
        private readonly AdjudicationDurations $durations,
        private readonly EventOutbox $outbox,
        private readonly IdentityGenerator $identities,
        private readonly RequestsDeterminationIssuance $issuance,
    ) {
    }

    /**
     * PM-1: receive the adjudication request and open exactly one ACTIVE process
     * for the challenge. Replay-safe: an existing active process is returned as-is.
     */
    public function openFor(ChallengeRef $challenge): AdjudicationProcessId
    {
        $existing = $this->store->activeForChallenge($challenge);
        if ($existing !== null) {
            return $existing->id();
        }

        $process = AdjudicationProcessState::open(
            $this->store->nextIdentity(),
            $challenge,
            $this->clock->now(),
        );
        $this->store->save($process);

        return $process->id();
    }

    /**
     * WP-4B (R-72): the arrival point for the two issuance inputs Adjudication does not
     * produce — `ContestedOutcomeRef` (Contestation, R-75) and `EvidenceEnvelopeRef`
     * (the Evidence context, R-74).
     *
     * **It names WHERE those facts land, never WHO delivers them.** The producers are
     * outside this slice, and this method must never acquire, derive or default what it
     * is given: the process records facts that have already crossed a boundary through an
     * approved contract; it does not establish them.
     */
    public function admitIssuanceContext(
        ChallengeRef $challenge,
        ContestedOutcomeRef $contestedOutcome,
        EvidenceEnvelopeRef $evidenceEnvelopeRef,
    ): void {
        $process = $this->store->activeForChallenge($challenge);
        if ($process === null) {
            return;
        }

        $this->store->save($process->retainIssuanceContext($contestedOutcome, $evidenceEnvelopeRef));
    }

    /** PM-2: admit an opaque evidence reference into this judgment. */
    public function admitEvidence(ChallengeRef $challenge, string $reference): void
    {
        $process = $this->store->activeForChallenge($challenge);
        if ($process === null) {
            return;
        }

        $this->store->save($process->admitEvidence($reference, $this->clock->now()));
    }

    /** Put the assembled basis before the constitutional authority. */
    public function submitToAuthority(ChallengeRef $challenge): void
    {
        $process = $this->store->activeForChallenge($challenge);
        if ($process === null) {
            return;
        }

        $this->store->save($process->submitToAuthority($this->clock->now()));
    }

    /**
     * PM-4 → PM-5: the authority's ruling decision arrives whole and concludes the
     * process, fixing the considered set and the deciding authority with it.
     */
    public function receiveRulingDecision(
        ChallengeRef $challenge,
        DeterminationOutcome $outcome,
        Legitimacy $legitimacy,
        Reason $reason,
        IssuedByAuthority $authority,
        EvidenceSet $consideredEvidence,
    ): void {
        $process = $this->store->activeForChallenge($challenge);
        if ($process === null) {
            // Two situations shared this branch before WP-6, and section 197 rules them
            // differently: a REDELIVERED decision on a concluded process is an
            // idempotent no-op (ADR-T3), while a LATE decision on an EXPIRED one is a
            // conflict that must never be honored.
            $this->refuseIfExpired($challenge);

            return;   // redelivered decision on a concluded process: a no-op
        }

        $this->store->save($process->concludeRulingRequested(
            $consideredEvidence,
            $authority,
            $outcome,
            $legitimacy,
            $reason,
            $this->clock->now(),
        ));
    }

    /**
     * PM-4 → PM-7: the authority found the evidence insufficient. The failure is
     * recorded; announcing it is WP-4's wiring.
     */
    public function receiveInsufficiencyDecision(
        ChallengeRef $challenge,
        Reason $reason,
        IssuedByAuthority $authority,
        EvidenceSet $consideredEvidence,
    ): void {
        $process = $this->store->activeForChallenge($challenge);
        if ($process === null) {
            return;
        }

        $this->store->save($process->concludeFailureDeclared(
            $consideredEvidence,
            $authority,
            $reason,
            $this->clock->now(),
        ));
    }

    /**
     * Section 197: a post-expiry authority decision dead-letters as a conflict.
     * Silence would be the one unacceptable response -- the authority must learn its
     * ruling arrived too late.
     */
    private function refuseIfExpired(ChallengeRef $challenge): void
    {
        $latest = $this->store->latestForChallenge($challenge);

        if ($latest !== null && $latest->status() === AdjudicationProcessStatus::Expired) {
            throw LateDecisionOnExpiredAdjudication::forChallenge($challenge);
        }
    }

    /**
     * PM-8: enforce the adjudication horizon. Expiry is a terminal fact, never a
     * conclusion — a timer must not adjudicate anything (Policy 4).
     */
    public function enforceHorizon(): void
    {
        $now = $this->clock->now();

        // The CUT-OFF, not "now". `dueForHorizon()` selects processes opened at or
        // before the instant it is given; passing `now` would make every non-terminal
        // process due, expiring one opened a second ago. Q-2 owns the duration (section
        // 81) -- this manager only subtracts it.
        $cutOff = $now->sub($this->durations->maximumAdjudicationDuration());

        foreach ($this->store->dueForHorizon($cutOff) as $process) {
            $expired = $process->expire($now);
            $this->store->save($expired);

            // Section 197: expiry ANNOUNCES the failure-to-conclude. A clock consumed
            // no message, so there is no incoming conversation to continue -- this
            // publication BEGINS one (ADR-MP-06; ARB Decision B).
            $this->outbox->enqueue(
                EventProvenance::start($this->identities->next()),
                new AdjudicationExpired($expired->challengeRef(), $now),
            );
        }
    }
}
