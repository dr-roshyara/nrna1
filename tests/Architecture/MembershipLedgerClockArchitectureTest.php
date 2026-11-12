<?php

declare(strict_types=1);

namespace Tests\Architecture;

use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockReader;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use Illuminate\Database\Connection;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-343 task 3 (plan 20261112-1251 D-1; D-209; proof LC-5): one database clock.
 * - The repository's record time comes from a LedgerClockReader, never from an application-clock closure.
 * - In production code the only reader is the PostgreSQL clock (clock_timestamp()); scripted readers exist
 *   only under tests/.
 * - The ledger clock migration is PostgreSQL-only (DB-1).
 */
final class MembershipLedgerClockArchitectureTest extends TestCase
{
    private const APP = 'app';
    private const MIGRATION = 'database/migrations/2026_11_12_130000_create_org_membership_ledger_clocks_table.php';

    private static function codeWithoutComments(string $path): string
    {
        $code = '';
        foreach (token_get_all((string) file_get_contents($path)) as $t) {
            if (!(is_array($t) && in_array($t[0], [T_COMMENT, T_DOC_COMMENT], true))) {
                $code .= is_array($t) ? $t[1] : $t;
            }
        }

        return $code;
    }

    public function test_the_repository_takes_a_ledger_clock_reader_and_no_closure(): void
    {
        $parameters = (new \ReflectionClass(PostgresMemberRepository::class))->getConstructor()?->getParameters() ?? [];
        $types = array_map(static fn (\ReflectionParameter $p): string => (string) $p->getType(), $parameters);

        $this->assertSame([Connection::class, LedgerClockReader::class], $types);
    }

    public function test_the_only_ledger_clock_reader_in_production_code_is_the_postgres_clock(): void
    {
        $root = dirname(__DIR__, 2) . '/' . self::APP;
        $implementers = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)) as $file) {
            $path = (string) $file;
            if (str_ends_with($path, '.php') && preg_match('/implements\s+[^{]*\bLedgerClockReader\b/', self::codeWithoutComments($path)) === 1) {
                $implementers[] = basename($path);
            }
        }

        $this->assertSame(['PostgresLedgerClockReader.php'], $implementers);
    }

    public function test_the_postgres_reader_reads_clock_timestamp(): void
    {
        $code = self::codeWithoutComments(dirname(__DIR__, 2) . '/app/Contexts/Membership/Infrastructure/Organisational/PostgresLedgerClockReader.php');

        $this->assertStringContainsString('clock_timestamp()', $code);
        $this->assertStringNotContainsString('microtime', $code);
        $this->assertStringNotContainsString('now()', $code);
    }

    public function test_the_ledger_clock_migration_is_postgres_only(): void
    {
        $code = self::codeWithoutComments(dirname(__DIR__, 2) . '/' . self::MIGRATION);

        $this->assertStringNotContainsString('getDriverName', $code);
        $this->assertStringNotContainsString('sqlite', strtolower($code));
    }

    /** PBDIGIT-343 task 8: C2 and the Published Language stay framework-free and never reach Infrastructure. */
    public function test_c2_and_the_published_language_are_pure(): void
    {
        $root = dirname(__DIR__, 2);
        foreach ([...glob("$root/app/Contexts/Membership/Application/PublishedLanguage/*.php") ?: [], "$root/app/Contexts/Membership/Application/Organisational/CoverageKnownAt.php"] as $file) {
            $code = self::codeWithoutComments($file);
            $this->assertStringNotContainsString('Illuminate\\', $code, basename($file));
            $this->assertStringNotContainsString('\\Infrastructure\\', $code, basename($file));
        }
    }
}
