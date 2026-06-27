<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\Simplified\ElectionConstitutionHasher;
use App\Domain\Election\Security\Simplified\ElectionConstitutionSnapshot;
use App\Domain\Election\Security\Simplified\EvaluationEnvelope;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationResult;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationState;
use App\Domain\Election\Security\Simplified\EvidenceSnapshot;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use App\Domain\Election\Security\SovereigntyConvergenceFitnessFunction;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * SovereigntyConvergenceFitnessTest
 *
 * Machine-enforced constitutional invariants for sovereignty convergence.
 * Each fitness function (F1-F6) protects against regression of D.R.2, D.R.3,
 * and constitutional topology semantics.
 *
 * CONSTITUTIONAL LAW:
 * Violation means constitutional regression — NOT a test failure.
 * Each fitness function blocks sovereignty transfer until resolved.
 */
class SovereigntyConvergenceFitnessTest extends TestCase
{
    // =========================================================================
    // F1 — No Singular Sovereignty Fields
    // Forbidden: property/field names that imply singular authority semantics.
    // Only ConstitutionalObservationContext is canonical.
    // =========================================================================

    /** @dataProvider forbiddenSingularFieldProvider */
    public function test_F1_no_singular_authority_fields(string $className, string $forbiddenField): void
    {
        $reflection = new ReflectionClass($className);

        // Check public properties
        foreach ($reflection->getProperties() as $prop) {
            $this->assertDoesNotMatchRegularExpression(
                "/$forbiddenField/i",
                $prop->getName(),
                sprintf(
                    'F1 violation: %s::$%s contains forbidden singular authority field pattern "%s"',
                    $className,
                    $prop->getName(),
                    $forbiddenField,
                ),
            );
        }
    }

    public static function forbiddenSingularFieldProvider(): array
    {
        $simplifiedClasses = [
            EvaluationEnvelope::class,
            ConstitutionalObservationContext::class,
            OverlaySignal::class,
            EvidenceSnapshot::class,
            EvidenceEvaluationResult::class,
        ];

        $forbiddenPatterns = ['overlaySignal', 'primarySignal', 'strongestSignal', 'highestSeverity'];

        $cases = [];
        foreach ($simplifiedClasses as $class) {
            foreach ($forbiddenPatterns as $pattern) {
                $cases["$class::$pattern"] = [$class, $pattern];
            }
        }
        return $cases;
    }

    // =========================================================================
    // F2 — Envelope Plurality Invariant
    // Every EvaluationEnvelope must hold ConstitutionalObservationContext.
    // Singular OverlaySignal parameter implies prioritization and authority.
    // =========================================================================

    public function test_F2_envelope_plurality_uses_observation_context(): void
    {
        $reflection = new ReflectionClass(EvaluationEnvelope::class);
        $constructor = $reflection->getConstructor();
        $params = $constructor->getParameters();

        // Find the observations parameter
        $observationsParam = null;
        foreach ($params as $param) {
            if ($param->getName() === 'observations') {
                $observationsParam = $param;
                break;
            }
        }

        $this->assertNotNull($observationsParam, 'EvaluationEnvelope must have an $observations parameter');

        $type = $observationsParam->getType();
        $this->assertNotNull($type, '$observations parameter must be type-hinted');
        $this->assertSame(
            ConstitutionalObservationContext::class,
            $type->getName(),
            'EvaluationEnvelope::$observations must be ConstitutionalObservationContext, not singular OverlaySignal',
        );
    }

    public function test_F2_envelope_plurality_preserves_multiple_signals(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('signal_a', 'First signal', []),
            OverlaySignal::evidenceInconsistent('signal_b', 'Second signal', []),
            OverlaySignal::attestationPresent('signal_c', 'Third signal', []),
        ]);

        $envelope = new EvaluationEnvelope(
            new EvidenceEvaluationResult(
                EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                \App\Domain\Election\Security\Simplified\EvidenceClassification::Attested,
                \App\Domain\Election\Security\Simplified\EvaluationReasonCode::ALL_POLICIES_PASSED,
                [],
                [],
            ),
            $context,
            new EvidenceSnapshot(
                EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                \App\Domain\Election\Security\Simplified\EvidenceClassification::Attested,
                true, true, null, '',
                [],
            ),
        );

        $this->assertCount(3, $envelope->observations->all());
    }

    // =========================================================================
    // F3 — Replay Hash Stability
    // Identical constitutional snapshots must produce identical integrity hashes.
    // =========================================================================

    public function test_F3_identical_snapshots_produce_identical_hash(): void
    {
        $snapshot1 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'per_ip',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'fingerprint_hash',
            ballotAuthorizationProtocol: 'dual_code',
            verificationRequired: true,
        );

        $snapshot2 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'per_ip',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'fingerprint_hash',
            ballotAuthorizationProtocol: 'dual_code',
            verificationRequired: true,
        );

        $hash1 = ElectionConstitutionHasher::hash($snapshot1);
        $hash2 = ElectionConstitutionHasher::hash($snapshot2);

        $this->assertSame($hash1, $hash2, 'F3 violation: identical snapshots produced different hashes');
    }

    public function test_F3_different_snapshots_produce_different_hash(): void
    {
        $snapshot1 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'per_ip',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'fingerprint_hash',
            ballotAuthorizationProtocol: 'dual_code',
            verificationRequired: true,
        );

        $snapshot2 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'per_ip',
            maxVotesPerIp: 10,
            deviceBindingStrategy: 'fingerprint_hash',
            ballotAuthorizationProtocol: 'dual_code',
            verificationRequired: true,
        );

        $hash1 = ElectionConstitutionHasher::hash($snapshot1);
        $hash2 = ElectionConstitutionHasher::hash($snapshot2);

        $this->assertNotSame($hash1, $hash2, 'F3 violation: different snapshots should produce different hashes');
    }

    // =========================================================================
    // F4 — Resolver Exclusivity
    // Legitimacy derivation must be confined to the constitutional resolver path.
    // Controllers, middleware, services, and queue workers must NOT derive
    // LegitimacyOutcome or TrustEvaluationState for enforcement purposes.
    //
    // ConstitutionalLegitimacyDecision is the EXCLUSIVE resolver authority.
    // All legitimacy outcomes MUST flow through this class.
    //
    // Exceptions (telemetry-only, not enforcement):
    // - VoteController — receives (not derives) LegitimacyOutcome from
    //   ConstitutionalLegitimacyDecision for divergence tracking only
    // - SecurityEventRecorder — event recording, not enforcement
    // =========================================================================

    public function test_F4_legitimacy_outcome_not_used_outside_constitutional_path(): void
    {
        // Approved locations for LegitimacyOutcome reference.
        // These are the constitutional path + telemetry exceptions.
        $constitutionalPrefixes = [
            'app/Domain/Election/Security/',
            'app/Application/Election/Security/',
            'app/Application/Election/Capabilities/Policies/',
        ];

        // Explicit approved files outside the constitutional path prefixes
        $approvedExplicit = [
            'app/Http/Controllers/VoteController.php', // divergence telemetry only — NOT enforcement
        ];

        $projectRoot = dirname(__DIR__, 5);

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($projectRoot . '/app', \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        $violations = [];
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = str_replace($projectRoot . '/', '', $file->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath);

            $content = file_get_contents($file->getPathname());

            if (!str_contains($content, 'LegitimacyOutcome')) {
                continue;
            }

            // Check if under a constitutional path prefix
            $isUnderPrefix = false;
            foreach ($constitutionalPrefixes as $prefix) {
                if (str_starts_with($relativePath, $prefix)) {
                    $isUnderPrefix = true;
                    break;
                }
            }

            // Check explicit approved list
            $isExplicit = false;
            foreach ($approvedExplicit as $approved) {
                if (str_ends_with($relativePath, $approved)) {
                    $isExplicit = true;
                    break;
                }
            }

            if (!$isUnderPrefix && !$isExplicit) {
                $violations[] = $relativePath;
            }
        }

        $this->assertCount(
            0,
            $violations,
            'F4 violation: LegitimacyOutcome used outside constitutional path in: ' . implode(', ', $violations),
        );
    }

    public function test_F4_trust_evaluation_state_not_used_outside_constitutional_path(): void
    {
        // Approved locations for TrustEvaluationState reference
        $constitutionalPrefixes = [
            'app/Domain/Election/Security/',
            'app/Application/Election/Security/',
            'app/Application/Election/Capabilities/Policies/',
        ];

        $projectRoot = dirname(__DIR__, 5);

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($projectRoot . '/app', \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        $violations = [];
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = str_replace($projectRoot . '/', '', $file->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath);

            $content = file_get_contents($file->getPathname());

            if (!str_contains($content, 'TrustEvaluationState')) {
                continue;
            }

            $isUnderPrefix = false;
            foreach ($constitutionalPrefixes as $prefix) {
                if (str_starts_with($relativePath, $prefix)) {
                    $isUnderPrefix = true;
                    break;
                }
            }

            if (!$isUnderPrefix) {
                $violations[] = $relativePath;
            }
        }

        $this->assertCount(
            0,
            $violations,
            'F4 violation: TrustEvaluationState used outside constitutional path in: ' . implode(', ', $violations),
        );
    }

    // =========================================================================
    // F5 — Observation Preservation
    // Adding observations must not erase lineage or collapse plurality.
    // Sovereign monotonicity: more evidence must not weaken insufficiency.
    // =========================================================================

    public function test_F5_adding_observations_preserves_count(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('o1', 'Signal one', []),
        ]);

        $this->assertCount(1, $context->observations);

        $largerContext = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('o1', 'Signal one', []),
            OverlaySignal::evidenceInconsistent('o2', 'Signal two', []),
            OverlaySignal::attestationPresent('o3', 'Signal three', []),
        ]);

        $this->assertCount(3, $largerContext->observations);
        $this->assertSame('o1', $largerContext->observations[0]->overlayIdentifier);
        $this->assertSame('o2', $largerContext->observations[1]->overlayIdentifier);
        $this->assertSame('o3', $largerContext->observations[2]->overlayIdentifier);
    }

    public function test_F5_observations_property_is_readonly(): void
    {
        $reflection = new ReflectionClass(ConstitutionalObservationContext::class);
        $property = $reflection->getProperty('observations');

        $this->assertTrue($property->isReadOnly());
    }

    // =========================================================================
    // F6 — Deterministic Convergence
    // Same evidence → same legitimacy outcome, independent of topology.
    // =========================================================================

    public function test_F6_same_evidence_produces_same_hash(): void
    {
        $constitutionData = [
            'networkBindingStrategy' => 'per_ip',
            'maxVotesPerIp' => 6,
            'deviceBindingStrategy' => 'fingerprint_hash',
            'ballotAuthorizationProtocol' => 'dual_code',
            'verificationRequired' => true,
        ];

        $snapshot1 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: $constitutionData['networkBindingStrategy'],
            maxVotesPerIp: $constitutionData['maxVotesPerIp'],
            deviceBindingStrategy: $constitutionData['deviceBindingStrategy'],
            ballotAuthorizationProtocol: $constitutionData['ballotAuthorizationProtocol'],
            verificationRequired: $constitutionData['verificationRequired'],
        );

        $snapshot2 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: $constitutionData['networkBindingStrategy'],
            maxVotesPerIp: $constitutionData['maxVotesPerIp'],
            deviceBindingStrategy: $constitutionData['deviceBindingStrategy'],
            ballotAuthorizationProtocol: $constitutionData['ballotAuthorizationProtocol'],
            verificationRequired: $constitutionData['verificationRequired'],
        );

        // Hash determinism: same constitutional snapshot → same hash
        $hash1 = ElectionConstitutionHasher::hash($snapshot1);
        $hash2 = ElectionConstitutionHasher::hash($snapshot2);

        $this->assertSame($hash1, $hash2);
    }

    // =========================================================================
    // F10 — Legitimacy Derivation Restriction
    // Only ConstitutionalLegitimacyDecision may map TrustEvaluationState to
    // LegitimacyOutcome. No fromTrustState() method or equivalent mapping
    // may exist outside the resolver.
    //
    // DD.3a enforcement: LegitimacyOutcome::fromTrustState() has been removed
    // from the enum. The mapping is inlined in ConstitutionalLegitimacyDecision.
    // This test verifies no equivalent derivation method exists elsewhere.
    // =========================================================================

    public function test_F10_no_from_trust_state_outside_resolver(): void
    {
        $projectRoot = dirname(__DIR__, 5);

        // Scan for any LegitimacyOutcome::fromTrustState pattern in non-doc files
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($projectRoot . '/app', \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        $violations = [];
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = str_replace($projectRoot . '/', '', $file->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath);

            $content = file_get_contents($file->getPathname());

            if (str_contains($content, 'fromTrustState')) {
                $violations[] = $relativePath;
            }
        }

        // LegitimacyOutcome.php is approved — it contains a doc comment
        // explaining that fromTrustState was removed as a DD.3a enforcement.
        $approved = [
            'app/Domain/Election/Security/LegitimacyOutcome.php',
            'app/Domain/Election/Security/SovereigntyConvergenceFitnessFunction.php', // doc-only reference
        ];
        $realViolations = array_filter($violations, function (string $path) use ($approved): bool {
            foreach ($approved as $a) {
                if (str_ends_with($path, $a)) {
                    return false;
                }
            }
            return true;
        });

        $this->assertCount(
            0,
            $realViolations,
            'F10 violation: fromTrustState pattern detected outside approved files: ' . implode(', ', $realViolations),
        );
    }

    public function test_F10_no_trust_state_to_outcome_mapping_outside_resolver(): void
    {
        $projectRoot = dirname(__DIR__, 5);

        // Scan for direct TrustEvaluationState → LegitimacyOutcome matching in non-resolver files.
        // Files that co-reference both types but aren't the resolver may be performing
        // the mapping outside the constitutional derivation path.
        $approvedFiles = [
            'app/Domain/Election/Security/LegitimacyOutcome.php',
            'app/Application/Election/Security/ConstitutionalLegitimacyDecision.php',
            'app/Domain/Election/Security/SovereigntyConvergenceFitnessFunction.php', // doc-only co-reference
        ];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($projectRoot . '/app', \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        $violations = [];
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = str_replace($projectRoot . '/', '', $file->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath);

            // Skip approved files
            $isApproved = false;
            foreach ($approvedFiles as $approved) {
                if (str_ends_with($relativePath, $approved)) {
                    $isApproved = true;
                    break;
                }
            }
            if ($isApproved) {
                continue;
            }

            $content = file_get_contents($file->getPathname());

            // Check for co-occurrence of both types — potential mapping outside resolver
            if (str_contains($content, 'TrustEvaluationState') && str_contains($content, 'LegitimacyOutcome')) {
                $violations[] = $relativePath;
            }
        }

        $this->assertCount(
            0,
            $violations,
            'F10 violation: files outside resolver co-reference TrustEvaluationState and LegitimacyOutcome: ' . implode(', ', $violations),
        );
    }

    // =========================================================================
    // Fitness function canonical identifiers — verify enum completeness
    // =========================================================================

    public function test_fitness_function_enum_has_ten_cases(): void
    {
        $cases = SovereigntyConvergenceFitnessFunction::cases();

        $this->assertCount(10, $cases);
    }

    public function test_F4_F5_F6_F10_block_transfer(): void
    {
        $this->assertFalse(SovereigntyConvergenceFitnessFunction::F1_NoSingularAuthorityFields->blocksTransfer());
        $this->assertFalse(SovereigntyConvergenceFitnessFunction::F2_EnvelopePlurality->blocksTransfer());
        $this->assertFalse(SovereigntyConvergenceFitnessFunction::F3_ReplayHashStability->blocksTransfer());
        $this->assertTrue(SovereigntyConvergenceFitnessFunction::F4_ResolverExclusivity->blocksTransfer());
        $this->assertTrue(SovereigntyConvergenceFitnessFunction::F5_ObservationPreservation->blocksTransfer());
        $this->assertTrue(SovereigntyConvergenceFitnessFunction::F6_DeterministicConvergence->blocksTransfer());
        $this->assertFalse(SovereigntyConvergenceFitnessFunction::F7_NoControllerLegitimacy->blocksTransfer());
        $this->assertFalse(SovereigntyConvergenceFitnessFunction::F8_NoProjectionLegitimacy->blocksTransfer());
        $this->assertFalse(SovereigntyConvergenceFitnessFunction::F9_ReplayDeterminism->blocksTransfer());
        $this->assertTrue(SovereigntyConvergenceFitnessFunction::F10_LegitimacyDerivationOnly->blocksTransfer());
    }
}
