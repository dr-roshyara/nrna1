<?php

declare(strict_types=1);

namespace Tests\Unit\Testing;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-283 R2, decision D-283-1, F2: the default suites pin EVERY PostgreSQL target to the test database, not
 * only `pgsql`. `pgsql_audit` and `pgsql_geo` read their own DB_AUDIT_* / DB_PGSQL_GEO_* variables (the geo
 * default is `publicdigit`), and DATABASE_URL overrides a connection's target. So all of them are pinned, and
 * DATABASE_URL is set and empty. Targeting only (host, port, database, username); passwords stay unpinned
 * (PBDIGIT-00: no database password in a tracked file).
 *
 * Database-free: the bootstrap runs in a separate PHP process that only sets and prints variables (no Laravel,
 * no connection); the XML files are parsed.
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Testing/DefaultSuitePinningTest.php
 */
final class DefaultSuitePinningTest extends TestCase
{
    private const ROOT = __DIR__.'/../../..';

    /** The variables a pinned suite must set, as "suffix => which DB_* value it mirrors" per connection prefix. */
    private const MIRRORED = ['HOST' => 'DB_HOST', 'PORT' => 'DB_PORT', 'USERNAME' => 'DB_USERNAME'];

    private const PREFIXES = ['DB_AUDIT_', 'DB_PGSQL_GEO_'];

    /** @return array<string, string> the <server> entries of a PHPUnit configuration */
    private static function serverEntries(string $file): array
    {
        $xml = simplexml_load_file(self::ROOT.'/'.$file);
        $entries = [];
        foreach ($xml->php->server ?? [] as $server) {
            $entries[(string) $server['name']] = (string) $server['value'];
        }

        return $entries;
    }

    /** @return array<string, string> what a pinned suite must set, mirrored from its own DB_* values */
    private static function expectedPins(array $entries): array
    {
        $pins = ['DATABASE_URL' => ''];
        foreach (self::PREFIXES as $prefix) {
            $pins[$prefix.'DATABASE'] = 'nrna_test';
            foreach (self::MIRRORED as $suffix => $source) {
                $pins[$prefix.$suffix] = $entries[$source];
            }
        }

        return $pins;
    }

    public static function configurations(): array
    {
        return ['phpunit.xml' => ['phpunit.xml'], 'phpunit-pgsql.xml' => ['phpunit-pgsql.xml']];
    }

    #[DataProvider('configurations')]
    public function test_the_configuration_pins_every_target_to_the_test_database(string $file): void
    {
        $entries = self::serverEntries($file);

        $this->assertSame('nrna_test', $entries['DB_DATABASE'] ?? null, "{$file}: the default connection");
        foreach (self::expectedPins($entries) as $key => $value) {
            $this->assertArrayHasKey($key, $entries, "{$file} does not pin {$key}");
            $this->assertSame($value, $entries[$key], "{$file}: {$key}");
        }
    }

    public function test_the_bootstrap_pins_every_target_whatever_the_environment_says(): void
    {
        $expected = self::expectedPins(self::serverEntries('phpunit.xml')) + ['DB_DATABASE' => 'nrna_test'];
        $hostile = [
            'PATH' => (string) getenv('PATH'),
            'DATABASE_URL' => 'pgsql://u:p@db.example/publicdigit',
            'DB_DATABASE' => 'publicdigit',
            'DB_AUDIT_DATABASE' => 'publicdigit', 'DB_AUDIT_HOST' => 'db.example',
            'DB_PGSQL_GEO_DATABASE' => 'publicdigit', 'DB_PGSQL_GEO_HOST' => 'db.example',
        ];
        $code = 'require '.var_export(realpath(self::ROOT.'/tests/bootstrap-test-database.php'), true).';'
            .'$out = [];'
            .'foreach ('.var_export(array_keys($expected), true).' as $k) {'
            .'  $out[$k] = [$_SERVER[$k] ?? null, $_ENV[$k] ?? null, getenv($k)];'
            .'}'
            .'echo json_encode($out);';

        $process = proc_open([PHP_BINARY, '-r', $code], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, self::ROOT, $hostile);
        $this->assertIsResource($process);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        $this->assertSame(0, proc_close($process), "bootstrap subprocess failed: {$stderr}");

        $actual = json_decode($stdout, true, flags: JSON_THROW_ON_ERROR);
        foreach ($expected as $key => $value) {
            $this->assertSame([$value, $value, $value], $actual[$key], "{$key} in \$_SERVER, \$_ENV and putenv");
        }
    }
}
