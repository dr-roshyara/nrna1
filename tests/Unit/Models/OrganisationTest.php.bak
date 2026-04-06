<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\Election;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrganisationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function organisation_has_many_elections()
    {
        $org = Organisation::factory()->tenant()->create();
        $this->assertIsIterable($org->elections);
    }

    /** @test */
    public function organisation_has_many_posts()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

        // Create post directly without factory to bypass BelongsToTenant scope
        $post = Post::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'name' => 'Test Post',
            'is_national_wide' => false,
            'required_number' => 1,
        ]);

        $this->assertIsIterable($org->posts);
        $this->assertCount(1, $org->posts);
    }

    /** @test */
    public function organisation_has_many_user_organisation_roles()
    {
        $org = Organisation::factory()->tenant()->create();

        // Create user directly via DB to bypass Eloquent hooks
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
        $user = User::find($userId);

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $this->assertCount(1, $org->userOrganisationRoles);
    }

    /** @test */
    public function organisation_belongs_to_many_users_via_pivot()
    {
        $org = Organisation::factory()->tenant()->create();

        // Create user directly via DB to bypass Eloquent hooks
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
        $user = User::find($userId);

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $userIds = $org->users()->pluck('users.id')->toArray();
        $this->assertContains($user->id, $userIds);
    }

    /** @test */
    public function organisation_is_platform_returns_correct_boolean()
    {
        $platform = Organisation::factory()->platform()->create();
        $tenant = Organisation::factory()->tenant()->create();

        $this->assertTrue($platform->isPlatform());
        $this->assertFalse($tenant->isPlatform());
    }

    /** @test */
    public function organisation_is_tenant_returns_correct_boolean()
    {
        $platform = Organisation::factory()->platform()->create();
        $tenant = Organisation::factory()->tenant()->create();

        $this->assertFalse($platform->isTenant());
        $this->assertTrue($tenant->isTenant());
    }

    /** @test */
    public function organisation_get_default_platform_returns_platform_org()
    {
        // Delete any existing default platforms first
        Organisation::where('is_default', true)->delete();

        $platform = Organisation::factory()->platform()->state(['is_default' => true])->create();

        $retrieved = Organisation::getDefaultPlatform();

        $this->assertEquals($platform->id, $retrieved->id);
        $this->assertTrue($retrieved->isPlatform());
    }

    /** @test */
    public function organisation_users_pivot_includes_role()
    {
        $org = Organisation::factory()->tenant()->create();

        // Create user directly via DB to bypass Eloquent hooks
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
        $user = User::find($userId);

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $userWithPivot = $org->users()->first();
        $this->assertEquals('admin', $userWithPivot->pivot->role);
    }
}
