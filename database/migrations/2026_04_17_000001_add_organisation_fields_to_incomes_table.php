<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add organisation_id, source_type, source_id to incomes table.
     * Critical for multi-tenancy and integration with membership payments.
     */
    public function up(): void
    {
        // Only run if columns don't already exist (idempotent)
        if (! Schema::hasColumn('incomes', 'organisation_id')) {
            // Step 1: Add columns as nullable first
            Schema::table('incomes', function (Blueprint $table) {
                $table->uuid('organisation_id')->nullable()->after('id');
                $table->string('source_type')->nullable()->after('organisation_id'); // 'membership_fee'
                $table->uuid('source_id')->nullable()->after('source_type');
                $table->foreign('organisation_id')->references('id')->on('organisations')->cascadeOnDelete();
                // Composite index for Finance reporting queries
                $table->index(['organisation_id', 'source_type', 'created_at'], 'idx_income_org_source_date');
            });

            // Step 2: BACKFILL organisation_id from the user who submitted the income
            // This is critical for existing records to maintain data integrity
            DB::statement('
                UPDATE incomes
                SET organisation_id = (
                    SELECT organisation_id
                    FROM users
                    WHERE users.id = incomes.user_id
                )
                WHERE organisation_id IS NULL
            ');

            // Step 3: Make organisation_id non-nullable after backfill
            Schema::table('incomes', function (Blueprint $table) {
                $table->uuid('organisation_id')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if columns still exist (idempotent)
        if (Schema::hasColumn('incomes', 'organisation_id')) {
            Schema::table('incomes', function (Blueprint $table) {
                // Drop foreign key first if it exists
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexesDetail = $sm->listTableIndexes('incomes');

                // Only drop index if it exists
                if (isset($indexesDetail['idx_income_org_source_date'])) {
                    $table->dropIndex('idx_income_org_source_date');
                }

                // Check before dropping foreign key
                $foreignKeys = $sm->listTableForeignKeys('incomes');
                foreach ($foreignKeys as $fk) {
                    if ($fk->getLocalColumns()[0] === 'organisation_id') {
                        $table->dropForeign(['organisation_id']);
                        break;
                    }
                }

                $table->dropColumn(['organisation_id', 'source_type', 'source_id']);
            });
        }
    }
};
