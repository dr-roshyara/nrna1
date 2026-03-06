<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Post;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Candidacy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function post_belongs_to_organisation()
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

        $this->assertEquals($org->id, $post->organisation->id);
        $this->assertIsIterable($post->organisation->posts);
    }

    /** @test */
    public function post_belongs_to_election()
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

        $this->assertEquals($election->id, $post->election->id);
    }

    /** @test */
    public function post_has_many_candidacies()
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

        $this->assertCount(1, $post->candidacies()->withoutGlobalScopes()->get());
        $this->assertEquals($candidacy->id, $post->candidacies()->withoutGlobalScopes()->first()->id);
    }

    /** @test */
    public function post_scope_for_organisation_filters_correctly()
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

        // Test forOrganisation scope
        $org1Posts = Post::forOrganisation($org1->id)->get();
        $this->assertCount(1, $org1Posts);
        $this->assertEquals($post1->id, $org1Posts->first()->id);

        $org2Posts = Post::forOrganisation($org2->id)->get();
        $this->assertCount(1, $org2Posts);
        $this->assertEquals($post2->id, $org2Posts->first()->id);
    }

    /** @test */
    public function post_scope_for_election_filters_correctly()
    {
        $org = Organisation::factory()->tenant()->create();

        $election1 = Election::factory()->forOrganisation($org)->create();
        $election2 = Election::factory()->forOrganisation($org)->create();

        $post1 = Post::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election1->id,
            'name' => 'Election1 Post',
            'is_national_wide' => true,
            'required_number' => 1,
        ]);

        $post2 = Post::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election2->id,
            'name' => 'Election2 Post',
            'is_national_wide' => true,
            'required_number' => 1,
        ]);

        // Test forElection scope
        $election1Posts = Post::forElection($election1->id)->get();
        $this->assertCount(1, $election1Posts);
        $this->assertEquals($post1->id, $election1Posts->first()->id);

        $election2Posts = Post::forElection($election2->id)->get();
        $this->assertCount(1, $election2Posts);
        $this->assertEquals($post2->id, $election2Posts->first()->id);
    }
}
