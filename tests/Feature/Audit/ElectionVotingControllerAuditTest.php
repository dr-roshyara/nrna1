<?php

namespace Tests\Feature\Audit;

use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionVotingControllerAuditTest extends TestCase
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
     * Test: ElectionVotingController::start() logs 'voting_started' event
     * Category: voters
     * Metadata: none
     */
    public function test_start_logs_voting_started_event(): void
    {
        $election = Election::factory()->create([
            'type' => 'real',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $user = User::factory()->create(['organisation_id' => $election->organisation_id]);
        // User must be in user_organisation_roles before being in election_memberships
        \App\Models\UserOrganisationRole::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $election->organisation_id,
            'role' => 'voter',
        ]);
        $election->memberships()->create([
            'user_id' => $user->id,
            'organisation_id' => $election->organisation_id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        // Act: Start voting
        $response = $this->actingAs($user)
            ->post(route('elections.start', $election->slug));

        // Assert: Redirect successful
        $response->assertRedirect();

        // Assert: Audit log created
        $logFile = $this->getLogFilePath($election, 'voters.jsonl');
        $this->assertFileExists($logFile, "Audit log file not created at {$logFile}");

        // Assert: Entry contains correct event
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertNotEmpty($lines, "Audit log is empty");

        $entry = json_decode($lines[0], true);
        $this->assertIsArray($entry);
        $this->assertEquals('voting_started', $entry['event']);
        $this->assertEquals($user->id, $entry['user_id']);
        $this->assertEquals('voters', $entry['category']);
    }

    /**
     * Test: ElectionVotingController::start() logs 'ip_blocked' event with metadata
     * Category: voters
     * Metadata: reason, max
     */
    public function test_start_logs_ip_blocked_event_when_limit_reached(): void
    {
        $election = Election::factory()->create([
            'type' => 'real',
            'status' => 'active',
            'ip_restriction_enabled' => true,
            'ip_restriction_max_per_ip' => 1,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $user = User::factory()->create(['organisation_id' => $election->organisation_id]);
        // User must be in user_organisation_roles before being in election_memberships
        \App\Models\UserOrganisationRole::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $election->organisation_id,
            'role' => 'voter',
        ]);
        $election->memberships()->create([
            'user_id' => $user->id,
            'organisation_id' => $election->organisation_id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        // Create a completed vote from this IP (simulate previous voter from same IP)
        $otherUser = User::factory()->create(['organisation_id' => $election->organisation_id]);
        VoterSlug::factory()->create([
            'election_id' => $election->id,
            'user_id' => $otherUser->id,
            'organisation_id' => $election->organisation_id,
            'step_1_ip' => '192.168.1.100',
            'has_voted' => true,
        ]);

        // Act: Try to start voting from same IP (should be blocked)
        $response = $this->actingAs($user)
            ->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
            ->post(route('elections.start', $election->slug));

        // Assert: Redirect with error
        $response->assertRedirect();

        // Assert: Audit log created
        $logFile = $this->getLogFilePath($election, 'voters.jsonl');
        $this->assertFileExists($logFile, "Audit log file not created at {$logFile}");

        // Assert: Entry contains correct event and metadata
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertNotEmpty($lines, "Audit log is empty");

        $entry = json_decode($lines[0], true);
        $this->assertIsArray($entry);
        $this->assertEquals('ip_blocked', $entry['event']);
        $this->assertEquals($user->id, $entry['user_id']);
        $this->assertEquals('voters', $entry['category']);
        $this->assertEquals('limit_exceeded', $entry['metadata']['reason'] ?? null);
        $this->assertEquals(1, $entry['metadata']['max'] ?? null);
    }

    /**
     * Helper: Get the audit log file path for an election
     */
    private function getLogFilePath(Election $election, string $filename): string
    {
        $startDate = $election->start_date;
        $folderName = sprintf(
            '%s_%s_%s',
            $election->slug,
            $startDate->format('Ymd'),
            $startDate->format('Hi')
        );

        return $this->auditBasePath . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $filename;
    }
}
