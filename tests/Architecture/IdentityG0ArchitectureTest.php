<?php

declare(strict_types=1);

namespace Tests\Architecture;

use App\Contexts\Identity\Domain\ContextPersonRef;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-0 (D-225 IG-3, IG-P3, IG-P8; hexagonal rules):
 * - Identity's Domain and Application are pure PHP and final;
 * - no Identity port or service takes a ContextPersonRef as input (no reverse lookup);
 * - Reference Geography's Domain and Application import nothing from Identity; its Infrastructure imports only
 *   Identity's published application service (ActingContextRef and its answer, refusal and the context enum),
 *   never PersonId, AccountId, Identity Infrastructure or Identity ports.
 * The checks are pure functions over source text; planted violations prove they are not vacuous.
 */
final class IdentityG0ArchitectureTest extends TestCase
{
    private const IMPURE = '/Illuminate\\\\|Laravel\\\\|Eloquent|App\\\\Models\\\\|\\\\Infrastructure\\\\|DB::|Auth::|Cache::|Log::|\b(?:now|time|date|rand|mt_rand|random_int|random_bytes|uniqid|microtime)\s*\(/';

    private const GEOGRAPHY_MAY_IMPORT_FROM_IDENTITY = [
        'App\\Contexts\\Identity\\Application\\ActingContextRef',
        'App\\Contexts\\Identity\\Application\\ActingContextRefAnswer',
        'App\\Contexts\\Identity\\Application\\ActingContextRefRefusal',
        'App\\Contexts\\Identity\\Domain\\PlatformContext',
    ];

    /** @return array<string, string> */
    private static function sources(string $dir): array
    {
        $root = dirname(__DIR__, 2) . '/' . $dir;
        $out = [];
        if (!is_dir($root)) {
            return $out;
        }
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if ($file->getExtension() === 'php') {
                $code = '';
                foreach (token_get_all((string) file_get_contents($file->getPathname())) as $t) {
                    if (!(is_array($t) && in_array($t[0], [T_COMMENT, T_DOC_COMMENT], true))) {
                        $code .= is_array($t) ? $t[1] : $t;
                    }
                }
                $out["{$dir}/" . substr($file->getPathname(), strlen($root) + 1)] = $code;
            }
        }
        ksort($out);

        return $out;
    }

    /** @param array<string, string> $sources @return list<string> */
    private static function impure(array $sources): array
    {
        $v = [];
        foreach ($sources as $f => $code) {
            if (preg_match(self::IMPURE, $code, $m)) {
                $v[] = "{$f}: {$m[0]}";
            }
        }

        return $v;
    }

    /** @param array<string, string> $sources @return list<string> */
    private static function identityImports(array $sources, array $allowed): array
    {
        $v = [];
        foreach ($sources as $f => $code) {
            preg_match_all('/^use\s+(App\\\\Contexts\\\\Identity\\\\[^;\s]+)/m', $code, $m);
            foreach ($m[1] as $import) {
                if (!in_array($import, $allowed, true)) {
                    $v[] = "{$f}: {$import}";
                }
            }
        }

        return $v;
    }

    public function test_identity_domain_and_application_are_pure(): void
    {
        $sources = [...self::sources('app/Contexts/Identity/Domain'), ...self::sources('app/Contexts/Identity/Application')];

        $this->assertNotEmpty($sources);
        $this->assertSame([], self::impure($sources));
    }

    public function test_no_identity_port_or_service_accepts_a_context_person_ref(): void
    {
        $offending = [];
        foreach (glob(dirname(__DIR__, 2) . '/app/Contexts/Identity/{Application,Application/Ports,Infrastructure}/*.php', GLOB_BRACE) ?: [] as $file) {
            $class = 'App\\Contexts\\Identity\\' . str_replace(['/', '.php'], ['\\', ''], substr($file, strlen(dirname(__DIR__, 2) . '/app/Contexts/Identity/')));
            if (!class_exists($class) && !interface_exists($class)) {
                continue;
            }
            foreach ((new \ReflectionClass($class))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                // A factory that wraps a reference into its own value (e.g. ActingContextRefAnswer::granted) is not a
                // lookup. A reverse lookup is a method that takes a reference and returns anything else.
                $returns = (string) $method->getReturnType();
                if (in_array($returns, ['self', 'static', $class], true)) {
                    continue;
                }
                foreach ($method->getParameters() as $p) {
                    if ((string) $p->getType() === ContextPersonRef::class || (string) $p->getType() === '?' . ContextPersonRef::class) {
                        $offending[] = "{$class}::{$method->getName()}";
                    }
                }
            }
        }

        $this->assertSame([], $offending, 'IG-3: no reverse lookup');
    }

    public function test_reference_geography_domain_and_application_import_nothing_from_identity(): void
    {
        $sources = [...self::sources('app/Contexts/ReferenceGeography/Domain'), ...self::sources('app/Contexts/ReferenceGeography/Application')];

        $this->assertNotEmpty($sources);
        $this->assertSame([], self::identityImports($sources, []));
    }

    public function test_reference_geography_infrastructure_uses_only_identitys_published_service(): void
    {
        $this->assertSame([], self::identityImports(self::sources('app/Contexts/ReferenceGeography/Infrastructure'), self::GEOGRAPHY_MAY_IMPORT_FROM_IDENTITY));
    }

    public function test_the_checks_catch_planted_violations(): void
    {
        $planted = [
            'A.php' => "<?php\nuse Illuminate\\Support\\Facades\\DB;\nfinal class A {}\n",
            'B.php' => "<?php\nfinal class B { public function f() { return random_bytes(16); } }\n",
            'C.php' => "<?php\nuse App\\Contexts\\Identity\\Domain\\PersonId;\nfinal class C {}\n",
            'D.php' => "<?php\nuse App\\Contexts\\Identity\\Application\\ActingContextRef;\nfinal class D {}\n",
        ];

        $this->assertSame(['A.php: Illuminate\\', 'B.php: random_bytes('], self::impure(['A.php' => $planted['A.php'], 'B.php' => $planted['B.php']]));
        $this->assertSame(['C.php: App\\Contexts\\Identity\\Domain\\PersonId'], self::identityImports(['C.php' => $planted['C.php'], 'D.php' => $planted['D.php']], self::GEOGRAPHY_MAY_IMPORT_FROM_IDENTITY));
        $this->assertSame(['D.php: App\\Contexts\\Identity\\Application\\ActingContextRef'], self::identityImports(['D.php' => $planted['D.php']], []), 'Domain/Application may import nothing');
    }
}
