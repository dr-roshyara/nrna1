<?php

namespace Tests\Feature\Contexts\Elections;

use App\Contexts\Elections\Infrastructure\Policies\EloquentVoterEligibilityQueryService;
use App\Domain\Election\Enum\ElectionMode;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * EloquentVoterEligibilityQueryServiceTest — Infrastructure Implementation
 *
 * Responsibility: Test the Eloquent-backed implementation of VoterEligibilityPolicy.
 * This is where DB queries meet domain rules.
 *
 * ✔ Tests:
 * - Election-only mode: queries organisation_users correctly
 * - Full membership mode: queries members + fees correctly
 * - No N+1 queries
 * - Proper tenant scoping
 *
 * ❌ Forbidden:
 * - Decision logic testing (belongs in policy unit tests)
 * - Infrastructure details (raw queries - use persistence tests)
 */
class EloquentVoterEligibilityQueryServiceTest extends TestCase
{
    use RefreshDatabase;

    private EloquentVoterEligibilityQueryService $service;
    private Organisation $organisation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(EloquentVoterEligibilityQueryService::class);
        $this->organisation = Organisation::factory()->create(['uses_full_membership' => false]);
    }

    /**
     * Test B.1.1: Election-only user qualifies via Eloquent query
     */
    public function test_election_only_user_qualifies(): void
    {
        $user = User::factory()->create();
        OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $result = $this->service->isEligible(
            $user->id,
            $this->organisation->id,
            ElectionMode::ElectionOnly
        );

        $this->assertTrue($result);
    }

    /**
     * Test B.1.2: Election-only user rejected when inactive
     */
    public function test_election_only_inactive_user_rejected(): void
    {
        $user = User::factory()->create();
        OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user->id,
            'status' => 'inactive',
            'joined_at' => now(),
        ]);

        $result = $this->service->isEligible(
            $user->id,
            $this->organisation->id,
            ElectionMode::ElectionOnly
        );

        $this->assertFalse($result);
    }

    /**
     * Test B.1.3: Election-only user rejected when not in organisation
     */
    public function test_election_only_user_not_in_organisation(): void
    {
        $user = User::factory()->create();

        $result = $this->service->isEligible(
            $user->id,
            $this->organisation->id,
            ElectionMode::ElectionOnly
        );

        $this->assertFalse($result);
    }

    /**
     * Test B.1.4: Election-only user rejected when soft-deleted
     */
    public function test_election_only_soft_deleted_user_rejected(): void
    {
        $user = User::factory()->create();
        $record = OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id' => $user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);
        $record->delete();

        $result = $this->service->isEligible(
            $user->id,
            $this->organisation->id,
            ElectionMode::ElectionOnly
        );

        $this->assertFalse($result);
    }

    /**
     * Test B.1.5: Full membership mode accepts valid voter
     */
    public function test_full_membership_valid_voter_accepted(): void
    {
        $fullMembershipOrg = Organisation::factory()->create(['uses_full_membership' => true]);
        $user = User::factory()->create();

        // Create organisation user first (required for member)
        $orgUser = \App\Models\OrganisationUser::create([
            'organisation_id' => $fullMembershipOrg->id,
            'user_id' => $user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        // Create member with valid fees
        \App\Models\Member::factory()->create([
            'organisation_user_id' => $orgUser->id,
            'organisation_id' => $fullMembershipOrg->id,
            'status' => 'active',
            'fees_status' => 'paid',
        ]);

        $result = $this->service->isEligible(
            $user->id,
            $fullMembershipOrg->id,
            ElectionMode::FullMembership
        );

        $this->assertTrue($result);
    }

    /**
     * Test B.1.6: Full membership rejects user without member record
     */
    public function test_full_membership_no_member_record_rejected(): void
    {
        $fullMembershipOrg = Organisation::factory()->create(['uses_full_membership' => true]);
        $user = User::factory()->create();

        $result = $this->service->isEligible(
            $user->id,
            $fullMembershipOrg->id,
            ElectionMode::FullMembership
        );

        $this->assertFalse($result);
    }

    /**
     * Test B.1.7: Full membership rejects unpaid fees
     */
    public function test_full_membership_unpaid_fees_rejected(): void
    {
        $fullMembershipOrg = Organisation::factory()->create(['uses_full_membership' => true]);
        $user = User::factory()->create();

        $orgUser = \App\Models\OrganisationUser::create([
            'organisation_id' => $fullMembershipOrg->id,
            'user_id' => $user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        \App\Models\Member::factory()->create([
            'organisation_user_id' => $orgUser->id,
            'organisation_id' => $fullMembershipOrg->id,
            'status' => 'active',
            'fees_status' => 'unpaid',
        ]);

        $result = $this->service->isEligible(
            $user->id,
            $fullMembershipOrg->id,
            ElectionMode::FullMembership
        );

        $this->assertFalse($result);
    }

    /**
     * Test B.1.8: Tenant isolation - org A doesn't see org B voters
     */
    public function test_tenant_isolation_respected(): void
    {
        $orgA = Organisation::factory()->create(['uses_full_membership' => false]);
        $orgB = Organisation::factory()->create(['uses_full_membership' => false]);
        $user = User::factory()->create();

        // User only in org A
        OrganisationUser::create([
            'organisation_id' => $orgA->id,
            'user_id' => $user->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        // User should be eligible for orgA
        $resultOrgA = $this->service->isEligible(
            $user->id,
            $orgA->id,
            ElectionMode::ElectionOnly
        );

        // But NOT eligible for orgB
        $resultOrgB = $this->service->isEligible(
            $user->id,
            $orgB->id,
            ElectionMode::ElectionOnly
        );

        $this->assertTrue($resultOrgA);
        $this->assertFalse($resultOrgB);
    }
}
