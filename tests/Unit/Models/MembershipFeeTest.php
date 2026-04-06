<?php

namespace Tests\Unit\Models;

use App\Models\Member;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MembershipFeeTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Member $member;
    private MembershipType $type;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org  = Organisation::factory()->create(['type' => 'tenant']);

        // Set the tenant session so BelongsToTenant global scope resolves correctly
        session(['current_organisation_id' => $this->org->id]);

        $user       = User::factory()->create();
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

        $orgUser = OrganisationUser::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => $user->id,
            'role'            => 'member',
            'status'          => 'active',
        ]);

        $this->member = Member::create([
            'id'                      => (string) Str::uuid(),
            'organisation_id'         => $this->org->id,
            'organisation_user_id'    => $orgUser->id,
            'status'                  => 'active',
            'membership_expires_at'   => now()->addYear(),
        ]);
    }

    private function makeFee(array $attrs = []): MembershipFee
    {
        return MembershipFee::create(array_merge([
            'id'                  => (string) Str::uuid(),
            'organisation_id'     => $this->org->id,
            'member_id'           => $this->member->id,
            'membership_type_id'  => $this->type->id,
            'amount'              => 50.00,
            'currency'            => 'EUR',
            'fee_amount_at_time'  => 50.00,
            'currency_at_time'    => 'EUR',
            'status'              => 'pending',
        ], $attrs));
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /** @test */
    public function it_belongs_to_a_member(): void
    {
        $fee = $this->makeFee();
        $this->assertEquals($this->member->id, $fee->member->id);
    }

    // ── Snapshot immutability ─────────────────────────────────────────────────

    /** @test */
    public function fee_amount_snapshot_is_stored_at_time_of_creation(): void
    {
        $fee = $this->makeFee(['amount' => 50.00, 'fee_amount_at_time' => 50.00]);

        // Even if type fee changes later, the snapshot is preserved
        $this->type->update(['fee_amount' => 75.00]);
        $fee->refresh();

        $this->assertEquals(50.00, (float) $fee->fee_amount_at_time);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /** @test */
    public function overdue_scope_returns_past_due_pending_fees(): void
    {
        $this->makeFee(['due_date' => now()->subDay(), 'status' => 'pending', 'idempotency_key' => 'k1']);
        $this->makeFee(['due_date' => now()->addDay(), 'status' => 'pending', 'idempotency_key' => 'k2']);
        $this->makeFee(['due_date' => now()->subDay(), 'status' => 'paid',    'idempotency_key' => 'k3']);

        $overdue = MembershipFee::overdue()->get();
        $this->assertCount(1, $overdue);
    }

    /** @test */
    public function paid_scope_filters_correctly(): void
    {
        $this->makeFee(['status' => 'paid',    'idempotency_key' => 'p1']);
        $this->makeFee(['status' => 'pending', 'idempotency_key' => 'p2']);
        $this->makeFee(['status' => 'waived',  'idempotency_key' => 'p3']);

        $paid = MembershipFee::paid()->get();
        $this->assertCount(1, $paid);
        $this->assertEquals('paid', $paid->first()->status);
    }

    // ── Idempotency ───────────────────────────────────────────────────────────

    /** @test */
    public function idempotency_key_must_be_unique(): void
    {
        $this->makeFee(['idempotency_key' => 'unique-key-abc']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->makeFee(['idempotency_key' => 'unique-key-abc']);
    }
}
