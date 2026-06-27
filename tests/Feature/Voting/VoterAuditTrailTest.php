<?php

namespace Tests\Feature\Voting;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Services\ElectionAuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class VoterAuditTrailTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $voter;
    private ElectionAuditService $auditService;
    private string $auditBasePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create();
        $this->voter = User::factory()->create(['name' => 'John Doe']);
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'slug' => 'test-election-2026',
            'start_date' => now(),
        ]);

        $this->auditService = app(ElectionAuditService::class);
        $this->auditBasePath = storage_path('logs/audit');
    }

    protected function tearDown(): void
    {
        // Clean up test audit files
        if (File::exists($this->auditBasePath)) {
            File::deleteDirectory($this->auditBasePath);
        }
        parent::tearDown();
    }

    /** @test */
    public function per_voter_audit_file_is_created(): void
    {
        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 1,
            action: 'code_verified',
            metadata: ['code_id' => 'test-code-uuid']
        );

        // Find the audit folder
        $folders = File::directories($this->auditBasePath);
        $this->assertNotEmpty($folders, 'Audit folder should be created');

        $voterFile = $folders[0] . '/voters/John_Doe.jsonl';
        $this->assertFileExists($voterFile, 'Per-voter file should exist');

        $content = File::get($voterFile);
        $lines = array_filter(explode("\n", $content));
        $this->assertCount(1, $lines, 'Should have one log entry');

        $entry = json_decode($lines[0], true);
        $this->assertEquals(1, $entry['step']);
        $this->assertEquals('code_verified', $entry['action']);
        $this->assertEquals($this->voter->name, $entry['voter_name']);
    }

    /** @test */
    public function per_voter_file_records_multiple_steps(): void
    {
        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 1,
            action: 'code_verified'
        );

        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 2,
            action: 'agreement_accepted'
        );

        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 3,
            action: 'vote_submitted',
            metadata: ['post_count' => 3]
        );

        $folders = File::directories($this->auditBasePath);
        $voterFile = $folders[0] . '/voters/John_Doe.jsonl';

        $content = File::get($voterFile);
        $lines = array_filter(explode("\n", $content));
        $this->assertCount(3, $lines, 'Should have three log entries');

        $entries = array_map(fn ($l) => json_decode($l, true), $lines);
        $this->assertEquals([1, 2, 3], array_column($entries, 'step'));
        $this->assertEquals(
            ['code_verified', 'agreement_accepted', 'vote_submitted'],
            array_column($entries, 'action')
        );
    }

    /** @test */
    public function voter_email_is_masked_in_audit(): void
    {
        $this->voter->update(['email' => 'john.doe@example.com']);

        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 1,
            action: 'code_verified'
        );

        $folders = File::directories($this->auditBasePath);
        $voterFile = $folders[0] . '/voters/John_Doe.jsonl';
        $content = File::get($voterFile);
        $entry = json_decode(explode("\n", $content)[0], true);

        $this->assertStringContainsString('***@', $entry['voter_email']);
        $this->assertStringNotContainsString('john.doe', $entry['voter_email']);
    }

    /** @test */
    public function voter_name_with_special_chars_creates_safe_filename(): void
    {
        $this->voter->update(['name' => 'Jöhn Döe / Müller']);

        $this->auditService->logVoterAction(
            election: $this->election,
            voter: $this->voter,
            step: 1,
            action: 'code_verified'
        );

        $folders = File::directories($this->auditBasePath);
        $votersDir = $folders[0] . '/voters';
        $files = File::files($votersDir);

        $this->assertCount(1, $files);
        $filename = $files[0]->getFilename();
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9_\-]+\.jsonl$/', $filename, 'Filename should be sanitized');
    }

    /** @test */
    public function audit_trail_spans_entire_voting_flow(): void
    {
        $steps = [
            ['step' => 1, 'action' => 'code_created', 'metadata' => []],
            ['step' => 1, 'action' => 'code_verified', 'metadata' => ['code_id' => 'abc']],
            ['step' => 2, 'action' => 'agreement_accepted', 'metadata' => []],
            ['step' => 3, 'action' => 'vote_submitted', 'metadata' => ['post_count' => 2]],
            ['step' => 4, 'action' => 'vote_verified', 'metadata' => []],
            ['step' => 5, 'action' => 'vote_confirmed', 'metadata' => ['receipt_hash' => 'abc123']],
        ];

        foreach ($steps as $s) {
            $this->auditService->logVoterAction(
                election: $this->election,
                voter: $this->voter,
                step: $s['step'],
                action: $s['action'],
                metadata: $s['metadata']
            );
        }

        $folders = File::directories($this->auditBasePath);
        $voterFile = $folders[0] . '/voters/John_Doe.jsonl';
        $content = File::get($voterFile);
        $lines = array_filter(explode("\n", $content));

        $this->assertCount(6, $lines, 'Should record all 6 voting steps');
    }

    /** @test */
    public function admin_can_download_voter_audit_file(): void
    {
        $this->auditService->logVoterAction(
            election: $this->election, voter: $this->voter,
            step: 1, action: 'code_verified'
        );

        $admin = User::factory()->create(['is_super_admin' => true]);

        $folders = File::directories($this->auditBasePath);
        $folderName = basename($folders[0]);

        $response = $this->actingAs($admin)
            ->get("/platform/elections/{$this->election->slug}/audit/{$folderName}/voters/John_Doe.jsonl");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/octet-stream');
    }
}
