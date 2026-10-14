<?php

declare(strict_types=1);

namespace Tests\Architecture;

use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;
use Tests\Isolated\Pbdigit274\IsolatedDatabaseTestCase;
use Tests\Support\DatabaseFreeTest;

/**
 * PBDIGIT-283 R4: every test that boots Laravel goes through Tests\CreatesApplication, which holds the database-free
 * marker (R1) and the pre-provider configuration check (R3), and every Laravel test case has a cover:
 * Tests\TestCase, an isolated base, or the DatabaseFreeTest marker. A test that boots Laravel some other way
 * (`require bootstrap/app.php`, its own createApplication()) bypasses both, and under `--no-configuration` boots on
 * the .env database (incident 2026-10-13).
 *
 * Database-free: source files are read and classes reflected, never run.
 * Run: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/LaravelBootIsGuardedTest.php
 */
final class LaravelBootIsGuardedTest extends TestCase
{
    private const COVERS = [\Tests\TestCase::class, IsolatedDatabaseTestCase::class, IsolatedCommittedDatabaseTestCase::class];

    /**
     * Files allowed to require bootstrap/app.php, each with the reason it is safe.
     * g6-worker.php is a separate worker process: it requires the isolated identity guard first (asserted below).
     */
    private const BOOT_SITES = [
        'tests/CreatesApplication.php',
        'tests/Isolated/Pbdigit274/Concurrency/g6-worker.php',
    ];

    private static function root(): string
    {
        return dirname(__DIR__, 2);
    }

    /** @return iterable<string, string> relative path => source of every PHP file under tests/ */
    private static function testSources(): iterable
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(self::root().'/tests', \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                yield substr($file->getPathname(), strlen(self::root()) + 1) => (string) file_get_contents($file->getPathname());
            }
        }
    }

    public static function bootstrapSnippets(): array
    {
        return [
            'assigned'                   => ["\$app = require __DIR__.'/../bootstrap/app.php';", true],
            'property'                   => ["\$this->app = require \$base.'/bootstrap/app.php';", true],
            'static property'            => ["static::\$app = require \$base . '/bootstrap/app.php';", true],
            'parenthesised'              => ["\$app = require(\$base.'/bootstrap/app.php');", true],
            'require_once'               => ["require_once \$base.'/bootstrap/app.php';", true],
            'include'                    => ["\$app = include \$base.'/bootstrap/app.php';", true],
            'path split across literals' => ["\$app = require \$base.'/bootstrap'.'/app.php';", true],
            'double quotes'              => ["\$app = require \"{\$base}/bootstrap/app.php\";", true],
            'line comment'               => ["// \$app = require \$base.'/bootstrap/app.php';", false],
            'doc comment'                => ["/** require bootstrap/app.php */", false],
            'string mention'             => ["\$s = \"require \\\$base.'/bootstrap/app.php'\";", false],
            'other require'              => ["require \$base.'/vendor/autoload.php';", false],
        ];
    }

    /** True if the PHP source has an executable require/include whose literal path parts name bootstrap/app.php. */
    private static function requiresLaravelBootstrap(string $source): bool
    {
        $tokens = token_get_all($source);
        $inclusions = [T_REQUIRE, T_REQUIRE_ONCE, T_INCLUDE, T_INCLUDE_ONCE];
        foreach ($tokens as $i => $token) {
            if (! is_array($token) || ! in_array($token[0], $inclusions, true)) {
                continue;
            }
            $path = '';
            for ($j = $i + 1; isset($tokens[$j]) && $tokens[$j] !== ';'; $j++) {
                if (is_array($tokens[$j]) && in_array($tokens[$j][0], [T_CONSTANT_ENCAPSED_STRING, T_ENCAPSED_AND_WHITESPACE], true)) {
                    $path .= trim($tokens[$j][1], '\'"');
                }
            }
            if (str_contains($path, 'bootstrap/app.php')) {
                return true;
            }
        }

        return false;
    }

    #[DataProvider('bootstrapSnippets')]
    public function test_the_detector_finds_executable_bootstraps_only(string $snippet, bool $boots): void
    {
        $this->assertSame($boots, self::requiresLaravelBootstrap("<?php\n".$snippet."\n"));
    }

    public function test_only_create_application_requires_the_laravel_bootstrap(): void
    {
        $sites = [];
        foreach (self::testSources() as $path => $source) {
            if (self::requiresLaravelBootstrap($source)) {
                $sites[] = $path;
            }
        }
        sort($sites);

        $this->assertSame(self::BOOT_SITES, $sites, 'a test boots Laravel without Tests\CreatesApplication (no marker, no pre-provider check)');
    }

    public function test_the_worker_confirms_the_isolated_database_before_it_boots(): void
    {
        $source = (string) file_get_contents(self::root().'/tests/Isolated/Pbdigit274/Concurrency/g6-worker.php');
        $guard = strpos($source, "require \$base.'/tests/bootstrap-isolated-database.php'");
        $boot = strpos($source, "require \$base.'/bootstrap/app.php'");

        $this->assertNotFalse($guard, 'the worker must require the isolated identity guard');
        $this->assertNotFalse($boot);
        $this->assertLessThan($boot, $guard, 'the identity guard must run before Laravel boots');
    }

    /** @return list<class-string<LaravelTestCase>> every Laravel test case declared under tests/ */
    private static function laravelTestCases(): array
    {
        $classes = [];
        foreach (self::testSources() as $source) {
            if (! preg_match('/^namespace\s+([^;]+);/m', $source, $ns)
                || ! preg_match('/^(?:final\s+|abstract\s+|readonly\s+)*class\s+(\w+)/m', $source, $cls)) {
                continue;
            }
            $class = $ns[1].'\\'.$cls[1];
            try {
                if (class_exists($class) && is_subclass_of($class, LaravelTestCase::class)) {
                    $classes[] = $class;
                }
            } catch (\Throwable) {
                continue;   // a file that cannot be loaded cannot run either
            }
        }

        return $classes;
    }

    public function test_every_laravel_test_case_creates_its_application_through_the_guarded_trait(): void
    {
        $expected = realpath(self::root().'/tests/CreatesApplication.php');
        $bypassing = [];
        foreach (self::laravelTestCases() as $class) {
            if ((new \ReflectionMethod($class, 'createApplication'))->getFileName() !== $expected) {
                $bypassing[] = $class;
            }
        }

        $this->assertSame([], $bypassing, 'a Laravel test case creates its application without Tests\CreatesApplication');
    }

    public function test_every_laravel_test_case_has_a_cover(): void
    {
        $classes = self::laravelTestCases();
        $uncovered = [];
        foreach ($classes as $class) {
            $covered = is_subclass_of($class, DatabaseFreeTest::class) || in_array($class, self::COVERS, true);
            foreach (self::COVERS as $cover) {
                $covered = $covered || is_subclass_of($class, $cover);
            }
            if (! $covered) {
                $uncovered[] = $class;
            }
        }

        $this->assertGreaterThan(100, count($classes), 'the scan found the Laravel test cases');
        $this->assertSame([], $uncovered, 'a Laravel test case has no cover: extend Tests\TestCase or an isolated base, or implement DatabaseFreeTest');
    }

    /**
     * Anonymous classes can't be reflected by name, so their boot sites are checked in the source: an anonymous class
     * using CreatesApplication must implement DatabaseFreeTest, except the one deliberately unmarked fixture that
     * proves the R3 pre-provider check on its own (it boots only against non-existent sockets).
     */
    public function test_every_anonymous_boot_site_is_marked_or_a_known_unmarked_fixture(): void
    {
        $unmarked = [];
        $marked = 0;
        foreach (self::testSources() as $path => $source) {
            preg_match_all('/new\s+class\b([^{]*)\{\s*use\s+(?:\\\\?Tests\\\\)?CreatesApplication\s*;/', $source, $sites);
            foreach ($sites[1] as $header) {
                preg_match('/\bimplements\b[^{]*\bDatabaseFreeTest\b/', $header) === 1 ? $marked++ : $unmarked[] = $path;
            }
        }

        $this->assertGreaterThanOrEqual(5, $marked, 'the scan found the marked anonymous boot sites');
        $this->assertSame(['tests/Unit/Testing/PreProviderHookTest.php'], $unmarked, 'an anonymous class boots Laravel without the DatabaseFreeTest marker');
    }
}
