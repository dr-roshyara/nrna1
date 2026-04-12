<?php

namespace Tests\Feature\Demo;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test the voting system configuration
 */
class VotingConfigurationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that config file exists
     */
    public function test_voting_config_file_exists()
    {
        $this->assertTrue(file_exists(config_path('voting.php')));
    }

    /**
     * Test default configuration is SIMPLE MODE
     */
    public function test_default_mode_is_simple()
    {
        $this->assertEquals(0, config('voting.two_codes_system'));
    }

    /**
     * Test SIMPLE MODE configuration
     */
    public function test_simple_mode_configuration()
    {
        config(['voting.two_codes_system' => 0]);
        $this->assertEquals(0, config('voting.two_codes_system'));
        // is_strict should be false when mode is 0
        $isStrict = config('voting.two_codes_system') == 1;
        $this->assertFalse($isStrict);
    }

    /**
     * Test STRICT MODE configuration
     */
    public function test_strict_mode_configuration()
    {
        config(['voting.two_codes_system' => 1]);
        $this->assertEquals(1, config('voting.two_codes_system'));
        // is_strict should be true when mode is 1
        $isStrict = config('voting.two_codes_system') == 1;
        $this->assertTrue($isStrict);
    }

    /**
     * Test that configuration can be set and retrieved
     */
    public function test_configuration_retrieval()
    {
        // Test reading default
        $defaultMode = config('voting.two_codes_system');
        $this->assertEquals(0, $defaultMode);

        // Test setting different value
        config(['voting.two_codes_system' => 1]);
        $this->assertEquals(1, config('voting.two_codes_system'));

        // Reset to default
        config(['voting.two_codes_system' => 0]);
        $this->assertEquals(0, config('voting.two_codes_system'));
    }
}
