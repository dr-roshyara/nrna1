<?php

namespace App\Contexts\Elections\Infrastructure\Repositories;

use App\Contexts\Elections\Domain\Repositories\VoterRepositoryInterface;
use App\Models\ElectionMembership;
use Illuminate\Support\Facades\DB;

/**
 * EloquentVoterRepository — Infrastructure Implementation
 *
 * Responsibility: Implement VoterRepositoryInterface using Eloquent.
 * Hides database mechanics from application/domain layers.
 *
 * All voter persistence operations are isolated here.
 * No application logic — only data access.
 */
final class EloquentVoterRepository implements VoterRepositoryInterface
{
    /**
     * Find voter membership including soft-deleted rows.
     *
     * Critical for re-import: checks if voter was previously assigned and removed.
     * Uses withTrashed() to include deleted_at IS NOT NULL rows.
     */
    public function findWithTrashed(string $userId, string $electionId): ?ElectionMembership
    {
        return ElectionMembership::query()
            ->withoutGlobalScopes()
            ->withTrashed()
            ->where('user_id', $userId)
            ->where('election_id', $electionId)
            ->lockForUpdate()  // Row-level lock prevents race on restore
            ->first();
    }

    /**
     * Create new voter membership.
     *
     * Simple insert for voters not yet assigned to this election.
     */
    public function create(array $attributes): ElectionMembership
    {
        return ElectionMembership::create($attributes);
    }

    /**
     * Restore soft-deleted membership and update attributes.
     *
     * Used during re-import when same voter is assigned again after previous removal.
     * restore() unsets deleted_at. update() applies new status/timestamps.
     */
    public function restoreAndUpdate(ElectionMembership $membership, array $attributes): ElectionMembership
    {
        $membership->restore();
        $membership->update($attributes);
        return $membership;
    }

    /**
     * Bulk insert multiple voter records.
     *
     * Optimized for high-volume imports (500+ voters).
     * Uses DB::table() directly to skip Eloquent overhead.
     * Caller is responsible for ensuring rows are valid/unique (pre-validated before calling).
     */
    public function bulkInsert(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        DB::table('election_memberships')->insert($rows);
    }

    /**
     * Get active voter IDs for an election.
     *
     * Excludes soft-deleted rows (WHERE deleted_at IS NULL implicit in query).
     * Used during bulk import to identify duplicates.
     */
    public function existingVoterIds(string $electionId): array
    {
        return ElectionMembership::query()
            ->withoutGlobalScopes()
            ->where('election_id', $electionId)
            ->whereNull('deleted_at')
            ->distinct()
            ->pluck('user_id')
            ->toArray();
    }
}
