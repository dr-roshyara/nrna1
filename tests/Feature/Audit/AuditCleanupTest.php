<?php

namespace Tests\Feature\Audit;

use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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
     * An election whose Evidence Preservation Window has long closed.
     *
     * The window is Contestation Window + Maximum Adjudication Duration + Legal
     * Safety Margin measured from the anchor; an anchor 400 days back is past every
     * interim bootstrap. Fixtures that assert DELETION need one, because under
     * Policy 2 an unresolvable folder is retained.
     */
    private function electionWithClosedWindow(string $slug): Election
    {
        return Election::factory()->create([
            'slug' => $slug,
            'end_date' => Carbon::now()->subDays(400),
            'results_published_at' => null,
            'archived_at' => null,
        ]);
    }

    /** An election whose window is still open — the anchor is now. */
    private function electionWithOpenWindow(string $slug): Election
    {
        return Election::factory()->create([
            'slug' => $slug,
            'end_date' => Carbon::now(),
            'results_published_at' => null,
            'archived_at' => null,
        ]);
    }

    private function makeAuditFolder(string $slug, int $ageInDays): string
    {
        $folder = $this->auditBasePath . DIRECTORY_SEPARATOR . $slug . '_20260301_1200';
        File::makeDirectory($folder, 0755, true);
        touch($folder, Carbon::now()->subDays($ageInDays)->timestamp);

        return $folder;
    }

    // ---------------------------------------------------------------------
    // Slice 7C — the retention guard (Constitutional Policy 2)
    // ---------------------------------------------------------------------

    /**
     * Test: a folder whose election's preservation window is still open is retained,
     * however old the folder is.
     */
    public function test_it_retains_a_folder_whose_preservation_window_is_open(): void
    {
        $this->electionWithOpenWindow('guard-open');
        $folder = $this->makeAuditFolder('guard-open', 40);

        $this->artisan('audit:cleanup', ['--days' => 30])->assertSuccessful();

        $this->assertTrue(
            File::exists($folder),
            'Evidence inside an open Evidence Preservation Window must never be deleted.'
        );
    }

    /**
     * Test: once the window has closed the guard releases — it is a guard, not a freeze.
     */
    public function test_it_deletes_a_folder_whose_preservation_window_has_closed(): void
    {
        $this->electionWithClosedWindow('guard-closed');
        $folder = $this->makeAuditFolder('guard-closed', 40);

        $this->artisan('audit:cleanup', ['--days' => 30])->assertSuccessful();

        $this->assertFalse(
            File::exists($folder),
            'Deletion must resume once the Evidence Preservation Window has closed.'
        );
    }

    /**
     * Test: a folder that cannot be mapped to an election is retained — fail closed.
     */
    public function test_it_retains_a_folder_that_cannot_be_mapped_to_an_election(): void
    {
        $folder = $this->makeAuditFolder('no-such-election', 40);

        $this->artisan('audit:cleanup', ['--days' => 30])->assertSuccessful();

        $this->assertTrue(
            File::exists($folder),
            'An unresolvable folder-to-election mapping must not be deleted.'
        );
    }

    /**
     * Test: the age cutoff can no longer override the retention invariant.
     */
    public function test_days_option_no_longer_overrides_the_retention_invariant(): void
    {
        $this->electionWithOpenWindow('guard-days');
        $folder = $this->makeAuditFolder('guard-days', 400);

        $this->artisan('audit:cleanup', ['--days' => 1])->assertSuccessful();

        $this->assertTrue(
            File::exists($folder),
            'A --days cutoff must not delete evidence inside an open window.'
        );
    }

    // ---------------------------------------------------------------------
    // Pre-existing behaviour — fixtures amended under R-65 so that folders
    // asserting DELETION resolve to an election with a closed window. No
    // assertion is weakened and no test is removed.
    // ---------------------------------------------------------------------

    /**
     * Test: Command deletes folders older than specified days
     */
    public function test_it_deletes_folders_older_than_specified_days(): void
    {
        $this->electionWithClosedWindow('old-election');
        $this->electionWithClosedWindow('recent-election');

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
        $this->electionWithClosedWindow('election-mid');

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
            $this->electionWithClosedWindow("election-old-{$i}");

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
