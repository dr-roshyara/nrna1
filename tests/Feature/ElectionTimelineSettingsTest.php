<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionTimelineSettingsTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);

        $this->election = Election::factory()->create([
            'organisation_id'          => $this->organisation->id,
            'type'                     => 'real',
            'administration_suggested_start' => null,
            'administration_suggested_end'   => null,
            'nomination_suggested_start'     => null,
            'nomination_suggested_end'       => null,
            'voting_starts_at'               => null,
            'voting_ends_at'                 => null,
            'results_published_at'           => null,
        ]);

        $this->admin = User::factory()->forOrganisation($this->organisation)->create();

        UserOrganisationRole::where('user_id', $this->admin->id)
            ->where('organisation_id', $this->organisation->id)
            ->update(['role' => 'admin']);
    }

    // =========================================================================
    // PAGE ACCESS TESTS
    // =========================================================================

    /** @test */
    public function timeline_page_is_accessible(): void
    {
        $this->actingAs($this->admin)
            ->get(route('elections.timeline', $this->election->slug))
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page->component('Election/Timeline'));
    }

    /** @test */
    public function timeline_page_redirects_guest_to_login(): void
    {
        $this->get(route('elections.timeline', $this->election->slug))
            ->assertRedirect(route('login'));
    }

    // =========================================================================
    // DATE UPDATE TESTS
    // =========================================================================

    /** @test */
    public function can_update_administration_dates(): void
    {
        $this->actingAs($this->admin);

        $start = Carbon::tomorrow()->format('Y-m-d H:i:s');
        $end = Carbon::tomorrow()->addDays(14)->format('Y-m-d H:i:s');

        $response = $this->patch(route('elections.update-timeline', $this->election->slug), [
            'administration_suggested_start' => $start,
            'administration_suggested_end' => $end,
        ]);

        $response->assertRedirect();
        $this->election->refresh();

        $this->assertNotNull($this->election->administration_suggested_start);
        $this->assertNotNull($this->election->administration_suggested_end);
    }

    /** @test */
    public function validates_end_date_after_start_date(): void
    {
        $this->actingAs($this->admin);

        $start = Carbon::tomorrow()->addDays(14)->format('Y-m-d H:i:s');
        $end = Carbon::tomorrow()->format('Y-m-d H:i:s');

        $response = $this->patch(route('elections.update-timeline', $this->election->slug), [
            'administration_suggested_start' => $start,
            'administration_suggested_end' => $end,
        ]);

        $response->assertSessionHasErrors(['administration_suggested_end']);
    }

    /** @test */
    public function can_update_all_phases_at_once(): void
    {
        $this->actingAs($this->admin);

        $payload = [
            'administration_suggested_start' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
            'administration_suggested_end' => Carbon::tomorrow()->addDays(14)->format('Y-m-d H:i:s'),
            'nomination_suggested_start' => Carbon::tomorrow()->addDays(15)->format('Y-m-d H:i:s'),
            'nomination_suggested_end' => Carbon::tomorrow()->addDays(28)->format('Y-m-d H:i:s'),
            'voting_starts_at' => Carbon::tomorrow()->addDays(29)->format('Y-m-d H:i:s'),
            'voting_ends_at' => Carbon::tomorrow()->addDays(35)->format('Y-m-d H:i:s'),
            'results_published_at' => Carbon::tomorrow()->addDays(36)->format('Y-m-d H:i:s'),
        ];

        $response = $this->patch(route('elections.update-timeline', $this->election->slug), $payload);

        $response->assertRedirect();
        $this->election->refresh();

        $this->assertNotNull($this->election->administration_suggested_start);
        $this->assertNotNull($this->election->nomination_suggested_start);
        $this->assertNotNull($this->election->voting_starts_at);
        $this->assertNotNull($this->election->results_published_at);
        $this->assertTrue($this->election->results_published);
    }

    /** @test */
    public function non_admin_cannot_update_timeline(): void
    {
        $regularUser = User::factory()->forOrganisation($this->organisation)->create();

        $this->actingAs($regularUser)
            ->patch(route('elections.update-timeline', $this->election->slug), [
                'administration_suggested_start' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
            ])
            ->assertStatus(403);
    }

    // =========================================================================
    // VALIDATION TESTS
    // =========================================================================

    /** @test */
    public function validates_nomination_dates_chronologically(): void
    {
        $this->actingAs($this->admin);

        $response = $this->patch(route('elections.update-timeline', $this->election->slug), [
            'nomination_suggested_start' => Carbon::tomorrow()->addDays(14)->format('Y-m-d H:i:s'),
            'nomination_suggested_end' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
        ]);

        $response->assertSessionHasErrors(['nomination_suggested_end']);
    }

    /** @test */
    public function validates_phase_chronological_order(): void
    {
        $this->actingAs($this->admin);

        $response = $this->patch(route('elections.update-timeline', $this->election->slug), [
            'administration_suggested_end' => Carbon::tomorrow()->addDays(20)->format('Y-m-d H:i:s'),
            'nomination_suggested_start' => Carbon::tomorrow()->addDays(14)->format('Y-m-d H:i:s'),
        ]);

        $response->assertSessionHasErrors(['nomination_suggested_start']);
    }

    /** @test */
    public function voting_start_date_cannot_be_in_past(): void
    {
        $this->actingAs($this->admin);

        $response = $this->patch(route('elections.update-timeline', $this->election->slug), [
            'voting_starts_at' => Carbon::yesterday()->format('Y-m-d H:i:s'),
        ]);

        $response->assertSessionHasErrors(['voting_starts_at']);
    }

    // =========================================================================
    // RESULTS PUBLICATION TESTS
    // =========================================================================

    /** @test */
    public function setting_results_published_at_auto_publishes_results(): void
    {
        $this->actingAs($this->admin);

        $response = $this->patch(route('elections.update-timeline', $this->election->slug), [
            'results_published_at' => Carbon::tomorrow()->addDays(36)->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect();
        $this->election->refresh();

        $this->assertNotNull($this->election->results_published_at);
        $this->assertTrue($this->election->results_published);
    }
}
