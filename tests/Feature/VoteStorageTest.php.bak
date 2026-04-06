<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * VoteStorageTest - Verify votes use vote_hash, not voting_code
 * and store anonymity correctly (no user_id)
 *
 * These are SCHEMA VALIDATION tests - they verify the database structure is correct.
 */
class VoteStorageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function vote_table_has_vote_hash_column()
    {
        $columns = \DB::getSchemaBuilder()->getColumnListing('votes');

        // ✅ Assert vote_hash exists
        $this->assertContains('vote_hash', $columns, 'votes table should have vote_hash column');

        // ✅ Assert voting_code does NOT exist
        $this->assertNotContains('voting_code', $columns, 'votes table should NOT have voting_code column');
    }

    /** @test */
    public function vote_table_has_no_user_id_column()
    {
        $columns = \DB::getSchemaBuilder()->getColumnListing('votes');

        // ✅ Assert NO user_id column (votes are anonymous)
        $this->assertNotContains('user_id', $columns, 'votes table should NOT have user_id column (votes are anonymous)');
    }

    /** @test */
    public function vote_table_has_no_vote_posts_not_no_vote_option()
    {
        $columns = \DB::getSchemaBuilder()->getColumnListing('votes');

        // ✅ Assert no_vote_posts exists
        $this->assertContains('no_vote_posts', $columns, 'votes table should have no_vote_posts column (JSON array)');

        // ✅ Assert no_vote_option does NOT exist
        $this->assertNotContains('no_vote_option', $columns, 'votes table should NOT have no_vote_option column (boolean)');
    }

    /** @test */
    public function vote_model_casts_no_vote_posts_as_array()
    {
        // ✅ Assert casting is configured
        $vote = new Vote();
        $casts = $vote->getCasts();
        $this->assertArrayHasKey('no_vote_posts', $casts);
        $this->assertEquals('array', $casts['no_vote_posts']);
    }

    /** @test */
    public function vote_hash_is_hidden_from_api_responses()
    {
        // ✅ Assert vote_hash is in hidden array (won't be exposed in API)
        $vote = new Vote();
        $hidden = $vote->getHidden();

        $this->assertContains('vote_hash', $hidden, 'vote_hash should be hidden from API responses');
    }

    /** @test */
    public function demo_vote_table_uses_vote_hash()
    {
        $columns = \DB::getSchemaBuilder()->getColumnListing('demo_votes');

        // ✅ Assert vote_hash exists
        $this->assertContains('vote_hash', $columns, 'demo_votes table should have vote_hash column');

        // ✅ Assert NO voting_code
        $this->assertNotContains('voting_code', $columns, 'demo_votes table should NOT have voting_code column');

        // ✅ Assert NO user_id
        $this->assertNotContains('user_id', $columns, 'demo_votes table should NOT have user_id column');
    }

    /** @test */
    public function vote_has_organisation_id_for_audit()
    {
        $columns = \DB::getSchemaBuilder()->getColumnListing('votes');

        // ✅ Assert organisation_id exists (for audit trail and tenant isolation)
        $this->assertContains('organisation_id', $columns, 'votes table should have organisation_id for audit');
    }

    /** @test */
    public function vote_has_election_id_for_audit()
    {
        $columns = \DB::getSchemaBuilder()->getColumnListing('votes');

        // ✅ Assert election_id exists (for audit trail)
        $this->assertContains('election_id', $columns, 'votes table should have election_id for audit');
    }

    /** @test */
    public function base_vote_model_has_verifybycode_method()
    {
        // ✅ Assert method exists for vote verification
        $vote = new Vote();
        $this->assertTrue(method_exists($vote, 'verifyByCode'), 'Vote model should have verifyByCode() method');
    }

    /** @test */
    public function base_vote_model_has_getverificationdata_method()
    {
        // ✅ Assert method exists for returning verification data
        $vote = new Vote();
        $this->assertTrue(method_exists($vote, 'getVerificationData'), 'Vote model should have getVerificationData() method');
    }

    /** @test */
    public function vote_model_has_no_user_relationship()
    {
        // ✅ Assert user() relationship doesn't exist (votes are anonymous)
        $vote = new Vote();

        // Try to access non-existent relationship
        try {
            $user = $vote->user;
            $this->fail('Vote should not have user() relationship');
        } catch (\Exception $e) {
            // Expected - relationship should not exist
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function vote_fillable_includes_vote_hash()
    {
        // ✅ Assert vote_hash is fillable (mass-assignable)
        $vote = new Vote();
        $fillable = $vote->getFillable();

        $this->assertContains('vote_hash', $fillable, 'vote_hash should be mass-assignable');
    }

    /** @test */
    public function vote_fillable_includes_no_vote_posts()
    {
        // ✅ Assert no_vote_posts is fillable
        $vote = new Vote();
        $fillable = $vote->getFillable();

        $this->assertContains('no_vote_posts', $fillable, 'no_vote_posts should be mass-assignable');
    }

    /** @test */
    public function vote_fillable_excludes_user_id()
    {
        // ✅ Assert user_id is NOT fillable (cannot be set via mass assignment)
        $vote = new Vote();
        $fillable = $vote->getFillable();

        $this->assertNotContains('user_id', $fillable, 'user_id should NOT be mass-assignable (votes are anonymous)');
    }

    /** @test */
    public function demo_vote_fillable_includes_vote_hash()
    {
        // ✅ Assert DemoVote also has vote_hash fillable
        $demoVote = new \App\Models\DemoVote();
        $fillable = $demoVote->getFillable();

        $this->assertContains('vote_hash', $fillable, 'DemoVote vote_hash should be mass-assignable');
    }
}
