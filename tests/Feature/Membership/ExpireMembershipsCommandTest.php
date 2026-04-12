<?php

/**
 * TDD — Phase 4: Automated membership expiry via artisan command
 *
 * Members with membership_expires_at < today must transition active → expired.
 * No such command exists yet. All tests MUST FAIL before the command is created (Red).
 */

namespace Tests\Feature\Membership;

use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ExpireMembershipsCommandTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private MembershipType $type;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org  = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);
        $this->type = MembershipType::factory()->fullMember()->create([
            'organisation_id' => $this->org->id,
        ]);
    }

    private function makeMember(string $status = 'active', ?string $expiresAt = null): Member
    {
        $user    = User::factory()->create();
        $orgUser = OrganisationUser::factory()->create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);
        return Member::factory()->create([
            'organisation_id'       => $this->org->id,
            'organisation_user_id'  => $orgUser->id,
            'membership_type_id'    => $this->type->id,
            'status'                => $status,
            'membership_expires_at' => $expiresAt,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 1 — Core expiry logic
    // ══════════════════════════════════════════════════════════════════════════

    public function test_member_past_expiry_date_is_set_to_expired(): void
    {
        $member = $this->makeMember('active', now()->subDay()->toDateTimeString());

        $this->artisan('membership:expire')->assertSuccessful();

        $this->assertEquals('expired', $member->fresh()->status);
    }

    public function test_lifetime_member_is_never_expired(): void
    {
        $member = $this->makeMember('active', null); // null = lifetime

        $this->artisan('membership:expire')->assertSuccessful();

        $this->assertEquals('active', $member->fresh()->status,
            'Lifetime member (null expires_at) must not be expired');
    }

    public function test_already_expired_member_is_not_double_processed(): void
    {
        $member = $this->makeMember('expired', now()->subDays(10)->toDateTimeString());

        $this->artisan('membership:expire')->assertSuccessful();

        // Status stays 'expired', not changed to something else
        $this->assertEquals('expired', $member->fresh()->status);
    }

    public function test_member_expiring_today_is_not_expired(): void
    {
        // expires_at = today (not past yet in strict < comparison)
        $member = $this->makeMember('active', now()->startOfDay()->toDateTimeString());

        $this->artisan('membership:expire')->assertSuccessful();

        $this->assertEquals('active', $member->fresh()->status,
            'Member whose expiry date is today should remain active (< today, not <=)');
    }

    public function test_future_member_is_not_expired(): void
    {
        $member = $this->makeMember('active', now()->addDays(30)->toDateTimeString());

        $this->artisan('membership:expire')->assertSuccessful();

        $this->assertEquals('active', $member->fresh()->status);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 2 — Voting rights after expiry
    // ══════════════════════════════════════════════════════════════════════════

    public function test_expired_member_voting_rights_become_none(): void
    {
        $member = $this->makeMember('active', now()->subDay()->toDateTimeString());
        $member->update(['fees_status' => 'paid']); // had full rights

        $this->artisan('membership:expire')->assertSuccessful();

        $member->refresh()->load('membershipType');
        $this->assertEquals('none', $member->voting_rights,
            'Expired member must have voting_rights = none regardless of fees');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 3 — Command output
    // ══════════════════════════════════════════════════════════════════════════

    public function test_command_reports_count_of_expired_members(): void
    {
        $this->makeMember('active', now()->subDays(2)->toDateTimeString());
        $this->makeMember('active', now()->subDays(5)->toDateTimeString());
        $this->makeMember('active', now()->addDays(30)->toDateTimeString()); // still active

        $this->artisan('membership:expire')
            ->expectsOutputToContain('2')
            ->assertSuccessful();
    }
}
