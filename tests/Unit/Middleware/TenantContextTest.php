<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\TenantContext;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TenantContextTest extends TestCase
{
    use RefreshDatabase;

    private TenantContext $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new TenantContext();
    }

    /** @test */
    public function it_sets_tenant_context_for_authenticated_user(): void
    {
        $organisation = Organisation::factory()->create();
        $user = User::factory()->create(['organisation_id' => $organisation->id]);
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $request = Request::create('/test', 'GET');
        $this->middleware->handle($request, fn($req) => response('OK'));

        $this->assertEquals($organisation->id, session('current_organisation_id'));
    }

    /** @test */
    public function it_denies_access_if_user_has_no_organisation_role(): void
    {
        $organisation = Organisation::factory()->create();
        $user = User::factory()->create(['organisation_id' => $organisation->id]);
        // No UserOrganisationRole created - user has no role

        $this->actingAs($user);

        $request = Request::create('/test', 'GET');

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->expectExceptionCode(403);

        $this->middleware->handle($request, fn($req) => response('OK'));
    }

    /** @test */
    public function it_handles_demo_users_without_organisation(): void
    {
        $demoUser = User::factory()->create(['organisation_id' => null]);
        $this->actingAs($demoUser);

        $request = Request::create('/test', 'GET');
        $this->middleware->handle($request, fn($req) => response('OK'));

        $this->assertNull(session('current_organisation_id'));
    }

    /** @test */
    public function it_uses_cache_for_organisation_lookup(): void
    {
        $organisation = Organisation::factory()->create();
        $user = User::factory()->create(['organisation_id' => $organisation->id]);
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        // Clear cache before first request
        Cache::forget("user.{$user->id}.organisation_id");

        $request = Request::create('/test', 'GET');
        $this->middleware->handle($request, fn($req) => response('OK'));

        // Verify value is cached
        $cachedValue = Cache::get("user.{$user->id}.organisation_id");
        $this->assertEquals($organisation->id, $cachedValue);
    }

    /** @test */
    public function it_skips_tenant_context_for_guests(): void
    {
        $request = Request::create('/test', 'GET');
        $this->middleware->handle($request, fn($req) => response('OK'));

        $this->assertNull(session('current_organisation_id'));
    }

    /** @test */
    public function it_prevents_privilege_escalation_when_role_missing(): void
    {
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        $user = User::factory()->create(['organisation_id' => $org1->id]);
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org1->id,
            'role' => 'admin',
        ]);
        // User has NO role in org2

        $this->actingAs($user);

        // Tamper: change user's organisation_id to org2 (has no role there)
        $user->update(['organisation_id' => $org2->id]);
        Cache::forget("user.{$user->id}.organisation_id");

        $request = Request::create('/test', 'GET');

        // Should deny access - user has no role in org2
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->expectExceptionCode(403);

        $this->middleware->handle($request, fn($req) => response('OK'));
    }

    /** @test */
    public function it_allows_users_with_multiple_organisation_roles(): void
    {
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        $user = User::factory()->create(['organisation_id' => $org1->id]);

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

        $this->actingAs($user);

        $request = Request::create('/test', 'GET');
        $this->middleware->handle($request, fn($req) => response('OK'));

        // Should set context to user's primary organisation_id
        $this->assertEquals($org1->id, session('current_organisation_id'));
    }
}
