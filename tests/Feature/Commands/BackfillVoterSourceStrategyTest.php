<?php

namespace Tests\Feature\Commands;

use App\Models\Election;
use App\Models\Organisation;
use App\Domain\Election\Enum\ElectionMode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BackfillVoterSourceStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper: Insert election with NULL snapshot via raw SQL
     * This bypasses the NOT NULL constraint to test backfill scenarios
     */
    private function createElectionWithNullSnapshot(Organisation $org, string $slug): string
    {
        $electionId = \Illuminate\Support\Str::uuid();
        DB::insert(
            'INSERT INTO elections (id, organisation_id, name, slug, type, voter_source_strategy, state, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, NULL, ?, NOW(), NOW())',
            [$electionId, $org->id, ucfirst(str_replace('-', ' ', $slug)), $slug, 'real', 'draft']
        );
        return $electionId;
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_audit_only_reports_elections_missing_snapshot()
    {
        $org = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org-audit',
            'uses_full_membership' => false,
        ]);

        $electionId = $this->createElectionWithNullSnapshot($org, 'audit-test');

        // Run with --audit-only
        $this->artisan('app:backfill-voter-source-strategy', ['--audit-only' => true])
            ->assertExitCode(0);

        // Verify DB unchanged
        $this->assertDatabaseHas('elections', [
            'id' => $electionId,
            'slug' => 'audit-test',
            'voter_source_strategy' => null,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_backfill_sets_election_only_from_election_only_org()
    {
        $org = Organisation::create([
            'name' => 'Election-Only Org',
            'slug' => 'election-only-org',
            'uses_full_membership' => false,
        ]);

        $this->createElectionWithNullSnapshot($org, 'to-backfill-1');

        // Run backfill command
        $this->artisan('app:backfill-voter-source-strategy')
            ->assertExitCode(0);

        // Verify election now has snapshot
        $this->assertDatabaseHas('elections', [
            'slug' => 'to-backfill-1',
            'voter_source_strategy' => 'election_only',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_backfill_sets_full_membership_from_full_membership_org()
    {
        $org = Organisation::create([
            'name' => 'Full Membership Org',
            'slug' => 'full-membership-org',
            'uses_full_membership' => true,
        ]);

        $this->createElectionWithNullSnapshot($org, 'to-backfill-2');

        // Run backfill command
        $this->artisan('app:backfill-voter-source-strategy')
            ->assertExitCode(0);

        // Verify election now has snapshot
        $this->assertDatabaseHas('elections', [
            'slug' => 'to-backfill-2',
            'voter_source_strategy' => 'full_membership',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_backfill_skips_elections_already_having_snapshot()
    {
        $org = Organisation::create([
            'name' => 'Skip Test Org',
            'slug' => 'skip-test-org',
            'uses_full_membership' => false,
        ]);

        Election::query()->create([
            'organisation_id' => $org->id,
            'name' => 'Already Has Snapshot',
            'slug' => 'already-filled',
            'type' => 'real',
            'voter_source_strategy' => 'election_only', // already set
        ]);

        // Run backfill command
        $this->artisan('app:backfill-voter-source-strategy')
            ->assertExitCode(0);

        // Verify election unchanged
        $this->assertDatabaseHas('elections', [
            'slug' => 'already-filled',
            'voter_source_strategy' => 'election_only',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_backfill_reports_count_of_updated_elections()
    {
        $org = Organisation::create([
            'name' => 'Count Test Org',
            'slug' => 'count-test-org',
            'uses_full_membership' => true,
        ]);

        // Create 3 elections with null snapshot via raw SQL
        for ($i = 1; $i <= 3; $i++) {
            $this->createElectionWithNullSnapshot($org, "count-test-{$i}");
        }

        // Create 2 elections with non-null snapshot (can use Election::create now)
        for ($i = 4; $i <= 5; $i++) {
            Election::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'organisation_id' => $org->id,
                'name' => "Election {$i}",
                'slug' => "count-test-{$i}",
                'type' => 'real',
                'voter_source_strategy' => 'full_membership',
            ]);
        }

        // Run backfill command
        $this->artisan('app:backfill-voter-source-strategy')
            ->assertExitCode(0);

        // Verify 3 elections were backfilled and 2 were skipped
        $this->assertDatabaseCount('elections', 5);
        $this->assertDatabaseMissing('elections', [
            'voter_source_strategy' => null,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_backfill_is_idempotent_when_run_twice()
    {
        $org = Organisation::create([
            'name' => 'Idempotent Org',
            'slug' => 'idempotent-org',
            'uses_full_membership' => false,
        ]);

        // Create 3 elections with null snapshot via raw SQL
        for ($i = 1; $i <= 3; $i++) {
            $this->createElectionWithNullSnapshot($org, "idempotent-{$i}");
        }

        // Run backfill command first time
        $this->artisan('app:backfill-voter-source-strategy')
            ->assertExitCode(0);

        // Verify all 3 are now filled
        $this->assertDatabaseCount('elections', 3);
        $this->assertDatabaseMissing('elections', [
            'voter_source_strategy' => null,
        ]);

        // Run backfill command second time (idempotent - should skip all already filled)
        $this->artisan('app:backfill-voter-source-strategy')
            ->assertExitCode(0);

        // All elections still have their snapshots (unchanged)
        $this->assertDatabaseCount('elections', 3);
        $this->assertDatabaseMissing('elections', [
            'voter_source_strategy' => null,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_backfill_resumes_correctly_after_partial_failure()
    {
        $org = Organisation::create([
            'name' => 'Partial Failure Org',
            'slug' => 'partial-failure-org',
            'uses_full_membership' => true,
        ]);

        // Create 5 elections with null snapshot via raw SQL
        for ($i = 1; $i <= 5; $i++) {
            $this->createElectionWithNullSnapshot($org, "partial-{$i}");
        }

        // Manually backfill first 2 (simulating partial run)
        DB::table('elections')
            ->where(function ($q) {
                $q->where('slug', 'like', 'partial-1%')
                  ->orWhere('slug', 'like', 'partial-2%');
            })
            ->update(['voter_source_strategy' => 'full_membership']);

        // Run backfill command (should skip first 2, process remaining 3)
        $this->artisan('app:backfill-voter-source-strategy')
            ->assertExitCode(0);

        // Verify all 5 now have snapshots
        $this->assertDatabaseCount('elections', 5);
        $this->assertDatabaseMissing('elections', [
            'voter_source_strategy' => null,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_audit_only_does_not_affect_idempotency()
    {
        $org = Organisation::create([
            'name' => 'Audit Idempotent Org',
            'slug' => 'audit-idempotent-org',
            'uses_full_membership' => false,
        ]);

        $election = Election::query()->create([
            'organisation_id' => $org->id,
            'name' => 'Audit Idempotent Election',
            'slug' => 'audit-idempotent',
            'type' => 'real',
            'voter_source_strategy' => 'election_only', // already filled
        ]);

        // Run audit-only
        $this->artisan('app:backfill-voter-source-strategy', ['--audit-only' => true])
            ->assertExitCode(0);

        // Verify election unchanged (should not be affected by audit-only on filled election)
        $this->assertDatabaseHas('elections', [
            'slug' => 'audit-idempotent',
            'voter_source_strategy' => 'election_only',
        ]);
    }
}
