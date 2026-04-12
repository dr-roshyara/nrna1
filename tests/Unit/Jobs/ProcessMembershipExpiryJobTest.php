<?php

namespace Tests\Unit\Jobs;

use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProcessMembershipExpiryJobTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Member $member;
    private MembershipType $type;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

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

        $memberUser = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $memberUser->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);
        $orgUser = OrganisationUser::create([
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
            'membership_expires_at' => now()->subDay(), // already expired
        ]);
    }

    /** @test */
    public function pending_applications_past_expiry_are_auto_rejected(): void
    {
        $app = MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => User::factory()->create()->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
            'expires_at'         => now()->subDay(), // expired
        ]);

        Artisan::call('membership:process-expiry');

        $this->assertDatabaseHas('membership_applications', [
            'id'     => $app->id,
            'status' => 'rejected',
        ]);
    }

    /** @test */
    public function non_expired_applications_are_untouched(): void
    {
        $app = MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => User::factory()->create()->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
            'expires_at'         => now()->addDay(), // not yet expired
        ]);

        Artisan::call('membership:process-expiry');

        $this->assertDatabaseHas('membership_applications', [
            'id'     => $app->id,
            'status' => 'submitted',
        ]);
    }

    /** @test */
    public function pending_fees_past_due_are_marked_overdue(): void
    {
        $fee = MembershipFee::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'member_id'          => $this->member->id,
            'membership_type_id' => $this->type->id,
            'amount'             => 50.00,
            'currency'           => 'EUR',
            'fee_amount_at_time' => 50.00,
            'currency_at_time'   => 'EUR',
            'status'             => 'pending',
            'due_date'           => now()->subDay()->toDateString(),
        ]);

        Artisan::call('membership:process-expiry');

        $this->assertDatabaseHas('membership_fees', [
            'id'     => $fee->id,
            'status' => 'overdue',
        ]);
    }

    /** @test */
    public function fees_not_yet_due_are_untouched(): void
    {
        $fee = MembershipFee::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'member_id'          => $this->member->id,
            'membership_type_id' => $this->type->id,
            'amount'             => 50.00,
            'currency'           => 'EUR',
            'fee_amount_at_time' => 50.00,
            'currency_at_time'   => 'EUR',
            'status'             => 'pending',
            'due_date'           => now()->addDay()->toDateString(),
        ]);

        Artisan::call('membership:process-expiry');

        $this->assertDatabaseHas('membership_fees', [
            'id'     => $fee->id,
            'status' => 'pending',
        ]);
    }
}
