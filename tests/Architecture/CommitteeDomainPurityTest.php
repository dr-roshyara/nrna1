<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class CommitteeDomainPurityTest extends TestCase
{
    private const DOMAIN_PATH = 'app/Contexts/Membership/Domain/Committee';
    private const FORBIDDEN_IMPORTS = [
        'Illuminate\\',
        'Laravel\\',
        'Eloquent',
        'Model',
        'Carbon\\',
    ];

    public function test_domain_layer_has_no_framework_dependencies(): void
    {
        $violations = [];

        foreach ($this->getPhpFiles(self::DOMAIN_PATH) as $file) {
            $content = file_get_contents($file->getRealPath());

            foreach (self::FORBIDDEN_IMPORTS as $forbidden) {
                if (preg_match("~use\s+" . preg_quote($forbidden, '~') . "~", $content)) {
                    $violations[] = sprintf(
                        '%s imports %s (forbidden)',
                        $file->getPathname(),
                        $forbidden
                    );
                }
            }
        }

        $this->assertEmpty($violations, implode("\n", $violations));
    }

    public function test_aggregate_is_final(): void
    {
        $filePath = self::DOMAIN_PATH . '/CommitteeAggregate.php';
        $content = file_get_contents($filePath);

        $this->assertStringContainsString(
            'final class CommitteeAggregate',
            $content,
            'CommitteeAggregate must be final'
        );
    }

    public function test_value_objects_are_readonly(): void
    {
        $readonlyVOs = [
            'CommitteeId.php',
            'MemberId.php',
            'TermPeriod.php',
            'EffectivePeriod.php',
            'StructuralOperationalState.php',
            'CommitteeFacts.php',
        ];

        foreach ($readonlyVOs as $vo) {
            $filePath = self::DOMAIN_PATH . '/ValueObjects/' . $vo;

            if (!file_exists($filePath)) {
                $this->markTestIncomplete("File {$vo} does not exist");
                continue;
            }

            $content = file_get_contents($filePath);

            $this->assertTrue(
                str_contains($content, 'readonly class') || str_contains($content, 'enum'),
                "{$vo} must be readonly or enum"
            );
        }
    }

    public function test_domain_events_are_readonly(): void
    {
        $eventFiles = [
            'CommitteeCreated.php',
            'CommitteeParentAttached.php',
            'CommitteeLifecycleChanged.php',
            'CommitteeTermUpdated.php',
        ];

        foreach ($eventFiles as $eventFile) {
            $filePath = self::DOMAIN_PATH . '/Events/' . $eventFile;

            if (!file_exists($filePath)) {
                $this->markTestIncomplete("Event file {$eventFile} does not exist");
                continue;
            }

            $content = file_get_contents($filePath);

            $this->assertStringContainsString(
                'readonly class',
                $content,
                "{$eventFile} must be readonly"
            );
        }
    }

    public function test_policy_has_no_constructor_dependency_injection(): void
    {
        $filePath = self::DOMAIN_PATH . '/Policies/CommitteeHierarchyPolicy.php';
        $content = file_get_contents($filePath);

        $this->assertStringContainsString('final class CommitteeHierarchyPolicy', $content);
        $this->assertStringNotContainsString('public function __construct', $content);
    }

    public function test_value_objects_have_private_constructor(): void
    {
        $voFiles = [
            'CommitteeId.php',
            'MemberId.php',
        ];

        foreach ($voFiles as $vo) {
            $filePath = self::DOMAIN_PATH . '/ValueObjects/' . $vo;

            if (!file_exists($filePath)) {
                $this->markTestIncomplete("File {$vo} does not exist");
                continue;
            }

            $content = file_get_contents($filePath);

            $this->assertStringContainsString(
                'private function __construct',
                $content,
                "{$vo} must have private constructor to enforce factory pattern"
            );
        }
    }

    public function test_aggregate_exposes_release_events_only(): void
    {
        $filePath = self::DOMAIN_PATH . '/CommitteeAggregate.php';
        $content = file_get_contents($filePath);

        $this->assertStringContainsString('public function releaseEvents', $content);
        $this->assertStringContainsString('public function peekEvents', $content);
        $this->assertStringNotContainsString('public function recordEvent', $content);
    }

    public function test_no_public_setters_in_aggregate(): void
    {
        $filePath = self::DOMAIN_PATH . '/CommitteeAggregate.php';
        $content = file_get_contents($filePath);

        $setters = preg_match_all('/public\s+function\s+set\w+\(/', $content);

        $this->assertEquals(0, $setters, 'Aggregate must not have public setter methods');
    }

    /**
     * @return iterable<SplFileInfo>
     */
    private function getPhpFiles(string $path): iterable
    {
        if (!is_dir($path)) {
            return [];
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                yield $file;
            }
        }
    }
}
