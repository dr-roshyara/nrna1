<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Demo\DemoCode;
use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemoCodeModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * RED TEST 1: DemoCode fillable includes voting_code
     */
    public function test_demo_code_fillable_includes_voting_code()
    {
        $fillable = (new DemoCode())->getFillable();

        $this->assertContains('voting_code', $fillable);
    }

    /**
     * RED TEST 2: DemoCode fillable includes device_fingerprint_hash
     */
    public function test_demo_code_fillable_includes_device_fingerprint_hash()
    {
        $fillable = (new DemoCode())->getFillable();

        $this->assertContains('device_fingerprint_hash', $fillable);
    }

    /**
     * RED TEST 3: DemoCode fillable includes voting_time_in_minutes
     */
    public function test_demo_code_fillable_includes_voting_time_in_minutes()
    {
        $fillable = (new DemoCode())->getFillable();

        $this->assertContains('voting_time_in_minutes', $fillable);
    }

    /**
     * RED TEST 4: DemoCode does NOT have has_used_code1 or has_used_code2 in fillable
     */
    public function test_demo_code_fillable_does_not_include_nonexistent_columns()
    {
        $fillable = (new DemoCode())->getFillable();

        $this->assertNotContains('has_used_code1', $fillable);
        $this->assertNotContains('has_used_code2', $fillable);
    }

    /**
     * RED TEST 5: DemoCode can store voting_code
     */
    public function test_demo_code_can_store_voting_code()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        $code = DemoCode::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'voting_code' => 'test_voting_code_12345',
            'code_to_open_voting_form' => 'code1',
        ]);

        $this->assertEquals('test_voting_code_12345', $code->voting_code);
    }

    /**
     * RED TEST 6: DemoCode can store device_fingerprint_hash
     */
    public function test_demo_code_can_store_device_fingerprint_hash()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        $fingerprint = hash('sha256', 'test_device_signal');

        $code = DemoCode::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'device_fingerprint_hash' => $fingerprint,
            'code_to_open_voting_form' => 'code1',
        ]);

        $this->assertEquals($fingerprint, $code->device_fingerprint_hash);
    }

    /**
     * RED TEST 7: DemoCode can store voting_time_in_minutes
     */
    public function test_demo_code_can_store_voting_time_in_minutes()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        $code = DemoCode::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'voting_time_in_minutes' => 45,
            'code_to_open_voting_form' => 'code1',
        ]);

        $this->assertEquals(45, $code->voting_time_in_minutes);
    }

    /**
     * RED TEST 8: DemoCode schema table has voting_code column
     */
    public function test_demo_codes_table_has_voting_code_column()
    {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('demo_codes');

        $this->assertContains('voting_code', $columns);
    }

    /**
     * RED TEST 9: DemoCode isExpired method uses voting_time_in_minutes
     */
    public function test_demo_code_is_expired_method_respects_voting_time_in_minutes()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        // Code with 1 minute voting window, sent 2 minutes ago
        $code = DemoCode::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'code_to_open_voting_form' => 'code1',
            'code_to_open_voting_form_sent_at' => now()->subMinutes(2),
            'voting_time_in_minutes' => 1,  // Only 1 minute window
        ]);

        $this->assertTrue($code->isExpired());
    }

    /**
     * RED TEST 10: DemoCode isExpired respects longer voting windows
     */
    public function test_demo_code_is_not_expired_with_longer_window()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        // Code with 60 minute voting window, sent 2 minutes ago
        $code = DemoCode::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'code_to_open_voting_form' => 'code1',
            'code_to_open_voting_form_sent_at' => now()->subMinutes(2),
            'voting_time_in_minutes' => 60,  // 60 minute window
        ]);

        $this->assertFalse($code->isExpired());
    }
}
