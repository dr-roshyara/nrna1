<?php

namespace Tests\Feature\Audit;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

class VoterVerificationControllerAuditTest extends TestCase
{
    use RefreshDatabase;

    private string $auditBasePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auditBasePath = storage_path('logs/audit');

        // Clean up audit directory before each test
        if (File::exists($this->auditBasePath)) {
            File::deleteDirectory($this->auditBasePath);
        }
    }

    /**
     * Test: VoterVerificationController::store() logs 'voter_verified' event
     * Category: committee
     * Metadata: verified_ip, fingerprint
     */
    public function test_store_logs_voter_verified_event(): void
    {
        // Setup
        $organisation = Organisation::factory()->create();

        $election = Election::factory()->create([
            'organisation_id' => $organisation->id,
            'type' => 'real',
            'status' => 'active',
        ]);

        // Create committee member (admin)
        $admin = User::factory()->create(['organisation_id' => $organisation->id]);
        \App\Models\UserOrganisationRole::create([
            'id' => Str::uuid(),
            'user_id' => $admin->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        // Create voter to verify
        $voter = User::factory()->create(['organisation_id' => $election->organisation_id]);
        // Voter must be in user_organisation_roles before being in election_memberships
        \App\Models\UserOrganisationRole::create([
            'id' => Str::uuid(),
            'user_id' => $voter->id,
            'organisation_id' => $election->organisation_id,
            'role' => 'voter',
        ]);
        $election->memberships()->create([
            'user_id' => $voter->id,
            'organisation_id' => $election->organisation_id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        // Act: Store voter verification
        $response = $this->actingAs($admin)
            ->post(route('organisations.elections.voters.verify', [
                'organisation' => $organisation->slug,
                'election' => $election->id,
            ]), [
                'user_id' => $voter->id,
                'verified_ip' => '192.168.1.50',
                'verified_device_fingerprint_hash' => 'device_fingerprint_hash_123',
            ]);

        // Assert: Successful redirect
        $response->assertRedirect();

        // Assert: Audit log created
        $logFile = $this->getLogFilePath($election, 'committee.jsonl');
        $this->assertFileExists($logFile, "Audit log file not created at {$logFile}");

        // Assert: Entry contains correct event
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertNotEmpty($lines, "Audit log is empty");

        $entry = json_decode($lines[0], true);
        $this->assertIsArray($entry);
        $this->assertEquals('voter_verified', $entry['event']);
        $this->assertEquals($admin->id, $entry['user_id']);
        $this->assertEquals('committee', $entry['category']);

        // Assert: Metadata contains verification details
        $this->assertEquals('192.168.1.50', $entry['metadata']['verified_ip'] ?? null);
        $this->assertEquals('device_fingerprint_hash_123', $entry['metadata']['fingerprint'] ?? null);
    }

    /**
     * Test: VoterVerificationController::revoke() logs 'verification_revoked' event
     * Category: committee
     * Metadata: none
     */
    public function test_revoke_logs_verification_revoked_event(): void
    {
        // Setup
        $organisation = Organisation::factory()->create();

        $election = Election::factory()->create([
            'organisation_id' => $organisation->id,
            'type' => 'real',
            'status' => 'active',
        ]);

        // Create committee member (admin)
        $admin = User::factory()->create(['organisation_id' => $organisation->id]);
        \App\Models\UserOrganisationRole::create([
            'id' => Str::uuid(),
            'user_id' => $admin->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        // Create voter and existing verification
        $voter = User::factory()->create(['organisation_id' => $election->organisation_id]);
        // Voter must be in user_organisation_roles before being in election_memberships
        \App\Models\UserOrganisationRole::create([
            'id' => Str::uuid(),
            'user_id' => $voter->id,
            'organisation_id' => $election->organisation_id,
            'role' => 'voter',
        ]);
        $election->memberships()->create([
            'user_id' => $voter->id,
            'organisation_id' => $election->organisation_id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $verification = VoterVerification::create([
            'election_id' => $election->id,
            'user_id' => $voter->id,
            'organisation_id' => $organisation->id,
            'verified_ip' => '192.168.1.50',
            'verified_device_fingerprint_hash' => 'device_fingerprint_hash_123',
            'verified_at' => now(),
        ]);

        // Act: Revoke voter verification
        $response = $this->actingAs($admin)
            ->delete(route('organisations.elections.voters.verification.revoke', [
                'organisation' => $organisation->slug,
                'election' => $election->id,
                'verification' => $verification->id,
            ]));

        // Assert: Successful redirect
        $response->assertRedirect();

        // Assert: Audit log created
        $logFile = $this->getLogFilePath($election, 'committee.jsonl');
        $this->assertFileExists($logFile, "Audit log file not created at {$logFile}");

        // Assert: Entry contains correct event
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertNotEmpty($lines, "Audit log is empty");

        $entry = json_decode($lines[0], true);
        $this->assertIsArray($entry);
        $this->assertEquals('verification_revoked', $entry['event']);
        $this->assertEquals($admin->id, $entry['user_id']);
        $this->assertEquals('committee', $entry['category']);
    }

    /**
     * Helper: Get the audit log file path for an election
     */
    private function getLogFilePath(Election $election, string $filename): string
    {
        $startDate = $election->start_date ?? now();
        $folderName = sprintf(
            '%s_%s_%s',
            $election->slug,
            $startDate->format('Ymd'),
            $startDate->format('Hi')
        );

        return $this->auditBasePath . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $filename;
    }
}
