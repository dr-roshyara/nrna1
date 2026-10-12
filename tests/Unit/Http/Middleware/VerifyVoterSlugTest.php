<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Exceptions\Voting\ElectionMismatchException;
use App\Exceptions\Voting\ElectionNotFoundException;
use App\Exceptions\Voting\ExpiredVoterSlugException;
use App\Exceptions\Voting\InvalidVoterSlugException;
use App\Exceptions\Voting\OrganisationMismatchException;
use App\Exceptions\Voting\VoterSlugLifetimeMisconfigured;
use App\Http\Middleware\VerifyVoterSlug;
use App\Models\DemoVoterSlug;
use App\Models\Election;
use App\Models\VoterSlug;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\GenericUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\CreatesApplication;

/**
 * PBDIGIT-273: the VerifyVoterSlug verification contract V-1 … V-6 (plan
 * docs/plans/20261011-1318-verifyvoterslug-hardening-plan.md), exercised on the real middleware class.
 *
 * DATABASE-FREE by design: models are built in memory and never saved, the election relation is set
 * explicitly, and the last test asserts that no database connection was opened. It deliberately does not
 * extend Tests\TestCase, whose setUp writes the platform organisation. Request-path behaviour is covered
 * separately (tests/Feature/Voting/VerifyVoterSlugRequestPathTest.php).
 */
final class VerifyVoterSlugTest extends BaseTestCase
{
    use CreatesApplication;

    private const ORG = '11111111-1111-4111-8111-111111111111';
    private const OTHER_ORG = '22222222-2222-4222-8222-222222222222';
    private const USER = 'aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa';
    private const OTHER_USER = 'bbbbbbbb-bbbb-4bbb-8bbb-bbbbbbbbbbbb';
    private const RAW_SLUG = 'RAWSLUGVALUE0123456789abcdefXYZ';

    private Carbon $now;

    protected function setUp(): void
    {
        parent::setUp();
        $this->now = Carbon::parse('2026-10-11 12:00:00', 'UTC');
        Carbon::setTestNow($this->now);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    // ---------------------------------------------------------------- fixtures (in memory, never saved)

    /** GenericUser, not App\Models\User: instantiating the Eloquent User runs schema-introspection queries. */
    private function user(string $id = self::USER): GenericUser
    {
        return new GenericUser(['id' => $id]);
    }

    private function election(string $type = 'real', ?string $organisationId = self::ORG): Election
    {
        return (new Election())->forceFill(['id' => 'eeeeeeee-eeee-4eee-8eee-eeeeeeeeeeee', 'organisation_id' => $organisationId, 'type' => $type]);
    }

    /** @param array<string, mixed> $overrides */
    private function slug(string $class = VoterSlug::class, array $overrides = [], ?Election $election = null): Model
    {
        /** @var VoterSlug|DemoVoterSlug $slug */
        $slug = new $class();
        $slug->forceFill(array_merge([
            'id' => 'cccccccc-cccc-4ccc-8ccc-cccccccccccc',
            'slug' => self::RAW_SLUG,
            'user_id' => self::USER,
            'organisation_id' => self::ORG,
            'election_id' => 'eeeeeeee-eeee-4eee-8eee-eeeeeeeeeeee',
            'is_active' => true,
            'status' => 'active',
            'created_at' => $this->now->copy(),
            'expires_at' => $this->now->copy()->addMinutes(30),
        ], $overrides));
        $slug->setRelation('election', $election ?? $this->election($class === DemoVoterSlug::class ? 'demo' : 'real'));

        return $slug;
    }

    private function request(mixed $vslug, bool $demoRoute = false): Request
    {
        $request = Request::create($demoRoute ? '/v/x/demo-code/create' : '/v/x/code/create', 'GET');
        $route = new Route('GET', $demoRoute ? 'v/{vslug}/demo-code/create' : 'v/{vslug}/code/create', static fn () => null);
        $route->name($demoRoute ? 'slug.demo-code.create' : 'slug.code.create');
        $route->middleware($demoRoute ? ['voter.slug.verify', 'election.demo'] : ['voter.slug.verify']);
        $route->bind($request);
        $route->setParameter('vslug', $vslug);
        $request->setRouteResolver(static fn () => $route);

        return $request;
    }

    /** @return array{passed: bool, attribute: mixed, exception: ?\Throwable} */
    private function verify(Request $request): array
    {
        $passed = false;
        try {
            (new VerifyVoterSlug())->handle($request, function (Request $r) use (&$passed) {
                $passed = true;

                return response('ok');
            });
            $exception = null;
        } catch (\Throwable $e) {
            $exception = $e;
        }

        return ['passed' => $passed, 'attribute' => $request->attributes->get('voter_slug'), 'exception' => $exception];
    }

    private function assertRejectedWith(string $exceptionClass, array $result): void
    {
        $this->assertInstanceOf($exceptionClass, $result['exception'], 'expected ' . $exceptionClass . ', got ' . ($result['exception'] ? get_class($result['exception']) : 'no exception'));
        $this->assertFalse($result['passed'], 'the next handler must not run after a failed check');
        $this->assertNull($result['attribute'], 'voter_slug must not be set after a failed check');
    }

    // ---------------------------------------------------------------- acceptance (characterisation)

    public function test_a_valid_real_slug_passes_and_is_stored_on_the_request(): void
    {
        $this->actingAs($this->user());
        $slug = $this->slug();

        $result = $this->verify($this->request($slug));

        $this->assertNull($result['exception']);
        $this->assertTrue($result['passed']);
        $this->assertSame($slug, $result['attribute']);
    }

    public function test_a_valid_demo_slug_passes_on_a_demo_route(): void
    {
        $this->actingAs($this->user());
        $slug = $this->slug(DemoVoterSlug::class);

        $result = $this->verify($this->request($slug, demoRoute: true));

        $this->assertNull($result['exception']);
        $this->assertTrue($result['passed']);
    }

    public function test_a_voted_slug_still_passes_so_post_vote_pages_keep_working(): void
    {
        // Step order and post-vote access belong to EnsureVoterStepOrder / VerifyVoterSlugConsistency (V-7).
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, ['status' => 'voted', 'has_voted' => true])));

        $this->assertTrue($result['passed']);
    }

    // ---------------------------------------------------------------- V-1 authentication

    public function test_a_guest_is_rejected_before_any_slug_lookup(): void
    {
        // A string parameter would require a lookup; the guest must be rejected first (no DB access here).
        $result = $this->verify($this->request(self::RAW_SLUG));

        $this->assertRejectedWith(AuthenticationException::class, $result);
    }

    public function test_a_guest_is_rejected_even_for_a_bound_slug(): void
    {
        $result = $this->verify($this->request($this->slug()));

        $this->assertRejectedWith(AuthenticationException::class, $result);
    }

    // ---------------------------------------------------------------- V-2 slug type must match the route

    public function test_a_demo_slug_is_rejected_on_a_real_route(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(DemoVoterSlug::class)));

        $this->assertRejectedWith(InvalidVoterSlugException::class, $result);
    }

    public function test_a_real_slug_is_rejected_on_a_demo_route(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, [], $this->election('demo')), demoRoute: true));

        $this->assertRejectedWith(InvalidVoterSlugException::class, $result);
    }

    // ---------------------------------------------------------------- V-3 ownership

    public function test_another_users_slug_is_rejected(): void
    {
        $this->actingAs($this->user(self::OTHER_USER));

        $result = $this->verify($this->request($this->slug()));

        // Decision-driven change (PBDIGIT-274 S7, PO O-5 = "plain not found", 2026-10-13): the same 404 as an
        // unknown link (RouteServiceProvider binding), instead of SlugOwnershipException.
        $this->assertRejectedWith(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class, $result);
        $this->assertSame('Voting link not found.', $result['exception']->getMessage());
    }

    // ---------------------------------------------------------------- V-5 revoked / inactive

    public function test_an_inactive_slug_is_rejected(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, ['is_active' => false])));

        $this->assertRejectedWith(InvalidVoterSlugException::class, $result);
    }

    public function test_a_soft_deleted_slug_is_rejected_even_when_still_marked_active(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, ['deleted_at' => $this->now->copy()->subMinute()])));

        $this->assertRejectedWith(InvalidVoterSlugException::class, $result);
    }

    public function test_a_slug_with_expired_status_is_rejected_even_when_still_marked_active(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, ['status' => 'expired'])));

        // PBDIGIT-274 (PO: an expired session is restartable for its owner): status 'expired' is session expiry,
        // reported as Expired (was Invalid under PBDIGIT-273).
        $this->assertRejectedWith(ExpiredVoterSlugException::class, $result);
    }

    public function test_a_slug_marked_expired_and_inactive_by_the_retrieved_hook_is_reported_expired_not_invalid(): void
    {
        // PBDIGIT-274 guard order V-4 before V-5b: the model's retrieved hook sets is_active=false and
        // status='expired' together; the owner must get the expiry outcome, not "no longer active".
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, ['status' => 'expired', 'is_active' => false])));

        $this->assertRejectedWith(ExpiredVoterSlugException::class, $result);
    }

    public function test_an_inactive_slug_that_is_not_expired_stays_invalid(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, ['is_active' => false, 'status' => 'active'])));

        $this->assertRejectedWith(InvalidVoterSlugException::class, $result);
    }

    public function test_a_misconfigured_lifetime_fails_closed_before_any_slug_check_with_a_safe_critical_log(): void
    {
        // PBDIGIT-274 O-4 / O-10 = B: strict, no fallback; logged with a reason code and type only.
        Log::spy();
        config(['voting.time_in_minutes' => '20min']);
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug()));

        $this->assertRejectedWith(VoterSlugLifetimeMisconfigured::class, $result);
        $this->assertSame(503, $result['exception']->getHttpCode());
        Log::shouldHaveReceived('critical', function (string $message, array $context = []) {
            return $message === 'voter_slug_lifetime_misconfigured'
                && ($context['reason'] ?? null) === 'not_integer'
                && ($context['configured_type'] ?? null) === 'string'
                && !str_contains((string) json_encode($context), '20min')
                && !str_contains((string) json_encode($context), self::RAW_SLUG);
        });
    }

    // ---------------------------------------------------------------- V-4 expiry (now >= expires_at is expired)

    public function test_a_slug_one_second_before_its_deadline_passes(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, ['expires_at' => $this->now->copy()->addSecond()])));

        $this->assertTrue($result['passed']);
    }

    public function test_a_slug_exactly_at_its_deadline_is_rejected(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, ['expires_at' => $this->now->copy()])));

        $this->assertRejectedWith(ExpiredVoterSlugException::class, $result);
    }

    public function test_a_slug_after_its_deadline_is_rejected_even_when_still_marked_active(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, ['expires_at' => $this->now->copy()->subSecond()])));

        $this->assertRejectedWith(ExpiredVoterSlugException::class, $result);
    }

    public function test_a_slug_without_an_expiry_is_rejected(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, ['expires_at' => null])));

        $this->assertRejectedWith(ExpiredVoterSlugException::class, $result);
    }

    // Fixed lifetime from creation (Human PO 2026-10-11: cap in the guard now): valid only while
    // now < min(expires_at, created_at + voting.time_in_minutes), so no in-place extension can lengthen a slug.

    public function test_an_extended_expiry_does_not_outlive_thirty_minutes_from_creation(): void
    {
        $this->actingAs($this->user());
        $extended = ['created_at' => $this->now->copy()->subMinutes(30), 'expires_at' => $this->now->copy()->addMinutes(25)];

        $result = $this->verify($this->request($this->slug(VoterSlug::class, $extended)));

        $this->assertRejectedWith(ExpiredVoterSlugException::class, $result);
    }

    public function test_one_second_before_thirty_minutes_from_creation_passes(): void
    {
        $this->actingAs($this->user());
        $fields = ['created_at' => $this->now->copy()->subMinutes(30)->addSecond(), 'expires_at' => $this->now->copy()->addMinutes(25)];

        $this->assertTrue($this->verify($this->request($this->slug(VoterSlug::class, $fields)))['passed']);
    }

    public function test_a_slug_without_a_creation_time_is_rejected(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, ['created_at' => null])));

        $this->assertRejectedWith(ExpiredVoterSlugException::class, $result);
    }

    public function test_the_lifetime_comes_from_the_single_configured_duration(): void
    {
        config(['voting.time_in_minutes' => 20]);
        $this->actingAs($this->user());
        $fields = ['created_at' => $this->now->copy()->subMinutes(20), 'expires_at' => $this->now->copy()->addMinutes(10)];

        $result = $this->verify($this->request($this->slug(VoterSlug::class, $fields)));

        $this->assertRejectedWith(ExpiredVoterSlugException::class, $result);
    }

    public function test_an_extended_demo_slug_is_capped_too(): void
    {
        $this->actingAs($this->user());
        $extended = ['created_at' => $this->now->copy()->subMinutes(31), 'expires_at' => $this->now->copy()->addMinutes(5)];

        $result = $this->verify($this->request($this->slug(DemoVoterSlug::class, $extended), demoRoute: true));

        $this->assertRejectedWith(ExpiredVoterSlugException::class, $result);
    }

    public function test_an_expired_demo_slug_is_rejected(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(DemoVoterSlug::class, ['expires_at' => $this->now->copy()]), demoRoute: true));

        $this->assertRejectedWith(ExpiredVoterSlugException::class, $result);
    }

    // ---------------------------------------------------------------- V-6 election and organisation consistency

    public function test_a_slug_whose_election_is_missing_is_rejected(): void
    {
        $this->actingAs($this->user());
        $slug = $this->slug();
        $slug->setRelation('election', null);

        $result = $this->verify($this->request($slug));

        $this->assertRejectedWith(ElectionNotFoundException::class, $result);
    }

    public function test_a_slug_whose_election_belongs_to_another_organisation_is_rejected(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, [], $this->election('real', self::OTHER_ORG))));

        $this->assertRejectedWith(OrganisationMismatchException::class, $result);
    }

    public function test_missing_organisation_ids_are_rejected_not_treated_as_equal(): void
    {
        // Review 2026-10-11 (point 4): null === null must not pass the organisation check.
        $this->actingAs($this->user());

        $bothMissing = $this->verify($this->request($this->slug(VoterSlug::class, ['organisation_id' => null], $this->election('real', null))));
        $slugMissing = $this->verify($this->request($this->slug(VoterSlug::class, ['organisation_id' => null])));

        $this->assertRejectedWith(OrganisationMismatchException::class, $bothMissing);
        $this->assertRejectedWith(OrganisationMismatchException::class, $slugMissing);
    }

    public function test_a_real_route_with_a_demo_election_is_rejected(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(VoterSlug::class, [], $this->election('demo'))));

        $this->assertRejectedWith(ElectionMismatchException::class, $result);
    }

    public function test_a_demo_route_with_a_real_election_is_rejected(): void
    {
        $this->actingAs($this->user());

        $result = $this->verify($this->request($this->slug(DemoVoterSlug::class, [], $this->election('real')), demoRoute: true));

        $this->assertRejectedWith(ElectionMismatchException::class, $result);
    }

    // ---------------------------------------------------------------- safe logging

    public function test_no_raw_slug_reaches_logs_or_exception_context_and_nothing_is_logged_as_emergency(): void
    {
        Log::spy();
        $this->actingAs($this->user(self::OTHER_USER));

        $result = $this->verify($this->request($this->slug()));

        // Since S7 (O-5) a foreign link ends in the plain 404; its message must not carry the raw link either.
        $this->assertInstanceOf(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class, $result['exception']);
        $this->assertStringNotContainsString(self::RAW_SLUG, $result['exception']->getMessage());
        Log::shouldNotHaveReceived('emergency');
        foreach (['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert'] as $level) {
            Log::shouldNotHaveReceived($level, [\Mockery::on(fn () => true), \Mockery::on(fn ($context) => str_contains((string) json_encode($context), self::RAW_SLUG))]);
        }
    }

    // ---------------------------------------------------------------- route-type marker (review condition 2)

    public function test_every_slug_route_agrees_on_demo_type_between_the_binding_and_the_middleware(): void
    {
        // The {vslug} binding (RouteServiceProvider) picks the model by "demo" in the route name; this middleware
        // uses the explicit `election.demo` route middleware. Both must classify every verified slug route alike.
        $checked = 0;
        foreach (app('router')->getRoutes() as $route) {
            $middleware = $route->gatherMiddleware();
            if (!str_contains($route->uri(), '{vslug}') || !in_array('voter.slug.verify', $middleware, true)) {
                continue;
            }
            $checked++;
            $this->assertSame(
                str_contains((string) $route->getName(), 'demo'),
                in_array('election.demo', $middleware, true),
                "route {$route->getName()} ({$route->uri()}) is classified differently by the binding and the middleware",
            );
        }
        $this->assertSame(19, $checked, 'expected 9 real and 10 demo verified slug routes (routes/election/electionRoutes.php)');
    }

    /** @return list<Route> every registered route with a {vslug} parameter */
    private function slugRoutes(): array
    {
        return array_values(array_filter(
            iterator_to_array(app('router')->getRoutes()),
            static fn (Route $route) => str_contains($route->uri(), '{vslug}'),
        ));
    }

    public function test_the_registered_routes_classify_demo_and_real_correctly_through_the_middleware(): void
    {
        // Review 2026-10-11 (point 1): run the middleware on the ACTUAL registered route objects, so the runtime
        // result of gatherMiddleware() (alias kept or resolved?) decides, not a hand-built route.
        $this->actingAs($this->user());
        $demo = 0;
        $real = 0;
        foreach ($this->slugRoutes() as $registered) {
            if (!in_array('voter.slug.verify', $registered->gatherMiddleware(), true)) {
                continue;
            }
            $isDemo = str_contains((string) $registered->getName(), 'demo');
            $isDemo ? $demo++ : $real++;
            foreach ([true, false] as $matchingType) {
                $slugIsDemo = $matchingType ? $isDemo : !$isDemo;
                $slug = $this->slug($slugIsDemo ? DemoVoterSlug::class : VoterSlug::class);
                $route = clone $registered;
                $request = Request::create('/' . str_replace('{vslug}', 'x', $route->uri()), $route->methods()[0]);
                $route->bind($request);
                $route->setParameter('vslug', $slug);
                $request->setRouteResolver(static fn () => $route);

                $result = $this->verify($request);

                $matchingType
                    ? $this->assertTrue($result['passed'], "{$route->getName()}: a matching slug must pass")
                    : $this->assertRejectedWith(InvalidVoterSlugException::class, $result);
            }
        }
        $this->assertSame([9, 10], [$real, $demo], 'real and demo verified slug routes');
    }

    public function test_every_slug_route_runs_the_guard_before_step_order_and_entitlement(): void
    {
        // Review 2026-10-11 (point 5). Known exception: the broken test route v/{vslug}/test (backlog).
        $later = ['voter.slug.window', 'voter.slug.consistency', 'ensure.election.voter', 'voter.step.order', 'vote.eligibility', 'vote.organisation', 'voting.code.window', 'election.demo', 'voting.active'];
        foreach ($this->slugRoutes() as $route) {
            $middleware = $route->gatherMiddleware();
            if ($route->getName() === 'test.voter.page') {
                $this->assertNotContains('voter.slug.verify', $middleware, 'the known test route changed: revisit the exclusion');
                continue;
            }
            $guard = array_search('voter.slug.verify', $middleware, true);
            $this->assertNotFalse($guard, "{$route->getName()} has no voter.slug.verify");
            foreach ($later as $name) {
                $position = array_search($name, $middleware, true);
                if ($position !== false) {
                    $this->assertGreaterThan($guard, $position, "{$route->getName()}: $name runs before voter.slug.verify");
                }
            }
        }
    }

    // ---------------------------------------------------------------- evidence that this file is database-free

    public function test_a_bound_slug_is_verified_without_any_database_query(): void
    {
        // Evidence for THIS test's calls (each test gets a fresh application): no query ran. The other cases avoid
        // the database by construction (bound models, preloaded election, guests rejected first). Real requests
        // take the Election::withoutGlobalScopes()->find() branch, covered only by the request-path test.
        $queries = 0;
        DB::listen(function () use (&$queries) {
            $queries++;
        });
        $this->actingAs($this->user());
        $this->verify($this->request($this->slug()));
        $this->verify($this->request($this->slug(VoterSlug::class, ['expires_at' => $this->now->copy()])));
        $this->verify($this->request($this->slug(DemoVoterSlug::class), demoRoute: true));

        $this->assertSame(0, $queries, 'the middleware must not query the database for a bound slug with a loaded election');
        foreach (DB::getConnections() as $name => $connection) {
            $this->assertInstanceOf(\Closure::class, $connection->getRawPdo(), "connection '$name' was opened");
        }
    }
}
