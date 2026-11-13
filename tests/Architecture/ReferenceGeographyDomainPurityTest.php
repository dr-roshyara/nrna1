<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-1 (hexagonal rule; D-147; D-225; D-226 §2, §5a). The Reference Geography domain:
 * - imports only itself: no Laravel, Eloquent, database, HTTP, queue, Identity, Organisation, Membership,
 *   Governance or the legacy App\Contexts\Geography;
 * - has no IO, clock or randomness (dates are received as values);
 * - declares every class final;
 * - holds no personal data: no name / e-mail / phone / address / PersonId / AccountId property.
 * The checks are pure functions over source text; planted violations prove they are not vacuous.
 */
final class ReferenceGeographyDomainPurityTest extends TestCase
{
    private const DOMAIN = 'app/Contexts/ReferenceGeography/Domain';

    private const IMPURE = '/Illuminate\\\\|Laravel\\\\|Eloquent|App\\\\Models\\\\|\\\\Infrastructure\\\\|DB::|Cache::|Log::|Queue::|Http\\\\|Carbon|new\s+\\\\?DateTime(?:Immutable)?\b|\b(?:now|time|date|rand|mt_rand|random_int|random_bytes|uniqid|microtime|file_get_contents|file_put_contents|fopen|curl_init)\s*\(/';

    private const PERSONAL = '/\$(?:name|firstName|lastName|email|eMail|phone|address|street|personId|accountId)\b/i';

    /** @return array<string, string> */
    private static function sources(): array
    {
        $root = dirname(__DIR__, 2) . '/' . self::DOMAIN;
        $out = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if ($file->getExtension() === 'php') {
                $code = '';
                foreach (token_get_all((string) file_get_contents($file->getPathname())) as $t) {
                    if (!(is_array($t) && in_array($t[0], [T_COMMENT, T_DOC_COMMENT], true))) {
                        $code .= is_array($t) ? $t[1] : $t;
                    }
                }
                $out[substr($file->getPathname(), strlen($root) + 1)] = $code;
            }
        }
        ksort($out);

        return $out;
    }

    /** @return list<string> */
    private static function foreignImports(array $sources): array
    {
        $v = [];
        foreach ($sources as $f => $code) {
            preg_match_all('/^use\s+([^;\s]+)/m', $code, $m);
            foreach ($m[1] as $import) {
                if (!str_starts_with($import, 'App\\Contexts\\ReferenceGeography\\Domain\\')) {
                    $v[] = "{$f}: {$import}";
                }
            }
        }

        return $v;
    }

    /** @return list<string> */
    private static function findings(array $sources, string $pattern): array
    {
        $v = [];
        foreach ($sources as $f => $code) {
            if (preg_match($pattern, $code, $m)) {
                $v[] = "{$f}: {$m[0]}";
            }
        }

        return $v;
    }

    /** @return list<string> */
    private static function nonFinal(array $sources): array
    {
        $v = [];
        foreach ($sources as $f => $code) {
            if (preg_match('/^\s*(?:abstract\s+|readonly\s+)*class\s+\w+/m', $code) === 1 && preg_match('/^\s*final\s+(?:readonly\s+)?class\s+\w+/m', $code) !== 1) {
                $v[] = $f;
            }
        }

        return $v;
    }

    public function test_the_real_domain_is_scanned(): void
    {
        $this->assertArrayHasKey('SlotKey.php', self::sources());
    }

    public function test_the_domain_imports_only_itself(): void
    {
        $this->assertSame([], self::foreignImports(self::sources()));
    }

    public function test_the_domain_has_no_framework_io_clock_or_randomness(): void
    {
        $this->assertSame([], self::findings(self::sources(), self::IMPURE));
    }

    public function test_the_domain_holds_no_personal_data(): void
    {
        $this->assertSame([], self::findings(self::sources(), self::PERSONAL));
    }

    public function test_every_domain_class_is_final(): void
    {
        $this->assertSame([], self::nonFinal(self::sources()));
    }

    public function test_the_checks_catch_planted_violations(): void
    {
        $planted = [
            'A.php' => "<?php\nuse App\\Contexts\\Geography\\Domain\\Models\\GeoAdministrativeUnit;\nfinal class A {}\n",
            'B.php' => "<?php\nuse App\\Contexts\\Organisation\\Domain\\Unit;\nfinal class B {}\n",
            'C.php' => "<?php\nfinal class C { public function f() { return new \\DateTimeImmutable(); } }\n",
            'D.php' => "<?php\nfinal class D { public function __construct(public string \$email) {} }\n",
            'E.php' => "<?php\nclass E {}\n",
        ];

        $this->assertSame(['A.php: App\\Contexts\\Geography\\Domain\\Models\\GeoAdministrativeUnit', 'B.php: App\\Contexts\\Organisation\\Domain\\Unit'], self::foreignImports(['A.php' => $planted['A.php'], 'B.php' => $planted['B.php']]));
        $this->assertSame(['C.php: new \\DateTimeImmutable'], self::findings(['C.php' => $planted['C.php']], self::IMPURE));
        $this->assertSame(['D.php: $email'], self::findings(['D.php' => $planted['D.php']], self::PERSONAL));
        $this->assertSame(['E.php'], self::nonFinal(['E.php' => $planted['E.php']]));
    }
}
