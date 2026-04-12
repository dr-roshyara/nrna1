<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionCreationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $owner;
    private User $admin;
    private User $chief;
    private User $deputy;
    private User $commissioner;
    private User $regularMember;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);

        $this->owner         = $this->createUserWithRole('owner');
        $this->admin         = $this->createUserWithRole('admin');
        $this->chief         = $this->createOfficer('chief', 'active');
        $this->deputy        = $this->createOfficer('deputy', 'active');
        $this->commissioner  = $this->createOfficer('commissioner', 'active');
        $this->regularMember = $this->createUserWithRole('voter');
    }

    // =========================================================================
    // Permission Tests — who CAN create
    // =========================================================================

    public function test_organisation_owner_can_create_election(): void
    {
        $response = $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertRedirect();
        $this->assertDatabaseHas('elections', [
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
            'type'            => 'real',
            'status'          => 'planned',
        ]);
    }

    public function test_organisation_admin_can_create_election(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertRedirect();
        $this->assertDatabaseHas('elections', [
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
        ]);
    }

    // =========================================================================
    // Permission Tests — who CANNOT create
    // =========================================================================

    public function test_election_chief_cannot_create_election(): void
    {
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertForbidden();
        $this->assertDatabaseMissing('elections', [
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
        ]);
    }

    public function test_election_deputy_cannot_create_election(): void
    {
        $response = $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertForbidden();
        $this->assertDatabaseMissing('elections', [
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
        ]);
    }

    public function test_election_commissioner_cannot_create_election(): void
    {
        $response = $this->actingAs($this->commissioner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertForbidden();
        $this->assertDatabaseMissing('elections', [
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
        ]);
    }

    public function test_regular_member_cannot_create_election(): void
    {
        $response = $this->actingAs($this->regularMember)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertForbidden();
        $this->assertDatabaseMissing('elections', [
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
        ]);
    }

    public function test_policy_only_allows_owner_and_admin_to_create(): void
    {
        $this->assertTrue($this->owner->can('create', [Election::class, $this->org]));
        $this->assertTrue($this->admin->can('create', [Election::class, $this->org]));
        $this->assertFalse($this->chief->can('create', [Election::class, $this->org]));
        $this->assertFalse($this->deputy->can('create', [Election::class, $this->org]));
        $this->assertFalse($this->commissioner->can('create', [Election::class, $this->org]));
        $this->assertFalse($this->regularMember->can('create', [Election::class, $this->org]));
    }

    // =========================================================================
    // Validation Tests
    // =========================================================================

    public function test_election_requires_name(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), array_merge($this->validPayload(), ['name' => '']))
            ->assertSessionHasErrors('name');
    }

    public function test_election_requires_start_date(): void
    {
        $payload = $this->validPayload();
        unset($payload['start_date']);

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $payload)
            ->assertSessionHasErrors('start_date');
    }

    public function test_election_requires_end_date(): void
    {
        $payload = $this->validPayload();
        unset($payload['end_date']);

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $payload)
            ->assertSessionHasErrors('end_date');
    }

    public function test_start_date_must_be_before_end_date(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), array_merge($this->validPayload(), [
                'start_date' => now()->addDays(14)->toDateString(),
                'end_date'   => now()->addDays(7)->toDateString(),
            ]))
            ->assertSessionHasErrors('end_date');
    }

    public function test_start_date_cannot_be_in_past(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), array_merge($this->validPayload(), [
                'start_date' => now()->subDays(1)->toDateString(),
            ]))
            ->assertSessionHasErrors('start_date');
    }

    public function test_cannot_submit_demo_type(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), array_merge($this->validPayload(), ['type' => 'demo']))
            ->assertSessionHasErrors('type');
    }

    public function test_election_name_must_be_unique_within_organisation(): void
    {
        Election::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
            'slug'            => 'general-election-2026-existing',
            'type'            => 'real',
            'status'          => 'planned',
            'start_date'      => now()->addDays(7),
            'end_date'        => now()->addDays(14),
        ]);

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload())
            ->assertSessionHasErrors('name');
    }

    public function test_same_election_name_is_allowed_in_different_organisation(): void
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
        Election::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $otherOrg->id,
            'name'            => 'General Election 2026',
            'slug'            => 'general-election-2026-other',
            'type'            => 'real',
            'status'          => 'planned',
            'start_date'      => now()->addDays(7),
            'end_date'        => now()->addDays(14),
        ]);

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload())
            ->assertRedirect();

        $this->assertDatabaseHas('elections', [
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
        ]);
    }

    // =========================================================================
    // Default Value Tests
    // =========================================================================

    public function test_election_defaults_to_planned_status(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $election = Election::withoutGlobalScopes()
            ->where('name', 'General Election 2026')
            ->where('organisation_id', $this->org->id)
            ->first();

        $this->assertEquals('planned', $election->status);
    }

    public function test_election_type_is_always_real(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $election = Election::withoutGlobalScopes()
            ->where('name', 'General Election 2026')
            ->where('organisation_id', $this->org->id)
            ->first();

        $this->assertEquals('real', $election->type);
    }

    public function test_description_is_optional(): void
    {
        $payload = $this->validPayload();
        unset($payload['description']);

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $payload)
            ->assertRedirect();

        $this->assertDatabaseHas('elections', [
            'organisation_id' => $this->org->id,
            'description'     => null,
        ]);
    }

    public function test_slug_is_generated_on_creation(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $election = Election::withoutGlobalScopes()
            ->where('name', 'General Election 2026')
            ->where('organisation_id', $this->org->id)
            ->first();

        $this->assertNotNull($election->slug);
        $this->assertStringContainsString('general-election-2026', $election->slug);
    }

    public function test_success_flash_message_on_creation(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload())
            ->assertSessionHas('success');
    }

    // =========================================================================
    // Cross-Organisation Tests
    // =========================================================================

    public function test_officer_from_different_org_cannot_create_election(): void
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
        $outsider = $this->createUserWithRoleInOrg('owner', $otherOrg);

        $response = $this->actingAs($outsider)
            ->withSession(['current_organisation_id' => $otherOrg->id])
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $this->assertContains($response->status(), [302, 403, 404]);
        $this->assertDatabaseMissing('elections', [
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
        ]);
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    private function validPayload(): array
    {
        return [
            'name'        => 'General Election 2026',
            'description' => 'Election for organisation leadership',
            'start_date'  => now()->addDays(7)->toDateString(),
            'end_date'    => now()->addDays(14)->toDateString(),
        ];
    }

    private function createUserWithRole(string $role): User
    {
        return $this->createUserWithRoleInOrg($role, $this->org);
    }

    private function createUserWithRoleInOrg(string $role, Organisation $org): User
    {
        $user = User::factory()->create([
            'organisation_id'   => $org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $org->id,
            'role'            => $role,
        ]);
        return $user;
    }

    private function createOfficer(string $role, string $status): User
    {
        $user = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $user->id,
            'role'            => $role,
            'status'          => $status,
            'appointed_by'    => $user->id,
            'appointed_at'    => now(),
            'accepted_at'     => $status === 'active' ? now() : null,
        ]);
        return $user;
    }
}
