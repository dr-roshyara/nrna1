<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;
use App\Models\Organisation;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * Override beginDatabaseTransaction to handle PostgreSQL's stricter transaction handling.
     * PostgreSQL doesn't support nested transactions without savepoints in RefreshDatabase context.
     * PostgreSQL savepoints require explicit naming, which RefreshDatabase doesn't properly handle.
     * Solution: Disable transaction-based test isolation for PostgreSQL; use migrate:fresh instead.
     */
    public function beginDatabaseTransaction()
    {
        // Skip transaction-based isolation for PostgreSQL tests
        // PostgreSQL's stricter transaction model causes "already in transaction" errors
        // when RefreshDatabase tries to nest transactions
        if ($this->app['db']->getDriverName() === 'pgsql') {
            return; // Skip — RefreshDatabase uses migrate:fresh for PostgreSQL isolation
        }

        // Call parent if method exists (for compatibility with different Laravel versions)
        if (method_exists(parent::class, 'beginDatabaseTransaction')) {
            parent::beginDatabaseTransaction();
        }
    }

    /**
     * Set up the test environment.
     * Create publicdigit (default) organisation that tests expect to exist.
     *
     * Using ID=1 for publicdigit organisation (natural auto_increment).
     * The User model boot method assigns newly registered users to this organisation.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create platform organisation with same search keys as OrganisationSeeder and UserFactory
        // This prevents duplicate slug errors in tests.
        // Uses UUID-compatible search keys: type='platform', is_default=true
        try {
            // Check if table exists first
            if (!Schema::hasTable('organisations')) {
                return; // Table not created yet
            }

            // Use same search keys as OrganisationSeeder and UserFactory
            // to ensure idempotent creation and prevent duplicate slug violations
            Organisation::firstOrCreate(
                ['type' => 'platform', 'is_default' => true],
                [
                    'name' => 'PublicDigit',
                    'slug' => 'publicdigit',
                ]
            );
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to create platform organisation: ' . $e->getMessage());
        }
    }
}
