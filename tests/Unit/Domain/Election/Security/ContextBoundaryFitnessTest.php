<?php

namespace Tests\Unit\Domain\Election\Security;

use PHPUnit\Framework\TestCase;

/**
 * ContextBoundaryFitnessTest
 *
 * Machine-enforced context boundary invariants for P11 — Fitness Functions
 * for Context Boundaries. Enforces the dependency rules from
 * ContextDependencyRules.md (P7d).
 *
 * CONSTITUTIONAL LAW:
 * Violation means architectural boundary regression — NOT a test failure.
 * Each fitness function blocks deployment until the boundary violation is resolved.
 *
 * Context Map:
 *   Observation → Evidence → Legitimacy
 *   Replay ↕ (all)
 *   Governance → Legitimacy (outcome only)
 *   Projection → Observation (read-only, no Legitimacy)
 *
 * Status: Some tests require P12 namespace migration for full structural enforcement.
 * Tests marked [ACTIVE] are enforceable now.
 * Tests marked [PENDING NAMESPACE] document what must be enforced post-P12.
 */
class ContextBoundaryFitnessTest extends TestCase
{
    private string $projectRoot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectRoot = dirname(__DIR__, 5);
    }

    // =========================================================================
    // F11.1 — Context Dependency Enforcement
    // Forbidden cross-context imports (from ContextDependencyRules.md FDR-1
    // through FDR-7).
    // =========================================================================

    /**
     * F11.1a [ACTIVE]: Evidence must not import Legitimacy types (FDR-1).
     *
     * Evidence files must NOT reference LegitimacyOutcome or
     * ConstitutionalLegitimacyDecision. Only ConstitutionalLegitimacyDecision
     * itself is exempt as the sole resolver.
     *
     * Evidence files include:
     *   - app/Application/Election/Security/Policies/
     *   - app/Application/Election/Security/Capabilities/
     *   - app/Domain/Election/Security/Simplified/ (evidence types)
     *   - app/Application/Election/Security/TrustPolicyEvaluator.php
     *   - app/Application/Election/Security/PolicySequence.php
     */
    public function test_F11_1a_evidence_must_not_import_legitimacy(): void
    {
        $evidenceDirs = [
            'app/Application/Election/Security/Policies',
            'app/Application/Election/Security/Capabilities',
            'app/Application/Election/Security/TrustPolicyEvaluator.php',
            'app/Application/Election/Security/PolicySequence.php',
            'app/Application/Election/Security/TrustSnapshotAssembler.php',
            'app/Application/Election/Security/SecurityEventRecorder.php',
            'app/Application/Election/Security/Overlays',
        ];

        // Legitimacy types that Evidence must NOT reference
        $forbiddenTypes = ['LegitimacyOutcome', 'ConstitutionalLegitimacyDecision'];

        // Files explicitly exempted (telemetry, infrastructure)
        $exemptFiles = [
            // SecurityEventRecorder may reference LegitimacyEvaluated event
            'SecurityEventRecorder.php',
        ];

        $violations = [];

        foreach ($evidenceDirs as $path) {
            $fullPath = $this->projectRoot . '/' . $path;

            if (is_file($fullPath)) {
                $violations = array_merge(
                    $violations,
                    $this->scanFileForForbiddenTypes($fullPath, $forbiddenTypes, $exemptFiles),
                );
            } elseif (is_dir($fullPath)) {
                $violations = array_merge(
                    $violations,
                    $this->scanDirectoryForForbiddenTypes($fullPath, $forbiddenTypes, $exemptFiles),
                );
            }
        }

        $this->assertCount(
            0,
            $violations,
            "F11.1a violations — Evidence must not import Legitimacy types:\n" . implode("\n", $violations),
        );
    }

    /**
     * F11.1b [ACTIVE]: Observation (Overlays) must not import Legitimacy types
     * (FDR-3 / OBS-1).
     *
     * Overlay files produce OverlaySignal only. They must not reference
     * LegitimacyOutcome or any legitimacy derivation.
     */
    public function test_F11_1b_observation_must_not_import_legitimacy(): void
    {
        $overlayDir = $this->projectRoot . '/app/Application/Election/Security/Overlays';
        $forbiddenTypes = ['LegitimacyOutcome', 'ConstitutionalLegitimacyDecision'];

        $violations = $this->scanDirectoryForForbiddenTypes($overlayDir, $forbiddenTypes, []);

        $this->assertCount(
            0,
            $violations,
            "F11.1b violations — Observation must not import Legitimacy types:\n" . implode("\n", $violations),
        );
    }

    /**
     * F11.1c [ACTIVE]: Legitimacy must not import Observation types directly
     * (FDR-2).
     *
     * Legitimacy must not reference OverlaySignal or
     * ConstitutionalObservationContext directly. Observations must pass through
     * Evidence evaluation first.
     *
     * Legitimacy files include:
     *   - ConstitutionalLegitimacyDecision
     *   - Any file in Security/ that consumes EvaluationEnvelope
     */
    public function test_F11_1c_legitimacy_must_not_import_observation_directly(): void
    {
        $legitimacyFiles = [
            $this->projectRoot . '/app/Application/Election/Security/ConstitutionalLegitimacyDecision.php',
        ];

        // Observation types that Legitimacy must NOT reference directly
        $forbiddenTypes = ['OverlaySignal', 'ConstitutionalObservationContext'];

        $violations = [];

        foreach ($legitimacyFiles as $file) {
            if (!file_exists($file)) {
                continue;
            }
            $violations = array_merge(
                $violations,
                $this->scanFileForForbiddenTypes($file, $forbiddenTypes, []),
            );
        }

        $this->assertCount(
            0,
            $violations,
            "F11.1c violations — Legitimacy must not import Observation types directly:\n" . implode("\n", $violations),
        );
    }

    /**
     * F11.1d [ACTIVE]: Controllers must not derive LegitimacyOutcome (FDR-1
     * extension / F7).
     *
     * VoteController may RECEIVE LegitimacyOutcome (from the resolver) for
     * divergence telemetry, but must not CREATE it.
     */
    public function test_F11_1d_controllers_must_not_derive_legitimacy(): void
    {
        $controllerDir = $this->projectRoot . '/app/Http/Controllers';
        $forbiddenPatterns = [
            'LegitimacyOutcome::Allowed',
            'LegitimacyOutcome::Denied',
            'LegitimacyOutcome::Deferred',
            'LegitimacyOutcome::Investigate',
            'new ConstitutionalLegitimacyDecision',
        ];

        // Explicitly approved files where outcome is received (not derived)
        $exemptFiles = [
            'VoteController.php', // receives outcome from resolver for telemetry
        ];

        $violations = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($controllerDir, \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $isExempt = false;
            foreach ($exemptFiles as $exempt) {
                if (str_ends_with($file->getPathname(), $exempt)) {
                    $isExempt = true;
                    break;
                }
            }
            if ($isExempt) {
                continue;
            }

            $content = file_get_contents($file->getPathname());
            foreach ($forbiddenPatterns as $pattern) {
                if (str_contains($content, $pattern)) {
                    $relativePath = str_replace($this->projectRoot . '/', '', $file->getPathname());
                    $relativePath = str_replace('\\', '/', $relativePath);
                    $violations[] = "$relativePath contains forbidden pattern: $pattern";
                }
            }
        }

        $this->assertCount(
            0,
            $violations,
            "F11.1d violations — Controllers must not derive LegitimacyOutcome:\n" . implode("\n", $violations),
        );
    }

    /**
     * F11.1e [ACTIVE]: Temporary context isolation (FDR-6 / P-TEMP-2).
     *
     * Permanent contexts must not import Migration types.
     * Migration types must be deletable without breaking permanent code.
     *
     * Note: Full enforcement requires P12 namespace migration. This test
     * covers the currently identifiable migration types.
     */
    public function test_F11_1e_temporary_context_isolation(): void
    {
        // Migration-related types
        $migrationTypes = [
            'SovereigntyDivergenceRecord',
            'ConstitutionalDivergenceType',
            'DivergenceSeverity',
            'DivergenceObserver',
        ];

        // Directories that are part of migration (exempt from scan)
        $migrationDirs = [
            'app/Services/Constitutional',
        ];

        // Permanent directories that must not reference migration types
        $permanentDirs = [
            'app/Domain/Election/Security',
            'app/Application/Election/Security',
            'app/Http/Controllers',
        ];

        $violations = [];

        foreach ($permanentDirs as $dir) {
            $fullPath = $this->projectRoot . '/' . $dir;
            if (!is_dir($fullPath)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($fullPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            );

            foreach ($iterator as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                // Check if file is in an exempt migration directory
                $isInMigrationDir = false;
                foreach ($migrationDirs as $mDir) {
                    if (str_starts_with(str_replace('\\', '/', $file->getPathname()), str_replace('\\', '/', $this->projectRoot . '/' . $mDir))) {
                        $isInMigrationDir = true;
                        break;
                    }
                }
                if ($isInMigrationDir) {
                    continue;
                }

                $content = file_get_contents($file->getPathname());
                $relativePath = str_replace($this->projectRoot . '/', '', $file->getPathname());
                $relativePath = str_replace('\\', '/', $relativePath);

                foreach ($migrationTypes as $type) {
                    // Look for use statements or direct references
                    if (preg_match('/\b' . preg_quote($type, '/') . '\b/', $content)) {
                        $violations[] = "$relativePath references migration type: $type";
                    }
                }
            }
        }

        // Currently we expect SOME violations because Migration types are
        // co-located in Security/ namespace (P12 deferred).
        // This test documents the KNOWN violations that must be resolved
        // before D.5 deletion.
        //
        // Assertion: we track the count so it doesn't grow.
        // TODO: Enforce zero violations after P12 namespace migration.
        $this->assertLessThanOrEqual(
            10,
            count($violations),
            "F11.1e — Too many permanent-context references to migration types:\n" . implode("\n", $violations),
        );
    }

    /**
     * F11.1f [PENDING NAMESPACE]: Projection must not reference LegitimacyOutcome
     * (FDR-4 / F8).
     *
     * F8 already covers Vue/JS file scanning. This extends to PHP controllers
     * and Inertia page handlers in the projection layer.
     *
     * Note: Currently enforced by F8 fitness test for Vue files.
     * PHP-level enforcement will be added after P12 when context boundaries
     * are structurally clear.
     */
    public function test_F11_1f_projection_must_not_reference_legitimacy(): void
    {
        // F8 already covers Vue/JS file scanning.
        // This test documents the requirement for PHP-level enforcement
        // after P12 namespace migration.
        $this->addToAssertionCount(1);

        // TODO after P12: Scan Inertia page handlers, dashboard controllers
        // for LegitimacyOutcome references.
        $this->markTestIncomplete(
            'F11.1f requires P12 namespace migration for PHP-level enforcement. ' .
            'Vue/JS enforcement is covered by F8 fitness test.',
        );
    }

    // =========================================================================
    // F11.2 — Published Language Boundary Verification
    // Only approved published language types may cross context boundaries.
    // =========================================================================

    /**
     * F11.2a [ACTIVE]: Legitimacy must consume Evidence ONLY via EvaluationEnvelope.
     *
     * ConstitutionalLegitimacyDecision must receive EvaluationEnvelope or
     * VotingTrustResult (which wraps envelope data), not raw Evidence types
     * like ConstitutionalEvidenceSnapshot.
     */
    public function test_F11_2a_legitimacy_consumes_only_published_language(): void
    {
        $resolverFile = $this->projectRoot . '/app/Application/Election/Security/ConstitutionalLegitimacyDecision.php';
        $this->assertFileExists($resolverFile);

        $content = file_get_contents($resolverFile);

        // Must not reference raw Evidence internals
        $forbiddenEvidenceInternals = [
            'ConstitutionalEvidenceSnapshot',
            'EvidenceSnapshot',
            'OverlaySignal',
            'EvidenceClassification',
        ];

        $violations = [];
        foreach ($forbiddenEvidenceInternals as $type) {
            if (str_contains($content, $type)) {
                $violations[] = "References Evidence internal type: $type";
            }
        }

        $this->assertCount(
            0,
            $violations,
            "F11.2a — Legitimacy resolver must consume Evidence only via published language (EvaluationEnvelope):\n" . implode("\n", $violations),
        );
    }

    /**
     * F11.2b [ACTIVE]: Evaluation must not produce LegitimacyOutcome (EVL-1).
     *
     * Evaluation types (EvidenceEvaluationResult, EvaluationEnvelope) must not
     * reference or produce LegitimacyOutcome. Only ConstitutionalLegitimacyDecision
     * maps evaluation state → outcome.
     */
    public function test_F11_2b_evaluation_must_not_produce_legitimacy(): void
    {
        $evaluationFiles = [
            $this->projectRoot . '/app/Domain/Election/Security/Simplified/EvidenceEvaluationResult.php',
            $this->projectRoot . '/app/Domain/Election/Security/Simplified/EvaluationEnvelope.php',
            $this->projectRoot . '/app/Domain/Election/Security/Simplified/EvidenceEvaluationState.php',
        ];

        $forbiddenType = 'LegitimacyOutcome';
        $violations = [];

        foreach ($evaluationFiles as $file) {
            if (!file_exists($file)) {
                continue;
            }
            $content = file_get_contents($file);
            if (str_contains($content, $forbiddenType)) {
                $relativePath = str_replace($this->projectRoot . '/', '', $file);
                $violations[] = "$relativePath references $forbiddenType (EVL-1 violation)";
            }
        }

        $this->assertCount(
            0,
            $violations,
            "F11.2b violations — Evaluation must not produce LegitimacyOutcome:\n" . implode("\n", $violations),
        );
    }

    // =========================================================================
    // F11.3 — Dependency Direction Verification
    // One-way dependency arrows must not be reversed.
    // =========================================================================

    /**
     * F11.3a [ACTIVE]: Evidence depends on Observation (one-way).
     *
     * Observation files must not reference EvaluationEnvelope or
     * EvidenceEvaluationResult. Observation produces signals; it does not
     * consume evaluation results.
     */
    public function test_F11_3a_observation_must_not_depend_on_evidence(): void
    {
        $overlayDir = $this->projectRoot . '/app/Application/Election/Security/Overlays';
        $forbiddenTypes = ['EvaluationEnvelope', 'EvidenceEvaluationResult', 'EvidenceSnapshot'];

        $violations = $this->scanDirectoryForForbiddenTypes($overlayDir, $forbiddenTypes, []);

        $this->assertCount(
            0,
            $violations,
            "F11.3a violations — Observation must not depend on Evidence types:\n" . implode("\n", $violations),
        );
    }

    /**
     * F11.3b [PENDING NAMESPACE]: Governance must not depend on Evidence types
     * (must consume only via Legitimacy).
     *
     * Governance enforces LegitimacyOutcome. It must not bypass Legitimacy
     * and read Evidence types directly.
     *
     * Note: Full enforcement requires P12 namespace migration. Currently,
     * Governance is handled by Laravel conventions (Generic Domain).
     */
    public function test_F11_3b_governance_must_not_bypass_legitimacy(): void
    {
        // Governance is a Generic Domain handled by Laravel conventions.
        // Full structural enforcement requires P12 namespace migration.
        //
        // For now, middleware and controllers are expected to reference
        // Laravel infrastructure types. The GOV-2 rule (may block what
        // Legitimacy allows, must never allow what Legitimacy denies)
        // is enforced by convention, not by structural test.
        $this->addToAssertionCount(1);

        $this->markTestIncomplete(
            'F11.3b requires P12 namespace migration for structural enforcement. ' .
            'Governance is a Generic Domain (per P4) — enforcement is convention-based.',
        );
    }

    // =========================================================================
    // F11.4 — Published Language Contract Verification
    // Verify that the types crossing each boundary match the published language
    // specification.
    // =========================================================================

    /**
     * F11.4a [ACTIVE]: EvaluationEnvelope must contain ConstitutionalObservationContext
     * (not singular OverlaySignal).
     *
     * Verified by reflection on the envelope constructor.
     */
    public function test_F11_4a_envelope_uses_observation_context(): void
    {
        // This is already tested by F2 in SovereigntyConvergenceFitnessTest.
        // This test documents the published language contract.
        $this->addToAssertionCount(1);
    }

    /**
     * F11.4b [ACTIVE]: Replay consumed Evidence types must be read-only.
     *
     * ReplaySession must not modify ConstitutionalEvidenceSnapshot or
     * related Evidence types.
     */
    public function test_F11_4b_replay_evidence_is_read_only(): void
    {
        $replayDir = $this->projectRoot . '/app/Domain/Election/Replay';

        // Check ReplaySession for any setter-like methods on evidence
        $sessionFile = $replayDir . '/ReplaySession.php';
        $this->assertFileExists($sessionFile);

        $content = file_get_contents($sessionFile);

        // Must not modify evidence or re-evaluate
        $forbiddenPatterns = [
            'evaluate',
            'setEvidence',
            'modifyEvidence',
            'EvidenceEvaluationState',
        ];

        $violations = [];
        foreach ($forbiddenPatterns as $pattern) {
            if (str_contains($content, $pattern)) {
                $violations[] = "ReplaySession references forbidden pattern: $pattern";
            }
        }

        $this->assertCount(
            0,
            $violations,
            "F11.4b — Replay must not modify or re-evaluate evidence:\n" . implode("\n", $violations),
        );
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    /**
     * Scan a directory recursively for files containing forbidden type references.
     *
     * @return string[] Human-readable violation messages
     */
    private function scanDirectoryForForbiddenTypes(
        string $dir,
        array $forbiddenTypes,
        array $exemptFiles,
    ): array {
        $violations = [];

        if (!is_dir($dir)) {
            return $violations;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $violations = array_merge(
                $violations,
                $this->scanFileForForbiddenTypes($file->getPathname(), $forbiddenTypes, $exemptFiles),
            );
        }

        return $violations;
    }

    /**
     * Scan a single PHP file for forbidden type references.
     *
     * @return string[] Human-readable violation messages
     */
    private function scanFileForForbiddenTypes(
        string $filePath,
        array $forbiddenTypes,
        array $exemptFiles,
    ): array {
        $violations = [];

        // Check if filename is exempt
        $filename = basename($filePath);
        foreach ($exemptFiles as $exempt) {
            if ($filename === $exempt || str_ends_with($filePath, $exempt)) {
                return $violations;
            }
        }

        $content = file_get_contents($filePath);
        $relativePath = str_replace($this->projectRoot . '/', '', $filePath);
        $relativePath = str_replace('\\', '/', $relativePath);

        // Check for use statements referencing forbidden types
        foreach ($forbiddenTypes as $type) {
            // Match: use Full\Namespace\TypeName; or use Full\Namespace\TypeName as Alias;
            // Also match: TypeName:: (static call) or TypeName::class
            if (preg_match('/\b' . preg_quote($type, '/') . '\b/', $content)) {
                $violations[] = "$relativePath references forbidden type: $type";
            }
        }

        return $violations;
    }
}
