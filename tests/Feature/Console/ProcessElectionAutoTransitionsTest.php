<?php

namespace Tests\Feature\Console;

use App\Models\Election;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\ElectionMembership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessElectionAutoTransitionsTest extends TestCase
{
    use RefreshDatabase;

    // ── Administration → Nomination Transitions (4 tests) ────────────────────

    public function test_transitions_to_nomination_when_grace_elapsed(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'allow_auto_transition' => true,
            'auto_transition_grace_days' => 1,
            'administration_completed_at' => now()->subDays(2),
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        // Create required post, voter, and approved candidacy
        $post = Post::factory()->create(['election_id' => $election->id]);
        Candidacy::factory()->create(['post_id' => $post->id, 'status' => 'approved']);

        $voter = User::factory()->create();
        ElectionMembership::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'user_id' => $voter->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);

        $election->refresh();
        $this->assertTrue($election->nomination_completed);
    }

    public function test_skips_transition_when_allow_auto_transition_false(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'allow_auto_transition' => false,
            'auto_transition_grace_days' => 1,
            'administration_completed_at' => now()->subDays(2),
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);

        $election->refresh();
        $this->assertFalse($election->nomination_completed);
    }

    public function test_skips_transition_when_grace_not_elapsed(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'allow_auto_transition' => true,
            'auto_transition_grace_days' => 3,
            'administration_completed_at' => now()->subHours(12),
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);

        $election->refresh();
        $this->assertFalse($election->nomination_completed);
    }

    public function test_skips_nomination_transition_when_no_posts_or_voters(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'allow_auto_transition' => true,
            'auto_transition_grace_days' => 1,
            'administration_completed_at' => now()->subDays(2),
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);

        $election->refresh();
        $this->assertFalse($election->nomination_completed);
    }

    // ── Nomination → Voting Transitions (3 tests) ───────────────────────────

    public function test_transitions_to_voting_when_nomination_ended_no_pending_candidates(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'allow_auto_transition' => true,
            'auto_transition_grace_days' => 0,
            'nomination_completed_at' => now()->subDays(1),
        ]);

        // Create post with only approved candidates
        $post = Post::factory()->create(['election_id' => $election->id]);
        Candidacy::factory()->create(['post_id' => $post->id, 'status' => 'approved']);

        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);

        $election->refresh();
        // After transition, voting should be locked
        $this->assertTrue($election->voting_locked);
    }

    public function test_skips_voting_transition_when_pending_candidates_exist(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'allow_auto_transition' => true,
            'auto_transition_grace_days' => 0,
            'nomination_completed_at' => now()->subDays(1),
        ]);

        // Create post with pending candidacy
        $post = Post::factory()->create(['election_id' => $election->id]);
        Candidacy::factory()->create(['post_id' => $post->id, 'status' => 'pending']);

        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);

        $election->refresh();
        // Voting should not be locked when pending candidates exist
        $this->assertFalse($election->voting_locked);
    }

    public function test_skips_voting_transition_when_grace_not_elapsed(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'allow_auto_transition' => true,
            'auto_transition_grace_days' => 5,
            'nomination_completed_at' => now()->subHours(12),
        ]);

        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);

        $election->refresh();
        // Voting should not be locked when grace period not elapsed
        $this->assertFalse($election->voting_locked);
    }

    // ── Voting Lock Enforcement (2 tests) ───────────────────────────────────

    public function test_locks_voting_when_voting_ended_and_not_locked(): void
    {
        $user = User::factory()->create();
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_locked' => false,
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHours(1),
            'results_published_at' => null,
        ]);

        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);

        $election->refresh();
        $this->assertTrue($election->voting_locked);
        $this->assertNotNull($election->voting_locked_at);
    }

    public function test_does_not_lock_voting_when_still_active(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_locked' => false,
            'voting_starts_at' => now()->subHours(1),
            'voting_ends_at' => now()->addHours(2),
        ]);

        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);

        $election->refresh();
        $this->assertFalse($election->voting_locked);
    }

    // ── Command Output & Exit Code (2 tests) ────────────────────────────────

    public function test_command_reports_transition_count(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'allow_auto_transition' => true,
            'auto_transition_grace_days' => 1,
            'administration_completed_at' => now()->subDays(2),
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $post = Post::factory()->create(['election_id' => $election->id]);
        Candidacy::factory()->create(['post_id' => $post->id, 'status' => 'approved']);

        $voter = User::factory()->create();
        ElectionMembership::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'user_id' => $voter->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $this->artisan('elections:process-auto-transitions')
            ->expectsOutput('Processed 1 election(s) for automatic transitions')
            ->assertExitCode(0);
    }

    public function test_command_exits_with_0(): void
    {
        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);
    }

    // ── Grace Period Configuration (2 tests) ─────────────────────────────────

    public function test_respects_auto_transition_grace_days_column(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'allow_auto_transition' => true,
            'auto_transition_grace_days' => 7,
            'administration_completed_at' => now()->subDays(2),
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);

        $election->refresh();
        $this->assertFalse($election->nomination_completed);
    }

    public function test_respects_allow_auto_transition_flag(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'allow_auto_transition' => false,
            'auto_transition_grace_days' => 0,
            'administration_completed_at' => now()->subDays(2),
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $this->artisan('elections:process-auto-transitions')->assertExitCode(0);

        $election->refresh();
        $this->assertFalse($election->nomination_completed);
    }
}
