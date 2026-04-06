<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\DemoVote;
use App\Models\DemoCode;
use App\Models\DemoVoterSlug;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemoVoteModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * RED TEST 1: DemoVote $casts includes cast_at
     */
    public function test_casts_includes_cast_at()
    {
        $casts = (new DemoVote())->getCasts();

        $this->assertArrayHasKey('cast_at', $casts);
        $this->assertEquals('datetime', $casts['cast_at']);
    }

    /**
     * RED TEST 2: DemoVote $casts includes no_vote_posts
     */
    public function test_casts_includes_no_vote_posts()
    {
        $casts = (new DemoVote())->getCasts();

        $this->assertArrayHasKey('no_vote_posts', $casts);
        $this->assertEquals('array', $casts['no_vote_posts']);
    }

    /**
     * RED TEST 3: DemoVote booted() calls parent booted()
     */
    public function test_booted_calls_parent_booted()
    {
        // Create an organisation for the demo election
        $org = Organisation::factory()->create(['type' => 'tenant']);

        // Create a demo election within an organisation
        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => $org->id,
        ]);

        // Parent booted() validates election_id exists and is valid
        // This test verifies parent::booted() is being called
        $vote = DemoVote::create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'cast_at' => now(),
            'voted_at' => now(),
            'receipt_hash' => hash('sha256', 'test_receipt'),  // Required by parent
        ]);

        $this->assertNotNull($vote->id);
        $this->assertEquals($election->id, $vote->election_id);
    }

    /**
     * ✅ REMOVED: test_demo_vote_belongs_to_demo_code
     *
     * REASON: This test expected a backwards relationship that would break anonymity.
     * Votes must NEVER have a relationship back to Code (which contains user_id).
     *
     * Correct pattern:
     * - Code → Vote (✅ Code can find its vote via voting_code)
     * - Vote → Code (❌ NEVER - vote should not link to code)
     *
     * The voting_code column on votes is a data field, not a relationship target.
     */

    /**
     * ✅ REMOVED: test_demo_vote_belongs_to_demo_voter_slug
     *
     * REASON: While voter_slug_id exists, adding a relationship here creates
     * a traceable link from vote → voter_slug → user, violating anonymity.
     *
     * Correct pattern:
     * - Only the controller/service layer knows the vote-to-slug mapping
     * - The model itself must not expose this relationship
     */

    /**
     * RED TEST 6: vote_hash is in fillable
     */
    public function test_vote_hash_in_fillable()
    {
        $fillable = (new DemoVote())->getFillable();

        $this->assertContains('vote_hash', $fillable);
    }

    /**
     * RED TEST 7: no_vote_posts is in fillable
     */
    public function test_no_vote_posts_in_fillable()
    {
        $fillable = (new DemoVote())->getFillable();

        $this->assertContains('no_vote_posts', $fillable);
    }

    /**
     * RED TEST 8: DemoVote can be created with all required fields
     */
    public function test_demo_vote_can_be_created_with_all_fields()
    {
        // Create an organisation for the demo election
        $org = Organisation::factory()->create(['type' => 'tenant']);

        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => $org->id,
        ]);

        $vote = DemoVote::create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'vote_hash' => hash('sha256', 'test_hash'),
            'receipt_hash' => hash('sha256', 'test_receipt'),  // Required by parent
            'no_vote_posts' => ['post1', 'post2'],
            'cast_at' => now(),
            'voted_at' => now(),
            'voting_code' => 'CODE123',
            'candidate_selections' => ['candidate_1', 'candidate_5'],
            'device_fingerprint_hash' => hash('sha256', 'device_sig'),
            'device_metadata_anonymized' => ['browser' => 'Chrome', 'platform' => 'Linux'],
        ]);

        $this->assertNotNull($vote->id);
        $this->assertEquals(['post1', 'post2'], $vote->no_vote_posts);
        $this->assertEquals(['candidate_1', 'candidate_5'], $vote->candidate_selections);
        $this->assertNotNull($vote->vote_hash);
        $this->assertNotNull($vote->device_fingerprint_hash);
    }
}
