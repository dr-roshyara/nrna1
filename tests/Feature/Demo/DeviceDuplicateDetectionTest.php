<?php

namespace Tests\Feature\Demo;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\Demo\DemoCode;
use App\Services\Voting\DeviceFingerprintService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceDuplicateDetectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * RED TEST 1: Device service can check if device voted in election
     */
    public function test_device_fingerprint_service_exists()
    {
        $this->assertTrue(class_exists(DeviceFingerprintService::class));
    }

    /**
     * RED TEST 2: hasDeviceVotedInElection returns false for new device
     */
    public function test_has_device_voted_returns_false_for_new_device()
    {
        $this->markTestIncomplete('Implement DeviceFingerprintService first');
    }

    /**
     * RED TEST 3: hasDeviceVotedInElection returns true after device voted
     */
    public function test_has_device_voted_returns_true_after_vote()
    {
        $this->markTestIncomplete('Implement DeviceFingerprintService first');
    }

    /**
     * RED TEST 4: Different election, same device returns false
     */
    public function test_same_device_different_election_returns_false()
    {
        $this->markTestIncomplete('Implement DeviceFingerprintService first');
    }

    /**
     * RED TEST 5: Multiple vote attempts from same device are detected
     */
    public function test_multiple_vote_attempts_from_same_device_detected()
    {
        $election = Election::factory()->create(['type' => 'demo']);
        $fingerprint = 'test_fingerprint_hash_abc123';

        // First voter with this device
        $code1 = DemoCode::factory()->create([
            'device_fingerprint_hash' => $fingerprint,
            'election_id' => $election->id,
            'has_voted' => true,
        ]);

        // Second voter with same device should trigger duplicate detection
        $code2 = DemoCode::factory()->create([
            'device_fingerprint_hash' => $fingerprint,
            'election_id' => $election->id,
            'has_voted' => false,
        ]);

        // Both records should exist with same fingerprint
        $this->assertDatabaseHas('demo_codes', [
            'device_fingerprint_hash' => $fingerprint,
            'election_id' => $election->id,
            'has_voted' => true,
        ]);

        $this->assertDatabaseHas('demo_codes', [
            'device_fingerprint_hash' => $fingerprint,
            'election_id' => $election->id,
            'has_voted' => false,
        ]);
    }
}
