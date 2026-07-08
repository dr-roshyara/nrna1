<?php

declare(strict_types=1);

namespace App\Contexts\Election\Infrastructure\Persistence;

use App\Contexts\Election\Application\Port\AppliedDeterminationLedger;
use App\Contexts\Election\Domain\DeterminationId;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Infrastructure\Models\ElectionAppliedDeterminationModel;

/**
 * Eloquent-backed reaction-state ledger. Tenant scope is applied by the model's
 * {@see \App\Traits\BelongsToTenant} trait, so queries here ask a purely intra-tenant
 * question. `remember()` is idempotent via `updateOrCreate` on (election, determination)
 * within the ambient organisation (backed by the tenant-scoped unique index).
 */
final class EloquentAppliedDeterminationLedger implements AppliedDeterminationLedger
{
    /**
     * @return list<DeterminationId>
     */
    public function appliedDeterminations(ElectionId $id): array
    {
        $rows = ElectionAppliedDeterminationModel::query()
            ->where('election_id', $id->toString())
            ->pluck('determination_id')
            ->all();

        $determinations = [];
        foreach ($rows as $determinationId) {
            if (is_scalar($determinationId)) {
                $determinations[] = DeterminationId::fromString((string) $determinationId);
            }
        }

        return $determinations;
    }

    public function remember(ElectionId $id, DeterminationId ...$determinations): void
    {
        foreach ($determinations as $determination) {
            ElectionAppliedDeterminationModel::query()->updateOrCreate([
                'election_id' => $id->toString(),
                'determination_id' => $determination->toString(),
            ]);
        }
    }
}
