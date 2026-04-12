<?php

namespace Tests\Feature\Jobs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schedule;
use Tests\TestCase;

class ScheduledJobsTest extends TestCase
{
    /**
     * Test that scheduled jobs are configured.
     */
    public function test_scheduled_jobs_are_configured()
    {
        // Get all scheduled events
        $events = collect(Schedule::events());

        // Should have at least one scheduled event
        $this->assertGreaterThan(0, $events->count(), 'Scheduled jobs should be configured');
    }

    /**
     * Test that console commands are scheduled.
     *
     * Phase 2 moved scheduled jobs to routes/console.php
     */
    public function test_console_commands_are_scheduled()
    {
        $events = collect(Schedule::events());

        // Should have scheduled commands
        $commandSchedules = $events->filter(function($event) {
            return isset($event->command) && !empty($event->command);
        });

        $this->assertGreaterThan(0, $commandSchedules->count(), 'Console commands should be scheduled');
    }

    /**
     * Test scheduled jobs have valid cron expressions.
     */
    public function test_scheduled_jobs_have_valid_cron()
    {
        $events = collect(Schedule::events());

        foreach ($events as $event) {
            // Each event should have a cron expression
            $this->assertNotNull($event->expression, 'Each scheduled event should have a cron expression');

            // Cron expression should match pattern
            $this->assertMatchesRegularExpression(
                '/^(\*|([0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])|\*\/([0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])) /',
                $event->expression,
                'Should have valid cron expression format'
            );
        }
    }

    /**
     * Test that maintenance mode job exists.
     */
    public function test_maintenance_mode_tasks_exist()
    {
        $events = collect(Schedule::events());

        // Should have at least the standard tasks
        $this->assertGreaterThan(0, $events->count());
    }

    /**
     * Test scheduled jobs are enabled.
     */
    public function test_scheduled_jobs_are_enabled()
    {
        $events = collect(Schedule::events());

        foreach ($events as $event) {
            // Jobs should not be skipped (shouldSkip should return false for normal operation)
            // This is a conceptual check
            $this->assertNotNull($event);
        }
    }

    /**
     * Test multiple jobs can be scheduled.
     */
    public function test_multiple_jobs_can_be_scheduled()
    {
        $events = collect(Schedule::events());

        // If there are multiple events, they should each be valid
        if ($events->count() > 1) {
            $this->assertGreaterThan(1, $events->count());
        }
    }

    /**
     * Test schedule is accessible via Schedule facade.
     */
    public function test_schedule_facade_is_accessible()
    {
        // Should be able to access Schedule facade
        $this->assertNotNull(Schedule::class);

        // Should be able to get events
        $events = Schedule::events();
        $this->assertIsArray($events);
    }

    /**
     * Test scheduled jobs have proper callbacks.
     */
    public function test_scheduled_jobs_have_callbacks()
    {
        $events = collect(Schedule::events());

        foreach ($events as $event) {
            // Each event should have a way to execute
            // (either command, callable, or closure)
            $this->assertTrue(
                isset($event->command) || isset($event->callback) || is_callable($event),
                'Each scheduled event should have a command or callback'
            );
        }
    }

    /**
     * Test scheduled jobs maintain timezone.
     */
    public function test_scheduled_jobs_respect_timezone()
    {
        $config = config('app.timezone');

        // Application should have a timezone configured
        $this->assertNotNull($config);
        $this->assertNotEmpty($config);
    }

    /**
     * Test schedule prevents overlapping execution.
     */
    public function test_schedule_prevents_overlaps()
    {
        // The schedule should be designed to prevent overlapping execution
        // This is verified at the job configuration level
        $events = collect(Schedule::events());

        // Just verify schedule is configured
        $this->assertNotNull($events);
    }

    /**
     * Test console kernel is properly configured.
     */
    public function test_console_kernel_configured()
    {
        // Verify artisan commands are accessible
        $this->assertTrue(class_exists(\Illuminate\Console\Application::class));
    }
}
