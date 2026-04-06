<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Organisation;
use App\Models\Election;
use App\Models\Post;
use App\Models\Candidacy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ElectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function election_belongs_to_organisation()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

        $this->assertEquals($org->id, $election->organisation_id);
        $this->assertEquals($org->id, $election->organisation->id);
    }

    /** @test */
    public function election_has_many_posts()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();
        
        Post::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'name' => 'Test Post',
            'is_national_wide' => false,
            'required_number' => 1,
        ]);

        $this->assertCount(1, $election->posts);
    }

    /** @test */
    public function election_has_many_candidacies_through_posts()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();
        
        $post = Post::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'name' => 'Test Post',
            'is_national_wide' => false,
            'required_number' => 1,
        ]);

        $userId = \Illuminate\Support\Str::uuid()->toString();
        \Illuminate\Support\Facades\DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $userId,
            $org->id,
            'Test User',
            'test@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        Candidacy::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => $userId,
            'name' => 'Candidate Name',
            'status' => 'approved',
        ]);

        $this->assertCount(1, $election->candidacies);
    }

    /** @test */
    public function election_scope_for_organisation_filters_correctly()
    {
        $org1 = Organisation::factory()->tenant()->create();
        $org2 = Organisation::factory()->tenant()->create();
        
        Election::factory()->forOrganisation($org1)->create();
        Election::factory()->forOrganisation($org2)->create();

        $org1Elections = Election::forOrganisation($org1->id)->count();
        $this->assertEquals(1, $org1Elections);
    }

    /** @test */
    public function election_is_demo_returns_correct_boolean()
    {
        $org = Organisation::factory()->tenant()->create();
        $demo = Election::factory()->forOrganisation($org)->demo()->create();
        $real = Election::factory()->forOrganisation($org)->real()->create();

        $this->assertTrue($demo->isDemo());
        $this->assertFalse($real->isDemo());
    }

    /** @test */
    public function election_is_real_returns_correct_boolean()
    {
        $org = Organisation::factory()->tenant()->create();
        $demo = Election::factory()->forOrganisation($org)->demo()->create();
        $real = Election::factory()->forOrganisation($org)->real()->create();

        $this->assertFalse($demo->isReal());
        $this->assertTrue($real->isReal());
    }

    /** @test */
    public function election_is_currently_active_checks_dates_and_flag()
    {
        $org = Organisation::factory()->tenant()->create();
        
        // Active election
        $active = Election::factory()->forOrganisation($org)->create([
            'is_active' => true,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);
        
        // Inactive flag
        $inactive = Election::factory()->forOrganisation($org)->create([
            'is_active' => false,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $this->assertTrue($active->isCurrentlyActive());
        $this->assertFalse($inactive->isCurrentlyActive());
    }

    /** @test */
    public function election_with_essential_relations_scope_loads_data()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

        $loaded = Election::withEssentialRelations()->find($election->id);

        $this->assertNotNull($loaded->organisation);
        $this->assertEquals($org->id, $loaded->organisation->id);
    }
}
