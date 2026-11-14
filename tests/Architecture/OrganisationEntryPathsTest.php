<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 6, T-10 part 3 (tactical design §3.2; D-160): the legacy path and the registry path never share an
 * organisation. Static half (the route half is in OrganisationEntryHttpTest):
 * - Platform never uses the legacy tenant resolvers, their session key or their container key;
 * - the legacy tenant resolvers never use Platform, nor the registry selection's session key.
 * Comments are ignored. The legacy files are only READ here: slice 2 changes nothing in the legacy path.
 */
final class OrganisationEntryPathsTest extends TestCase
{
    private const LEGACY_RESOLVERS = [
        'app/Http/Middleware/TenantContext.php',
        'app/Services/TenantContext.php',
        'app/Http/Middleware/IdentifyTenantFromHeader.php',
    ];
    private const LEGACY_MARKERS = [
        'App\\Http\\Middleware\\TenantContext',
        'App\\Services\\TenantContext',
        'App\\Http\\Middleware\\IdentifyTenantFromHeader',
        'current_organisation_id',
        'current.organisation_id',
    ];
    private const REGISTRY_MARKERS = ['App\\Contexts\\Platform', 'platform.organisation_selection'];

    private static function code(string $path): string
    {
        $code = '';
        foreach (token_get_all((string) file_get_contents($path)) as $t) {
            $code .= is_array($t) ? (in_array($t[0], [T_COMMENT, T_DOC_COMMENT], true) ? '' : $t[1]) : $t;
        }

        return str_replace('\\\\', '\\', $code);
    }

    /** @param list<string> $markers @return list<string> */
    private static function hits(string $relative, string $code, array $markers): array
    {
        return array_map(static fn (string $m): string => "{$relative}: {$m}", array_values(array_filter($markers, static fn (string $m): bool => str_contains($code, $m))));
    }

    public function test_platform_never_uses_the_legacy_tenant_resolvers(): void
    {
        $root = dirname(__DIR__, 2);
        $hits = [];
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator("{$root}/app/Contexts/Platform", \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $hits = [...$hits, ...self::hits(substr($file->getPathname(), strlen($root) + 1), self::code($file->getPathname()), self::LEGACY_MARKERS)];
            }
        }
        $this->assertSame([], $hits);
    }

    public function test_the_legacy_tenant_resolvers_never_use_platform(): void
    {
        $root = dirname(__DIR__, 2);
        $hits = [];
        foreach (self::LEGACY_RESOLVERS as $relative) {
            $this->assertFileExists("{$root}/{$relative}");
            $hits = [...$hits, ...self::hits($relative, self::code("{$root}/{$relative}"), self::REGISTRY_MARKERS)];
        }
        $this->assertSame([], $hits);
    }

    public function test_the_checks_see_a_planted_crossing(): void
    {
        $this->assertSame(['x.php: current_organisation_id'], self::hits('x.php', "<?php session('current_organisation_id');", self::LEGACY_MARKERS));
        $this->assertSame(['y.php: App\\Contexts\\Platform'], self::hits('y.php', "<?php use App\\Contexts\\Platform\\Foo;", self::REGISTRY_MARKERS));
        $this->assertSame([], self::hits('z.php', self::codeOf("<?php // current_organisation_id in a comment\n"), self::LEGACY_MARKERS));
    }

    private static function codeOf(string $source): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'oep');
        file_put_contents($tmp, $source);
        try {
            return self::code($tmp);
        } finally {
            unlink($tmp);
        }
    }
}
