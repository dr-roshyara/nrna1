<?php

/**
 * TDD — Voter Dropdown Eligibility
 *
 * Rule: The "unassigned members" dropdown in ElectionVoterController::index()
 * must show ONLY active formal members with full voting rights — the same
 * eligibility rule enforced on all write paths.
 */

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class VoterDropdownTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election     $election;
    private User         $officer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->real()
            ->create(['status' => 'active']);

        $this->officer = $this->makeOfficer('chief', 'active');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    private function makeOfficer(string $role, string $status): User
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

    private function makeActiveMember(
        User    $user,
        ?Carbon $expiresAt   = null,
        string  $fees        = 'paid',
        bool    $grantVoting = true,
        string  $status      = 'active'
    ): Member {
        $type = MembershipType::factory()
            ->for($this->org)
            ->state(['grants_voting_rights' => $grantVoting])
            ->create();

        $orgUser = OrganisationUser::factory()
            ->for($this->org)
            ->for($user)
            ->create(['status' => 'active']);

        // Required composite FK: (user_id, organisation_id) → user_organisation_roles
        UserOrganisationRole::firstOrCreate(
            ['user_id' => $user->id, 'organisation_id' => $this->org->id],
            ['id' => (string) Str::uuid(), 'role' => 'voter']
        );

        return Member::factory()
            ->for($this->org)
            ->for($orgUser, 'organisationUser')
            ->for($type, 'membershipType')
            ->create([
                'status'                => $status,
                'fees_status'           => $fees,
                'membership_expires_at' => $expiresAt,
            ]);
    }

    private function getDropdown(): \Illuminate\Support\Collection
    {
        $response = $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->get(route('elections.voters.index', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]));

        $response->assertOk();

        return collect($response->original->getData()['page']['props']['unassignedMembers']);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Tests
    // ══════════════════════════════════════════════════════════════════════════

    public function test_dropdown_shows_only_eligible_members(): void
    {
        // Eligible — active, paid, voting type
        $eligible = User::factory()->create(['organisation_id' => $this->org->id, 'name' => 'Eligible User', 'email_verified_at' => now()]);
        $this->makeActiveMember($eligible, now()->addYear(), 'paid', true);

        // Ineligible — staff role only, no Member record
        $staffOnly = User::factory()->create(['organisation_id' => $this->org->id, 'name' => 'Staff Only', 'email_verified_at' => now()]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $staffOnly->id,
            'organisation_id' => $this->org->id,
            'role'            => 'staff',
        ]);

        // Ineligible — expired membership
        $expired = User::factory()->create(['organisation_id' => $this->org->id, 'name' => 'Expired Member', 'email_verified_at' => now()]);
        $this->makeActiveMember($expired, now()->subDay(), 'paid', true);

        $members = $this->getDropdown();

        $names = $members->pluck('name')->toArray();

        $this->assertContains('Eligible User', $names);
        $this->assertNotContains('Staff Only', $names);
        $this->assertNotContains('Expired Member', $names);
    }

    public function test_dropdown_excludes_members_with_unpaid_fees(): void
    {
        $unpaid = User::factory()->create(['organisation_id' => $this->org->id, 'name' => 'Unpaid Member', 'email_verified_at' => now()]);
        $this->makeActiveMember($unpaid, now()->addYear(), 'unpaid', true);

        $members = $this->getDropdown();

        $this->assertNotContains('Unpaid Member', $members->pluck('name')->toArray());
    }

    public function test_dropdown_excludes_associate_type_members(): void
    {
        $associate = User::factory()->create(['organisation_id' => $this->org->id, 'name' => 'Associate Member', 'email_verified_at' => now()]);
        $this->makeActiveMember($associate, now()->addYear(), 'paid', false); // grantVoting = false

        $members = $this->getDropdown();

        $this->assertNotContains('Associate Member', $members->pluck('name')->toArray());
    }

    public function test_dropdown_excludes_already_assigned_voters(): void
    {
        $assigned = User::factory()->create(['organisation_id' => $this->org->id, 'name' => 'Already Assigned', 'email_verified_at' => now()]);
        $this->makeActiveMember($assigned, now()->addYear(), 'paid', true);

        // Register them as a voter for this election
        ElectionMembership::create([
            'user_id'         => $assigned->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'role'            => 'voter',
            'status'          => 'active',
            'assigned_by'     => $this->officer->id,
            'assigned_at'     => now(),
        ]);

        $members = $this->getDropdown();

        $this->assertNotContains('Already Assigned', $members->pluck('name')->toArray());
    }
}
