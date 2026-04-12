<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\VoterSlug;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VoterSlugTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function voter_slug_belongs_to_user()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

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

        $slug = VoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'slug' => 'voter-abc123',
            'current_step' => 1,
            'status' => 'active',
        ]);

        $this->assertEquals($user->id, $slug->user->id);
    }

    /** @test */
    public function voter_slug_belongs_to_election()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

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

        $slug = VoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'slug' => 'voter-abc123',
            'current_step' => 1,
            'status' => 'active',
        ]);

        $this->assertEquals($election->id, $slug->election->id);
    }

    /** @test */
    public function voter_slug_belongs_to_organisation()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

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

        $slug = VoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'slug' => 'voter-abc123',
            'current_step' => 1,
            'status' => 'active',
        ]);

        $this->assertEquals($org->id, $slug->organisation->id);
    }

    /** @test */
    public function voter_slug_has_unique_slug_per_election()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

        $user1Id = Str::uuid()->toString();
        $user2Id = Str::uuid()->toString();

        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user1Id,
            $org->id,
            'User 1',
            'user1@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user2Id,
            $org->id,
            'User 2',
            'user2@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        $user1 = User::find($user1Id);
        $user2 = User::find($user2Id);

        VoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user1->id,
            'slug' => 'unique-slug-abc',
            'current_step' => 1,
            'status' => 'active',
        ]);

        // Attempt to create duplicate user-election pair should fail
        $this->expectException(\Illuminate\Database\QueryException::class);
        VoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user1->id,
            'slug' => 'different-slug',
            'current_step' => 1,
            'status' => 'active',
        ]);
    }

    /** @test */
    public function voter_slug_scope_for_organisation()
    {
        $org1 = Organisation::factory()->tenant()->create();
        $org2 = Organisation::factory()->tenant()->create();

        $election1 = Election::factory()->forOrganisation($org1)->create();
        $election2 = Election::factory()->forOrganisation($org2)->create();

        $user1Id = Str::uuid()->toString();
        $user2Id = Str::uuid()->toString();

        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user1Id,
            $org1->id,
            'User 1',
            'user1@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user2Id,
            $org2->id,
            'User 2',
            'user2@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        $user1 = User::find($user1Id);
        $user2 = User::find($user2Id);

        $slug1 = VoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org1->id,
            'election_id' => $election1->id,
            'user_id' => $user1->id,
            'slug' => 'org1-slug',
            'current_step' => 1,
            'status' => 'active',
        ]);

        $slug2 = VoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org2->id,
            'election_id' => $election2->id,
            'user_id' => $user2->id,
            'slug' => 'org2-slug',
            'current_step' => 1,
            'status' => 'active',
        ]);

        $org1_slugs = VoterSlug::forOrganisation($org1->id)->get();
        $org2_slugs = VoterSlug::forOrganisation($org2->id)->get();

        $this->assertCount(1, $org1_slugs);
        $this->assertEquals($slug1->id, $org1_slugs->first()->id);

        $this->assertCount(1, $org2_slugs);
        $this->assertEquals($slug2->id, $org2_slugs->first()->id);
    }

    /** @test */
    public function voter_slug_scope_for_election()
    {
        $org = Organisation::factory()->tenant()->create();
        $election1 = Election::factory()->forOrganisation($org)->create();
        $election2 = Election::factory()->forOrganisation($org)->create();

        $user1Id = Str::uuid()->toString();
        $user2Id = Str::uuid()->toString();

        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user1Id,
            $org->id,
            'User 1',
            'user1@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user2Id,
            $org->id,
            'User 2',
            'user2@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        $user1 = User::find($user1Id);
        $user2 = User::find($user2Id);

        $slug1 = VoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election1->id,
            'user_id' => $user1->id,
            'slug' => 'elec1-slug',
            'current_step' => 1,
            'status' => 'active',
        ]);

        $slug2 = VoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election2->id,
            'user_id' => $user2->id,
            'slug' => 'elec2-slug',
            'current_step' => 1,
            'status' => 'active',
        ]);

        $elec1_slugs = VoterSlug::forElection($election1->id)->get();
        $elec2_slugs = VoterSlug::forElection($election2->id)->get();

        $this->assertCount(1, $elec1_slugs);
        $this->assertEquals($slug1->id, $elec1_slugs->first()->id);

        $this->assertCount(1, $elec2_slugs);
        $this->assertEquals($slug2->id, $elec2_slugs->first()->id);
    }

    /** @test */
    public function voter_slug_tracks_current_step()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

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

        $slug = VoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'slug' => 'step-tracker',
            'current_step' => 1,
            'status' => 'active',
        ]);

        $this->assertEquals(1, $slug->current_step);

        $slug->update(['current_step' => 3]);
        $this->assertEquals(3, $slug->refresh()->current_step);

        $slug->update(['current_step' => 5, 'status' => 'voted']);
        $this->assertEquals(5, $slug->refresh()->current_step);
        $this->assertEquals('voted', $slug->refresh()->status);
    }

    /** @test */
    public function voter_slug_one_way_relationship_to_votes()
    {
        // CRITICAL ANONYMITY TEST
        // VoterSlug → Vote is allowed (one-way)
        // Vote → VoterSlug must NOT exist

        $slug = new VoterSlug();

        // Verify votes() relationship exists
        $this->assertTrue(method_exists($slug, 'votes'));

        // Verify no inverse relationship on Vote model
        // (This is tested in Phase B.3 - Vote model tests)
    }
}
