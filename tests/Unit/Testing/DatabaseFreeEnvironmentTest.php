<?php

declare(strict_types=1);

namespace Tests\Unit\Testing;

use PHPUnit\Framework\TestCase;
use Tests\Support\DatabaseFreeEnvironment;
use Tests\Support\DatabaseTargetRules;
use Tests\Support\TestDatabaseSafety;

/**
 * PBDIGIT-283 R1(b1), decision D-283-3, correction C-3: the environment a database-free test boots Laravel
 * under. Pure: these tests never boot Laravel; the neutralisation is checked as data, and apply/restore on
 * throw-away keys.
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Testing/DatabaseFreeEnvironmentTest.php
 */
final class DatabaseFreeEnvironmentTest extends TestCase
{
    private const PREFIXES = ['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'];

    public function test_every_target_points_at_a_non_existent_socket_under_an_approved_name(): void
    {
        $vars = DatabaseFreeEnvironment::variables();

        $this->assertSame('testing', $vars['APP_ENV']);
        $this->assertSame('pgsql', $vars['DB_CONNECTION']);
        $this->assertSame('', $vars['DATABASE_URL'], 'set and empty, so .env cannot fill it in');
        foreach (self::PREFIXES as $prefix) {
            $this->assertSame(DatabaseFreeEnvironment::SOCKET, $vars[$prefix.'HOST'], $prefix);
            $this->assertArrayHasKey($prefix.'PORT', $vars, $prefix);
            $this->assertContains($vars[$prefix.'DATABASE'], TestDatabaseSafety::RESETTABLE, $prefix);
        }
        $this->assertSame(DatabaseFreeEnvironment::SOCKET, $vars['DB_READ_HOST']);
        $this->assertSame(DatabaseFreeEnvironment::SOCKET, $vars['DB_WRITE_HOST']);
    }

    public function test_the_socket_does_not_exist(): void
    {
        $this->assertStringStartsWith('/', DatabaseFreeEnvironment::SOCKET, 'a PostgreSQL host starting with / is a socket directory');
        $this->assertFileDoesNotExist(DatabaseFreeEnvironment::SOCKET);
    }

    public function test_the_neutralised_targets_satisfy_the_shared_rules(): void
    {
        $vars = DatabaseFreeEnvironment::variables();
        $connections = [
            'pgsql'       => ['driver' => 'pgsql', 'url' => $vars['DATABASE_URL'], 'database' => $vars['DB_DATABASE']],
            'pgsql_audit' => ['driver' => 'pgsql', 'database' => $vars['DB_AUDIT_DATABASE']],
            'pgsql_geo'   => ['driver' => 'pgsql', 'database' => $vars['DB_PGSQL_GEO_DATABASE']],
        ];

        $this->assertSame([], DatabaseTargetRules::targetProblems(
            $vars['APP_ENV'], $vars['DB_CONNECTION'], $connections, TestDatabaseSafety::RESETTABLE,
        ));
    }

    // ── apply and restore ───────────────────────────────────────────────────

    public function test_restore_returns_set_empty_and_absent_variables_to_their_previous_state(): void
    {
        $suffix = bin2hex(random_bytes(4));
        $set = "PBD283_SET_{$suffix}";
        $empty = "PBD283_EMPTY_{$suffix}";
        $absent = "PBD283_ABSENT_{$suffix}";
        $_SERVER[$set] = $_ENV[$set] = 'before';
        putenv("{$set}=before");
        $_SERVER[$empty] = $_ENV[$empty] = '';
        putenv("{$empty}=");

        try {
            $snapshot = DatabaseFreeEnvironment::apply([$set => 'x', $empty => 'y', $absent => 'z']);
            foreach ([$set => 'x', $empty => 'y', $absent => 'z'] as $key => $value) {
                $this->assertSame($value, $_SERVER[$key]);
                $this->assertSame($value, $_ENV[$key]);
                $this->assertSame($value, getenv($key));
            }

            DatabaseFreeEnvironment::restore($snapshot);

            $this->assertSame('before', $_SERVER[$set]);
            $this->assertSame('before', $_ENV[$set]);
            $this->assertSame('before', getenv($set));
            $this->assertSame('', $_SERVER[$empty]);
            $this->assertSame('', $_ENV[$empty]);
            $this->assertSame('', getenv($empty));
            $this->assertArrayNotHasKey($absent, $_SERVER);
            $this->assertArrayNotHasKey($absent, $_ENV);
            $this->assertFalse(getenv($absent));
        } finally {
            foreach ([$set, $empty, $absent] as $key) {
                unset($_SERVER[$key], $_ENV[$key]);
                putenv($key);
            }
        }
    }

    public function test_restore_keeps_the_sources_separate(): void
    {
        // A key present in $_SERVER only must not reappear in $_ENV or the process environment.
        $key = 'PBD283_SERVER_ONLY_'.bin2hex(random_bytes(4));
        $_SERVER[$key] = 'server';

        try {
            DatabaseFreeEnvironment::restore(DatabaseFreeEnvironment::apply([$key => 'x']));

            $this->assertSame('server', $_SERVER[$key]);
            $this->assertArrayNotHasKey($key, $_ENV);
            $this->assertFalse(getenv($key));
        } finally {
            unset($_SERVER[$key], $_ENV[$key]);
            putenv($key);
        }
    }

    // ── the pre-boot check ──────────────────────────────────────────────────

    /** @return array{0: array, 1: array, 2: array} $_SERVER, $_ENV and getenv() after a full neutralisation */
    private function neutralised(): array
    {
        $vars = DatabaseFreeEnvironment::variables();

        return [$vars, $vars, $vars];
    }

    public function test_a_fully_neutralised_environment_has_no_unsafe_target(): void
    {
        [$server, $env, $process] = $this->neutralised();

        $this->assertSame([], DatabaseFreeEnvironment::unsafeTargets($server, $env, $process));
    }

    public function test_every_target_missing_or_different_in_any_source_is_unsafe(): void
    {
        foreach (array_keys(DatabaseFreeEnvironment::variables()) as $key) {
            foreach ([0, 1, 2] as $source) {
                $sources = $this->neutralised();
                unset($sources[$source][$key]);
                $this->assertSame([$key], DatabaseFreeEnvironment::unsafeTargets(...$sources), "{$key} missing in source {$source}");

                $sources = $this->neutralised();
                $sources[$source][$key] = 'publicdigit';
                $this->assertSame([$key], DatabaseFreeEnvironment::unsafeTargets(...$sources), "{$key} changed in source {$source}");
            }
        }
    }

    public function test_a_set_url_is_unsafe_even_though_other_targets_are_neutralised(): void
    {
        [$server, $env, $process] = $this->neutralised();
        $process['DATABASE_URL'] = 'pgsql://u:p@localhost/publicdigit';

        $this->assertSame(['DATABASE_URL'], DatabaseFreeEnvironment::unsafeTargets($server, $env, $process));
    }

    // ── enter(): apply, then check, refusing before any boot ───────────────

    private function missingCache(): string
    {
        return sys_get_temp_dir().'/pbd283-no-such-cache-'.bin2hex(random_bytes(4)).'.php';
    }

    /** @return array<string, array{0: mixed, 1: mixed, 2: string|false}> every target's value in each source */
    private function liveTargets(): array
    {
        $live = [];
        foreach (array_keys(DatabaseFreeEnvironment::variables()) as $key) {
            $live[$key] = [$_SERVER[$key] ?? null, $_ENV[$key] ?? null, getenv($key)];
        }

        return $live;
    }

    public function test_enter_neutralises_every_target_and_its_snapshot_restores_them(): void
    {
        $before = $this->liveTargets();

        $snapshot = DatabaseFreeEnvironment::enter($this->missingCache());
        try {
            $this->assertSame([], DatabaseFreeEnvironment::unsafeTargets($_SERVER, $_ENV, getenv()));
        } finally {
            DatabaseFreeEnvironment::restore($snapshot);
        }

        $this->assertSame($before, $this->liveTargets());
    }

    public function test_enter_refuses_a_neutralisation_that_misses_a_target_and_restores_first(): void
    {
        $before = $this->liveTargets();
        $incomplete = DatabaseFreeEnvironment::variables();
        unset($incomplete['DB_PGSQL_GEO_HOST']);
        $sentinel = '/nonexistent-283-sentinel-'.bin2hex(random_bytes(4));
        $geo = DatabaseFreeEnvironment::apply(['DB_PGSQL_GEO_HOST' => $sentinel]);

        try {
            DatabaseFreeEnvironment::enter($this->missingCache(), $incomplete);
            $this->fail('expected a refusal before boot');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('DB_PGSQL_GEO_HOST', $e->getMessage());
            $this->assertStringNotContainsString($sentinel, $e->getMessage(), 'names keys, never values');
            $this->assertSame($sentinel, getenv('DB_PGSQL_GEO_HOST'), 'restored before the refusal');
        } finally {
            DatabaseFreeEnvironment::restore($geo);
        }
        $this->assertSame($before, $this->liveTargets());
    }

    public function test_enter_refuses_a_cached_configuration_and_restores_first(): void
    {
        $before = $this->liveTargets();
        $file = tempnam(sys_get_temp_dir(), 'pbd283cfg');

        // PHPUnit's own failures extend RuntimeException: capture the refusal, assert outside the try (PBDIGIT-283 R3).
        $refusal = null;
        try {
            DatabaseFreeEnvironment::restore(DatabaseFreeEnvironment::enter($file));   // restore only if not refused
        } catch (\RuntimeException $e) {
            $refusal = $e;
        } finally {
            unlink($file);
        }

        $this->assertNotNull($refusal, 'expected a refusal: a cached configuration ignores every variable');
        $this->assertStringContainsString('The configuration is cached', $refusal->getMessage());
        $this->assertSame($before, $this->liveTargets());
    }

    public function test_enter_refuses_an_app_config_cache_override(): void
    {
        $override = DatabaseFreeEnvironment::apply(['APP_CONFIG_CACHE' => $this->missingCache()]);

        try {
            DatabaseFreeEnvironment::enter($this->missingCache());
            $this->fail('expected a refusal: APP_CONFIG_CACHE is set');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('cached', $e->getMessage());
        } finally {
            DatabaseFreeEnvironment::restore($override);
        }
    }
}
