<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoCandidateCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Organisation Admins Can Create Custom Demo Candidates
     *
     * Org admins should be able to create custom demo posts and candidates
     * scoped to their organisation. Other organisations should not see this data.
     */
    public function test_org_admin_can_create_custom_demo_candidates()
    {
        // Org admin user
        $admin = User::factory()->create(['organisation_id' => 5]);
        $this->actingAs($admin);
        session(['current_organisation_id' => 5]);

        // Create org-specific demo election
        $election = Election::withoutGlobalScopes()->create([
            'name' => 'Org 5 Demo',
            'slug' => 'org5-demo',
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        // Create custom post
        $post = DemoPost::create([
            'post_id' => 'custom-position-' . $election->id,
            'election_id' => $election->id,
            'name' => 'Custom Position',
            'nepali_name' => 'कस्टम पद',
            'position_order' => 1,
            'required_number' => 2,
            'organisation_id' => 5
        ]);

        // Create custom candidates
        $candidate1 = DemoCandidacy::create([
            'candidacy_id' => 'custom-cand-1-' . $election->id,
            'user_id' => 'custom-user-1',
            'election_id' => $election->id,
            'post_id' => $post->post_id,
            'user_name' => 'Custom Candidate 1',
            'image_path_1' => 'candidate1.jpg',
            'organisation_id' => 5
        ]);

        $candidate2 = DemoCandidacy::create([
            'candidacy_id' => 'custom-cand-2-' . $election->id,
            'user_id' => 'custom-user-2',
            'election_id' => $election->id,
            'post_id' => $post->post_id,
            'user_name' => 'Custom Candidate 2',
            'image_path_1' => 'candidate2.jpg',
            'organisation_id' => 5
        ]);

        // Verify custom data exists with correct org
        $this->assertDatabaseHas('demo_posts', [
            'id' => $post->id,
            'organisation_id' => 5,
            'name' => 'Custom Position'
        ]);

        $this->assertDatabaseHas('demo_candidacies', [
            'candidacy_id' => $candidate1->candidacy_id,
            'organisation_id' => 5,
            'user_name' => 'Custom Candidate 1'
        ]);

        $this->assertDatabaseHas('demo_candidacies', [
            'candidacy_id' => $candidate2->candidacy_id,
            'organisation_id' => 5,
            'user_name' => 'Custom Candidate 2'
        ]);

        // Other orgs cannot see this data
        $otherUser = User::factory()->create(['organisation_id' => 6]);
        $this->actingAs($otherUser);
        session(['current_organisation_id' => 6]);

        $this->assertCount(0, DemoPost::all());
        $this->assertCount(0, DemoCandidacy::all());
    }
}
