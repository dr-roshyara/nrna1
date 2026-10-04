<?php

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-213 C2 — Voter Admission domain/application purity (CLAUDE.md layer rules,
 * ADR_20261004_1549 §2.1 vocabulary).
 *
 * - Domain and Application classes of the admission model import no framework code.
 * - No class in the Elections context is named "Gate": that word already means the
 *   election-wide acceptance gates (OperatingCore\Gate, EM-GOV-016); the admission
 *   vocabulary is Requirement → Evidence → Decision → Entitlement.
 */
final class VoterAdmissionDomainPurityTest extends TestCase
{
    private const PURE_FILES = [
        'app/Contexts/Elections/Domain/ValueObjects/AdmissionRequirement.php',
        'app/Contexts/Elections/Domain/ValueObjects/VoterAdmissionPolicy.php',
        'app/Contexts/Elections/Domain/ValueObjects/AdmissionEvidence.php',
        'app/Contexts/Elections/Domain/ValueObjects/AdmissionDecision.php',
        'app/Contexts/Elections/Domain/Exceptions/AdmissionPolicyLocked.php',
        'app/Contexts/Elections/Application/Ports/VoterAdmissionPolicyStore.php',
        'app/Contexts/Elections/Application/Ports/AdmissionImpactQuery.php',
        'app/Contexts/Elections/Application/Commands/ChangeVoterAdmissionPolicyCommand.php',
        'app/Contexts/Elections/Application/Handlers/ChangeVoterAdmissionPolicyHandler.php',
    ];

    private function root(): string
    {
        return dirname(__DIR__, 2);
    }

    public function test_admission_domain_and_application_import_no_framework_code(): void
    {
        $violations = [];

        foreach (self::PURE_FILES as $relative) {
            $path = $this->root() . '/' . $relative;

            if (! is_file($path)) {
                $violations[] = sprintf('%s does not exist', $relative);
                continue;
            }

            if (preg_match('~use\s+Illuminate\\\\|\\\\Illuminate\\\\Support\\\\Facades~', file_get_contents($path))) {
                $violations[] = sprintf('%s depends on Illuminate', $relative);
            }
        }

        $this->assertEmpty($violations, implode("\n", $violations));
    }

    public function test_no_gate_named_class_exists_in_the_elections_context(): void
    {
        $violations = [];
        $iterator   = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->root() . '/app/Contexts/Elections', \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php' && preg_match('/Gate/', $file->getBasename('.php'))) {
                $violations[] = $file->getPathname();
            }
        }

        $this->assertEmpty($violations, "Use the admission vocabulary, not 'Gate':\n" . implode("\n", $violations));
    }
}
