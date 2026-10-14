<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Tests\Support\DatabaseFreeEnvironment;
use Tests\Support\DatabaseFreeTest;

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

            $app->make(Kernel::class)->bootstrap();
        } finally {
            if ($snapshot !== null) {
                DatabaseFreeEnvironment::restore($snapshot);
            }
        }

        return $app;
    }
}
