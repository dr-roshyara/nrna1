<?php

/**
 * ElectionPolicy — manageVoters authorization coverage
 *
 * Verifies that the manageVoters() policy gate correctly allows chief/deputy
 * officers and org owners/admins while blocking commissioners and plain members.
 */

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionPolicyTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election     $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->real()
            ->create(['status' => 'active']);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function makeOfficer(string $role): User
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
            'status'          => 'active',
            'appointed_by'    => $user->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);
        return $user;
    }

    private function makeOrgUser(string $role): User
    {
        $user = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => $role,
        ]);
        return $user;
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  manageVoters policy
    // ══════════════════════════════════════════════════════════════════════════

    public function test_chief_can_manage_voters(): void
    {
        $chief = $this->makeOfficer('chief');
        $this->assertTrue($chief->can('manageVoters', $this->election));
    }

    public function test_deputy_can_manage_voters(): void
    {
        $deputy = $this->makeOfficer('deputy');
        $this->assertTrue($deputy->can('manageVoters', $this->election));
    }

    public function test_owner_can_manage_voters(): void
    {
        $owner = $this->makeOrgUser('owner');
        $this->assertTrue($owner->can('manageVoters', $this->election));
    }

    public function test_admin_can_manage_voters(): void
    {
        $admin = $this->makeOrgUser('admin');
        $this->assertTrue($admin->can('manageVoters', $this->election));
    }

    public function test_commissioner_cannot_manage_voters(): void
    {
        $commissioner = $this->makeOfficer('commissioner');
        $this->assertFalse($commissioner->can('manageVoters', $this->election));
    }

    public function test_plain_member_cannot_manage_voters(): void
    {
        $member = $this->makeOrgUser('member');
        $this->assertFalse($member->can('manageVoters', $this->election));
    }
}
