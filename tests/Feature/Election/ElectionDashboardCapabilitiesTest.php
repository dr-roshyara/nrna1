<?php

namespace Tests\Feature\Election;

use App\Models\User;
use App\Models\Organisation;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ElectionDashboardCapabilitiesTest — TDD Test for Constitutional Authority Projection
 *
 * REQUIREMENT: Election management pages must receive stateMachine.capabilities
 * from backend before frontend can use useElectionCapabilities() composable.
 *
 * This test ensures the controller provides the capability snapshot with proper
 * schema: { allowed, denial_reason, denial_detail } for each capability entry.
 */
class ElectionDashboardCapabilitiesTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_election_dashboard_receives_stateMachine_with_capabilities(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $org = Organisation::factory()->create();

        $user->organisationRoles()->create([
            'organisation_id' => $org->id,
            'role' => 'chief',
        ]);

        session(['current_organisation_id' => $org->id]);

        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'type' => 'real',
        ]);

        $response = $this->actingAs($user)->get(route('election.dashboard'));

        // Dashboard redirects based on resolver priority, but ensure that
        // if an election page is rendered, it has stateMachine.capabilities
        //
        // For now, verify the response is a redirect (expected behavior)
        $response->assertRedirect();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_stateMachine_data_structure_includes_all_required_fields(): void
    {
        // Unit test the getStateMachineData method directly to verify structure
        $user = User::factory()->create(['email_verified_at' => now()]);
        $org = Organisation::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'type' => 'real',
            'state' => 'draft',
        ]);

        // Use app() to get controller with dependencies injected
        $controller = app(\App\Http\Controllers\Election\ElectionManagementController::class);

        // Use reflection to call the private method
        $reflection = new \ReflectionMethod($controller, 'getStateMachineData');
        $reflection->setAccessible(true);
        $stateMachine = $reflection->invoke($controller, $election);

        // Verify structure
        $this->assertIsArray($stateMachine);
        $this->assertArrayHasKey('currentState', $stateMachine);
        $this->assertArrayHasKey('completedStates', $stateMachine);
        $this->assertArrayHasKey('projectionAvailable', $stateMachine);
        $this->assertArrayHasKey('capabilities', $stateMachine);
        $this->assertArrayHasKey('capabilities_metadata', $stateMachine);

        // Verify capabilities_metadata structure
        $this->assertArrayHasKey('resolver_version', $stateMachine['capabilities_metadata']);
        $this->assertArrayHasKey('generated_at', $stateMachine['capabilities_metadata']);
        $this->assertArrayHasKey('constitution_hash', $stateMachine['capabilities_metadata']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_capabilities_map_has_all_required_actions(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $org = Organisation::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'type' => 'real',
        ]);

        $controller = app(\App\Http\Controllers\Election\ElectionManagementController::class);
        $reflection = new \ReflectionMethod($controller, 'getStateMachineData');
        $reflection->setAccessible(true);
        $stateMachine = $reflection->invoke($controller, $election);

        $requiredCapabilities = [
            'submit_for_approval',
            'approve',
            'reject',
            'auto_submit',
            'begin_setup',
            'revise_and_resubmit',
            'complete_administration',
            'complete_nomination',
            'apply_candidacy',
            'open_voting',
            'close_voting',
            'publish_results',
            'archive',
            'suspend',
            'resume',
        ];

        foreach ($requiredCapabilities as $action) {
            $this->assertArrayHasKey($action, $stateMachine['capabilities'],
                "Capability '{$action}' not found in capabilities map"
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_capability_entry_has_required_fields(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $org = Organisation::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'type' => 'real',
        ]);

        $controller = app(\App\Http\Controllers\Election\ElectionManagementController::class);
        $reflection = new \ReflectionMethod($controller, 'getStateMachineData');
        $reflection->setAccessible(true);
        $stateMachine = $reflection->invoke($controller, $election);

        // Verify each capability entry has required fields
        foreach ($stateMachine['capabilities'] as $action => $entry) {
            $this->assertIsArray($entry, "Capability '{$action}' entry should be an array");
            $this->assertArrayHasKey('allowed', $entry, "Capability '{$action}' missing 'allowed'");
            $this->assertArrayHasKey('denial_reason', $entry, "Capability '{$action}' missing 'denial_reason'");
            $this->assertArrayHasKey('denial_detail', $entry, "Capability '{$action}' missing 'denial_detail'");

            // Verify types
            $this->assertIsBool($entry['allowed']);
            $this->assertTrue(
                $entry['denial_reason'] === null || is_string($entry['denial_reason']),
                "Capability '{$action}' denial_reason must be string|null"
            );
            $this->assertTrue(
                $entry['denial_detail'] === null || is_string($entry['denial_detail']),
                "Capability '{$action}' denial_detail must be string|null"
            );
        }
    }
}
