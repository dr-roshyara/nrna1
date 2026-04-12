<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * ResultCalculationTest - Verify results use candidate_id, not candidacy_id
 * and store vote_hash for verification
 *
 * These are SCHEMA VALIDATION tests - they verify the database structure is correct.
 */
class ResultCalculationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function results_table_has_candidate_id_not_candidacy_id()
    {
        $columns = \DB::getSchemaBuilder()->getColumnListing('results');

        // ✅ Assert candidate_id exists
        $this->assertContains('candidate_id', $columns, 'results table should have candidate_id column');

        // ✅ Assert candidacy_id does NOT exist
        $this->assertNotContains('candidacy_id', $columns, 'results table should NOT have candidacy_id column');
    }

    /** @test */
    public function results_table_has_vote_hash_column()
    {
        $columns = \DB::getSchemaBuilder()->getColumnListing('results');

        // ✅ Assert vote_hash exists for verification
        $this->assertContains('vote_hash', $columns, 'results table should have vote_hash for verification');
    }

    /** @test */
    public function demo_results_table_uses_candidate_id()
    {
        $columns = \DB::getSchemaBuilder()->getColumnListing('demo_results');

        // ✅ Assert candidate_id exists
        $this->assertContains('candidate_id', $columns, 'demo_results table should have candidate_id column');

        // ✅ Assert candidacy_id does NOT exist
        $this->assertNotContains('candidacy_id', $columns, 'demo_results table should NOT have candidacy_id column');

        // ✅ Assert vote_hash exists
        $this->assertContains('vote_hash', $columns, 'demo_results table should have vote_hash column');
    }

    /** @test */
    public function base_result_model_has_candidate_relationship()
    {
        // ✅ Assert candidate relationship exists (NOT candidacy)
        $result = new Result();
        $this->assertTrue(method_exists($result, 'candidate'), 'Result model should have candidate() relationship');
    }

    /** @test */
    public function base_result_model_has_for_candidate_scope()
    {
        // ✅ Assert forCandidate scope exists (NOT forCandidacy)
        $this->assertTrue(
            method_exists(Result::class, 'scopeForCandidate'),
            'Result model should have forCandidate() scope method'
        );
    }

    /** @test */
    public function base_result_model_has_top_candidates_method()
    {
        // ✅ Assert topCandidatesForPost method exists
        $result = new Result();
        $this->assertTrue(
            method_exists($result, 'topCandidatesForPost'),
            'Result model should have topCandidatesForPost() method'
        );
    }

    /** @test */
    public function result_model_has_count_for_candidate_method()
    {
        // ✅ Assert countForCandidate static method exists
        $this->assertTrue(
            method_exists(Result::class, 'countForCandidate'),
            'Result model should have countForCandidate() static method'
        );
    }

    /** @test */
    public function results_table_has_required_audit_columns()
    {
        $columns = \DB::getSchemaBuilder()->getColumnListing('results');

        // ✅ Assert audit columns exist
        $this->assertContains('election_id', $columns, 'results should have election_id for audit');
        $this->assertContains('post_id', $columns, 'results should have post_id');
        $this->assertContains('vote_id', $columns, 'results should have vote_id');
        $this->assertContains('vote_count', $columns, 'results should have vote_count for aggregation');
        $this->assertContains('vote_hash', $columns, 'results should have vote_hash for verification');
    }

    /** @test */
    public function result_fillable_includes_candidate_id()
    {
        // ✅ Assert candidate_id is mass-assignable
        $result = new Result();
        $fillable = $result->getFillable();

        $this->assertContains('candidate_id', $fillable, 'candidate_id should be mass-assignable');
    }

    /** @test */
    public function result_fillable_includes_vote_hash()
    {
        // ✅ Assert vote_hash is mass-assignable
        $result = new Result();
        $fillable = $result->getFillable();

        $this->assertContains('vote_hash', $fillable, 'vote_hash should be mass-assignable');
    }

    /** @test */
    public function result_fillable_excludes_candidacy_id()
    {
        // ✅ Assert candidacy_id is NOT in fillable (use candidate_id instead)
        $result = new Result();
        $fillable = $result->getFillable();

        $this->assertNotContains('candidacy_id', $fillable, 'candidacy_id should NOT be mass-assignable (use candidate_id)');
    }

    /** @test */
    public function demo_result_fillable_includes_candidate_id()
    {
        // ✅ Assert DemoResult also uses candidate_id
        $demoResult = new \App\Models\DemoResult();
        $fillable = $demoResult->getFillable();

        $this->assertContains('candidate_id', $fillable, 'DemoResult should have candidate_id mass-assignable');
    }

    /** @test */
    public function result_model_has_for_post_scope()
    {
        // ✅ Assert forPost scope exists for filtering
        $this->assertTrue(
            method_exists(Result::class, 'scopeForPost'),
            'Result model should have forPost() scope method'
        );
    }
}
