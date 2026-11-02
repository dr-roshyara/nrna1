<?php

declare(strict_types=1);

namespace Tests\Unit\Testing;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-324 step 4 (plan §3.4, AC-2): the Membership PostgreSQL bootstrap, run as a child process with a
 * controlled environment (the pattern of tests/Isolated/Pbdigit274/Guard/IsolatedBootstrapScriptTest).
 *
 * Database-free: every case is refused before a connection is attempted, except the last one, whose credentials
 * point at a non-existent Unix-socket directory, so it cannot reach a network or a server and fails closed.
 * The child gets only the variables listed here, never this process's environment or .env.
 *
 * Note: like the PBDIGIT-274 bootstrap, the guard refuses a cached Laravel configuration
 * (bootstrap/cache/config.php). In a working tree that has one, the cases after the credential check report
 * [config_cached] instead; run these tests in a tree without a config cache.
 */
final class MembershipPgBootstrapTest extends TestCase
{
    private const ROOT = __DIR__.'/../../..';
    private const SCRIPT = self::ROOT.'/tests/bootstrap-membership-pg.php';
    private const CONFIG = self::ROOT.'/phpunit.membership-pg.xml';

    private const CREDENTIALS = [
        'MEMBERSHIP_PG_DB_HOST'     => '/nonexistent-membership-pg-socket-dir',
        'MEMBERSHIP_PG_DB_USERNAME' => 'membership_pg',
        'MEMBERSHIP_PG_DB_PASSWORD' => 'pw-must-never-be-printed',
    ];

    private const SYSTEM_ID = ['MEMBERSHIP_PG_SYSTEM_ID' => '7432001'];

    /** @return array{int, string, string} exit code, stdout, stderr */
    private function runBootstrap(array $env): array
    {
        // `env -i` starts from an empty environment and also passes variables whose value is empty.
        $command = ['env', '-i', 'PATH='.getenv('PATH')];
        foreach ($env as $key => $value) {
            $command[] = "{$key}={$value}";
        }
        $command[] = PHP_BINARY;
        $command[] = self::SCRIPT;
        $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $this->assertIsResource($process);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return [proc_close($process), $stdout, $stderr];
    }

    public static function refusedBeforeAnyConnection(): array
    {
        $creds = self::CREDENTIALS + self::SYSTEM_ID;
        $name = static fn (string $n): array => ['MEMBERSHIP_PG_TEST_DB' => $n];

        return [
            'name missing'                => [$creds, 'missing'],
            'name empty'                  => [$name('') + $creds, 'empty'],
            'reserved word null'          => [$name('null') + $creds, 'reserved'],
            'shared test db'              => [$name('nrna_test') + $creds, 'forbidden'],
            'malformed'                   => [$name('membership-pg') + $creds, 'malformed'],
            'no credentials'              => [$name('membership_pg_isolated') + self::SYSTEM_ID, 'credentials_missing'],
            'only PBDIGIT-274 credentials' => [$name('membership_pg_isolated') + self::SYSTEM_ID + ['PBDIGIT274_DB_HOST' => '/x', 'PBDIGIT274_DB_USERNAME' => 'u'], 'credentials_missing'],
            'config cache override'       => [$name('membership_pg_isolated') + ['APP_CONFIG_CACHE' => '/tmp/x.php'] + $creds, 'config_cached'],
            'the PBDIGIT-274 database'    => [$name('pbdigit274_isolated_test') + $creds, 'not_approved'],
            'near name'                   => [$name('membership_pg_isolated_x') + $creds, 'not_approved'],
            'system id missing'           => [$name('membership_pg_isolated') + self::CREDENTIALS, 'system_id_missing'],
            'system id malformed'         => [$name('membership_pg_isolated') + self::CREDENTIALS + ['MEMBERSHIP_PG_SYSTEM_ID' => 'abc'], 'system_id_missing'],
        ];
    }

    #[DataProvider('refusedBeforeAnyConnection')]
    public function test_unsafe_configuration_aborts_before_any_connection(array $env, string $reason): void
    {
        [$exit, $stdout, $stderr] = $this->runBootstrap($env);

        $this->assertNotSame(0, $exit);
        $this->assertStringContainsString("[{$reason}]", $stderr);
        $this->assertStringNotContainsString('[connection_failed]', $stderr);
        $this->assertStringNotContainsString('confirmed', $stderr);
        $this->assertStringNotContainsString('pw-must-never-be-printed', $stdout.$stderr);
    }

    public function test_the_approved_name_with_a_system_id_still_has_to_reach_the_cluster(): void
    {
        [$exit, $stdout, $stderr] = $this->runBootstrap(['MEMBERSHIP_PG_TEST_DB' => 'membership_pg_isolated'] + self::CREDENTIALS + self::SYSTEM_ID);

        $this->assertNotSame(0, $exit);
        $this->assertStringContainsString('[connection_failed]', $stderr);
        $this->assertStringNotContainsString('confirmed', $stderr);
        $this->assertStringNotContainsString('pw-must-never-be-printed', $stdout.$stderr);
    }

    public function test_the_bootstrap_never_boots_laravel_or_names_a_shared_database(): void
    {
        $this->assertFileExists(self::SCRIPT);
        $source = (string) file_get_contents(self::SCRIPT);
        $this->assertStringNotContainsString('nrna_test', $source);
        $this->assertStringNotContainsString('bootstrap/app.php', $source, 'the bootstrap must not boot Laravel');
        $this->assertStringNotContainsString('bootstrap-isolated-database', $source, 'it does not chain to the PBDIGIT-274 bootstrap');
    }

    public function test_the_configuration_runs_only_the_membership_suite_and_sets_no_database_values(): void
    {
        $this->assertFileExists(self::CONFIG);
        $xml = simplexml_load_file(self::CONFIG);
        $this->assertNotFalse($xml);

        $this->assertSame('tests/bootstrap-membership-pg.php', (string) $xml['bootstrap']);
        $directories = array_map('strval', $xml->xpath('//testsuite/directory'));
        $this->assertSame(['./tests/Feature/Contexts/Membership/Organisational'], $directories);
        $this->assertSame([], $xml->xpath('//testsuite/file'));

        foreach ($xml->xpath('//php/*') as $entry) {
            $name = (string) $entry['name'];
            $this->assertDoesNotMatchRegularExpression('/^(DB_|DATABASE_URL|PBDIGIT274_|MEMBERSHIP_PG_)/', $name, "{$name} must come from the environment, not the file");
        }
    }
}
