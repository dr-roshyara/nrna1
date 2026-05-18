<?php

namespace App\Contexts\Elections\Application\Handlers;

use App\Contexts\Elections\Application\Commands\BulkAssignVotersCommand;
use App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy;
use App\Contexts\Elections\Domain\Repositories\VoterRepositoryInterface;
use App\Domain\Election\Events\BulkVotersAssignedToElection;
use App\Models\DeadLetterEntry;
use App\Services\ElectionCacheService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
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
     * 6. OUTSIDE transaction: invalidate cache, dispatch event, audit log
     * 7. Return counts: success, already_existing, invalid, failed
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
                // Write each row in the failed chunk to dead-letter queue
                foreach ($chunk as $userId) {
                    DeadLetterEntry::create([
                        'queue_name'      => 'voter_bulk_assign',
                        'payload'         => [
                            'user_id'         => $userId,
                            'election_id'     => $cmd->electionId,
                            'organisation_id' => $cmd->organisationId,
                        ],
                        'error_message'   => $e->getMessage(),
                        'error_class'     => $e::class,
                        'organisation_id' => $cmd->organisationId,
                        'election_id'     => $cmd->electionId,
                    ]);
                }

                $failedCount += count($chunk);

                Log::channel('voter_failures')->error('Chunk failed in bulk voter assignment', [
                    'election_id'      => $cmd->electionId,
                    'organisation_id'  => $cmd->organisationId,
                    'chunk_size'       => count($chunk),
                    'error'            => $e->getMessage(),
                    'error_class'      => $e::class,
                ]);
            }
        }

        $result = [
            'success'          => $successCount,
            'already_existing' => $alreadyExistingCount,
            'invalid'          => $invalidCount,
            'failed'           => $failedCount,
        ];

        // Step 6: OUTSIDE transaction — cache invalidation, event dispatch, audit log
        ElectionCacheService::forgetVoterKeys($cmd->organisationId, $cmd->electionId);

        Event::dispatch(new BulkVotersAssignedToElection(
            electionId:        $cmd->electionId,
            organisationId:    $cmd->organisationId,
            successCount:      $result['success'],
            alreadyExistingCount: $result['already_existing'],
            invalidCount:      $result['invalid'],
            failedCount:       $result['failed'],
            assignedBy:        $cmd->assignedBy,
            idempotencyKey:    $cmd->idempotencyKey,
            occurredAt:        new \DateTimeImmutable(),
        ));

        Log::channel('voter_audit')->info('Bulk voters assigned to election', [
            'election_id'      => $cmd->electionId,
            'organisation_id'  => $cmd->organisationId,
            'success_count'    => $result['success'],
            'already_existing' => $result['already_existing'],
            'invalid_count'    => $result['invalid'],
            'failed_count'     => $result['failed'],
            'assigned_by'      => $cmd->assignedBy,
            'idempotency_key'  => $cmd->idempotencyKey,
        ]);

        return $result;
    }
}
