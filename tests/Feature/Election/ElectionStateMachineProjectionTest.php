<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionStateMachineProjectionTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $chief;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->organisation->id]);

        $this->chief = User::factory()->create(['organisation_id' => $this->organisation->id]);
    }

    private function createElectionWithChief(string $state): Election
    {
        $election = Election::factory()
            ->forOrganisation($this->organisation)
            ->real()
            ->create(['state' => $state]);

        ElectionOfficer::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $this->chief->id,
            'election_id' => $election->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $this->chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        return $election;
    }

    public function test_stateMachine_includes_completedStates_and_projectionAvailable(): void
    {
        $election = $this->createElectionWithChief('setup_nomination');

        $this->actingAs($this->chief)
            ->get(route('elections.management', $election))
            ->assertInertia(fn($assert) => $assert
                ->has('stateMachine.completedStates')
                ->has('stateMachine.projectionAvailable')
            );
    }

    public function test_draft_has_empty_completedStates_and_projection_available(): void
    {
        $election = $this->createElectionWithChief('draft');

        $this->actingAs($this->chief)
            ->get(route('elections.management', $election))
            ->assertInertia(fn($assert) => $assert
                ->where('stateMachine.completedStates', [])
                ->where('stateMachine.projectionAvailable', true)
            );
    }

    public function test_completedStates_structure_and_types(): void
    {
        $election = $this->createElectionWithChief('draft');

        $this->actingAs($this->chief)
            ->get(route('elections.management', $election))
            ->assertInertia(function ($assert) {
                $stateMachine = $assert->toArray()['props']['stateMachine'];

                // Verify structure
                $this->assertIsArray($stateMachine['completedStates']);
                $this->assertIsBool($stateMachine['projectionAvailable']);

                // All values should be strings (lifecycle state names)
                foreach ($stateMachine['completedStates'] as $state) {
                    $this->assertIsString($state);
                }
            });
    }

    public function test_completedStates_respects_progression_order(): void
    {
        $election = $this->createElectionWithChief('draft');

        $this->actingAs($this->chief)
            ->get(route('elections.management', $election))
            ->assertInertia(function ($assert) {
                $completedStates = $assert->toArray()['props']['stateMachine']['completedStates'];

                // Draft should have no prior completed states
                $this->assertEmpty($completedStates);
            });
    }
}
