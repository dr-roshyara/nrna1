<?php

declare(strict_types=1);

namespace Tests\Support;

use RuntimeException;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;

/**
 * The environment a database-free test boots Laravel under (PBDIGIT-283 R1, decision D-283-3, correction C-3;
 * plan docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md).
 *
 * Every database target points at a socket directory that does not exist, under an approved name, and the
 * DATABASE_URL override is set and empty. The values are written where Laravel's environment repository reads
 * them ($_SERVER, $_ENV, putenv); the repository is immutable, so .env cannot override them. They are set
 * before bootstrap/app.php is required and restored afterwards, so nothing leaks into later tests.
 * Pure: no framework, no database.
 */
final class DatabaseFreeEnvironment
{
    /** A PostgreSQL host starting with '/' is a socket directory; this one does not exist, so any query fails. */
    public const SOCKET = '/nonexistent-283-database-free-socket-dir';

    /** An approved name (TestDatabaseSafety::RESETTABLE), never reached: the socket does not exist. */
    public const DATABASE = 'nrna_test';

    /** @return array<string, string> every variable that selects a database target, neutralised */
    public static function variables(): array
    {
        $vars = ['APP_ENV' => 'testing', 'DB_CONNECTION' => 'pgsql', 'DATABASE_URL' => ''];
        foreach (['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {
            $vars[$prefix.'HOST'] = self::SOCKET;
            $vars[$prefix.'PORT'] = '5432';
            $vars[$prefix.'DATABASE'] = self::DATABASE;
        }
        $vars['DB_READ_HOST'] = self::SOCKET;
        $vars['DB_WRITE_HOST'] = self::SOCKET;

        return $vars;
    }

    /**
     * Writes $variables to $_SERVER, $_ENV and putenv, and returns what was there before, per source.
     *
     * @param array<string, string> $variables
     * @return array<string, array{server: ?string, env: ?string, process: string|false}>
     */
    public static function apply(array $variables): array
    {
        $snapshot = [];
        foreach (array_keys($variables) as $key) {
            $snapshot[$key] = [
                'server' => array_key_exists($key, $_SERVER) ? $_SERVER[$key] : null,
                'env' => array_key_exists($key, $_ENV) ? $_ENV[$key] : null,
                'process' => getenv($key),
            ];
        }
        DatabaseIdentityGuard::applyEnvironment($variables);

        return $snapshot;
    }

    /** Puts every source back exactly as apply() found it: set, empty or absent. */
    public static function restore(array $snapshot): void
    {
        foreach ($snapshot as $key => $before) {
            if ($before['server'] === null) {
                unset($_SERVER[$key]);
            } else {
                $_SERVER[$key] = $before['server'];
            }
            if ($before['env'] === null) {
                unset($_ENV[$key]);
            } else {
                $_ENV[$key] = $before['env'];
            }
            putenv($before['process'] === false ? $key : "{$key}={$before['process']}");
        }
    }

    /**
     * Neutralises, then checks before anything boots: every target neutralised in all three sources, and no
     * cached configuration (it would ignore every variable). On a refusal the environment is restored first and
     * nothing has booted. The caller restores the returned snapshot after bootstrap.
     *
     * @param array<string, string>|null $variables only to prove the check: the check always compares against variables()
     * @throws RuntimeException naming keys, never values
     */
    public static function enter(string $configCachePath, ?array $variables = null): array
    {
        $snapshot = self::apply($variables ?? self::variables());
        $unsafe = self::unsafeTargets($_SERVER, $_ENV, getenv());
        $cached = DatabaseTargetRules::configCacheProblem($configCachePath, getenv()) !== null;
        if ($unsafe === [] && !$cached) {
            return $snapshot;
        }
        self::restore($snapshot);

        throw new RuntimeException('PBDIGIT-283: refusing to boot Laravel for a database-free test; nothing was booted. '
            .($unsafe !== [] ? 'Not neutralised: '.implode(', ', $unsafe).'. ' : '')
            .($cached ? 'The configuration is cached (bootstrap/cache/config.php or APP_CONFIG_CACHE); run without a config cache.' : ''));
    }

    /**
     * The pre-boot check: every target must hold its neutralised value in all three sources Laravel reads.
     *
     * @return list<string> the keys that are missing or different in any source; empty means safe to boot
     */
    public static function unsafeTargets(array $server, array $env, array $process): array
    {
        $unsafe = [];
        foreach (self::variables() as $key => $value) {
            foreach ([$server, $env, $process] as $source) {
                if (($source[$key] ?? null) !== $value) {
                    $unsafe[] = $key;
                    break;
                }
            }
        }

        return $unsafe;
    }
}
