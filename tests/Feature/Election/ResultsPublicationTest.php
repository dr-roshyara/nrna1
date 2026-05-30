<?php

namespace Tests\Feature\Election;

use App\Contexts\Elections\Domain\Events\ResultsPublishedEvent;
use App\Contexts\Elections\Domain\Events\ResultsUnpublishedEvent;
use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ResultsPublicationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $chief;
    private User $deputy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();

        $this->chief = User::factory()->create(['name' => 'Chief Officer']);
        $this->deputy = User::factory()->create(['name' => 'Deputy Officer']);

        // Create organisation membership for both officers (required by middleware)
        UserOrganisationRole::create([
            'user_id' => $this->chief->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'admin',
        ]);

        UserOrganisationRole::create([
            'user_id' => $this->deputy->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'admin',
        ]);

        // Create election in counting state through legitimate constitutional transitions
        // This respects the Election aggregate's state progression rules
        $this->election = Election::factory()
            ->for($this->organisation)
            ->inCountingState()
            ->create();

        // Get the chief officer created by the fixture
        // (the fixture sets up the chief during its transition sequence)
        $this->chief = \App\Models\User::where('name', 'Chief Officer')
            ->whereHas('electionOfficers', function ($query) {
                $query->where('election_id', $this->election->id)
                    ->where('role', 'chief');
            })
            ->first();

        // Create election officers
        ElectionOfficer::create([
            'election_id' => $this->election->id,
            'user_id' => $this->chief->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'chief',
            'status' => 'active',
        ]);

        ElectionOfficer::create([
            'election_id' => $this->election->id,
            'user_id' => $this->deputy->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'deputy',
            'status' => 'active',
        ]);
    }

    public function test_viewboard_requires_authentication(): void
    {
        $response = $this->get(
            route('elections.viewboard', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ])
        );

        $response->assertRedirect(route('login'));
    }

    public function test_viewboard_renders_correct_inertia_props(): void
    {
        $response = $this->actingAs($this->chief)->get(
            route('elections.viewboard', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ])
        );

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Election/Viewboard')
            ->has('election')
            ->has('stats')
            ->where('readonly', true)
        );

        // Verify election data is passed
        $response->assertInertia(fn ($page) => $page
            ->where('election.id', $this->election->id)
            ->where('election.name', $this->election->name)
        );
    }

    public function test_publish_requires_chief_authorization(): void
    {
        $response = $this->actingAs($this->deputy)->post(
            route('elections.publish', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ])
        );

        $response->assertStatus(403);
    }

    public function test_publish_transitions_state_machine(): void
    {
        // Before publish
        $this->assertEquals('counting', $this->election->state);
        $this->assertFalse($this->election->results_published);

        $response = $this->actingAs($this->chief)->post(
            route('elections.publish', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ])
        );

        $response->assertRedirect();

        $this->election->refresh();
        $this->assertTrue($this->election->results_published);
        $this->assertNotNull($this->election->results_published_at);
        $this->assertEquals('results_published', $this->election->state);
    }

    public function test_publish_dispatches_results_published_event(): void
    {
        Event::fake([ResultsPublishedEvent::class]);

        $this->actingAs($this->chief)->post(
            route('elections.publish', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ])
        );

        Event::assertDispatched(ResultsPublishedEvent::class, function ($event) {
            return $event->electionId() === $this->election->id
                && $event->publishedBy() === $this->chief->id
                && $event->state() === 'results_published';
        });
    }

    public function test_unpublish_requires_authorization(): void
    {
        // First, publish the results
        $this->election->update([
            'results_published' => true,
            'results_published_at' => now(),
            'state' => 'results_published',
        ]);

        // Deputy officer should NOT be able to unpublish
        $response = $this->actingAs($this->deputy)->post(
            route('elections.unpublish', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ])
        );

        $response->assertStatus(403);
    }

    public function test_unpublish_by_chief_sets_results_unpublished(): void
    {
        // First, publish the results
        $this->election->update([
            'results_published' => true,
            'results_published_at' => now(),
            'state' => 'results_published',
        ]);

        $response = $this->actingAs($this->chief)->post(
            route('elections.unpublish', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ])
        );

        $response->assertRedirect();

        $this->election->refresh();
        $this->assertFalse($this->election->results_published);
    }

    public function test_unpublish_dispatches_results_unpublished_event(): void
    {
        // Publish first
        $this->election->update([
            'results_published' => true,
            'results_published_at' => now(),
            'state' => 'results_published',
        ]);

        Event::fake([ResultsUnpublishedEvent::class]);

        $this->actingAs($this->chief)->post(
            route('elections.unpublish', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ])
        );

        Event::assertDispatched(ResultsUnpublishedEvent::class, function ($event) {
            return $event->electionId() === $this->election->id
                && $event->unpublishedBy() === $this->chief->id
                && $event->previousState() === 'results_published';
        });
    }
}
