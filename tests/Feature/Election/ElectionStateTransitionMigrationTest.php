<?php

namespace Tests\Feature\Election;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ElectionStateTransitionMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_election_state_transitions_table_exists(): void
    {
        $this->assertTrue(
            Schema::hasTable('election_state_transitions'),
            'The election_state_transitions table should exist.'
        );
    }

    public function test_has_required_columns(): void
    {
        $requiredColumns = [
            'id', 'election_id', 'from_state', 'to_state', 'trigger',
            'actor_id', 'reason', 'metadata', 'created_at'
        ];

        foreach ($requiredColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('election_state_transitions', $column),
                "The election_state_transitions table should have a '{$column}' column."
            );
        }
    }

    public function test_has_no_updated_at_column(): void
    {
        $this->assertFalse(
            Schema::hasColumn('election_state_transitions', 'updated_at'),
            'The election_state_transitions table should NOT have an updated_at column (immutable).'
        );
    }

    public function test_election_id_is_indexed(): void
    {
        $indexes = Schema::getIndexes('election_state_transitions');
        $indexNames = array_map(fn($index) => $index['name'], $indexes);

        $hasElectionIdIndex = collect($indexNames)->contains(function ($name) {
            return str_contains($name, 'election_id');
        });

        $this->assertTrue(
            $hasElectionIdIndex,
            'The election_state_transitions table should have an index on election_id.'
        );
    }
}
