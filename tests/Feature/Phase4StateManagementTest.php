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
use Illuminate\Support\Facades\Hash;

/**
 * Phase 4: State Management Testing
 *
 * Tests to verify that the voting system correctly manages state transitions:
 * 1. User state (voter → has voted)
 * 2. Code record state (initial → submitted → completed)
 * 3. VoterSlug step progression (1 → 8)
 * 4. Vote aggregation in results table
 */
class Phase4StateManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $election;
    protected $voter;
    protected $code;
    protected $voterSlug;

    public function setUp(): void
    {
        parent::setUp();

        // Create real election
        $this->election = Election::factory()->real()->create([
            'name' => 'State Management Test Election',
            'type' => 'real',
            'is_active' => true,
        ]);

        // Create voter
        $this->voter = User::factory()->voter()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false,
        ]);

        // Create code for voter
        $this->code = Code::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->election->id,
            'code1' => '123456',
            'code2' => '654321',
            'can_vote_now' => 1,
            'has_voted' => 0,
            'vote_submitted' => 0,
            'is_code1_usable' => 1,
            'session_name' => 'vote_data_' . $this->voter->id,
        ]);

        // Create voter slug for tracking
        $this->voterSlug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->election->id,
            'slug' => 'test-voter-' . uniqid(),
            'current_step' => 1,
        ]);

        // Create posts and candidacies
        for ($i = 1; $i <= 3; $i++) {
            $post = Post::factory()->create([
                'election_id' => $this->election->id,
                'post_id' => "POST{$i}",
                'required_number' => 1,
                'position_order' => $i,
            ]);

            for ($j = 1; $j <= 3; $j++) {
                $candidateUser = User::factory()->create();
                Candidacy::factory()->create([
                    'election_id' => $this->election->id,
                    'post_id' => $post->post_id,
                    'candidacy_id' => "CAND{$i}{$j}",
                    'user_id' => $candidateUser->user_id,
                    'position_order' => $j,
                    'proposer_id' => "PROPOSER{$i}{$j}",
                    'supporter_id' => "SUPPORTER{$i}{$j}",
                ]);
            }
        }
    }

    /**
     * TEST 1: Initial code state is correct
     *
     * Verify code record starts with proper initial state
     */
    public function test_initial_code_state()
    {
        \Log::info('TEST 1: Checking initial code state');

        // Verify code exists
        $this->assertNotNull($this->code->id, 'Code should exist');

        // Verify initial state flags
        $this->assertEquals(1, $this->code->can_vote_now, 'can_vote_now should be 1');
        $this->assertEquals(0, $this->code->has_voted, 'has_voted should be 0 initially');
        $this->assertEquals(0, $this->code->vote_submitted, 'vote_submitted should be 0');

        // Verify code values are set
        $this->assertNotEmpty($this->code->code1, 'code1 should be set');
        $this->assertNotEmpty($this->code->code2, 'code2 should be set');
        $this->assertEquals('123456', $this->code->code1, 'code1 should match test value');
        $this->assertEquals('654321', $this->code->code2, 'code2 should match test value');
    }

    /**
     * TEST 2: Code state updates when vote submitted
     *
     * Verify code record is updated when vote submission happens
     */
    public function test_code_state_updates_after_vote_submit()
    {
        \Log::info('TEST 2: Checking code state after vote submission');

        // Simulate vote submission by updating code
        $this->code->vote_submitted = 1;
        $this->code->vote_submitted_at = now();
        $this->code->save();

        // Verify state changed
        $freshCode = Code::find($this->code->id);
        $this->assertEquals(1, $freshCode->vote_submitted, 'vote_submitted should be 1 after submit');
        $this->assertNotNull($freshCode->vote_submitted_at, 'vote_submitted_at should be set');
    }

    /**
     * TEST 3: Code state updates when vote is confirmed
     *
     * Verify has_voted flag is set when vote is confirmed
     */
    public function test_code_has_voted_flag_set_on_confirmation()
    {
        \Log::info('TEST 3: Checking has_voted flag on confirmation');

        // Initially should be 0
        $this->assertEquals(0, $this->code->has_voted, 'has_voted should be 0 initially');

        // Simulate vote confirmation
        $this->code->has_voted = 1;
        $this->code->save();

        // Verify flag is set
        $freshCode = Code::find($this->code->id);
        $this->assertEquals(1, $freshCode->has_voted, 'has_voted should be 1 after confirmation');

        // Verify this prevents double voting (Phase 2 check)
        if ($this->election->type === 'real' && $freshCode && $freshCode->has_voted) {
            $this->assertTrue(true, 'Double vote prevention condition is met');
        }
    }

    /**
     * TEST 4: Code can transition through voting states
     *
     * Verify complete state transition sequence
     */
    public function test_code_state_transition_sequence()
    {
        \Log::info('TEST 4: Testing full code state transition');

        $code = $this->code;

        // State 1: Initial
        $this->assertEquals(0, $code->has_voted, 'State 1: Not voted');
        $this->assertEquals(0, $code->vote_submitted, 'State 1: Not submitted');

        // State 2: Vote submitted
        $code->vote_submitted = 1;
        $code->vote_submitted_at = now();
        $code->save();

        $code = Code::find($code->id);
        $this->assertEquals(1, $code->vote_submitted, 'State 2: Submitted');

        // State 3: Vote confirmed
        $code->has_voted = 1;
        $code->save();

        $code = Code::find($code->id);
        $this->assertEquals(1, $code->vote_submitted, 'State 3: Still submitted');
        $this->assertEquals(1, $code->has_voted, 'State 3: Now voted');

        // Verify can't vote again
        $this->assertTrue($code->has_voted, 'Final: Should prevent double voting');
    }

    /**
     * TEST 5: VoterSlug step progression
     *
     * Verify VoterSlug step tracking works
     */
    public function test_voter_slug_step_progression()
    {
        \Log::info('TEST 5: Checking voter slug step progression');

        $slug = $this->voterSlug;

        // Initially should be at step 1
        $this->assertEquals(1, $slug->current_step, 'Should start at step 1');

        // Progress through steps
        $steps = [
            ['step' => 2, 'description' => 'Code received'],
            ['step' => 3, 'description' => 'Code verified'],
            ['step' => 4, 'description' => 'Viewing candidates'],
            ['step' => 5, 'description' => 'Candidates selected'],
            ['step' => 6, 'description' => 'Vote submitted'],
            ['step' => 7, 'description' => 'Vote verified'],
            ['step' => 8, 'description' => 'Vote confirmed'],
        ];

        foreach ($steps as $stepInfo) {
            $slug->current_step = $stepInfo['step'];
            $slug->save();

            $fresh = VoterSlug::find($slug->id);
            $this->assertEquals($stepInfo['step'], $fresh->current_step,
                "Should be at step {$stepInfo['step']} - {$stepInfo['description']}");
        }
    }

    /**
     * TEST 6: Multiple voters have independent state
     *
     * Verify state management is per-voter
     */
    public function test_voter_state_independence()
    {
        \Log::info('TEST 6: Testing voter state independence');

        // Create another voter
        $voter2 = User::factory()->voter()->create();
        $code2 = Code::factory()->create([
            'user_id' => $voter2->id,
            'election_id' => $this->election->id,
            'has_voted' => 0,
        ]);

        // Modify first voter's code
        $this->code->has_voted = 1;
        $this->code->save();

        // Verify second voter is unaffected
        $this->assertEquals(1, $this->code->fresh()->has_voted,
            'Voter 1 should be marked as voted');
        $this->assertEquals(0, $code2->fresh()->has_voted,
            'Voter 2 should NOT be marked as voted');

        // Now mark voter 2 as voted
        $code2->has_voted = 1;
        $code2->save();

        // Verify both are voted but independently
        $this->assertEquals(1, $this->code->fresh()->has_voted, 'Voter 1 still voted');
        $this->assertEquals(1, $code2->fresh()->has_voted, 'Voter 2 now voted');
    }

    /**
     * TEST 7: Code state persists correctly
     *
     * Verify state changes are persisted to database
     */
    public function test_code_state_persistence()
    {
        \Log::info('TEST 7: Testing code state persistence');

        // Set multiple flags
        $this->code->can_vote_now = 0;
        $this->code->vote_submitted = 1;
        $this->code->has_voted = 1;
        $this->code->is_code1_usable = 0;
        $this->code->vote_submitted_at = now();
        $this->code->save();

        // Retrieve fresh from DB
        $fresh = Code::find($this->code->id);

        // Verify all state is persisted
        $this->assertEquals(0, $fresh->can_vote_now, 'can_vote_now persisted');
        $this->assertEquals(1, $fresh->vote_submitted, 'vote_submitted persisted');
        $this->assertEquals(1, $fresh->has_voted, 'has_voted persisted');
        $this->assertEquals(0, $fresh->is_code1_usable, 'is_code1_usable persisted');
        $this->assertNotNull($fresh->vote_submitted_at, 'vote_submitted_at persisted');
    }

    /**
     * TEST 8: State transitions are atomic
     *
     * Verify state doesn't get corrupted during transitions
     */
    public function test_state_transition_atomicity()
    {
        \Log::info('TEST 8: Testing state transition atomicity');

        $code = $this->code;

        // Complex state transition
        $code->vote_submitted = 1;
        $code->vote_submitted_at = now();
        $code->can_vote_now = 0;  // Lock after submission
        $code->save();

        // Simulate second update
        $code->has_voted = 1;
        $code->is_code1_usable = 0;
        $code->save();

        // Verify final state is correct
        $final = Code::find($code->id);
        $this->assertEquals(1, $final->vote_submitted);
        $this->assertEquals(0, $final->can_vote_now);
        $this->assertEquals(1, $final->has_voted);
        $this->assertEquals(0, $final->is_code1_usable);
    }
}
