<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class UserOrganisationRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_organisation_role_belongs_to_user()
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

        $role = UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $this->assertEquals($user->id, $role->user->id);
    }

    /** @test */
    public function user_organisation_role_belongs_to_organisation()
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

        $role = UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $this->assertEquals($org->id, $role->organisation->id);
    }

    /** @test */
    public function user_organisation_role_scope_with_role_filters_correctly()
    {
        $org = Organisation::factory()->tenant()->create();
        $user1Id = \Illuminate\Support\Str::uuid()->toString();
        $user2Id = \Illuminate\Support\Str::uuid()->toString();

        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user1Id,
            $org->id,
            'Test User 1',
            'test1@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);
        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user2Id,
            $org->id,
            'Test User 2',
            'test2@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);
        $user1 = User::find($user1Id);
        $user2 = User::find($user2Id);

        UserOrganisationRole::create([
            'user_id' => $user1->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);
        UserOrganisationRole::create([
            'user_id' => $user2->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        $admins = UserOrganisationRole::where('role', 'admin')->count();
        $members = UserOrganisationRole::where('role', 'member')->count();
        $this->assertEquals(1, $admins);
        $this->assertEquals(1, $members);
    }

    /** @test */
    public function unique_constraint_prevents_duplicate_user_org_pair()
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

        // Attempt to create duplicate - should fail
        $this->expectException(QueryException::class);
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);
    }
}
