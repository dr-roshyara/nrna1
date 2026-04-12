<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoterVerificationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $admin;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();

        // Create base entities
        $this->organisation = Organisation::factory()->create();
        $this->admin = User::factory()->create();
        $this->voter = User::factory()->create();

        // Add admin and voter to organisation first (required for election membership)
        // This creates the user_organisation_roles records needed for composite foreign key
        \DB::table('user_organisation_roles')->insert([
            ['id' => \Illuminate\Support\Str::uuid(), 'user_id' => $this->admin->id, 'organisation_id' => $this->organisation->id, 'role' => 'admin', 'created_at' => now()],
            ['id' => \Illuminate\Support\Str::uuid(), 'user_id' => $this->voter->id, 'organisation_id' => $this->organisation->id, 'role' => 'member', 'created_at' => now()],
        ]);

        // Create election for this organisation with IP verification mode
        $this->election = Election::factory()
            ->for($this->organisation)
            ->create(['voter_verification_mode' => 'ip_only']);

        // Add admin to election with explicit relationships
        ElectionMembership::factory()
            ->create([
                'organisation_id' => $this->organisation->id,
                'election_id' => $this->election->id,
                'user_id' => $this->admin->id,
                'role' => 'admin',
                'status' => 'active',
            ]);

        // Add voter to election with explicit relationships
        ElectionMembership::factory()
            ->create([
                'organisation_id' => $this->organisation->id,
                'election_id' => $this->election->id,
                'user_id' => $this->voter->id,
                'role' => 'voter',
                'status' => 'active',
            ]);
    }

    public function test_admin_can_save_voter_verification(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('elections.voters.verify', [$this->organisation->slug, $this->election->slug]), [
                'user_id' => $this->voter->id,
                'verified_ip' => '192.168.1.100',
                'verified_device_fingerprint_hash' => null,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('voter_verifications', [
            'election_id' => $this->election->id,
            'user_id' => $this->voter->id,
            'verified_ip' => '192.168.1.100',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_save_verification_with_ip_only(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('elections.voters.verify', [$this->organisation->slug, $this->election->slug]), [
                'user_id' => $this->voter->id,
                'verified_ip' => '10.0.0.50',
            ]);

        $response->assertRedirect();

        $verification = VoterVerification::where('election_id', $this->election->id)
            ->where('user_id', $this->voter->id)
            ->first();

        $this->assertNotNull($verification);
        $this->assertEquals('10.0.0.50', $verification->verified_ip);
        $this->assertEquals('active', $verification->status);
    }

    public function test_admin_can_revoke_voter_verification(): void
    {
        // Create a verification
        VoterVerification::factory()
            ->for($this->election)
            ->for($this->voter, 'user')
            ->for($this->admin, 'verifiedBy')
            ->create(['status' => 'active']);

        $verification = VoterVerification::where('election_id', $this->election->id)
            ->where('user_id', $this->voter->id)
            ->first();

        $response = $this->actingAs($this->admin)
            ->delete(route('elections.voters.verification.revoke', [$this->organisation->slug, $this->election->slug, $verification->id]));

        $response->assertRedirect();

        $verification->refresh();
        $this->assertEquals('revoked', $verification->status);
        $this->assertNotNull($verification->revoked_by);
        $this->assertNotNull($verification->revoked_at);
    }

    public function test_re_verifying_updates_existing_record(): void
    {
        // Create initial verification
        VoterVerification::factory()
            ->for($this->election)
            ->for($this->voter, 'user')
            ->for($this->admin, 'verifiedBy')
            ->create(['verified_ip' => '192.168.1.1']);

        // Re-verify with different IP
        $this->actingAs($this->admin)
            ->post(route('elections.voters.verify', [$this->organisation->slug, $this->election->slug]), [
                'user_id' => $this->voter->id,
                'verified_ip' => '192.168.1.100',
            ]);

        // Should have only one record, updated
        $verifications = VoterVerification::where('election_id', $this->election->id)
            ->where('user_id', $this->voter->id)
            ->get();

        $this->assertEquals(1, $verifications->count());
        $this->assertEquals('192.168.1.100', $verifications->first()->verified_ip);
    }

    public function test_non_admin_cannot_save_verification(): void
    {
        $nonAdmin = User::factory()->create();

        // Add nonAdmin to organisation first
        \DB::table('user_organisation_roles')->insert([
            ['id' => \Illuminate\Support\Str::uuid(), 'user_id' => $nonAdmin->id, 'organisation_id' => $this->organisation->id, 'created_at' => now()],
        ]);

        ElectionMembership::factory()
            ->create([
                'organisation_id' => $this->organisation->id,
                'election_id' => $this->election->id,
                'user_id' => $nonAdmin->id,
                'role' => 'voter',
                'status' => 'active',
            ]);

        $response = $this->actingAs($nonAdmin)
            ->post(route('elections.voters.verify', [$this->organisation->slug, $this->election->slug]), [
                'user_id' => $this->voter->id,
                'verified_ip' => '192.168.1.100',
            ]);

        $response->assertForbidden();
    }

    // NOTE: Tests 6-10 below verify that voter verification is enforced during the voting process.
    // These tests will be implemented once the voting enforcement endpoint is created.
    // For now, they're skipped to focus on admin verification endpoints (tests 1-5).

    public function test_voter_not_verified_is_blocked_when_mode_not_none(): void
    {
        // TODO: Implement voting enforcement endpoint and test here
        // Election requires verification
        $this->election->update(['voter_verification_mode' => 'ip_only']);

        // This test will verify that a voter without verification cannot vote
        // when the election requires verification
        $this->markTestSkipped('Voting enforcement endpoint not yet implemented');
    }

    public function test_voter_on_wrong_ip_is_blocked(): void
    {
        // TODO: Implement voting enforcement endpoint and test here
        // Verify voter on specific IP
        VoterVerification::factory()
            ->for($this->election)
            ->for($this->voter, 'user')
            ->for($this->admin, 'verifiedBy')
            ->create(['verified_ip' => '192.168.1.100']);

        // Election requires IP verification
        $this->election->update(['voter_verification_mode' => 'ip_only']);

        // This test will verify that a voter on a different IP is blocked
        $this->markTestSkipped('Voting enforcement endpoint not yet implemented');
    }

    public function test_voter_on_wrong_device_is_blocked(): void
    {
        // TODO: Implement voting enforcement endpoint and test here
        // Verify voter on specific device
        $correctFingerprint = 'abc123def456789';
        VoterVerification::factory()
            ->for($this->election)
            ->for($this->voter, 'user')
            ->for($this->admin, 'verifiedBy')
            ->create(['verified_device_fingerprint_hash' => $correctFingerprint]);

        // Election requires device verification
        $this->election->update(['voter_verification_mode' => 'fingerprint_only']);

        // This test will verify that a voter on a different device is blocked
        $this->markTestSkipped('Voting enforcement endpoint not yet implemented');
    }

    public function test_verification_not_enforced_when_mode_is_none(): void
    {
        // TODO: Implement voting enforcement endpoint and test here
        // Election does NOT require verification

        // This test will verify that when mode=none, verification is not enforced
        $this->markTestSkipped('Voting enforcement endpoint not yet implemented');
    }

    public function test_verified_voter_bypasses_election_ip_count_limit(): void
    {
        // TODO: Implement voting enforcement endpoint and test here
        // Enable IP count limit on election

        // This test will verify that verified voters bypass the election-level IP count limit
        $this->markTestSkipped('Voting enforcement endpoint not yet implemented');
    }
}
