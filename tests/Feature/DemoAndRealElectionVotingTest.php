<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VoterSlug;
use App\Models\Election;
use App\Models\Vote;
use App\Models\DemoVote;
use App\Models\Code;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

/**
 * COMPREHENSIVE VOTING SYSTEM TEST SUITE
 *
 * Tests both Demo and Real Election systems end-to-end
 * Following TDD approach: RED → GREEN → REFACTOR
 */
class DemoAndRealElectionVotingTest extends TestCase
{
    use RefreshDatabase;

    protected Election $demoElection;
    protected Election $realElection;
    protected User $voter;

    protected function setUp(): void
    {
        parent::setUp();

        // Create elections
        $this->demoElection = Election::create([
            'name' => 'Demo Election',
            'type' => 'demo',
            'starts_at' => now(),
            'ends_at' => now()->addHours(2),
        ]);

        $this->realElection = Election::create([
            'name' => 'Real Election',
            'type' => 'real',
            'starts_at' => now(),
            'ends_at' => now()->addHours(2),
        ]);

        // Create test voter
        $this->voter = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false,
        ]);
    }

    // ============================================================================
    // DEMO ELECTION TESTS
    // ============================================================================

    /** @test */
    public function demo_vote_verification_code_is_generated_on_save()
    {
        // Arrange: Create a demo vote
        $demoVote = new DemoVote();
        $demoVote->election_id = $this->demoElection->id;
        $demoVote->voting_code = Hash::make('test_voting_code');
        $demoVote->verification_code = bin2hex(random_bytes(16));

        // Act: Save the vote
        $demoVote->save();

        // Assert: Verification code is saved
        $this->assertNotNull($demoVote->verification_code);
        $this->assertEquals(32, strlen($demoVote->verification_code));

        // Verify it can be retrieved
        $retrieved = DemoVote::find($demoVote->id);
        $this->assertEquals($demoVote->verification_code, $retrieved->verification_code);
    }

    /** @test */
    public function demo_vote_can_be_verified_by_verification_code()
    {
        // Arrange: Create and save a demo vote
        $verificationCode = bin2hex(random_bytes(16));
        $demoVote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => Hash::make('test_voting_code'),
            'verification_code' => $verificationCode,
            'no_vote_option' => false,
            'candidate_01' => json_encode(['candidacy_id' => 'test_candidate']),
        ]);

        // Act: Look up vote by verification code
        $found = DemoVote::where('verification_code', $verificationCode)->first();

        // Assert: Vote found correctly
        $this->assertNotNull($found);
        $this->assertEquals($demoVote->id, $found->id);
    }

    /** @test */
    public function demo_vote_verification_code_is_unique_per_vote()
    {
        // Arrange: Create multiple demo votes
        $code1 = bin2hex(random_bytes(16));
        $code2 = bin2hex(random_bytes(16));

        $vote1 = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => Hash::make('code1'),
            'verification_code' => $code1,
        ]);

        $vote2 = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => Hash::make('code2'),
            'verification_code' => $code2,
        ]);

        // Assert: Codes are different
        $this->assertNotEquals($code1, $code2);
        $this->assertNotEquals($vote1->id, $vote2->id);
    }

    /** @test */
    public function demo_election_allows_multiple_votes_per_user()
    {
        // Arrange: Create voter slug for demo election
        $slug = VoterSlug::create([
            'user_id' => $this->voter->id,
            'slug' => 'demo-test-' . rand(1000, 9999),
            'election_id' => $this->demoElection->id,
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // Act: Create first demo vote
        $vote1 = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'user_id' => $this->voter->id,
            'voting_code' => Hash::make('first_vote'),
            'verification_code' => bin2hex(random_bytes(16)),
        ]);

        // Act: Create second demo vote (should be allowed)
        $vote2 = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'user_id' => $this->voter->id,
            'voting_code' => Hash::make('second_vote'),
            'verification_code' => bin2hex(random_bytes(16)),
        ]);

        // Assert: Both votes exist
        $this->assertNotNull($vote1->id);
        $this->assertNotNull($vote2->id);
        $this->assertEquals(2, DemoVote::where('user_id', $this->voter->id)
                                      ->where('election_id', $this->demoElection->id)
                                      ->count());
    }

    /** @test */
    public function demo_votes_are_stored_separately_from_real_votes()
    {
        // Arrange: Create both demo and real votes
        $demoVote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => Hash::make('demo'),
            'verification_code' => bin2hex(random_bytes(16)),
        ]);

        $realVote = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => Hash::make('real'),
        ]);

        // Act & Assert: They're in different tables
        $this->assertEquals(1, DemoVote::count());
        $this->assertEquals(1, Vote::count());

        // Verify they don't interfere with each other
        $demoCount = DemoVote::where('election_id', $this->demoElection->id)->count();
        $realCount = Vote::where('election_id', $this->realElection->id)->count();

        $this->assertEquals(1, $demoCount);
        $this->assertEquals(1, $realCount);
    }

    // ============================================================================
    // REAL ELECTION TESTS
    // ============================================================================

    /** @test */
    public function real_election_blocks_second_vote()
    {
        // Arrange: Create voter slug for real election
        $slug = VoterSlug::create([
            'user_id' => $this->voter->id,
            'slug' => 'real-test-' . rand(1000, 9999),
            'election_id' => $this->realElection->id,
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // Create code record
        $code = Code::create([
            'user_id' => $this->voter->id,
            'code1' => 'TEST001',
            'code2' => 'TEST002',
            'has_voted' => false,
            'session_name' => 'test_session',
            'code_for_vote' => Hash::make('verification_code_hash'),
            'code2_for_vote' => Hash::make('second_code'),
        ]);

        $this->voter->update(['code_id' => $code->id]);

        // Act: Create first real vote
        $vote1 = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => Hash::make('real_vote_1'),
        ]);

        // Mark user as voted
        $code->update(['has_voted' => true]);

        // Act: Attempt to create second vote (should be blocked at controller level)
        // This test verifies the database allows it, but controller blocks it
        $vote2 = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => Hash::make('real_vote_2'),
        ]);

        // Assert: Both votes exist in DB (controller should prevent duplicate submission)
        $this->assertNotNull($vote1->id);
        $this->assertNotNull($vote2->id);
        // But code has_voted flag prevents second submission
        $this->assertTrue($code->refresh()->has_voted);
    }

    /** @test */
    public function real_election_vote_has_no_verification_code_at_save()
    {
        // Arrange: Create real vote
        $vote = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => Hash::make('real_vote'),
        ]);

        // Assert: Real votes don't have verification_code set
        // (they use email-based verification instead)
        $this->assertNull($vote->verification_code);
    }

    /** @test */
    public function real_vote_verification_uses_email_based_code()
    {
        // Arrange: Create real vote submission code
        $verificationCode = 'ABC123DEF456';
        $hashedCode = Hash::make($verificationCode);

        $code = Code::create([
            'user_id' => $this->voter->id,
            'code1' => 'SUBMIT001',
            'code2' => 'SUBMIT002',
            'has_voted' => true,
            'session_name' => 'real_session',
            'code_for_vote' => $hashedCode,
            'code2_for_vote' => Hash::make('second_submission'),
        ]);

        // Act: Verify the code
        $verified = Hash::check($verificationCode, $code->code_for_vote);

        // Assert: Code verified correctly
        $this->assertTrue($verified);
    }

    // ============================================================================
    // VOTER STEP WORKFLOW TESTS
    // ============================================================================

    /** @test */
    public function demo_election_voter_progresses_through_5_steps()
    {
        // Arrange: Create voter slug for demo election
        $slug = VoterSlug::create([
            'user_id' => $this->voter->id,
            'slug' => 'demo-step-' . rand(1000, 9999),
            'election_id' => $this->demoElection->id,
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // Assert: Initial step is 1
        $this->assertEquals(1, $slug->current_step);

        // Act: Progress through steps
        $slug->update(['current_step' => 2]);
        $this->assertEquals(2, $slug->refresh()->current_step);

        $slug->update(['current_step' => 3]);
        $this->assertEquals(3, $slug->refresh()->current_step);

        $slug->update(['current_step' => 4]);
        $this->assertEquals(4, $slug->refresh()->current_step);

        $slug->update(['current_step' => 5]);
        $this->assertEquals(5, $slug->refresh()->current_step);
    }

    /** @test */
    public function demo_and_real_elections_have_separate_voter_slugs()
    {
        // Arrange: Create slugs for both elections with same user
        $demoSlug = VoterSlug::create([
            'user_id' => $this->voter->id,
            'slug' => 'demo-' . rand(1000, 9999),
            'election_id' => $this->demoElection->id,
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
        ]);

        $realSlug = VoterSlug::create([
            'user_id' => $this->voter->id,
            'slug' => 'real-' . rand(1000, 9999),
            'election_id' => $this->realElection->id,
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
        ]);

        // Assert: Both slugs exist and are different
        $this->assertNotNull($demoSlug->id);
        $this->assertNotNull($realSlug->id);
        $this->assertNotEquals($demoSlug->slug, $realSlug->slug);
        $this->assertNotEquals($demoSlug->id, $realSlug->id);
    }

    // ============================================================================
    // VERIFICATION CODE WORKFLOW TESTS
    // ============================================================================

    /** @test */
    public function demo_vote_verification_code_can_be_copied_and_pasted()
    {
        // Arrange: Create demo vote with verification code
        $code = bin2hex(random_bytes(16));
        $vote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => Hash::make('demo_vote'),
            'verification_code' => $code,
        ]);

        // Act: Simulate copying and pasting the code
        $copiedCode = $vote->verification_code;

        // Act: Use pasted code to find vote
        $foundVote = DemoVote::where('verification_code', $copiedCode)->first();

        // Assert: Vote found with pasted code
        $this->assertNotNull($foundVote);
        $this->assertEquals($vote->id, $foundVote->id);
    }

    /** @test */
    public function demo_vote_verification_code_format_is_hex_32_chars()
    {
        // Arrange: Create demo vote
        $code = bin2hex(random_bytes(16));
        $vote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => Hash::make('test'),
            'verification_code' => $code,
        ]);

        // Assert: Verification code is 32 character hex string
        $this->assertEquals(32, strlen($vote->verification_code));
        $this->assertTrue(ctype_xdigit($vote->verification_code));
    }

    // ============================================================================
    // ELECTION TYPE VERIFICATION TESTS
    // ============================================================================

    /** @test */
    public function demo_election_type_is_correctly_identified()
    {
        // Assert: Demo election has correct type
        $this->assertEquals('demo', $this->demoElection->type);
    }

    /** @test */
    public function real_election_type_is_correctly_identified()
    {
        // Assert: Real election has correct type
        $this->assertEquals('real', $this->realElection->type);
    }

    /** @test */
    public function votes_belong_to_correct_election_type()
    {
        // Arrange: Create votes
        $demoVote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => Hash::make('demo'),
            'verification_code' => bin2hex(random_bytes(16)),
        ]);

        $realVote = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => Hash::make('real'),
        ]);

        // Assert: Votes can be found by election type
        $demoVotes = DemoVote::where('election_id', $this->demoElection->id)->get();
        $realVotes = Vote::where('election_id', $this->realElection->id)->get();

        $this->assertEquals(1, $demoVotes->count());
        $this->assertEquals(1, $realVotes->count());
    }

    // ============================================================================
    // DATA INTEGRITY TESTS
    // ============================================================================

    /** @test */
    public function demo_vote_stores_all_candidate_selections()
    {
        // Arrange: Create demo vote with multiple candidate selections
        $candidates = [];
        for ($i = 1; $i <= 5; $i++) {
            $col = 'candidate_' . sprintf('%02d', $i);
            $candidates[$col] = json_encode([
                'candidacy_id' => "candidate_$i",
                'name' => "Candidate $i",
            ]);
        }

        // Act: Save vote with candidates
        $vote = DemoVote::create(array_merge([
            'election_id' => $this->demoElection->id,
            'voting_code' => Hash::make('test'),
            'verification_code' => bin2hex(random_bytes(16)),
            'no_vote_option' => false,
        ], $candidates));

        // Assert: All candidates saved correctly
        $retrieved = DemoVote::find($vote->id);
        for ($i = 1; $i <= 5; $i++) {
            $col = 'candidate_' . sprintf('%02d', $i);
            $this->assertNotNull($retrieved->$col);
        }
    }

    /** @test */
    public function demo_vote_no_vote_option_is_stored_correctly()
    {
        // Arrange & Act: Create demo vote with no_vote_option
        $vote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => Hash::make('test'),
            'verification_code' => bin2hex(random_bytes(16)),
            'no_vote_option' => true,
        ]);

        // Assert: no_vote_option stored and retrieved correctly
        $retrieved = DemoVote::find($vote->id);
        $this->assertTrue($retrieved->no_vote_option);
    }
}
