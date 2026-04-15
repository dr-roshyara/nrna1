<?php

namespace Tests\Feature\Audit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AuditCleanupTest extends TestCase
{
    use RefreshDatabase;

    private string $auditBasePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auditBasePath = storage_path('logs/audit');

        // Clean up audit directory before each test
        if (File::exists($this->auditBasePath)) {
            File::deleteDirectory($this->auditBasePath);
        }
    }

    /**
     * Test: Command deletes folders older than specified days
     */
    public function test_it_deletes_folders_older_than_specified_days(): void
    {
        // Create old folder (40 days ago)
        $oldFolder = $this->auditBasePath . DIRECTORY_SEPARATOR . 'old-election_20260301_1200';
        File::makeDirectory($oldFolder, 0755, true);

        // Set modification time to 40 days ago
        $oldTime = now()->subDays(40)->timestamp;
        touch($oldFolder, $oldTime);

        // Create recent folder (5 days ago)
        $recentFolder = $this->auditBasePath . DIRECTORY_SEPARATOR . 'recent-election_20260410_1200';
        File::makeDirectory($recentFolder, 0755, true);

        // Set modification time to 5 days ago
        $recentTime = now()->subDays(5)->timestamp;
        touch($recentFolder, $recentTime);

        // Act: Run cleanup with 30 day retention
        $this->artisan('audit:cleanup', ['--days' => 30])
            ->assertSuccessful();

        // Assert: Old folder deleted, recent folder kept
        $this->assertFalse(
            File::exists($oldFolder),
            "Old folder (40 days) should be deleted with 30-day retention"
        );
        $this->assertTrue(
            File::exists($recentFolder),
            "Recent folder (5 days) should be kept with 30-day retention"
        );
    }

    /**
     * Test: Command handles empty audit directory gracefully
     */
    public function test_it_handles_empty_audit_directory(): void
    {
        // Act: Run cleanup on empty directory
        $this->artisan('audit:cleanup')
            ->assertSuccessful()
            ->expectsOutput('No audit logs found.');
    }

    /**
     * Test: Command respects custom retention days
     */
    public function test_it_respects_custom_retention_days(): void
    {
        // Create folder 15 days old
        $folder = $this->auditBasePath . DIRECTORY_SEPARATOR . 'election-mid_20260331_1200';
        File::makeDirectory($folder, 0755, true);
        touch($folder, now()->subDays(15)->timestamp);

        // Act: Run cleanup with 10 day retention
        $this->artisan('audit:cleanup', ['--days' => 10])
            ->assertSuccessful();

        // Assert: Folder deleted (15 days > 10 day retention)
        $this->assertFalse(
            File::exists($folder),
            "Folder 15 days old should be deleted with 10-day retention"
        );
    }

    /**
     * Test: Command keeps folders within retention window
     */
    public function test_it_keeps_folders_within_retention_window(): void
    {
        // Create folder 1 day old
        $folder = $this->auditBasePath . DIRECTORY_SEPARATOR . 'election-recent_20260414_1200';
        File::makeDirectory($folder, 0755, true);
        touch($folder, now()->subDays(1)->timestamp);

        // Act: Run cleanup with 30 day retention
        $this->artisan('audit:cleanup', ['--days' => 30])
            ->assertSuccessful();

        // Assert: Folder kept
        $this->assertTrue(
            File::exists($folder),
            "Folder 1 day old should be kept with 30-day retention"
        );
    }

    /**
     * Test: Command reports correct deletion count
     */
    public function test_it_reports_deletion_count(): void
    {
        // Create 3 old folders
        for ($i = 1; $i <= 3; $i++) {
            $folder = $this->auditBasePath . DIRECTORY_SEPARATOR . "election-old-{$i}_20260301_1200";
            File::makeDirectory($folder, 0755, true);
            touch($folder, now()->subDays(40)->timestamp);
        }

        // Act: Run cleanup
        $this->artisan('audit:cleanup', ['--days' => 30])
            ->assertSuccessful()
            ->expectsOutput('Cleanup complete. 3 folder(s) deleted.');
    }

    /**
     * Test: Command works when audit directory doesn't exist yet
     */
    public function test_it_handles_nonexistent_audit_directory(): void
    {
        // Ensure directory doesn't exist
        if (File::exists($this->auditBasePath)) {
            File::deleteDirectory($this->auditBasePath);
        }

        // Act: Run cleanup on non-existent directory
        $this->artisan('audit:cleanup')
            ->assertSuccessful()
            ->expectsOutput('No audit logs found.');
    }
}
