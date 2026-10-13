<?php

declare(strict_types=1);

namespace Tests\Architecture;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Tests\Isolated\Pbdigit274\IsolatedDatabaseTestCase;

/**
 * Every test that resets a database goes through a guard (incident 2026-10-13: a test run wiped the development
 * database). A class using RefreshDatabase, DatabaseMigrations or DatabaseTruncation must inherit either
 * Tests\TestCase (setUpTraits() → Tests\Support\TestDatabaseSafety) or IsolatedDatabaseTestCase (its own
 * identity guard). Database-free: classes are loaded and reflected, never run.
 */
final class DatabaseResetIsGuardedTest extends TestCase
{
    private const RESET_TRAITS = [RefreshDatabase::class, DatabaseMigrations::class, DatabaseTruncation::class];
    private const GUARDED_BASES = [\Tests\TestCase::class, IsolatedDatabaseTestCase::class];

    public function test_every_database_resetting_test_inherits_a_guarded_base(): void
    {
        $root = dirname(__DIR__, 2);
        $checked = 0;
        $unguarded = [];
        $inert = [];

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root.'/tests', \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            $source = $file->getExtension() === 'php' ? file_get_contents($file->getPathname()) : '';
            if (! preg_match('/\b(RefreshDatabase|DatabaseMigrations|DatabaseTruncation)\b/', $source)
                || ! preg_match('/^namespace\s+([^;]+);/m', $source, $ns)
                || ! preg_match('/^(?:final\s+|abstract\s+)*class\s+(\w+)/m', $source, $cls)) {
                continue;
            }
            $class = $ns[1].'\\'.$cls[1];
            if (! class_exists($class)) {
                continue;
            }
            if (! array_intersect(self::RESET_TRAITS, class_uses_recursive($class))) {
                continue;
            }
            if (! is_subclass_of($class, \Illuminate\Foundation\Testing\TestCase::class)) {
                // Plain PHPUnit never runs Laravel's setUpTraits(), so the trait is inert unless called directly.
                $this->assertDoesNotMatchRegularExpression(
                    '/->(refreshDatabase|refreshTestDatabase|runDatabaseMigrations|truncateDatabaseTables)\(/',
                    $source,
                    "{$class} calls a database reset itself without a guarded base",
                );
                $inert[] = $class;
                continue;
            }
            $checked++;
            $guarded = in_array($class, self::GUARDED_BASES, true);
            foreach (self::GUARDED_BASES as $base) {
                $guarded = $guarded || is_subclass_of($class, $base);
            }
            if (! $guarded) {
                $unguarded[] = $class;
            }
        }

        $this->assertGreaterThan(100, $checked, 'the scan found the database tests');
        $this->assertSame([], $unguarded, 'a database-resetting test bypasses the database guard');
        // Known inert uses (2026-10-13): plain PHPUnit classes that carry RefreshDatabase without ever running it.
        sort($inert);
        $this->assertSame([
            'Tests\Unit\Application\Election\Security\IpEvidenceDeterminismTest',
            'Tests\Unit\Application\Election\Security\IpEvidenceTopologyEquivalenceTest',
        ], $inert, 'a new inert reset trait appeared; give the class a guarded base or remove the trait');
    }
}
