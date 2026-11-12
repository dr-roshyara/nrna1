<?php

declare(strict_types=1);

namespace Tests\Support;

use Illuminate\Support\Facades\DB;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Isolated\Pbdigit274\Support\IsolatedDatabaseRefused;

/**
 * PBDIGIT-345 task 0 (slice 2 plan §6): TEST-ONLY named connections to the four databases that
 * scripts/testing/membership-pg.sh creates in its throwaway cluster: one central and three organisation databases.
 *
 * - It reuses the PBDIGIT-274 / PBDIGIT-324 identity guard; it is not a second guard.
 * - It accepts only the four fixed names, in order. Each connection must report its own current_database() and the
 *   system_identifier captured at container start; otherwise nothing stays registered.
 * - It never touches Laravel's default connection and is never used by production code. Slice 2's real routing
 *   (OrganisationConnectionFactory, delta §3.3) comes in later tasks and must not depend on this class.
 */
final class PlatformPgIsolatedDatabases
{
    /** connection name => database name (the order the script exports) */
    public const CONNECTIONS = [
        'platform_central' => 'platform_pg_central',
        'platform_org_a' => 'platform_pg_org_a',
        'platform_org_b' => 'platform_pg_org_b',
        'platform_org_c' => 'platform_pg_org_c',
    ];

    public const ENV_LIST = 'PLATFORM_PG_DATABASES';

    /**
     * True only inside scripts/testing/membership-pg.sh, which captures the cluster identity. Outside it (the default
     * phpunit.xml) the harness tests skip; inside it, anything missing fails, never skips.
     *
     * @param array<string, string> $env
     */
    public static function isIsolatedRun(array $env): bool
    {
        return trim((string) ($env[MembershipPgIsolatedDatabase::SYSTEM_ID_ENV] ?? '')) !== '';
    }

    public const SKIP_REASON = 'runs only on the throwaway cluster of scripts/testing/membership-pg.sh (PBDIGIT-345 task 0)';

    /** @param array<string, string> $env */
    public static function register(array $env): void
    {
        $listed = $env[self::ENV_LIST] ?? null;
        if (!is_string($listed) || explode(',', $listed) !== array_values(self::CONNECTIONS)) {
            throw new IsolatedDatabaseRefused('platform_databases', self::ENV_LIST.' must list exactly '.implode(',', self::CONNECTIONS).'.');
        }
        $settings = DatabaseIdentityGuard::connectionSettings($env, MembershipPgIsolatedDatabase::CREDENTIAL_PREFIX);
        $expectedSystemId = $env[MembershipPgIsolatedDatabase::SYSTEM_ID_ENV] ?? null;

        $registered = [];
        try {
            foreach (self::CONNECTIONS as $connection => $database) {
                DB::purge($connection);
                config(["database.connections.{$connection}" => self::connectionConfig($database, $settings)]);
                $registered[] = $connection;
                DatabaseIdentityGuard::verifyIdentity($database, static fn () => DB::connection($connection)->selectOne('SELECT current_database() AS n')->n);
                DatabaseIdentityGuard::verifySystemIdentifier($expectedSystemId, static fn () => DB::connection($connection)->selectOne('SELECT system_identifier::text AS id FROM pg_control_system()')->id);
            }
        } catch (IsolatedDatabaseRefused $e) {
            foreach ($registered as $connection) {
                DB::purge($connection);
                config(["database.connections.{$connection}" => null]);
            }
            throw $e;
        }
    }

    /** Empties all four databases (schema public dropped and recreated). Re-checks each identity first. */
    public static function reset(): void
    {
        foreach (self::CONNECTIONS as $connection => $database) {
            $c = DB::connection($connection);
            DatabaseIdentityGuard::verifyIdentity($database, static fn () => $c->selectOne('SELECT current_database() AS n')->n);
            $c->statement('DROP SCHEMA public CASCADE');
            $c->statement('CREATE SCHEMA public');
        }
    }

    /** @param array{host: string, port: string, username: string, password: string} $settings */
    private static function connectionConfig(string $database, array $settings): array
    {
        return [
            'driver' => 'pgsql',
            'host' => $settings['host'],
            'port' => $settings['port'],
            'database' => $database,
            'username' => $settings['username'],
            'password' => $settings['password'],
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ];
    }
}
