<?php

declare(strict_types=1);

namespace Tests\Unit\Testing;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Support\NegativeEvidence;

/**
 * PBDIGIT-283 R6 negative evidence (G-RUN-NEG): the pure rules of the harness tests/Evidence/Pbdigit283/negative-evidence.php.
 * The harness itself is not run here; these tests only check its decisions on given data.
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Testing/NegativeEvidenceTest.php
 */
final class NegativeEvidenceTest extends TestCase
{
    // ── the child environment ───────────────────────────────────────────────

    public function test_the_child_environment_points_every_target_at_a_dead_socket_and_the_default_at_publicdigit(): void
    {
        $env = NegativeEvidence::childEnvironment('/usr/bin', '/home/x');

        $this->assertSame(['PATH' => '/usr/bin', 'HOME' => '/home/x'], array_intersect_key($env, ['PATH' => 1, 'HOME' => 1]));
        $this->assertSame('testing', $env['APP_ENV']);
        $this->assertSame('pgsql', $env['DB_CONNECTION']);
        $this->assertSame('', $env['DATABASE_URL']);
        $this->assertSame('', $env['DB_URL'], "Laravel 11's own URL variable, read by the framework-merged connections");
        foreach (['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {
            $this->assertSame(NegativeEvidence::SOCKET, $env[$prefix.'HOST'], $prefix);
            $this->assertSame(NegativeEvidence::CLOSED_PORT, $env[$prefix.'PORT'], $prefix);
            $this->assertSame(NegativeEvidence::UNSAFE_DATABASE, $env[$prefix.'DATABASE'], $prefix);
            $this->assertSame('', $env[$prefix.'PASSWORD'], $prefix);
        }
        foreach (['DB_READ_HOST', 'DB_WRITE_HOST', 'DB_SOCKET'] as $key) {
            $this->assertSame(NegativeEvidence::SOCKET, $env[$key], $key);
        }
        $this->assertArrayNotHasKey('APP_CONFIG_CACHE', $env);
    }

    public function test_the_child_environment_inherits_nothing_else(): void
    {
        $this->assertSame([], array_diff_key(NegativeEvidence::childEnvironment('/p', '/h'), NegativeEvidence::variables() + ['PATH' => 1, 'HOME' => 1]));
    }

    // ── the pre-run check ───────────────────────────────────────────────────

    /** A pre-run report as the harness expects it under the child environment (no passwords). */
    private function report(array $override = []): array
    {
        $socket = NegativeEvidence::SOCKET;
        $env = fn (string $driver, string $database) => ['driver' => $driver, 'url' => '', 'host' => $socket, 'port' => '1', 'database' => $database, 'username' => 'nobody'];
        $report = [
            'cached' => false,
            'env' => 'testing',
            'default' => 'pgsql',
            'bound_db' => false,
            'sqlite_target_exists' => false,
            'connections' => [
                'sqlite'      => ['driver' => 'sqlite', 'url' => '', 'database' => 'publicdigit'],
                'mysql'       => $env('mysql', 'publicdigit') + ['write' => ['host' => $socket], 'unix_socket' => $socket],
                'pgsql'       => $env('pgsql', 'publicdigit'),
                'pgsql_audit' => array_diff_key($env('pgsql', 'publicdigit'), ['url' => 1]),
                'pgsql_geo'   => array_diff_key($env('pgsql', 'publicdigit'), ['url' => 1]),
                'sqlsrv'      => $env('sqlsrv', 'publicdigit'),
                // merged from vendor/laravel/framework/config/database.php by LoadConfiguration (environment-controlled)
                'mariadb'     => $env('mariadb', 'publicdigit') + ['unix_socket' => $socket],
                'testing'     => ['driver' => 'pgsql', 'host' => '127.0.0.1', 'port' => 5432, 'database' => 'nrna_test', 'username' => 'nrna'],
            ],
        ];

        return array_replace_recursive($report, $override);
    }

    public function test_the_expected_report_passes(): void
    {
        $this->assertSame([], NegativeEvidence::preflightProblems($this->report()));
    }

    public static function unsafeReports(): array
    {
        $other = '/tmp';

        return [
            'configuration cached'           => [['cached' => true]],
            'APP_ENV not testing'            => [['env' => 'local']],
            'default not pgsql'              => [['default' => 'testing']],
            'database manager bound'         => [['bound_db' => true]],
            'sqlite target exists'           => [['sqlite_target_exists' => true]],
            'default not the unsafe canary'  => [['connections' => ['pgsql' => ['database' => 'nrna_test']]]],
            'default host reachable'         => [['connections' => ['pgsql' => ['host' => '127.0.0.1']]]],
            'audit host elsewhere'           => [['connections' => ['pgsql_audit' => ['host' => $other]]]],
            'geo port open'                  => [['connections' => ['pgsql_geo' => ['port' => '5432']]]],
            'mysql write host'               => [['connections' => ['mysql' => ['write' => ['host' => '127.0.0.1']]]]],
            'mysql unix socket'              => [['connections' => ['mysql' => ['unix_socket' => '']]]],
            'sqlsrv host'                    => [['connections' => ['sqlsrv' => ['host' => 'localhost']]]],
            'mariadb host'                   => [['connections' => ['mariadb' => ['host' => '127.0.0.1']]]],
            'mariadb port open'              => [['connections' => ['mariadb' => ['port' => '3306']]]],
            'mariadb unix socket'            => [['connections' => ['mariadb' => ['unix_socket' => '']]]],
            'mariadb url (DB_URL)'           => [['connections' => ['mariadb' => ['url' => 'mariadb://x@h/publicdigit']]]],
            'url on a connection'            => [['connections' => ['pgsql' => ['url' => 'pgsql://x@h/publicdigit']]]],
            'url on the exception'           => [['connections' => ['testing' => ['url' => 'pgsql://x@h/nrna_test']]]],
            'exception: other host'          => [['connections' => ['testing' => ['host' => '10.0.0.1']]]],
            'exception: other database'      => [['connections' => ['testing' => ['database' => 'publicdigit']]]],
            'exception: other port'          => [['connections' => ['testing' => ['port' => 5433]]]],
            'exception: other user'          => [['connections' => ['testing' => ['username' => 'postgres']]]],
            'exception: other driver'        => [['connections' => ['testing' => ['driver' => 'mysql']]]],
            'unknown connection'             => [['connections' => ['landlord' => ['driver' => 'pgsql', 'host' => '127.0.0.1', 'database' => 'x']]]],
            'a password in the report'       => [['connections' => ['pgsql' => ['password' => 'x']]]],
        ];
    }

    #[DataProvider('unsafeReports')]
    public function test_an_unsafe_or_unexpected_report_stops_the_run(array $override): void
    {
        $this->assertNotSame([], NegativeEvidence::preflightProblems($this->report($override)));
    }

    public function test_a_missing_exception_connection_stops_the_run(): void
    {
        $report = $this->report();
        unset($report['connections']['testing']);

        $this->assertNotSame([], NegativeEvidence::preflightProblems($report));
    }

    public function test_a_missing_framework_merged_connection_stops_the_run(): void
    {
        // The loaded set must be exactly the reviewed one; Laravel adds `mariadb` from its defaults (no MariaDB in use).
        $report = $this->report();
        unset($report['connections']['mariadb']);

        $this->assertNotSame([], NegativeEvidence::preflightProblems($report));
    }

    public function test_a_problem_never_echoes_a_url(): void
    {
        $problems = NegativeEvidence::preflightProblems($this->report(['connections' => ['pgsql' => ['url' => 'pgsql://u:s3cr3t@h/x']]]));

        $this->assertStringNotContainsString('s3cr3t', implode(' ', $problems));
    }

    // ── the refusal ─────────────────────────────────────────────────────────

    private const REFUSED = "There was 1 error:\n1) Tests\\Evidence\\Pbdigit283\\ResetCanaryTest::test_the_canary_body_is_never_reached\n"
        ."RuntimeException: PBDIGIT-283: refusing to boot Laravel for a test before any service provider is registered; nothing was connected. "
        ."Connection 'pgsql' targets 'publicdigit', which is not approved.\nERRORS!\nTests: 1, Assertions: 0, Errors: 1.";

    public function test_the_expected_refusal_is_evidence(): void
    {
        $this->assertSame([], NegativeEvidence::refusalProblems(2, self::REFUSED));
    }

    public static function notEvidence(): array
    {
        return [
            'exit code 0'              => [0, self::REFUSED],
            'no refusal message'       => [2, "ERRORS!\nTests: 1, Assertions: 0, Errors: 1. ResetCanaryTest"],
            'refused for another reason' => [2, str_replace("Connection 'pgsql' targets 'publicdigit'", "APP_ENV is 'local'", self::REFUSED)],
            'a connection attempt'     => [2, self::REFUSED."\nSQLSTATE[08006] connection to server failed"],
            'the canary body ran'      => [2, self::REFUSED."\nthe canary body was reached"],
            'another test ran'         => [2, str_replace('ResetCanaryTest', 'OtherTest', self::REFUSED)],
            'more than one test'       => [2, str_replace('Tests: 1,', 'Tests: 2,', self::REFUSED)],
        ];
    }

    #[DataProvider('notEvidence')]
    public function test_anything_else_is_not_evidence(int $exitCode, string $output): void
    {
        $this->assertNotSame([], NegativeEvidence::refusalProblems($exitCode, $output));
    }
}
