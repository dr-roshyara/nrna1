<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * TenantId Enforcement Test
 *
 * Ensures canonical identity model is maintained across Membership context.
 * This is the PHPUnit layer of the Architecture Guard Framework (Phase 1).
 *
 * Prevents regression of the bounded context identity consolidation.
 */
final class TenantIdEnforcementTest extends TestCase
{
    private function getProjectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    public function test_membership_context_uses_only_shared_tenantid(): void
    {
        $membershipPath = $this->getProjectRoot() . '/app/Contexts/Membership';

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($membershipPath)
        );

        $violatingFiles = [];

        foreach ($files as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $content = file_get_contents($file->getPathname());

            // Check for forbidden legacy TenantId
            if (str_contains($content, 'Membership\\Domain\\ValueObjects\\TenantId')) {
                $violatingFiles[] = $file->getRelativePathname();
            }
        }

        $this->assertEmpty(
            $violatingFiles,
            "Membership context MUST use only Shared\\TenantId. Violations:\n" .
            implode("\n", $violatingFiles)
        );
    }

    public function test_tenantid_constructor_is_not_called_directly(): void
    {
        $membershipPath = $this->getProjectRoot() . '/app/Contexts/Membership';

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($membershipPath)
        );

        $violatingFiles = [];

        foreach ($files as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $content = file_get_contents($file->getPathname());

            // Check for unsafe direct constructor calls
            if (preg_match('/new\s+TenantId\s*\(/', $content)) {
                $violatingFiles[] = $file->getRelativePathname();
            }
        }

        $this->assertEmpty(
            $violatingFiles,
            "TenantId must be constructed via factories (fromString/fromOrganisationId). Violations:\n" .
            implode("\n", $violatingFiles)
        );
    }

    public function test_tenantid_factory_methods_are_canonical(): void
    {
        $tenantIdPath = $this->getProjectRoot() . '/app/Contexts/Shared/Domain/ValueObjects/TenantId.php';

        $this->assertFileExists($tenantIdPath, 'Canonical TenantId must exist');

        $content = file_get_contents($tenantIdPath);

        // Verify canonical construction paths exist
        $this->assertStringContainsString('public static function fromString', $content);
        $this->assertStringContainsString('public static function fromOrganisationId', $content);

        // Verify constructor is private
        $this->assertStringContainsString('private function __construct', $content);
    }
}
