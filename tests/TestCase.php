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
     * Set up the test environment.
     * Create platform organisation that tests expect to exist.
     *
     * Using ID=1 for platform organisation (natural auto_increment).
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create platform organisation as ID=1 (first entry with auto_increment)
        // This is required for foreign key constraints in tests
        try {
            // Check if table exists first
            if (!Schema::hasTable('organisations')) {
                return; // Table not created yet
            }

            // Create or get platform organisation
            Organisation::firstOrCreate(
                ['slug' => 'platform'],
                [
                    'name' => 'Platform',
                    'type' => 'other',
                ]
            );
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to create platform organisation: ' . $e->getMessage());
        }
    }
}
