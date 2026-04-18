<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Convert all 27+ ENUM columns to VARCHAR(50) with CHECK constraints for PostgreSQL compatibility.
     * MySQL ENUM columns are left unchanged (Laravel handles them natively).
     * This migration is idempotent — it only runs on PostgreSQL and only if CHECK constraints don't exist.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::transaction(function () {
            // Define all enum conversions: table => [column => 'value1,value2,value3']
            $conversions = [
                // Core Platform
                'organisations' => [
                    'type' => "'platform','tenant'",
                ],
                'elections' => [
                    'status' => "'draft','active','closed','cancelled'",
                    'type' => "'real','demo'",
                ],

                // Voter & Voting
                'voter_slugs' => [
                    'status' => "'active','expired','completed'",
                ],
                'election_officers' => [
                    'status' => "'active','pending','inactive','resigned'",
                    'role' => "'chief','deputy','commissioner'",
                ],

                // Membership
                'members' => [
                    'status' => "'active','inactive','suspended','ended'",
                    'fees_status' => "'exempt','pending','paid','overdue'",
                ],
                'election_memberships' => [
                    'status' => "'pending','active','inactive','suspended'",
                ],
                'organisation_users' => [
                    'role' => "'owner','admin','member','voter','commission'",
                    'status' => "'active','inactive'",
                ],

                // Newsletter
                'newsletter_recipients' => [
                    'status' => "'pending','sent','failed','bounced'",
                ],

                // Contributions
                'contributions' => [
                    'proof_type' => "'self_report','photo','document','third_party','community_attestation','institutional'",
                    'status' => "'pending','verified','rejected'",
                ],
                'contribution_files' => [
                    'status' => "'pending','verified','rejected'",
                ],

                // Member Import Jobs
                'member_import_jobs' => [
                    'status' => "'pending','processing','completed','failed'",
                ],

                // Membership Fees
                'membership_fees' => [
                    'status' => "'pending','paid','overdue','cancelled'",
                ],

                // Membership Applications
                'membership_applications' => [
                    'status' => "'pending','approved','rejected','withdrawn'",
                ],

                // Organisation Invitations
                'organisation_invitations' => [
                    'status' => "'pending','accepted','rejected','expired'",
                ],

                // Organisation Participants
                'organisation_participants' => [
                    'status' => "'active','inactive'",
                    'role' => "'participant','organizer'",
                ],
            ];

            foreach ($conversions as $table => $columns) {
                foreach ($columns as $column => $values) {
                    // Check if table and column exist
                    if (!DB::getSchemaBuilder()->hasColumn($table, $column)) {
                        continue;
                    }

                    $constraintName = "{$table}_{$column}_check";

                    // Check if constraint already exists (idempotent)
                    $constraints = DB::select("
                        SELECT constraint_name
                        FROM information_schema.table_constraints
                        WHERE table_name = ? AND constraint_name = ?
                    ", [$table, $constraintName]);

                    if (!empty($constraints)) {
                        continue; // Already converted
                    }

                    // PostgreSQL: ALTER TYPE (if using native enum)
                    // For safety, we convert via VARCHAR to avoid type issues
                    $tempColumn = "{$column}_new";

                    // 1. Create new VARCHAR column
                    DB::statement("ALTER TABLE {$table} ADD COLUMN {$tempColumn} VARCHAR(50)");

                    // 2. Copy data from old column (cast enum to text)
                    DB::statement("UPDATE {$table} SET {$tempColumn} = {$column}::text");

                    // 3. Drop old column
                    DB::statement("ALTER TABLE {$table} DROP COLUMN {$column}");

                    // 4. Rename new column back to original name
                    DB::statement("ALTER TABLE {$table} RENAME COLUMN {$tempColumn} TO {$column}");

                    // 5. Add CHECK constraint
                    DB::statement("
                        ALTER TABLE {$table}
                        ADD CONSTRAINT {$constraintName}
                        CHECK ({$column} IN ({$values}))
                    ");
                }
            }
        });
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::transaction(function () {
            // Drop all CHECK constraints created by this migration
            $conversions = [
                'organisations' => ['type'],
                'elections' => ['status', 'type'],
                'voter_slugs' => ['status'],
                'election_officers' => ['status', 'role'],
                'members' => ['status', 'fees_status'],
                'election_memberships' => ['status'],
                'organisation_users' => ['role', 'status'],
                'newsletter_recipients' => ['status'],
                'contributions' => ['proof_type', 'status'],
                'contribution_files' => ['status'],
                'member_import_jobs' => ['status'],
                'membership_fees' => ['status'],
                'membership_applications' => ['status'],
                'organisation_invitations' => ['status'],
                'organisation_participants' => ['status', 'role'],
            ];

            foreach ($conversions as $table => $columns) {
                foreach ($columns as $column) {
                    if (!DB::getSchemaBuilder()->hasColumn($table, $column)) {
                        continue;
                    }

                    $constraintName = "{$table}_{$column}_check";

                    // Drop CHECK constraint if it exists
                    DB::statement("
                        ALTER TABLE {$table}
                        DROP CONSTRAINT IF EXISTS {$constraintName}
                    ");
                }
            }
        });
    }
};
