<?php

namespace Tests\Feature\Membership;

use App\Events\Membership\MembershipRenewed;
use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipRenewal;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class MembershipRenewalTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private Member $member;
    private User $memberUser;
    private MembershipType $type;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role'            => 'admin',
        ]);

        $this->type = MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Annual',
            'slug'            => 'annual',
            'fee_amount'      => 50.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => true,
        ]);

        $this->memberUser = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->memberUser->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);

        $orgUser = OrganisationUser::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => $this->memberUser->id,
            'role'            => 'member',
            'status'          => 'active',
        ]);

        $this->member = Member::create([
            'id'                    => (string) Str::uuid(),
            'organisation_id'       => $this->org->id,
            'organisation_user_id'  => $orgUser->id,
            'status'                => 'active',
            'membership_expires_at' => now()->addDays(10),
        ]);
    }

    // ── Admin renewal ─────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_renew_any_member(): void
    {
        Event::fake([MembershipRenewed::class]);

        $response = $this->actingAs($this->admin)->post(
            route('organisations.members.renew', [$this->org->slug, $this->member->id]),
            ['membership_type_id' => $this->type->id]
        );

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('membership_renewals', [
            'organisation_id' => $this->org->id,
            'member_id'       => $this->member->id,
        ]);
    }

    /** @test */
    public function renewal_updates_member_expires_at(): void
    {
        Event::fake([MembershipRenewed::class]);

        $oldExpiry = $this->member->membership_expires_at;

        $this->actingAs($this->admin)->post(
            route('organisations.members.renew', [$this->org->slug, $this->member->id]),
            ['membership_type_id' => $this->type->id]
        );

        $this->member->refresh();
        $this->assertTrue($this->member->membership_expires_at->isAfter($oldExpiry));
    }

    /** @test */
    public function renewal_creates_a_linked_fee(): void
    {
        Event::fake([MembershipRenewed::class]);

        $this->actingAs($this->admin)->post(
            route('organisations.members.renew', [$this->org->slug, $this->member->id]),
            ['membership_type_id' => $this->type->id]
        );

        $renewal = MembershipRenewal::where('member_id', $this->member->id)->first();
        $this->assertNotNull($renewal);
        $this->assertNotNull($renewal->fee_id);
        $this->assertDatabaseHas('membership_fees', [
            'id'     => $renewal->fee_id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function renewal_fires_membership_renewed_event(): void
    {
        Event::fake([MembershipRenewed::class]);

        $this->actingAs($this->admin)->post(
            route('organisations.members.renew', [$this->org->slug, $this->member->id]),
            ['membership_type_id' => $this->type->id]
        );

        Event::assertDispatched(MembershipRenewed::class, fn ($e) =>
            $e->renewal->member_id === $this->member->id
        );
    }

    // ── Self-renewal ──────────────────────────────────────────────────────────

    /** @test */
    public function member_can_self_renew_within_window(): void
    {
        Event::fake([MembershipRenewed::class]);

        // Member expires in 10 days — within the 90-day window
        $response = $this->actingAs($this->memberUser)->post(
            route('organisations.members.renew', [$this->org->slug, $this->member->id]),
            ['membership_type_id' => $this->type->id]
        );

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function member_cannot_renew_more_than_90_days_after_expiry(): void
    {
        // Set expiry 91 days in the past
        $this->member->update(['membership_expires_at' => now()->subDays(91)]);

        $response = $this->actingAs($this->memberUser)->post(
            route('organisations.members.renew', [$this->org->slug, $this->member->id]),
            ['membership_type_id' => $this->type->id]
        );

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function lifetime_member_cannot_be_renewed(): void
    {
        $lifetimeType = MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Lifetime',
            'slug'            => 'lifetime',
            'fee_amount'      => 0,
            'fee_currency'    => 'EUR',
            'duration_months' => null, // lifetime
            'is_active'       => true,
        ]);

        // Lifetime members have no expiry
        $this->member->update(['membership_expires_at' => null]);

        $response = $this->actingAs($this->admin)->post(
            route('organisations.members.renew', [$this->org->slug, $this->member->id]),
            ['membership_type_id' => $lifetimeType->id]
        );

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
