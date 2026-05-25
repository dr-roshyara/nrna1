<?php

namespace Tests\Architecture\GovernanceRuntime;

use App\Models\Election;
use App\Models\Organisation;
use App\Domain\Election\Enum\VoterSourceStrategy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Behavioral convergence tests for voter-source strategy.
 *
 * These tests verify that:
 * 1. Election snapshots are created atomically, not via post-update
 * 2. Snapshots survive direct organisation mutations
 * 3. Runtime routing uses election snapshot as authoritative source
 * 4. Election-scoped code never reads org boolean directly
 */
class VoterStrategyConvergenceTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_snapshot_created_atomically_not_via_post_update()
    {
        $org = Organisation::create([
            'name' => 'Atomic Test Org',
            'slug' => 'atomic-test',
            'uses_full_membership' => false,
        ]);

        // Create election with voter_source_strategy in initial array
        $election = Election::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'organisation_id' => $org->id,
            'name' => 'Atomic Test Election',
            'slug' => 'atomic-test-election',
            'type' => 'real',
            'voter_source_strategy' => VoterSourceStrategy::fromOrganisation($org)->value,
        ]);

        // Verify snapshot exists immediately after create (not null)
        $this->assertDatabaseHas('elections', [
            'id' => $election->id,
            'voter_source_strategy' => 'election_only',
        ]);

        // Refresh and verify snapshot is still present (no additional update needed)
        $election->refresh();
        $this->assertNotNull($election->voter_source_strategy);
        $this->assertEquals('election_only', $election->voter_source_strategy);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_snapshot_survives_direct_org_mutation()
    {
        $org = Organisation::create([
            'name' => 'Mutation Test Org',
            'slug' => 'mutation-test',
            'uses_full_membership' => false,
        ]);

        $election = Election::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'organisation_id' => $org->id,
            'name' => 'Mutation Test Election',
            'slug' => 'mutation-test-election',
            'type' => 'real',
            'voter_source_strategy' => 'election_only', // snapshot: election_only
        ]);

        // Directly mutate organisation governance policy
        $org->update(['uses_full_membership' => true]);

        // Verify election snapshot is unchanged
        $this->assertDatabaseHas('elections', [
            'id' => $election->id,
            'voter_source_strategy' => 'election_only',
        ]);

        // Verify ElectionMode reads snapshot, not org (refresh election to get latest org)
        $election->refresh();
        $mode = VoterSourceStrategy::fromElection($election);
        $this->assertTrue($mode->isImportedVoterRegistry());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_election_mode_from_election_reads_snapshot_first()
    {
        $org = Organisation::create([
            'name' => 'Snapshot Priority Org',
            'slug' => 'snapshot-priority',
            'uses_full_membership' => true,  // org mode = full_membership
        ]);

        $election = Election::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'organisation_id' => $org->id,
            'name' => 'Snapshot Priority Election',
            'slug' => 'snapshot-priority-election',
            'type' => 'real',
            'voter_source_strategy' => 'election_only', // snapshot = election_only (conflicts with org)
        ]);

        // ElectionMode must read snapshot (election_only), not org (full_membership)
        $mode = VoterSourceStrategy::fromElection($election);
        $this->assertTrue($mode->isImportedVoterRegistry());
        $this->assertFalse($mode->isMembershipRegistry());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_missing_snapshot_throws_exception_enforcing_sovereignty()
    {
        $org = Organisation::create([
            'name' => 'Sovereignty Enforcement Org',
            'slug' => 'sovereignty-enforcement',
            'uses_full_membership' => true,
        ]);

        // Create election with NULL snapshot (violates Phase 3.2 constraint)
        $election = Election::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'organisation_id' => $org->id,
            'name' => 'Sovereignty Enforcement Election',
            'slug' => 'sovereignty-enforcement-election',
            'type' => 'real',
            'voter_source_strategy' => null,
        ]);

        // Phase 3.2: Missing snapshot throws RuntimeException
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches(
            '/Election .+ missing voter_source_strategy snapshot.*backfill-voter-source-strategy/'
        );

        VoterSourceStrategy::fromElection($election);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_unassigned_eligible_query_defaults_to_full_membership_mode()
    {
        $org = Organisation::create([
            'name' => 'Query Default Test Org',
            'slug' => 'query-default-test',
            'uses_full_membership' => true,
        ]);

        // Calling unassignedEligibleQuery without passing mode parameter should default to FullMembership
        $eligibilityService = app(\App\Services\VoterEligibilityService::class);
        $query = $eligibilityService->unassignedEligibleQuery($org, []);

        // Verify query targets members table (full membership path) by checking the SQL
        $sql = $query->toSql();
        $this->assertStringContainsString('members', $sql);
    }
}
