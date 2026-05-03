<?php

namespace App\Contexts\Membership\Infrastructure\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Membership Module Database Seeder
 *
 * Seeds the Membership context for a tenant database.
 * Called during module installation for new tenants.
 *
 * Responsibilities:
 * - Run membership migrations (members table)
 * - Seed default data if needed
 * - Idempotent (safe to run multiple times)
 *
 * @package Membership
 */
class MembershipDatabaseSeeder extends Seeder
{
    /**
     * Run the membership database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Log::info('Starting Membership module database seeding', [
            'database' => DB::connection()->getDatabaseName(),
        ]);

        try {
            // Step 1: Run Membership migrations
            $this->runMembershipMigrations();

            // Step 2: Seed default data (if needed)
            $this->seedDefaultData();

            Log::info('Membership module database seeding completed successfully');
        } catch (\Exception $e) {
            Log::error('Membership module database seeding failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Run membership migrations for the tenant database.
     *
     * UPDATED: Now uses correct Tenant/ subfolder path following Platform Context convention.
     *
     * @return void
     */
    protected function runMembershipMigrations(): void
    {
        // FIXED: Use Tenant/ subfolder path (migrations were reorganized in Platform Context integration)
        $migrationPath = 'app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant';
        $database = DB::connection()->getDatabaseName();

        Log::info('Running membership migrations', [
            'path' => $migrationPath,
            'database' => $database,
            'connection' => config('database.default'),
        ]);

        // Run migrations for Membership context with explicit database connection
        Artisan::call('migrate', [
            '--path' => $migrationPath,
            '--database' => 'tenant_install',  // Use the connection set in InstallMembershipModule
            '--force' => true,
        ]);

        $output = Artisan::output();
        Log::info('Membership migrations output', ['output' => $output]);
    }

    /**
     * Seed default data for Membership module.
     *
     * In the future, this could seed:
     * - Default committee types
     * - Default member statuses
     * - Default forum categories
     * - Default levy types
     *
     * For now, we keep it minimal - the members table starts empty.
     *
     * @return void
     */
    protected function seedDefaultData(): void
    {
        Log::info('Seeding default membership data');

        // Check if members table exists
        if (!$this->tableExists('members')) {
            Log::warning('Members table does not exist, skipping default data seeding');
            return;
        }

        // For now, no default data needed
        // Members table starts empty - populated by MemberRegistrationService

        Log::info('Default membership data seeding completed');
    }

    /**
     * Check if a table exists in the current database.
     *
     * @param string $tableName
     * @return bool
     */
    protected function tableExists(string $tableName): bool
    {
        $connection = DB::connection();
        $database = $connection->getDatabaseName();

        $result = $connection->select(
            "SELECT COUNT(*) as count
             FROM information_schema.tables
             WHERE table_schema = ?
             AND table_name = ?",
            [$database, $tableName]
        );

        return $result[0]->count > 0;
    }
}
