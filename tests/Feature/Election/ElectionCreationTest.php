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

        // The middleware stack runs. A previous blanket `withoutMiddleware()` also
        // disabled SubstituteBindings, so {organisation} never bound and
        // authorize('create', [Election::class, $organisation]) denied every request
        // — 403 instead of the expected redirect, and no validation errors flashed.
        // The bypass was the defect, not the workaround. (PBDIGIT-48)
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
        $election = Election::withoutGlobalScopes()
            ->where('organisation_id', $this->org->id)
            ->where('name', 'General Election 2026')
            ->first();

        $this->assertNotNull($election, 'Election was not created.');

        // Assert constitutional facts instead of deprecated state column
        $this->assertNull($election->voting_starts_at);
        $this->assertNull($election->voting_ends_at);
        $this->assertNull($election->administration_suggested_start);
        $this->assertNull($election->administration_suggested_end);
        $this->assertEquals(20, $election->expected_voter_count);
        $this->assertEquals('real', $election->type);
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

    // ═══════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // NEW: Constitutional Tests — expected_voter_count (TDD)
    // ═══════════════════════════════════════════════════════════════════════════════════════════════════════════════════

    public function test_election_requires_expected_voter_count(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), array_merge($this->validPayload(), ['expected_voter_count' => '']))
            ->assertSessionHasErrors('expected_voter_count');
    }

    public function test_expected_voter_count_must_be_at_least_1(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), array_merge($this->validPayload(), ['expected_voter_count' => 0]))
            ->assertSessionHasErrors('expected_voter_count');
    }

    public function test_expected_voter_count_is_saved_on_creation(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), array_merge($this->validPayload(), ['expected_voter_count' => 50]));

        $this->assertDatabaseHas('elections', [
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
            'expected_voter_count' => 50,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // NEW: Approval Routing Tests (constitutional consequences)
    // ═══════════════════════════════════════════════════════════════════════════════════════════════════════════════════

    public function test_small_election_saves_expected_voter_count_for_auto_approval(): void
    {
        // ≤40 voters → engine will auto-approve
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), array_merge($this->validPayload(), ['expected_voter_count' => 35]));

        $this->assertDatabaseHas('elections', [
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
            'expected_voter_count' => 35,
        ]);
    }

    public function test_large_election_saves_expected_voter_count_for_manual_review(): void
    {
        // >40 voters → engine will require manual approval
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), array_merge($this->validPayload(), ['expected_voter_count' => 100]));

        $this->assertDatabaseHas('elections', [
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
            'expected_voter_count' => 100,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // NEW: Lifecycle Capability Tests (draft invariants)
    // ═══════════════════════════════════════════════════════════════════════════════════════════════════════════════════

    public function test_draft_election_created_with_null_dates(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $election = Election::withoutGlobalScopes()
            ->where('name', 'General Election 2026')
            ->where('organisation_id', $this->org->id)
            ->first();

        $this->assertNull($election->voting_starts_at);
        $this->assertNull($election->voting_ends_at);
        $this->assertNull($election->administration_suggested_start);
        $this->assertNull($election->administration_suggested_end);
    }

    public function test_draft_election_state_is_draft(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $election = Election::withoutGlobalScopes()
            ->where('name', 'General Election 2026')
            ->where('organisation_id', $this->org->id)
            ->first();

        $this->assertEquals('draft', $election->state);
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
            'type'            => 'real',
            'state'           => 'draft',
            'slug'            => 'general-election-2026-' . Str::lower(Str::random(8)),
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
            'name'            => 'Different Election 2026',
            'type'            => 'real',
            'state'           => 'draft',
            'slug'            => 'different-election-2026-' . Str::lower(Str::random(8)),
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

    public function test_election_defaults_to_draft_state(): void
    {
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $election = Election::withoutGlobalScopes()
            ->where('name', 'General Election 2026')
            ->where('organisation_id', $this->org->id)
            ->first();

        $this->assertEquals('draft', $election->state);
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

    public function test_create_page_renders_timezone_field(): void
    {
        $response = $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->get(route('organisations.elections.create', $this->org->slug));

        $response->assertStatus(200);
        $response->assertSee('timezone', false);
    }

    private function validPayload(): array
    {
        return [
            'name'                  => 'General Election 2026',
            'description'           => 'Election for organisation leadership',
            'expected_voter_count'  => 20,
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
        UserOrganisationRole::updateOrCreate(
            [
                'user_id'         => $user->id,
                'organisation_id' => $org->id,
            ],
            [
                'role'            => $role,
            ]
        );
        return $user;
    }

    private function createOfficer(string $role, string $status): User
    {
        $user = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::updateOrCreate(
            [
                'user_id'         => $user->id,
                'organisation_id' => $this->org->id,
            ],
            [
                'role'            => 'voter',
            ]
        );
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
