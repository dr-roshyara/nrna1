<?php

namespace Tests\Feature\Routes;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Route;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeTest;

/**
 * S4 regression guard — vote.eligibility middleware on Step 1 routes.
 *
 * Root cause confirmed: accidental omission during route-group refactoring.
 * The branch split the unified voter-slug group into:
 *   - Step 1 group (no slug.window, to allow expired-slug code regeneration)
 *   - Steps 2-5 group (full middleware chain)
 *
 * vote.eligibility was accidentally dropped from the Step 1 group.
 * An ineligible voter could then obtain a ballot code before being blocked.
 *
 * This test asserts the contract at the route-definition level (no DB needed).
 * If vote.eligibility is accidentally removed again the test fails immediately.
 */
class VotingEligibilityMiddlewareRegressionTest extends TestCase
{
    private static Application $app;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // PBDIGIT-283 R4: boot through Tests\CreatesApplication as a database-free test (marker + pre-provider check).
        $app = (new class implements DatabaseFreeTest {
            use CreatesApplication;
        })->createApplication();

        static::$app = $app;
    }

    private function route(string $name): ?Route
    {
        return static::$app['router']->getRoutes()->getByName($name);
    }

    // -----------------------------------------------------------------------
    // S4 — Step 1 must carry vote.eligibility (the regression)
    // -----------------------------------------------------------------------

    public function test_step1_code_create_route_requires_vote_eligibility(): void
    {
        $route = $this->route('slug.code.create');

        $this->assertNotNull($route, 'Route slug.code.create must be registered');
        $this->assertContains(
            'vote.eligibility',
            $route->gatherMiddleware(),
            'Step 1 GET code/create must be protected by vote.eligibility — ineligible voter must be blocked before obtaining a ballot code'
        );
    }

    public function test_step1_code_store_route_requires_vote_eligibility(): void
    {
        $route = $this->route('slug.code.store');

        $this->assertNotNull($route, 'Route slug.code.store must be registered');
        $this->assertContains(
            'vote.eligibility',
            $route->gatherMiddleware(),
            'Step 1 POST code must be protected by vote.eligibility — a voter whose eligibility was revoked must be blocked at code submission'
        );
    }

    // -----------------------------------------------------------------------
    // Completeness — Steps 2-5 still carry vote.eligibility
    // -----------------------------------------------------------------------

    /**
     * @dataProvider step2to5Routes
     */
    public function test_steps_2_to_5_still_carry_vote_eligibility(string $routeName): void
    {
        $route = $this->route($routeName);

        if ($route === null) {
            $this->markTestSkipped("Route {$routeName} not registered");
        }

        $this->assertContains(
            'vote.eligibility',
            $route->gatherMiddleware(),
            "Route {$routeName} must remain protected by vote.eligibility"
        );
    }

    public static function step2to5Routes(): array
    {
        return [
            'agreement GET'  => ['slug.code.agreement'],
            'agreement POST' => ['slug.code.agreement.submit'],
            'vote GET'       => ['slug.vote.create'],
            'vote POST'      => ['slug.vote.store'],
        ];
    }

    // -----------------------------------------------------------------------
    // Full shared middleware on Step 1
    // -----------------------------------------------------------------------

    /**
     * @dataProvider sharedMiddlewareOnStep1
     */
    public function test_step1_code_create_carries_all_shared_middleware(string $middleware): void
    {
        $route = $this->route('slug.code.create');
        $this->assertNotNull($route);
        $this->assertContains(
            $middleware,
            $route->gatherMiddleware(),
            "slug.code.create is missing shared middleware: {$middleware}"
        );
    }

    /**
     * @dataProvider sharedMiddlewareOnStep1
     */
    public function test_step1_code_store_carries_all_shared_middleware(string $middleware): void
    {
        $route = $this->route('slug.code.store');
        $this->assertNotNull($route);
        $this->assertContains(
            $middleware,
            $route->gatherMiddleware(),
            "slug.code.store is missing shared middleware: {$middleware}"
        );
    }

    public static function sharedMiddlewareOnStep1(): array
    {
        $required = [
            'vote.eligibility',
            'voter.slug.verify',
            'voter.slug.consistency',
            'ensure.election.voter',
            'vote.organisation',
        ];

        return array_combine($required, array_map(fn($m) => [$m], $required));
    }
}
