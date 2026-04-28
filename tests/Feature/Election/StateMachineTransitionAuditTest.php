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

        $this->election->update([
            'state'           => 'administration',
            'posts_count'     => 1,
            'voters_count'    => 1,
        ]);

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

        $this->election->completeAdministration('Starting nomination phase', $this->chief->id);

        $transitions = ElectionStateTransition::where('election_id', $this->election->id)
            ->where('to_state', 'nomination')
            ->get();

        $this->assertNotEmpty($transitions, 'No ElectionStateTransition record found for complete_administration');
        $this->assertEquals('administration', $transitions->first()->from_state);
        $this->assertEquals('nomination', $transitions->first()->to_state);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function complete_nomination_does_not_change_state_to_voting(): void
    {
        $this->election->update([
            'state'           => 'nomination',
            'posts_count'     => 1,
            'voters_count'    => 1,
            'candidates_count' => 1,
        ]);

        Candidacy::factory()->create([
            'post_id' => Post::factory()->create(['election_id' => $this->election->id])->id,
            'status'  => 'approved',
        ]);

        $this->election->completeNomination('Nomination complete', $this->chief->id);

        $this->assertEquals('nomination', $this->election->fresh()->state);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function complete_nomination_sets_nomination_completed_flag_without_changing_state(): void
    {
        $this->election->update([
            'state'           => 'nomination',
            'posts_count'     => 1,
            'voters_count'    => 1,
            'candidates_count' => 1,
        ]);

        Candidacy::factory()->create([
            'post_id' => Post::factory()->create(['election_id' => $this->election->id])->id,
            'status'  => 'approved',
        ]);

        $this->election->completeNomination('Nomination complete', $this->chief->id);

        $fresh = $this->election->fresh();
        $this->assertTrue($fresh->nomination_completed);
        $this->assertNotNull($fresh->nomination_completed_at);
        $this->assertEquals('nomination', $fresh->state);
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

        $this->election->update([
            'state'              => 'results_pending',
            'results_published'  => false,
        ]);

        $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual(
                action: 'publish_results',
                actorId: $chief2->id,
                reason: 'Results published by election officer',
            )
        );

        $this->assertEquals('results', $this->election->fresh()->state);
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

        $this->election->update([
            'state'              => 'results_pending',
            'results_published'  => false,
        ]);

        $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual(
                action: 'publish_results',
                actorId: $chief2->id,
                reason: 'Results published by election officer',
            )
        );

        $transitions = ElectionStateTransition::where('election_id', $this->election->id)
            ->where('from_state', 'results_pending')
            ->where('to_state', 'results')
            ->get();

        $this->assertNotEmpty($transitions, 'No ElectionStateTransition record found for publish_results');
        $this->assertEquals('results_pending', $transitions->first()->from_state);
        $this->assertEquals('results', $transitions->first()->to_state);
    }
}
