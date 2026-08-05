<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Persistence;

use App\Contexts\Adjudication\Application\Process\AdjudicationProcessId;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessState;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessStatus;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\ElectionId;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Determination\TargetId;
use App\Contexts\Adjudication\Domain\Determination\TargetType;
use App\Contexts\Adjudication\Infrastructure\Models\AdjudicationProcessModel;
use DateTimeImmutable;

/**
 * Sole translation between adjudication-process state and its row (WP-2).
 *
 * Named in the roadmap's WP-2 component list. It keeps the Application-seated
 * state free of persistence concerns, exactly as `DeterminationMapper` does for
 * the aggregate — the difference being that this state is orchestration, not an
 * aggregate (EPIC-004K §11).
 */
final class AdjudicationProcessMapper
{
    /** @return array<string, mixed> */
    public function toRow(AdjudicationProcessState $state, string $organisationId): array
    {
        return [
            'id' => $state->id()->toString(),
            'organisation_id' => $organisationId,
            'challenge_ref' => $state->challengeRef()->toString(),
            'status' => $state->status()->value,
            'admitted_evidence' => $state->admittedEvidence(),
            'considered_evidence' => $state->consideredEvidence()?->toArray(),
            'concluded_by_authority' => $state->concludedByAuthority()?->toString(),
            'outcome' => $state->outcome()?->value,
            'legitimacy' => $state->legitimacy()?->value,
            'reason' => $state->reason()?->toString(),
            'opened_at' => $state->openedAt(),
            'concluded_at' => $state->concludedAt(),

            // WP-4B issuance context. Written as delivered -- this mapper derives nothing.
            'contested_election_id' => $state->contestedOutcome()?->electionId->toString(),
            'contested_type' => $state->contestedOutcome()?->type->value,
            'contested_target_id' => $state->contestedOutcome()?->targetId->toString(),
            'evidence_envelope_ref' => $state->evidenceEnvelopeRef()?->toString(),
            'jurisdiction' => $state->jurisdiction()?->toString(),
            'issuance_requested_at' => $state->issuanceRequestedAt(),
        ];
    }

    public function toState(AdjudicationProcessModel $model): AdjudicationProcessState
    {
        $admitted = $model->admitted_evidence;
        $considered = $model->considered_evidence ?? [];

        return AdjudicationProcessState::reconstitute(
            AdjudicationProcessId::fromString($model->id),
            ChallengeRef::fromString($model->challenge_ref),
            AdjudicationProcessStatus::from($model->status),
            $admitted,
            $this->toDateTime($model->opened_at) ?? new DateTimeImmutable('@0'),
            $considered === [] ? null : EvidenceSet::fromRefs(...$considered),
            $model->concluded_by_authority === null
                ? null
                : IssuedByAuthority::fromString($model->concluded_by_authority),
            $model->outcome === null ? null : DeterminationOutcome::from($model->outcome),
            $model->legitimacy === null ? null : Legitimacy::from($model->legitimacy),
            $model->reason === null ? null : Reason::fromString($model->reason),
            $this->toDateTime($model->concluded_at),
            $this->toContestedOutcome($model),
            $model->evidence_envelope_ref === null
                ? null
                : EvidenceEnvelopeRef::fromString($model->evidence_envelope_ref),
            $model->jurisdiction === null ? null : Jurisdiction::fromString($model->jurisdiction),
            $this->toDateTime($model->issuance_requested_at),
        );
    }

    /**
     * Rebuild Adjudication's LOCAL ContestedOutcomeRef from its three wire parts (ADR-T16).
     *
     * All three or none: the three columns are only ever written together, by one method, so
     * **a partially populated contested outcome violates the persistence invariant this slice
     * establishes.** Not "corruption" -- that word implies storage damage, and this is an
     * invariant violation, which is a different thing with a different remedy.
     *
     * **This mapper does not currently distinguish a partial row from an absent one** -- it
     * returns null for both. Detecting the violation here would mean introducing a failure
     * mode and an exception type, which is a design act outside this slice's authorized scope.
     * Recorded so the limitation is visible rather than silently absorbed.
     *
     * FUTURE ARCHITECTURAL CANDIDATE (traceability only, not a commitment): detect partial
     * reconstruction through a dedicated persistence invariant, once authorized.
     */
    private function toContestedOutcome(AdjudicationProcessModel $model): ?ContestedOutcomeRef
    {
        if ($model->contested_election_id === null
            || $model->contested_type === null
            || $model->contested_target_id === null) {
            return null;
        }

        return ContestedOutcomeRef::of(
            ElectionId::fromString($model->contested_election_id),
            TargetType::from($model->contested_type),
            TargetId::fromString($model->contested_target_id),
        );
    }

    private function toDateTime(mixed $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        return is_string($value) ? new DateTimeImmutable($value) : null;
    }
}
