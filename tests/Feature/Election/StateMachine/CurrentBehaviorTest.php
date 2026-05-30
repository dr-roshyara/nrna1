<?php

namespace Tests\Feature\Election\StateMachine;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\StateMachine\Transition;
use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Election State Machine Behavior Tests
 *
 * Tests the CONSTITUTIONAL state machine where state is DERIVED from facts,
 * not directly stored. These tests verify that state derivation rules work correctly.
 *
 * Key Principle: Set constitutional facts → verify state derives correctly
 * Never test by directly setting the state column.
 *
 * The 10 SSOT States:
 * draft → submitted_for_approval → approved → setup → ready_for_voting
 * → voting_active → counting → results_published (terminal)
 * Also: rejected (terminal)
 */
class CurrentBehaviorTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $chief;
    private User $deputy;
    private User $platform_admin;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->chief = User::factory()->create(['email_verified_at' => now()]);
        $this->deputy = User::factory()->create(['email_verified_at' => now()]);
        $this->platform_admin = User::factory()->create(['email_verified_at' => now()]);

        // Setup chief in organisation
        UserOrganisationRole::updateOrCreate(
            ['user_id' => $this->chief->id, 'organisation_id' => $this->org->id],
            ['role' => 'chief']
        );

        // Setup deputy in organisation
        UserOrganisationRole::updateOrCreate(
            ['user_id' => $this->deputy->id, 'organisation_id' => $this->org->id],
            ['role' => 'deputy']
        );

        // Setup platform admin
        UserOrganisationRole::updateOrCreate(
            ['user_id' => $this->platform_admin->id, 'organisation_id' => $this->org->id],
            ['role' => 'admin']
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // State Derivation Tests — Verify DRAFT state
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Test P0.1: DRAFT state derivation
     *
     * Verifies: New election with no facts set derives to DRAFT
     * Facts: submitted_for_approval_at = NULL, approved_at = NULL, etc.
     */
    public function test_new_election_derives_to_draft_state(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create();

        $state = ElectionLifecycle::of($this->election)->state();

        $this->assertEquals('draft', $state->value);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // State Derivation Tests — Verify SUBMITTED_FOR_APPROVAL state
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Test P0.2: SUBMITTED_FOR_APPROVAL state derivation
     *
     * Verifies: Election with submitted_for_approval_at set (no approved_at) derives to SUBMITTED_FOR_APPROVAL
     * Facts: submitted_for_approval_at = NOW, approved_at = NULL, rejected_at = NULL
     */
    public function test_election_with_submitted_for_approval_at_derives_correctly(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'submitted_for_approval_at' => now(),
                'approved_at' => null,
                'rejected_at' => null,
            ]);

        $state = ElectionLifecycle::of($this->election)->state();

        $this->assertEquals('submitted_for_approval', $state->value);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // State Derivation Tests — Verify APPROVED state
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Test P0.3: APPROVED state derivation
     *
     * Verifies: Election with approved_at set (and administration not completed) derives to APPROVED
     * Facts: approved_at = NOW, administration_completed = false
     */
    public function test_election_with_approved_at_derives_to_approved(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now(),
                'administration_completed' => false,
                'setup_started_at' => null,
            ]);

        $state = ElectionLifecycle::of($this->election)->state();

        $this->assertEquals('approved', $state->value);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // State Derivation Tests — Verify SETUP state
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Test P0.4: SETUP state derivation
     *
     * Verifies: Election with setup_started_at set AND administration_completed = false
     * derives to SETUP
     * Facts: setup_started_at = SET, administration_completed = false
     */
    public function test_election_with_setup_started_derives_to_setup(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now()->subHours(2),
                'setup_started_at' => now()->subHour(),
                'administration_completed' => false,
                'nomination_completed' => false,
            ]);

        $state = ElectionLifecycle::of($this->election)->state();

        $this->assertEquals('setup', $state->value);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // State Derivation Tests — Verify READY_FOR_VOTING state
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Test P0.5: READY_FOR_VOTING state derivation
     *
     * Verifies: Election with setup complete but voting not yet started
     * derives to READY_FOR_VOTING
     * Facts: administration_completed = true, nomination_completed = true,
     *        voting_starts_at = FUTURE
     */
    public function test_election_ready_for_voting_derives_correctly(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now()->subHours(5),
                'setup_started_at' => now()->subHours(4),
                'administration_completed' => true,
                'administration_completed_at' => now()->subHours(3),
                'nomination_completed' => true,
                'nomination_completed_at' => now()->subHours(2),
                'voting_starts_at' => now()->addHours(2),  // Future
                'voting_ends_at' => now()->addHours(6),    // Future
            ]);

        $state = ElectionLifecycle::of($this->election)->state();

        $this->assertEquals('ready_for_voting', $state->value);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // State Derivation Tests — Verify VOTING_ACTIVE state
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Test P0.6: VOTING_ACTIVE state derivation
     *
     * Verifies: Election with voting window open (now between start and end)
     * derives to VOTING_ACTIVE
     * Facts: voting_starts_at = PAST, voting_ends_at = FUTURE
     */
    public function test_election_with_voting_window_open_derives_to_voting_active(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now()->subHours(8),
                'setup_started_at' => now()->subHours(7),
                'administration_completed' => true,
                'nomination_completed' => true,
                'voting_locked' => true,
                'voting_locked_at' => now()->subHour(),
                'voting_starts_at' => now()->subHour(),   // Past
                'voting_ends_at' => now()->addHours(3),   // Future
            ]);

        $state = ElectionLifecycle::of($this->election)->state();

        $this->assertEquals('voting_active', $state->value);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // State Derivation Tests — Verify COUNTING state
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Test P0.7: COUNTING state derivation
     *
     * Verifies: Election with voting closed but results not published
     * derives to COUNTING
     * Facts: voting_ends_at = PAST, results_published_at = NULL
     */
    public function test_election_with_voting_ended_derives_to_counting(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now()->subHours(12),
                'setup_started_at' => now()->subHours(11),
                'administration_completed' => true,
                'nomination_completed' => true,
                'voting_locked' => true,
                'voting_locked_at' => now()->subHours(6),
                'voting_starts_at' => now()->subHours(5),
                'voting_ends_at' => now()->subHour(),     // Past
                'results_published_at' => null,           // Not published
            ]);

        $state = ElectionLifecycle::of($this->election)->state();

        $this->assertEquals('counting', $state->value);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // State Derivation Tests — Verify RESULTS_PUBLISHED state
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Test P0.8: RESULTS_PUBLISHED state derivation
     *
     * Verifies: Election with results published derives to RESULTS_PUBLISHED (terminal)
     * Facts: results_published_at = SET
     */
    public function test_election_with_published_results_derives_to_results_published(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now()->subHours(12),
                'setup_started_at' => now()->subHours(11),
                'administration_completed' => true,
                'nomination_completed' => true,
                'voting_locked' => true,
                'voting_locked_at' => now()->subHours(6),
                'voting_starts_at' => now()->subHours(8),
                'voting_ends_at' => now()->subHour(),
                'results_published_at' => now(),           // Published
            ]);

        $state = ElectionLifecycle::of($this->election)->state();

        $this->assertEquals('results_published', $state->value);
    }

    /**
     * Test P0.9: REJECTED state derivation (terminal)
     *
     * Verifies: Election with rejected_at set derives to REJECTED
     * Facts: rejected_at = SET
     */
    public function test_election_with_rejected_at_derives_to_rejected(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'submitted_for_approval_at' => now()->subDay(),
                'rejected_at' => now(),
                'rejection_reason' => 'Insufficient documentation',
            ]);

        $state = ElectionLifecycle::of($this->election)->state();

        $this->assertEquals('rejected', $state->value);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Transition Tests — Verify actual transitions work
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Test P1.1: submit_for_approval transition
     *
     * Verifies: DRAFT → SUBMITTED_FOR_APPROVAL via submit_for_approval action
     */
    public function test_submit_for_approval_transition(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create();

        // Make chief an officer for this election
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
            'user_id' => $this->chief->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $this->chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        // Authenticate as chief before transition
        $this->actingAs($this->chief);

        $this->election->transitionTo(Transition::manual(
            action: 'submit_for_approval',
            actorId: $this->chief->id,
            reason: 'Submitting for admin review'
        ));

        $state = ElectionLifecycle::of($this->election->fresh())->state();
        $this->assertEquals('submitted_for_approval', $state->value);
    }

    /**
     * Test P1.2: approve transition
     *
     * Verifies: SUBMITTED_FOR_APPROVAL → APPROVED via approve action
     * (Platform admin only)
     */
    public function test_approve_transition(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create(['submitted_for_approval_at' => now()]);

        // Authenticate as platform admin
        $this->actingAs($this->platform_admin);

        $this->election->transitionTo(Transition::manual(
            action: 'approve',
            actorId: $this->platform_admin->id,
            reason: 'Approved by admin'
        ));

        $state = ElectionLifecycle::of($this->election->fresh())->state();
        $this->assertEquals('approved', $state->value);
    }

    /**
     * Test P1.3: begin_setup transition
     *
     * Verifies: APPROVED → SETUP via begin_setup action
     */
    public function test_begin_setup_transition(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now(),
                'administration_completed' => false,
                'setup_started_at' => null,
            ]);

        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
            'user_id' => $this->chief->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $this->chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        $this->actingAs($this->chief);

        $this->election->transitionTo(Transition::manual(
            action: 'begin_setup',
            actorId: $this->chief->id,
            reason: 'Beginning setup phase'
        ));

        $state = ElectionLifecycle::of($this->election->fresh())->state();
        $this->assertEquals('setup', $state->value);
    }

    /**
     * Test P1.4: complete_administration transition
     *
     * Verifies: SETUP → READY_FOR_VOTING via complete_administration action
     */
    public function test_complete_administration_transition(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now()->subHours(2),
                'setup_started_at' => now()->subHour(),
                'administration_completed' => false,
                'nomination_completed' => false,
                'voting_starts_at' => now()->addHours(4),
                'voting_ends_at' => now()->addHours(8),
            ]);

        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
            'user_id' => $this->chief->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $this->chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        $this->actingAs($this->chief);

        $this->election->transitionTo(Transition::manual(
            action: 'complete_administration',
            actorId: $this->chief->id,
            reason: 'Administration phase complete'
        ));

        $state = ElectionLifecycle::of($this->election->fresh())->state();
        $this->assertEquals('ready_for_voting', $state->value);
    }

    /**
     * Test P1.5: open_voting transition
     *
     * Verifies: READY_FOR_VOTING → VOTING_ACTIVE via open_voting action
     */
    public function test_open_voting_transition(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now()->subHours(4),
                'setup_started_at' => now()->subHours(3),
                'administration_completed' => true,
                'administration_completed_at' => now()->subHours(3),
                'nomination_completed' => true,
                'nomination_completed_at' => now()->subHours(2),
                'voting_starts_at' => now()->addHours(1),
                'voting_ends_at' => now()->addHours(5),
            ]);

        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
            'user_id' => $this->chief->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $this->chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        $this->actingAs($this->chief);

        $this->election->transitionTo(Transition::manual(
            action: 'open_voting',
            actorId: $this->chief->id,
            reason: 'Opening voting'
        ));

        $state = ElectionLifecycle::of($this->election->fresh())->state();
        $this->assertEquals('voting_active', $state->value);
    }

    /**
     * Test P1.6: close_voting transition
     *
     * Verifies: VOTING_ACTIVE → COUNTING via close_voting action
     */
    public function test_close_voting_transition(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now()->subHours(6),
                'setup_started_at' => now()->subHours(5),
                'administration_completed' => true,
                'nomination_completed' => true,
                'voting_locked' => true,
                'voting_locked_at' => now()->subHour(),
                'voting_starts_at' => now()->subHour(),
                'voting_ends_at' => now()->addHours(3),
            ]);

        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
            'user_id' => $this->chief->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $this->chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        $this->actingAs($this->chief);

        $this->election->transitionTo(Transition::manual(
            action: 'close_voting',
            actorId: $this->chief->id,
            reason: 'Closing voting early'
        ));

        $state = ElectionLifecycle::of($this->election->fresh())->state();
        $this->assertEquals('counting', $state->value);
    }

    /**
     * Test P1.7: publish_results transition
     *
     * Verifies: COUNTING → RESULTS_PUBLISHED via publish_results action
     */
    public function test_publish_results_transition(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now()->subHours(10),
                'setup_started_at' => now()->subHours(9),
                'administration_completed' => true,
                'nomination_completed' => true,
                'voting_locked' => true,
                'voting_locked_at' => now()->subHours(4),
                'voting_starts_at' => now()->subHours(5),
                'voting_ends_at' => now()->subHour(),
                'results_published_at' => null,
            ]);

        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
            'user_id' => $this->chief->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $this->chief->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        $this->actingAs($this->chief);

        $this->election->transitionTo(Transition::manual(
            action: 'publish_results',
            actorId: $this->chief->id,
            reason: 'Publishing results'
        ));

        $state = ElectionLifecycle::of($this->election->fresh())->state();
        $this->assertEquals('results_published', $state->value);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Capability Tests — Verify lifecycle snapshot methods
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Test P2.1: canVote capability
     *
     * Verifies: Election in VOTING_ACTIVE state can accept votes
     */
    public function test_voting_active_can_vote(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'voting_locked' => true,
                'voting_locked_at' => now()->subHour(),
                'voting_starts_at' => now()->subHour(),
                'voting_ends_at' => now()->addHours(2),
            ]);

        $lifecycle = ElectionLifecycle::of($this->election);

        $this->assertTrue($lifecycle->canVote());
    }

    /**
     * Test P2.2: cannot vote before voting window opens
     *
     * Verifies: Election with voting_starts_at in future cannot accept votes
     */
    public function test_ready_for_voting_cannot_vote(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'administration_completed' => true,
                'nomination_completed' => true,
                'voting_starts_at' => now()->addHour(),
                'voting_ends_at' => now()->addDay(),
            ]);

        $lifecycle = ElectionLifecycle::of($this->election);

        $this->assertFalse($lifecycle->canVote());
    }

    /**
     * Test P2.3: canEditTimeline capability
     *
     * Verifies: Election in SETUP state can edit timeline
     */
    public function test_setup_can_edit_timeline(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'approved_at' => now(),
                'setup_started_at' => now(),
                'administration_completed' => false,
            ]);

        $lifecycle = ElectionLifecycle::of($this->election);

        $this->assertTrue($lifecycle->canEditTimeline());
    }

    /**
     * Test P2.4: allowedActions returns correct transitions
     *
     * Verifies: DRAFT state shows correct allowed actions
     */
    public function test_draft_allowed_actions(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create();

        $lifecycle = ElectionLifecycle::of($this->election);
        $actions = $lifecycle->allowedActions();

        $this->assertContains('submit_for_approval', $actions);
    }

    /**
     * Test P2.5: allowedActions for submitted_for_approval
     *
     * Verifies: SUBMITTED_FOR_APPROVAL shows admin approval actions
     */
    public function test_submitted_for_approval_allowed_actions(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create(['submitted_for_approval_at' => now()]);

        $lifecycle = ElectionLifecycle::of($this->election);
        $actions = $lifecycle->allowedActions();

        $this->assertContains('approve', $actions);
        $this->assertContains('reject', $actions);
    }

    /**
     * Test P2.6: allowedActions for voting_active
     *
     * Verifies: VOTING_ACTIVE shows close_voting action
     */
    public function test_voting_active_allowed_actions(): void
    {
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'voting_locked' => true,
                'voting_locked_at' => now()->subHour(),
                'voting_starts_at' => now()->subHour(),
                'voting_ends_at' => now()->addHours(2),
            ]);

        $lifecycle = ElectionLifecycle::of($this->election);
        $actions = $lifecycle->allowedActions();

        $this->assertContains('close_voting', $actions);
    }
}
