<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\ElectionMembership;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\User;
use App\Models\Organisation;
use App\Models\ElectionStateTransition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StateMachineTransitionAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $chief;
    protected Organisation $organisation;
    protected Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->chief = User::factory()->create();

        $this->election = Election::factory()->create([
            'organisation_id' => $this->organisation->id,
            'state'           => 'draft',
        ]);

        ElectionOfficer::create([
            'user_id'        => $this->chief->id,
            'election_id'    => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'role'            => 'chief',
            'status'          => 'active',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function complete_administration_creates_audit_record_via_transitionTo(): void
    {
        $voter = User::factory()->forOrganisation($this->organisation)->create();
        $committee = User::factory()->forOrganisation($this->organisation)->create();

        // Real setup_administration derivation facts (approved + setup started), replacing the stale raw
        // 'state' string — the transition goes through the constitutional Guard, which derives the state
        // (same correction as complete_nomination_does_not_change_state_to_voting below).
        $this->election->update([
            'approved_at'      => now()->subDay(),
            'setup_started_at' => now()->subHours(2),
            'state'            => 'setup_administration',   // persisted column synced to the derived state
            'posts_count'     => 1,
            'voters_count'    => 1,
        ]);
        // As in a real request: the has_voters precondition reads tenant-scoped memberships.
        \App\Services\TenantContext::set($this->organisation->id);

        Post::factory()->create(['election_id' => $this->election->id]);

        ElectionMembership::create([
            'id'              => \Illuminate\Support\Str::uuid(),
            'organisation_id' => $this->organisation->id,
            'election_id'     => $this->election->id,
            'user_id'         => $voter->id,
            'role'            => 'voter',
            'status'          => 'active',
            'metadata'        => [],
            'has_voted'       => false,
            'suspension_status' => 'none',
        ]);

        ElectionMembership::create([
            'id'              => \Illuminate\Support\Str::uuid(),
            'organisation_id' => $this->organisation->id,
            'election_id'     => $this->election->id,
            'user_id'         => $committee->id,
            'role'            => 'committee',
            'status'          => 'active',
            'metadata'        => [],
            'has_voted'       => false,
            'suspension_status' => 'none',
        ]);

        // The ConstitutionalTransitionGuard authorises the AUTHENTICATED principal (Auth::user()), as in the
        // complete_nomination tests below.
        $this->actingAs($this->chief);
        $this->election->completeAdministration('Starting nomination phase', $this->chief->id);

        // 'setup_administration' / 'setup_nomination' are the current constitutional names for the phases this
        // test calls "administration" / "nomination" (ElectionConstitution: complete_administration) — the
        // assertion's substance (an audit record of that transition) is unchanged.
        $transitions = ElectionStateTransition::where('election_id', $this->election->id)
            ->where('to_state', 'setup_nomination')
            ->get();

        $this->assertNotEmpty($transitions, 'No ElectionStateTransition record found for complete_administration');
        $this->assertEquals('setup_administration', $transitions->first()->from_state);
        $this->assertEquals('setup_nomination', $transitions->first()->to_state);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function complete_nomination_does_not_change_state_to_voting(): void
    {
        // Real setup_nomination derivation fact, replacing the stale raw
        // 'state' string — complete_nomination now goes through the
        // constitutional Guard, which derives state from
        // ElectionLifecycleEngineImpl, not from a literal state string.
        $this->election->update([
            'administration_completed' => true,
            'posts_count'     => 1,
            'voters_count'    => 1,
            'candidates_count' => 1,
        ]);
        $this->actingAs($this->chief);

        Candidacy::factory()->create([
            'post_id' => Post::factory()->create(['election_id' => $this->election->id])->id,
            'status'  => 'approved',
        ]);

        $this->election->completeNomination('Nomination complete', $this->chief->id);

        // 'setup_nomination' is the current constitutional name for the
        // same phase this test's name calls "nomination" — the assertion's
        // substance (still nomination, not voting) is unchanged.
        $this->assertEquals('setup_nomination', $this->election->fresh()->state);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function complete_nomination_sets_nomination_completed_flag_without_changing_state(): void
    {
        $this->election->update([
            'administration_completed' => true,
            'posts_count'     => 1,
            'voters_count'    => 1,
            'candidates_count' => 1,
        ]);
        $this->actingAs($this->chief);

        Candidacy::factory()->create([
            'post_id' => Post::factory()->create(['election_id' => $this->election->id])->id,
            'status'  => 'approved',
        ]);

        $this->election->completeNomination('Nomination complete', $this->chief->id);

        $fresh = $this->election->fresh();
        $this->assertTrue($fresh->nomination_completed);
        $this->assertNotNull($fresh->nomination_completed_at);
        $this->assertEquals('setup_nomination', $fresh->state);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function publish_results_controller_transitions_state_to_results_via_transitionTo(): void
    {
        $chief2 = User::factory()->create();
        ElectionOfficer::create([
            'user_id'        => $chief2->id,
            'election_id'    => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'role'            => 'chief',
            'status'          => 'active',
        ]);

        $this->election->update($this->countingFacts());
        $this->actingAs($chief2);   // the Guard authorises the authenticated principal (publish_results: chief)

        $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual(
                action: 'publish_results',
                actorId: $chief2->id,
                reason: 'Results published by election officer',
            )
        );

        // 'results_published' is the current constitutional name for the "results" state (ElectionConstitution:
        // publish_results → results_published); the substance — publishing moves the election to results — is unchanged.
        $this->assertEquals('results_published', $this->election->fresh()->state);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function publish_results_creates_audit_record(): void
    {
        $chief2 = User::factory()->create();
        ElectionOfficer::create([
            'user_id'        => $chief2->id,
            'election_id'    => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'role'            => 'chief',
            'status'          => 'active',
        ]);

        $this->election->update($this->countingFacts());
        $this->actingAs($chief2);   // the Guard authorises the authenticated principal (publish_results: chief)

        $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual(
                action: 'publish_results',
                actorId: $chief2->id,
                reason: 'Results published by election officer',
            )
        );

        // 'counting' / 'results_published' are the current constitutional names for "results_pending" /
        // "results" (ElectionConstitution: publish_results counting → results_published); substance unchanged.
        $transitions = ElectionStateTransition::where('election_id', $this->election->id)
            ->where('from_state', 'counting')
            ->where('to_state', 'results_published')
            ->get();

        $this->assertNotEmpty($transitions, 'No ElectionStateTransition record found for publish_results');
        $this->assertEquals('counting', $transitions->first()->from_state);
        $this->assertEquals('results_published', $transitions->first()->to_state);
    }

    /**
     * Real Counting derivation facts (replacing the stale raw 'results_pending' state string): setup completed,
     * voting legitimately opened, voting window ended, results not yet published (ElectionLifecycleEngineImpl).
     */
    private function countingFacts(): array
    {
        return [
            'approved_at'              => now()->subDays(5),
            'administration_completed' => true,
            'nomination_completed'     => true,
            'voting_locked'            => true,
            'voting_starts_at'         => now()->subDays(2),
            'voting_ends_at'           => now()->subHour(),
            'results_published'        => false,
            // Persisted column synced to the derived state (the ElectionScenarioFactory convention): the audit
            // record's from_state is read from the persisted column (Election::getCurrentStateAttribute).
            'state'                    => 'counting',
        ];
    }
}
