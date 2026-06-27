<?php

namespace Tests\Feature\Election;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ElectionVotingLockColumnsMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_voting_locked_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('elections', 'voting_locked'),
            'The elections table should have a voting_locked column.'
        );
    }

    public function test_voting_locked_column_exists(): void
    {
        // Already tested in test_has_voting_locked_column, but confirms it exists
        $this->assertTrue(
            Schema::hasColumn('elections', 'voting_locked'),
            'The voting_locked column must exist.'
        );
    }

    public function test_has_voting_locked_at_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('elections', 'voting_locked_at'),
            'The elections table should have a voting_locked_at column.'
        );
    }

    public function test_has_voting_locked_by_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('elections', 'voting_locked_by'),
            'The elections table should have a voting_locked_by column.'
        );
    }

    public function test_has_results_locked_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('elections', 'results_locked'),
            'The elections table should have a results_locked column.'
        );
    }

    public function test_results_locked_column_exists(): void
    {
        // Already tested in test_has_results_locked_column, but confirms it exists
        $this->assertTrue(
            Schema::hasColumn('elections', 'results_locked'),
            'The results_locked column must exist.'
        );
    }

    public function test_has_results_locked_at_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('elections', 'results_locked_at'),
            'The elections table should have a results_locked_at column.'
        );
    }
}
