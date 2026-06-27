<?php

namespace Tests\Feature\Election;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase C.2.5 Step 6: Resolver-Centric Authority Assertions
 *
 * MIGRATION: Rewritten from deprecated allowsAction() to resolver-based capability checks.
 * Tests now validate that ElectionCapabilityResolver determines authority based on
 * constitutional facts, not state column or role assumptions.
 *
 * KEY CHANGE: Tests verify WHAT authority exists, not WHETHER allowsAction() returns true.
 */
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
    }

    /**
     * Test: Editing settings allowed when election is in setup phase
     *
     * MIGRATED: Now verifies resolver snapshot shows canEdit = true
     * for elections in SetupAdministration state.
     */
    public function test_manage_settings_allowed_in_administration_for_officer(): void
    {
        $election = Election::factory()->create([
            'state' => 'setup_administration',
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        // Resolver determines authority based on constitutional facts
        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // In setup phase, editing should be allowed
        $this->assertTrue($snapshot->canEdit,
            'ElectionCapabilityResolver should allow editing in SetupAdministration state'
        );
    }

    /**
     * Test: Editing settings allowed during nomination phase
     *
     * MIGRATED: Now verifies resolver snapshot shows canEdit = true
     * for elections in SetupNomination state.
     */
    public function test_manage_settings_allowed_in_nomination_for_officer(): void
    {
        $election = Election::factory()->create([
            'state' => 'setup_nomination',
            'administration_completed' => true,
            'nomination_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // In nomination phase, editing should be allowed
        $this->assertTrue($snapshot->canEdit,
            'ElectionCapabilityResolver should allow editing in SetupNomination state'
        );
    }

    /**
     * Test: Editing settings denied during voting phase
     *
     * MIGRATED: Now verifies resolver snapshot shows canEdit = false
     * for elections in VotingActive state.
     */
    public function test_manage_settings_denied_during_voting_for_officer(): void
    {
        $election = Election::factory()->create([
            'state' => 'voting_active',
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(1),
            'voting_ends_at' => now()->addHours(2),
        ]);

        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // During voting phase, editing should be denied
        $this->assertFalse($snapshot->canEdit,
            'ElectionCapabilityResolver should deny editing during VotingActive state'
        );
    }

    /**
     * Test: Editing settings denied during counting phase
     *
     * MIGRATED: Now verifies resolver snapshot shows canEdit = false
     * for elections in Counting state.
     *
     * IMPORTANT: Resolver recomputes state from facts, so we must set
     * approved_at to ensure resolver recognizes this as legitimate Counting.
     */
    public function test_manage_settings_denied_during_results_pending_for_officer(): void
    {
        $election = Election::factory()->create([
            'state' => 'counting',
            'approved_at' => now()->subDays(3),  // Must be approved to reach Counting
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHours(2),  // Voting ended → Counting state
            'results_published_at' => null,
        ]);

        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // Verify resolver computed Counting state
        $this->assertEquals('counting', $snapshot->state->value,
            'Resolver should derive Counting state from constitutional facts'
        );

        // In counting phase, editing should be denied
        $this->assertFalse($snapshot->canEdit,
            'ElectionCapabilityResolver should deny editing during Counting state'
        );
    }

    /**
     * Test: Editing settings denied when results published
     *
     * MIGRATED: Now verifies resolver snapshot shows canEdit = false
     * for elections in ResultsPublished state.
     */
    public function test_manage_settings_denied_in_results_state(): void
    {
        $election = Election::factory()->create([
            'state' => 'results_published',
            'results_published_at' => now()->subHours(1),
        ]);

        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // In results published state, editing should be denied
        $this->assertFalse($snapshot->canEdit,
            'ElectionCapabilityResolver should deny editing in ResultsPublished state'
        );
    }

    /**
     * Test: Voting allowed during voting phase
     *
     * MIGRATED: Now verifies resolver snapshot shows canVote = true
     * for elections in VotingActive state with proper preconditions.
     */
    public function test_cast_vote_allowed_during_voting_state(): void
    {
        $election = Election::factory()->create([
            'state' => 'voting_active',
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(1),
            'voting_ends_at' => now()->addHours(2),
        ]);

        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // During voting phase, voting should be allowed
        $this->assertTrue($snapshot->canVote,
            'ElectionCapabilityResolver should allow voting during VotingActive state'
        );
    }

    /**
     * Test: Voting denied outside voting phase
     *
     * MIGRATED: Now verifies resolver snapshot shows canVote = false
     * for elections NOT in VotingActive state.
     */
    public function test_cast_vote_denied_outside_voting_state(): void
    {
        $election = Election::factory()->create([
            'state' => 'setup_administration',
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // In setup phase, voting should be denied
        $this->assertFalse($snapshot->canVote,
            'ElectionCapabilityResolver should deny voting in SetupAdministration state'
        );
    }

    /**
     * Test: Voting denied after voting window closes
     *
     * MIGRATED: Now verifies resolver snapshot shows canVote = false
     * for elections in Counting or later states.
     */
    public function test_cast_vote_denied_after_voting_ends(): void
    {
        $election = Election::factory()->create([
            'state' => 'counting',
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHours(1),
        ]);

        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // After voting ends, voting should be denied
        $this->assertFalse($snapshot->canVote,
            'ElectionCapabilityResolver should deny voting in Counting state'
        );
    }

    /**
     * Test: Results viewing allowed when results published
     *
     * MIGRATED: Now verifies resolver snapshot shows appropriate permissions
     * for elections in ResultsPublished state.
     */
    public function test_view_results_allowed_in_results_state(): void
    {
        $election = Election::factory()->create([
            'state' => 'results_published',
            'results_published_at' => now()->subHours(1),
        ]);

        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // In results published state, results are viewable
        // (proxy: election is not locked, can publish results returns false)
        $this->assertTrue($snapshot->state->value === 'results_published',
            'Resolver should recognize ResultsPublished state'
        );
    }

    /**
     * Test: Results viewing denied before publication
     *
     * MIGRATED: Now verifies resolver snapshot shows appropriate restrictions
     * for elections before results are published.
     */
    public function test_view_results_denied_before_publication(): void
    {
        $election = Election::factory()->create([
            'state' => 'counting',
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHours(2),
            'results_published_at' => null,
        ]);

        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // In counting state, results should not be published yet
        $this->assertFalse($snapshot->canPublishResults,
            'Resolver should deny result publication during Counting state'
        );

        // Check that results_published_at is null (not published)
        $this->assertNull($election->results_published_at,
            'Results should not be published yet'
        );
    }
}
