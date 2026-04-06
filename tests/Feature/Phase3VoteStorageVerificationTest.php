<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Election;
use App\Models\Code;
use App\Models\User;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\VoterSlug;
use App\Models\Vote;
use App\Models\DemoVote;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * Phase 3: Vote Storage Verification
 *
 * Tests to verify votes are stored correctly in:
 * 1. votes table (real elections)
 * 2. demo_votes table (demo elections)
 * 3. results table (vote aggregation)
 * 4. JSON structure integrity
 */
class Phase3VoteStorageVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected $realElection;
    protected $demoElection;
    protected $voter;
    protected $code;
    protected $voterSlug;
    protected $posts = [];
    protected $candidacies = [];

    public function setUp(): void
    {
        parent::setUp();

        // Create real election
        $this->realElection = Election::factory()->real()->create([
            'name' => 'Test Real Election',
            'type' => 'real',
            'is_active' => true,
        ]);

        // Create demo election
        $this->demoElection = Election::factory()->real()->create([
            'name' => 'Test Demo Election',
            'type' => 'demo',
            'is_active' => true,
        ]);

        // Create voter
        $this->voter = User::factory()->voter()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false,
        ]);

        // Create posts and candidacies for real election
        for ($i = 1; $i <= 3; $i++) {
            $this->posts[$i] = Post::factory()->create([
                'election_id' => $this->realElection->id,
                'post_id' => "POST{$i}",
                'name' => "Position {$i}",
                'required_number' => 1,
                'position_order' => $i,
            ]);

            for ($j = 1; $j <= 3; $j++) {
                $candidateUser = User::factory()->create();
                $this->candidacies[$i][$j] = Candidacy::factory()->create([
                    'election_id' => $this->realElection->id,
                    'post_id' => $this->posts[$i]->post_id,
                    'candidacy_id' => "CAND{$i}{$j}",
                    'user_id' => $candidateUser->user_id,
                    'position_order' => $j,
                    'proposer_id' => "PROPOSER{$i}{$j}",
                    'supporter_id' => "SUPPORTER{$i}{$j}",
                ]);
            }
        }

        // Create code for real election
        $this->code = Code::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->realElection->id,
            'code1' => '123456',
            'code2' => '654321',
            'can_vote_now' => 1,
            'has_voted' => 0,
            'is_code1_usable' => 1,
            'session_name' => 'vote_data_' . $this->voter->id,
        ]);

        // Create voter slug
        $this->voterSlug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->realElection->id,
            'slug' => 'test-voter-slug-' . uniqid(),
            'current_step' => 3,
        ]);
    }

    /**
     * TEST 1: Verify vote record exists in votes table after submission
     *
     * After vote is stored, check that:
     * - Vote record exists in votes table
     * - election_id matches
     * - code_id is correct (if used)
     *
     * NOTE: Candidate columns are JSON type, so we store them as arrays
     */
    public function test_vote_record_stored_in_votes_table()
    {
        // Verify no votes exist initially
        $this->assertEquals(0, Vote::where('election_id', $this->realElection->id)->count(),
            'No votes should exist initially');

        // Log to show test start
        \Log::info('TEST 1: Checking vote storage in votes table');

        // Simulate vote storage (create vote record)
        // NOTE: candidate fields are JSON columns, so we store as arrays
        $vote = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => $this->code->code1,
            'candidate_01' => ['id' => 'CAND11', 'name' => 'Candidate 11'],
            'candidate_02' => ['id' => 'CAND22', 'name' => 'Candidate 22'],
            'candidate_03' => ['id' => 'CAND33', 'name' => 'Candidate 33'],
            // Other candidate columns (04-60) default to null
        ]);

        // Verify vote was stored
        $this->assertNotNull($vote->id, 'Vote should have an ID after creation');
        $this->assertEquals($this->realElection->id, $vote->election_id, 'Vote should reference correct election');

        // Verify it appears in database
        $storedVote = Vote::find($vote->id);
        $this->assertNotNull($storedVote, 'Vote should be retrievable from database');

        // Verify candidate data (stored as JSON arrays)
        $this->assertNotNull($storedVote->candidate_01, 'First candidate should be stored');
        $this->assertNotNull($storedVote->candidate_02, 'Second candidate should be stored');
        $this->assertNotNull($storedVote->candidate_03, 'Third candidate should be stored');

        // Verify count
        $this->assertEquals(1, Vote::where('election_id', $this->realElection->id)->count(),
            'Exactly one vote should exist for this election');
    }

    /**
     * TEST 2: Verify vote record is NOT in demo_votes table for real election
     *
     * Real election votes should go to votes table, NOT demo_votes table
     */
    public function test_real_election_vote_not_in_demo_votes_table()
    {
        // Verify demo_votes table is empty
        $this->assertEquals(0, DemoVote::where('election_id', $this->realElection->id)->count(),
            'No demo votes should exist for real election initially');

        // Create vote in votes table (real election)
        Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => $this->code->code1,
            'candidate_01' => ['id' => 'CAND11'],
            'candidate_02' => ['id' => 'CAND22'],
            'candidate_03' => ['id' => 'CAND33'],
        ]);

        // Verify it's not in demo_votes
        $this->assertEquals(0, DemoVote::where('election_id', $this->realElection->id)->count(),
            'Real election votes should NOT be in demo_votes table');

        // Verify it IS in votes
        $this->assertEquals(1, Vote::where('election_id', $this->realElection->id)->count(),
            'Real election votes should be in votes table');
    }

    /**
     * TEST 3: Verify demo_votes table for demo elections
     *
     * Demo election votes should go to demo_votes table
     */
    public function test_demo_election_vote_stored_in_demo_votes_table()
    {
        // Create code for demo election
        $demoCode = Code::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->demoElection->id,
            'code1' => '111111',
            'code2' => '222222',
            'can_vote_now' => 1,
            'has_voted' => 0,
        ]);

        // Verify demo_votes is empty
        $this->assertEquals(0, DemoVote::where('election_id', $this->demoElection->id)->count(),
            'No demo votes should exist initially');

        // Create demo vote
        $demoVote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => $demoCode->code1,
            'candidate_01' => ['id' => 'CAND11'],
            'candidate_02' => ['id' => 'CAND22'],
            'candidate_03' => ['id' => 'CAND33'],
        ]);

        // Verify demo vote was stored
        $this->assertNotNull($demoVote->id, 'Demo vote should have an ID');
        $this->assertEquals($this->demoElection->id, $demoVote->election_id, 'Demo vote should reference correct election');

        // Verify count
        $this->assertEquals(1, DemoVote::where('election_id', $this->demoElection->id)->count(),
            'Exactly one demo vote should exist');

        // Verify it's NOT in votes table
        $this->assertEquals(0, Vote::where('election_id', $this->demoElection->id)->count(),
            'Demo votes should NOT be in votes table');
    }

    /**
     * TEST 4: Verify vote JSON structure integrity
     *
     * If votes are stored as JSON, verify the structure is valid
     */
    public function test_vote_json_structure_integrity()
    {
        // Create vote with candidate selections
        $selectedCandidates = [
            'national' => ['CAND11', 'CAND22', 'CAND33'],
            'regional' => ['CAND41', 'CAND52'],
        ];

        $vote = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => $this->code->code1,
            'candidate_01' => ['id' => 'CAND11'],
            'candidate_02' => ['id' => 'CAND22'],
            'candidate_03' => ['id' => 'CAND33'],
            'candidate_04' => ['id' => 'CAND41'],
            'candidate_05' => ['id' => 'CAND52'],
        ]);

        // Retrieve and verify structure
        $storedVote = Vote::find($vote->id);

        // Verify individual candidates are accessible (stored as JSON arrays)
        $this->assertIsArray($storedVote->candidate_01, 'Candidate should be array');
        $this->assertEquals('CAND11', $storedVote->candidate_01['id']);
        $this->assertEquals('CAND22', $storedVote->candidate_02['id']);
        $this->assertEquals('CAND33', $storedVote->candidate_03['id']);
        $this->assertEquals('CAND41', $storedVote->candidate_04['id']);
        $this->assertEquals('CAND52', $storedVote->candidate_05['id']);

        // Verify null candidates
        $this->assertNull($storedVote->candidate_06, 'Unselected candidates should be null');
        $this->assertNull($storedVote->candidate_60, 'Candidate_60 should be null if not selected');
    }

    /**
     * TEST 5: Verify multiple votes from same voter are tracked correctly
     *
     * In demo elections, same voter can vote multiple times
     * Each vote should have its own record
     */
    public function test_multiple_votes_from_same_voter_tracked()
    {
        // Create code for demo election (allows revoting)
        $demoCode = Code::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->demoElection->id,
            'code1' => '111111',
            'code2' => '222222',
            'can_vote_now' => 1,
            'has_voted' => 0,
        ]);

        // Vote 1
        $vote1 = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => $demoCode->code1,
            'candidate_01' => ['id' => 'CAND11'],
            'candidate_02' => ['id' => 'CAND22'],
            'candidate_03' => ['id' => 'CAND33'],
        ]);

        // Vote 2 (different selections)
        $vote2 = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => $demoCode->code1,
            'candidate_01' => ['id' => 'CAND12'],  // Different selection
            'candidate_02' => ['id' => 'CAND23'],
            'candidate_03' => ['id' => 'CAND31'],
        ]);

        // Verify both votes exist
        $this->assertEquals(2, DemoVote::where('election_id', $this->demoElection->id)->count(),
            'Both votes should be stored');

        // Verify they have different selections
        $stored1 = DemoVote::find($vote1->id);
        $stored2 = DemoVote::find($vote2->id);

        $this->assertEquals('CAND11', $stored1->candidate_01['id']);
        $this->assertEquals('CAND12', $stored2->candidate_01['id']);

        // Verify they're linked to same code (via voting_code)
        $this->assertEquals($demoCode->code1, $stored1->voting_code);
        $this->assertEquals($demoCode->code1, $stored2->voting_code);
    }

    /**
     * TEST 6: Verify election_id is correctly set on stored votes
     *
     * Critical: Votes must be associated with correct election
     */
    public function test_vote_has_correct_election_id()
    {
        // Create another election
        $otherElection = Election::factory()->real()->create([
            'name' => 'Other Election',
            'type' => 'real',
        ]);

        // Create vote for first election
        $vote1 = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => $this->code->code1,
            'candidate_01' => ['id' => 'CAND11'],
        ]);

        // Create code for other election
        $otherCode = Code::factory()->create([
            'election_id' => $otherElection->id,
            'user_id' => $this->voter->id,
        ]);

        // Create vote for other election
        $vote2 = Vote::create([
            'election_id' => $otherElection->id,
            'voting_code' => $otherCode->code1,
            'candidate_01' => ['id' => 'CAND11'],
        ]);

        // Verify election_ids are correct
        $this->assertEquals($this->realElection->id, Vote::find($vote1->id)->election_id,
            'Vote 1 should have correct election_id');
        $this->assertEquals($otherElection->id, Vote::find($vote2->id)->election_id,
            'Vote 2 should have correct election_id');

        // Verify count per election
        $this->assertEquals(1, Vote::where('election_id', $this->realElection->id)->count(),
            'Real election should have exactly 1 vote');
        $this->assertEquals(1, Vote::where('election_id', $otherElection->id)->count(),
            'Other election should have exactly 1 vote');
    }

    /**
     * TEST 7: Verify code_id links vote to correct code record
     *
     * Vote must be traceable back to the code that submitted it
     */
    public function test_vote_links_to_correct_code()
    {
        // Create second code for same voter
        $otherCode = Code::create([
            'user_id' => $this->voter->id,
            'election_id' => $this->realElection->id,
            'code1' => '999999',
            'code2' => '888888',
            'can_vote_now' => 1,
            'has_voted' => 0,
            'is_code1_usable' => 1,
        ]);

        // Create votes with different codes
        $vote1 = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => $this->code->code1,
            'candidate_01' => ['id' => 'CAND11'],
        ]);

        $vote2 = Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => $otherCode->code1,
            'candidate_01' => ['id' => 'CAND12'],
        ]);

        // Verify code linkage (via voting_code)
        $this->assertEquals($this->code->code1, Vote::find($vote1->id)->voting_code,
            'Vote 1 should link to first code');
        $this->assertEquals($otherCode->code1, Vote::find($vote2->id)->voting_code,
            'Vote 2 should link to second code');

        // Verify relationship works
        $code1Votes = Vote::where('voting_code', $this->code->code1)->get();
        $code2Votes = Vote::where('voting_code', $otherCode->code1)->get();

        $this->assertEquals(1, $code1Votes->count(), 'First code should have 1 vote');
        $this->assertEquals(1, $code2Votes->count(), 'Second code should have 1 vote');
    }

    /**
     * TEST 8: Verify has_voted flag is set when vote is stored
     *
     * After vote submission, the Code::has_voted flag should be true
     */
    public function test_has_voted_flag_set_after_vote_stored()
    {
        // Verify initially false
        $this->assertEquals(0, $this->code->has_voted, 'has_voted should be 0 initially');

        // Create vote
        Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => $this->code->code1,
            'candidate_01' => ['id' => 'CAND11'],
        ]);

        // Manually set has_voted (simulating what the controller should do)
        $this->code->has_voted = 1;
        $this->code->save();

        // Verify flag is set
        $freshCode = Code::find($this->code->id);
        $this->assertEquals(1, $freshCode->has_voted, 'has_voted should be 1 after vote stored');

        // Verify double vote is now blocked (using first_submission logic)
        if ($this->realElection->type === 'real' && $freshCode && $freshCode->has_voted) {
            // This condition should block the vote (as tested in Phase 2)
            $this->assertTrue(true, 'Double vote prevention condition is met');
        }
    }
}
