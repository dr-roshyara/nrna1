<?php

declare(strict_types=1);

namespace Tests\Unit\Testing;

use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeEnvironment;
use Tests\Support\DatabaseFreeTest;

/**
 * PBDIGIT-283 R1(b2), correction C-3: createApplication() refuses a marked test BEFORE Laravel boots when its
 * environment cannot be neutralised (here: APP_CONFIG_CACHE is set, so a cached configuration would ignore every
 * variable). Proven at boot level: no Application exists after the refusal.
 *
 * Fail-safe: the outer net (DatabaseFreeBootTest::outerNetVariables()) is applied and verified first, so even a
 * broken refusal could only boot against non-existent sockets.
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Testing/DatabaseFreeBootRefusalTest.php
 */
final class DatabaseFreeBootRefusalTest extends TestCase
{
    public function test_a_marked_test_is_refused_before_boot(): void
    {
        $outerNet = DatabaseFreeEnvironment::apply(DatabaseFreeBootTest::outerNetVariables()
            + ['APP_CONFIG_CACHE' => sys_get_temp_dir().'/pbd283-no-such-cache-'.bin2hex(random_bytes(4)).'.php']);
        foreach (DatabaseFreeBootTest::outerNetVariables() as $key => $value) {
            if (getenv($key) !== $value || ($_SERVER[$key] ?? null) !== $value || ($_ENV[$key] ?? null) !== $value) {
                DatabaseFreeEnvironment::restore($outerNet);
                $this->fail("PBDIGIT-283 C-3: {$key} is not neutralised; aborting before Laravel boots.");
            }
        }
        $previous = Container::getInstance();
        Container::setInstance(null);

        try {
            $marked = new class implements DatabaseFreeTest {
                use CreatesApplication;
            };
            $marked->createApplication();
            $this->fail('expected a refusal before boot');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('nothing was booted', $e->getMessage());
            $this->assertNotInstanceOf(Application::class, Container::getInstance(), 'no Application was created');
            $this->assertSame(DatabaseFreeBootTest::outerNetVariables()['DB_HOST'], getenv('DB_HOST'), 'restored before the refusal');
        } finally {
            if (Container::getInstance() instanceof Application) {   // only if the refusal failed
                HandleExceptions::flushState();
                Facade::clearResolvedInstances();
                Facade::setFacadeApplication(null);
            }
            Container::setInstance($previous);
            DatabaseFreeEnvironment::restore($outerNet);
        }
    }
}
