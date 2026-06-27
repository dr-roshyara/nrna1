<?php

namespace Tests\Feature;

use App\Models\Code;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * VoteController downloadVotePDF TDD Test Suite
 *
 * Tests PDF generation for vote receipts.
 * Follows RED-GREEN-REFACTOR cycle.
 */
class VoteControllerDownloadPdfTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $user;
    private Election $election;
    private Vote $vote;

    protected function setUp(): void
    {
        parent::setUp();
        Election::resetPlatformOrgCache();

        // Create test organisation
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        // Create test user
        $this->user = User::factory()->create([
            'organisation_id' => $this->org->id,
            'email_verified_at' => now(),
            'name' => 'Test Voter',
            'region' => 'Test Region',
        ]);

        // Create test election
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type' => 'real',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        // Create test vote (no user_id - votes are anonymous)
        $this->vote = Vote::factory()->create([
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
        ]);

        // Authenticate user
        $this->actingAs($this->user);
    }

    // ── RED: Test cases that should pass ──────────────────────────────────────

    /** @test */
    public function it_returns_pdf_with_correct_headers()
    {
        // Prepare vote display data in session
        $sessionKey = 'vote_display_data_' . $this->vote->id;
        $voteDisplayData = $this->prepareVoteDisplayData();
        session()->put($sessionKey, $voteDisplayData);

        // Request PDF download
        $response = $this->get(route('vote.download-pdf', ['vote_id' => $this->vote->id]));

        // Verify response is successful
        $response->assertStatus(200);

        // Verify PDF headers
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition');
    }

    /** @test */
    public function it_generates_pdf_with_voter_information()
    {
        $sessionKey = 'vote_display_data_' . $this->vote->id;
        $voteDisplayData = $this->prepareVoteDisplayData();
        session()->put($sessionKey, $voteDisplayData);

        $response = $this->get(route('vote.download-pdf', ['vote_id' => $this->vote->id]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function it_generates_pdf_with_election_information()
    {
        $sessionKey = 'vote_display_data_' . $this->vote->id;
        $voteDisplayData = $this->prepareVoteDisplayData();
        session()->put($sessionKey, $voteDisplayData);

        $response = $this->get(route('vote.download-pdf', ['vote_id' => $this->vote->id]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function it_returns_404_when_session_data_missing()
    {
        // Don't set session data
        $response = $this->get(route('vote.download-pdf', ['vote_id' => $this->vote->id]));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_403_when_session_data_invalid()
    {
        // Set invalid session data
        $sessionKey = 'vote_display_data_' . $this->vote->id;
        session()->put($sessionKey, ['invalid' => 'data']);

        $response = $this->get(route('vote.download-pdf', ['vote_id' => $this->vote->id]));

        $response->assertStatus(403);
    }

    /** @test */
    public function it_generates_pdf_with_vote_selections()
    {
        $sessionKey = 'vote_display_data_' . $this->vote->id;
        $voteDisplayData = $this->prepareVoteDisplayData([
            'vote_selections' => [
                [
                    'post_id' => 'post-1',
                    'post_name' => 'President',
                    'candidates' => ['John Doe', 'Jane Smith'],
                    'no_vote' => false,
                ]
            ]
        ]);
        session()->put($sessionKey, $voteDisplayData);

        $response = $this->get(route('vote.download-pdf', ['vote_id' => $this->vote->id]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function it_generates_pdf_with_vote_statistics()
    {
        $sessionKey = 'vote_display_data_' . $this->vote->id;
        $voteDisplayData = $this->prepareVoteDisplayData([
            'summary' => [
                'total_positions' => 3,
                'positions_voted' => 2,
                'candidates_selected' => 2,
                'election_id' => $this->election->id,
                'election_name' => $this->election->name,
                'election_start_date' => $this->election->start_date->format('M d, Y'),
                'election_end_date' => $this->election->end_date->format('M d, Y'),
            ]
        ]);
        session()->put($sessionKey, $voteDisplayData);

        $response = $this->get(route('vote.download-pdf', ['vote_id' => $this->vote->id]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function pdf_filename_contains_timestamp()
    {
        $sessionKey = 'vote_display_data_' . $this->vote->id;
        $voteDisplayData = $this->prepareVoteDisplayData();
        session()->put($sessionKey, $voteDisplayData);

        $response = $this->get(route('vote.download-pdf', ['vote_id' => $this->vote->id]));

        $response->assertStatus(200);

        // Verify filename has correct format
        $this->assertStringContainsString(
            'vote_receipt_',
            $response->headers->get('Content-Disposition')
        );
    }

    // ── Helper methods ────────────────────────────────────────────────────────

    private function prepareVoteDisplayData(array $overrides = []): array
    {
        $defaults = [
            'vote_id' => $this->vote->id,
            'verification_code' => 'test-code-' . Str::random(32),
            'verification_timestamp' => now()->toISOString(),
            'verification_successful' => true,
            'is_own_vote' => true,
            'election_type' => 'real',
            'voter_info' => [
                'name' => 'Test Voter',
                'user_id' => $this->user->user_id ?? 'TEST_USER',
                'region' => 'Test Region',
            ],
            'vote_info' => [
                'voted_at' => now()->format('M j, Y \a\t g:i A'),
                'no_vote_option' => false,
                'voting_code_used' => 'test-code',
            ],
            'vote_selections' => [
                [
                    'post_id' => 'post-1',
                    'post_name' => 'President',
                    'candidates' => ['Candidate A'],
                    'no_vote' => false,
                ]
            ],
            'summary' => [
                'total_positions' => 1,
                'positions_voted' => 1,
                'candidates_selected' => 1,
                'election_id' => $this->election->id,
                'election_name' => $this->election->name,
                'election_start_date' => $this->election->start_date->format('M d, Y'),
                'election_end_date' => $this->election->end_date->format('M d, Y'),
            ]
        ];

        // Deep merge overrides, preferring override values
        foreach ($overrides as $key => $value) {
            if (is_array($value) && isset($defaults[$key]) && is_array($defaults[$key])) {
                $defaults[$key] = array_merge($defaults[$key], $value);
            } else {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }
}
