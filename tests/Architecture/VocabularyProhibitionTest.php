<?php

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class VocabularyProhibitionTest extends TestCase
{
    public function test_no_domain_imports_illuminate(): void
    {
        $violations = [];
        $files = $this->getAllPhpFiles('app/Domain/Election/Security');

        foreach ($files as $file) {
            $content = file_get_contents($file);
            if (preg_match('/use Illuminate\\\\/', $content)) {
                $violations[] = "❌ $file: Domain cannot import Illuminate";
            }
        }

        $this->assertEmpty($violations, "\n" . implode("\n", $violations));
    }

    public function test_layer3_no_authority_vocabulary(): void
    {
        $violations = [];
        $forbidden = ['allow', 'deny', 'authorize', 'permit', 'capability', 'eligible', 'vote', 'trusted', 'untrusted'];
        $files = $this->getAllPhpFiles('app/Domain/Election/Security/Simplified/Interpretation');

        if (empty($files)) return; // Skip if directory doesn't exist

        foreach ($files as $file) {
            $content = file_get_contents($file);
            if (str_contains($content, 'interface') || str_contains($content, 'trait')) continue;

            foreach ($forbidden as $word) {
                if (preg_match('/\b' . $word . '\b/i', $content)) {
                    $violations[] = "❌ $file: Contains '$word' in Layer 3 (Interpretation)";
                    break;
                }
            }
        }

        $this->assertEmpty($violations, "\n" . implode("\n", $violations));
    }

    public function test_reason_must_be_enum_not_string(): void
    {
        $violations = [];
        $file = 'app/Domain/Election/Security/Simplified/EvidenceEvaluationResult.php';

        if (!file_exists($file)) {
            $this->markTestSkipped('EvidenceEvaluationResult not found');
        }

        $content = file_get_contents($file);

        if (preg_match('/public\s+string\s+\$reason/', $content)) {
            $violations[] = "❌ $file: reason must be enum, not string";
        }

        if (!preg_match('/EvaluationReasonCode/', $content)) {
            $violations[] = "❌ $file: Must use EvaluationReasonCode enum";
        }

        $this->assertEmpty($violations, "\n" . implode("\n", $violations));
    }

    public function test_no_free_text_in_replay_objects(): void
    {
        $violations = [];
        $files = $this->getAllPhpFiles('app/Domain/Election/Security/Simplified');

        foreach ($files as $file) {
            if (!preg_match('/(Result|Snapshot|Envelope|Trail)\.php/', $file)) continue;

            $content = file_get_contents($file);

            // Look for string $reason or string $message in replay objects
            if (preg_match('/class\s+\w+\s*\{[^}]*(public\s+string\s+\$reason|public\s+string\s+\$message)/', $content)) {
                $violations[] = "❌ $file: Replay object has free-text string (must be enum)";
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
