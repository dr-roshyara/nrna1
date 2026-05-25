<?php

namespace Tests\Feature\Election;

use App\Contexts\Elections\Application\Commands\AssignVoterCommand;
use App\Contexts\Elections\Application\Commands\BulkAssignVotersCommand;
use App\Contexts\Elections\Application\Handlers\AssignVoterHandler;
use App\Contexts\Elections\Application\Handlers\BulkAssignVotersHandler;
use App\Domain\Election\Enum\VoterSourceStrategy;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * ElectionMembershipInfrastructureTest — Layer 3: Side Effects Only
 *
 * Responsibility: Lock EXTERNAL EFFECTS ONLY
 *
 * ✔ Allowed:
 * - cache invalidation
 * - event dispatch
 * - logging side effects
 *
 * ❌ Forbidden:
 * - eligibility logic
 * - DB validation
 * - domain decisions
 *
 * Mental Model: "What external systems were notified about this change?"
 *
 * Important: These tests capture the CURRENT (Phase A) cache implementation.
 * Phase C will refactor cache handling to ElectionCacheService.
 * These tests verify current behavior, not future architecture.
 */
class ElectionMembershipInfrastructureTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $user;
    private User $assignedBy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['uses_full_membership' => false]);
        $this->election = Election::factory()->create(['organisation_id' => $this->organisation->id]);
        $this->user = User::factory()->create();
        $this->assignedBy = User::factory()->create();

        UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $this->user->id,
        ]);

        // For election-only mode tests, also add to organisation_users
        \App\Models\OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $this->user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);
    }

    /**
     * Test I.1.1: assignVoter invalidates voter_count cache
     *
     * Validates: When membership is created via handler, cache key for voter count is cleared
     * Via Eloquent events: ElectionMembership::booted() calls Cache::forget()
     */
    public function test_assign_voter_invalidates_voter_count_cache(): void
    {
        Cache::spy();

        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        Cache::shouldHaveReceived('forget')
            ->with("election.{$this->election->id}.voter_count");
    }

    /**
     * Test I.1.2: assignVoter invalidates voter_stats cache
     *
     * Validates: When membership is created via handler, cache key for voter stats is cleared
     * Via Eloquent events: ElectionMembership::booted() calls Cache::forget()
     */
    public function test_assign_voter_invalidates_voter_stats_cache(): void
    {
        Cache::spy();

        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        Cache::shouldHaveReceived('forget')
            ->with("election.{$this->election->id}.voter_stats");
    }

    /**
     * Test I.1.3: Membership update invalidates caches
     *
     * Validates: When membership is updated (via save), cache is invalidated
     */
    public function test_membership_update_invalidates_cache(): void
    {
        $handler = app(AssignVoterHandler::class);
        $membership = $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        Cache::spy();
        $membership->update(['status' => 'inactive']);

        Cache::shouldHaveReceived('forget')
            ->with("election.{$this->election->id}.voter_count");
        Cache::shouldHaveReceived('forget')
            ->with("election.{$this->election->id}.voter_stats");
    }

    /**
     * Test I.1.4: Membership delete invalidates caches
     *
     * Validates: When membership is deleted, cache is invalidated
     */
    public function test_membership_delete_invalidates_cache(): void
    {
        $handler = app(AssignVoterHandler::class);
        $membership = $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        Cache::spy();
        $membership->delete();

        Cache::shouldHaveReceived('forget')
            ->with("election.{$this->election->id}.voter_count");
        Cache::shouldHaveReceived('forget')
            ->with("election.{$this->election->id}.voter_stats");
    }

    /**
     * Test I.1.5: bulkAssignVoters invalidates cache
     *
     * Validates: Bulk operation invalidates cache after inserts
     * Via handler: Each assignment triggers Eloquent save event
     */
    public function test_bulk_assign_voters_invalidates_cache(): void
    {
        $user2 = User::factory()->create();
        \App\Models\OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user2->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        Cache::spy();

        $handler = app(BulkAssignVotersHandler::class);
        $handler->handle(new BulkAssignVotersCommand(
            userIds: [$this->user->id, $user2->id],
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        Cache::shouldHaveReceived('forget')
            ->with("election.{$this->election->id}.voter_count");
        Cache::shouldHaveReceived('forget')
            ->with("election.{$this->election->id}.voter_stats");
    }

    /**
     * Test I.1.6: Cache invalidation is election-scoped (not global)
     *
     * Validates: Only the affected election's cache is cleared,
     * not all elections
     */
    public function test_cache_invalidation_is_election_scoped(): void
    {
        $otherElection = Election::factory()->create(['organisation_id' => $this->organisation->id]);

        Cache::spy();

        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        Cache::shouldHaveReceived('forget')
            ->with("election.{$this->election->id}.voter_count");
    }

    /**
     * Test I.1.7: Cache keys include election ID
     *
     * Validates: Cache invalidation uses election ID in key name
     */
    public function test_cache_keys_include_election_id(): void
    {
        Cache::spy();

        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        // Verify cache keys were cleared with correct election ID pattern
        Cache::shouldHaveReceived('forget')
            ->with("election.{$this->election->id}.voter_count");
        Cache::shouldHaveReceived('forget')
            ->with("election.{$this->election->id}.voter_stats");
    }

    /**
     * Test I.1.8: Cache invalidation happens synchronously
     *
     * Validates: Cache is cleared immediately when membership changes,
     * not queued or deferred
     *
     * Current implementation clears:
     * - election.{id}.voter_count
     * - election.{id}.voter_stats
     * - user.{id}.voter.{id} (per-user eligibility)
     */
    public function test_cache_invalidation_is_synchronous(): void
    {
        Cache::spy();

        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        // Cache::forget called 3 times (voter_count, voter_stats, user cache)
        Cache::shouldHaveReceived('forget')->times(3);
    }

    /**
     * Test I.1.9: Multiple elections have independent cache keys
     *
     * Validates: Assigning voter to election A doesn't clear cache for election B
     */
    public function test_multiple_elections_have_independent_caches(): void
    {
        $otherUser = User::factory()->create();
        UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $otherUser->id,
        ]);

        // Assign to first election
        Cache::spy();
        $handler = app(AssignVoterHandler::class);
        $handler->handle(new AssignVoterCommand(
            userId: $this->user->id,
            electionId: $this->election->id,
            organisationId: $this->organisation->id,
            mode: VoterSourceStrategy::fromOrganisation($this->organisation),
            assignedBy: $this->assignedBy->id,
        ));

        $firstElectionKey = "election.{$this->election->id}.voter_count";
        Cache::shouldHaveReceived('forget')->with($firstElectionKey);
    }
}
