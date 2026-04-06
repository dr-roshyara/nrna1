<?php

namespace Tests\Unit\Models;

use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipRenewal;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MembershipRenewalTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Member $member;
    private User $admin;
    private MembershipType $type;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org   = Organisation::factory()->create(['type' => 'tenant']);

        // Set the tenant session so BelongsToTenant global scope resolves correctly
        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create();
        $this->type  = MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Annual',
            'slug'            => 'annual',
            'fee_amount'      => 50.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => true,
        ]);

        $memberUser = User::factory()->create();
        $orgUser    = OrganisationUser::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => $memberUser->id,
            'role'            => 'member',
            'status'          => 'active',
        ]);

        $this->member = Member::create([
            'id'                    => (string) Str::uuid(),
            'organisation_id'       => $this->org->id,
            'organisation_user_id'  => $orgUser->id,
            'status'                => 'active',
            'membership_expires_at' => now()->addMonths(1),
        ]);
    }

    private function makeFee(): MembershipFee
    {
        return MembershipFee::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'member_id'          => $this->member->id,
            'membership_type_id' => $this->type->id,
            'amount'             => 50.00,
            'currency'           => 'EUR',
            'fee_amount_at_time' => 50.00,
            'currency_at_time'   => 'EUR',
            'status'             => 'pending',
        ]);
    }

    private function makeRenewal(array $attrs = []): MembershipRenewal
    {
        return MembershipRenewal::create(array_merge([
            'id'                   => (string) Str::uuid(),
            'organisation_id'      => $this->org->id,
            'member_id'            => $this->member->id,
            'membership_type_id'   => $this->type->id,
            'renewed_by'           => $this->admin->id,
            'old_expires_at'       => now()->addMonths(1),
            'new_expires_at'       => now()->addMonths(13),
        ], $attrs));
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /** @test */
    public function it_belongs_to_a_member(): void
    {
        $renewal = $this->makeRenewal();
        $this->assertEquals($this->member->id, $renewal->member->id);
    }

    /** @test */
    public function it_belongs_to_renewed_by_user(): void
    {
        $renewal = $this->makeRenewal();
        $this->assertEquals($this->admin->id, $renewal->renewedBy->id);
    }

    /** @test */
    public function it_belongs_to_a_membership_type(): void
    {
        $renewal = $this->makeRenewal();
        $this->assertEquals($this->type->id, $renewal->membershipType->id);
    }

    // ── Business logic ────────────────────────────────────────────────────────

    /** @test */
    public function new_expires_at_is_after_old_expires_at(): void
    {
        $renewal = $this->makeRenewal([
            'old_expires_at' => now()->addMonths(1),
            'new_expires_at' => now()->addMonths(13),
        ]);

        $this->assertTrue($renewal->new_expires_at->isAfter($renewal->old_expires_at));
    }

    /** @test */
    public function it_links_to_a_membership_fee(): void
    {
        $fee     = $this->makeFee();
        $renewal = $this->makeRenewal(['fee_id' => $fee->id]);

        $this->assertEquals($fee->id, $renewal->fee->id);
    }

    /** @test */
    public function fee_link_is_optional(): void
    {
        $renewal = $this->makeRenewal(['fee_id' => null]);
        $this->assertNull($renewal->fee);
    }
}
