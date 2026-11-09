<?php

namespace Tests\Feature\Routes;

use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeTest;

/**
 * PBDIGIT-340: social sign-in (Google, Facebook) is removed (Principal decision P-I5, 2026-11-09).
 *
 * Both paths created accounts with a plain-text constant password, printed raw database errors to
 * visitors, and queried columns the users table no longer has. D-75: accounts are never created or
 * linked from an outside identity's e-mail. The Google Calendar integration is a different feature
 * and must stay.
 */
class SocialSignInRemovedTest extends TestCase
{
    private static Application $app;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$app = (new class implements DatabaseFreeTest {
            use CreatesApplication;
        })->createApplication();
    }

    /** @return list<string> */
    private function registeredUris(): array
    {
        return array_map(
            fn ($route) => $route->uri(),
            static::$app['router']->getRoutes()->getRoutes()
        );
    }

    public function test_no_social_sign_in_route_is_registered(): void
    {
        $uris = $this->registeredUris();

        foreach (['login/google', 'login/google/callback', 'auth/google', 'auth/google/callback',
                  'login/facebook', 'login/facebook/callback'] as $uri) {
            $this->assertNotContains($uri, $uris, "social sign-in route still registered: {$uri}");
        }

        foreach (['login.google-redirect', 'login.google-callback', 'login.facebook-redirect', 'auth.google'] as $name) {
            $this->assertNull(static::$app['router']->getRoutes()->getByName($name), "named route still registered: {$name}");
        }
    }

    public function test_no_social_sign_in_controller_remains(): void
    {
        // Checked by file, not class_exists(): a stale Composer class map would otherwise try to load them.
        foreach (['GoogleLoginController', 'GoogleController', 'SocialController'] as $class) {
            $this->assertFileDoesNotExist(
                static::$app->path("Http/Controllers/{$class}.php"),
                "social sign-in controller still present: {$class}"
            );
        }
    }

    public function test_google_calendar_integration_is_kept(): void
    {
        $uris = $this->registeredUris();

        foreach (['google', 'google/oauth', 'google/webhook'] as $uri) {
            $this->assertContains($uri, $uris, "calendar route removed by mistake: {$uri}");
        }
    }
}
