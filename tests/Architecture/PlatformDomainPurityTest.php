<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 1 (hexagonal rule: Domain is pure PHP; Rule 10: domain classes final). The Platform domain:
 * - imports only itself and the shared TenantId;
 * - has no framework, persistence, HTTP, queue, filesystem, clock or randomness. A date may be RECEIVED as a value
 *   (`\DateTimeImmutable` type), never created or read from a clock;
 * - declares every class final (enums are final by nature).
 *
 * The checks are pure functions over source text, so test_the_checks_catch_planted_violations proves they are not
 * vacuous without touching production code.
 */
final class PlatformDomainPurityTest extends TestCase
{
    private const DOMAIN = 'app/Contexts/Platform/Domain';

    private const ALLOWED_IMPORTS = [
        'App\\Contexts\\Platform\\Domain\\',
        'App\\Contexts\\Shared\\Domain\\ValueObjects\\TenantId',
    ];

    private const IMPURE = '/Illuminate\\\\|Laravel\\\\|Eloquent|App\\\\Models\\\\|\\\\Infrastructure\\\\|\\\\Http\\\\|DB::|Cache::|Log::|Queue::|Bus::|Storage::|Carbon|new\s+\\\\?DateTime(?:Immutable)?\b|\b(?:now|time|date|rand|mt_rand|random_int|uniqid|microtime|file_get_contents|file_put_contents|fopen|curl_init)\s*\(/';

    /** @return array<string, string> relative path => code without comments */
    private static function sources(): array
    {
        $root = dirname(__DIR__, 2) . '/' . self::DOMAIN;
        $out = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if ($file->getExtension() === 'php') {
                $out[substr($file->getPathname(), strlen($root) + 1)] = self::withoutComments((string) file_get_contents($file->getPathname()));
            }
        }
        ksort($out);

        return $out;
    }

    private static function withoutComments(string $source): string
    {
        $code = '';
        foreach (token_get_all($source) as $token) {
            if (!(is_array($token) && in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true))) {
                $code .= is_array($token) ? $token[1] : $token;
            }
        }

        return $code;
    }

    /** @param array<string, string> $sources @return list<string> */
    private static function importViolations(array $sources): array
    {
        $violations = [];
        foreach ($sources as $file => $code) {
            preg_match_all('/^use\s+([^;]+);/m', $code, $m);
            foreach ($m[1] as $import) {
                $ok = false;
                foreach (self::ALLOWED_IMPORTS as $prefix) {
                    $ok = $ok || str_starts_with($import, $prefix);
                }
                if (!$ok) {
                    $violations[] = "{$file}: use {$import}";
                }
            }
        }

        return $violations;
    }

    /** @param array<string, string> $sources @return list<string> */
    private static function impurityViolations(array $sources): array
    {
        $violations = [];
        foreach ($sources as $file => $code) {
            if (preg_match(self::IMPURE, $code, $m)) {
                $violations[] = "{$file}: {$m[0]}";
            }
        }

        return $violations;
    }

    /** @param array<string, string> $sources @return list<string> */
    private static function nonFinalClasses(array $sources): array
    {
        $violations = [];
        foreach ($sources as $file => $code) {
            if (preg_match('/^\s*(?:abstract\s+|readonly\s+)*class\s+\w+/m', $code) === 1 && preg_match('/^\s*final\s+(?:readonly\s+)?class\s+\w+/m', $code) !== 1) {
                $violations[] = $file;
            }
        }

        return $violations;
    }

    public function test_the_domain_exists_and_is_scanned(): void
    {
        $this->assertArrayHasKey('ServedOrganisation.php', self::sources(), 'the guard must scan the real domain');
    }

    public function test_the_domain_imports_only_itself_and_the_shared_tenant_id(): void
    {
        $this->assertSame([], self::importViolations(self::sources()));
    }

    public function test_the_domain_has_no_framework_persistence_io_clock_or_randomness(): void
    {
        $this->assertSame([], self::impurityViolations(self::sources()));
    }

    public function test_every_domain_class_is_final(): void
    {
        $this->assertSame([], self::nonFinalClasses(self::sources()));
    }

    public function test_the_checks_catch_planted_violations(): void
    {
        $planted = [
            'A.php' => "<?php\nnamespace App\\Contexts\\Platform\\Domain;\nuse Illuminate\\Support\\Facades\\DB;\nfinal class A {}\n",
            'B.php' => "<?php\nnamespace App\\Contexts\\Platform\\Domain;\nuse App\\Contexts\\Platform\\Infrastructure\\Registry;\nfinal class B {}\n",
            'C.php' => "<?php\nfinal class C { public function f() { return new \\DateTimeImmutable(); } }\n",
            'D.php' => "<?php\nfinal class D { public function f() { return DB::table('x'); } }\n",
            'E.php' => "<?php\nclass E {}\n",
            'F.php' => "<?php\nfinal readonly class F { public function __construct(public \\DateTimeImmutable \$at) {} }\n",
        ];

        $this->assertSame(['A.php: use Illuminate\\Support\\Facades\\DB', 'B.php: use App\\Contexts\\Platform\\Infrastructure\\Registry'], self::importViolations($planted));
        $impure = self::impurityViolations($planted);
        $this->assertContains('C.php: new \\DateTimeImmutable', $impure, 'creating a date reads the clock');
        $this->assertContains('D.php: DB::', $impure);
        $this->assertNotContains('F.php', array_map(static fn (string $v): string => explode(':', $v)[0], $impure), 'receiving a date as a value is allowed');
        $this->assertSame(['E.php'], self::nonFinalClasses($planted));
    }
}
