<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 4 (tactical design §3.5 "two migration sets"; M-5; T-10 part 2). Database-free.
 * - The ORGANISATION set lives in database/migrations/organisation/ and runs only on organisation databases (through
 *   provisioning). Laravel's migrator globs `<path>/*_*.php`, never subdirectories, so the central `migrate` cannot
 *   pick it up; every file belongs to exactly one set.
 * - An organisation migration references only tables the organisation set itself creates (no central table: no
 *   `organisations`, no `users`), and never names a connection (it runs on the one it is given).
 * - The reverse: no central migration references an organisation-only table (organisation_identity).
 * The same checker is run against planted rogue migrations, so it is proven to catch each violation.
 */
final class OrganisationMigrationSetTest extends TestCase
{
    private const ORG_ONLY_TABLES = ['organisation_identity'];

    private static function root(): string
    {
        return dirname(__DIR__, 2);
    }

    /** @return list<string> every file of the organisation set: all paths of config organisation_routing.migration_paths */
    private static function organisationFiles(): array
    {
        $paths = (require self::root() . '/config/organisation_routing.php')['migration_paths'];
        $files = [];
        foreach ($paths as $p) {
            $files = [...$files, ...(glob("{$p}/*_*.php") ?: [])];
        }

        return $files;
    }

    /** @return list<string> files the central migrator sees (as Migrator::getMigrationFiles: one glob, no recursion) */
    private static function centralFiles(string $dir, string $ext = '.php'): array
    {
        return glob("{$dir}/*_*{$ext}") ?: [];
    }

    /** The source without comments (as the architecture ratchet scans), so prose is never read as SQL; heredoc SQL stays. */
    private static function code(string $source): string
    {
        $out = '';
        foreach (token_get_all($source) as $token) {
            $out .= is_array($token) ? (in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true) ? ' ' : $token[1]) : $token;
        }

        return $out;
    }

    /** @return list<string> the table names a migration's source references */
    private static function referencedTables(string $source): array
    {
        $source = self::code($source);
        $patterns = [
            "/Schema::(?:create|table|drop|dropIfExists|rename)\(\s*'([a-z_][a-z0-9_]*)'/i",
            "/->on\(\s*'([a-z_][a-z0-9_]*)'/i",
            '/\b(?:CREATE\s+TABLE(?:\s+IF\s+NOT\s+EXISTS)?|ALTER\s+TABLE|DROP\s+TABLE(?:\s+IF\s+EXISTS)?|INSERT\s+INTO|DELETE\s+FROM|UPDATE|TRUNCATE|REFERENCES|FROM|JOIN)\s+([a-z_][a-z0-9_]*)/i',
            '/\bON\s+([a-z_][a-z0-9_]*)\s+(?:FOR\s+EACH|USING|\()/i',
        ];
        $found = [];
        foreach ($patterns as $p) {
            preg_match_all($p, $source, $m);
            $found = [...$found, ...$m[1]];
        }
        // SQL keywords the patterns can meet in place of a table name (e.g. "BEFORE UPDATE OR DELETE ON t", "UPDATE OF col",
        // task 5b: "INSERT ... ON CONFLICT (col) DO NOTHING"; the INSERT INTO pattern still sees the inserted table)
        $ignore = ['select', 'public', 'only', 'each', 'row', 'set', 'where', 'not', 'exists', 'or', 'of', 'conflict'];

        return array_values(array_unique(array_filter(array_map('strtolower', $found), static fn (string $t) => !in_array($t, $ignore, true))));
    }

    /** @return list<string> the tables the set creates */
    private static function createdTables(array $sources): array
    {
        $created = [];
        foreach ($sources as $src) {
            preg_match_all("/Schema::create\(\s*'([a-z_][a-z0-9_]*)'|CREATE\s+TABLE(?:\s+IF\s+NOT\s+EXISTS)?\s+([a-z_][a-z0-9_]*)/i", $src, $m);
            $created = [...$created, ...array_filter([...$m[1], ...$m[2]])];
        }

        return array_values(array_unique(array_map('strtolower', $created)));
    }

    /**
     * @param list<string> $files organisation migration files
     * @return list<string> "file: problem" for every violation
     */
    public static function organisationViolations(array $files): array
    {
        $sources = array_map(static fn (string $f) => (string) file_get_contents($f), $files);
        $own = self::createdTables($sources);
        $out = [];
        foreach ($files as $i => $file) {
            $name = basename($file);
            foreach (self::referencedTables($sources[$i]) as $table) {
                if (!in_array($table, $own, true)) {
                    $out[] = "{$name}: references '{$table}', which the organisation set does not create";
                }
            }
            if (preg_match('/(?:->|::)connection\(|\bprotected\s+\$connection\b/', self::code($sources[$i])) === 1) {   // DB::, Schema:: and ->connection(
                $out[] = "{$name}: names a connection";
            }
        }
        sort($out);

        return $out;
    }

    /** @param list<string> $files central migration files @return list<string> */
    public static function centralViolations(array $files): array
    {
        $out = [];
        foreach ($files as $file) {
            foreach (array_intersect(self::referencedTables((string) file_get_contents($file)), self::ORG_ONLY_TABLES) as $table) {
                $out[] = basename($file) . ": references the organisation-only table '{$table}'";
            }
        }

        return $out;
    }

    public function test_the_organisation_set_exists_and_the_central_migrator_never_sees_it(): void
    {
        $org = self::organisationFiles();
        $this->assertGreaterThanOrEqual(2, count($org), "the organisation set: Platform's organisation_identity and Membership's ledger");
        $central = array_map('realpath', self::centralFiles(self::root() . '/database/migrations'));
        foreach ($org as $file) {
            $this->assertNotContains(realpath($file), $central, basename($file) . ' must belong to the organisation set only');
        }
    }

    public function test_t10_organisation_migrations_reference_only_organisation_tables_and_no_connection(): void
    {
        $this->assertSame([], self::organisationViolations(self::organisationFiles()));
    }

    public function test_no_central_migration_references_an_organisation_only_table(): void
    {
        $this->assertSame([], self::centralViolations(self::centralFiles(self::root() . '/database/migrations')));
    }

    public function test_the_checker_catches_a_planted_rogue_migration_in_each_direction(): void
    {
        $fixtures = dirname(__DIR__) . '/Fixtures/OrganisationMigrationSet';
        $this->assertSame([
            "2099_01_01_000000_rogue_org_migration.php.fixture: names a connection",
            "2099_01_01_000000_rogue_org_migration.php.fixture: references 'organisations', which the organisation set does not create",
            "2099_01_01_000000_rogue_org_migration.php.fixture: references 'users', which the organisation set does not create",
        ], self::organisationViolations(glob("{$fixtures}/organisation/*.fixture") ?: []));
        $this->assertSame(["2099_01_01_000000_rogue_central_migration.php.fixture: references the organisation-only table 'organisation_identity'"],
            self::centralViolations(glob("{$fixtures}/central/*.fixture") ?: []));
    }
}
