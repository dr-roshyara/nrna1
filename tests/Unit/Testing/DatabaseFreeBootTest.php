<?php

declare(strict_types=1);

namespace Tests\Unit\Testing;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeEnvironment;
use Tests\Support\DatabaseFreeTest;
use Tests\Support\DatabaseTargetRules;
use Tests\Support\TestDatabaseSafety;

/**
 * PBDIGIT-283 R1(b2), decision D-283-3, correction C-3: a marked test boots Laravel with every database
 * target neutralised, and the environment is restored afterwards.
 *
 * Fail-safe (C-3): before createApplication() runs, setUp() points every target at a DIFFERENT non-existent
 * socket (the "outer net") and verifies it, aborting without booting if any check fails. So this test never
 * boots with an unneutralised environment, even if the marker is broken; and finding the marker's own socket
 * in the booted configuration proves it was the marker that neutralised.
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Testing/DatabaseFreeBootTest.php
 */
final class DatabaseFreeBootTest extends BaseTestCase implements DatabaseFreeTest
{
    use CreatesApplication;

    private const OUTER_NET = '/nonexistent-283-outer-net-socket-dir';

    private array $outerNet = [];

    /** @return array<string, string> the marker's targets, each at the outer-net socket instead */
    public static function outerNetVariables(): array
    {
        return array_map(
            fn (string $value) => $value === DatabaseFreeEnvironment::SOCKET ? self::OUTER_NET : $value,
            DatabaseFreeEnvironment::variables(),
        );
    }

    protected function setUp(): void
    {
        $this->outerNet = DatabaseFreeEnvironment::apply(self::outerNetVariables());
        foreach (self::outerNetVariables() as $key => $value) {
            if (($_SERVER[$key] ?? null) !== $value || ($_ENV[$key] ?? null) !== $value || getenv($key) !== $value) {
                DatabaseFreeEnvironment::restore($this->outerNet);
                $this->fail("PBDIGIT-283 C-3: {$key} is not neutralised; aborting before Laravel boots.");
            }
        }

        parent::setUp();   // createApplication() runs here
    }

    protected function tearDown(): void
    {
        try {
            parent::tearDown();
        } finally {
            DatabaseFreeEnvironment::restore($this->outerNet);
        }
    }

    public function test_every_connection_is_at_the_markers_socket(): void
    {
        $config = $this->app['config'];
        foreach (DatabaseTargetRules::CONNECTIONS as $name) {
            $this->assertSame(DatabaseFreeEnvironment::SOCKET, $config->get("database.connections.{$name}.host"), $name);
            $this->assertSame(DatabaseFreeEnvironment::DATABASE, $config->get("database.connections.{$name}.database"), $name);
        }
        $this->assertSame('pgsql', $config->get('database.default'));
        $this->assertEmpty($config->get('database.connections.pgsql.url'));
    }

    public function test_the_booted_configuration_satisfies_the_shared_rules(): void
    {
        $this->assertSame([], DatabaseTargetRules::targetProblems(
            $this->app->environment(),
            $this->app['config']->get('database.default'),
            $this->app['config']->get('database.connections'),
            TestDatabaseSafety::RESETTABLE,
        ));
    }

    public function test_the_environment_is_restored_after_boot(): void
    {
        foreach (self::outerNetVariables() as $key => $value) {
            $this->assertSame($value, getenv($key), $key);
            $this->assertSame($value, $_SERVER[$key] ?? null, $key);
            $this->assertSame($value, $_ENV[$key] ?? null, $key);
        }
    }
}
