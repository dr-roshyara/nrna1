<?php

namespace Tests\Feature\Election;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CurrentStateColumnDropTest extends TestCase
{
    /**
     * Test that current_state column does not exist on elections table
     * This verifies the migration to drop it (if it ever existed) has run
     */
    public function test_current_state_column_does_not_exist_on_elections_table(): void
    {
        $hasColumn = Schema::hasColumn('elections', 'current_state');

        $this->assertFalse(
            $hasColumn,
            'The "current_state" column should not exist on the elections table. State must be derived from timestamps only.'
        );
    }
}
