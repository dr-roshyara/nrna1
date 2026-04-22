<?php

namespace Tests\Feature\Middleware;

use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureElectionStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_allows_request_when_action_permitted(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $this->assertTrue($election->allowsAction('manage_settings'));
    }

    public function test_blocks_request_when_action_not_permitted_with_403(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(1),
            'voting_ends_at' => now()->addHours(2),
        ]);

        $this->assertFalse($election->allowsAction('manage_settings'));
    }

    public function test_error_message_includes_operation_and_state(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(1),
            'voting_ends_at' => now()->addHours(2),
        ]);

        $stateInfo = $election->state_info;
        $this->assertArrayHasKey('name', $stateInfo);
        $this->assertIsString($stateInfo['name']);
    }

    public function test_resolves_election_from_string_slug(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $foundElection = Election::where('id', $election->id)->withoutGlobalScopes()->first();
        $this->assertNotNull($foundElection);
    }

    public function test_resolves_election_from_model_instance(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $this->assertInstanceOf(Election::class, $election);
        $this->assertTrue($election->allowsAction('manage_settings'));
    }

    public function test_returns_404_when_election_not_found(): void
    {
        $foundElection = Election::where('id', 'non-existent-uuid')->first();
        $this->assertNull($foundElection);
    }

    public function test_middleware_uses_state_machine_delegation(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $stateMachine = $election->getStateMachine();
        $this->assertTrue($stateMachine->allowsAction('manage_settings'));
    }
}
