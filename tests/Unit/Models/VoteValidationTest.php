<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Vote;
use App\Models\Result;
use App\Models\Election;
use App\Models\DemoVote;
use App\Models\DemoResult;
use App\Exceptions\InvalidRealVoteException;
use App\Exceptions\OrganisationMismatchException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * VoteValidationTest
 *
 * Tests Phase 2: Model-level validation hooks for real votes and results.
 *
 * Tests that:
 * - organisation_id MUST NOT be NULL on real votes/results
 * - organisation_id MUST match election/vote organisation
 * - Demo votes/results are not affected by validation
 * - Proper exceptions are thrown with context
 */
class VoteValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
    }

    protected function tearDown(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        parent::tearDown();
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // VOTE VALIDATION TESTS
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Test 1: Real vote WITHOUT organisation_id throws InvalidRealVoteException
     *
     * CRITICAL: Real votes MUST have organisation_id
     */
    public function test_real_vote_without_organisation_id_throws_exception()
    {
        $election = Election::factory()->create(['organisation_id' => 1]);

        $this->expectException(InvalidRealVoteException::class);
        $this->expectExceptionMessage('Real votes require a valid organisation context');

        Vote::create([
            'election_id' => $election->id,
            'organisation_id' => null,  // ← INVALID
            'voting_code' => 'TEST123',
        ]);
    }

    /**
     * Test 2: Real vote without election_id throws InvalidRealVoteException
     */
    public function test_real_vote_without_election_id_throws_exception()
    {
        $this->expectException(InvalidRealVoteException::class);
        $this->expectExceptionMessage('Real votes require a valid election');

        Vote::create([
            'election_id' => null,  // ← INVALID
            'organisation_id' => 1,
            'voting_code' => 'TEST123',
        ]);
    }

    /**
     * Test 3: Real vote referencing non-existent election throws exception
     */
    public function test_real_vote_with_invalid_election_id_throws_exception()
    {
        $this->expectException(InvalidRealVoteException::class);
        $this->expectExceptionMessage('Election (id: 9999) not found');

        Vote::create([
            'election_id' => 9999,  // ← Non-existent
            'organisation_id' => 1,
            'voting_code' => 'TEST123',
        ]);
    }

    /**
     * Test 4: Real vote with MISMATCHED organisation_id throws OrganisationMismatchException
     *
     * CRITICAL: Vote org_id must match election org_id
     */
    public function test_real_vote_with_mismatched_organisation_throws_exception()
    {
        $election = Election::factory()->create(['organisation_id' => 1, 'type' => 'real']);  // Election in org 1

        $this->expectException(OrganisationMismatchException::class);
        $this->expectExceptionMessage('Vote organisation_id');

        Vote::create([
            'election_id' => $election->id,
            'organisation_id' => 2,  // ← MISMATCHED (election is in org 1)
            'voting_code' => 'TEST123',
        ]);
    }

    /**
     * Test 5: Valid real vote passes all validation and is created successfully
     *
     * HAPPY PATH: All validation checks pass
     */
    public function test_valid_real_vote_passes_validation()
    {
        $election = Election::factory()->create(['organisation_id' => 1, 'type' => 'real']);

        // ✅ Should NOT throw exception
        $vote = Vote::create([
            'election_id' => $election->id,
            'organisation_id' => 1,  // ← Matches election
            'voting_code' => 'TEST123',
        ]);

        $this->assertNotNull($vote->id);
        $this->assertEquals(1, $vote->organisation_id);
        $this->assertEquals($election->id, $vote->election_id);
    }

    /**
     * Test 6: Demo vote is NOT affected by real vote validation
     *
     * CRITICAL: Demo votes have separate validation (or no validation)
     */
    public function test_demo_vote_bypasses_real_vote_validation()
    {
        $election = Election::factory()->create(['organisation_id' => null, 'type' => 'demo']);

        // ✅ Demo vote with NULL organisation should NOT throw exception
        $demoVote = DemoVote::create([
            'election_id' => $election->id,
            'organisation_id' => null,  // ← Valid for demo votes
            'voting_code' => 'DEMO123',
        ]);

        $this->assertNotNull($demoVote->id);
        $this->assertNull($demoVote->organisation_id);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // RESULT VALIDATION TESTS
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Test 7: Real result WITHOUT organisation_id throws InvalidRealVoteException
     *
     * CRITICAL: Real results MUST have organisation_id
     */
    public function test_real_result_without_organisation_id_throws_exception()
    {
        $election = Election::factory()->create(['organisation_id' => 1, 'type' => 'real']);
        $vote = Vote::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => 1,
        ]);

        $this->expectException(InvalidRealVoteException::class);
        $this->expectExceptionMessage('Real results require a valid organisation context');

        Result::create([
            'vote_id' => $vote->id,
            'organisation_id' => null,  // ← INVALID
            'post_id' => 'post-1',
            'candidacy_id' => 'candidacy-1',
        ]);
    }

    /**
     * Test 8: Real result without vote_id throws InvalidRealVoteException
     */
    public function test_real_result_without_vote_id_throws_exception()
    {
        $this->expectException(InvalidRealVoteException::class);
        $this->expectExceptionMessage('Real results require a valid vote');

        Result::create([
            'vote_id' => null,  // ← INVALID
            'organisation_id' => 1,
            'post_id' => 'post-1',
            'candidacy_id' => 'candidacy-1',
        ]);
    }

    /**
     * Test 9: Real result referencing non-existent vote throws exception
     */
    public function test_real_result_with_invalid_vote_id_throws_exception()
    {
        $this->expectException(InvalidRealVoteException::class);
        $this->expectExceptionMessage('Vote (id: 9999) not found');

        Result::create([
            'vote_id' => 9999,  // ← Non-existent
            'organisation_id' => 1,
            'post_id' => 'post-1',
            'candidacy_id' => 'candidacy-1',
        ]);
    }

    /**
     * Test 10: Real result with MISMATCHED organisation_id throws OrganisationMismatchException
     *
     * CRITICAL: Result org_id must match vote org_id
     */
    public function test_real_result_with_mismatched_organisation_throws_exception()
    {
        $election = Election::factory()->create(['organisation_id' => 1, 'type' => 'real']);
        $vote = Vote::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => 1,  // Vote is in org 1
        ]);

        $this->expectException(OrganisationMismatchException::class);
        $this->expectExceptionMessage('Result organisation_id');

        Result::create([
            'vote_id' => $vote->id,
            'organisation_id' => 2,  // ← MISMATCHED (vote is in org 1)
            'post_id' => 'post-1',
            'candidacy_id' => 'candidacy-1',
        ]);
    }

    /**
     * Test 11: Valid real result passes all validation and is created successfully
     *
     * HAPPY PATH: All validation checks pass
     */
    public function test_valid_real_result_passes_validation()
    {
        $election = Election::factory()->create(['organisation_id' => 1, 'type' => 'real']);
        $vote = Vote::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => 1,
        ]);

        // ✅ Should NOT throw exception
        $result = Result::create([
            'vote_id' => $vote->id,
            'organisation_id' => 1,  // ← Matches vote
            'post_id' => 'post-1',
            'candidacy_id' => 'candidacy-1',
        ]);

        $this->assertNotNull($result->id);
        $this->assertEquals($vote->id, $result->vote_id);
        $this->assertEquals(1, $result->organisation_id);
    }

    /**
     * Test 12: Demo result is NOT affected by real result validation
     *
     * CRITICAL: Demo results have separate validation (or no validation)
     */
    public function test_demo_result_bypasses_real_result_validation()
    {
        $election = Election::factory()->create(['organisation_id' => null, 'type' => 'demo']);
        $demoVote = DemoVote::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => null,
        ]);

        // ✅ Demo result with NULL organisation should NOT throw exception
        $demoResult = DemoResult::create([
            'vote_id' => $demoVote->id,
            'organisation_id' => null,  // ← Valid for demo results
            'post_id' => 'post-1',
            'candidacy_id' => 'candidacy-1',
        ]);

        $this->assertNotNull($demoResult->id);
        $this->assertNull($demoResult->organisation_id);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // COMPLETE WORKFLOW TESTS
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Test 13: Complete real voting workflow - vote + result both pass validation
     *
     * HAPPY PATH: Create election, vote, and results all pass validation
     */
    public function test_complete_real_voting_workflow_passes_validation()
    {
        $election = Election::factory()->create(['organisation_id' => 1, 'type' => 'real']);

        // ✅ Create vote
        $vote = Vote::create([
            'election_id' => $election->id,
            'organisation_id' => 1,
            'voting_code' => 'WORKFLOW_TEST_001',
        ]);

        $this->assertNotNull($vote->id);

        // ✅ Create results for vote
        $result1 = Result::create([
            'vote_id' => $vote->id,
            'organisation_id' => 1,
            'post_id' => 'president',
            'candidacy_id' => 'candidate-1',
        ]);

        $result2 = Result::create([
            'vote_id' => $vote->id,
            'organisation_id' => 1,
            'post_id' => 'vice-president',
            'candidacy_id' => 'candidate-2',
        ]);

        $this->assertNotNull($result1->id);
        $this->assertNotNull($result2->id);

        // Verify all records are in same organisation
        $this->assertEquals($vote->organisation_id, $result1->organisation_id);
        $this->assertEquals($vote->organisation_id, $result2->organisation_id);
    }
}
