<?php

/**
 * TDD — Voter Eligibility Enforcement
 *
 * Rule: A user can become a voter ONLY IF they are an active formal member
 * with full voting rights (membership_type.grants_voting_rights = true,
 * status = active, fees_status IN (paid, exempt), not expired).
 *
 * Covers all four write paths:
 *  A — User::isEligibleVoter()              (unit)
 *  B — ElectionVoterController::store()     (single HTTP)
 *  C — ElectionVoterController::bulkStore() (bulk HTTP)
 *  D — ElectionMembership::bulkAssignVoters (model method)
 *  E — VoterImportService::preview()        (import path)
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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class VoterEligibilityTest extends TestCase
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

    // ── Helpers ─────────────────────────────────────────────────────────────��─

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

    /**
     * Creates a User → OrganisationUser → Member → MembershipType chain.
     * Also seeds the UserOrganisationRole row required by the election_memberships FK.
     */
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
                'status'               => $status,
                'fees_status'          => $fees,
                'membership_expires_at' => $expiresAt,
            ]);
    }

    private function makeCsv(string $content): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'voter_eligibility_') . '.csv';
        file_put_contents($path, $content);
        return new UploadedFile($path, 'voters.csv', 'text/csv', null, true);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group A — User::isEligibleVoter() unit
    // ══════════════════════════════════════════════════════════════════════════

    public function test_eligible_voter_with_paid_fees_and_voting_type(): void
    {
        $user = User::factory()->create();
        $this->makeActiveMember($user);

        $this->assertTrue($user->isEligibleVoter($this->org));
    }

    public function test_eligible_voter_with_exempt_fees(): void
    {
        $user = User::factory()->create();
        $this->makeActiveMember($user, fees: 'exempt');

        $this->assertTrue($user->isEligibleVoter($this->org));
    }

    public function test_eligible_voter_with_null_expiry_lifetime_member(): void
    {
        $user = User::factory()->create();
        $this->makeActiveMember($user, expiresAt: null);

        $this->assertTrue($user->isEligibleVoter($this->org));
    }

    public function test_eligible_voter_with_future_expiry(): void
    {
        $user = User::factory()->create();
        $this->makeActiveMember($user, expiresAt: now()->addYear());

        $this->assertTrue($user->isEligibleVoter($this->org));
    }

    public function test_ineligible_if_fees_status_is_unpaid(): void
    {
        $user = User::factory()->create();
        $this->makeActiveMember($user, fees: 'unpaid');

        $this->assertFalse($user->isEligibleVoter($this->org));
    }

    public function test_ineligible_if_fees_status_is_partial(): void
    {
        $user = User::factory()->create();
        $this->makeActiveMember($user, fees: 'partial');

        $this->assertFalse($user->isEligibleVoter($this->org));
    }

    public function test_ineligible_if_membership_type_does_not_grant_voting(): void
    {
        $user = User::factory()->create();
        $this->makeActiveMember($user, grantVoting: false);

        $this->assertFalse($user->isEligibleVoter($this->org));
    }

    public function test_ineligible_if_membership_expired(): void
    {
        $user = User::factory()->create();
        $this->makeActiveMember($user, expiresAt: now()->subDay());

        $this->assertFalse($user->isEligibleVoter($this->org));
    }

    public function test_ineligible_if_member_status_is_suspended(): void
    {
        $user   = User::factory()->create();
        $member = $this->makeActiveMember($user);
        $member->update(['status' => 'suspended']);

        $this->assertFalse($user->isEligibleVoter($this->org));
    }

    public function test_ineligible_if_member_is_soft_deleted(): void
    {
        $user   = User::factory()->create();
        $member = $this->makeActiveMember($user);
        $member->delete();

        $this->assertFalse($user->isEligibleVoter($this->org));
    }

    public function test_ineligible_if_no_member_record_exists(): void
    {
        $user = User::factory()->create();
        // Only add org role — no Member record
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'staff',
        ]);

        $this->assertFalse($user->isEligibleVoter($this->org));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group B — store() single voter HTTP
    // ══════════════════════════════════════════════════════════════════════════

    public function test_store_assigns_eligible_member_as_voter(): void
    {
        $voter = User::factory()->create(['email_verified_at' => now()]);
        $this->makeActiveMember($voter);

        $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.store', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['user_id' => $voter->id])
            ->assertRedirect();

        $this->assertDatabaseHas('election_memberships', [
            'election_id' => $this->election->id,
            'user_id'     => $voter->id,
            'role'        => 'voter',
        ]);
    }

    public function test_store_rejects_user_without_member_record(): void
    {
        $voter = User::factory()->create(['email_verified_at' => now()]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'role'            => 'staff',
        ]);

        $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.store', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['user_id' => $voter->id])
            ->assertSessionHasErrors(['user_id']);

        $this->assertDatabaseMissing('election_memberships', [
            'election_id' => $this->election->id,
            'user_id'     => $voter->id,
        ]);
    }

    public function test_store_rejects_member_with_unpaid_fees(): void
    {
        $voter = User::factory()->create(['email_verified_at' => now()]);
        $this->makeActiveMember($voter, fees: 'unpaid');

        $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.store', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['user_id' => $voter->id])
            ->assertSessionHasErrors(['user_id']);
    }

    public function test_store_rejects_associate_type_member(): void
    {
        $voter = User::factory()->create(['email_verified_at' => now()]);
        $this->makeActiveMember($voter, grantVoting: false);

        $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.store', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['user_id' => $voter->id])
            ->assertSessionHasErrors(['user_id']);
    }

    public function test_store_rejects_member_with_expired_membership(): void
    {
        $voter = User::factory()->create(['email_verified_at' => now()]);
        $this->makeActiveMember($voter, expiresAt: now()->subDay());

        $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.store', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['user_id' => $voter->id])
            ->assertSessionHasErrors(['user_id']);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group C — bulkStore() HTTP
    // ══════════════════════════════════════════════════════════════════════════

    public function test_bulk_store_assigns_only_eligible_members(): void
    {
        $eligible1 = User::factory()->create(['email_verified_at' => now()]);
        $eligible2 = User::factory()->create(['email_verified_at' => now()]);
        $ineligible = User::factory()->create(['email_verified_at' => now()]);

        $this->makeActiveMember($eligible1);
        $this->makeActiveMember($eligible2);
        // ineligible has only an org role, no Member record
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $ineligible->id,
            'organisation_id' => $this->org->id,
            'role'            => 'staff',
        ]);

        $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.bulk', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['user_ids' => [$eligible1->id, $eligible2->id, $ineligible->id]])
            ->assertRedirect();

        $this->assertDatabaseCount('election_memberships', 2);
        $result = session('bulk_result');
        $this->assertGreaterThanOrEqual(1, $result['invalid'] ?? 0);
    }

    public function test_bulk_store_with_all_eligible_users(): void
    {
        $users = User::factory()->count(3)->create(['email_verified_at' => now()]);
        foreach ($users as $user) {
            $this->makeActiveMember($user);
        }

        $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.bulk', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['user_ids' => $users->pluck('id')->toArray()])
            ->assertRedirect();

        $this->assertDatabaseCount('election_memberships', 3);
    }

    public function test_bulk_store_with_none_eligible_inserts_zero_rows(): void
    {
        $users = User::factory()->count(3)->create(['email_verified_at' => now()]);
        foreach ($users as $user) {
            UserOrganisationRole::create([
                'id'              => (string) Str::uuid(),
                'user_id'         => $user->id,
                'organisation_id' => $this->org->id,
                'role'            => 'staff',
            ]);
        }

        $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.bulk', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['user_ids' => $users->pluck('id')->toArray()])
            ->assertRedirect();

        $this->assertDatabaseCount('election_memberships', 0);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group D — ElectionMembership::bulkAssignVoters() model method
    // ══════════════════════════════════════════════════════════════════════════

    public function test_bulk_assign_filters_out_ineligible_users(): void
    {
        $eligible   = User::factory()->create();
        $unpaid     = User::factory()->create();
        $noMember   = User::factory()->create();

        $this->makeActiveMember($eligible);
        $this->makeActiveMember($unpaid, fees: 'unpaid');
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $noMember->id,
            'organisation_id' => $this->org->id,
            'role'            => 'staff',
        ]);

        $result = ElectionMembership::bulkAssignVoters(
            [$eligible->id, $unpaid->id, $noMember->id],
            $this->election->id,
            $this->officer->id
        );

        $this->assertEquals(1, $result['success']);
        $this->assertEquals(2, $result['invalid']);
    }

    public function test_bulk_assign_all_eligible_creates_all_records(): void
    {
        $users = User::factory()->count(3)->create();
        foreach ($users as $user) {
            $this->makeActiveMember($user);
        }

        $result = ElectionMembership::bulkAssignVoters(
            $users->pluck('id')->toArray(),
            $this->election->id,
            $this->officer->id
        );

        $this->assertEquals(3, $result['success']);
        $this->assertEquals(0, $result['invalid']);
    }

    public function test_bulk_assign_skips_already_assigned_voter(): void
    {
        $existing = User::factory()->create();
        $newUser  = User::factory()->create();

        $this->makeActiveMember($existing);
        $this->makeActiveMember($newUser);

        // Pre-assign existing user
        ElectionMembership::create([
            'user_id'         => $existing->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'role'            => 'voter',
            'status'          => 'active',
            'assigned_by'     => $this->officer->id,
            'assigned_at'     => now(),
        ]);

        $result = ElectionMembership::bulkAssignVoters(
            [$existing->id, $newUser->id],
            $this->election->id,
            $this->officer->id
        );

        $this->assertEquals(1, $result['success']);
        $this->assertEquals(1, $result['already_existing']);
    }

    public function test_bulk_assign_blocks_associate_type_member(): void
    {
        $user = User::factory()->create();
        $this->makeActiveMember($user, grantVoting: false);

        $result = ElectionMembership::bulkAssignVoters(
            [$user->id],
            $this->election->id,
            $this->officer->id
        );

        $this->assertEquals(0, $result['success']);
        $this->assertEquals(1, $result['invalid']);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group E — VoterImportService::preview() via HTTP
    // ══════════════════════════════════════════════════════════════════════════

    public function test_import_preview_marks_eligible_member_as_valid(): void
    {
        $user = User::factory()->create(['email' => 'eligible@example.com']);
        $this->makeActiveMember($user);

        $csv = "email\neligible@example.com\n";

        $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import.preview', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['file' => $this->makeCsv($csv)])
            ->assertOk()
            ->assertJsonPath('stats.valid', 1)
            ->assertJsonPath('stats.invalid', 0);
    }

    public function test_import_preview_marks_unpaid_member_as_invalid(): void
    {
        $user = User::factory()->create(['email' => 'unpaid@example.com']);
        $this->makeActiveMember($user, fees: 'unpaid');

        $csv = "email\nunpaid@example.com\n";

        $response = $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import.preview', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['file' => $this->makeCsv($csv)])
            ->assertOk()
            ->assertJsonPath('stats.invalid', 1);

        $error = $response->json('preview.0.errors.0');
        $this->assertStringContainsStringIgnoringCase('eligible voter', $error);
    }

    public function test_import_preview_marks_user_without_member_record_as_invalid(): void
    {
        $user = User::factory()->create(['email' => 'staff@example.com']);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'staff',
        ]);

        $csv = "email\nstaff@example.com\n";

        $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import.preview', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['file' => $this->makeCsv($csv)])
            ->assertOk()
            ->assertJsonPath('stats.invalid', 1);
    }

    public function test_import_preview_marks_expired_member_as_invalid(): void
    {
        $user = User::factory()->create(['email' => 'expired@example.com']);
        $this->makeActiveMember($user, expiresAt: now()->subDay());

        $csv = "email\nexpired@example.com\n";

        $this->actingAs($this->officer)
            ->withSession($this->orgSession())
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import.preview', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
            ]), ['file' => $this->makeCsv($csv)])
            ->assertOk()
            ->assertJsonPath('stats.invalid', 1);
    }
}
