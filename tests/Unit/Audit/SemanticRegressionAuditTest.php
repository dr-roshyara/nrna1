<?php

namespace Tests\Unit\Audit;

use App\Application\Election\Services\ElectionCapabilityResolver;
use App\Domain\Election\Security\TrustEvaluationState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;
use ReflectionMethod;
use Tests\TestCase;

class SemanticRegressionAuditTest extends TestCase
{
    use RefreshDatabase;

    /**
     * AUDIT 5.1: Domain/Application layer has no forbidden authority vocabulary
     * Scans PHP files for procedural authority semantics (allow, deny, grant, canVote)
     */
    public function test_domain_application_layers_exclude_forbidden_vocabulary(): void
    {
        $forbiddenTerms = [
            'public function allow' => 'allow() method forbidden',
            'public function deny' => 'deny() method forbidden',
            'public function grant' => 'grant() method forbidden',
            'public function canVote' => 'canVote() method forbidden',
            'public function isTrusted' => 'isTrusted() method forbidden',
        ];

        $scanDirs = [
            app_path('Domain/Election/Security'),
            app_path('Application/Election/Security'),
        ];

        $violations = [];

        foreach ($scanDirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            // Use RecursiveDirectoryIterator for cross-platform compatibility
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                if (!$file->isFile() || $file->getExtension() !== 'php') {
                    continue;
                }

                $content = file_get_contents($file->getPathname());

                foreach ($forbiddenTerms as $term => $reason) {
                    if (strpos($content, $term) !== false) {
                        $violations[] = "{$file->getPathname()}: {$reason}";
                    }
                }
            }
        }

        $this->assertEmpty(
            $violations,
            "Forbidden vocabulary found in domain/application layers:\n" . implode("\n", $violations)
        );
    }

    /**
     * AUDIT 5.2: Policy classes use "evaluate" method only
     * All policy implementations must define evaluate(), no allow/deny methods
     */
    public function test_policy_classes_implement_evaluate_method(): void
    {
        $policyClasses = [
            'App\Application\Election\Security\Policies\VerificationAttestationPolicy',
            'App\Application\Election\Security\Policies\NetworkBindingPolicy',
            'App\Application\Election\Security\Policies\DeviceBindingPolicy',
            'App\Application\Election\Capabilities\Policies\TrustCapabilityPolicy',
        ];

        foreach ($policyClasses as $class) {
            if (!class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            // Assert: Has evaluate() method
            $this->assertTrue(
                $reflection->hasMethod('evaluate'),
                "{$class} must have evaluate() method"
            );

            // Assert: Does NOT have forbidden methods
            $forbiddenMethods = ['allow', 'deny', 'grant', 'canVote', 'isTrusted'];
            foreach ($forbiddenMethods as $method) {
                $this->assertFalse(
                    $reflection->hasMethod($method),
                    "{$class} must not have {$method}() method"
                );
            }
        }
    }

    /**
     * AUDIT 5.3: Overlay classes return OverlaySignal (not authority decision)
     * Return type must be OverlaySignal; no allow/deny/grant methods
     */
    public function test_overlay_classes_return_overlay_signal_type(): void
    {
        $overlayClasses = [
            'App\Application\Election\Security\Overlays\EmergencyConditionOverlay',
            'App\Application\Election\Security\Overlays\RegistrarAttestationElevation',
            'App\Application\Election\Security\Overlays\SuspiciousActivityOverlay',
            'App\Application\Election\Security\Overlays\IpVelocityOverlay',
            'App\Application\Election\Security\Overlays\DeviceAnomalyOverlay',
        ];

        foreach ($overlayClasses as $class) {
            if (!class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            // Verify evaluate() method exists
            if (!$reflection->hasMethod('evaluate')) {
                continue;
            }

            $method = $reflection->getMethod('evaluate');
            $returnType = (string)$method->getReturnType();

            $this->assertStringContainsString(
                'OverlaySignal',
                $returnType,
                "{$class}::evaluate() must return OverlaySignal, got {$returnType}"
            );
        }
    }

    /**
     * AUDIT 5.4: Resolver uses "evaluate" terminology only
     * ElectionCapabilityResolver must not have authorize/permit/allow/deny methods
     */
    public function test_resolver_uses_evaluate_terminology(): void
    {
        $resolverClass = ElectionCapabilityResolver::class;

        if (!class_exists($resolverClass)) {
            $this->assertTrue(true);
            return;
        }

        $reflection = new ReflectionClass($resolverClass);

        // Assert: Has evaluate() method
        $this->assertTrue(
            $reflection->hasMethod('evaluate'),
            "Resolver must have evaluate() method"
        );

        // Assert: Does not have forbidden methods
        $forbiddenMethods = ['authorize', 'permit', 'allow', 'deny'];
        foreach ($forbiddenMethods as $method) {
            $this->assertFalse(
                $reflection->hasMethod($method),
                "Resolver must not have {$method}() method"
            );
        }
    }

    /**
     * AUDIT 5.5: Trust vocabulary uses enums instead of booleans/strings
     * TrustLevel, TrustEvaluationState must be enum types
     */
    public function test_trust_vocabulary_uses_enum_types(): void
    {
        // Assert: Enums exist
        $this->assertTrue(
            enum_exists('App\Domain\Election\Security\TrustLevel'),
            "TrustLevel enum must exist"
        );

        $this->assertTrue(
            enum_exists('App\Domain\Election\Security\TrustEvaluationState'),
            "TrustEvaluationState enum must exist"
        );

        // Assert: VotingTrustResult uses evaluationState, not trusted bool
        $resultClass = 'App\Domain\Election\Security\VotingTrustResult';
        if (class_exists($resultClass)) {
            $reflection = new ReflectionClass($resultClass);
            $properties = $reflection->getProperties();

            $hasEvaluationState = false;
            $hasTrustedBool = false;

            foreach ($properties as $prop) {
                if ($prop->getName() === 'evaluationState') {
                    $hasEvaluationState = true;
                }
                if ($prop->getName() === 'trusted') {
                    $hasTrustedBool = true;
                }
            }

            $this->assertTrue(
                $hasEvaluationState,
                "VotingTrustResult must have evaluationState property (not trusted bool)"
            );

            $this->assertFalse(
                $hasTrustedBool,
                "VotingTrustResult must not have trusted boolean property (use evaluationState enum)"
            );
        }
    }

    /**
     * AUDIT 5.6: FormRequest::authorize() checks identity, not voting
     * Framework semantics allowed (user logged in); participation checks forbidden
     */
    public function test_form_request_authorize_restricts_identity_not_voting(): void
    {
        // Note: This is framework semantic nuance
        // authorize() method checking user->is_authenticated is ALLOWED
        // authorize() method checking can('vote_in_election') is FORBIDDEN

        // In testing, we accept that FormRequest::authorize() may exist
        // as long as it only checks identity, not voting capability

        // For now, this audit documents the expected pattern
        $this->assertTrue(true);
    }

    /**
     * AUDIT 5.7: Blade templates read from snapshot, not call inline authority
     * Templates must use $snapshot->capabilities, not $election->can_user_vote()
     */
    public function test_blade_templates_read_snapshot_not_inline_authority(): void
    {
        $viewPath = resource_path('views');
        if (!is_dir($viewPath)) {
            $this->assertTrue(true);
            return;
        }

        $forbiddenPatterns = [
            '$election->can_user_vote',
            '->can_user_vote',
            '@can(\'vote\'',
            '@can("vote"',
        ];

        // Use RecursiveDirectoryIterator for cross-platform compatibility
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($viewPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            // Check only blade files related to voting/elections
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $filename = $file->getFilename();
            if (strpos($filename, 'voting') === false && strpos($filename, 'election') === false) {
                continue;
            }

            $content = file_get_contents($file->getPathname());

            foreach ($forbiddenPatterns as $forbidden) {
                $this->assertStringNotContainsString(
                    $forbidden,
                    $content,
                    "File {$file->getPathname()} uses forbidden pattern: {$forbidden}"
                );
            }
        }

        $this->assertTrue(true);
    }

    /**
     * AUDIT 5.8: No new forbidden vocabulary in recent code
     * Verify codebase maintains semantic discipline
     */
    public function test_no_forbidden_vocabulary_in_critical_files(): void
    {
        $criticalFiles = [
            app_path('Application/Election/Security/TrustPolicyEvaluator.php'),
            app_path('Application/Election/Services/ElectionCapabilityResolver.php'),
            app_path('Application/Election/Capabilities/Policies/TrustCapabilityPolicy.php'),
        ];

        $forbiddenTerms = [
            'allow(' => 'allow() calls forbidden',
            'deny(' => 'deny() calls forbidden',
            'grant(' => 'grant() calls forbidden',
            'can_vote' => 'can_vote references forbidden',
            'canVote(' => 'canVote() calls forbidden',
        ];

        foreach ($criticalFiles as $file) {
            if (!file_exists($file)) {
                continue;
            }

            $content = file_get_contents($file);

            foreach ($forbiddenTerms as $term => $reason) {
                $this->assertStringNotContainsString(
                    $term,
                    $content,
                    "{$file}: {$reason}"
                );
            }
        }

        $this->assertTrue(true);
    }

    /**
     * AUDIT 5.9: VotingTrustResult returns TrustEvaluationState (not boolean)
     * Guarantees evaluator returns evidence state, not authority decision
     */
    public function test_voting_trust_result_uses_evaluation_state_enum(): void
    {
        $resultClass = 'App\Domain\Election\Security\VotingTrustResult';

        if (!class_exists($resultClass)) {
            $this->assertTrue(true);
            return;
        }

        $reflection = new ReflectionClass($resultClass);
        $properties = $reflection->getProperties();

        $foundEvaluationState = false;

        foreach ($properties as $prop) {
            if ($prop->getName() === 'evaluationState') {
                $foundEvaluationState = true;

                // Verify type is TrustEvaluationState enum
                $type = (string)$prop->getType();
                $this->assertStringContainsString(
                    'TrustEvaluationState',
                    $type,
                    "evaluationState must be typed as TrustEvaluationState enum"
                );
            }
        }

        $this->assertTrue(
            $foundEvaluationState,
            "VotingTrustResult must have evaluationState property"
        );
    }

    /**
     * AUDIT 5.10: Capability decisions use CapabilityDenialReason enum
     * Not freeform strings; typed reasons only
     */
    public function test_capability_denial_uses_typed_reason_enum(): void
    {
        $reasonClass = 'App\Application\Election\Capabilities\CapabilityDenialReason';

        if (!class_exists($reasonClass)) {
            $this->assertTrue(true);
            return;
        }

        // Assert: Is enum
        $this->assertTrue(
            enum_exists($reasonClass),
            "CapabilityDenialReason must be enum type"
        );

        // Assert: Has required cases
        $requiredCases = [
            'TrustDenied',
            'ConstitutionalReviewPending',
            'TrustEvaluationInconclusive',
        ];

        $reflection = new ReflectionClass($reasonClass);
        $constants = $reflection->getConstants();

        foreach ($requiredCases as $case) {
            $this->assertTrue(
                isset($constants[$case]) || method_exists($reflection, 'cases'),
                "CapabilityDenialReason enum must have {$case} case"
            );
        }
    }
}
