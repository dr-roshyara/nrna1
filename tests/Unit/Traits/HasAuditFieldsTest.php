<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\User;
use App\Traits\HasAuditFields;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class HasAuditFieldsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Skip tests if columns don't exist yet
        if (!Schema::hasColumn('users', 'approvedBy')) {
            $this->markTestSkipped('Required columns not yet migrated. Run php artisan migrate first.');
        }
    }

    /** @test */
    public function it_returns_audit_trail_array()
    {
        $user = User::factory()->create([
            'approvedBy' => 'Committee Member',
            'suspendedBy' => null,
            'suspended_at' => null,
            'voting_started_at' => now(),
            'vote_submitted_at' => null,
            'vote_completed_at' => null,
            'voter_registration_at' => now()->subDay(),
        ]);

        $auditTrail = $user->getAuditTrail();

        $this->assertIsArray($auditTrail);
        $this->assertArrayHasKey('approved_by', $auditTrail);
        $this->assertArrayHasKey('suspended_by', $auditTrail);
        $this->assertArrayHasKey('suspended_at', $auditTrail);
        $this->assertArrayHasKey('voting_started_at', $auditTrail);
        $this->assertArrayHasKey('vote_submitted_at', $auditTrail);
        $this->assertArrayHasKey('vote_completed_at', $auditTrail);
        $this->assertArrayHasKey('voter_registration_at', $auditTrail);

        $this->assertEquals('Committee Member', $auditTrail['approved_by']);
        $this->assertNull($auditTrail['suspended_by']);
        $this->assertNotNull($auditTrail['voting_started_at']);
        $this->assertNotNull($auditTrail['voter_registration_at']);
    }

    /** @test */
    public function it_determines_suspended_status()
    {
        // Test suspended user
        $suspendedUser = User::factory()->create([
            'can_vote' => 0,
            'suspended_at' => now(),
        ]);

        $this->assertTrue($suspendedUser->isSuspended());

        // Test non-suspended user
        $activeUser = User::factory()->create([
            'can_vote' => 1,
            'suspended_at' => null,
        ]);

        $this->assertFalse($activeUser->isSuspended());

        // Test user with can_vote=0 but no suspended_at (should not be considered suspended)
        $inactiveUser = User::factory()->create([
            'can_vote' => 0,
            'suspended_at' => null,
        ]);

        $this->assertFalse($inactiveUser->isSuspended());
    }

    /** @test */
    public function it_merges_fillable_fields()
    {
        $user = new User();

        // Check that the fillable fields were merged
        $fillable = $user->getFillable();

        $this->assertContains('approvedBy', $fillable);
        $this->assertContains('suspendedBy', $fillable);
    }

    /** @test */
    public function it_casts_dates()
    {
        $user = new User();
        $casts = $user->getCasts();

        $dateFields = [
            'suspended_at',
            'voting_started_at',
            'vote_submitted_at',
            'vote_completed_at',
            'voter_registration_at',
        ];

        foreach ($dateFields as $field) {
            $this->assertArrayHasKey($field, $casts);
            $this->assertEquals('datetime', $casts[$field]);
        }

        // Test actual casting with a real user
        $testDate = '2026-03-05 10:00:00';
        $user = User::factory()->create([
            'suspended_at' => $testDate,
            'voting_started_at' => $testDate,
            'vote_submitted_at' => $testDate,
            'vote_completed_at' => $testDate,
            'voter_registration_at' => $testDate,
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->suspended_at);
        $this->assertEquals($testDate, $user->suspended_at->format('Y-m-d H:i:s'));
    }
}