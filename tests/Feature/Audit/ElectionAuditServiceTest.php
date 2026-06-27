<?php

namespace Tests\Feature\Audit;

use App\Models\Election;
use App\Models\User;
use App\Services\ElectionAuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ElectionAuditServiceTest extends TestCase
{
    use RefreshDatabase;

    private ElectionAuditService $auditService;
    private Election $election;
    private User $user;
    private string $auditBasePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->auditService = app(ElectionAuditService::class);
        $this->auditBasePath = str_replace('/', DIRECTORY_SEPARATOR, storage_path('logs/audit'));

        // Clean up before tests
        if (File::exists($this->auditBasePath)) {
            File::deleteDirectory($this->auditBasePath);
        }

        // Ensure the audit directory exists for tests
        if (!is_dir($this->auditBasePath)) {
            mkdir($this->auditBasePath, 0755, true);
        }

        $this->election = Election::factory()->create([
            'name' => 'Namaste Election',
            'slug' => 'namaste-i6g7ahfc',
            'start_date' => '2026-04-14 21:00:00',
            'type' => 'real',
        ]);

        $this->user = User::factory()->create([
            'name' => 'Niraj Adhikari',
            'email' => 'restaurant.namastenepal@gmail.com',
        ]);
    }

    protected function tearDown(): void
    {
        if (File::exists($this->auditBasePath)) {
            File::deleteDirectory($this->auditBasePath);
        }
        parent::tearDown();
    }

    /** @test */
    public function it_creates_election_folder_on_first_log(): void
    {
        $this->auditService->log(
            election: $this->election,
            event: 'voting_started',
            user: $this->user,
        );

        $expectedFolder = $this->auditBasePath . DIRECTORY_SEPARATOR . $this->election->slug . '_20260414_2100';

        $this->assertTrue(is_dir($expectedFolder), "Folder does not exist: {$expectedFolder}");
    }

    /** @test */
    public function it_writes_jsonl_entry_with_correct_format(): void
    {
        $this->auditService->log(
            election: $this->election,
            event: 'vote_cast',
            user: $this->user,
            metadata: ['post_count' => 3, 'receipt' => 'abc123'],
        );

        $logFile = $this->auditBasePath . DIRECTORY_SEPARATOR . $this->election->slug . '_20260414_2100' . DIRECTORY_SEPARATOR . 'election.jsonl';
        $this->assertTrue(file_exists($logFile), "File does not exist: {$logFile}");

        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertCount(1, $lines);

        $entry = json_decode($lines[0], true);

        $this->assertEquals('vote_cast', $entry['event']);
        $this->assertEquals($this->election->id, $entry['election_id']);
        $this->assertEquals('namaste-i6g7ahfc', $entry['election_slug']);
        $this->assertEquals($this->user->id, $entry['user_id']);
        $this->assertEquals('Niraj Adhikari', $entry['user_name']);
        $this->assertEquals(3, $entry['metadata']['post_count']);
        $this->assertArrayHasKey('timestamp', $entry);
    }

    /** @test */
    public function it_masks_email_in_logs(): void
    {
        $this->auditService->log(
            election: $this->election,
            event: 'voting_started',
            user: $this->user,
        );

        $logFile = $this->auditBasePath . DIRECTORY_SEPARATOR . $this->election->slug . '_20260414_2100' . DIRECTORY_SEPARATOR . 'election.jsonl';
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $entry = json_decode($lines[0], true);

        $this->assertEquals('r***@gmail.com', $entry['user_email']);
    }

    /** @test */
    public function it_writes_to_separate_files_by_category(): void
    {
        // Voter event
        $this->auditService->log(
            election: $this->election,
            event: 'voting_started',
            user: $this->user,
            category: 'voters',
        );

        // Committee event
        $admin = User::factory()->create(['name' => 'Admin User']);
        $this->auditService->log(
            election: $this->election,
            event: 'settings_changed',
            user: $admin,
            category: 'committee',
            metadata: ['changes' => ['ip_restriction_enabled' => ['from' => false, 'to' => true]]],
        );

        $basePath = $this->auditBasePath . DIRECTORY_SEPARATOR . $this->election->slug . '_20260414_2100';

        $this->assertTrue(file_exists($basePath . DIRECTORY_SEPARATOR . 'voters.jsonl'));
        $this->assertTrue(file_exists($basePath . DIRECTORY_SEPARATOR . 'committee.jsonl'));
        $this->assertTrue(file_exists($basePath . DIRECTORY_SEPARATOR . 'election.jsonl'));

        // Verify voters file has voter event
        $voterLines = file($basePath . DIRECTORY_SEPARATOR . 'voters.jsonl', FILE_IGNORE_NEW_LINES);
        $this->assertCount(1, $voterLines);
        $voterEntry = json_decode($voterLines[0], true);
        $this->assertEquals('voting_started', $voterEntry['event']);

        // Verify committee file has admin event
        $committeeLines = file($basePath . DIRECTORY_SEPARATOR . 'committee.jsonl', FILE_IGNORE_NEW_LINES);
        $this->assertCount(1, $committeeLines);
        $committeeEntry = json_decode($committeeLines[0], true);
        $this->assertEquals('settings_changed', $committeeEntry['event']);

        // Verify election.jsonl has both
        $electionLines = file($basePath . DIRECTORY_SEPARATOR . 'election.jsonl', FILE_IGNORE_NEW_LINES);
        $this->assertCount(2, $electionLines);
    }

    /** @test */
    public function it_captures_ip_address_when_provided(): void
    {
        $this->auditService->log(
            election: $this->election,
            event: 'voting_started',
            user: $this->user,
            ip: '192.168.1.100',
        );

        $logFile = $this->auditBasePath . DIRECTORY_SEPARATOR . $this->election->slug . '_20260414_2100' . DIRECTORY_SEPARATOR . 'election.jsonl';
        $lines = file($logFile, FILE_IGNORE_NEW_LINES);
        $entry = json_decode($lines[0], true);

        $this->assertEquals('192.168.1.100', $entry['ip']);
    }

    /** @test */
    public function it_handles_null_user_gracefully(): void
    {
        $this->auditService->log(
            election: $this->election,
            event: 'ip_blocked',
            user: null,
            ip: '10.0.0.1',
            metadata: ['reason' => 'limit_exceeded'],
        );

        $logFile = $this->auditBasePath . DIRECTORY_SEPARATOR . $this->election->slug . '_20260414_2100' . DIRECTORY_SEPARATOR . 'election.jsonl';
        $lines = file($logFile, FILE_IGNORE_NEW_LINES);
        $entry = json_decode($lines[0], true);

        $this->assertEquals('ip_blocked', $entry['event']);
        $this->assertNull($entry['user_id']);
        $this->assertEquals('10.0.0.1', $entry['ip']);
        $this->assertEquals('limit_exceeded', $entry['metadata']['reason']);
    }
}
