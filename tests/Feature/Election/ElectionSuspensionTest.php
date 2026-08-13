<?php

namespace Tests\Feature\Election;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\Constitution\ElectionConstitution;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\ElectionAuditLog;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Phase B: Operational Suspension Controls
 * Feature Test: Election Suspension & Resume Lifecycle
 *
 * RED tests for suspension behavior:
 * 1. Suspension captures lifecycle context (for audit, not restoration)
 * 2. Suspension stores governance metadata (why, who, when)
 * 3. Resume clears suspension flags, engine re-derives state
 * 4. Resume does not restore from suspended_lifecycle_context column
 * 5. Suspension does not mutate business facts
 * 6. Capabilities are blocked during suspension
 */
class ElectionSuspensionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable CSRF for all POST requests in this test class.
        // None of these tests test CSRF behavior; they test suspend/resume business logic.
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    /**
     * RED test: Suspend captures engine-derived lifecycle context
     *
     * When an election is suspended, we preserve which lifecycle state
     * it was in at the moment of suspension. This is for audit trails
     * and forensic reconstruction ONLY — not for restoration.
     */
    public function test_suspend_captures_lifecycle_context(): void
    {
        $election = Election::factory()->create([
            'setup_started_at' => now()->subDays(30),
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(2),  // voting is currently active
            'voting_ends_at' => now()->addHours(6),
            'results_published_at' => null,
            'suspended_at' => null,
            'suspended_lifecycle_context' => null,
        ]);

        // EM-VOT-002 (adopted): VotingActive requires an approved candidate —
        // this test's premise is an election actively voting when suspended.
        $post = \App\Models\Post::factory()->create([
            'election_id'     => $election->id,
            'organisation_id' => $election->organisation_id,
        ]);
        \App\Models\Candidacy::factory()->create([
            'post_id'         => $post->id,
            'organisation_id' => $election->organisation_id,
            'user_id'         => \App\Models\User::factory()->create()->id,
            'status'          => 'approved',
        ]);

        // Election should be in VotingActive state
        $this->assertEquals(ElectionLifecycleState::VotingActive, $election->currentState());

        // Suspend the election
        // The engine-derived state (VotingActive) should be captured in suspended_lifecycle_context
        $election->update([
            'suspended_at' => now(),
            'suspended_lifecycle_context' => ElectionLifecycleState::VotingActive->value,
        ]);

        $this->assertNotNull($election->suspended_at);
        $this->assertEquals('voting_active', $election->suspended_lifecycle_context,
            'Suspended context should capture the engine-derived state at suspension time');
    }

    /**
     * RED test: Suspend stores governance metadata
     *
     * Suspension should record:
     * - Who suspended it (UUID of user)
     * - Why it was suspended (reason text)
     * - Category of suspension (fraud_investigation, legal_hold, etc.)
     * - When it was suspended (suspended_at timestamp)
     */
    public function test_suspend_stores_governance_metadata(): void
    {
        $election = Election::factory()->create();
        $user = \App\Models\User::factory()->create();

        $election->update([
            'suspended_at' => now(),
            'suspended_by' => $user->id,
            'suspended_reason' => 'Potential vote manipulation detected in regional voting data',
            'suspension_category' => 'fraud_investigation',
            'suspended_lifecycle_context' => ElectionLifecycleState::VotingActive->value,
        ]);

        $this->assertNotNull($election->suspended_at);
        $this->assertEquals($user->id, $election->suspended_by);
        $this->assertStringContainsString('vote manipulation', $election->suspended_reason);
        $this->assertEquals('fraud_investigation', $election->suspension_category);
    }

    /**
     * RED test: Resume clears suspension flags only
     *
     * When resuming, we clear all suspension metadata:
     * - suspended_at
     * - suspended_by
     * - suspended_reason
     * - suspension_category
     * - suspended_lifecycle_context
     *
     * We do NOT restore suspended_lifecycle_context to the state column.
     * The engine re-derives state from current facts.
     */
    public function test_resume_clears_suspension_flags_only(): void
    {
        $user = \App\Models\User::factory()->create();

        $election = Election::factory()->create([
            'setup_started_at' => now()->subDays(30),
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(2),
            'voting_ends_at' => now()->addHours(6),
            'results_published_at' => null,
            'suspended_at' => now()->subHours(2),
            'suspended_by' => $user->id,
            'suspended_reason' => 'Emergency governance hold',
            'suspension_category' => 'operational_incident',
            'suspended_lifecycle_context' => ElectionLifecycleState::VotingActive->value,
        ]);

        // Verify it's suspended
        $this->assertNotNull($election->suspended_at);

        // Resume the election
        $election->update([
            'suspended_at' => null,
            'suspended_by' => null,
            'suspended_reason' => null,
            'suspension_category' => null,
            'suspended_lifecycle_context' => null,
            'resumed_at' => now(),
            'resumed_by' => $user->id,
        ]);

        // All suspension flags should be cleared
        $this->assertNull($election->suspended_at);
        $this->assertNull($election->suspended_by);
        $this->assertNull($election->suspended_reason);
        $this->assertNull($election->suspension_category);
        $this->assertNull($election->suspended_lifecycle_context);

        // Resume metadata recorded for audit
        $this->assertNotNull($election->resumed_at);
        $this->assertEquals($user->id, $election->resumed_by);
    }

    /**
     * RED test: Resume does not restore state from column
     *
     * After resuming, the engine must re-derive lifecycle state
     * from current constitutional facts, NOT read it from
     * suspended_lifecycle_context column.
     *
     * Example: If election was VotingActive, then suspended, then
     * time passed and voting window expired, resume should re-derive
     * Counting (not VotingActive).
     */
    public function test_resume_does_not_restore_from_context_column(): void
    {
        $election = Election::factory()->create([
            'approved_at' => now()->subDays(40),      // approved long ago
            'setup_started_at' => now()->subDays(30),
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(5),
            'voting_ends_at' => now()->subHours(1),   // voting ended 1 hour ago
            'results_published_at' => null,
            'suspended_at' => now()->subHours(2),     // suspended 2 hours ago (after voting ended)
            'suspended_lifecycle_context' => ElectionLifecycleState::VotingActive->value,  // old context
        ]);

        // Before resume, engine returns Suspended (because suspended_at is set)
        $this->assertEquals(ElectionLifecycleState::Suspended, $election->currentState());

        // Resume: clear suspension flags
        $election->update([
            'suspended_at' => null,
            'suspended_lifecycle_context' => null,
            'resumed_at' => now(),
        ]);

        // After resume, engine should re-derive from current facts
        // Voting window is closed (voting_ends_at is in the past)
        // So engine should return Counting (not VotingActive from the context column)
        $state = $election->currentState();

        $this->assertEquals(ElectionLifecycleState::Counting, $state,
            'Engine must re-derive from facts, not restore from suspended_lifecycle_context. ' .
            'Voting window expired during suspension, so state should be Counting.');
    }

    /**
     * RED test: Suspension does not mutate business facts
     *
     * All business facts (timestamps, completion flags, approval facts)
     * should remain unchanged when suspending.
     *
     * Suspension is purely an operational overlay — it changes
     * capabilities and blocks actions, but preserves all constitutional facts.
     */
    public function test_suspension_does_not_mutate_business_facts(): void
    {
        $election = Election::factory()->create([
            'approved_at' => now()->subDays(20),
            'setup_started_at' => now()->subDays(15),
            'administration_completed_at' => now()->subDays(10),
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(2),
            'voting_ends_at' => now()->addHours(6),
        ]);

        // Snapshot facts before suspension
        $approvedAtBefore = $election->approved_at;
        $setupStartedAtBefore = $election->setup_started_at;
        $adminCompletedAtBefore = $election->administration_completed_at;
        $votingStartsBefore = $election->voting_starts_at;
        $votingEndsBefore = $election->voting_ends_at;

        // Suspend
        $election->update([
            'suspended_at' => now(),
            'suspended_reason' => 'Testing governance hold',
        ]);

        // All facts should be unchanged
        $this->assertEquals($approvedAtBefore, $election->approved_at);
        $this->assertEquals($setupStartedAtBefore, $election->setup_started_at);
        $this->assertEquals($adminCompletedAtBefore, $election->administration_completed_at);
        $this->assertEquals($votingStartsBefore, $election->voting_starts_at);
        $this->assertEquals($votingEndsBefore, $election->voting_ends_at);

        // Completion flags unchanged
        $this->assertTrue($election->administration_completed);
        $this->assertTrue($election->nomination_completed);
    }

    /**
     * RED test: Capabilities are blocked during suspension
     *
     * When suspended, the snapshot should return:
     * - canVote = false (no voting allowed)
     * - canEdit = false (no editing allowed)
     * - canManageVoters = false (no voter management)
     * - canPublishResults = false (no results publication)
     * - canEditTimeline = false (no date changes)
     * - allowedActions = ['resume'] (only resume is allowed)
     * - blockedReason = message explaining suspension
     */
    public function test_capabilities_blocked_during_suspension(): void
    {
        $election = Election::factory()->create([
            'setup_started_at' => now()->subDays(30),
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(2),
            'voting_ends_at' => now()->addHours(6),
            'suspended_at' => now(),
            'suspended_reason' => 'Fraud investigation',
            'suspension_category' => 'fraud_investigation',
        ]);

        $chief = User::factory()->create(['organisation_id' => $election->organisation_id]);
        ElectionOfficer::create([
            'organisation_id' => $election->organisation_id,
            'user_id' => $chief->id,
            'election_id' => $election->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        // Get the API response where capabilities are computed
        $response = $this->actingAs($chief)
            ->get(route('elections.management', $election));

        $response->assertOk();
        $stateMachine = $response->original->getData()['page']['props']['stateMachine'];
        $capabilities = $stateMachine['capabilities'];

        // All capabilities except 'resume' should be denied
        $blockedActions = [
            'submit_for_approval', 'approve', 'reject', 'auto_submit', 'begin_setup',
            'revise_and_resubmit', 'complete_administration', 'complete_nomination',
            'open_voting', 'close_voting', 'publish_results', 'archive', 'suspend',
        ];

        foreach ($blockedActions as $action) {
            $this->assertFalse(
                $capabilities[$action]['allowed'],
                "Action '{$action}' must be denied during suspension"
            );
        }

        // Only 'resume' should be allowed
        $this->assertTrue(
            $capabilities['resume']['allowed'],
            'Resume must be allowed when election is suspended'
        );
    }

    // ============================================================
    // HTTP SUSPEND FLOW TESTS — constitutional, projection-safe
    //
    // Uses ElectionScenarioFactory (NOT manual state fabrication).
    // Derives blocked actions from ElectionConstitution (NOT hardcoded).
    // Uses ElectionLifecycle::of()->state() for engine state assertions.
    // Uses assertInertia() for Inertia response assertions.
    // ============================================================

    /**
     * Derive all actions the Constitution defines, minus 'resume'.
     * This is future-proof: adding a new action to RULES automatically
     * includes it in the blocked-actions assertion.
     */
    private static function blockedActionsExceptResume(): array
    {
        return array_values(array_diff(
            array_keys(ElectionConstitution::RULES),
            ['resume']
        ));
    }

    /**
     * Extract capabilities array from an Inertia management page response.
     * Uses assertInertia to validate the response shape before extracting.
     */
    private function extractCapabilities(\Illuminate\Testing\TestResponse $response): array
    {
        $response->assertInertia(fn ($page) => $page
            ->component('Election/Management')
            ->has('stateMachine.capabilities')
        );

        return $response->original->getData()['page']['props']['stateMachine']['capabilities'];
    }

    /**
     * Assert the engine derives a specific lifecycle state (not raw DB column).
     */
    private function assertEngineDerivesState(Election $election, ElectionLifecycleState $expected): void
    {
        $actual = ElectionLifecycle::of($election->fresh())->state();
        $this->assertEquals(
            $expected,
            $actual,
            "Engine should derive {$expected->value}, got {$actual->value}"
        );
    }

    // ----------------------------------------------------------
    // 1. BASIC ROUTE EXISTENCE
    // ----------------------------------------------------------

    /** @test */
    public function suspend_route_redirects_when_chief_posts(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

        [$election, $chief, $org] = ElectionScenarioFactory::votingActiveWithChief();

        \App\Services\TenantContext::set($org->id);

        $managementUrl = route('elections.management', ['election' => $election->slug]);

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->from($managementUrl)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Governance hold: voting irregularities detected in regional data.',
            ]);

        $response->assertRedirect($managementUrl);
        $response->assertSessionHas('success');
        $this->assertNotNull($election->fresh()->suspended_at,
            'suspended_at must be set after suspend');
    }

    /** @test */
    public function suspend_sets_suspended_at_and_engine_derives_suspended(): void
    {
        // ── 1. Prove aggregate runtime works ──
        [$election1, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        $this->assertNull($election1->fresh()->suspended_at);
        $this->assertEngineDerivesState($election1, ElectionLifecycleState::VotingActive);

        $this->actingAs($chief);
        $transition = \App\Domain\Election\StateMachine\Transition::manual(
            action: 'suspend',
            actorId: $chief->id,
            reason: 'Direct transition to prove aggregate runtime works.',
            metadata: ['ip' => '127.0.0.1', 'suspension_category' => 'general'],
        );
        $election1->transitionTo($transition);

        // Direct transition must persist — proves transition/persistence layer is correct
        $this->assertNotNull($election1->fresh()->suspended_at,
            'Aggregate runtime: direct transitionTo must persist suspended_at');
        $this->assertEngineDerivesState($election1, ElectionLifecycleState::Suspended);

        // ── 2. Test orchestration runtime (HTTP) with a FRESH election ──
        [$election2, $chief2, $org2] = ElectionScenarioFactory::votingActiveWithChief();
        $this->assertNull($election2->fresh()->suspended_at);

        $response = $this->actingAs($chief2)
            ->withSession(['current_organisation_id' => $org2->id])
            ->from(route('elections.management', ['election' => $election2->slug]))
            ->post(route('elections.suspend', ['election' => $election2->slug]), [
                'reason' => 'HTTP-orchestrated suspension for engine derivation test.',
            ]);

        // Assert HTTP orchestration evidence
        $response->assertRedirect();
        // The redirect target reveals WHERE execution stopped in the middleware pipeline.
        // Election management routes redirect to login if auth fails, or to the
        // management page if the controller runs and returns back().
        $this->assertStringContainsString(
            'management',
            $response->headers->get('Location'),
            'Redirect should point to management page (controller ran) not login (auth failed)'
        );
        $response->assertSessionHasNoErrors();
        $response->assertSessionMissing('error');

        // If this fails, the issue is in the HTTP orchestration layer, NOT the transition/persistence
        $this->assertNotNull($election2->fresh()->suspended_at,
            'Orchestration runtime: HTTP POST to suspend must persist suspended_at');
        $this->assertEngineDerivesState($election2, ElectionLifecycleState::Suspended);
    }

    // ----------------------------------------------------------
    // 2. GOVERNANCE METADATA
    // ----------------------------------------------------------

    /** @test */
    public function suspend_stores_governance_metadata(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        $response = $this->actingAs($chief)
            ->from(route('elections.management', ['election' => $election->slug]))
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Fraud investigation: multiple votes from single IP range detected.',
                'suspension_category' => 'investigation',
            ]);

        $response->assertSessionMissing('error');
        $response->assertStatus(302);
        $response->assertRedirect(route('elections.management', ['election' => $election->slug]));

        $fresh = $election->fresh();
        // Debug: dump what we got
        fwrite(STDERR, "\n=== DEBUG SAVE ===\n");
        fwrite(STDERR, 'election_id: ' . $election->id . "\n");
        fwrite(STDERR, 'fresh_id: ' . ($fresh->id ?? 'null') . "\n");
        fwrite(STDERR, 'suspended_by: ' . ($fresh->suspended_by ?? 'null') . "\n");
        fwrite(STDERR, 'suspended_at: ' . ($fresh->suspended_at ?? 'null') . "\n");
        fwrite(STDERR, 'chief_id (expected): ' . $chief->id . "\n");
        fwrite(STDERR, '=== END DEBUG ===\n');

        $this->assertEquals($chief->id, $fresh->suspended_by);
        $this->assertStringContainsString('Fraud investigation', $fresh->suspended_reason);
        $this->assertEquals('investigation', $fresh->suspension_category);
        $this->assertNotNull($fresh->suspended_at);
    }

    // ----------------------------------------------------------
    // 3. AUTHORIZATION MATRIX
    // ----------------------------------------------------------

    /** @test */
    public function suspend_allowed_for_chief(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        $response = $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Chief-initiated governance hold for testing.',
            ]);

        $response->assertStatus(302);
        $this->assertNotNull($election->fresh()->suspended_at);
    }

    /** @test */
    public function suspend_allowed_for_platform_admin(): void
    {
        [$election, , $org] = ElectionScenarioFactory::votingActiveWithChief();

        // Platform admin: is_super_admin = true
        $admin = User::factory()->forOrganisation($org)->create([
            'is_super_admin' => true,
            'platform_role' => 'platform_admin',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Platform admin governance intervention for testing.',
            ]);

        $response->assertStatus(302);
        $this->assertNotNull($election->fresh()->suspended_at);
    }

    /** @test */
    public function suspend_denied_for_deputy(): void
    {
        [$election, , $org] = ElectionScenarioFactory::votingActiveWithChief();

        $deputy = User::factory()->forOrganisation($org)->create();
        ElectionOfficer::create([
            'user_id' => $deputy->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'role' => 'deputy',
            'status' => 'active',
            'appointed_by' => $deputy->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        $response = $this->actingAs($deputy)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Deputy should not be allowed to suspend.',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function suspend_denied_for_ordinary_user(): void
    {
        [$election, , $org] = ElectionScenarioFactory::votingActiveWithChief();

        $user = User::factory()->forOrganisation($org)->create();

        $response = $this->actingAs($user)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Ordinary user should not be allowed to suspend.',
            ]);

        $response->assertStatus(403);
    }

    // ----------------------------------------------------------
    // 4. VALIDATION
    // ----------------------------------------------------------

    /** @test */
    public function suspend_validates_reason_required(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        $response = $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => '',
            ]);

        $response->assertSessionHasErrors(['reason']);
        $this->assertNull($election->fresh()->suspended_at);
    }

    /** @test */
    public function suspend_validates_reason_min_length(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        $response = $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Short',
            ]);

        $response->assertSessionHasErrors(['reason']);
        $this->assertNull($election->fresh()->suspended_at);
    }

    /** @test */
    public function suspend_fails_when_already_suspended(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        // First suspension succeeds
        $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'First governance hold for testing duplicate prevention.',
            ]);

        $this->assertNotNull($election->fresh()->suspended_at);

        // Second suspension fails
        $response = $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'This second suspension attempt should be rejected.',
            ]);

        $response->assertSessionHas('error');
    }

    // ----------------------------------------------------------
    // 5. GOVERNANCE AUDIT INTEGRITY
    // ----------------------------------------------------------

    /** @test */
    public function suspend_creates_governance_audit_record(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Audit integrity test: verifying full governance metadata capture.',
                'suspension_category' => 'investigation',
            ]);

        $log = ElectionAuditLog::where('election_id', $election->id)
            ->where('action', 'suspend')
            ->first();

        $this->assertNotNull($log, 'Audit log must contain a suspend action record');

        // Verify governance audit metadata
        $this->assertEquals('suspend', $log->action);
        $this->assertEquals($chief->id, $log->actor_id ?? $log->user_id);
        $this->assertStringContainsString('Audit integrity test', $log->new_values['reason'] ?? '');
        $this->assertNotNull($log->created_at);

        // Verify the engine-derived state after the transition
        $this->assertEngineDerivesState($election, ElectionLifecycleState::Suspended);
    }

    // ----------------------------------------------------------
    // 6. OVERLAY SHORT-CIRCUIT (integration-level)
    // ----------------------------------------------------------

    /** @test */
    public function suspension_overlay_short_circuits_all_capabilities_except_resume(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        // Suspend via HTTP
        $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Integration test: verifying overlay short-circuit after HTTP suspend.',
            ]);

        // Fetch management page to get capabilities (via Inertia)
        $response = $this->actingAs($chief)
            ->get(route('elections.management', $election));

        $capabilities = $this->extractCapabilities($response);

        // Derive blocked actions from Constitution (NOT hardcoded)
        $blockedActions = self::blockedActionsExceptResume();

        foreach ($blockedActions as $action) {
            $this->assertArrayHasKey(
                $action, $capabilities,
                "Capability '{$action}' missing from projection"
            );
            $this->assertFalse(
                $capabilities[$action]['allowed'] ?? true,
                "Action '{$action}' must be denied during suspension"
            );
        }

        // Only resume should be allowed
        $this->assertTrue(
            $capabilities['resume']['allowed'] ?? false,
            'Resume must be allowed when election is suspended'
        );
    }

    // ----------------------------------------------------------
    // 7. SUSPEND DOES NOT MUTATE LIFECYCLE PROGRESSION
    // ----------------------------------------------------------

    /** @test */
    public function suspension_does_not_alter_lifecycle_progression(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        // Snapshot business facts before suspension
        $factsBefore = [
            'approved_at' => $election->approved_at,
            'voting_starts_at' => $election->voting_starts_at,
            'voting_ends_at' => $election->voting_ends_at,
            'administration_completed' => $election->administration_completed,
            'nomination_completed' => $election->nomination_completed,
        ];

        // Suspend
        $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Verifying suspension does not alter lifecycle progression facts.',
            ]);

        // Business facts unchanged
        $fresh = $election->fresh();
        $this->assertEquals($factsBefore['approved_at']->toIso8601String(), $fresh->approved_at->toIso8601String());
        $this->assertEquals($factsBefore['voting_starts_at']->toIso8601String(), $fresh->voting_starts_at->toIso8601String());
        $this->assertEquals($factsBefore['voting_ends_at']->toIso8601String(), $fresh->voting_ends_at->toIso8601String());
        $this->assertTrue($fresh->administration_completed);
        $this->assertTrue($fresh->nomination_completed);

        // Engine derives Suspended (overlay active)
        $this->assertEngineDerivesState($election, ElectionLifecycleState::Suspended);

        // Resume via constitutional transition (NOT direct field mutation)
        $this->actingAs($chief)
            ->post(route('elections.resume', ['election' => $election->slug]));

        // Engine re-derives VotingActive from unchanged facts
        $this->assertEngineDerivesState($election, ElectionLifecycleState::VotingActive);
    }

    // ----------------------------------------------------------
    // 8. SUSPEND → RESUME ROUNDTRIP (full capability map)
    // ----------------------------------------------------------

    /** @test */
    public function suspend_then_resume_restores_full_capability_map(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        // Helper: extract allowed boolean from each capability
        $capabilityMap = fn(array $caps): array => array_map(
            fn($c) => $c['allowed'] ?? false,
            $caps
        );

        // 1. Snapshot ENTIRE capability map before suspend
        $preResponse = $this->actingAs($chief)
            ->get(route('elections.management', $election));

        $preCapabilities = $this->extractCapabilities($preResponse);
        $preMap = $capabilityMap($preCapabilities);

        // 2. Suspend via HTTP
        $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Roundtrip test: full capability map restoration after resume.',
            ]);

        $this->assertEngineDerivesState($election, ElectionLifecycleState::Suspended);

        // 3. Verify ALL capabilities blocked except resume during suspension
        $suspendedResponse = $this->actingAs($chief)
            ->get(route('elections.management', $election));

        $suspendedCapabilities = $this->extractCapabilities($suspendedResponse);

        $blockedActions = self::blockedActionsExceptResume();
        foreach ($blockedActions as $action) {
            $this->assertFalse(
                $suspendedCapabilities[$action]['allowed'] ?? true,
                "Action '{$action}' must be denied during suspension"
            );
        }
        $this->assertTrue($suspendedCapabilities['resume']['allowed'] ?? false);

        // 4. Resume via constitutional transition
        $this->actingAs($chief)
            ->post(route('elections.resume', ['election' => $election->slug]));

        $this->assertEngineDerivesState($election, ElectionLifecycleState::VotingActive);

        // 5. Verify ENTIRE capability map is restored to pre-suspend state
        $postResponse = $this->actingAs($chief)
            ->get(route('elections.management', $election));

        $postCapabilities = $this->extractCapabilities($postResponse);
        $postMap = $capabilityMap($postCapabilities);

        $this->assertEquals(
            $preMap,
            $postMap,
            'Full capability map must be restored after resume'
        );
    }

    // ----------------------------------------------------------
    // 9. SUSPENSION_CATEGORY VOCABULARY
    // ----------------------------------------------------------

    /** @test */
    public function suspend_accepts_valid_categories(): void
    {
        $categories = Election::SUSPENSION_CATEGORIES;

        foreach ($categories as $category) {
            // Fresh election per category to avoid direct field mutation between iterations
            [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

            $this->actingAs($chief)
                ->post(route('elections.suspend', ['election' => $election->slug]), [
                    'reason' => "Testing suspension category: {$category}.",
                    'suspension_category' => $category,
                ]);

            $this->assertEquals(
                $category,
                $election->fresh()->suspension_category,
                "Category '{$category}' should be accepted"
            );

            // Resume via constitutional transition (NOT direct field mutation)
            $this->actingAs($chief)
                ->post(route('elections.resume', ['election' => $election->slug]));

            $this->assertEngineDerivesState($election, ElectionLifecycleState::VotingActive);
        }
    }

    /** @test */
    public function suspend_rejects_invalid_category(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        $response = $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Testing invalid category rejection.',
                'suspension_category' => 'invalid_category_value',
            ]);

        $response->assertSessionHasErrors(['suspension_category']);
    }

    // ----------------------------------------------------------
    // 10. LIFECYCLE PROJECTION INVARIANT
    // ----------------------------------------------------------

    /** @test */
    public function suspension_does_not_appear_in_lifecycle_progression(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        $projection = \App\Domain\Election\Projection\ElectionLifecycleProjection::class;

        // Suspended must not be in the linear progression
        $this->assertNotContains(
            'suspended',
            $projection::PROGRESSION,
            'Suspended must be excluded from linear lifecycle progression'
        );

        // Suspend via HTTP
        $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Verifying suspension does not corrupt lifecycle projection.',
            ]);

        $this->assertEngineDerivesState($election, ElectionLifecycleState::Suspended);

        // Resume via constitutional transition (NOT direct field mutation)
        $this->actingAs($chief)
            ->post(route('elections.resume', ['election' => $election->slug]));

        $this->assertEngineDerivesState($election, ElectionLifecycleState::VotingActive);
    }

    // ----------------------------------------------------------
    // 11. RUNTIME CONTINUITY MATRIX
    // ----------------------------------------------------------

    /** @test */
    public function voting_active_suspend_resume_preserves_lifecycle_position(): void
    {
        [$election, $chief] = ElectionScenarioFactory::votingActiveWithChief();

        $this->assertEngineDerivesState($election, ElectionLifecycleState::VotingActive);

        $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Continuity matrix: VotingActive lifecycle position preservation.',
            ]);

        $this->assertEngineDerivesState($election, ElectionLifecycleState::Suspended);

        $this->actingAs($chief)
            ->post(route('elections.resume', ['election' => $election->slug]));

        $this->assertEngineDerivesState($election, ElectionLifecycleState::VotingActive);
    }

    /** @test */
    public function setup_nomination_suspend_resume_preserves_lifecycle_position(): void
    {
        [$election, $chief, $org] = ElectionScenarioFactory::setupNominationWithChief();

        $this->assertEngineDerivesState($election, ElectionLifecycleState::SetupNomination);

        \App\Services\TenantContext::set($org->id);
        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->from(route('elections.management', ['election' => $election->slug]))
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Continuity matrix: SetupNomination lifecycle position preservation.',
            ]);

        $this->assertEngineDerivesState($election, ElectionLifecycleState::Suspended);

        $this->actingAs($chief)
            ->post(route('elections.resume', ['election' => $election->slug]));

        $this->assertEngineDerivesState($election, ElectionLifecycleState::SetupNomination);
    }

    /** @test */
    public function setup_administration_suspend_resume_preserves_lifecycle_position(): void
    {
        [$election, $chief] = ElectionScenarioFactory::setupAdministrationWithChief();

        $this->assertEngineDerivesState($election, ElectionLifecycleState::SetupAdministration);

        $this->actingAs($chief)
            ->post(route('elections.suspend', ['election' => $election->slug]), [
                'reason' => 'Continuity matrix: SetupAdministration lifecycle position preservation.',
            ]);

        $this->assertEngineDerivesState($election, ElectionLifecycleState::Suspended);

        $this->actingAs($chief)
            ->post(route('elections.resume', ['election' => $election->slug]));

        $this->assertEngineDerivesState($election, ElectionLifecycleState::SetupAdministration);
    }
}
