<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\DeviceFingerprint;
use App\Models\Code;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;

class DeviceFingerprintTest extends TestCase
{
    /**
     * Helper to create a code using raw inserts to bypass factory issues
     */
    protected function createCodeWithDevice(string $deviceHash, string $electionId, string $orgId): Code
    {
        $faker = FakerFactory::create();

        // Ensure election exists
        if (!DB::table('elections')->where('id', $electionId)->exists()) {
            DB::table('elections')->insert([
                'id' => $electionId,
                'organisation_id' => $orgId,
                'name' => $faker->word(),
                'slug' => $faker->slug(),
                'type' => 'demo',
                'is_active' => 1,
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create user with raw insert
        $userId = Str::uuid()->toString();
        DB::table('users')->insert([
            'id' => $userId,
            'organisation_id' => $orgId,
            'name' => $faker->name(),
            'email' => $faker->email(),
            'password' => 'hashed',
            'remember_token' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create code with raw insert (only use columns that exist in UUID schema)
        $codeId = Str::uuid()->toString();
        DB::table('codes')->insert([
            'id' => $codeId,
            'organisation_id' => $orgId,
            'user_id' => $userId,
            'election_id' => $electionId,
            'device_fingerprint_hash' => $deviceHash,
            'code1' => (string) rand(100000, 999999),
            'code2' => (string) rand(100000, 999999),
            'is_code_to_save_vote_usable' => 1,
            'is_code2_usable' => 0,
            'can_vote_now' => 0,
            'has_voted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Return the code without global scopes
        return Code::withoutGlobalScopes()->find($codeId) ?? throw new \Exception("Code not found: $codeId");
    }

    /** @test */
    public function it_generates_consistent_hash_for_same_device()
    {
        $request = new Request([], [], [], [], [], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9',
        ]);

        $service = new DeviceFingerprint();

        $hash1 = $service->generate($request);
        $hash2 = $service->generate($request);

        $this->assertEquals($hash1, $hash2);
    }

    /** @test */
    public function it_generates_different_hash_for_different_ips()
    {
        $request1 = new Request([], [], [], [], [], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0...',
        ]);

        $request2 = new Request([], [], [], [], [], [
            'REMOTE_ADDR' => '192.168.1.2',
            'HTTP_USER_AGENT' => 'Mozilla/5.0...',
        ]);

        $service = new DeviceFingerprint();

        $hash1 = $service->generate($request1);
        $hash2 = $service->generate($request2);

        $this->assertNotEquals($hash1, $hash2);
    }

    /** @test */
    public function it_respects_max_votes_per_device_config()
    {
        config(['voting.max_votes_per_device' => 3]);

        $deviceHash = 'test-device-hash';
        $electionId = Str::uuid()->toString();
        $orgId = Organisation::getDefaultPlatform()->id;

        // Create 2 codes with explicit device hash
        $this->createCodeWithDevice($deviceHash, $electionId, $orgId);
        $this->createCodeWithDevice($deviceHash, $electionId, $orgId);

        $service = new DeviceFingerprint();
        $result = $service->canVote($deviceHash, $electionId);

        $this->assertTrue($result['allowed']);
        $this->assertEquals(2, $result['used']);
        $this->assertEquals(3, $result['max']);
        $this->assertEquals(1, $result['remaining']);
    }

    /** @test */
    public function it_blocks_when_max_votes_reached()
    {
        config(['voting.max_votes_per_device' => 2]);

        $deviceHash = 'test-device-hash';
        $electionId = Str::uuid()->toString();
        $orgId = Organisation::getDefaultPlatform()->id;

        // Create 2 codes (max reached)
        $this->createCodeWithDevice($deviceHash, $electionId, $orgId);
        $this->createCodeWithDevice($deviceHash, $electionId, $orgId);

        $service = new DeviceFingerprint();
        $result = $service->canVote($deviceHash, $electionId);

        $this->assertFalse($result['allowed']);
        $this->assertEquals(0, $result['remaining']);
    }

    /** @test */
    public function it_detects_anomalous_patterns()
    {
        config([
            'voting.device_time_window_minutes' => 10,
            'voting.device_anomaly_threshold' => 3,
        ]);

        $deviceHash = 'test-device-hash';
        $electionId = Str::uuid()->toString();
        $orgId = Organisation::getDefaultPlatform()->id;

        // Create 4 codes in 5 minutes (exceeds threshold of 3)
        // Using helper to avoid foreign key issues
        for ($i = 0; $i < 4; $i++) {
            // Create the code using helper (handles election creation)
            $code = $this->createCodeWithDevice($deviceHash, $electionId, $orgId);

            // Update the created_at time to be staggered
            DB::table('codes')
                ->where('id', $code->id)
                ->update(['created_at' => now()->subMinutes($i * 2)]);
        }

        $service = new DeviceFingerprint();
        $anomaly = $service->detectAnomaly($deviceHash, $electionId);

        $this->assertTrue($anomaly['detected']);
        $this->assertEquals(4, $anomaly['count']);
        $this->assertEquals(3, $anomaly['threshold']);
    }

    /** @test */
    public function it_returns_family_voting_message_when_configured()
    {
        config(['voting.max_votes_per_device' => 3]);

        $organisation = Organisation::getDefaultPlatform();

        $service = new DeviceFingerprint();
        $message = $service->getLimitMessage($organisation);

        // Message should mention 3 votes per device
        $this->assertStringContainsString('3', $message);
        $this->assertStringContainsString('vote', $message);
    }

    /** @test */
    public function it_gets_device_statistics()
    {
        $deviceHash = 'test-device-hash';
        $electionId = Str::uuid()->toString();
        $orgId = Organisation::getDefaultPlatform()->id;

        // Create 3 codes
        $this->createCodeWithDevice($deviceHash, $electionId, $orgId);
        $this->createCodeWithDevice($deviceHash, $electionId, $orgId);
        $this->createCodeWithDevice($deviceHash, $electionId, $orgId);

        $service = new DeviceFingerprint();
        $stats = $service->getDeviceStats($deviceHash, $electionId);

        $this->assertEquals(3, $stats['total_codes']);
        $this->assertArrayHasKey('used_codes', $stats);
        $this->assertArrayHasKey('unused_codes', $stats);
        $this->assertArrayHasKey('first_used', $stats);
        $this->assertArrayHasKey('last_used', $stats);
    }
}
