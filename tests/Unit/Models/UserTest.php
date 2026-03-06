<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use App\Models\Candidacy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_belongs_to_current_organisation()
    {
        $org = Organisation::factory()->tenant()->create();
        $userId = \Illuminate\Support\Str::uuid()->toString();
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

        $this->assertEquals($org->id, $user->organisation_id);
    }

    /** @test */
    public function user_belongs_to_many_organisations_via_pivot()
    {
        $org1 = Organisation::factory()->tenant()->create();
        $org2 = Organisation::factory()->tenant()->create();
        $userId = \Illuminate\Support\Str::uuid()->toString();
        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $userId,
            $org1->id,
            'Test User',
            'test@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);
        $user = User::find($userId);

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org1->id,
            'role' => 'admin',
        ]);
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org2->id,
            'role' => 'member',
        ]);

        $orgIds = $user->organisations()->pluck('organisations.id')->toArray();
        $this->assertContains($org1->id, $orgIds);
        $this->assertContains($org2->id, $orgIds);
    }

    /** @test */
    public function user_has_many_organisation_roles()
    {
        $org = Organisation::factory()->tenant()->create();
        $userId = \Illuminate\Support\Str::uuid()->toString();
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

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $this->assertCount(1, $user->organisationRoles);
    }

    /** @test */
    public function user_has_many_candidacies()
    {
        $org = Organisation::factory()->tenant()->create();
        $userId = \Illuminate\Support\Str::uuid()->toString();
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

        $election = \App\Models\Election::factory()->forOrganisation($org)->create();
        $post = \App\Models\Post::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'name' => 'Test Post',
            'is_national_wide' => false,
            'required_number' => 1,
        ]);

        Candidacy::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => $user->id,
            'name' => 'Candidate Name',
            'status' => 'approved',
        ]);

        $this->assertCount(1, $user->candidacies);
    }

    /** @test */
    public function user_has_no_direct_vote_relationship()
    {
        $org = Organisation::factory()->tenant()->create();
        $userId = \Illuminate\Support\Str::uuid()->toString();
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

        // Verify the relationship doesn't exist
        $this->assertFalse(method_exists($user, 'votes'));
    }

    /** @test */
    public function user_has_no_direct_result_relationship()
    {
        $org = Organisation::factory()->tenant()->create();
        $userId = \Illuminate\Support\Str::uuid()->toString();
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

        // Verify the relationship doesn't exist
        $this->assertFalse(method_exists($user, 'results'));
    }
}
