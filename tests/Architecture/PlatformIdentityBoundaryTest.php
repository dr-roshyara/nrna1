<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 5c (baseline §9 pattern: consumer-owned port + one adapter): Platform reaches Identity ONLY
 * through IdentityContactAccounts (Infrastructure), behind Platform's own ContactAccounts port. Platform's Domain and
 * Application import nothing of Identity; the adapter imports only AccountDirectory, AccountId and IdentityUnavailable. The same imports are recorded one by one in the architecture-ratchet baseline.
 */
final class PlatformIdentityBoundaryTest extends TestCase
{
    private const ADAPTER = 'app/Contexts/Platform/Infrastructure/IdentityContactAccounts.php';
    private const ALLOWED = [
        'App\\Contexts\\Identity\\Application\\IdentityUnavailable',
        'App\\Contexts\\Identity\\Application\\Ports\\AccountDirectory',
        'App\\Contexts\\Identity\\Domain\\AccountId',
    ];

    /** @return list<string> "file: imported Identity class" for every Identity reference in Platform */
    private static function identityReferences(): array
    {
        $root = dirname(__DIR__, 2);
        $out = [];
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root . '/app/Contexts/Platform', \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $code = '';
            foreach (token_get_all((string) file_get_contents($file->getPathname())) as $t) {
                $code .= is_array($t) ? (in_array($t[0], [T_COMMENT, T_DOC_COMMENT], true) ? '' : $t[1]) : $t;
            }
            preg_match_all('/\\\\?App\\\\Contexts\\\\Identity\\\\[A-Za-z0-9_\\\\]+/', $code, $m);
            foreach (array_unique($m[0]) as $class) {
                $out[] = substr($file->getPathname(), strlen($root) + 1) . ': ' . ltrim($class, '\\');
            }
        }
        sort($out);

        return $out;
    }

    public function test_platform_reaches_identity_only_through_its_one_adapter(): void
    {
        $this->assertSame(array_map(static fn (string $c): string => self::ADAPTER . ': ' . $c, self::ALLOWED), self::identityReferences());
    }
}
