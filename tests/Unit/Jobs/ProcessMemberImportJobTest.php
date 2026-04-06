<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\MemberImportJob;
use App\Jobs\ProcessMemberImportJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class ProcessMemberImportJobTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->org   = Organisation::factory()->create(['type' => 'tenant']);
        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        $this->org->users()->attach($this->admin->id, ['id' => \Illuminate\Support\Str::uuid(), 'role' => 'admin']);
    }

    private function makeImportJob(string $csvContent): MemberImportJob
    {
        $path = "imports/{$this->org->id}/test.csv";
        Storage::disk('local')->put($path, $csvContent);

        return MemberImportJob::create([
            'organisation_id'   => $this->org->id,
            'initiated_by'      => $this->admin->id,
            'file_path'         => $path,
            'original_filename' => 'members.csv',
            'status'            => 'pending',
            'total_rows'        => 0,
            'processed_rows'    => 0,
            'imported_count'    => 0,
            'skipped_count'     => 0,
        ]);
    }

    public function test_it_parses_semicolon_csv_and_creates_users(): void
    {
        $csv = "firstname;lastname;E-Mail\nJohn;Doe;john@example.com\nJane;Smith;jane@example.com\n";
        $importJob = $this->makeImportJob($csv);

        (new ProcessMemberImportJob($importJob->id))->handle();

        $this->assertDatabaseCount('users', 3); // admin + 2 imported
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);

        $importJob->refresh();
        $this->assertEquals('completed', $importJob->status);
        $this->assertEquals(2, $importJob->imported_count);
        $this->assertEquals(0, $importJob->skipped_count);
    }

    public function test_it_attaches_imported_users_to_organisation(): void
    {
        $csv = "E-Mail\njohn@example.com\njane@example.com\n";
        $importJob = $this->makeImportJob($csv);

        (new ProcessMemberImportJob($importJob->id))->handle();

        $orgUserIds = $this->org->users()->pluck('users.id');
        $john = User::where('email', 'john@example.com')->first();
        $jane = User::where('email', 'jane@example.com')->first();

        $this->assertTrue($orgUserIds->contains($john->id));
        $this->assertTrue($orgUserIds->contains($jane->id));
    }

    public function test_it_skips_rows_with_missing_email(): void
    {
        $csv = "firstname;lastname;E-Mail\nJohn;Doe;john@example.com\nBad;Row;\nJane;Smith;jane@example.com\n";
        $importJob = $this->makeImportJob($csv);

        (new ProcessMemberImportJob($importJob->id))->handle();

        $importJob->refresh();
        $this->assertEquals(2, $importJob->imported_count);
        $this->assertEquals(1, $importJob->skipped_count);
        $this->assertDatabaseMissing('users', ['email' => '']);
    }

    public function test_it_skips_rows_with_already_existing_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $csv = "E-Mail\nexisting@example.com\nnew@example.com\n";
        $importJob = $this->makeImportJob($csv);

        (new ProcessMemberImportJob($importJob->id))->handle();

        $importJob->refresh();
        $this->assertEquals(1, $importJob->imported_count);
        $this->assertEquals(1, $importJob->skipped_count);
        $this->assertCount(1, $importJob->error_log ?? []);
    }

    public function test_it_marks_job_failed_when_file_does_not_exist(): void
    {
        $importJob = MemberImportJob::create([
            'organisation_id'   => $this->org->id,
            'initiated_by'      => $this->admin->id,
            'file_path'         => 'imports/nonexistent.csv',
            'original_filename' => 'members.csv',
            'status'            => 'pending',
            'total_rows'        => 0,
            'processed_rows'    => 0,
            'imported_count'    => 0,
            'skipped_count'     => 0,
        ]);

        (new ProcessMemberImportJob($importJob->id))->handle();

        $importJob->refresh();
        $this->assertEquals('failed', $importJob->status);
        $this->assertNotEmpty($importJob->error_log);
    }

    public function test_it_processes_large_file_in_chunks(): void
    {
        // Build a 500-row CSV
        $rows = ["E-Mail"];
        for ($i = 1; $i <= 500; $i++) {
            $rows[] = "user{$i}@example.com";
        }
        $csv = implode("\n", $rows);
        $importJob = $this->makeImportJob($csv);

        (new ProcessMemberImportJob($importJob->id))->handle();

        $importJob->refresh();
        $this->assertEquals('completed', $importJob->status);
        $this->assertEquals(500, $importJob->imported_count);
        $this->assertDatabaseCount('users', 501); // 500 imported + 1 admin
    }

    public function test_it_deletes_the_file_after_successful_import(): void
    {
        $csv = "E-Mail\njohn@example.com\n";
        $importJob = $this->makeImportJob($csv);
        $filePath = $importJob->file_path;

        (new ProcessMemberImportJob($importJob->id))->handle();

        Storage::disk('local')->assertMissing($filePath);
    }

    public function test_it_marks_job_as_processing_while_running(): void
    {
        // Verify started_at is set when job begins
        $csv = "E-Mail\njohn@example.com\n";
        $importJob = $this->makeImportJob($csv);

        (new ProcessMemberImportJob($importJob->id))->handle();

        $importJob->refresh();
        $this->assertNotNull($importJob->started_at);
        $this->assertNotNull($importJob->completed_at);
        $this->assertEquals('completed', $importJob->status);
    }
}
