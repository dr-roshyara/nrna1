<?php

namespace App\Models\Demo;

use App\Models\Code;

/**
 * DemoCode Model - Demo Election Codes
 *
 * Extends Code to inherit all shared voting code logic.
 * This model represents voting codes used in DEMO elections for testing.
 *
 * Table: demo_codes
 * Inheritance: DemoCode extends Code
 * Siblings: Code (for real elections)
 *
 * Demo codes are stored in a separate demo_codes table to ensure
 * complete physical and logical separation from real voting data.
 *
 * Demo codes can be reset and reused for testing purposes.
 */
class DemoCode extends Code
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demo_codes';

    /**
     * Check if this is a demo code (always true for this class)
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return true;
    }

    /**
     * Check if this is a real code (always false for this class)
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return false;
    }
}
