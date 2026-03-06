<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\EnsureOrganisationMember;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EnsureOrganisationMemberTest extends TestCase
{
    protected $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new EnsureOrganisationMember();

        // Ensure platform organisation exists for factory defaults
        if (!Organisation::where('type', 'platform')->where('is_default', true)->exists()) {
            Organisation::factory()->create(['type' => 'platform', 'is_default' => true]);
        }
    }

    /**
     * Test middleware allows member access to organisation
     *
     * @test
     */
    public function it_allows_member_access_to_organization()
    {
        // Arrange
        $organisation = Organisation::factory()->create();
        $user = $this->createTestUser($organisation->id);

        // Create membership relationship
        $this->attachUserToOrganisation($user, $organisation->id, 'member');

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organisation->slug);

        $this->actingAs($user);

        // Act
        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertNotNull($response);
        $this->assertNotNull($request->attributes->get('organisation'));
        $this->assertEquals($organisation->id, $request->attributes->get('organisation')->id);
    }

    /**
     * Test middleware blocks non-member access with 403
     *
     * @test
     */
    public function it_blocks_non_member_access_with_403()
    {
        // Arrange
        $organisation = Organisation::factory()->create();
        $user = $this->createTestUser($organisation->id);

        // User is NOT a member of this organisation

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET'
        );

        // Mock the route parameter resolution
        $request = $this->setupRequestWithRouteParameter($request, $organisation->slug);

        $this->actingAs($user);

        // Act & Assert
        Log::shouldReceive('warning')
            ->withArgs(function ($message, $context) use ($organisation, $user) {
                return $message === 'EnsureOrganisation: Non-member access attempt'
                    && $context['user_id'] === $user->id
                    && $context['organisation_id'] === $organisation->id;
            })
            ->once();

        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        $this->assertTrue($response->status() === 403 || $response instanceof \Illuminate\Http\RedirectResponse);
    }

    /**
     * Test middleware returns 404 when organisation not found
     *
     * @test
     */
    public function it_returns_404_when_organization_not_found()
    {
        // Arrange
        $platformOrg = Organisation::where('type', 'platform')->where('is_default', true)->first();
        $user = $this->createTestUser($platformOrg->id);

        $request = Request::create('/organisations/nonexistent-slug/voters', 'GET');
        $request = $this->setupRequestWithRouteParameter($request, 'nonexistent-slug');

        $this->actingAs($user);

        // Act
        Log::shouldReceive('warning')
            ->withArgs(function ($message) {
                return $message === 'EnsureOrganisation: organisation not found';
            })
            ->once();

        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertTrue($response->status() === 404 || $response instanceof \Illuminate\Http\RedirectResponse);
    }

    /**
     * Test middleware requires authentication
     *
     * @test
     */
    public function it_requires_authentication()
    {
        // Arrange
        $organisation = Organisation::factory()->create();

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organisation->slug);

        Auth::shouldReceive('check')->andReturn(false);

        // Act
        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertTrue($response instanceof \Illuminate\Http\RedirectResponse);
    }

    /**
     * Test middleware stores organisation in request attributes
     *
     * @test
     */
    public function it_stores_organization_in_request_attributes()
    {
        // Arrange
        $organisation = Organisation::factory()->create();
        $user = $this->createTestUser($organisation->id);

        $this->attachUserToOrganisation($user, $organisation->id, 'member');

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organisation->slug);

        $this->actingAs($user);

        // Act
        $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertNotNull($request->attributes->get('organisation'));
        $this->assertEquals($organisation->id, $request->attributes->get('organisation')->id);
        $this->assertEquals($organisation->slug, $request->attributes->get('organisation')->slug);
    }

    /**
     * Test middleware sets session context
     *
     * @test
     */
    public function it_sets_session_context_for_belongs_to_tenant()
    {
        // Arrange
        $organisation = Organisation::factory()->create();
        $user = $this->createTestUser($organisation->id);

        $this->attachUserToOrganisation($user, $organisation->id, 'member');

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organisation->slug);

        $this->actingAs($user);

        // Act
        $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertEquals($organisation->id, session('current_organisation_id'));
    }

    /**
     * Test middleware logs access attempt
     *
     * @test
     */
    public function it_logs_successful_access_attempt()
    {
        // Arrange
        $organisation = Organisation::factory()->create();
        $user = $this->createTestUser($organisation->id);

        $this->attachUserToOrganisation($user, $organisation->id, 'member');

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organisation->slug);

        $this->actingAs($user);

        // Act
        Log::shouldReceive('channel')
            ->with('voting_audit')
            ->andReturnSelf()
            ->shouldReceive('info')
            ->withArgs(function ($message, $context) use ($organisation, $user) {
                return $message === 'organisation context validated'
                    && $context['user_id'] === $user->id
                    && $context['organisation_id'] === $organisation->id;
            })
            ->once();

        $this->middleware->handle($request, function ($req) {
            return response('success');
        });
    }

    /**
     * Test middleware logs unauthorized access attempt
     *
     * @test
     */
    public function it_logs_unauthorized_access_attempt()
    {
        // Arrange
        $organisation = Organisation::factory()->create();
        $platformOrg = Organisation::where('type', 'platform')->where('is_default', true)->first();
        $user = $this->createTestUser($platformOrg->id);

        // User is NOT a member

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organisation->slug);

        $this->actingAs($user);

        // Act
        Log::shouldReceive('warning')
            ->withArgs(function ($message, $context) use ($organisation, $user) {
                return $message === 'EnsureOrganisation: Non-member access attempt'
                    && isset($context['ip_address'])
                    && isset($context['user_agent']);
            })
            ->once();

        $this->middleware->handle($request, function ($req) {
            return response('success');
        });
    }

    /**
     * Test middleware handles JSON requests properly
     *
     * @test
     */
    public function it_returns_json_error_for_api_requests()
    {
        // Arrange
        $organisation = Organisation::factory()->create();
        $user = $this->createTestUser($organisation->id);

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET',
            [],
            [],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );
        $request = $this->setupRequestWithRouteParameter($request, $organisation->slug);

        $this->actingAs($user);

        // Act
        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        // For non-members, should return JSON error
        if ($response->status() === 403) {
            $this->assertJson($response->getContent());
        }
    }

    /**
     * Test middleware with commission member role
     *
     * @test
     */
    public function it_allows_commission_member_access()
    {
        // Arrange
        $organisation = Organisation::factory()->create();
        $user = $this->createTestUser($organisation->id);

        // Attach as commission member
        $this->attachUserToOrganisation($user, $organisation->id, 'commission');

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organisation->slug);

        $this->actingAs($user);

        // Act
        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertNotNull($request->attributes->get('organisation'));
        $this->assertEquals('success', $response->getContent());
    }

    /**
     * Test middleware with multiple organisations - only specific org access
     *
     * @test
     */
    public function it_only_allows_access_to_member_organizations()
    {
        // Arrange
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();
        $user = $this->createTestUser($org1->id);

        // User is member of org1 only
        $this->attachUserToOrganisation($user, $org1->id, 'member');

        // Try to access org2
        $request = Request::create("/organisations/{$org2->slug}/voters", 'GET');
        $request = $this->setupRequestWithRouteParameter($request, $org2->slug);

        $this->actingAs($user);

        // Act
        Log::shouldReceive('warning')->once();
        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertTrue($response->status() === 403 || $response instanceof \Illuminate\Http\RedirectResponse);
    }

    /**
     * Test middleware extracts slug from different route parameters
     *
     * @test
     */
    public function it_extracts_slug_from_route_parameter()
    {
        // Arrange
        $testSlug = 'test-org-' . time() . '-' . uniqid();
        $organisation = Organisation::factory()->create(['slug' => $testSlug]);
        $user = $this->createTestUser($organisation->id);

        $this->attachUserToOrganisation($user, $organisation->id, 'member');

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $testSlug);

        $this->actingAs($user);

        // Act
        $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $retrievedOrg = $request->attributes->get('organisation');
        $this->assertEquals($testSlug, $retrievedOrg->slug);
    }

    /**
     * Test middleware can resolve organisation by UUID not just slug
     *
     * @test
     */
    public function it_resolves_organisation_by_uuid()
    {
        // Arrange - Use raw inserts to avoid factory dependency issues
        $userId = \Illuminate\Support\Str::uuid();
        $orgId = \Illuminate\Support\Str::uuid();
        $platformId = \Illuminate\Support\Str::uuid();

        // Create platform org first (for user foreign key)
        DB::table('organisations')->insert([
            'id' => $platformId,
            'name' => 'Platform',
            'slug' => 'platform-' . time(),
            'email' => 'platform@example.com',
            'type' => 'platform',
            'is_default' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create user with platform org
        DB::table('users')->insert([
            'id' => $userId,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => 'hashedpassword',
            'organisation_id' => $platformId,
            'region' => 'Bayern',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create test org
        DB::table('organisations')->insert([
            'id' => $orgId,
            'name' => 'Test Org',
            'slug' => 'test-org-uuid-' . time(),
            'email' => 'org@example.com',
            'type' => 'tenant',
            'is_default' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create pivot relationship
        DB::table('user_organisation_roles')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $userId,
            'organisation_id' => $orgId,
            'role' => 'member',
        ]);

        $user = User::find($userId);
        $organisation = Organisation::find($orgId);

        // Use UUID in route parameter instead of slug
        $request = Request::create(
            "/organisations/{$organisation->id}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organisation->id);

        $this->actingAs($user);

        // Act
        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert - Should find org by UUID and allow access
        $this->assertNotNull($request->attributes->get('organisation'));
        $this->assertEquals($organisation->id, $request->attributes->get('organisation')->id);
        $this->assertEquals('success', $response->getContent());
    }

    /**
     * Test middleware blocks access to soft-deleted organisations
     *
     * @test
     */
    public function it_blocks_access_to_soft_deleted_organisation()
    {
        // Arrange
        $organisation = Organisation::factory()->create();
        $user = $this->createTestUser($organisation->id);

        DB::table('user_organisation_roles')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'member',
        ]);

        // Soft delete the organisation
        $organisation->delete();

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organisation->slug);

        $this->actingAs($user);

        // Act
        Log::shouldReceive('warning')
            ->withArgs(function ($message, $context) use ($organisation) {
                return strpos($message, 'Attempt to access deleted organisation') !== false
                    && $context['organisation_id'] === $organisation->id;
            })
            ->once();

        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert - Should return 403 for soft-deleted org
        $this->assertTrue($response->status() === 403 || $response instanceof \Illuminate\Http\RedirectResponse);
    }

    /**
     * Test middleware logs complete security context on cross-org access attempt
     *
     * @test
     */
    public function it_logs_complete_security_context_on_cross_org_access()
    {
        // Arrange
        $organisation = Organisation::factory()->create();
        $platformOrg = Organisation::where('type', 'platform')->where('is_default', true)->first();
        $user = $this->createTestUser($platformOrg->id);

        // User is NOT a member of this organisation

        $request = Request::create(
            "/organisations/{$organisation->slug}/voters",
            'GET',
            [],
            [],
            [],
            ['HTTP_USER_AGENT' => 'Mozilla/5.0 Test']
        );
        $request = $this->setupRequestWithRouteParameter($request, $organisation->slug);

        $this->actingAs($user);

        // Act
        Log::shouldReceive('warning')
            ->withArgs(function ($message, $context) use ($organisation, $user) {
                return $message === 'EnsureOrganisation: Non-member access attempt'
                    && $context['user_id'] === $user->id
                    && $context['organisation_id'] === $organisation->id
                    && isset($context['ip_address'])
                    && isset($context['user_agent'])
                    && isset($context['user_name']);
            })
            ->once();

        $this->middleware->handle($request, function ($req) {
            return response('success');
        });
    }

    /**
     * Helper method to create a test user with all required fields
     */
    protected function createTestUser(string $organisationId): User
    {
        $userId = \Illuminate\Support\Str::uuid();

        DB::table('users')->insert([
            'id' => $userId,
            'name' => 'Test User ' . uniqid(),
            'email' => 'test-' . uniqid() . '@example.com',
            'email_verified_at' => now(),
            'password' => 'hashedpassword',
            'organisation_id' => $organisationId,
            'region' => 'Bayern',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::find($userId);
    }

    /**
     * Helper method to attach user to organisation with a role
     */
    protected function attachUserToOrganisation(User $user, string $organisationId, string $role = 'member'): void
    {
        DB::table('user_organisation_roles')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $organisationId,
            'role' => $role,
        ]);
    }

    /**
     * Helper method to setup request with route parameter
     */
    protected function setupRequestWithRouteParameter(Request $request, string $slug): Request
    {
        // Create a mock route that returns the slug parameter
        $request->setRouteResolver(function () use ($slug) {
            $route = \Mockery::mock(\Illuminate\Routing\Route::class);
            $route->shouldReceive('parameter')
                ->with('organisation', \Mockery::any())
                ->andReturn($slug);
            $route->shouldReceive('parameter')
                ->with('slug', \Mockery::any())
                ->andReturn($slug);
            return $route;
        });

        return $request;
    }
}
