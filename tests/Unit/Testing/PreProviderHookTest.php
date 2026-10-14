<?php

declare(strict_types=1);

namespace Tests\Unit\Testing;

use App\Providers\AppServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeEnvironment;

/**
 * PBDIGIT-283 R3, decision D-283-2: every Laravel-booting test refuses an unsafe database configuration AFTER the
 * configuration is loaded and BEFORE any service provider is registered, whatever PHPUnit configuration (or none)
 * started the run.
 *
 * Fail-safe: each case starts from the outer net (every target at a non-existent socket) and changes ONE aspect,
 * which is itself unreachable (a socket, a `.invalid` host). The environment is verified before boot; the fixture
 * is NOT marked, so the marker cannot mask the case.
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Testing/PreProviderHookTest.php
 */
final class PreProviderHookTest extends TestCase
{
    private const URL = 'pgsql://nobody@nonexistent-283.invalid:1/publicdigit?host=%2Fnonexistent-283-r3-socket-dir';

    private ?Container $previous = null;

    private array $snapshot = [];

    public static function unsafeConfigurations(): array
    {
        $missingCache = sys_get_temp_dir().'/pbd283-no-such-cache-'.bin2hex(random_bytes(4)).'.php';

        return [
            'default database not approved' => [['DB_DATABASE' => 'publicdigit'], "'pgsql'"],
            'audit database not approved'   => [['DB_AUDIT_DATABASE' => 'publicdigit'], "'pgsql_audit'"],
            'geo database not approved'     => [['DB_PGSQL_GEO_DATABASE' => 'publicdigit'], "'pgsql_geo'"],
            'DATABASE_URL set'              => [['DATABASE_URL' => self::URL], 'URL override'],
            'default connection not pgsql'  => [['DB_CONNECTION' => 'pgsql_audit'], 'default connection'],
            'APP_ENV not testing'           => [['APP_ENV' => 'local'], 'APP_ENV'],
            'APP_CONFIG_CACHE set'          => [['APP_CONFIG_CACHE' => $missingCache], 'cached'],
        ];
    }

    /** Applies the outer net plus $override and verifies it, or fails without booting. */
    private function environment(array $override): void
    {
        $wanted = $override + DatabaseFreeBootTest::outerNetVariables();
        $this->snapshot = DatabaseFreeEnvironment::apply($wanted);
        foreach ($wanted as $key => $value) {
            if (getenv($key) !== $value || ($_SERVER[$key] ?? null) !== $value || ($_ENV[$key] ?? null) !== $value) {
                $this->fail("PBDIGIT-283: {$key} is not as intended; aborting before Laravel boots.");
            }
        }
        $this->previous = Container::getInstance();
        Container::setInstance(null);
    }

    protected function tearDown(): void
    {
        $app = Container::getInstance();
        if ($app instanceof Application && $app->isBooted()) {
            HandleExceptions::flushState();
            Facade::clearResolvedInstances();
            Facade::setFacadeApplication(null);
            $app->flush();
        }
        Container::setInstance($this->previous);
        DatabaseFreeEnvironment::restore($this->snapshot);
        parent::tearDown();
    }

    private function createApplication(): Application
    {
        $unmarked = new class {
            use CreatesApplication;
        };

        return $unmarked->createApplication();
    }

    #[DataProvider('unsafeConfigurations')]
    public function test_an_unsafe_configuration_is_refused_before_any_provider_is_registered(array $override, string $named): void
    {
        $this->environment($override);

        // PHPUnit's own failures extend RuntimeException, so the refusal is captured, not asserted inside the try.
        $refusal = null;
        try {
            $this->createApplication();
        } catch (RuntimeException $e) {
            $refusal = $e;
        }

        $this->assertNotNull($refusal, 'expected a refusal before any provider is registered');
        $this->assertStringContainsString('PBDIGIT-283', $refusal->getMessage());
        $this->assertStringContainsString($named, $refusal->getMessage());
        $this->assertStringNotContainsString('nobody@', $refusal->getMessage(), 'a URL may carry credentials; never echo it');

        $app = Container::getInstance();
        $this->assertInstanceOf(Application::class, $app, 'the application existed when the configuration was checked');
        $this->assertFalse($app->bound('db'), 'the database manager must not be registered');
        $this->assertArrayNotHasKey(AppServiceProvider::class, $app->getLoadedProviders(), 'no application provider may be registered');
    }

    public function test_a_safe_configuration_boots(): void
    {
        $this->environment([]);

        $app = $this->createApplication();

        $this->assertTrue($app->isBooted());
        $this->assertSame(DatabaseFreeBootTest::outerNetVariables()['DB_HOST'], $app['config']->get('database.connections.pgsql.host'));
    }
}
