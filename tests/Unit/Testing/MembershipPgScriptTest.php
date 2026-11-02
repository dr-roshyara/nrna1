<?php

declare(strict_types=1);

namespace Tests\Unit\Testing;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-324 step 5 (plan §3.5, AC-6, AC-7): scripts/testing/membership-pg.sh, checked without starting a
 * container. Static rules on the source, plus the refusals that happen before podman is ever called.
 * Database-free and container-free.
 */
final class MembershipPgScriptTest extends TestCase
{
    private const SCRIPT = __DIR__.'/../../../scripts/testing/membership-pg.sh';

    private function source(): string
    {
        $this->assertFileExists(self::SCRIPT);

        return (string) file_get_contents(self::SCRIPT);
    }

    /** @return array{int, string} exit code, stdout + stderr */
    private function runScript(array $env): array
    {
        $command = ['env', '-i', 'PATH='.getenv('PATH')];
        foreach ($env as $key => $value) {
            $command[] = "{$key}={$value}";
        }
        $command[] = 'bash';
        $command[] = self::SCRIPT;
        $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $this->assertIsResource($process);
        $out = stream_get_contents($pipes[1]).stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return [proc_close($process), $out];
    }

    public function test_the_script_parses(): void
    {
        // It is run as `bash scripts/testing/membership-pg.sh`, so the executable bit is not required.
        $this->assertStringStartsWith('#!/usr/bin/env bash', $this->source());
        exec('bash -n '.escapeshellarg(self::SCRIPT).' 2>&1', $output, $exit);
        $this->assertSame(0, $exit, implode("\n", $output));
    }

    public function test_the_script_fails_on_any_error(): void
    {
        $this->assertStringContainsString('set -euo pipefail', $this->source());
    }

    public function test_the_database_port_is_bound_to_loopback_only(): void
    {
        $source = $this->source();
        $this->assertStringContainsString('-p 127.0.0.1::5432', $source);
        // Any other publish mapping to the container port (e.g. `-p 5432:5432`, `-p 0.0.0.0::5432`) is refused.
        // `pg_isready -p 5432` inside the container is a client port, not a publish mapping, so it is not matched.
        $this->assertDoesNotMatchRegularExpression('/-p\s+(?!127\.0\.0\.1::5432\b)\S*:5432\b/', $source);
        $this->assertDoesNotMatchRegularExpression('/--publish/', $source);
        $this->assertStringContainsString('"$PG_HOST" != "127.0.0.1"', $source, 'the published address is checked after start');
    }

    public function test_the_container_is_always_removed(): void
    {
        $source = $this->source();
        $this->assertMatchesRegularExpression('/trap\s+cleanup\s+EXIT/', $source);
        $this->assertMatchesRegularExpression('/cleanup\(\)\s*\{[^}]*podman rm -f/s', $source);
    }

    public function test_the_password_is_random_and_never_printed_or_put_on_a_command_line(): void
    {
        $source = $this->source();
        $this->assertStringContainsString('openssl rand', $source);
        foreach (preg_split('/\R/', $source) as $n => $line) {
            if (preg_match('/^\s*#/', $line)) {
                continue;
            }
            // The script keeps the password in $SECRET; no printing command may name it or a *PASS* variable.
            $this->assertDoesNotMatchRegularExpression('/\b(echo|printf|tee)\b.*(PASS|SECRET)/', $line, 'line '.($n + 1).' prints the password');
            $this->assertDoesNotMatchRegularExpression('/-e\s+POSTGRES_PASSWORD=/', $line, 'line '.($n + 1).' puts the password on the podman command line');
        }
        $this->assertStringContainsString('-e POSTGRES_PASSWORD ', $source, 'the password is passed by name from the environment');
    }

    public function test_the_script_uses_only_its_own_configuration_and_never_dotenv(): void
    {
        $source = $this->source();
        $this->assertStringContainsString('phpunit.membership-pg.xml', $source);
        $this->assertStringNotContainsString('.env', $source);
        $this->assertDoesNotMatchRegularExpression('/\bDB_(HOST|PORT|DATABASE|USERNAME|PASSWORD)\b/', preg_replace('/MEMBERSHIP_PG_DB_[A-Z]+/', '', $source));
        $this->assertStringContainsString('membership_pg_isolated', $source);
        $this->assertStringContainsString('MEMBERSHIP_PG_SYSTEM_ID', $source);
    }

    public function test_an_unset_image_is_refused_before_podman_is_called(): void
    {
        [$exit, $out] = $this->runScript([]);
        $this->assertNotSame(0, $exit);
        $this->assertStringContainsString('MEMBERSHIP_PG_IMAGE', $out);
        $this->assertStringNotContainsString('podman run', $out);
    }

    public function test_an_image_not_pinned_by_digest_is_refused_before_podman_is_called(): void
    {
        [$exit, $out] = $this->runScript(['MEMBERSHIP_PG_IMAGE' => 'docker.io/library/postgres:16']);
        $this->assertNotSame(0, $exit);
        $this->assertStringContainsString('digest', $out);
    }
}
