<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationDashboardMembershipTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $member;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org    = Organisation::factory()->create(['type' => 'tenant']);
        $this->member = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->member->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);
    }

    public function test_organisation_show_includes_membership_prop(): void
    {
        $this->actingAs($this->member)
             ->get(route('organisations.show', $this->org->slug))
             ->assertStatus(200)
             ->assertInertia(fn ($page) =>
                 $page->component('Organisations/Show')
                      ->has('membership')
             );
    }

    public function test_membership_prop_is_array(): void
    {
        $response = $this->actingAs($this->member)
             ->get(route('organisations.show', $this->org->slug));

        $response->assertStatus(200);
        $props = $response->viewData('page')['props'] ?? [];
        $this->assertIsArray($props['membership'] ?? []);
    }
}
