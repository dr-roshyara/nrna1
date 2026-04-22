<?php

namespace Tests\Feature\Election;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ElectionAuditLogMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_election_audit_logs_table_exists(): void
    {
        $this->assertTrue(
            Schema::hasTable('election_audit_logs'),
            'The election_audit_logs table should exist.'
        );
    }

    public function test_has_all_required_columns(): void
    {
        $requiredColumns = [
            'id', 'election_id', 'action', 'old_values', 'new_values',
            'user_id', 'ip_address', 'user_agent', 'session_id',
            'created_at', 'updated_at'
        ];

        foreach ($requiredColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('election_audit_logs', $column),
                "The election_audit_logs table should have a '{$column}' column."
            );
        }
    }

    public function test_json_columns_exist(): void
    {
        $this->assertTrue(
            Schema::hasColumn('election_audit_logs', 'old_values'),
            'The election_audit_logs table should have an old_values JSON column.'
        );

        $this->assertTrue(
            Schema::hasColumn('election_audit_logs', 'new_values'),
            'The election_audit_logs table should have a new_values JSON column.'
        );
    }
}
