<?php

namespace Tests\Feature\Organisation;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrganisationCreationPageTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_create_page_accessible_to_authenticated_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('organisations.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Organisations/Create'));
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $this->get(route('organisations.create'))->assertRedirect(route('login'));
    }

    public function test_store_creates_organisation_with_valid_name(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Test New Organisation']);

        $response->assertRedirect();
        $this->assertDatabaseHas('organisations', [
            'name' => 'Test New Organisation',
            'type' => 'tenant',
        ]);
    }

    public function test_store_assigns_creator_as_owner(): void
    {
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Ownership Test Org']);

        $org = Organisation::where('name', 'Ownership Test Org')->first();

        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id'         => $this->user->id,
            'organisation_id' => $org->id,
            'role'            => 'owner',
        ]);
    }

    public function test_store_updates_users_current_organisation(): void
    {
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Switch Org']);

        $org = Organisation::where('name', 'Switch Org')->first();
        $this->assertEquals($org->id, $this->user->fresh()->organisation_id);
    }

    public function test_store_requires_name(): void
    {
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_store_requires_name_at_least_3_chars(): void
    {
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Ab'])
            ->assertSessionHasErrors('name');
    }

    public function test_store_rejects_name_over_255_chars(): void
    {
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => str_repeat('a', 256)])
            ->assertSessionHasErrors('name');
    }

    public function test_slug_is_generated_from_name(): void
    {
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'My Amazing Organisation']);

        $this->assertDatabaseHas('organisations', [
            'name' => 'My Amazing Organisation',
            'slug' => 'my-amazing-organisation',
        ]);
    }

    public function test_duplicate_slug_gets_counter_appended(): void
    {
        Organisation::factory()->create(['slug' => 'test-org']);

        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Test Org']);

        $this->assertDatabaseHas('organisations', ['slug' => 'test-org-1']);
    }

    public function test_redirect_after_creation_goes_to_organisations_show(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Redirect Test Org']);

        $org = Organisation::where('name', 'Redirect Test Org')->first();
        $response->assertRedirect(route('organisations.show', $org->slug));
    }

    public function test_show_page_accessible_to_creator_immediately_after_creation(): void
    {
        $this->actingAs($this->user)
            ->post(route('organisations.store'), ['name' => 'Accessible Org']);

        $org = Organisation::where('name', 'Accessible Org')->first();

        $this->actingAs($this->user)
            ->withSession(['current_organisation_id' => $org->id])
            ->get(route('organisations.show', $org->slug))
            ->assertStatus(200);
    }

    public function test_organisations_index_lists_users_organisations(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->user->id,
            'organisation_id' => $org->id,
            'role'            => 'owner',
        ]);

        $response = $this->actingAs($this->user)->get(route('organisations.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Organisations/Index')
            ->has('organisations', 1)
        );
    }

    public function test_organisations_index_excludes_other_users_organisations(): void
    {
        // Org for another user, not this user
        Organisation::factory()->create(['type' => 'tenant']);

        $response = $this->actingAs($this->user)->get(route('organisations.index'));

        $response->assertInertia(fn ($page) => $page->has('organisations', 0));
    }
}
