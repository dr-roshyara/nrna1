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

class VoteResultsCompatibilityTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $user;
    private VoterSlug $voterSlug;
    private Code $code;
    private Post $post1;
    private Post $post2;
    private Candidacy $candidacy1;
    private Candidacy $candidacy2;
    private Candidacy $candidacy3;

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

        // Create two posts with multiple candidates
        $this->post1 = Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'name' => 'President',
        ]);

        $this->post2 = Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'name' => 'Vice President',
        ]);

        $this->candidacy1 = Candidacy::factory()->create([
            'post_id' => $this->post1->id,
            'organisation_id' => $this->organisation->id,
            'name' => 'Candidate 1',
        ]);

        $this->candidacy2 = Candidacy::factory()->create([
            'post_id' => $this->post1->id,
            'organisation_id' => $this->organisation->id,
            'name' => 'Candidate 2',
        ]);

        $this->candidacy3 = Candidacy::factory()->create([
            'post_id' => $this->post2->id,
            'organisation_id' => $this->organisation->id,
            'name' => 'Candidate 3',
        ]);

        $this->voterSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'test-compatibility',
            'is_active' => true,
            'status' => 'active',
            'current_step' => 5,
            'expires_at' => now()->addHour(),
        ]);

        $this->code = Code::factory()->create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'can_vote_now' => true,
            'has_code1_sent' => true,
            'is_code_to_open_voting_form_usable' => true,
            'vote_submitted' => true,
            'vote_submitted_at' => now(),
        ]);
    }

    /**
     * T1: When vote is saved with candidates, results table should have entries for each candidate
     */
    public function test_vote_creates_results_for_all_selected_candidates(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));

        // Store candidates in JSON format (as save_vote() does)
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post1->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy1->id],
                ['candidacy_id' => $this->candidacy2->id],
            ]
        ]);
        $vote->candidate_02 = json_encode([
            'post_id' => $this->post2->id,
            'post_name' => 'Vice President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy3->id],
            ]
        ]);
        $vote->save();

        // Verify results were created for each candidate
        $results = Result::where('vote_id', $vote->id)->get();
        $this->assertCount(3, $results, 'Vote should create 3 result records');
    }

    /**
     * T2: Result records should link to correct candidate_id and post_id
     */
    public function test_result_records_link_to_correct_candidates_and_posts(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));

        // JSON format for candidates
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post1->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy1->id],
            ]
        ]);
        $vote->candidate_02 = json_encode([
            'post_id' => $this->post2->id,
            'post_name' => 'Vice President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy3->id],
            ]
        ]);
        $vote->save();

        $results = Result::where('vote_id', $vote->id)->get();

        // Verify correct candidates are linked
        $candidateIds = $results->pluck('candidacy_id')->sort()->values();
        $expectedIds = collect([$this->candidacy1->id, $this->candidacy3->id])->sort()->values();
        $this->assertEquals($expectedIds, $candidateIds, 'Results should link to selected candidates');

        // Verify posts are correct
        foreach ($results as $result) {
            $candidacy = Candidacy::find($result->candidacy_id);
            $this->assertEquals($candidacy->post_id, $result->post_id, "Result post_id should match candidate's post_id");
        }
    }

    /**
     * T3: Only selected candidates should have results (not null/empty columns)
     */
    public function test_only_selected_candidates_create_results(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));

        // Only set candidate_01, leave others null
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post1->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy1->id],
            ]
        ]);
        $vote->save();

        $results = Result::where('vote_id', $vote->id)->get();
        $this->assertCount(1, $results, 'Only 1 candidate selected should create 1 result');
        $this->assertEquals($this->candidacy1->id, $results->first()->candidacy_id);
    }

    /**
     * T4: Vote no_vote_posts should create abstention results with candidacy_id = null
     */
    public function test_no_vote_posts_create_abstention_results(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [$this->post1->id]; // Voting NO on this post
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));

        // No-vote for post1 (should create abstention result)
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post1->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => true,
            'candidates' => []
        ]);

        // Select candidate for post2
        $vote->candidate_02 = json_encode([
            'post_id' => $this->post2->id,
            'post_name' => 'Vice President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy3->id],
            ]
        ]);
        $vote->save();

        $results = Result::where('vote_id', $vote->id)->get();

        // Should have 2 results: 1 abstention (post1) + 1 candidate vote (post2)
        $this->assertCount(2, $results, 'Vote should create results for both abstentions and candidates');

        // Verify abstention result for post1
        $post1Abstention = $results->where('post_id', $this->post1->id)->first();
        $this->assertNotNull($post1Abstention, 'Post1 should have an abstention result');
        $this->assertNull($post1Abstention->candidacy_id, 'Abstention should have null candidacy_id');
        $this->assertTrue($post1Abstention->no_vote, 'Abstention should have no_vote = true');

        // Verify candidate result for post2
        $post2Vote = $results->where('post_id', $this->post2->id)->first();
        $this->assertNotNull($post2Vote, 'Post2 should have a candidate vote result');
        $this->assertEquals($this->candidacy3->id, $post2Vote->candidacy_id);
        $this->assertFalse($post2Vote->no_vote, 'Candidate vote should have no_vote = false');
    }

    /**
     * T5: Results and votes should be linked bidirectionally (no orphaned results)
     */
    public function test_results_are_linked_to_votes(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post1->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy1->id],
            ]
        ]);
        $vote->save();

        $result = Result::where('vote_id', $vote->id)->first();
        $this->assertNotNull($result, 'Result should exist for vote');
        $this->assertEquals($vote->id, $result->vote_id, 'Result should reference correct vote_id');

        // Verify result can be retrieved through relationship
        $this->assertTrue($vote->results()->exists(), 'Vote should have related results');
    }

    /**
     * T6: Vote JSON columns should be queryable through results (aggregate verification)
     */
    public function test_vote_results_aggregation_matches_json_data(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));

        $vote->candidate_01 = json_encode([
            'post_id' => $this->post1->id,
            'post_name' => 'President',
            'required_number' => 2,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy1->id],
                ['candidacy_id' => $this->candidacy2->id],
            ]
        ]);
        $vote->candidate_02 = json_encode([
            'post_id' => $this->post2->id,
            'post_name' => 'Vice President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy3->id],
            ]
        ]);
        $vote->save();

        // Count results
        $selectedFromResults = Result::where('vote_id', $vote->id)->count();

        // Should have 3 results (2 from post1 + 1 from post2)
        $this->assertEquals(3, $selectedFromResults,
            'Vote JSON selected count should match results count');
    }

    /**
     * T7: Multiple votes should create separate results (no cross-vote contamination)
     */
    public function test_multiple_votes_create_separate_results(): void
    {
        // Create first vote
        $vote1 = new Vote();
        $vote1->election_id = $this->election->id;
        $vote1->organisation_id = $this->organisation->id;
        $vote1->no_vote_posts = [];
        $vote1->vote_hash = 'hash-1-' . bin2hex(random_bytes(20));
        $vote1->receipt_hash = 'receipt-1-' . bin2hex(random_bytes(20));
        $vote1->candidate_01 = json_encode([
            'post_id' => $this->post1->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy1->id],
            ]
        ]);
        $vote1->save();

        // Create second vote
        $vote2 = new Vote();
        $vote2->election_id = $this->election->id;
        $vote2->organisation_id = $this->organisation->id;
        $vote2->no_vote_posts = [];
        $vote2->vote_hash = 'hash-2-' . bin2hex(random_bytes(20));
        $vote2->receipt_hash = 'receipt-2-' . bin2hex(random_bytes(20));
        $vote2->candidate_01 = json_encode([
            'post_id' => $this->post1->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy2->id],
            ]
        ]);
        $vote2->save();

        $results1 = Result::where('vote_id', $vote1->id)->get();
        $results2 = Result::where('vote_id', $vote2->id)->get();

        $this->assertCount(1, $results1);
        $this->assertCount(1, $results2);
        $this->assertEquals($this->candidacy1->id, $results1->first()->candidacy_id);
        $this->assertEquals($this->candidacy2->id, $results2->first()->candidacy_id);
    }

    /**
     * T8: Results should be scoped to organisation (no cross-tenant contamination)
     */
    public function test_results_respects_organisation_scope(): void
    {
        // Create vote in our election
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post1->id,
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy1->id],
            ]
        ]);
        $vote->save();

        // Query results should only return results from this election
        $results = Result::where('vote_id', $vote->id)->get();
        $this->assertCount(1, $results);

        // All results should be from the same election
        foreach ($results as $result) {
            $this->assertEquals($this->election->id, $result->vote->election_id);
            $this->assertEquals($this->organisation->id, $result->organisation_id);
        }
    }

    /**
     * T9: Vote with maximum candidates should create maximum results
     */
    public function test_vote_with_max_candidates_creates_all_results(): void
    {
        // Create a vote with multiple candidate slots filled
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));

        // Fill candidate_01 and candidate_02
        $vote->candidate_01 = json_encode([
            'post_id' => $this->post1->id,
            'post_name' => 'President',
            'required_number' => 2,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy1->id],
                ['candidacy_id' => $this->candidacy2->id],
            ]
        ]);
        $vote->save();

        $results = Result::where('vote_id', $vote->id)->get();
        $this->assertCount(2, $results, 'Vote with 2 candidates should create 2 results');
    }

    /**
     * T10: Result deletion/cascading should respect vote integrity
     */
    public function test_result_data_can_be_reconstructed_from_vote(): void
    {
        $vote = new Vote();
        $vote->election_id = $this->election->id;
        $vote->organisation_id = $this->organisation->id;
        $vote->no_vote_posts = [];
        $vote->vote_hash = 'test-hash-' . bin2hex(random_bytes(20));
        $vote->receipt_hash = 'test-receipt-' . bin2hex(random_bytes(20));

        $vote->candidate_01 = json_encode([
            'post_id' => $this->post1->id,
            'post_name' => 'President',
            'required_number' => 2,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy1->id],
                ['candidacy_id' => $this->candidacy2->id],
            ]
        ]);
        $vote->candidate_02 = json_encode([
            'post_id' => $this->post2->id,
            'post_name' => 'Vice President',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => $this->candidacy3->id],
            ]
        ]);
        $vote->save();

        // Extract candidates from results
        $candidatesFromResults = Result::where('vote_id', $vote->id)
            ->get()
            ->pluck('candidacy_id')
            ->sort()
            ->values();

        // Expected candidates in order
        $expectedCandidates = collect([
            $this->candidacy1->id,
            $this->candidacy2->id,
            $this->candidacy3->id,
        ])->sort()->values();

        $this->assertEquals($expectedCandidates, $candidatesFromResults,
            'Candidates should be reconstructible from results');
    }
}
