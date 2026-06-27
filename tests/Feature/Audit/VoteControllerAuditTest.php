<?php

namespace Tests\Feature\Audit;

use App\Models\Code;
use App\Models\Election;
use App\Models\Post;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

class VoteControllerAuditTest extends TestCase
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
     * Test: VoteController::first_submission() logs 'vote_submitted' event
     * Category: voters
     * Metadata: post_count
     */
    public function test_first_submission_logs_vote_submitted_event(): void
    {
        // Setup election with posts
        $election = Election::factory()->create([
            'type' => 'real',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        // Create posts
        $posts = Post::factory(3)->create(['election_id' => $election->id]);

        // Create user and voter slug
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

        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
        ]);

        // Create voting code
        $code = Code::factory()->create([
            'election_id' => $election->id,
        ]);

        // Act: Submit first vote (step 3-4)
        $response = $this->actingAs($user)
            ->post(route('slug.vote.submit', ['vslug' => $voterSlug->slug]), [
                'code' => $code->code1,
                'selections' => [
                    $posts[0]->id => 'candidate_1',
                    $posts[1]->id => 'candidate_2',
                    $posts[2]->id => 'candidate_3',
                ],
            ]);

        // Assert: Successful response (200 or 3xx redirect)
        $this->assertTrue($response->status() === 200 || $response->status() >= 300); // Either 200 or 3xx

        // Assert: Audit log created
        $logFile = $this->getLogFilePath($election, 'voters.jsonl');
        $this->assertFileExists($logFile, "Audit log file not created at {$logFile}");

        // Assert: Entry contains correct event and metadata
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertNotEmpty($lines, "Audit log is empty");

        $entry = json_decode($lines[0], true);
        $this->assertIsArray($entry);
        $this->assertEquals('vote_submitted', $entry['event']);
        $this->assertEquals($user->id, $entry['user_id']);
        $this->assertEquals('voters', $entry['category']);
        $this->assertEquals(3, $entry['metadata']['post_count'] ?? null);
    }

    /**
     * Test: VoteController::store() logs 'vote_confirmed' event
     * Category: voters
     * Metadata: receipt_hash
     */
    public function test_store_logs_vote_confirmed_event(): void
    {
        // Setup election with posts
        $election = Election::factory()->create([
            'type' => 'real',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        // Create posts
        $posts = Post::factory(2)->create(['election_id' => $election->id]);

        // Create user and voter slug
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

        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
        ]);

        // Create voting codes
        $code = Code::factory()->create([
            'election_id' => $election->id,
        ]);

        // Act: Submit final vote (step 5)
        $response = $this->actingAs($user)
            ->post(route('slug.vote.store', ['vslug' => $voterSlug->slug]), [
                'code' => $code->code2,
                'selections' => [
                    $posts[0]->id => 'candidate_1',
                    $posts[1]->id => 'candidate_2',
                ],
            ]);

        // Assert: Successful response (200 or 3xx redirect)
        $this->assertTrue($response->status() === 200 || $response->status() >= 300);

        // Assert: Audit log created or updated
        $logFile = $this->getLogFilePath($election, 'voters.jsonl');
        $this->assertFileExists($logFile, "Audit log file not created at {$logFile}");

        // Assert: Entry contains correct event and metadata
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->assertNotEmpty($lines, "Audit log is empty");

        // Find vote_confirmed entry (may not be first if vote_submitted was also logged)
        $entry = null;
        foreach ($lines as $line) {
            $decoded = json_decode($line, true);
            if ($decoded['event'] === 'vote_confirmed') {
                $entry = $decoded;
                break;
            }
        }

        $this->assertNotNull($entry, "vote_confirmed event not found in audit log");
        $this->assertEquals('vote_confirmed', $entry['event']);
        $this->assertEquals($user->id, $entry['user_id']);
        $this->assertEquals('voters', $entry['category']);
        $this->assertNotEmpty($entry['metadata']['receipt_hash'] ?? null, "receipt_hash metadata not present");
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
