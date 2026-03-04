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
     * Create publicdigit (default) organisation that tests expect to exist.
     *
     * Using ID=1 for publicdigit organisation (natural auto_increment).
     * The User model boot method assigns newly registered users to this organisation.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create publicdigit organisation as ID=1 (first entry with auto_increment)
        // This is required for:
        // 1. User model boot method to find it when registering new users
        // 2. Foreign key constraints in tests
        try {
            // Check if table exists first
            if (!Schema::hasTable('organisations')) {
                return; // Table not created yet
            }

            // Create publicdigit organisation (the default org for all users)
            Organisation::firstOrCreate(
                ['slug' => 'publicdigit'],
                [
                    'name' => 'Public Digit',
                    'type' => 'platform',
                ]
            );
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to create publicdigit organisation: ' . $e->getMessage());
        }
    }
}
