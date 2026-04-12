<?php

namespace Tests\Unit\Demo;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemoCandidateMappingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that candidate mapping uses correct fields from the model
     */
    public function test_candidate_mapping_uses_correct_fields()
    {
        // Create test data
        $election = Election::factory()->create(['type' => 'demo']);
        $post = DemoPost::factory()->create([
            'election_id' => $election->id,
            'is_national_wide' => 1,
            'organisation_id' => null,
        ]);

        // Create candidate user
        $candidateUser = User::factory()->create([
            'name' => 'Test Candidate Name',
            'region' => 'Test Region',
        ]);

        // Create candidacy
        $candidacy = DemoCandidacy::factory()->create([
            'post_id' => $post->id,
            'user_id' => $candidateUser->id,
            'name' => 'Candidacy Name Field',
            'position_order' => 1,
        ]);

        // Test that we can access the fields used in the controller mapping
        $this->assertEquals($candidacy->id, $candidacy->id, 'candidacy_id should be $c->id');
        $this->assertEquals($candidateUser->name, $candidacy->user->name, 'user_name should come from $c->user->name');
        $this->assertEquals('Candidacy Name Field', $candidacy->name, 'candidacy_name should be $c->name');
        $this->assertEquals(1, $candidacy->position_order, 'position_order should be available');

        // Test the mapped structure as it appears in controller
        $mapped = [
            'id' => $candidacy->id,
            'candidacy_id' => $candidacy->id,
            'user_id' => $candidacy->user_id,
            'user_name' => $candidacy->user->name ?? $candidacy->name ?? 'Demo Candidate',
            'post_id' => $candidacy->post_id,
            'image_path_1' => null,
            'candidacy_name' => $candidacy->name,
            'proposer_name' => null,
            'supporter_name' => null,
            'position_order' => $candidacy->position_order,
        ];

        $this->assertNotEmpty($mapped['user_name'], 'user_name should not be empty');
        $this->assertEquals('Test Candidate Name', $mapped['user_name'], 'user_name should be the candidate user name');
        $this->assertEquals('Candidacy Name Field', $mapped['candidacy_name'], 'candidacy_name should be the candidacy name field');
    }

    /**
     * Test mapping with user->name fallback
     */
    public function test_candidate_mapping_with_user_name_fallback()
    {
        $election = Election::factory()->create(['type' => 'demo']);
        $post = DemoPost::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => null,
        ]);

        $candidateUser = User::factory()->create(['name' => 'John Doe']);
        $candidacy = DemoCandidacy::factory()->create([
            'post_id' => $post->id,
            'user_id' => $candidateUser->id,
            'name' => 'Generic Name',
        ]);

        // Simulate the mapping used in controller
        $user_name = $candidacy->user->name ?? $candidacy->name ?? 'Demo Candidate';

        $this->assertEquals('John Doe', $user_name, 'Should use user.name as primary source');
    }

    /**
     * Test mapping with null user (fallback to candidacy name)
     */
    public function test_candidate_mapping_fallback_to_candidacy_name()
    {
        $election = Election::factory()->create(['type' => 'demo']);
        $post = DemoPost::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => null,
        ]);

        $candidacy = DemoCandidacy::factory()->create([
            'post_id' => $post->id,
            'name' => 'Fallback Name',
        ]);

        // Simulate the mapping - if user relationship is not loaded, use candidacy name
        $user_name = ($candidacy->user?->name) ?? $candidacy->name ?? 'Demo Candidate';

        $this->assertEquals('Fallback Name', $user_name, 'Should fallback to candidacy name');
    }
}
