<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionGracePeriodUITest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->election = Election::factory()->create([
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
            'allow_auto_transition' => false,
            'auto_transition_grace_days' => 0,
        ]);

        // Grant user chief officer role for this election
        ElectionOfficer::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'role' => 'chief',
            'status' => 'active',
        ]);
    }

    public function test_timeline_view_shows_grace_period_settings(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('elections.timeline', $this->election));

        $response->assertStatus(200);
        // Verify the response contains the grace period related text from the Vue component
        $response->assertSee('allow_auto_transition');
        $response->assertSee('auto_transition_grace_days');
    }

    public function test_can_update_auto_transition_grace_days(): void
    {
        $response = $this->actingAs($this->user)
            ->patch(route('elections.update-timeline', $this->election), [
                'allow_auto_transition' => true,
                'auto_transition_grace_days' => 3,
            ]);

        $this->election->refresh();
        $this->assertEquals(3, $this->election->auto_transition_grace_days);
        $this->assertTrue($this->election->allow_auto_transition);
    }

    public function test_can_toggle_allow_auto_transition(): void
    {
        $this->assertFalse($this->election->allow_auto_transition);

        $response = $this->actingAs($this->user)
            ->patch(route('elections.update-timeline', $this->election), [
                'auto_transition_grace_days' => 1,
                'allow_auto_transition' => true,
            ]);

        $this->election->refresh();
        $this->assertTrue($this->election->allow_auto_transition);

        // Toggle it back off
        $response = $this->actingAs($this->user)
            ->patch(route('elections.update-timeline', $this->election), [
                'auto_transition_grace_days' => 1,
                'allow_auto_transition' => false,
            ]);

        $this->election->refresh();
        $this->assertFalse($this->election->allow_auto_transition);
    }
}
