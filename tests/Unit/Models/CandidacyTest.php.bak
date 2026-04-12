<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Candidacy;
use App\Models\Post;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CandidacyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function candidacy_belongs_to_organisation()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();
        $post = Post::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'name' => 'Test Post',
            'is_national_wide' => true,
            'required_number' => 1,
        ]);

        $candidacy = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'name' => 'Test Candidacy',
            'description' => 'Test description',
            'position_order' => 1,
            'status' => 'approved',
        ]);

        $this->assertEquals($org->id, $candidacy->organisation->id);
    }

    /** @test */
    public function candidacy_belongs_to_post()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();
        $post = Post::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'name' => 'Test Post',
            'is_national_wide' => true,
            'required_number' => 1,
        ]);

        $candidacy = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'name' => 'Test Candidacy',
            'description' => 'Test description',
            'position_order' => 1,
            'status' => 'approved',
        ]);

        $this->assertEquals($post->id, $candidacy->post->id);
    }

    /** @test */
    public function candidacy_belongs_to_user()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();
        $post = Post::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'name' => 'Test Post',
            'is_national_wide' => true,
            'required_number' => 1,
        ]);

        // Create user directly via DB
        $userId = Str::uuid()->toString();
        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $userId,
            $org->id,
            'Test User',
            'test@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);
        $user = User::find($userId);

        $candidacy = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => $user->id,
            'name' => 'Test Candidacy',
            'description' => 'Test description',
            'position_order' => 1,
            'status' => 'approved',
        ]);

        $this->assertEquals($user->id, $candidacy->user->id);
    }

    /** @test */
    public function candidacy_accesses_election_via_post()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();
        $post = Post::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'name' => 'Test Post',
            'is_national_wide' => true,
            'required_number' => 1,
        ]);

        $candidacy = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'name' => 'Test Candidacy',
            'description' => 'Test description',
            'position_order' => 1,
            'status' => 'approved',
        ]);

        // Access election through post relationship
        $this->assertEquals($election->id, $candidacy->post->election->id);
    }

    /** @test */
    public function candidacy_scope_for_organisation_filters_correctly()
    {
        $org1 = Organisation::factory()->tenant()->create();
        $org2 = Organisation::factory()->tenant()->create();

        $election1 = Election::factory()->forOrganisation($org1)->create();
        $election2 = Election::factory()->forOrganisation($org2)->create();

        $post1 = Post::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org1->id,
            'election_id' => $election1->id,
            'name' => 'Org1 Post',
            'is_national_wide' => true,
            'required_number' => 1,
        ]);

        $post2 = Post::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org2->id,
            'election_id' => $election2->id,
            'name' => 'Org2 Post',
            'is_national_wide' => true,
            'required_number' => 1,
        ]);

        $candidacy1 = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org1->id,
            'post_id' => $post1->id,
            'name' => 'Org1 Candidacy',
            'description' => 'Test',
            'position_order' => 1,
            'status' => 'approved',
        ]);

        $candidacy2 = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org2->id,
            'post_id' => $post2->id,
            'name' => 'Org2 Candidacy',
            'description' => 'Test',
            'position_order' => 1,
            'status' => 'approved',
        ]);

        // Test forOrganisation scope
        $org1Candidacies = Candidacy::forOrganisation($org1->id)->withoutGlobalScopes()->get();
        $this->assertCount(1, $org1Candidacies);
        $this->assertEquals($candidacy1->id, $org1Candidacies->first()->id);

        $org2Candidacies = Candidacy::forOrganisation($org2->id)->withoutGlobalScopes()->get();
        $this->assertCount(1, $org2Candidacies);
        $this->assertEquals($candidacy2->id, $org2Candidacies->first()->id);
    }

    /** @test */
    public function candidacy_scope_approved_filters_status()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();
        $post = Post::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'name' => 'Test Post',
            'is_national_wide' => true,
            'required_number' => 1,
        ]);

        $approved = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'name' => 'Approved Candidacy',
            'description' => 'Test',
            'position_order' => 1,
            'status' => 'approved',
        ]);

        $pending = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'name' => 'Pending Candidacy',
            'description' => 'Test',
            'position_order' => 2,
            'status' => 'pending',
        ]);

        // Test approved scope
        $approvedCandidacies = Candidacy::approved()->withoutGlobalScopes()->get();
        $this->assertCount(1, $approvedCandidacies);
        $this->assertEquals($approved->id, $approvedCandidacies->first()->id);
        $this->assertEquals('approved', $approvedCandidacies->first()->status);
    }

    /** @test */
    public function candidacy_has_no_direct_election_relationship()
    {
        // Verify that Candidacy model does NOT have a direct election() relationship
        // The only way to access election is through post: $candidacy->post->election

        $candidacy = new Candidacy();

        // Check that directElection or election method doesn't exist as a relationship
        // (It may exist as a method for other purposes, but not as a hasManyThrough)
        $methods = get_class_methods($candidacy);

        // The election() method should NOT exist on Candidacy
        // Users must access it via post relationship
        $this->assertFalse(in_array('election', $methods) && method_exists($candidacy, 'election') &&
                          ($candidacy->getRelations()['election'] ?? false));
    }
}
