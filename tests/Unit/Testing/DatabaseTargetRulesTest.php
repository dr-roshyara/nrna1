<?php

declare(strict_types=1);

namespace Tests\Unit\Testing;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Support\DatabaseTargetRules;

/**
 * PBDIGIT-283 R1(a): the shared, pure rules for which databases a test may target (plan
 * docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md, F3, F7).
 *
 * Parameterised by the caller's approved set: the default suite passes TestDatabaseSafety::RESETTABLE, the
 * isolated guard keeps its own. Database-free: plain PHPUnit, no Laravel, no connection.
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Testing/DatabaseTargetRulesTest.php
 */
final class DatabaseTargetRulesTest extends TestCase
{
    private const APPROVED = ['nrna_test', 'pbd_isolated'];

    /** A safe configuration: every confined connection on an approved database, no URL anywhere. */
    private function connections(array $override = []): array
    {
        $connections = [
            'sqlite'      => ['driver' => 'sqlite', 'url' => null, 'database' => 'x.sqlite'],
            'mysql'       => ['driver' => 'mysql', 'url' => null, 'database' => 'forge'],
            'pgsql'       => ['driver' => 'pgsql', 'url' => null, 'database' => 'nrna_test'],
            'pgsql_audit' => ['driver' => 'pgsql', 'database' => 'nrna_test'],
            'pgsql_geo'   => ['driver' => 'pgsql', 'database' => 'pbd_isolated'],
        ];
        foreach ($override as $name => $values) {
            $connections[$name] = $values === null ? null : array_merge($connections[$name] ?? [], $values);
        }

        return array_filter($connections, fn ($c) => $c !== null);
    }

    // ── identifier syntax and reserved words (moved out of DatabaseIdentityGuard) ──

    public static function identifierProblems(): array
    {
        return [
            'getenv false'       => [false, 'missing'],
            'null'               => [null, 'missing'],
            'empty'              => ['', 'empty'],
            'whitespace'         => ['  ', 'empty'],
            'hyphen'             => ['nrna-test', 'malformed'],
            'leading digit'      => ['1db', 'malformed'],
            'too long'           => [str_repeat('a', 64), 'malformed'],
            'not a string'       => [42, 'malformed'],
            'reserved, any case' => ['NULL', 'reserved'],
            'reserved empty'     => ['empty', 'reserved'],
        ];
    }

    #[DataProvider('identifierProblems')]
    public function test_an_identifier_problem_is_named(mixed $raw, string $problem): void
    {
        $this->assertSame($problem, DatabaseTargetRules::identifierProblem($raw));
    }

    public function test_a_plain_identifier_has_no_problem(): void
    {
        $this->assertNull(DatabaseTargetRules::identifierProblem('nrna_test'));
        $this->assertNull(DatabaseTargetRules::identifierProblem('_a'.str_repeat('b', 61)));
    }

    // ── a cached configuration ignores every environment variable ──────────

    public function test_a_cached_configuration_is_a_problem(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'pbd283cfg');
        try {
            $this->assertSame('config_cached', DatabaseTargetRules::configCacheProblem($file, []));
        } finally {
            unlink($file);
        }
        $this->assertNull(DatabaseTargetRules::configCacheProblem($file, []));
    }

    public function test_an_app_config_cache_override_is_a_problem_even_when_empty(): void
    {
        $missing = sys_get_temp_dir().'/pbd283-no-such-cache-'.bin2hex(random_bytes(4)).'.php';
        $this->assertSame('config_cached', DatabaseTargetRules::configCacheProblem($missing, ['APP_CONFIG_CACHE' => '']));
    }

    // ── the targets ─────────────────────────────────────────────────────────

    public function test_a_safe_configuration_has_no_problems(): void
    {
        $this->assertSame([], DatabaseTargetRules::targetProblems('testing', 'pgsql', $this->connections(), self::APPROVED));
    }

    public function test_app_env_other_than_testing_is_a_problem(): void
    {
        foreach (['local', 'production', ''] as $env) {
            $this->assertNotSame([], DatabaseTargetRules::targetProblems($env, 'pgsql', $this->connections(), self::APPROVED), $env);
        }
    }

    public function test_the_default_connection_must_be_pgsql(): void
    {
        foreach (['mysql', 'sqlite', 'pgsql_audit', 'testing'] as $default) {
            $this->assertNotSame([], DatabaseTargetRules::targetProblems('testing', $default, $this->connections(), self::APPROVED), $default);
        }
    }

    public static function unsafeConfinedConnections(): array
    {
        return [
            'default on the dev database'   => [['pgsql' => ['database' => 'publicdigit']]],
            'default on nrna'               => [['pgsql' => ['database' => 'nrna']]],
            'audit elsewhere'               => [['pgsql_audit' => ['database' => 'nrna']]],
            'geo on its repository default' => [['pgsql_geo' => ['database' => 'publicdigit']]],
            'geo missing'                   => [['pgsql_geo' => null]],
            'audit not postgres'            => [['pgsql_audit' => ['driver' => 'mysql']]],
            'approved name, other case'     => [['pgsql' => ['database' => 'NRNA_TEST']]],
            'database missing'              => [['pgsql' => ['database' => null]]],
        ];
    }

    #[DataProvider('unsafeConfinedConnections')]
    public function test_a_confined_connection_off_the_approved_set_is_a_problem(array $override): void
    {
        $this->assertNotSame([], DatabaseTargetRules::targetProblems('testing', 'pgsql', $this->connections($override), self::APPROVED));
    }

    public static function urlOverrides(): array
    {
        return [
            'default'        => ['pgsql'],
            'audit'          => ['pgsql_audit'],
            'geo'            => ['pgsql_geo'],
            'unused mysql'   => ['mysql'],
            'unused sqlite'  => ['sqlite'],
        ];
    }

    #[DataProvider('urlOverrides')]
    public function test_a_url_on_any_connection_is_a_problem(string $name): void
    {
        $problems = DatabaseTargetRules::targetProblems(
            'testing', 'pgsql', $this->connections([$name => ['url' => 'pgsql://u:s3cr3t@db.example/publicdigit']]), self::APPROVED,
        );

        $this->assertNotSame([], $problems);
        $this->assertStringNotContainsString('s3cr3t', implode(' ', $problems), 'a URL may carry a password; never echo it');
    }

    public function test_the_rules_follow_the_callers_approved_set(): void
    {
        $connections = $this->connections(['pgsql_geo' => ['database' => 'nrna_test']]);

        $this->assertSame([], DatabaseTargetRules::targetProblems('testing', 'pgsql', $connections, ['nrna_test']));
        $this->assertNotSame([], DatabaseTargetRules::targetProblems('testing', 'pgsql', $connections, ['pbd_isolated']));
    }

    public function test_an_empty_approved_set_refuses_everything(): void
    {
        $this->assertNotSame([], DatabaseTargetRules::targetProblems('testing', 'pgsql', $this->connections(), []));
    }

    public function test_every_problem_is_reported_not_only_the_first(): void
    {
        $problems = DatabaseTargetRules::targetProblems('local', 'mysql', $this->connections([
            'pgsql_audit' => ['database' => 'nrna'],
            'pgsql_geo'   => ['database' => 'publicdigit'],
        ]), self::APPROVED);

        $this->assertCount(4, $problems);
    }
}
