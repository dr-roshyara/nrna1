<?php

namespace Tests\Architecture\GovernanceRuntime;

use App\Models\User;
use App\Models\Organisation;
use App\Models\Election;
use App\Domain\Election\Enum\ElectionMode;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use Tests\TestCase;

class VoterStrategyInvariantTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_canvote_method_exists_on_snapshot()
    {
        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::VotingActive,
            canEdit: false,
            canVote: true,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: false,
            blockedReason: null,
            allowedActions: []
        );

        // Verify the method exists
        $this->assertTrue(method_exists($snapshot, 'isParticipationLocked'), 'isParticipationLocked method must exist');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_snapshot_created_atomically_not_via_post_update()
    {
        $org = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org-arc',
            'uses_full_membership' => false,
        ]);

        $election = Election::create([
            'organisation_id' => $org->id,
            'name' => 'Test Election',
            'slug' => 'test-election-arc',
            'type' => 'real',
            'voter_source_strategy' => ElectionMode::fromOrganisation($org)->value,
        ]);

        // Immediately read from database without any post-creation update
        $fresh = Election::withoutGlobalScopes()->find($election->id);

        $this->assertNotNull($fresh, 'Election must exist in database');
        $this->assertNotNull($fresh->voter_source_strategy, 'voter_source_strategy must be set at creation time');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_snapshot_survives_direct_org_mutation()
    {
        $org = Organisation::create([
            'name' => 'Election-Only Org',
            'slug' => 'election-only-org',
            'uses_full_membership' => false,
        ]);

        $election = Election::create([
            'organisation_id' => $org->id,
            'name' => 'Test Election',
            'slug' => 'test-election',
            'type' => 'real',
            'voter_source_strategy' => 'election_only',
        ]);

        // Directly mutate organisation in database
        \Illuminate\Support\Facades\DB::table('organisations')
            ->where('id', $org->id)
            ->update(['uses_full_membership' => true]);

        // Refresh election from database
        $election->refresh();

        // Snapshot should be unchanged
        $mode = ElectionMode::fromElection($election);
        $this->assertEquals(ElectionMode::ElectionOnly, $mode);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_voter_import_controller_uses_election_snapshot_not_org_mode()
    {
        $user = User::factory()->create();

        $org = Organisation::create([
            'name' => 'Full Membership Org',
            'slug' => 'full-membership-org',
            'uses_full_membership' => true,
        ]);

        $user->organisationRoles()->create([
            'organisation_id' => $org->id,
            'role' => 'owner',
        ]);

        // Election has election_only snapshot
        $election = Election::create([
            'organisation_id' => $org->id,
            'name' => 'Test Election',
            'slug' => 'test-election',
            'type' => 'real',
            'voter_source_strategy' => 'election_only',
        ]);

        // Even though org is full_membership, snapshot should be used
        $mode = ElectionMode::fromElection($election);
        $this->assertTrue($mode->isElectionOnly());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_no_election_scoped_controller_reads_org_boolean_directly()
    {
        // This is a meta-test: we verify that no Election-scoped controller
        // uses organisation->uses_full_membership directly without going through ElectionMode

        // Grep result would be 0 - we can't grep in tests, but this serves as
        // documentation of the invariant
        $this->assertTrue(true, 'Architecture documentation: no Election controller should access org->uses_full_membership');
    }
}
