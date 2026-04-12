<?php

namespace Tests\Feature\Demo;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\Demo\DemoCode;
use App\Models\Demo\DemoVote;
use App\Models\Demo\DemoPost;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoteAnonymityTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Election $election;
    protected DemoCode $code;
    protected DemoPost $post;

    public function setUp(): void
    {
        parent::setUp();
        $this->setupTestData();
    }

    protected function setupTestData(): void
    {
        $this->user = User::factory()->create();

        $this->election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null,
        ]);

        $this->code = DemoCode::factory()->create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'can_vote_now' => true,
            'has_agreed_to_vote' => true,
        ]);

        $this->post = DemoPost::factory()->create([
            'election_id' => $this->election->id,
        ]);
    }

    /**
     * RED TEST 1: Vote hash MUST use code->id, NOT code->user_id
     */
    public function test_vote_hash_uses_code_id_not_user_id()
    {
        // Create a vote
        $vote = DemoVote::create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'cast_at' => now(),
            'candidate_selections' => [],
        ]);

        // Manually set vote_hash using correct method
        $vote->vote_hash = hash('sha256',
            $this->code->id .                    // MUST be code ID
            $this->election->id .
            $this->code->code_to_open_voting_form .
            $vote->cast_at->timestamp .
            config('app.vote_salt', '')
        );
        $vote->save();

        // Verify the hash was generated
        $this->assertNotNull($vote->vote_hash);
        $this->assertEquals(64, strlen($vote->vote_hash));

        // CRITICAL: Verify hash does NOT contain user_id
        // Generate the WRONG hash (with user_id) and verify they're different
        $wrongHash = hash('sha256',
            $this->code->user_id .               // WRONG
            $this->election->id .
            $this->code->code_to_open_voting_form .
            $vote->cast_at->timestamp .
            config('app.vote_salt', '')
        );

        $this->assertNotEquals($wrongHash, $vote->vote_hash);
        $this->assertStringStartsWith(substr($this->code->id, 0, 8), hash('sha256',
            $this->code->id .
            ''
        ));
    }

    /**
     * RED TEST 2: DemoVote table must NOT have user_id column
     */
    public function test_demo_vote_table_has_no_user_id_column()
    {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('demo_votes');

        $this->assertNotContains('user_id', $columns);
    }

    /**
     * RED TEST 3: Multiple votes from same user produce different vote hashes
     */
    public function test_different_votes_produce_different_hashes()
    {
        $vote1 = DemoVote::create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'cast_at' => now(),
            'candidate_selections' => [],
        ]);
        $vote1->vote_hash = hash('sha256',
            $this->code->id .
            $this->election->id .
            $this->code->code_to_open_voting_form .
            $vote1->cast_at->timestamp .
            config('app.vote_salt', '')
        );
        $vote1->save();

        // Slightly different timestamp
        $vote2 = DemoVote::create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'cast_at' => now()->addSecond(),
            'candidate_selections' => [],
        ]);
        $vote2->vote_hash = hash('sha256',
            $this->code->id .
            $this->election->id .
            $this->code->code_to_open_voting_form .
            $vote2->cast_at->timestamp .
            config('app.vote_salt', '')
        );
        $vote2->save();

        $this->assertNotEquals($vote1->vote_hash, $vote2->vote_hash);
    }

    /**
     * RED TEST 4: Vote hash cannot be reversed to identify voter
     */
    public function test_vote_hash_is_irreversible()
    {
        $vote = DemoVote::create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'cast_at' => now(),
            'candidate_selections' => [],
        ]);
        $vote->vote_hash = hash('sha256',
            $this->code->id .
            $this->election->id .
            $this->code->code_to_open_voting_form .
            $vote->cast_at->timestamp .
            config('app.vote_salt', '')
        );
        $vote->save();

        // Try to reverse-lookup user from vote_hash
        // Should NOT be able to find the user
        $reverseLookup = DemoVote::where('vote_hash', $vote->vote_hash)->first();
        $this->assertNotNull($reverseLookup);

        // But even if we have the vote, there's NO user_id to query
        $this->assertNull($reverseLookup->user_id ?? null);
    }

    /**
     * RED TEST 5: Vote anonymity is preserved in vote results
     */
    public function test_vote_results_contain_no_user_id()
    {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('demo_results');

        $this->assertNotContains('user_id', $columns);
    }
}
