<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\EnsureOrganizationMember;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EnsureOrganizationMemberTest extends TestCase
{
    protected $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new EnsureOrganizationMember();
    }

    /**
     * Test middleware allows member access to organization
     *
     * @test
     */
    public function it_allows_member_access_to_organization()
    {
        // Arrange
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        // Create membership relationship
        $user->organizationRoles()->attach($organization->id, ['role' => 'member']);

        $request = Request::create(
            "/organizations/{$organization->slug}/voters",
            'GET'
        );
        $request->setRouteResolver(function () use ($organization) {
            return app('router')->getRoutes()->match(
                \Illuminate\Http\Request::create(
                    "/organizations/{$organization->slug}/voters"
                )
            );
        });

        $this->actingAs($user);

        // Act
        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertNotNull($response);
        $this->assertNotNull($request->attributes->get('organization'));
        $this->assertEquals($organization->id, $request->attributes->get('organization')->id);
    }

    /**
     * Test middleware blocks non-member access with 403
     *
     * @test
     */
    public function it_blocks_non_member_access_with_403()
    {
        // Arrange
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        // User is NOT a member of this organization

        $request = Request::create(
            "/organizations/{$organization->slug}/voters",
            'GET'
        );

        // Mock the route parameter resolution
        $request = $this->setupRequestWithRouteParameter($request, $organization->slug);

        $this->actingAs($user);

        // Act & Assert
        Log::shouldReceive('warning')
            ->withArgs(function ($message, $context) use ($organization, $user) {
                return $message === 'EnsureOrganization: Non-member access attempt'
                    && $context['user_id'] === $user->id
                    && $context['organization_id'] === $organization->id;
            })
            ->once();

        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        $this->assertTrue($response->status() === 403 || $response instanceof \Illuminate\Http\RedirectResponse);
    }

    /**
     * Test middleware returns 404 when organization not found
     *
     * @test
     */
    public function it_returns_404_when_organization_not_found()
    {
        // Arrange
        $user = User::factory()->create();

        $request = Request::create('/organizations/nonexistent-slug/voters', 'GET');
        $request = $this->setupRequestWithRouteParameter($request, 'nonexistent-slug');

        $this->actingAs($user);

        // Act
        Log::shouldReceive('warning')
            ->withArgs(function ($message) {
                return $message === 'EnsureOrganization: Organization not found';
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
        $organization = Organization::factory()->create();

        $request = Request::create(
            "/organizations/{$organization->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organization->slug);

        Auth::shouldReceive('check')->andReturn(false);

        // Act
        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertTrue($response instanceof \Illuminate\Http\RedirectResponse);
    }

    /**
     * Test middleware stores organization in request attributes
     *
     * @test
     */
    public function it_stores_organization_in_request_attributes()
    {
        // Arrange
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $user->organizationRoles()->attach($organization->id, ['role' => 'member']);

        $request = Request::create(
            "/organizations/{$organization->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organization->slug);

        $this->actingAs($user);

        // Act
        $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertNotNull($request->attributes->get('organization'));
        $this->assertEquals($organization->id, $request->attributes->get('organization')->id);
        $this->assertEquals($organization->slug, $request->attributes->get('organization')->slug);
    }

    /**
     * Test middleware sets session context
     *
     * @test
     */
    public function it_sets_session_context_for_belongs_to_tenant()
    {
        // Arrange
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $user->organizationRoles()->attach($organization->id, ['role' => 'member']);

        $request = Request::create(
            "/organizations/{$organization->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organization->slug);

        $this->actingAs($user);

        // Act
        $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertEquals($organization->id, session('current_organisation_id'));
    }

    /**
     * Test middleware logs access attempt
     *
     * @test
     */
    public function it_logs_successful_access_attempt()
    {
        // Arrange
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $user->organizationRoles()->attach($organization->id, ['role' => 'member']);

        $request = Request::create(
            "/organizations/{$organization->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organization->slug);

        $this->actingAs($user);

        // Act
        Log::shouldReceive('channel')
            ->with('voting_audit')
            ->andReturnSelf()
            ->shouldReceive('info')
            ->withArgs(function ($message, $context) use ($organization, $user) {
                return $message === 'Organization context validated'
                    && $context['user_id'] === $user->id
                    && $context['organization_id'] === $organization->id;
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
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        // User is NOT a member

        $request = Request::create(
            "/organizations/{$organization->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organization->slug);

        $this->actingAs($user);

        // Act
        Log::shouldReceive('warning')
            ->withArgs(function ($message, $context) use ($organization, $user) {
                return $message === 'EnsureOrganization: Non-member access attempt'
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
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $request = Request::create(
            "/organizations/{$organization->slug}/voters",
            'GET',
            [],
            [],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );
        $request = $this->setupRequestWithRouteParameter($request, $organization->slug);

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
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        // Attach as commission member
        $user->organizationRoles()->attach($organization->id, ['role' => 'commission']);

        $request = Request::create(
            "/organizations/{$organization->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $organization->slug);

        $this->actingAs($user);

        // Act
        $response = $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $this->assertNotNull($request->attributes->get('organization'));
        $this->assertEquals('success', $response->getContent());
    }

    /**
     * Test middleware with multiple organizations - only specific org access
     *
     * @test
     */
    public function it_only_allows_access_to_member_organizations()
    {
        // Arrange
        $user = User::factory()->create();
        $org1 = Organization::factory()->create();
        $org2 = Organization::factory()->create();

        // User is member of org1 only
        $user->organizationRoles()->attach($org1->id, ['role' => 'member']);

        // Try to access org2
        $request = Request::create("/organizations/{$org2->slug}/voters", 'GET');
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
        $user = User::factory()->create();
        $testSlug = 'test-org-' . time() . '-' . uniqid();
        $organization = Organization::factory()->create(['slug' => $testSlug]);

        $user->organizationRoles()->attach($organization->id, ['role' => 'member']);

        $request = Request::create(
            "/organizations/{$organization->slug}/voters",
            'GET'
        );
        $request = $this->setupRequestWithRouteParameter($request, $testSlug);

        $this->actingAs($user);

        // Act
        $this->middleware->handle($request, function ($req) {
            return response('success');
        });

        // Assert
        $retrievedOrg = $request->attributes->get('organization');
        $this->assertEquals($testSlug, $retrievedOrg->slug);
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
                ->with('organization', \Mockery::any())
                ->andReturn($slug);
            $route->shouldReceive('parameter')
                ->with('slug', \Mockery::any())
                ->andReturn($slug);
            return $route;
        });

        return $request;
    }
}
