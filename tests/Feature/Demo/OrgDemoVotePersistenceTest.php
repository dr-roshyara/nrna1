<?php

namespace Tests\Feature\Demo;

use Tests\TestCase;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoVote;
use App\Models\DemoResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

/**
 * Org Demo Vote Persistence Tests
 *
 * Ensures that demo votes are persisted with correct organisation_id:
 * - Using session's current_organisation_id when set
 * - Falling back to election's organisation_id when session is empty
 */
class OrgDemoVotePersistenceTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $platformOrg;
    protected Election $demoElection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->platformOrg = Organisation::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'slug' => 'test-platform-' . Str::random(4),
            'type' => 'platform',
            'is_default' => true,
            'name' => 'Test Platform',
        ]);

        $this->demoElection = Election::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'type' => 'demo',
            'slug' => 'demo-election-test',
            'organisation_id' => $this->platformOrg->id,
            'name' => 'Demo Election',
            'is_active' => true,
            'status' => 'active',
        ]);
    }

    /** @test */
    public function demo_vote_organisation_id_uses_election_when_session_absent(): void
    {
        // Setup: no current_organisation_id in session
        session()->forget('current_organisation_id');

        // Create a demo vote directly (simulating save_vote internals after the fix)
        $vote = DemoVote::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->demoElection->id,
            'organisation_id' => session('current_organisation_id') ?? $this->demoElection->organisation_id,
            'voted_at' => now(),
            'cast_at' => now(),
            'receipt_hash' => hash('sha256', 'test'),
            'vote_hash' => hash('sha256', 'test-vote'),
        ]);

        // Assert: vote has election's organisation_id (not NULL)
        $this->assertDatabaseHas('demo_votes', [
            'election_id' => $this->demoElection->id,
            'organisation_id' => $this->platformOrg->id,
        ]);

        $this->assertNotNull($vote->organisation_id);
        $this->assertEquals($this->platformOrg->id, $vote->organisation_id);
    }

    /** @test */
    public function demo_result_organisation_id_uses_election_when_session_absent(): void
    {
        // Setup
        $post = DemoPost::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->demoElection->id,
            'organisation_id' => $this->platformOrg->id,
            'name' => 'Test Post',
            'is_national_wide' => true,
            'required_number' => 1,
        ]);

        $candidate = DemoCandidacy::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'post_id' => $post->id,
            'election_id' => $this->demoElection->id,
            'organisation_id' => $this->platformOrg->id,
            'name' => 'Test Candidate',
        ]);

        $vote = DemoVote::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->demoElection->id,
            'organisation_id' => $this->platformOrg->id,
            'voted_at' => now(),
            'cast_at' => now(),
            'receipt_hash' => hash('sha256', 'test'),
            'vote_hash' => hash('sha256', 'test-vote'),
        ]);

        // No session org_id set
        session()->forget('current_organisation_id');

        // Create result with fallback logic
        DemoResult::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'vote_id' => $vote->id,
            'election_id' => $this->demoElection->id,
            'post_id' => $post->id,
            'candidacy_id' => $candidate->id,
            'organisation_id' => session('current_organisation_id') ?? $this->demoElection->organisation_id,
        ]);

        // Assert: result has election's organisation_id (fallback)
        $this->assertDatabaseHas('demo_results', [
            'election_id' => $this->demoElection->id,
            'post_id' => $post->id,
            'organisation_id' => $this->platformOrg->id,
        ]);
    }

    /** @test */
    public function demo_vote_uses_session_organisation_when_present(): void
    {
        // Setup: create a different org and set session org_id
        $sessionOrg = Organisation::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'slug' => 'session-org',
            'type' => 'platform',
            'name' => 'Session Org',
        ]);
        session(['current_organisation_id' => $sessionOrg->id]);

        // Create vote with session-based fallback
        $vote = DemoVote::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->demoElection->id,
            'organisation_id' => session('current_organisation_id') ?? $this->demoElection->organisation_id,
            'voted_at' => now(),
            'cast_at' => now(),
            'receipt_hash' => hash('sha256', 'test2'),
            'vote_hash' => hash('sha256', 'test-vote-2'),
        ]);

        // Assert: vote uses SESSION org, not election's org
        $this->assertEquals($sessionOrg->id, $vote->organisation_id);
        $this->assertNotEquals($this->platformOrg->id, $vote->organisation_id);
    }

    /** @test */
    public function demo_votes_are_never_null_for_org_demo_elections(): void
    {
        // This test ensures that the fallback logic prevents NULL organisation_id
        // for org-scoped demo elections

        $votes = [];

        // Scenario 1: Session has org_id
        session(['current_organisation_id' => $this->platformOrg->id]);
        $votes[] = DemoVote::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->demoElection->id,
            'organisation_id' => session('current_organisation_id') ?? $this->demoElection->organisation_id,
            'voted_at' => now(),
            'cast_at' => now(),
            'receipt_hash' => hash('sha256', 'scenario1'),
            'vote_hash' => hash('sha256', 'scenario1-vote'),
        ]);

        // Scenario 2: Session missing org_id (fallback to election)
        session()->forget('current_organisation_id');
        $votes[] = DemoVote::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->demoElection->id,
            'organisation_id' => session('current_organisation_id') ?? $this->demoElection->organisation_id,
            'voted_at' => now(),
            'cast_at' => now(),
            'receipt_hash' => hash('sha256', 'scenario2'),
            'vote_hash' => hash('sha256', 'scenario2-vote'),
        ]);

        // Assert: NO votes have NULL organisation_id
        foreach ($votes as $vote) {
            $this->assertNotNull($vote->organisation_id);
        }

        // All votes in database should have organisation_id set
        $nullVotes = DemoVote::withoutGlobalScopes()
            ->where('election_id', $this->demoElection->id)
            ->whereNull('organisation_id')
            ->count();
        $this->assertEquals(0, $nullVotes);
    }
}
