<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Support\ConnectionReferences;
use Tests\Support\DatabaseTargetRules;

/**
 * PBDIGIT-283 R5: no test reset can reach the audit or geo database.
 *
 * RefreshDatabase runs `migrate:fresh` without `--database`, so `db:wipe` drops tables on the DEFAULT connection only
 * (vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/FreshCommand.php, WipeCommand.php). The
 * properties that keep it that way:
 * - no migration, in any loaded migration path, names a connection (so every table lives on the default database);
 * - every configured connection that application code names is a confined one, checked before boot by R3
 *   (DatabaseTargetRules::CONNECTIONS); names that aren't configured can't connect at all.
 *
 * Database-free: source files are read, never run.
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/NoReachIntoAuditOrGeoTest.php
 */
final class NoReachIntoAuditOrGeoTest extends TestCase
{
    private static function root(): string
    {
        return dirname(__DIR__, 2);
    }

    /** @return iterable<string, string> relative path => source of every PHP file under $dir */
    private static function sources(string $dir): iterable
    {
        if (! is_dir(self::root().'/'.$dir)) {
            return;
        }
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(self::root().'/'.$dir, \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                yield substr($file->getPathname(), strlen(self::root()) + 1) => (string) file_get_contents($file->getPathname());
            }
        }
    }

    // ── the detector ────────────────────────────────────────────────────────

    public static function snippets(): array
    {
        return [
            'Schema::connection literal'  => ["Schema::connection('pgsql_audit')->create('x', fn () => null);", ['pgsql_audit'], false],
            'DB::connection double quote' => ["DB::connection(\"pgsql_geo\")->table('x')->get();", ['pgsql_geo'], false],
            'model property'              => ["protected \$connection = 'pgsql_audit';", ['pgsql_audit'], false],
            'typed property'              => ["private string \$connection = 'tenant';", ['tenant'], false],
            'getConnection override'      => ["public function getConnection(): ?string { return 'pgsql_geo'; }", [], true],
            'default connection'          => ["DB::connection()->getDriverName();", [], false],
            'variable connection'         => ["Schema::connection(\$db->getName())->table('x', fn () => null);", [], false],
            'line comment'                => ["// Schema::connection('pgsql_audit')", [], false],
            'doc comment'                 => ["/** protected \$connection = 'pgsql_geo'; */", [], false],
        ];
    }

    #[DataProvider('snippets')]
    public function test_the_detector_finds_named_connections_outside_comments(string $snippet, array $names, bool $overrides): void
    {
        $source = "<?php\n".$snippet."\n";

        $this->assertSame($names, ConnectionReferences::named($source));
        $this->assertSame($overrides, ConnectionReferences::overridesGetConnection($source));
    }

    // ── migrations stay on the default connection ──────────────────────────

    /** Every migration root the application loads, as a path fragment its loadMigrationsFrom() argument contains. */
    private const MIGRATION_ROOTS = ['database/migrations', 'app/Contexts'];

    public function test_every_loaded_migration_path_is_scanned(): void
    {
        $calls = 0;
        foreach (self::sources('app') as $path => $source) {
            preg_match_all('/loadMigrationsFrom\(([^;]*)\);/', ConnectionReferences::withoutComments($source), $m);
            foreach ($m[1] as $argument) {
                $calls++;
                $this->assertMatchesRegularExpression(
                    '#Database/Migrations|database_path\(\s*[\'"]migrations#',
                    $argument,
                    "{$path} loads migrations from a path this test does not scan: {$argument}",
                );
                $this->assertStringNotContainsStringIgnoringCase('backup', $argument, "{$path} loads a backup migration folder");
            }
        }

        $this->assertGreaterThanOrEqual(9, $calls, 'the scan found the loadMigrationsFrom() calls');
    }

    public function test_no_migration_names_a_connection(): void
    {
        $migrations = 0;
        $named = [];
        foreach (self::MIGRATION_ROOTS as $root) {
            foreach (self::sources($root) as $path => $source) {
                if ($root === 'app/Contexts' && ! str_contains($path, '/Database/Migrations/')) {
                    continue;
                }
                $migrations++;
                if (ConnectionReferences::named($source) !== [] || ConnectionReferences::overridesGetConnection($source)) {
                    $named[] = $path;
                }
            }
        }

        // 2026-10-14: 200 under database/migrations (incl. landlord/) + 29 under app/Contexts; backups are not loaded.
        $this->assertGreaterThanOrEqual(229, $migrations, 'the scan found the migrations');
        $this->assertSame([], $named, 'a migration names a connection, so migrate:fresh on the default connection would not reset it');
    }

    // ── application code reaches only confined connections ────────────────

    public function test_every_configured_connection_named_by_application_code_is_confined(): void
    {
        $configured = ConnectionReferences::configuredConnections((string) file_get_contents(self::root().'/config/database.php'));
        $unconfined = [];
        foreach (self::sources('app') as $path => $source) {
            foreach (ConnectionReferences::named($source) as $name) {
                if (in_array($name, $configured, true) && ! in_array($name, DatabaseTargetRules::CONNECTIONS, true)) {
                    $unconfined[] = "{$path}: {$name}";
                }
            }
        }

        $this->assertContains('pgsql_audit', $configured, 'the scan read the configured connections');
        $this->assertSame([], $unconfined, 'application code names a configured connection that the pre-provider check (R3) does not confine');
    }
}
