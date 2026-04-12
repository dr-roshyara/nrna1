<?php

namespace Tests\Feature\Middleware;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureOrganisationMemberTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Organisation $org1;
    private Organisation $org2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->org1 = Organisation::create([
            'name' => 'Organisation 1',
            'slug' => 'org-1',
            'type' => 'tenant',
        ]);

        $this->org2 = Organisation::create([
            'name' => 'Organisation 2',
            'slug' => 'org-2',
            'type' => 'tenant',
        ]);

        $this->user->organisationRoles()->create([
            'organisation_id' => $this->org1->id,
            'role' => 'member',
        ]);
    }

    /** @test */
    public function user_can_access_organisation_they_are_member_of()
    {
        $this->actingAs($this->user)
            ->get("/organisations/{$this->org1->slug}")
            ->assertSuccessful();
    }

    /** @test */
    public function user_cannot_access_organisation_they_are_not_member_of()
    {
        $this->actingAs($this->user)
            ->get("/organisations/{$this->org2->slug}")
            ->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function unauthenticated_user_cannot_access_organisation_routes()
    {
        $this->get("/organisations/{$this->org1->slug}")
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function middleware_prevents_member_import_for_non_members()
    {
        $this->actingAs($this->user)
            ->get("/organisations/{$this->org2->slug}/members/import")
            ->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function member_can_access_import_page_for_their_organisation()
    {
        $this->actingAs($this->user)
            ->get("/organisations/{$this->org1->slug}/members/import")
            ->assertSuccessful();
    }

    /** @test */
    public function middleware_validates_organisation_exists()
    {
        $this->actingAs($this->user)
            ->get("/organisations/nonexistent-org")
            ->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }
}
