<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Persistence;

use App\Contexts\Adjudication\Application\Process\AdjudicationProcessId;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessState;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessStatus;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
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
