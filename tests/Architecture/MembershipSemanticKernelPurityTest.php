<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * Fitness test for the Membership Slice 0 semantic kernel (PBDIGIT-258; contract §2 C, §12, §25).
 *
 * - Pure PHP: no framework, persistence, HTTP, clock or randomness.
 * - No other context's vocabulary (Election, Finance, Governance, committees).
 * - Resolution never depends on the acceptance mechanism (Theorem 0).
 * - No generic DDD framework, and no stored lifecycle status.
 */
final class MembershipSemanticKernelPurityTest extends TestCase
{
    private const KERNEL = 'app/Contexts/Membership/Domain/Organisational';
    private const NAMESPACE = 'App\\Contexts\\Membership\\Domain\\Organisational';
    private const ALLOWED_IMPORTS = ['App\\Contexts\\Shared\\Domain\\ValueObjects\\TenantId'];

    /** @return array<string, string> path => source */
    private function sources(): array
    {
        $root = dirname(__DIR__, 2) . '/' . self::KERNEL;
        $files = glob($root . '/*.php') ?: [];
        $this->assertNotEmpty($files, 'kernel not found');
        $out = [];
        foreach ($files as $file) {
            $out[basename($file)] = self::withoutComments((string) file_get_contents($file));
        }

        return $out;
    }

    /** Scan code only: prose in docblocks may legitimately mention "time (" or "decide()". */
    private static function withoutComments(string $source): string
    {
        $code = '';
        foreach (token_get_all($source) as $token) {
            if (is_array($token) && in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)) {
                continue;
            }
            $code .= is_array($token) ? $token[1] : $token;
        }

        return $code;
    }

    public function test_the_kernel_imports_nothing_but_itself_and_the_shared_tenant_id(): void
    {
        $violations = [];
        foreach ($this->sources() as $file => $src) {
            preg_match_all('/^use\s+([^;]+);/m', $src, $m);
            foreach ($m[1] as $import) {
                if (!in_array($import, self::ALLOWED_IMPORTS, true) && !str_starts_with($import, self::NAMESPACE . '\\')) {
                    $violations[] = "$file: use $import";
                }
            }
        }

        $this->assertSame([], $violations);
    }

    public function test_the_kernel_has_no_framework_persistence_http_clock_or_randomness(): void
    {
        $forbidden = [
            '/Illuminate\\\\|Laravel\\\\|Eloquent|App\\\\Models\\\\|DB::|Carbon|DateTime|\\\\Http\\\\/',
            '/\b(?:time|date|now|rand|mt_rand|random_int|random_bytes|uniqid|microtime|hrtime)\s*\(/',
        ];
        $violations = [];
        foreach ($this->sources() as $file => $src) {
            foreach ($forbidden as $pattern) {
                if (preg_match($pattern, $src, $m)) {
                    $violations[] = "$file: {$m[0]}";
                }
            }
        }

        $this->assertSame([], $violations);
    }

    public function test_the_kernel_uses_no_vocabulary_of_other_contexts(): void
    {
        $pattern = '/\\\\Elections?\\\\|\\\\Finance\\\\|\\\\Governance\\\\|VotingEntitlement|canVote|FeeStatus|CommitteePosition|Committee/i';
        $violations = [];
        foreach ($this->sources() as $file => $src) {
            if (preg_match($pattern, $src, $m)) {
                $violations[] = "$file: {$m[0]}";
            }
        }

        $this->assertSame([], $violations);
    }

    public function test_resolution_does_not_depend_on_acceptance(): void
    {
        $sources = $this->sources();

        // Theorem 0: Resolution never reads G* (InvariantSet / Member::accept / Decision).
        $this->assertDoesNotMatchRegularExpression('/InvariantSet|Decision|RefusalReason|\bMember::|decide/', $sources['Resolution.php']);
        // The invariant set judges a resolved state; it does not resolve or decide.
        $this->assertDoesNotMatchRegularExpression('/Resolution::|Decision|decide/', $sources['InvariantSet.php']);
    }

    public function test_no_generic_ddd_framework_and_no_stored_lifecycle_status(): void
    {
        $violations = [];
        foreach ($this->sources() as $file => $src) {
            if (preg_match('/\b(?:abstract\s+class|extends\s+\w+|AggregateRoot|DomainEvent|EventBus|Specification|RuleEngine|StateMachine|CommandBus|RecordsEvents)\b/', $src, $m)) {
                $violations[] = "$file: {$m[0]}";
            }
            if (preg_match('/\$(?:status|isActive|isMember|isExpired|isLapsed|canVote)\b/', $src, $m)) {
                $violations[] = "$file: stored {$m[0]}";
            }
            if (preg_match('/^(?:final\s+(?:readonly\s+)?class|interface|enum)\s/m', $src) !== 1) {
                $violations[] = "$file: not a final class, interface or enum";
            }
        }

        $this->assertSame([], $violations);
    }
}
