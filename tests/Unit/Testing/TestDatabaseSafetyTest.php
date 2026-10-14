<?php

declare(strict_types=1);

namespace Tests\Unit\Testing;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\Support\TestDatabaseSafety;

/**
 * A test may reset (migrate:fresh) only a test database (incident 2026-10-13: a test run with
 * `--no-configuration` wiped the development database `publicdigit`). Database-free: the decision is pure.
 */
final class TestDatabaseSafetyTest extends TestCase
{
    public function test_the_test_databases_may_be_reset(): void
    {
        TestDatabaseSafety::assertResettable('pgsql', 'nrna_test');
        TestDatabaseSafety::assertResettable('pgsql', 'pbdigit274_isolated_test');

        $this->addToAssertionCount(2);
    }

    /** @return iterable<string, array{string, ?string}> */
    public static function refused(): iterable
    {
        yield 'the development database' => ['pgsql', 'publicdigit'];
        yield 'another application database' => ['pgsql', 'publicdigit_db'];
        yield 'a name that only starts like a test database' => ['pgsql', 'nrna_test_copy'];
        yield 'the postgres system database' => ['pgsql', 'postgres'];
        yield 'an unknown database' => ['pgsql', null];
        yield 'an empty name' => ['pgsql', ''];
        yield 'another driver' => ['sqlite', 'nrna_test'];
        yield 'a sqlite file' => ['sqlite', '/tmp/app.sqlite'];
        yield 'mysql' => ['mysql', 'nrna_test'];
    }

    /** @dataProvider refused */
    public function test_any_other_database_is_refused_before_it_is_touched(string $driver, ?string $database): void
    {
        try {
            TestDatabaseSafety::assertResettable($driver, $database);
            $this->fail('expected a refusal');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('Refusing to reset', $e->getMessage());
            $this->assertStringContainsString('nrna_test', $e->getMessage(), 'the message names what is allowed');
        }
    }

    public function test_the_refusal_names_the_database_and_the_safe_way_to_run(): void
    {
        try {
            TestDatabaseSafety::assertResettable('pgsql', 'publicdigit');
            $this->fail('expected a refusal');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('"publicdigit"', $e->getMessage());
            $this->assertStringContainsString('phpunit.xml', $e->getMessage());
        }
    }

    // ── PBDIGIT-283 R3: the loaded configuration, checked before any provider ──

    /** @return array<string, array> the connections of a safe configuration */
    private function safeConnections(): array
    {
        return [
            'pgsql'       => ['driver' => 'pgsql', 'url' => '', 'database' => 'nrna_test'],
            'pgsql_audit' => ['driver' => 'pgsql', 'database' => 'nrna_test'],
            'pgsql_geo'   => ['driver' => 'pgsql', 'database' => TestDatabaseSafety::RESETTABLE[1]],
        ];
    }

    private function missingCache(): string
    {
        return sys_get_temp_dir().'/pbd283-no-such-cache-'.bin2hex(random_bytes(4)).'.php';
    }

    public function test_a_safe_loaded_configuration_passes(): void
    {
        TestDatabaseSafety::refuseUnsafeConfiguration('testing', 'pgsql', $this->safeConnections(), $this->missingCache(), []);

        $this->addToAssertionCount(1);
    }

    public function test_an_unsafe_loaded_configuration_is_refused_with_every_problem(): void
    {
        $connections = $this->safeConnections();
        $connections['pgsql_geo']['database'] = 'publicdigit';
        $connections['mysql'] = ['driver' => 'mysql', 'url' => 'mysql://u:s3cr3t@db.example/x'];

        try {
            TestDatabaseSafety::refuseUnsafeConfiguration('local', 'pgsql', $connections, $this->missingCache(), ['APP_CONFIG_CACHE' => '']);
            $this->fail('expected a refusal');
        } catch (RuntimeException $e) {
            foreach (['PBDIGIT-283', "'pgsql_geo'", "'mysql'", 'APP_ENV', 'cached'] as $named) {
                $this->assertStringContainsString($named, $e->getMessage());
            }
            $this->assertStringNotContainsString('s3cr3t', $e->getMessage());
        }
    }
}
