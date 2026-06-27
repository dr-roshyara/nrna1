<?php

namespace App\Contexts\Elections\Domain\Repositories;

use App\Models\ElectionMembership;

/**
 * VoterRepositoryInterface — Domain Port for Voter Persistence
 *
 * Defines the contract for voter (ElectionMembership) persistence operations.
 * Implementation: EloquentVoterRepository (infrastructure layer).
 *
 * Responsibility: Hide Eloquent from application/domain layers.
 * All voter persistence goes through this interface.
 */
interface VoterRepositoryInterface
{
    /**
     * Find a voter membership (including soft-deleted).
     *
     * Used when re-importing to check if voter was previously assigned and soft-deleted.
     * Returns membership regardless of deleted_at status.
     *
     * @return ElectionMembership|null
     */
    public function findWithTrashed(string $userId, string $electionId): ?ElectionMembership;

    /**
     * Create a new voter membership.
     *
     * Inserts fresh record for user not previously assigned to this election.
     *
     * @return ElectionMembership
     */
    public function create(array $attributes): ElectionMembership;

    /**
     * Restore soft-deleted membership and update its attributes.
     *
     * Used when re-importing a voter who was previously removed (soft-deleted).
     * Undeletes row and updates status/assigned_at fields.
     *
     * @return ElectionMembership
     */
    public function restoreAndUpdate(ElectionMembership $membership, array $attributes): ElectionMembership;

    /**
     * Bulk insert voter records (high-performance batch).
     *
     * For importing 500+ voters at once.
     * Uses DB::table() directly to skip model overhead.
     *
     * @param array $rows Each row: ['id', 'user_id', 'election_id', 'organisation_id', 'role', 'status', 'created_at', 'updated_at']
     * @return void
     */
    public function bulkInsert(array $rows): void;

    /**
     * Get list of user IDs already assigned to this election.
     *
     * Excludes soft-deleted rows (we want active voters only).
     * Used to filter out duplicates during bulk import.
     *
     * @return array User IDs
     */
    public function existingVoterIds(string $electionId): array;
}
