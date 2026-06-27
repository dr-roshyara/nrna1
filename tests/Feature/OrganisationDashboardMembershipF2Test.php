<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationDashboardMembershipF2Test extends TestCase
{
    use RefreshDatabase;

    public function test_membership_widget_renders_with_no_committees(): void
    {
        $org    = Organisation::factory()->create(['type' => 'tenant']);
        $member = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $member->id,
            'organisation_id' => $org->id,
            'role'            => 'member',
        ]);

        $this->actingAs($member)
             ->get(route('organisations.show', $org->slug))
             ->assertStatus(200)
             ->assertInertia(fn ($page) =>
                 $page->component('Organisations/Show')
                      ->where('membership', [])
             );
    }
}
