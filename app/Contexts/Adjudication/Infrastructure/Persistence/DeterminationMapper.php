<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Persistence;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\Determination;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\DeterminationState;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Infrastructure\Models\DeterminationModel;

/**
 * Row ↔ aggregate mapping (Infrastructure owns the row shape; the aggregate owns
 * valid construction via reconstitute()). Persists aggregate STATE only.
 */
final class DeterminationMapper
{
    /** @return array<string, string> */
    public function toRow(Determination $determination): array
    {
        return [
            'id' => $determination->id()->toString(),
            'challenge_ref' => $determination->challengeRef()->toString(),
            'state' => $determination->state()->value,
            'evidence_envelope_ref' => $determination->evidenceEnvelopeRef()->toString(),
            'issued_by_authority' => $determination->issuedByAuthority()->toString(),
            'jurisdiction' => $determination->jurisdiction()->toString(),
        ];
    }

    public function toAggregate(DeterminationModel $model): Determination
    {
        return Determination::reconstitute(
            DeterminationId::fromString($model->id),
            ChallengeRef::fromString($model->challenge_ref),
            IssuedByAuthority::fromString($model->issued_by_authority),
            Jurisdiction::fromString($model->jurisdiction),
            EvidenceEnvelopeRef::fromString($model->evidence_envelope_ref),
            DeterminationState::from($model->state),
        );
    }
}
