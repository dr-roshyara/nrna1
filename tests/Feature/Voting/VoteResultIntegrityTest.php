<?php

namespace Tests\Feature\Voting;

use App\Models\Candidacy;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\Result;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteResultIntegrityTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private Post $post;
    private Candidacy $candidacy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create();
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'state' => 'voting',
        ]);
        $this->post = Post::factory()->forElection($this->election)->create();
        $this->candidacy = Candidacy::factory()->forPost($this->post)->create();
    }

    private function createVoteWithCandidates(array $selections): Vote
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->org->id;
        $vote->vote_hash = hash('sha256', uniqid('vote_', true));
        $vote->receipt_hash = hash('sha256', uniqid('receipt_', true));
        $vote->save();

        foreach ($selections as $index => $selection) {
            $col = 'candidate_' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            $vote->$col = json_encode($selection);
        }
        $vote->save(); // Triggers saved event → createResultsFromCandidates()

        return $vote->fresh();
    }

    /** @test */
    public function vote_save_populates_data_checksum(): void
    {
        $vote = $this->createVoteWithCandidates([
            ['post_id' => $this->post->id, 'post_name' => 'President', 'no_vote' => false,
             'candidates' => [['candidacy_id' => $this->candidacy->id]]],
        ]);

        $this->assertNotNull($vote->fresh()->data_checksum, 'data_checksum should be populated after save');
    }

    /** @test */
    public function verify_checksum_detects_tampering(): void
    {
        $vote = $this->createVoteWithCandidates([
            ['post_id' => $this->post->id, 'post_name' => 'President', 'no_vote' => false,
             'candidates' => [['candidacy_id' => $this->candidacy->id]]],
        ]);

        $this->assertTrue($vote->verifyChecksum(), 'Checksum should be valid after save');

        // Tamper with vote data directly (bypass model)
        \DB::table('votes')->where('id', $vote->id)->update([
            'candidate_01' => json_encode(['post_id' => $this->post->id, 'candidates' => [['candidacy_id' => 'tampered']]]),
        ]);

        $this->assertFalse($vote->fresh()->verifyChecksum(), 'Checksum should detect tampering');
    }

    /** @test */
    public function verify_results_integrity_detects_missing_results(): void
    {
        $candidacy2 = Candidacy::factory()->forPost($this->post)->create();

        $vote = $this->createVoteWithCandidates([
            ['post_id' => $this->post->id, 'post_name' => 'President', 'no_vote' => false,
             'candidates' => [['candidacy_id' => $this->candidacy->id], ['candidacy_id' => $candidacy2->id]]],
        ]);

        // Hard-delete one result to create count mismatch (soft-delete won't change count in verifyResultsIntegrity)
        Result::where('vote_id', $vote->id)->limit(1)->forceDelete();

        $integrity = $vote->fresh()->verifyResultsIntegrity();
        $this->assertFalse($integrity['is_valid'], 'Should detect missing results');
        $this->assertEquals(1, $integrity['stored_count']);
        $this->assertEquals(2, $integrity['expected_count']);
    }

    /** @test */
    public function sync_results_restores_consistency(): void
    {
        $vote = $this->createVoteWithCandidates([
            ['post_id' => $this->post->id, 'post_name' => 'President', 'no_vote' => false,
             'candidates' => [['candidacy_id' => $this->candidacy->id]]],
        ]);

        // Delete all results
        Result::where('vote_id', $vote->id)->delete();

        // Sync should restore them
        $vote->syncResults();

        $integrity = $vote->fresh()->verifyResultsIntegrity();
        $this->assertTrue($integrity['is_valid'], 'Sync should restore consistency');
        $this->assertEquals($integrity['expected_count'], $integrity['stored_count']);
    }

    /** @test */
    public function integrity_check_finds_all_votes_valid(): void
    {
        $vote1 = $this->createVoteWithCandidates([
            ['post_id' => $this->post->id, 'post_name' => 'President', 'no_vote' => false,
             'candidates' => [['candidacy_id' => $this->candidacy->id]]],
        ]);
        $vote2 = $this->createVoteWithCandidates([
            ['post_id' => $this->post->id, 'post_name' => 'President', 'no_vote' => true, 'candidates' => []],
        ]);

        $allValid = true;
        foreach ([$vote1, $vote2] as $vote) {
            if (!$vote->fresh()->verifyResultsIntegrity()['is_valid']) {
                $allValid = false;
            }
        }

        $this->assertTrue($allValid, 'All votes should pass integrity check');
    }

    /** @test */
    public function publish_results_runs_integrity_check(): void
    {
        $vote = $this->createVoteWithCandidates([
            ['post_id' => $this->post->id, 'post_name' => 'President', 'no_vote' => false,
             'candidates' => [['candidacy_id' => $this->candidacy->id]]],
        ]);

        $this->election->update(['state' => 'results_pending']);

        // Hard-delete a result to create count mismatch (soft-delete won't change count in verifyResultsIntegrity)
        Result::where('vote_id', $vote->id)->limit(1)->forceDelete();

        // Simulate the publish flow integrity sweep (withoutGlobalScopes bypasses BelongsToTenant in tests)
        $violations = [];
        foreach (Vote::withoutGlobalScopes()->where('election_id', $this->election->id)->get() as $v) {
            $integrity = $v->verifyResultsIntegrity();
            if (!$integrity['is_valid']) {
                $violations[] = ['vote_id' => $v->id];
                $v->syncResults(); // Auto-fix
            }
        }

        $this->assertNotEmpty($violations, 'Should detect violation');

        // After sync, should be clean
        $recheck = $vote->fresh()->verifyResultsIntegrity();
        $this->assertTrue($recheck['is_valid'], 'Should be valid after sync');
    }
}
