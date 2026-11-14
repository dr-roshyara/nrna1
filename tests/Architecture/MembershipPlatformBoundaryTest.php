<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 10 (D-233; routing design §3.5, §3.7 L-3; T-14 part 2). Static, comments ignored:
 * - Membership's Domain and Application import NOTHING of Platform;
 * - Membership uses only Platform's PUBLISHED routing types, and the infrastructure-level ones
 *   (RoutedOrganisationConnection, NotTheRoutedOrganisation) only from its Infrastructure;
 * - T-14 part 2: M-2 has no other true path. Every MembershipSourceOfTruthPort implementation in app/ is either
 *   FailClosedMembershipSourceOfTruth (`return false;` only) or RegistryRoutedLedgerSource, whose only answer is
 *   RoutedOrganisation::isRegistryRouted.
 */
final class MembershipPlatformBoundaryTest extends TestCase
{
    private const PUBLISHED_APPLICATION = ['App\\Contexts\\Platform\\Application\\Published\\RoutedOrganisation'];
    private const PUBLISHED_INFRASTRUCTURE = [
        'App\\Contexts\\Platform\\Infrastructure\\Published\\RoutedOrganisationConnection',
        'App\\Contexts\\Platform\\Infrastructure\\Published\\NotTheRoutedOrganisation',
    ];

    /** @return array<string, string> repository-relative path => code without comments */
    private static function sources(string $dir): array
    {
        $root = dirname(__DIR__, 2);
        $out = [];
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator("{$root}/{$dir}", \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $code = '';
            foreach (token_get_all((string) file_get_contents($file->getPathname())) as $t) {
                $code .= is_array($t) ? (in_array($t[0], [T_COMMENT, T_DOC_COMMENT], true) ? '' : $t[1]) : $t;
            }
            $out[substr($file->getPathname(), strlen($root) + 1)] = $code;
        }

        return $out;
    }

    /** @param array<string, string> $sources @return list<string> "file: Platform class" not allowed there */
    public static function platformReferences(array $sources): array
    {
        $hits = [];
        foreach ($sources as $path => $code) {
            preg_match_all('/\\\\?App\\\\Contexts\\\\Platform\\\\[A-Za-z0-9_\\\\]+/', $code, $m);
            $infrastructure = str_contains($path, '/Infrastructure/');
            $allowed = $infrastructure ? [...self::PUBLISHED_APPLICATION, ...self::PUBLISHED_INFRASTRUCTURE] : [];
            foreach (array_unique(array_map(static fn (string $c): string => ltrim($c, '\\'), $m[0])) as $class) {
                if (!in_array($class, $allowed, true)) {
                    $hits[] = "{$path}: {$class}";
                }
            }
        }

        return $hits;
    }

    public function test_membership_uses_only_platforms_published_routing_types_and_only_from_its_infrastructure(): void
    {
        // pre-existing, RECORDED deviations (module installation, "unclear ownership") stay visible in the ratchet
        // baseline; only they are tolerated here, so any new Membership→Platform reference fails
        $recorded = [];
        $baseline = json_decode((string) file_get_contents(dirname(__DIR__) . '/Architecture/baselines/architecture-ratchet.json'), true, 512, JSON_THROW_ON_ERROR);
        foreach (array_keys($baseline) as $key) {
            if (preg_match('~^A-3\|(app/Contexts/Membership/[^|]+)\|(App\\\\Contexts\\\\Platform\\\\.+)$~', $key, $m) === 1) {
                $recorded[] = "{$m[1]}: {$m[2]}";
            }
        }
        $this->assertCount(2, $recorded, 'the two recorded legacy deviations (InstallMembershipModule, MemberImportController)');

        $this->assertSame([], array_values(array_diff(self::platformReferences(self::sources('app/Contexts/Membership')), $recorded)));
    }

    public function test_t14_part2_m2_has_no_other_true_path(): void
    {
        $implementations = [];
        foreach (self::sources('app') as $path => $code) {
            if (preg_match('/class\s+(\w+)[^{]*implements[^{]*\bMembershipSourceOfTruthPort\b/', $code, $m) === 1) {
                $body = preg_match('/function\s+isLedger\s*\([^)]*\)\s*:\s*bool\s*\{(.*?)\n    \}/s', $code, $b) === 1 ? preg_replace('/\s+/', ' ', trim($b[1])) : null;
                $implementations[$m[1]] = $body;
            }
        }
        ksort($implementations);

        $this->assertSame([
            'FailClosedMembershipSourceOfTruth' => 'return false;',
            'RegistryRoutedLedgerSource' => 'return $this->routed->isRegistryRouted($organisation);',
        ], $implementations);
    }

    public function test_the_check_sees_planted_crossings(): void
    {
        $this->assertSame([
            'app/Contexts/Membership/Application/X.php: App\\Contexts\\Platform\\Application\\Published\\RoutedOrganisation',
            'app/Contexts/Membership/Infrastructure/Y.php: App\\Contexts\\Platform\\Infrastructure\\OrganisationContext',
        ], self::platformReferences([
            'app/Contexts/Membership/Application/X.php' => '<?php use App\\Contexts\\Platform\\Application\\Published\\RoutedOrganisation;',
            'app/Contexts/Membership/Infrastructure/Y.php' => '<?php use App\\Contexts\\Platform\\Infrastructure\\OrganisationContext;',
            'app/Contexts/Membership/Infrastructure/Z.php' => '<?php use App\\Contexts\\Platform\\Infrastructure\\Published\\RoutedOrganisationConnection;',
        ]));
    }
}
