<?php

namespace Tests\Isolated\Pbdigit274\Guard;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-274 S2 steps 1 and 1b: the real bootstrap script, run as a child process with a controlled
 * environment.
 *
 * Database-free: every case is refused before a connection is attempted. Credentials point at a
 * non-existent Unix-socket directory, so even a connection attempt could not reach a network or a server.
 * Since step 1b nothing is approved before G4 (DatabaseIdentityGuard::APPROVED_NAME is empty), so the
 * script-level `connection_failed` case is unreachable by design; it stays covered by the unit test.
 * The child gets only the variables listed here, never this process's environment or .env.
 */
class IsolatedBootstrapScriptTest extends TestCase
{
    private const SCRIPT = __DIR__.'/../../../bootstrap-isolated-database.php';

    private const CREDENTIALS = [
        'PBDIGIT274_DB_HOST'     => '/nonexistent-pbdigit274-socket-dir',
        'PBDIGIT274_DB_USERNAME' => 'iso_user',
        'PBDIGIT274_DB_PASSWORD' => 'pw-must-never-be-printed',
    ];

    /** @return array{int, string, string} exit code, stdout, stderr */
    private function runBootstrap(array $env): array
    {
        // `env -i` starts from an empty environment and, unlike proc_open's $env argument, also passes
        // variables whose value is empty (proc_open silently drops them).
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
        $creds = self::CREDENTIALS;

        return [
            'name missing'          => [$creds, 'missing'],
            'name empty'            => [['PBDIGIT274_TEST_DB' => ''] + $creds, 'empty'],
            'name whitespace'       => [['PBDIGIT274_TEST_DB' => '   '] + $creds, 'empty'],
            'reserved word null'    => [['PBDIGIT274_TEST_DB' => 'null'] + $creds, 'reserved'],
            'reserved word TRUE'    => [['PBDIGIT274_TEST_DB' => 'TRUE'] + $creds, 'reserved'],
            'shared test db'        => [['PBDIGIT274_TEST_DB' => 'nrna_test'] + $creds, 'forbidden'],
            'shared, upper'         => [['PBDIGIT274_TEST_DB' => 'NRNA_TEST'] + $creds, 'forbidden'],
            'malformed'             => [['PBDIGIT274_TEST_DB' => 'pbd-isolated'] + $creds, 'malformed'],
            'no credentials'        => [['PBDIGIT274_TEST_DB' => 'pbd_isolated'], 'credentials_missing'],
            'config cache override' => [['PBDIGIT274_TEST_DB' => 'pbd_isolated', 'APP_CONFIG_CACHE' => '/tmp/x.php'] + $creds, 'config_cached'],
            'well formed, not approved' => [['PBDIGIT274_TEST_DB' => 'pbd_isolated'] + $creds, 'not_approved'],
            'repository default name'   => [['PBDIGIT274_TEST_DB' => 'publicdigit'] + $creds, 'not_approved'],
        ];
    }

    #[DataProvider('refusedBeforeAnyConnection')]
    public function test_unsafe_configuration_aborts_before_any_connection(array $env, string $reason): void
    {
        // Credentials are present in every case but one, so any reason other than connection_failed proves
        // the guard stopped before it tried to connect.
        [$exit, $stdout, $stderr] = $this->runBootstrap($env);

        $this->assertNotSame(0, $exit);
        $this->assertStringContainsString("[{$reason}]", $stderr);
        $this->assertStringNotContainsString('[connection_failed]', $stderr);
        $this->assertStringNotContainsString('confirmed', $stderr);
        $this->assertStringNotContainsString('pw-must-never-be-printed', $stdout.$stderr);
    }

    public function test_the_script_never_falls_back_to_the_shared_test_bootstrap(): void
    {
        $this->assertFileExists(self::SCRIPT);
        $source = (string) file_get_contents(self::SCRIPT);
        $this->assertStringNotContainsString('bootstrap-test-database', $source);
        $this->assertStringNotContainsString('nrna_test', $source);
        $this->assertStringNotContainsString('bootstrap/app.php', $source, 'the bootstrap must not boot Laravel');
    }
}
