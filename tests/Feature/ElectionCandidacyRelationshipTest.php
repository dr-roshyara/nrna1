<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Election;
use App\Models\Candidacy;
use App\Models\DemoCandidate;

/**
 * ElectionCandidacyRelationshipTest
 *
 * Tests the relationships between Elections, Candidacies, and DemoCandidates
 * Ensures data is properly scoped by election
 */
class ElectionCandidacyRelationshipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Candidacy can be created with election_id
     */
    public function test_candidacy_can_be_created_with_election_id()
    {
        $election = Election::factory()->real()->create();

        $candidacy = Candidacy::create([
            'election_id' => $election->id,
            'post_id' => 'POST001',
            'user_id' => 'user_123',
            'candidacy_id' => 'Test Candidate',
            'position_order' => 1,
        ]);

        $this->assertNotNull($candidacy->election_id);
        $this->assertEquals($election->id, $candidacy->election_id);
    }

    /**
     * Test: Candidacy belongs to election
     */
    public function test_candidacy_belongs_to_election()
    {
        $election = Election::factory()->real()->create();

        $candidacy = Candidacy::create([
            'election_id' => $election->id,
            'post_id' => 'POST002',
            'user_id' => 'user_124',
            'candidacy_id' => 'Test Candidate 2',
            'position_order' => 1,
        ]);

        $this->assertTrue($candidacy->election()->exists());
        $this->assertEquals($election->id, $candidacy->election->id);
    }

    /**
     * Test: Election has many candidacies
     */
    public function test_election_has_many_candidacies()
    {
        $election = Election::factory()->real()->create();

        $candidacy1 = Candidacy::create([
            'election_id' => $election->id,
            'post_id' => 'POST003',
            'user_id' => 'user_125',
            'candidacy_id' => 'Test 3',
            'position_order' => 1,
        ]);

        $candidacy2 = Candidacy::create([
            'election_id' => $election->id,
            'post_id' => 'POST004',
            'user_id' => 'user_126',
            'candidacy_id' => 'Test 4',
            'position_order' => 2,
        ]);

        $this->assertEquals(2, $election->candidacies()->count());
        $this->assertTrue($election->candidacies()->pluck('id')->contains($candidacy1->id));
        $this->assertTrue($election->candidacies()->pluck('id')->contains($candidacy2->id));
    }

    /**
     * Test: Different elections have independent candidacies
     */
    public function test_different_elections_have_independent_candidacies()
    {
        $election1 = Election::factory()->real()->create();
        $election2 = Election::factory()->real()->create();

        $candidacy1 = Candidacy::create([
            'election_id' => $election1->id,
            'post_id' => 'POST005',
            'user_id' => 'user_127',
            'candidacy_id' => 'Test 5',
            'position_order' => 1,
        ]);

        $candidacy2 = Candidacy::create([
            'election_id' => $election2->id,
            'post_id' => 'POST006',
            'user_id' => 'user_128',
            'candidacy_id' => 'Test 6',
            'position_order' => 1,
        ]);

        $this->assertEquals(1, $election1->candidacies()->count());
        $this->assertEquals(1, $election2->candidacies()->count());
        $this->assertFalse($election1->candidacies()->pluck('id')->contains($candidacy2->id));
        $this->assertFalse($election2->candidacies()->pluck('id')->contains($candidacy1->id));
    }

    /**
     * Test: Candidacy with election_id is fillable
     */
    public function test_candidacy_election_id_is_fillable()
    {
        $election = Election::factory()->real()->create();

        $candidacy = Candidacy::create([
            'election_id' => $election->id,
            'post_id' => 'POST001',
            'user_id' => 'user_123',
            'candidacy_id' => 'John Doe',
            'position_order' => 1,
        ]);

        $this->assertNotNull($candidacy->election_id);
        $this->assertEquals($election->id, $candidacy->election_id);
    }

    /**
     * Test: Deleting election cascades to candidacies
     */
    public function test_deleting_election_cascades_to_candidacies()
    {
        $election = Election::factory()->real()->create();

        $candidacy = Candidacy::create([
            'election_id' => $election->id,
            'post_id' => 'POST007',
            'user_id' => 'user_129',
            'candidacy_id' => 'Test Cascade',
            'position_order' => 1,
        ]);

        $candidacyId = $candidacy->id;

        // Delete the election
        $election->delete();

        // Candidacy should also be deleted (cascade delete)
        $this->assertNull(Candidacy::find($candidacyId));
    }

    /**
     * Test: DemoCandidate can be created with election_id
     */
    public function test_demo_candidate_can_be_created_with_election_id()
    {
        $election = Election::factory()->demo()->create();

        $candidate = DemoCandidate::factory()->forElection($election)->create();

        $this->assertNotNull($candidate->election_id);
        $this->assertEquals($election->id, $candidate->election_id);
    }

    /**
     * Test: DemoCandidate belongs to election
     */
    public function test_demo_candidate_belongs_to_election()
    {
        $election = Election::factory()->demo()->create();

        $candidate = DemoCandidate::factory()->forElection($election)->create();

        $this->assertTrue($candidate->election()->exists());
        $this->assertEquals($election->id, $candidate->election->id);
    }

    /**
     * Test: Real and demo candidates are properly scoped by election type
     */
    public function test_real_and_demo_candidates_scoped_by_election()
    {
        $realElection = Election::factory()->real()->create();
        $demoElection = Election::factory()->demo()->create();

        // Create real candidate
        $realCandidate = Candidacy::create([
            'election_id' => $realElection->id,
            'post_id' => 'POST008',
            'user_id' => 'user_130',
            'candidacy_id' => 'Real Candidate',
            'position_order' => 1,
        ]);

        // Create demo candidate
        $demoCandidate = DemoCandidate::factory()->forElection($demoElection)->create();

        // Verify real candidates in candidacies table
        $this->assertEquals(1, Candidacy::where('election_id', $realElection->id)->count());

        // Verify demo candidates in demo_candidacies table
        $this->assertEquals(1, DemoCandidate::where('election_id', $demoElection->id)->count());

        // Verify they exist in different tables
        $this->assertNotNull(Candidacy::find($realCandidate->id));
        $this->assertNotNull(DemoCandidate::find($demoCandidate->id));
    }
}
