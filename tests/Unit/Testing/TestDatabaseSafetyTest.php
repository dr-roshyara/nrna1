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
}
