<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Process;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Port\AdjudicationDurations;
use App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore;
use App\Contexts\Adjudication\Application\Port\EventOutbox;
use App\Contexts\Adjudication\Application\Port\IdentityGenerator;
use App\Contexts\Adjudication\Application\Port\RequestsDeterminationIssuance;
use App\Contexts\Adjudication\Application\Process\Exception\ConflictingDeterminationForChallenge;
use App\Contexts\Adjudication\Application\Process\Exception\LateDecisionOnExpiredAdjudication;
use App\Contexts\Adjudication\Domain\Events\AdjudicationExpired;
use App\Contexts\Adjudication\Domain\Exception\DeterminationAlreadyIssued;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
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
        Jurisdiction $jurisdiction,
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

        // TRANSACTION 1 (ADR-T1) — the conclusion, recorded ALONE. The jurisdiction is
        // retained here because it arrived WITH the decision: it is the deciding
        // authority's fact (R-73), landing at the moment the authority speaks.
        $concluded = $process
            ->concludeRulingRequested(
                $consideredEvidence,
                $authority,
                $outcome,
                $legitimacy,
                $reason,
                $this->clock->now(),
            )
            ->retainJurisdiction($jurisdiction);

        $this->store->save($concluded);

        // TRANSACTION 2 — the issuance request. SEPARATE by ADR-T1: one aggregate per
        // transaction, so the conclusion is durable before issuance is attempted. The
        // window between the two is exactly what `redriveIssuance()` closes.
        $this->requestIssuanceFor($concluded);
    }

    /**
     * WP-4B: complete every process that CONCLUDED but never got its issuance requested.
     *
     * This is the crash-window recovery EPIC-004K §11 makes necessary by designing the
     * two commits as separate transactions. It is not a retry loop and holds no schedule
     * — it is idempotent and safe to invoke as often as an operator or a timer chooses.
     *
     * **Requesting issuance twice is prevented by the DURABLE marker, not by anything
     * this method remembers.** In-memory de-duplication would be destroyed by the very
     * crash this method exists to recover from.
     */
    public function redriveIssuance(): void
    {
        $failures = [];

        foreach ($this->store->concludedAwaitingIssuance() as $process) {
            // R-81: FAILURE ISOLATION PER PROCESS. Without this, one throwing process
            // aborts the pass — and because the query orders by `concluded_at`, the
            // OLDEST stuck process would starve every newer one indefinitely.
            try {
                $this->requestIssuanceFor($process);
            } catch (\Throwable $failure) {
                $failures[] = $failure;
            }
        }

        // Isolated, but NOT swallowed. Every process is attempted before any failure
        // propagates, so no process is starved; the first failure is then rethrown
        // UNCHANGED so its type and stack survive for the caller's translation. A
        // silent catch would convert a starvation defect into an invisible one.
        //
        // Rethrowing is safe against a retry: a process whose issuance was requested
        // has its marker written and has therefore left the redrive set (R-82).
        if ($failures !== []) {
            throw $failures[0];
        }
    }

    /**
     * The CONCLUDE -> ISSUE seam. One place, reached by both the live path and redrive,
     * so the two can never diverge.
     *
     * AP-2 AT THE SEAM: every field is the value the record holds. The seam DEFINES,
     * DEFAULTS and CLAMPS nothing — it does not fetch a contested outcome, does not
     * invent a jurisdiction, and does not substitute a fallback envelope. Each of those
     * facts belongs to a different producer (R-73 · R-74 · R-75), and this method is a
     * READER of them.
     *
     * FAILS CLOSED (AP-1): if any required fact is absent the request is NOT made and
     * the marker is NOT written, so the process stays in the redrive set and becomes
     * issuable the moment its missing input arrives. **Silence, not a guess** — a
     * determination issued on an invented input would be a constitutional defect far
     * worse than a delayed one.
     */
    private function requestIssuanceFor(AdjudicationProcessState $process): void
    {
        $contestedOutcome = $process->contestedOutcome();
        $evidenceEnvelopeRef = $process->evidenceEnvelopeRef();
        $jurisdiction = $process->jurisdiction();
        $authority = $process->concludedByAuthority();
        $outcome = $process->outcome();
        $legitimacy = $process->legitimacy();
        $reason = $process->reason();
        $consideredEvidence = $process->consideredEvidence();

        if ($contestedOutcome === null
            || $evidenceEnvelopeRef === null
            || $jurisdiction === null
            || $authority === null
            || $outcome === null
            || $legitimacy === null
            || $reason === null
            || $consideredEvidence === null) {
            return;
        }

        try {
            $this->issuance->request(new IssueDeterminationCommand(
                $process->challengeRef(),
                $outcome,
                $legitimacy,
                $reason,
                $authority,
                $jurisdiction,
                $evidenceEnvelopeRef,
                $contestedOutcome,
                $consideredEvidence,
                $this->clock->now(),
            ));
        } catch (DeterminationAlreadyIssued $refusal) {
            // R-84: INV-B1 refused. §12 forbids escalating this blindly — RECONCILE.
            $this->reconcileIssuanceRefusal($process, $refusal);

            return;
        }

        // Written only AFTER the request was made, and in the same transaction as it:
        // the marker's meaning is *"issuance was requested"*, so writing it earlier would
        // let a failure between the two silently drop the process out of the redrive set.
        $this->store->save($process->markIssuanceRequested($this->clock->now()));
    }

    /**
     * EPIC-004K §12's reconciliation, in its two ruled branches (R-84).
     *
     *   self-redelivery  → **ACK**: this process's own earlier request succeeded and only the
     *                      marker was lost (crash model B, R-83). The determination is
     *                      correct and complete; nothing is owed but the marker.
     *   competing writer → **DEAD-LETTER + ESCALATE**: a determination exists that this
     *                      process's conclusion did not produce.
     *
     * **THE DISCRIMINATOR IS THE DECIDING AUTHORITY**, compared against the authority this
     * process recorded at conclusion. It is not the process id: **the `Determination`
     * carries none, and giving it one would change the constitutional record and its
     * published payload (ADR-PL-01 · ADR-T5) — architecture, not engineering.** The
     * authority is the right comparison on its own terms, not merely the available one: a
     * determination bearing a DIFFERENT authority's ruling is precisely *"another writer
     * issued"*, whoever wrote it.
     *
     * **A refusal carrying no identity is treated as UNRECONCILABLE and escalated.** Acking
     * it would mark the process on an assumption, and §12 asks for reconciliation, not for a
     * guess that happens to keep the queue moving.
     */
    private function reconcileIssuanceRefusal(
        AdjudicationProcessState $process,
        DeterminationAlreadyIssued $refusal,
    ): void {
        $existingAuthority = $refusal->existingIssuedByAuthority;
        $concludedBy = $process->concludedByAuthority();

        if ($existingAuthority === null || $concludedBy === null) {
            throw ConflictingDeterminationForChallenge::forChallenge(
                $process->challengeRef(),
                'the refusal carried no identifying authority, so the two §12 branches cannot be told apart',
            );
        }

        if ($existingAuthority->toString() !== $concludedBy->toString()) {
            throw ConflictingDeterminationForChallenge::forChallenge(
                $process->challengeRef(),
                sprintf(
                    'the existing determination was issued by authority "%s" while this process concluded under "%s"',
                    $existingAuthority->toString(),
                    $concludedBy->toString(),
                ),
            );
        }

        // Ack. The marker IS written: leaving it absent would return this process to the
        // redrive set to refuse again on every pass — the self-poisoning loop R-81 addressed
        // from the other direction.
        $this->store->save($process->markIssuanceRequested($this->clock->now()));
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
