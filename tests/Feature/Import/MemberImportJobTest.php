<?php

namespace Tests\Feature\Import;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\MemberImportJob;
use App\Jobs\ProcessMemberImportJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class MemberImportJobTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Queue::fake();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        $this->org->users()->attach($this->admin->id, ['id' => \Illuminate\Support\Str::uuid(), 'role' => 'admin']);
    }

    // ── Controller: store ────────────────────────────────────────────────────

    public function test_it_stores_raw_file_and_returns_202_with_job_id(): void
    {
        $csv = "firstname;lastname;E-Mail\nJohn;Doe;john@example.com\nJane;Smith;jane@example.com\n";
        $file = UploadedFile::fake()->createWithContent('members.csv', $csv);

        $response = $this->actingAs($this->admin)
            ->post("/organisations/{$this->org->slug}/members/import", [
                'file' => $file,
            ]);

        $response->assertStatus(202);
        $response->assertJsonStructure(['job_id', 'status_url']);

        $jobId = $response->json('job_id');
        $this->assertDatabaseHas('member_import_jobs', [
            'id'              => $jobId,
            'organisation_id' => $this->org->id,
            'initiated_by'    => $this->admin->id,
            'status'          => 'pending',
        ]);

        Queue::assertPushed(ProcessMemberImportJob::class, function ($job) use ($jobId) {
            return $job->importJobId === $jobId;
        });
    }

    public function test_it_rejects_non_csv_files(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post("/organisations/{$this->org->slug}/members/import", [
                'file' => $file,
            ]);

        $response->assertStatus(422);
        Queue::assertNothingPushed();
    }

    public function test_it_rejects_unauthenticated_request(): void
    {
        $file = UploadedFile::fake()->createWithContent('members.csv', "email\nfoo@bar.com\n");

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post("/organisations/{$this->org->slug}/members/import", [
                'file' => $file,
            ]);

        $response->assertStatus(401);
        Queue::assertNothingPushed();
    }

    public function test_it_rejects_non_member_of_organisation(): void
    {
        $outsider = User::factory()->create(['email_verified_at' => now()]);
        $file = UploadedFile::fake()->createWithContent('members.csv', "email\nfoo@bar.com\n");

        $response = $this->actingAs($outsider)
            ->withHeaders(['Accept' => 'application/json'])
            ->post("/organisations/{$this->org->slug}/members/import", [
                'file' => $file,
            ]);

        $response->assertStatus(403);
        Queue::assertNothingPushed();
    }

    // ── Controller: status ───────────────────────────────────────────────────

    public function test_it_returns_status_for_a_pending_job(): void
    {
        $importJob = MemberImportJob::create([
            'organisation_id'   => $this->org->id,
            'initiated_by'      => $this->admin->id,
            'file_path'         => 'imports/test.csv',
            'original_filename' => 'members.csv',
            'status'            => 'pending',
            'total_rows'        => 0,
            'processed_rows'    => 0,
            'imported_count'    => 0,
            'skipped_count'     => 0,
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/organisations/{$this->org->slug}/members/import/{$importJob->id}/status");

        $response->assertStatus(200);
        $response->assertJson([
            'status'         => 'pending',
            'progress'       => 0,
            'imported_count' => 0,
        ]);
    }

    public function test_it_returns_403_for_job_belonging_to_different_organisation(): void
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
        $importJob = MemberImportJob::create([
            'organisation_id'   => $otherOrg->id,
            'initiated_by'      => $this->admin->id,
            'file_path'         => 'imports/test.csv',
            'original_filename' => 'members.csv',
            'status'            => 'pending',
            'total_rows'        => 0,
            'processed_rows'    => 0,
            'imported_count'    => 0,
            'skipped_count'     => 0,
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/organisations/{$this->org->slug}/members/import/{$importJob->id}/status");

        // firstOrFail scoped to this org returns 404 (job not found for this org)
        // 404 is actually better than 403 here — doesn't reveal the job exists
        $response->assertStatus(404);
    }

    public function test_it_returns_real_progress_percentage(): void
    {
        $importJob = MemberImportJob::create([
            'organisation_id'   => $this->org->id,
            'initiated_by'      => $this->admin->id,
            'file_path'         => 'imports/test.csv',
            'original_filename' => 'members.csv',
            'status'            => 'processing',
            'total_rows'        => 1000,
            'processed_rows'    => 500,
            'imported_count'    => 498,
            'skipped_count'     => 2,
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/organisations/{$this->org->slug}/members/import/{$importJob->id}/status");

        $response->assertStatus(200);
        $response->assertJson([
            'status'         => 'processing',
            'progress'       => 50,
            'total_rows'     => 1000,
            'processed_rows' => 500,
            'imported_count' => 498,
            'skipped_count'  => 2,
        ]);
    }
}
