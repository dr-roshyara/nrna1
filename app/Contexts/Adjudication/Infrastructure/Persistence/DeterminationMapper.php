<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Persistence;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\Determination;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\DeterminationState;
use App\Contexts\Adjudication\Domain\Determination\ElectionId;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\TargetId;
use App\Contexts\Adjudication\Domain\Determination\TargetType;
use App\Contexts\Adjudication\Infrastructure\Models\DeterminationModel;

/**
 * Row ↔ aggregate mapping (Infrastructure owns the row shape; the aggregate owns
 * valid construction via reconstitute()). Persists aggregate STATE only.
 * The contested-outcome reference (ADR-UL-01) is stored in 3 nullable columns
 * (null for rows written before payload schema v2). Reconstruction happens here
 * (Infrastructure), not on the VO.
 */
final class DeterminationMapper
{
    /** @return array<string, string|null> */
    public function toRow(Determination $determination): array
    {
        $co = $determination->contestedOutcome();

        return [
            'id' => $determination->id()->toString(),
            'challenge_ref' => $determination->challengeRef()->toString(),
            'state' => $determination->state()->value,
            'evidence_envelope_ref' => $determination->evidenceEnvelopeRef()->toString(),
            'issued_by_authority' => $determination->issuedByAuthority()->toString(),
            'jurisdiction' => $determination->jurisdiction()->toString(),
            'contested_election_id' => $co?->electionId->toString(),
            'contested_target_type' => $co?->type->value,
            'contested_target_id' => $co?->targetId->toString(),
        ];
    }

    public function toAggregate(DeterminationModel $model): Determination
    {
        $contestedOutcome = ($model->contested_election_id !== null
            && $model->contested_target_type !== null
            && $model->contested_target_id !== null)
            ? ContestedOutcomeRef::of(
                ElectionId::fromString($model->contested_election_id),
                TargetType::from($model->contested_target_type),
                TargetId::fromString($model->contested_target_id),
            )
            : null;

        return Determination::reconstitute(
            DeterminationId::fromString($model->id),
            ChallengeRef::fromString($model->challenge_ref),
            IssuedByAuthority::fromString($model->issued_by_authority),
            Jurisdiction::fromString($model->jurisdiction),
            EvidenceEnvelopeRef::fromString($model->evidence_envelope_ref),
            $contestedOutcome,
            DeterminationState::from($model->state),
        );
    }
}
