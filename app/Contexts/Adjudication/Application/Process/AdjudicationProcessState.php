<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Process;

use App\Contexts\Adjudication\Application\Process\Exception\IllegalProcessTransition;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use DateTimeImmutable;

/**
 * The state of one adjudication process, with its business guards.
 *
 * WHAT THIS IS NOT: an aggregate. The Candidate-2 ruling (EPIC-004C) settled that
 * "AdjudicationProceeding" has no business identity — the conduct is orchestration.
 * So this class is Application-seated, has no domain repository (EPIC-004K §11 ·
 * RMSP by analogy), and is not the constitutional record: the Determination
 * aggregate is. One truth, two records, one authoritative.
 *
 * WHAT IT PROTECTS (EPIC-004K §6):
 *   · exactly one conclusion, of exactly one kind — or an expiry; never a mix
 *   · no admission after conclusion
 *   · the conclusion, the evidence-set-as-considered and the deciding authority
 *     are ONE fact (PM-5) — a partially concluded process is unrepresentable
 *   · expiry is a distinct terminal fact, and never a conclusion (Policy 4)
 *
 * The authority DECIDES; this process RECEIVES (K1 · Q-1 · ADR-T23). Nothing here
 * computes legitimacy or sufficiency; the decision arrives whole.
 *
 * IMMUTABLE: every step returns a new instance, so a refused step cannot mutate
 * anything. Time is always injected (ER-03/04) — never read.
 */
final class AdjudicationProcessState
{
    /**
     * @param list<string> $admittedEvidence opaque references only (ADR-T11 — no
     *                                       voter↔vote linkage may exist here)
     */
    private function __construct(
        private readonly AdjudicationProcessId $id,
        private readonly ChallengeRef $challengeRef,
        private readonly AdjudicationProcessStatus $status,
        private readonly array $admittedEvidence,
        private readonly DateTimeImmutable $openedAt,
        private readonly ?EvidenceSet $consideredEvidence,
        private readonly ?IssuedByAuthority $concludedByAuthority,
        private readonly ?DeterminationOutcome $outcome,
        private readonly ?Legitimacy $legitimacy,
        private readonly ?Reason $reason,
        private readonly ?DateTimeImmutable $concludedAt,
    ) {
    }

    /** PM-1: the request for adjudication of a routed challenge is received. */
    public static function open(
        AdjudicationProcessId $id,
        ChallengeRef $challengeRef,
        DateTimeImmutable $at,
    ): self {
        return new self(
            $id,
            $challengeRef,
            AdjudicationProcessStatus::Opened,
            [],
            $at,
            null,
            null,
            null,
            null,
            null,
            null,
        );
    }

    /**
     * Rehydrate from the process store (Infrastructure only) — a controlled entry
     * point, so the transitions above remain the sole way a process advances.
     *
     * @param list<string> $admittedEvidence
     */
    public static function reconstitute(
        AdjudicationProcessId $id,
        ChallengeRef $challengeRef,
        AdjudicationProcessStatus $status,
        array $admittedEvidence,
        DateTimeImmutable $openedAt,
        ?EvidenceSet $consideredEvidence,
        ?IssuedByAuthority $concludedByAuthority,
        ?DeterminationOutcome $outcome,
        ?Legitimacy $legitimacy,
        ?Reason $reason,
        ?DateTimeImmutable $concludedAt,
    ): self {
        return new self(
            $id,
            $challengeRef,
            $status,
            $admittedEvidence,
            $openedAt,
            $consideredEvidence,
            $concludedByAuthority,
            $outcome,
            $legitimacy,
            $reason,
            $concludedAt,
        );
    }

    /** PM-2: admit an opaque evidence reference into THIS judgment. */
    public function admitEvidence(string $reference, DateTimeImmutable $at): self
    {
        $this->guard('admit evidence into', AdjudicationProcessStatus::Opened, AdjudicationProcessStatus::Assembling);

        return $this->with(
            status: AdjudicationProcessStatus::Assembling,
            admittedEvidence: [...$this->admittedEvidence, $reference],
        );
    }

    /** The assembled basis is put before the constitutional authority. */
    public function submitToAuthority(DateTimeImmutable $at): self
    {
        $this->guard('submit to the authority', AdjudicationProcessStatus::Assembling);

        return $this->with(status: AdjudicationProcessStatus::AwaitingDecision);
    }

    /**
     * The authority demands more evidence — a decision to NOT-YET-decide. It is
     * not a conclusion and must never be recorded as one.
     */
    public function returnForMoreEvidence(DateTimeImmutable $at): self
    {
        $this->guard('return for more evidence', AdjudicationProcessStatus::AwaitingDecision);

        return $this->with(status: AdjudicationProcessStatus::Assembling);
    }

    /**
     * PM-5/PM-6: the authority ruled. The conclusion, the considered set and the
     * deciding authority are fixed together, in one step.
     *
     * The PERMANENT fixation of the considered set is the aggregate's, at issuance
     * (ADR-T22 / INV-4); this is the deliberation's working record.
     */
    public function concludeRulingRequested(
        EvidenceSet $consideredEvidence,
        IssuedByAuthority $authority,
        DeterminationOutcome $outcome,
        Legitimacy $legitimacy,
        Reason $reason,
        DateTimeImmutable $at,
    ): self {
        $this->guard('conclude', AdjudicationProcessStatus::AwaitingDecision);

        return $this->with(
            status: AdjudicationProcessStatus::ConcludedRulingRequested,
            consideredEvidence: $consideredEvidence,
            concludedByAuthority: $authority,
            outcome: $outcome,
            legitimacy: $legitimacy,
            reason: $reason,
            concludedAt: $at,
        );
    }

    /** PM-7: the authority found the evidence insufficient; no ruling can issue. */
    public function concludeFailureDeclared(
        EvidenceSet $consideredEvidence,
        IssuedByAuthority $authority,
        Reason $reason,
        DateTimeImmutable $at,
    ): self {
        $this->guard('conclude', AdjudicationProcessStatus::AwaitingDecision);

        return $this->with(
            status: AdjudicationProcessStatus::ConcludedFailureDeclared,
            consideredEvidence: $consideredEvidence,
            concludedByAuthority: $authority,
            reason: $reason,
            concludedAt: $at,
        );
    }

    /**
     * PM-8: the adjudication horizon elapsed (Q-2's Maximum Adjudication Duration
     * — the process ENFORCES a bound it does not own).
     *
     * Policy 4: this is not an adjudication. It records no conclusion, no considered
     * set and no authority — automated verification never determines significance.
     */
    public function expire(DateTimeImmutable $at): self
    {
        $this->guard(
            'expire',
            AdjudicationProcessStatus::Opened,
            AdjudicationProcessStatus::Assembling,
            AdjudicationProcessStatus::AwaitingDecision,
        );

        return $this->with(status: AdjudicationProcessStatus::Expired);
    }

    public function id(): AdjudicationProcessId
    {
        return $this->id;
    }

    public function challengeRef(): ChallengeRef
    {
        return $this->challengeRef;
    }

    public function status(): AdjudicationProcessStatus
    {
        return $this->status;
    }

    /** @return list<string> */
    public function admittedEvidence(): array
    {
        return $this->admittedEvidence;
    }

    public function consideredEvidence(): ?EvidenceSet
    {
        return $this->consideredEvidence;
    }

    public function concludedByAuthority(): ?IssuedByAuthority
    {
        return $this->concludedByAuthority;
    }

    public function outcome(): ?DeterminationOutcome
    {
        return $this->outcome;
    }

    public function legitimacy(): ?Legitimacy
    {
        return $this->legitimacy;
    }

    public function reason(): ?Reason
    {
        return $this->reason;
    }

    public function openedAt(): DateTimeImmutable
    {
        return $this->openedAt;
    }

    public function concludedAt(): ?DateTimeImmutable
    {
        return $this->concludedAt;
    }

    /** @param list<string>|null $admittedEvidence */
    private function with(
        AdjudicationProcessStatus $status,
        ?array $admittedEvidence = null,
        ?EvidenceSet $consideredEvidence = null,
        ?IssuedByAuthority $concludedByAuthority = null,
        ?DeterminationOutcome $outcome = null,
        ?Legitimacy $legitimacy = null,
        ?Reason $reason = null,
        ?DateTimeImmutable $concludedAt = null,
    ): self {
        return new self(
            $this->id,
            $this->challengeRef,
            $status,
            $admittedEvidence ?? $this->admittedEvidence,
            $this->openedAt,
            $consideredEvidence ?? $this->consideredEvidence,
            $concludedByAuthority ?? $this->concludedByAuthority,
            $outcome ?? $this->outcome,
            $legitimacy ?? $this->legitimacy,
            $reason ?? $this->reason,
            $concludedAt ?? $this->concludedAt,
        );
    }

    private function guard(string $step, AdjudicationProcessStatus ...$allowed): void
    {
        if (!in_array($this->status, $allowed, true)) {
            throw IllegalProcessTransition::from($this->status, $step);
        }
    }
}
