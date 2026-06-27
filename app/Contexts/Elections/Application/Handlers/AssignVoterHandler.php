<?php

namespace App\Contexts\Elections\Application\Handlers;

use App\Contexts\Elections\Application\Commands\AssignVoterCommand;
use App\Contexts\Elections\Domain\Exceptions\DuplicateVoterException;
use App\Contexts\Elections\Domain\Exceptions\VoterNotEligibleException;
use App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy;
use App\Contexts\Elections\Domain\Repositories\VoterRepositoryInterface;
use App\Domain\Election\Events\VoterAssignedToElection;
use App\Models\ElectionMembership;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

/**
 * AssignVoterHandler — Single voter assignment orchestrator
 *
 * Responsibility:
 * - Check eligibility via policy (VoterEligibilityPolicy)
 * - Prevent duplicate active assignments
 * - Support re-import (restore soft-deleted)
 * - Persist via repository
 * - Return the persisted membership
 *
 * No domain events or cache invalidation here (Phase C.2 minimal).
 * Those are Phase C.4+ concerns.
 *
 * Anti-pattern: Don't fetch/query eligibility again — policy decides once.
 */
final class AssignVoterHandler
{
    public function __construct(
        private readonly VoterEligibilityPolicy $policy,
        private readonly VoterRepositoryInterface $repository,
    ) {}

    /**
     * Handle single voter assignment.
     *
     * Flow:
     * 1. Policy: Check eligibility (VoterNotEligibleException if false)
     * 2. Repository: Find existing (active, inactive, soft-deleted, or none)
     * 3. Logic:
     *    a. Active + not deleted → DuplicateVoterException
     *    b. Deleted → Restore + update (re-import)
     *    c. None → Create new
     * 4. Persist and return membership
     * 5. OUTSIDE transaction: dispatch event + audit log
     *
     * @throws VoterNotEligibleException User fails eligibility check
     * @throws DuplicateVoterException User already active in election
     * @return ElectionMembership Persisted membership
     */
    public function handle(AssignVoterCommand $cmd): ElectionMembership
    {
        // Step 1: Eligibility check via policy
        if (!$this->policy->isEligible($cmd->userId, $cmd->organisationId, $cmd->mode)) {
            throw new VoterNotEligibleException(
                "User '{$cmd->userId}' is not eligible to vote in this election."
            );
        }

        // Step 2: Check for existing membership (including soft-deleted)
        $existing = $this->repository->findWithTrashed($cmd->userId, $cmd->electionId);

        // Step 3: Decision logic
        if ($existing) {
            // User was previously assigned
            if ($existing->deleted_at === null && $existing->status === 'active') {
                // Already active — cannot re-assign
                throw new DuplicateVoterException(
                    "User '{$cmd->userId}' is already assigned to this election."
                );
            }

            // Soft-deleted or inactive — restore for re-import
            $membership = $this->repository->restoreAndUpdate($existing, [
                'status'      => 'active',
                'assigned_at' => now(),
                'assigned_by' => $cmd->assignedBy,
            ]);
        } else {
            // Step 4: Create new membership
            $membership = $this->repository->create([
                'user_id'        => $cmd->userId,
                'election_id'    => $cmd->electionId,
                'organisation_id'=> $cmd->organisationId,
                'role'           => 'voter',
                'status'         => 'active',
                'assigned_at'    => now(),
                'assigned_by'    => $cmd->assignedBy,
                'metadata'       => $cmd->metadata,
            ]);
        }

        // Step 5: OUTSIDE transaction — dispatch event and audit log
        Event::dispatch(new VoterAssignedToElection(
            userId:        $cmd->userId,
            electionId:    $cmd->electionId,
            organisationId: $cmd->organisationId,
            assignedBy:    $cmd->assignedBy,
            occurredAt:    new \DateTimeImmutable(),
        ));

        Log::channel('voter_audit')->info('Voter assigned to election', [
            'user_id'        => $cmd->userId,
            'election_id'    => $cmd->electionId,
            'organisation_id'=> $cmd->organisationId,
            'assigned_by'    => $cmd->assignedBy,
            'was_restored'   => $existing !== null,
        ]);

        return $membership;
    }
}
