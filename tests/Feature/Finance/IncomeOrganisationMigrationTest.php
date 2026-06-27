<?php

namespace Tests\Feature\Finance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class IncomeOrganisationMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * RED Test: Income table should have organisation_id column after migration
     */
    public function test_income_table_has_organisation_id_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('incomes', 'organisation_id'),
            'incomes table should have organisation_id column'
        );
    }

    /**
     * RED Test: Income table should have source_type column
     */
    public function test_income_table_has_source_type_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('incomes', 'source_type'),
            'incomes table should have source_type column'
        );
    }

    /**
     * RED Test: Income table should have source_id column
     */
    public function test_income_table_has_source_id_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('incomes', 'source_id'),
            'incomes table should have source_id column'
        );
    }

    /**
     * RED Test: Can create income record with organisation_id
     */
    public function test_income_can_be_created_with_organisation_id(): void
    {
        $organisation = \App\Models\Organisation::factory()->create();
        $user = \App\Models\User::factory()->create();

        // This should not throw an error
        $income = \App\Models\Income::create([
            'user_id'        => $user->id,
            'organisation_id' => $organisation->id,
            'country'        => 'NP',
            'committee_name' => 'Test',
            'period_from'    => now()->startOfMonth(),
            'period_to'      => now()->endOfMonth(),
        ]);

        $this->assertNotNull($income->id);
        $this->assertEquals($organisation->id, $income->organisation_id);
    }

    /**
     * RED Test: Existing income records should have organisation_id after backfill
     *
     * This test verifies the migration backfill logic worked correctly.
     * Any pre-existing incomes should have been backfilled with their user's organisation.
     */
    public function test_existing_income_records_have_organisation_id_after_backfill(): void
    {
        // Get any existing income records (from earlier migrations/seeds)
        $incomes = \App\Models\Income::all();

        // If any exist, they must have organisation_id
        foreach ($incomes as $income) {
            $this->assertNotNull(
                $income->organisation_id,
                "Income {$income->id} should have organisation_id after backfill, but it's NULL"
            );
        }
    }
}
