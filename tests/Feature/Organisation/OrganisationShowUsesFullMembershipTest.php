<?php

namespace Tests\Feature\Organisation;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OrganisationController::show() must expose uses_full_membership so the
 * frontend can decide whether the "Organisation at a Glance" zone (member
 * counts from the Full-Membership-only Member model) makes sense to show —
 * an election-only organisation has no such members, so that zone should
 * not render for it.
 */
class OrganisationShowUsesFullMembershipTest extends TestCase
{
    use RefreshDatabase;

    private function makeMember(Organisation $org): User
    {
        $user = User::factory()->create();
        UserOrganisationRole::updateOrCreate(
            ['user_id' => $user->id, 'organisation_id' => $org->id],
            ['role' => 'member']
        );

        return $user;
    }

    public function test_uses_full_membership_true_is_exposed_in_the_payload(): void
    {
        $org  = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => true]);
        $user = $this->makeMember($org);

        $props = $this->actingAs($user)
            ->get(route('organisations.show', $org->slug))
            ->assertOk()
            ->inertiaPage()['props'];

        $this->assertTrue($props['organisation']['uses_full_membership']);
    }

    public function test_uses_full_membership_false_is_exposed_in_the_payload(): void
    {
        $org  = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        $user = $this->makeMember($org);

        $props = $this->actingAs($user)
            ->get(route('organisations.show', $org->slug))
            ->assertOk()
            ->inertiaPage()['props'];

        $this->assertFalse($props['organisation']['uses_full_membership']);
    }

    public function test_logo_url_is_null_when_no_logo_is_set(): void
    {
        $org  = Organisation::factory()->create(['type' => 'tenant', 'logo' => null]);
        $user = $this->makeMember($org);

        $props = $this->actingAs($user)
            ->get(route('organisations.show', $org->slug))
            ->assertOk()
            ->inertiaPage()['props'];

        $this->assertNull($props['organisation']['logo_url']);
    }

    public function test_logo_url_resolves_the_stored_path_to_a_public_storage_url(): void
    {
        // organisations.logo stores a relative path under the "public" disk
        // (set by OrganisationController::store()'s upload handling) — the
        // page needs the resolved, browser-usable URL, not the raw path.
        $org  = Organisation::factory()->create(['type' => 'tenant', 'logo' => 'uploads/logos/example.png']);
        $user = $this->makeMember($org);

        $props = $this->actingAs($user)
            ->get(route('organisations.show', $org->slug))
            ->assertOk()
            ->inertiaPage()['props'];

        $this->assertSame(
            \Storage::disk('public')->url('uploads/logos/example.png'),
            $props['organisation']['logo_url']
        );
    }
}
