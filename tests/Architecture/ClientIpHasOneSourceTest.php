<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-287: proxy trust has one source, config/trustedproxy.php (App\Http\Middleware\TrustProxies). Settings that
 * look like they control it but never did must not exist, so nobody relies on them:
 * - VOTING_TRUST_PROXIES / voting_security.trust_proxies (both branches of its only reader returned $request->ip());
 * - getClientIp(true): Symfony's getClientIp() takes no argument; the `true` suggested a "trust proxies" switch.
 * Database-free: source files are read, never run.
 */
final class ClientIpHasOneSourceTest extends TestCase
{
    private const SCANNED = ['app', 'config', 'routes', 'bootstrap'];

    /** @return list<string> "path:line" of every match */
    private function sourceLinesMatching(string $pattern): array
    {
        $root = dirname(__DIR__, 2);
        $found = [];
        foreach (self::SCANNED as $dir) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root.'/'.$dir, \FilesystemIterator::SKIP_DOTS));
            foreach ($files as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }
                foreach (file($file->getPathname()) as $i => $line) {
                    if (preg_match($pattern, $line)) {
                        $found[] = substr($file->getPathname(), strlen($root) + 1).':'.($i + 1);
                    }
                }
            }
        }

        return $found;
    }

    public function test_no_code_reads_the_dead_voting_trust_proxies_setting(): void
    {
        $this->assertSame([], $this->sourceLinesMatching('/VOTING_TRUST_PROXIES|voting_security\.trust_proxies|[\'"]trust_proxies[\'"]/'));
    }

    public function test_no_code_passes_an_argument_to_get_client_ip(): void
    {
        // Calls on the request only (Request::, $request->, request()->); ValidateVotingIp has its own helper of the
        // same name that takes the request.
        $this->assertSame([], $this->sourceLinesMatching('/(Request::|\$request->|request\(\)->)getClientIp\(\s*[^)\s]/'));
    }
}
