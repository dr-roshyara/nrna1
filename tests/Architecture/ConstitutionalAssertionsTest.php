<?php

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ConstitutionalAssertionsTest extends TestCase
{
    public function test_capability_decision_construction_exclusive(): void
    {
        $violations = [];
        // Check ONLY Election context for CapabilityDecision exclusivity
        $files = $this->getAllPhpFiles('app/Domain/Election');
        $files = array_merge($files, $this->getAllPhpFiles('app/Application/Election'));

        foreach ($files as $file) {
            if (str_contains($file, 'ElectionCapabilityResolver')) continue;
            $content = file_get_contents($file);

            if (preg_match('/new CapabilityDecision/', $content)) {
                $violations[] = "❌ $file: Cannot construct CapabilityDecision outside resolver";
            }
        }

        $this->assertEmpty($violations, "\n" . implode("\n", $violations));
    }

    public function test_no_authority_in_policies(): void
    {
        $violations = [];
        $files = $this->getAllPhpFiles('app/Application/Election/Security');

        foreach ($files as $file) {
            if (!str_contains($file, 'Policy')) continue;
            $content = file_get_contents($file);

            if (preg_match('/CapabilityDecision::(ALLOW|DENY)/', $content)) {
                $violations[] = "❌ $file: Policy cannot return CapabilityDecision";
            }
        }

        $this->assertEmpty($violations, "\n" . implode("\n", $violations));
    }

    public function test_no_authority_in_overlays(): void
    {
        $violations = [];
        $files = $this->getAllPhpFiles('app/Domain/Election/Security/Simplified');

        foreach ($files as $file) {
            if (!str_contains($file, 'Overlay')) continue;
            $content = file_get_contents($file);

            if (preg_match('/elevate|ELEVATE|allow|ALLOW|deny|DENY/', $content)) {
                if (!str_contains($content, 'interface') && !str_contains($content, 'abstract')) {
                    $violations[] = "❌ $file: Overlay cannot use authority vocabulary";
                }
            }
        }

        $this->assertEmpty($violations, "\n" . implode("\n", $violations));
    }

    private function getAllPhpFiles(string $dir): array
    {
        if (!is_dir($dir)) return [];
        $files = [];
        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        ) as $file) {
            if ($file->getExtension() === 'php') {
                $files[] = $file->getRealPath();
            }
        }
        return $files;
    }
}
