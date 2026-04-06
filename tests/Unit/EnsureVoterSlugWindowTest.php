<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\VoterSlug;
use App\Http\Middleware\EnsureVoterSlugWindow;
use App\Services\VoterSlugService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * EnsureVoterSlugWindowTest
 *
 * Tests the middleware that validates voter slug ownership, expiration, and activity status.
 * This middleware enforces multi-layer security:
 * - Slug must exist and be a valid VoterSlug instance
 * - Slug must belong to the authenticated user
 * - Slug must belong to the current election
 * - Slug must not be expired
 * - Slug must be marked as active
 */
class EnsureVoterSlugWindowTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $organisation;
    protected User $user;
    protected Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test organisation
        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->organisation->id]);

        // Create test user
        $this->user = User::factory()->create();

        // Create test election
        $this->election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);
    }

    /**
     * RED TEST 1: Valid slug passes through successfully
     *
     * BUSINESS: Authenticated user with valid, non-expired slug should access voting pages
     */
    public function test_valid_slug_passes_through_middleware()
    {
        // Create valid slug
        $slug = VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->addMinutes(30),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
        ]);

        $request = Request::create('/', 'GET');
        $route = $this->createMock('Illuminate\Routing\Route');
        $route->method('parameter')->with('vslug')->willReturn($slug);
        $request->setRouteResolver(function () use ($route) { return $route; });

        $this->actingAs($this->user);
        $request->attributes->set('election', $this->election);

        $middleware = new EnsureVoterSlugWindow();
        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        // BUSINESS ASSERTION: Request should proceed
        $this->assertEquals('OK', $response->getContent());
        $this->assertEquals($this->user->id, $request->attributes->get('voter')->id);
        $this->assertEquals($slug->id, $request->attributes->get('voter_slug')->id);
    }

    /**
     * RED TEST 2: Expired slug is blocked
     *
     * SECURITY: Cannot access voting page with expired session
     */
    public function test_expired_slug_is_blocked()
    {
        // Create expired slug
        $slug = VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->subMinutes(5),  // Expired 5 minutes ago
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
        ]);

        $request = Request::create('/', 'GET');
        $route = $this->createMock('Illuminate\Routing\Route');
        $route->method('parameter')->with('vslug')->willReturn($slug);
        $request->setRouteResolver(function () use ($route) { return $route; });

        $this->actingAs($this->user);
        $request->attributes->set('election', $this->election);

        $middleware = new EnsureVoterSlugWindow();

        // BUSINESS ASSERTION: Should throw 403
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $middleware->handle($request, function ($req) {
            return response('OK');
        });
    }

    /**
     * RED TEST 3: Inactive slug is blocked
     *
     * SECURITY: Cannot use a deactivated slug
     */
    public function test_inactive_slug_is_blocked()
    {
        // Create inactive slug
        $slug = VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->addMinutes(30),
            'is_active' => false,  // Marked inactive
            'status' => 'active',
            'current_step' => 1,
        ]);

        $request = Request::create('/', 'GET');
        $route = $this->createMock('Illuminate\Routing\Route');
        $route->method('parameter')->with('vslug')->willReturn($slug);
        $request->setRouteResolver(function () use ($route) { return $route; });

        $this->actingAs($this->user);
        $request->attributes->set('election', $this->election);

        $middleware = new EnsureVoterSlugWindow();

        // BUSINESS ASSERTION: Should throw 403
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $middleware->handle($request, function ($req) {
            return response('OK');
        });
    }

    /**
     * RED TEST 4: Slug belonging to different user is blocked
     *
     * SECURITY: User cannot use another user's slug (prevents vote theft)
     */
    public function test_slug_from_different_user_is_blocked()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // Create slug for userA
        $slug = VoterSlug::create([
            'user_id' => $userA->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->addMinutes(30),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
        ]);

        $request = Request::create('/', 'GET');
        $route = $this->createMock('Illuminate\Routing\Route');
        $route->method('parameter')->with('vslug')->willReturn($slug);
        $request->setRouteResolver(function () use ($route) { return $route; });

        $this->actingAs($userB);
        $request->attributes->set('election', $this->election);

        $middleware = new EnsureVoterSlugWindow();

        // BUSINESS ASSERTION: Should throw AccessDeniedHttpException
        $this->expectException(AccessDeniedHttpException::class);
        $middleware->handle($request, function ($req) {
            return response('OK');
        });
    }

    /**
     * RED TEST 5: Slug from different election is blocked
     *
     * SECURITY: Cannot use slug from one election in another election
     */
    public function test_slug_from_different_election_is_blocked()
    {
        $election2 = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Create slug for election1
        $slug = VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->addMinutes(30),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
        ]);

        $request = Request::create('/', 'GET');
        $route = $this->createMock('Illuminate\Routing\Route');
        $route->method('parameter')->with('vslug')->willReturn($slug);
        $request->setRouteResolver(function () use ($route) { return $route; });

        $request->attributes->set('election', $election2);  // Different election
        $this->actingAs($this->user);

        $middleware = new EnsureVoterSlugWindow();

        // BUSINESS ASSERTION: Should throw AccessDeniedHttpException
        $this->expectException(AccessDeniedHttpException::class);
        $middleware->handle($request, function ($req) {
            return response('OK');
        });
    }

    /**
     * RED TEST 6: Non-existent slug is blocked
     *
     * SECURITY: Cannot access with invalid slug string
     */
    public function test_nonexistent_slug_is_blocked()
    {
        $request = Request::create('/', 'GET');
        $route = $this->createMock('Illuminate\Routing\Route');
        $route->method('parameter')->with('vslug')->willReturn('invalid_slug_that_does_not_exist');
        $request->setRouteResolver(function () use ($route) { return $route; });

        $this->actingAs($this->user);
        $request->attributes->set('election', $this->election);

        $middleware = new EnsureVoterSlugWindow();

        // BUSINESS ASSERTION: Should throw 403
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $middleware->handle($request, function ($req) {
            return response('OK');
        });
    }
}
