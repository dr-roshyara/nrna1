<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Tests\Support\DatabaseFreeEnvironment;
use Tests\Support\DatabaseFreeTest;
use Tests\Support\TestDatabaseSafety;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * A DatabaseFreeTest boots with every database target at a non-existent socket (PBDIGIT-283 R1, D-283-3):
     * neutralised and checked BEFORE bootstrap/app.php is required (refused, with nothing booted, if that fails),
     * and restored after bootstrap so nothing leaks into later tests.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $snapshot = $this instanceof DatabaseFreeTest
            ? DatabaseFreeEnvironment::enter(__DIR__.'/../bootstrap/cache/config.php')
            : null;

        try {
            $app = require __DIR__.'/../bootstrap/app.php';

            // PBDIGIT-283 R3 (D-283-2): check the LOADED configuration before any service provider is registered
            // (LoadConfiguration runs before RegisterProviders), for every test, whatever started the run.
            $app->afterBootstrapping(LoadConfiguration::class, static function ($app): void {
                TestDatabaseSafety::refuseUnsafeConfiguration(
                    $app->environment(),
                    (string) $app['config']->get('database.default'),
                    (array) $app['config']->get('database.connections', []),
                    $app->getCachedConfigPath(),
                    getenv(),
                );
            });

            $app->make(Kernel::class)->bootstrap();
        } finally {
            if ($snapshot !== null) {
                DatabaseFreeEnvironment::restore($snapshot);
            }
        }

        return $app;
    }
}
