<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * Fitness test for Membership Slice 1b (PBDIGIT-260; contract §7, §8 T13).
 *
 * - Dependencies point inward: Infrastructure → Application → Domain; never the reverse.
 * - The adapter carries no Governance / Election / Finance vocabulary.
 * - There are no Eloquent models for the authoritative tables (append-only, no easy mutation API).
 * - The migration is PostgreSQL-only: no driver branches (DB-1).
 */
final class MembershipSlice1bPersistenceBoundaryTest extends TestCase
{
    private const INFRA = 'app/Contexts/Membership/Infrastructure/Organisational';
    private const APPLICATION = 'app/Contexts/Membership/Application/Organisational';
    private const KERNEL = 'app/Contexts/Membership/Domain/Organisational';
    private const MIGRATION = 'database/migrations/2026_10_09_100000_create_org_membership_tables.php';

    /** @return array<string, string> file => code without comments */
    private static function code(string $dir): array
    {
        $root = dirname(__DIR__, 2) . '/' . $dir;
        $files = is_file($root) ? [$root] : iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)));
        $out = [];
        foreach ($files as $file) {
            $path = (string) $file;
            if (!str_ends_with($path, '.php')) {
                continue;
            }
            $code = '';
            foreach (token_get_all((string) file_get_contents($path)) as $t) {
                if (!(is_array($t) && in_array($t[0], [T_COMMENT, T_DOC_COMMENT], true))) {
                    $code .= is_array($t) ? $t[1] : $t;
                }
            }
            $out[basename($path)] = $code;
        }

        return $out;
    }

    public function test_kernel_and_application_do_not_depend_on_infrastructure(): void
    {
        $violations = [];
        foreach ([self::KERNEL, self::APPLICATION] as $dir) {
            foreach (self::code($dir) as $file => $code) {
                if (preg_match('/Membership\\\\Infrastructure\\\\|Illuminate\\\\/', $code, $m)) {
                    $violations[] = "$dir/$file: {$m[0]}";
                }
            }
        }

        $this->assertSame([], $violations);
    }

    public function test_the_adapter_has_no_other_context_vocabulary_and_no_eloquent_model(): void
    {
        $violations = [];
        foreach (self::code(self::INFRA) as $file => $code) {
            if (preg_match('/committee|participation|tenure|appointment|governance|election|finance|\bfee\b|VotingEntitlement/i', $code, $m)) {
                $violations[] = "$file: {$m[0]}";
            }
            if (preg_match('/extends\s+Model\b|Eloquent\\\\Model/', $code, $m)) {
                $violations[] = "$file: {$m[0]}";
            }
        }

        $this->assertSame([], $violations);
    }

    public function test_the_migration_is_postgresql_only(): void
    {
        $code = self::code(self::MIGRATION)[basename(self::MIGRATION)];

        $this->assertDoesNotMatchRegularExpression('/getDriverName|mysql|sqlite|app\(\)->environment|env\(/i', $code, 'DB-1, and no environment-dependent tricks');
    }
}
