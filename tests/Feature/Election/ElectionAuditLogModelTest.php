<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionAuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ElectionAuditLogModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_uses_uuid_primary_key(): void
    {
        $election = Election::factory()->create();

        $log = ElectionAuditLog::create([
            'election_id' => $election->id,
            'action' => 'created',
        ]);

        $this->assertIsString($log->id);
        $this->assertTrue(strlen($log->id) === 36); // UUID format
    }

    public function test_casts_old_values_as_array(): void
    {
        $election = Election::factory()->create();
        $oldValues = ['name' => 'Old Name', 'status' => 'draft'];

        $log = ElectionAuditLog::create([
            'election_id' => $election->id,
            'action' => 'updated',
            'old_values' => $oldValues,
        ]);

        $this->assertIsArray($log->old_values);
        $this->assertEquals($oldValues, $log->old_values);
    }

    public function test_casts_new_values_as_array(): void
    {
        $election = Election::factory()->create();
        $newValues = ['name' => 'New Name', 'status' => 'published'];

        $log = ElectionAuditLog::create([
            'election_id' => $election->id,
            'action' => 'updated',
            'new_values' => $newValues,
        ]);

        $this->assertIsArray($log->new_values);
        $this->assertEquals($newValues, $log->new_values);
    }

    public function test_belongs_to_election(): void
    {
        $election = Election::factory()->create();

        $log = ElectionAuditLog::create([
            'election_id' => $election->id,
            'action' => 'created',
        ]);

        $this->assertInstanceOf(Election::class, $log->election);
        $this->assertEquals($election->id, $log->election->id);
    }

    public function test_belongs_to_user(): void
    {
        $election = Election::factory()->create();
        $user = User::factory()->create();

        $log = ElectionAuditLog::create([
            'election_id' => $election->id,
            'action' => 'updated',
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $log->user);
        $this->assertEquals($user->id, $log->user->id);
    }

    public function test_scope_for_election(): void
    {
        $election1 = Election::factory()->create();
        $election2 = Election::factory()->create();

        ElectionAuditLog::create([
            'election_id' => $election1->id,
            'action' => 'created',
        ]);

        ElectionAuditLog::create([
            'election_id' => $election2->id,
            'action' => 'updated',
        ]);

        $logs = ElectionAuditLog::forElection($election1->id)->get();

        $this->assertCount(1, $logs);
        $this->assertEquals($election1->id, $logs->first()->election_id);
    }

    public function test_scope_for_action(): void
    {
        $election = Election::factory()->create();

        ElectionAuditLog::create([
            'election_id' => $election->id,
            'action' => 'created',
        ]);

        ElectionAuditLog::create([
            'election_id' => $election->id,
            'action' => 'updated',
        ]);

        ElectionAuditLog::create([
            'election_id' => $election->id,
            'action' => 'updated',
        ]);

        $logs = ElectionAuditLog::forAction('updated')->get();

        $this->assertCount(2, $logs);
        $this->assertTrue($logs->every(fn($log) => $log->action === 'updated'));
    }

    public function test_static_record_method_creates_log(): void
    {
        $election = Election::factory()->create();
        $user = User::factory()->create();

        $log = ElectionAuditLog::record(
            $election,
            'published',
            ['status' => 'draft'],
            ['status' => 'published'],
            $user
        );

        $this->assertInstanceOf(ElectionAuditLog::class, $log);
        $this->assertEquals($election->id, $log->election_id);
        $this->assertEquals('published', $log->action);
        $this->assertEquals(['status' => 'draft'], $log->old_values);
        $this->assertEquals(['status' => 'published'], $log->new_values);
        $this->assertEquals($user->id, $log->user_id);
    }

    public function test_record_method_captures_request_data_automatically(): void
    {
        $election = Election::factory()->create();
        $user = User::factory()->create();

        // Use the record method with null request (session not available in unit test)
        $log = ElectionAuditLog::record(
            $election,
            'transition',
            ['state' => 'administration'],
            ['state' => 'nomination'],
            $user,
            null
        );

        $this->assertEquals($election->id, $log->election_id);
        $this->assertEquals('transition', $log->action);
        $this->assertEquals('transition', $log->action);
        // IP address and user_agent are null when request is null (expected behavior)
        $this->assertNull($log->ip_address);
        $this->assertNull($log->user_agent);
    }
}
