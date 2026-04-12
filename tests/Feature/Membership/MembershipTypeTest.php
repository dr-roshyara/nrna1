<?php

namespace Tests\Feature\Membership;

use App\Models\MembershipApplication;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MembershipTypeTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $owner;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->owner = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->owner->id,
            'organisation_id' => $this->org->id,
            'role'            => 'owner',
        ]);

        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role'            => 'admin',
        ]);
    }

    // ── CRUD access control ───────────────────────────────────────────────────

    /** @test */
    public function only_owner_can_create_type(): void
    {
        $response = $this->actingAs($this->owner)->post(
            route('organisations.membership-types.store', $this->org->slug),
            [
                'name'            => 'Gold',
                'slug'            => 'gold',
                'fee_amount'      => 100.00,
                'fee_currency'    => 'EUR',
                'duration_months' => 12,
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('membership_types', ['slug' => 'gold', 'organisation_id' => $this->org->id]);
    }

    /** @test */
    public function admin_cannot_create_type(): void
    {
        $response = $this->actingAs($this->admin)->post(
            route('organisations.membership-types.store', $this->org->slug),
            [
                'name'            => 'Silver',
                'slug'            => 'silver',
                'fee_amount'      => 75.00,
                'fee_currency'    => 'EUR',
                'duration_months' => 12,
            ]
        );

        $response->assertForbidden();
        $this->assertDatabaseCount('membership_types', 0);
    }

    /** @test */
    public function only_owner_can_update_type(): void
    {
        $type = MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Annual',
            'slug'            => 'annual',
            'fee_amount'      => 50.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => true,
        ]);

        $response = $this->actingAs($this->owner)->put(
            route('organisations.membership-types.update', [$this->org->slug, $type->id]),
            ['fee_amount' => 60.00]
        );

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('membership_types', ['id' => $type->id, 'fee_amount' => 60.00]);
    }

    /** @test */
    public function admin_cannot_update_type(): void
    {
        $type = MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Annual',
            'slug'            => 'annual',
            'fee_amount'      => 50.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => true,
        ]);

        $response = $this->actingAs($this->admin)->put(
            route('organisations.membership-types.update', [$this->org->slug, $type->id]),
            ['fee_amount' => 60.00]
        );

        $response->assertForbidden();
        $this->assertDatabaseHas('membership_types', ['id' => $type->id, 'fee_amount' => 50.00]);
    }

    // ── Slug uniqueness ───────────────────────────────────────────────────────

    /** @test */
    public function slug_must_be_unique_per_organisation(): void
    {
        MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Annual',
            'slug'            => 'annual',
            'fee_amount'      => 50.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => true,
        ]);

        $response = $this->actingAs($this->owner)->post(
            route('organisations.membership-types.store', $this->org->slug),
            [
                'name'            => 'Annual Duplicate',
                'slug'            => 'annual',
                'fee_amount'      => 55.00,
                'fee_currency'    => 'EUR',
                'duration_months' => 12,
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHasErrors(['slug']);
    }

    // ── Deactivation ──────────────────────────────────────────────────────────

    /** @test */
    public function deactivated_type_cannot_be_used_for_new_applications(): void
    {
        $type = MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Annual',
            'slug'            => 'annual',
            'fee_amount'      => 50.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => false, // inactive
        ]);

        $applicant = User::factory()->create();
        $response = $this->actingAs($applicant)->post(
            route('organisations.membership.apply.store', $this->org->slug),
            ['membership_type_id' => $type->id]
        );

        $response->assertRedirect();
        $this->assertDatabaseCount('membership_applications', 0);
    }
}
