<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Organisation;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * Set up the test environment.
     * Create platform organisation that tests expect to exist.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create platform organisation with id=0 for system-wide data
        // This is required for foreign key constraints in tests
        try {
            Organisation::query()->firstOrCreate(
                ['id' => 0],
                [
                    'name' => 'Platform',
                    'slug' => 'platform',
                    'type' => 'other', // Must use valid enum value
                ]
            );
        } catch (\Exception $e) {
            // Silently fail if unable to create (migrations may not be ready)
        }
    }
}
