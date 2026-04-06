<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Result;
use App\Models\Vote;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResultTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function result_belongs_to_vote()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();
        $post = Post::factory()->forElection($election)->create();

        session(['current_organisation_id' => $org->id]);

        // Create user directly with organisation_id
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

        $candidacy = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => $userId,
            'name' => 'Test Candidate',
            'description' => 'Test Description',
            'status' => 'pending',
        ]);

        $vote = Vote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'vote_hash' => hash('sha256', 'test' . now()->timestamp),
            'cast_at' => now(),
        ]);

        $result = Result::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'vote_id' => $vote->id,
            'post_id' => $post->id,
            'candidacy_id' => $candidacy->id,
        ]);

        $this->assertEquals($vote->id, $result->vote->id);
    }

    /** @test */
    public function result_belongs_to_election()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();
        $post = Post::factory()->forElection($election)->create();

        session(['current_organisation_id' => $org->id]);

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

        $candidacy = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => $userId,
            'name' => 'Test Candidate',
            'description' => 'Test Description',
            'status' => 'pending',
        ]);

        $vote = Vote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'vote_hash' => hash('sha256', 'test' . now()->timestamp),
            'cast_at' => now(),
        ]);

        $result = Result::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'vote_id' => $vote->id,
            'post_id' => $post->id,
            'candidacy_id' => $candidacy->id,
        ]);

        $this->assertEquals($election->id, $result->election->id);
    }

    /** @test */
    public function result_belongs_to_post()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();
        $post = Post::factory()->forElection($election)->create();

        session(['current_organisation_id' => $org->id]);

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

        $candidacy = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => $userId,
            'name' => 'Test Candidate',
            'description' => 'Test Description',
            'status' => 'pending',
        ]);

        $vote = Vote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'vote_hash' => hash('sha256', 'test' . now()->timestamp),
            'cast_at' => now(),
        ]);

        $result = Result::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'vote_id' => $vote->id,
            'post_id' => $post->id,
            'candidacy_id' => $candidacy->id,
        ]);

        $this->assertEquals($post->id, $result->post->id);
    }

    /** @test */
    public function result_has_NO_user_relationship()
    {
        // CRITICAL ANONYMITY TEST
        // Verify that Result model does NOT have a user() relationship
        // This ensures results cannot be linked back to users

        $result = new Result();

        // Relationship must not exist - verify no user() method
        $this->assertFalse(method_exists($result, 'user'));

        // Verify no user_id column exists in database
        $columns = DB::getSchemaBuilder()->getColumnListing('results');
        $this->assertNotContains('user_id', $columns);
    }

    /** @test */
    public function result_scope_for_organisation_filters()
    {
        $org1 = Organisation::factory()->tenant()->create();
        $org2 = Organisation::factory()->tenant()->create();

        $election1 = Election::factory()->forOrganisation($org1)->create();
        $election2 = Election::factory()->forOrganisation($org2)->create();

        $post1 = Post::factory()->forElection($election1)->create();
        $post2 = Post::factory()->forElection($election2)->create();

        session(['current_organisation_id' => $org1->id]);
        $userId1 = Str::uuid()->toString();
        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $userId1,
            $org1->id,
            'User 1',
            'user1@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);
        $candidacy1 = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org1->id,
            'post_id' => $post1->id,
            'user_id' => $userId1,
            'name' => 'Candidate 1',
            'status' => 'pending',
        ]);

        $vote1 = Vote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org1->id,
            'election_id' => $election1->id,
            'vote_hash' => hash('sha256', 'vote1' . now()->timestamp),
            'cast_at' => now(),
        ]);

        session(['current_organisation_id' => $org2->id]);
        $userId2 = Str::uuid()->toString();
        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $userId2,
            $org2->id,
            'User 2',
            'user2@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);
        $candidacy2 = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org2->id,
            'post_id' => $post2->id,
            'user_id' => $userId2,
            'name' => 'Candidate 2',
            'status' => 'pending',
        ]);

        $vote2 = Vote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org2->id,
            'election_id' => $election2->id,
            'vote_hash' => hash('sha256', 'vote2' . now()->timestamp),
            'cast_at' => now(),
        ]);

        $result1 = Result::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org1->id,
            'election_id' => $election1->id,
            'vote_id' => $vote1->id,
            'post_id' => $post1->id,
            'candidacy_id' => $candidacy1->id,
        ]);

        $result2 = Result::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org2->id,
            'election_id' => $election2->id,
            'vote_id' => $vote2->id,
            'post_id' => $post2->id,
            'candidacy_id' => $candidacy2->id,
        ]);

        $org1_results = Result::forOrganisation($org1->id)->get();
        $org2_results = Result::forOrganisation($org2->id)->get();

        $this->assertCount(1, $org1_results);
        $this->assertEquals($result1->id, $org1_results->first()->id);

        $this->assertCount(1, $org2_results);
        $this->assertEquals($result2->id, $org2_results->first()->id);
    }

    /** @test */
    public function result_scope_for_election_filters()
    {
        $org = Organisation::factory()->tenant()->create();
        $election1 = Election::factory()->forOrganisation($org)->create();
        $election2 = Election::factory()->forOrganisation($org)->create();

        $post1 = Post::factory()->forElection($election1)->create();
        $post2 = Post::factory()->forElection($election2)->create();

        session(['current_organisation_id' => $org->id]);

        $userId1 = Str::uuid()->toString();
        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $userId1,
            $org->id,
            'User 1',
            'user1@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);
        $candidacy1 = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post1->id,
            'user_id' => $userId1,
            'name' => 'Candidate 1',
            'status' => 'pending',
        ]);

        $userId2 = Str::uuid()->toString();
        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $userId2,
            $org->id,
            'User 2',
            'user2@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);
        $candidacy2 = Candidacy::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post2->id,
            'user_id' => $userId2,
            'name' => 'Candidate 2',
            'status' => 'pending',
        ]);

        $vote1 = Vote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election1->id,
            'vote_hash' => hash('sha256', 'vote1' . now()->timestamp),
            'cast_at' => now(),
        ]);

        $vote2 = Vote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election2->id,
            'vote_hash' => hash('sha256', 'vote2' . now()->timestamp),
            'cast_at' => now(),
        ]);

        $result1 = Result::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election1->id,
            'vote_id' => $vote1->id,
            'post_id' => $post1->id,
            'candidacy_id' => $candidacy1->id,
        ]);

        $result2 = Result::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election2->id,
            'vote_id' => $vote2->id,
            'post_id' => $post2->id,
            'candidacy_id' => $candidacy2->id,
        ]);

        $elec1_results = Result::forElection($election1)->get();
        $elec2_results = Result::forElection($election2)->get();

        $this->assertCount(1, $elec1_results);
        $this->assertEquals($result1->id, $elec1_results->first()->id);

        $this->assertCount(1, $elec2_results);
        $this->assertEquals($result2->id, $elec2_results->first()->id);
    }
}
