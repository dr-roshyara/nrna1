<?php

namespace Tests\Feature;

use App\Models\Code;
use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use App\Models\Organisation;
use App\Models\ElectionMembership;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\Vote;
use App\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteIntegrityVerificationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $user;
    private Post $post;
    private Candidacy $candidacy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->organisation->id]);

        $this->user = User::factory()->forOrganisation($this->organisation)->create();
        $this->election = Election::factory()->create([
            'organisation_id' => $this->organisation->id,
            'type' => 'real',
            'status' => 'active',
        ]);

        ElectionMembership::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $this->post = Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);

        $this->candidacy = Candidacy::factory()->create([
            'post_id' => $this->post->id,
            'organisation_id' => $this->organisation->id,
        ]);
    }

    /**
     * T1: Vote should calculate and store data_checksum when created
     */
    public function test_vote_calculates_checksum_on_creation(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy->id],
            ]
        ]);
        $vote->save();

        // Verify checksum was calculated and stored
        $this->assertNotNull($vote->data_checksum, 'Checksum should be generated on creation');
        $this->assertTrue(strlen($vote->data_checksum) === 64, 'Checksum should be SHA256 (64 chars)');
    }

    /**
     * T2: Vote checksum should match calculated value
     */
    public function test_vote_checksum_can_be_verified(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy->id],
            ]
        ]);
        $vote->save();

        // Verify checksum is valid
        $this->assertTrue($vote->verifyChecksum(), 'Stored checksum should match calculated value');
    }

    /**
     * T3: Modified vote data should invalidate checksum
     */
    public function test_modified_vote_data_invalidates_checksum(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy->id],
            ]
        ]);
        $vote->save();

        // Store original checksum
        $originalChecksum = $vote->data_checksum;

        // Modify vote data (simulate corruption)
        $vote->candidate_02 = json_encode([
            'post_id' => $this->post->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy->id],
            ]
        ]);
        // Don't recalculate - simulate data drift

        // Verification should fail
        $this->assertFalse($vote->verifyChecksum(), 'Modified data should fail checksum verification');
    }

    /**
     * T4: Vote integrity check should detect result count mismatch
     */
    public function test_vote_integrity_check_detects_result_mismatch(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy->id],
            ]
        ]);
        $vote->save();

        // Verify initial state is valid
        $verification = $vote->verifyResultsIntegrity();
        $this->assertTrue($verification['is_valid'], 'Fresh vote should be valid');

        // Delete a result to simulate corruption
        Result::where('vote_id', $vote->id)->first()->forceDelete();

        // Now verification should fail
        $verification = $vote->verifyResultsIntegrity();
        $this->assertFalse($verification['is_valid'], 'Missing results should fail verification');
        $this->assertLessThan($verification['expected_count'], $verification['stored_count'], 'Stored should be less than expected after deletion');
    }

    /**
     * T5: Sync results should restore integrity
     */
    public function test_sync_results_restores_integrity(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy->id],
            ]
        ]);
        $vote->save();

        // Corrupt by deleting results
        Result::where('vote_id', $vote->id)->forceDelete();
        $beforeSync = $vote->verifyResultsIntegrity();
        $this->assertFalse($beforeSync['is_valid']);

        // Sync results
        $vote->syncResults();

        // Verify integrity restored
        $afterSync = $vote->verifyResultsIntegrity();
        $this->assertTrue($afterSync['is_valid'], 'Sync should restore integrity');
        $this->assertEquals($afterSync['expected_count'], $afterSync['stored_count']);
    }

    /**
     * T6: Get expected result count should match actual candidates
     */
    public function test_get_expected_result_count(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post->id,
            'post_name' => 'President',
            'required_number' => 2,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy->id],
            ]
        ]);
        $vote->save();

        // Expected count: 1 candidate selected
        $expected = $vote->getExpectedResultCount();
        $this->assertEquals(1, $expected);
    }

    /**
     * T7: Vote is_verified flag tracks verification status
     */
    public function test_vote_is_verified_flag(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy->id],
            ]
        ]);
        $vote->save();

        // Initially should be false (default value)
        $freshVote = Vote::find($vote->id);
        $this->assertFalse($freshVote->is_verified, 'Newly created vote should not be marked verified');

        // Mark as verified
        $freshVote->update(['is_verified' => true]);
        $freshVote->refresh();
        $this->assertTrue($freshVote->is_verified, 'Vote should be marked as verified after update');
    }

    /**
     * T8: Results last synced timestamp tracks when results were regenerated
     */
    public function test_results_last_synced_at_timestamp(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy->id],
            ]
        ]);
        $vote->save();

        // After sync, should have timestamp
        $vote->syncResults();
        $vote->refresh();

        $this->assertNotNull($vote->results_last_synced_at, 'Sync should record timestamp');
    }

    /**
     * T9: Verify vote integrity should return detailed status
     */
    public function test_verify_vote_returns_detailed_status(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy->id],
            ]
        ]);
        $vote->save();

        $verification = $vote->verifyResultsIntegrity();

        // Should have these keys
        $this->assertArrayHasKey('is_valid', $verification);
        $this->assertArrayHasKey('stored_count', $verification);
        $this->assertArrayHasKey('expected_count', $verification);
        $this->assertArrayHasKey('checksum_valid', $verification);
    }

    /**
     * T10: Bulk verification should check all votes
     */
    public function test_verify_all_votes_in_election(): void
    {
        // Create 3 votes
        for ($i = 0; $i < 3; $i++) {
            $vote = new Vote();
            $vote->election_id = $this->election->id;
            $vote->organisation_id = $this->organisation->id;
            $vote->no_vote_posts = [];
            $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
            $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
            $vote->candidate_01 = json_encode([
                'post_id' => $this->post->id,
                'post_name' => 'President',
                'required_number' => 1,
                'no_vote' => false,
                'candidates' => [
                    ['candidacy_id' => $this->candidacy->id],
                ]
            ]);
            $vote->save();
        }

        // Count votes in this election
        $voteCount = Vote::where('election_id', $this->election->id)->count();
        $this->assertEquals(3, $voteCount);

        // Should be able to verify all 3
        $results = Vote::where('election_id', $this->election->id)->get();
        $allValid = true;
        foreach ($results as $vote) {
            $verification = $vote->verifyResultsIntegrity();
            if (!$verification['is_valid']) {
                $allValid = false;
            }
        }
        $this->assertTrue($allValid, 'All votes should be valid');
    }
}
