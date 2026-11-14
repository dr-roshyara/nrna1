<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 5c (baseline §9 pattern: consumer-owned port + one adapter): Platform reaches Identity ONLY
 * through IdentityContactAccounts (Infrastructure), behind Platform's own ContactAccounts port, and (task 6) through
 * IdentitySignedInAccount, behind its SignedInAccount port (CurrentAccount only). Platform's Domain and
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

    /** task 6 (E-1): the second adapter, Platform's SignedInAccount port answered by Identity's CurrentAccount */
    private const SIGNED_IN_ADAPTER = 'app/Contexts/Platform/Infrastructure/IdentitySignedInAccount.php';

    public function test_platform_reaches_identity_only_through_its_adapters(): void
    {
        $expected = [
            ...array_map(static fn (string $c): string => self::ADAPTER . ': ' . $c, self::ALLOWED),
            self::SIGNED_IN_ADAPTER . ': App\\Contexts\\Identity\\Application\\Ports\\CurrentAccount',
        ];
        sort($expected);
        $this->assertSame($expected, self::identityReferences());
    }
}
