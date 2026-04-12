<?php

namespace Tests\Unit\Models\Demo;

use Tests\TestCase;
use App\Models\DemoVoterSlug;
use App\Models\VoterSlug;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoVoterSlugTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function demo_voter_slug_uses_demo_voter_slugs_table()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        // Create user with raw insert to ensure organisation_id is set
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

        session(['current_organisation_id' => $org->id]);

        // Use raw insert to test table isolation (only use columns that exist in migration)
        DB::insert('insert into demo_voter_slugs (id, organisation_id, election_id, user_id, slug, current_step, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?)', [
            $demoVoterSlugId = Str::uuid()->toString(),
            $org->id,
            $election->id,
            $userId,
            'demo-slug-' . Str::random(8),
            1,
            now(),
            now(),
        ]);

        // Verify it's in demo_voter_slugs table
        $demoVoterSlug = DB::table('demo_voter_slugs')->where('id', $demoVoterSlugId)->first();
        $this->assertNotNull($demoVoterSlug);

        // Real voter slugs table should not have this record
        $realVoterSlug = DB::table('voter_slugs')->where('id', $demoVoterSlugId)->first();
        $this->assertNull($realVoterSlug);
    }

    /** @test */
    public function demo_voter_slug_belongs_to_user()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        // Create user with raw insert to ensure organisation_id is set
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

        session(['current_organisation_id' => $org->id]);

        $demoVoterSlug = DemoVoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $userId,
            'slug' => 'test-slug-' . Str::random(8),
            'current_step' => 1,
        ]);

        $this->assertEquals($userId, $demoVoterSlug->user->id);
    }

    /** @test */
    public function demo_voter_slug_belongs_to_election()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        // Create user with raw insert to ensure organisation_id is set
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

        session(['current_organisation_id' => $org->id]);

        $demoVoterSlug = DemoVoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $userId,
            'slug' => 'test-slug-' . Str::random(8),
            'current_step' => 1,
        ]);

        $this->assertEquals($election->id, $demoVoterSlug->election->id);
    }

    /** @test */
    public function demo_voter_slug_tracks_step_progress()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        // Create user with raw insert to ensure organisation_id is set
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

        session(['current_organisation_id' => $org->id]);

        $demoVoterSlug = DemoVoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $userId,
            'slug' => 'test-slug-' . Str::random(8),
            'current_step' => 1,
            'step_meta' => ['ip' => '192.168.1.1', 'started_at' => now()->toIso8601String()],
        ]);

        $this->assertEquals(1, $demoVoterSlug->current_step);
        $this->assertIsArray($demoVoterSlug->step_meta);
        $this->assertEquals('192.168.1.1', $demoVoterSlug->step_meta['ip']);

        // Update to step 2
        $demoVoterSlug->update([
            'current_step' => 2,
            'step_meta' => ['ip' => '192.168.1.2', 'started_at' => now()->toIso8601String()],
        ]);

        $demoVoterSlug->refresh();
        $this->assertEquals(2, $demoVoterSlug->current_step);
        $this->assertEquals('192.168.1.2', $demoVoterSlug->step_meta['ip']);
    }

    /** @test */
    public function demo_voter_slug_scope_for_election_filters()
    {
        $org = Organisation::factory()->tenant()->create();
        $election1 = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);
        $election2 = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        // Create user with raw insert to ensure organisation_id is set
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

        session(['current_organisation_id' => $org->id]);

        DemoVoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election1->id,
            'user_id' => $userId,
            'slug' => 'slug-1-' . Str::random(8),
            'current_step' => 1,
        ]);

        DemoVoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election2->id,
            'user_id' => $userId,
            'slug' => 'slug-2-' . Str::random(8),
            'current_step' => 1,
        ]);

        $election1Slugs = DemoVoterSlug::forElection($election1->id)->get();
        $election2Slugs = DemoVoterSlug::forElection($election2->id)->get();

        $this->assertCount(1, $election1Slugs);
        $this->assertCount(1, $election2Slugs);
        $this->assertEquals($election1->id, $election1Slugs->first()->election_id);
        $this->assertEquals($election2->id, $election2Slugs->first()->election_id);
    }

    /** @test */
    public function demo_voter_slug_scope_for_user_filters()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        // Create user1 with raw insert
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

        // Create user2 with raw insert
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

        session(['current_organisation_id' => $org->id]);

        DemoVoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $userId1,
            'slug' => 'slug-1-' . Str::random(8),
            'current_step' => 1,
        ]);

        DemoVoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $userId2,
            'slug' => 'slug-2-' . Str::random(8),
            'current_step' => 1,
        ]);

        $user1Slugs = DemoVoterSlug::forUser($userId1)->get();
        $user2Slugs = DemoVoterSlug::forUser($userId2)->get();

        $this->assertCount(1, $user1Slugs);
        $this->assertCount(1, $user2Slugs);
        $this->assertEquals($userId1, $user1Slugs->first()->user_id);
        $this->assertEquals($userId2, $user2Slugs->first()->user_id);
    }

    /** @test */
    public function demo_voter_slug_is_demo_returns_true()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        // Create user with raw insert to ensure organisation_id is set
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

        session(['current_organisation_id' => $org->id]);

        $demoVoterSlug = DemoVoterSlug::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $userId,
            'slug' => 'test-slug-' . Str::random(8),
            'current_step' => 1,
        ]);

        $this->assertTrue($demoVoterSlug->isDemo());
    }

    /** @test */
    public function demo_voter_slug_isolation_from_real_slugs()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        // Create user with raw insert to ensure organisation_id is set
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

        session(['current_organisation_id' => $org->id]);

        // Insert demo voter slug using raw insert (only columns that exist in migration)
        $demoVoterSlugId = Str::uuid()->toString();
        DB::insert('insert into demo_voter_slugs (id, organisation_id, election_id, user_id, slug, current_step, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?)', [
            $demoVoterSlugId,
            $org->id,
            $election->id,
            $userId,
            'demo-isolated-' . Str::random(8),
            1,
            now(),
            now(),
        ]);

        // Verify it's only in demo_voter_slugs table
        $demoSlug = DB::table('demo_voter_slugs')->where('id', $demoVoterSlugId)->first();
        $this->assertNotNull($demoSlug);

        // Verify it doesn't appear in voter_slugs table
        $realSlug = DB::table('voter_slugs')->where('id', $demoVoterSlugId)->first();
        $this->assertNull($realSlug);
    }
}
