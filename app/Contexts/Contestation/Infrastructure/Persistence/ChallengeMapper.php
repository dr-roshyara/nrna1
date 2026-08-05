<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Persistence;

use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Infrastructure\Models\ChallengeModel;

/**
 * The SOLE translation point between the `challenges` row and the Challenge aggregate.
 * The aggregate is persistence-ignorant; this mapper owns row↔aggregate mapping and the
 * ADR-T16 string↔VO reconstruction.
 *
 * This reaction slice maps only the reaction-relevant columns (state + determination). The
 * raise-time columns exist on the table (nullable) but are neither read nor written here;
 * a later backlog item extends this mapper (additively) to own them — the migration and
 * repository do not change.
 */
final class ChallengeMapper
{
    /**
     * @return array<string, string|null>
     */
    public function toRow(Challenge $challenge): array
    {
        return [
            'id' => $challenge->id()->toString(),
            'state' => $challenge->state()->value,
            'determination_id' => $challenge->adjudicatedDeterminationId()?->toString(),
        ];
    }

    public function toAggregate(ChallengeModel $model): Challenge
    {
        $determinationId = is_scalar($model->determination_id) && (string) $model->determination_id !== ''
            ? DeterminationId::fromString((string) $model->determination_id)
            : null;

        return Challenge::reconstitute(
            ChallengeId::fromString((string) $model->id),
            ChallengeState::from((string) $model->state),
            $determinationId,
        );
    }
}
