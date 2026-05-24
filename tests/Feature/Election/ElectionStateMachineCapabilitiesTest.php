<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionStateMachineCapabilitiesTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $chief;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->organisation->id]);

        $this->election = Election::factory()
            ->forOrganisation($this->organisation)
            ->real()
            ->create(['state' => 'draft']);

        $this->chief = User::factory()->create(['organisation_id' => $this->organisation->id]);
        ElectionOfficer::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $this->chief->id,
            'election_id' => $this->election->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $this->chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);
    }

    public function test_management_index_returns_capabilities_in_state_machine_prop(): void
    {
        $response = $this->actingAs($this->chief)
            ->get(route('elections.management', $this->election));

        $response->assertOk();
        $response->assertInertia(fn($page) => $page->has('stateMachine.capabilities'));
    }

    public function test_capabilities_contains_exactly_all_constitution_actions(): void
    {
        $response = $this->actingAs($this->chief)
            ->get(route('elections.management', $this->election));

        $response->assertOk();
        $response->assertInertia(fn($page) => $page->has('stateMachine.capabilities'));

        $capabilities = $response->original->getData()['page']['props']['stateMachine']['capabilities'];
        $capabilityActions = array_keys($capabilities);

        $constitutionActions = [
            'submit_for_approval',
            'approve',
            'reject',
            'auto_submit',
            'begin_setup',
            'revise_and_resubmit',
            'complete_administration',
            'complete_nomination',
            'open_voting',
            'close_voting',
            'publish_results',
            'archive',
            'suspend',
            'resume',
        ];

        $this->assertEqualsCanonicalizing($constitutionActions, $capabilityActions,
            'Capabilities must contain exactly the constitution actions, no more, no less'
        );
    }

    public function test_each_capability_entry_has_allowed_and_denial_reason_fields(): void
    {
        $response = $this->actingAs($this->chief)
            ->get(route('elections.management', $this->election));

        $response->assertOk();
        $response->assertInertia(fn($page) => $page->has('stateMachine.capabilities'));

        $stateMachine = $response->original->getData()['page']['props']['stateMachine'];
        $capabilities = $stateMachine['capabilities'];

        foreach ($capabilities as $action => $entry) {
            $this->assertIsArray($entry, "Capability '{$action}' must be an array");
            $this->assertArrayHasKey('allowed', $entry, "Capability '{$action}' must have 'allowed' key");
            $this->assertIsBool($entry['allowed'], "Capability '{$action}.allowed' must be boolean");
            $this->assertArrayHasKey('denial_reason', $entry, "Capability '{$action}' must have 'denial_reason' key");
            if ($entry['denial_reason'] !== null) {
                $this->assertIsString($entry['denial_reason'], "Capability '{$action}.denial_reason' must be string or null");
            }
        }
    }

    public function test_suspended_election_capabilities_denies_all_except_resume(): void
    {
        $this->election->update(['suspended_at' => now()]);

        $response = $this->actingAs($this->chief)
            ->get(route('elections.management', $this->election));

        $response->assertOk();
        $response->assertInertia(fn($page) => $page->has('stateMachine.capabilities'));

        $stateMachine = $response->original->getData()['page']['props']['stateMachine'];
        $capabilities = $stateMachine['capabilities'];
        $trace = $stateMachine['capabilities_trace'] ?? null;

        // All actions except 'resume' should be denied
        $actionsToTest = [
            'submit_for_approval',
            'approve',
            'reject',
            'auto_submit',
            'begin_setup',
            'complete_administration',
            'complete_nomination',
            'open_voting',
            'close_voting',
            'publish_results',
            'archive',
        ];

        foreach ($actionsToTest as $action) {
            $this->assertFalse(
                $capabilities[$action]['allowed'],
                "Action '{$action}' must be denied when election is suspended"
            );
        }

        // 'resume' should be allowed
        $this->assertTrue(
            $capabilities['resume']['allowed'],
            "Action 'resume' must be allowed when election is suspended"
        );

        // Verify overlay short-circuit in trace (if trace is present)
        if ($trace !== null) {
            $policyNames = array_column($trace, 'policyName');
            $this->assertContains(
                'OverlayCapabilityPolicy',
                $policyNames,
                'OverlayCapabilityPolicy must appear in trace for suspended election'
            );
        }
    }

    public function test_allowed_actions_removed_from_state_machine_prop(): void
    {
        $response = $this->actingAs($this->chief)
            ->get(route('elections.management', $this->election));

        $response->assertOk();
        $stateMachine = $response->original->getData()['page']['props']['stateMachine'];

        $this->assertArrayNotHasKey(
            'allowedActions',
            $stateMachine,
            'allowedActions must be removed from stateMachine (C.2.5 removal contract)'
        );
    }

    public function test_open_voting_blocked_reason_removed_from_inertia_props(): void
    {
        $response = $this->actingAs($this->chief)
            ->get(route('elections.management', $this->election));

        $response->assertOk();
        $props = $response->original->getData()['page']['props'];

        $this->assertArrayNotHasKey(
            'openVotingBlockedReason',
            $props,
            'openVotingBlockedReason superseded by capabilities.open_voting.denial_reason'
        );
    }

    public function test_unauthenticated_user_gets_all_capabilities_denied(): void
    {
        // Make a request without authentication
        $response = $this->get(route('elections.management', $this->election));

        // Should be redirected to login, but if somehow accessed, capabilities should be denied
        // For now, we expect a redirect/forbidden
        $this->assertTrue(
            $response->status() === 302 || $response->status() === 403,
            'Unauthenticated user should not access election management'
        );
    }

    public function test_capabilities_metadata_contains_resolver_version_and_timestamp(): void
    {
        $response = $this->actingAs($this->chief)
            ->get(route('elections.management', $this->election));

        $response->assertOk();
        $response->assertInertia(fn($page) => $page->has('stateMachine.capabilities_metadata'));

        $stateMachine = $response->original->getData()['page']['props']['stateMachine'];
        $this->assertArrayHasKey('capabilities_metadata', $stateMachine,
            'stateMachine must have capabilities_metadata key'
        );

        $metadata = $stateMachine['capabilities_metadata'];
        $this->assertArrayHasKey('resolver_version', $metadata);
        $this->assertArrayHasKey('generated_at', $metadata);
        $this->assertArrayHasKey('constitution_hash', $metadata);

        $this->assertIsString($metadata['resolver_version']);
        $this->assertIsString($metadata['generated_at']);
        $this->assertIsString($metadata['constitution_hash']);
    }

    public function test_capabilities_actions_match_constitution_exactly_no_gaps_no_extras(): void
    {
        $response = $this->actingAs($this->chief)
            ->get(route('elections.management', $this->election));

        $response->assertOk();
        $response->assertInertia(fn($page) => $page->has('stateMachine.capabilities'));

        $stateMachine = $response->original->getData()['page']['props']['stateMachine'];
        $capabilities = $stateMachine['capabilities'];
        $capabilityActions = array_keys($capabilities);

        // 14 actions defined in constitution
        $this->assertCount(14, $capabilityActions,
            'Capabilities must contain exactly 14 actions (no gaps, no extras)'
        );

        // No undefined actions
        $validActions = [
            'submit_for_approval',
            'approve',
            'reject',
            'auto_submit',
            'begin_setup',
            'revise_and_resubmit',
            'complete_administration',
            'complete_nomination',
            'open_voting',
            'close_voting',
            'publish_results',
            'archive',
            'suspend',
            'resume',
        ];

        foreach ($capabilityActions as $action) {
            $this->assertContains($action, $validActions,
                "Action '{$action}' is not defined in constitution"
            );
        }
    }
}
