<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * Fitness test for Membership Slice 1a (PBDIGIT-259; contract §7; instruction §20).
 *
 * - The application layer (ports, handlers) uses no framework, persistence, facade,
 *   clock or randomness, and depends only on itself, the Membership kernel and the
 *   shared TenantId.
 * - Neither the kernel nor the application layer carries the vocabulary of
 *   Governance participation (committees, positions, tenures, appointments),
 *   Election or Finance. Authority appears only as recorded evidence and a consumer port.
 */
final class MembershipSlice1aBoundaryTest extends TestCase
{
    private const APPLICATION = 'app/Contexts/Membership/Application/Organisational';
    private const KERNEL = 'app/Contexts/Membership/Domain/Organisational';

    /** @return array<string, string> relative path => code without comments */
    private static function sources(string $dir): array
    {
        $root = dirname(__DIR__, 2) . '/' . $dir;
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
        $out = [];
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $code = '';
            foreach (token_get_all((string) file_get_contents($file->getPathname())) as $token) {
                if (is_array($token) && in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)) {
                    continue;
                }
                $code .= is_array($token) ? $token[1] : $token;
            }
            $out[substr($file->getPathname(), strlen($root) + 1)] = $code;
        }

        return $out;
    }

    public function test_the_application_layer_depends_only_on_itself_the_kernel_and_the_shared_tenant_id(): void
    {
        $allowed = [
            'App\\Contexts\\Membership\\Application\\Organisational\\',
            'App\\Contexts\\Membership\\Domain\\Organisational\\',
            'App\\Contexts\\Shared\\Domain\\ValueObjects\\TenantId',
        ];
        $violations = [];
        foreach (self::sources(self::APPLICATION) as $file => $code) {
            preg_match_all('/^use\s+([^;]+);/m', $code, $m);
            foreach ($m[1] as $import) {
                $ok = false;
                foreach ($allowed as $prefix) {
                    $ok = $ok || str_starts_with($import, $prefix);
                }
                if (!$ok) {
                    $violations[] = "$file: use $import";
                }
            }
        }

        $this->assertSame([], $violations);
    }

    public function test_the_application_layer_has_no_framework_persistence_clock_or_randomness(): void
    {
        $pattern = '/Illuminate\\\\|Laravel\\\\|Eloquent|App\\\\Models\\\\|DB::|Cache::|Log::|Carbon|DateTime|\b(?:now|time|date|rand|mt_rand|random_int|uniqid|microtime)\s*\(/';
        $violations = [];
        foreach (self::sources(self::APPLICATION) as $file => $code) {
            if (preg_match($pattern, $code, $m)) {
                $violations[] = "$file: {$m[0]}";
            }
        }

        $this->assertSame([], $violations);
    }

    public function test_no_governance_election_or_finance_vocabulary_in_membership(): void
    {
        $pattern = '/Committee|Participation|Tenure|Appointment|\bPosition\b|Mandate|Governance|Elections?\b|Election\\\\|VotingEntitlement|Finance|\bFee\b/i';
        $violations = [];
        foreach ([self::APPLICATION, self::KERNEL] as $dir) {
            foreach (self::sources($dir) as $file => $code) {
                if (preg_match($pattern, $code, $m)) {
                    $violations[] = "$dir/$file: {$m[0]}";
                }
            }
        }

        $this->assertSame([], $violations);
    }

    public function test_the_port_offers_no_update_or_delete(): void
    {
        $port = self::sources(self::APPLICATION)['Ports/MemberRepositoryPort.php'];

        $this->assertDoesNotMatchRegularExpression('/function\s+(?:update|delete|remove|save|replace|rewrite)\w*\s*\(/i', $port, 'append-only (R-1a-4)');
    }
}
