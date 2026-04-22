<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionPolicyStateAwareTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;
    private User $orgOwner;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orgOwner = User::factory()->create();
        $this->officer = User::factory()->create();

        $this->election = Election::factory()->create([
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        // Assign roles (would normally use permission system)
        // For testing, we'll check policy logic directly
    }

    public function test_manage_settings_allowed_in_administration_for_officer(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        // In administration state, should allow settings management
        $this->assertTrue($election->allowsAction('manage_settings'));
    }

    public function test_manage_settings_allowed_in_nomination_for_officer(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        // In nomination state, should allow settings management
        $this->assertTrue($election->allowsAction('manage_settings'));
    }

    public function test_manage_settings_denied_during_voting_for_officer(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(1),
            'voting_ends_at' => now()->addHours(2),
        ]);

        // During voting state, should deny settings management
        $this->assertFalse($election->allowsAction('manage_settings'));
    }

    public function test_manage_settings_denied_during_results_pending_for_officer(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHours(2),
            'results_published_at' => null,
        ]);

        // In results_pending state, should deny settings management
        $this->assertFalse($election->allowsAction('manage_settings'));
    }

    public function test_manage_settings_denied_in_results_state(): void
    {
        $election = Election::factory()->create([
            'results_published_at' => now()->subHours(1),
        ]);

        // In results state, should deny settings management
        $this->assertFalse($election->allowsAction('manage_settings'));
    }

    public function test_cast_vote_allowed_during_voting_state(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(1),
            'voting_ends_at' => now()->addHours(2),
        ]);

        // During voting state, should allow vote casting
        $this->assertTrue($election->allowsAction('cast_vote'));
    }

    public function test_cast_vote_denied_outside_voting_state(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        // Outside voting state, should deny vote casting
        $this->assertFalse($election->allowsAction('cast_vote'));
    }

    public function test_cast_vote_denied_after_voting_ends(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHours(1),
        ]);

        // After voting ends, should deny vote casting
        $this->assertFalse($election->allowsAction('cast_vote'));
    }

    public function test_view_results_allowed_in_results_state(): void
    {
        $election = Election::factory()->create([
            'results_published_at' => now()->subHours(1),
        ]);

        // In results state, should allow viewing results
        $this->assertTrue($election->allowsAction('view_results'));
    }

    public function test_view_results_denied_before_publication(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHours(2),
            'results_published_at' => null,
        ]);

        // Before results published, should deny viewing
        $this->assertFalse($election->allowsAction('view_results'));
    }
}
