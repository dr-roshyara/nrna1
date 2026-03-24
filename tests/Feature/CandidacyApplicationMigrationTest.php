<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CandidacyApplicationMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidacy_applications_table_has_required_columns(): void
    {
        $this->assertTrue(Schema::hasTable('candidacy_applications'));

        $required = [
            'id', 'user_id', 'organisation_id', 'election_id', 'post_id',
            'supporter_name', 'proposer_name', 'manifesto', 'documents',
            'status', 'rejection_reason', 'reviewed_at', 'reviewed_by',
            'created_at', 'updated_at',
        ];

        foreach ($required as $column) {
            $this->assertTrue(
                Schema::hasColumn('candidacy_applications', $column),
                "Missing column: {$column}"
            );
        }
    }
}
