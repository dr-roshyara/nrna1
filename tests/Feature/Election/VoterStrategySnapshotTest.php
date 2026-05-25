<?php

namespace Tests\Feature\Election;

use App\Models\User;
use App\Models\Organisation;
use App\Models\Election;
use App\Domain\Election\Enum\ElectionMode;
use Tests\TestCase;

class VoterStrategySnapshotTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_election_creation_atomically_snaps_voter_source_from_election_only_org()
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
            'voter_source_strategy' => ElectionMode::fromOrganisation($org)->value,
        ]);

        $this->assertNotNull($election);
        $this->assertEquals('election_only', $election->voter_source_strategy);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_election_creation_atomically_snaps_voter_source_from_full_membership_org()
    {
        $org = Organisation::create([
            'name' => 'Full Membership Org',
            'slug' => 'full-membership-org',
            'uses_full_membership' => true,
        ]);

        $election = Election::create([
            'organisation_id' => $org->id,
            'name' => 'Test Election',
            'slug' => 'test-election-2',
            'type' => 'real',
            'voter_source_strategy' => ElectionMode::fromOrganisation($org)->value,
        ]);

        $this->assertNotNull($election);
        $this->assertEquals('full_membership', $election->voter_source_strategy);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_org_strategy_change_after_election_creation_does_not_alter_election_snapshot()
    {
        $user = User::factory()->create();

        $org = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org',
            'uses_full_membership' => false,
        ]);

        $user->organisationRoles()->create([
            'organisation_id' => $org->id,
            'role' => 'owner',
        ]);

        $election = Election::create([
            'organisation_id' => $org->id,
            'name' => 'Test Election',
            'slug' => 'test-election',
            'type' => 'real',
            'voter_source_strategy' => ElectionMode::fromOrganisation($org)->value,
        ]);

        // Verify snapshot is election_only
        $this->assertEquals('election_only', $election->voter_source_strategy);

        // Change org strategy
        $org->update(['uses_full_membership' => true]);

        // Refresh election from database
        $election->refresh();

        // Snapshot should be unchanged
        $this->assertEquals('election_only', $election->voter_source_strategy);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_election_mode_from_election_uses_snapshot_when_present()
    {
        $org = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org',
            'uses_full_membership' => true,
        ]);

        $election = Election::create([
            'organisation_id' => $org->id,
            'name' => 'Test Election',
            'slug' => 'test-election',
            'type' => 'real',
            'voter_source_strategy' => 'election_only',
        ]);

        // Snapshot says election_only, org says full_membership
        // Snapshot should win
        $mode = ElectionMode::fromElection($election);
        $this->assertEquals(ElectionMode::ElectionOnly, $mode);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_election_mode_from_election_falls_back_to_org_when_snapshot_null()
    {
        $org = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org',
            'uses_full_membership' => true,
        ]);

        $election = Election::create([
            'organisation_id' => $org->id,
            'name' => 'Test Election',
            'slug' => 'test-election',
            'type' => 'real',
            'voter_source_strategy' => null,
        ]);

        // Snapshot is null, org says full_membership
        // Should fall back to org
        $mode = ElectionMode::fromElection($election);
        $this->assertEquals(ElectionMode::FullMembership, $mode);
    }
}
