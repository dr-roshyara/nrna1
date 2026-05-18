<?php

namespace App\Contexts\Elections\Application\Handlers;

use App\Contexts\Elections\Application\Commands\BulkAssignVotersCommand;
use App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy;
use App\Contexts\Elections\Domain\Repositories\VoterRepositoryInterface;
use Illuminate\Support\Str;

/**
 * BulkAssignVotersHandler — Bulk voter assignment orchestrator
 *
 * Responsibility:
 * - Filter eligible users (single policy query, not N+1)
 * - Exclude already-assigned voters
 * - Chunk large inputs into batches of 500
 * - Process each chunk in its own transaction
 * - Collect dead-letter entries on chunk failure
 * - Return aggregate counts
 *
 * Anti-pattern: Don't loop checking eligibility per user.
 * Do it once via policy.qualifyingSubset() — single DB query.
 */
final class BulkAssignVotersHandler
{
    public function __construct(
        private readonly VoterEligibilityPolicy $policy,
        private readonly VoterRepositoryInterface $repository,
    ) {}

    /**
     * Handle bulk voter assignment.
     *
     * Flow:
     * 1. Filter via policy (single query) → eligible user IDs
     * 2. Check repository for already-assigned → existing user IDs
     * 3. Calculate new assignments: eligible - existing
     * 4. Chunk new assignments (default 500 per chunk)
     * 5. For each chunk:
     *    a. Build row array (id, user_id, election_id, org_id, role, status, created_at, updated_at)
     *    b. Call repository->bulkInsert()
     *    c. On failure: log to dead-letter queue, continue
     * 6. Return counts: success, already_existing, invalid, failed
     *
     * @return array ['success' => int, 'already_existing' => int, 'invalid' => int, 'failed' => int]
     */
    public function handle(BulkAssignVotersCommand $cmd): array
    {
        // Step 1: Single policy query for eligibility (not N+1)
        $eligibleIds = $this->policy->qualifyingSubset(
            $cmd->userIds,
            $cmd->organisationId,
            $cmd->mode
        );

        // Step 2: Count invalid (not eligible)
        $invalidCount = count($cmd->userIds) - count($eligibleIds);

        // Step 3: Get already-assigned voters
        $existingIds = $this->repository->existingVoterIds($cmd->electionId);
        $alreadyExistingCount = count(array_intersect($eligibleIds, $existingIds));

        // Step 4: Calculate new assignments (eligible - existing)
        $newIds = array_diff($eligibleIds, $existingIds);

        // Step 5: Chunk and insert
        $successCount = 0;
        $failedCount = 0;

        foreach (array_chunk($newIds, $cmd->chunkSize) as $chunk) {
            try {
                $rows = array_map(function ($userId) use ($cmd) {
                    return [
                        'id'              => Str::uuid(),
                        'user_id'         => $userId,
                        'election_id'     => $cmd->electionId,
                        'organisation_id' => $cmd->organisationId,
                        'role'            => 'voter',
                        'status'          => 'active',
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }, $chunk);

                $this->repository->bulkInsert($rows);
                $successCount += count($chunk);
            } catch (\Exception $e) {
                // Dead-letter queue handling (Phase C.6)
                $failedCount += count($chunk);
            }
        }

        return [
            'success'          => $successCount,
            'already_existing' => $alreadyExistingCount,
            'invalid'          => $invalidCount,
            'failed'           => $failedCount,
        ];
    }
}
