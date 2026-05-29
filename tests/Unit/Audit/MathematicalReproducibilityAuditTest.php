<?php

namespace Tests\Unit\Audit;

use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use Tests\TestCase;

class MathematicalReproducibilityAuditTest extends TestCase
{
    /**
     * AUDIT 4.13: Trust Elevation Thresholds Are Integer-Only Deterministic
     *
     * Proves that threshold calculations produce identical results across runs
     * with no floating-point rounding ambiguity.
     *
     * Priority 4: Mathematical Reproducibility (Deterministic Governance)
     */
    public function test_trust_elevation_thresholds_are_integer_deterministic(): void
    {
        // Test cases: (maxVotesPerIp) → expected thresholds for each trust level
        $testCases = [
            6 => [
                'RegistrarAttested' => 12,      // 6 * 2 = 12
                'ContinuityVerified' => 9,      // floor(6 * 1.5) = 9
                'Attested' => 6,                // 6
                'Unverified' => 3,              // floor(6 / 2) = 3
            ],
            10 => [
                'RegistrarAttested' => 20,      // 10 * 2 = 20
                'ContinuityVerified' => 15,     // floor(10 * 1.5) = 15
                'Attested' => 10,               // 10
                'Unverified' => 5,              // floor(10 / 2) = 5
            ],
            7 => [
                'RegistrarAttested' => 14,      // 7 * 2 = 14
                'ContinuityVerified' => 10,     // floor(7 * 1.5) = 10 (not 10.5)
                'Attested' => 7,                // 7
                'Unverified' => 3,              // floor(7 / 2) = 3 (not 3.5, minimum 1)
            ],
            1 => [
                'RegistrarAttested' => 2,       // 1 * 2 = 2
                'ContinuityVerified' => 1,      // floor(1 * 1.5) = 1
                'Attested' => 1,                // 1
                'Unverified' => 1,              // floor(1 / 2) = 0, but max(1, 0) = 1
            ],
        ];

        foreach ($testCases as $maxVotesPerIp => $expectedThresholds) {
            $evidence = new NetworkTrustEvidence(
                currentIpHash: hash('sha256', '192.168.1.1'),
                registeredIpHash: null,
                whitelist: null,
                maxVotesPerIp: $maxVotesPerIp,
                votesFromThisIp: 0,
                restrictionEnabled: true,
                bindingStrategy: 'ip_strict',
            );

            // Calculate 3 times to verify determinism
            $thresholds = [];
            for ($i = 0; $i < 3; $i++) {
                $thresholds[$i] = [
                    'RegistrarAttested' => $evidence->remainingVotes(TrustLevel::RegistrarAttested) + $evidence->votesFromThisIp,
                    'ContinuityVerified' => $evidence->remainingVotes(TrustLevel::ContinuityVerified) + $evidence->votesFromThisIp,
                    'Attested' => $evidence->remainingVotes(TrustLevel::Attested) + $evidence->votesFromThisIp,
                    'Unverified' => $evidence->remainingVotes(TrustLevel::Unverified) + $evidence->votesFromThisIp,
                ];
            }

            // All three calculations must be identical
            $this->assertEquals(
                $thresholds[0],
                $thresholds[1],
                "Threshold calculation diverged between runs 1 and 2 (maxVotesPerIp={$maxVotesPerIp})"
            );

            $this->assertEquals(
                $thresholds[1],
                $thresholds[2],
                "Threshold calculation diverged between runs 2 and 3 (maxVotesPerIp={$maxVotesPerIp})"
            );

            // All must match expected values
            $this->assertEquals(
                $expectedThresholds,
                $thresholds[0],
                "Threshold calculations do not match expected values (maxVotesPerIp={$maxVotesPerIp})"
            );
        }
    }

    /**
     * AUDIT 4.14: No Floating-Point Operations in Threshold Paths
     *
     * Scans code for floor(), ceil(), round() calls with division or multiplication
     * operations that could introduce rounding ambiguity.
     */
    public function test_no_floating_point_threshold_operations(): void
    {
        $forbiddenPatterns = [
            'floor(' => 'floor() forbidden in thresholds — use intdiv()',
            'ceil(' => 'ceil() forbidden in thresholds — use intdiv() with adjustment',
            'round(' => 'round() forbidden in thresholds — use intdiv()',
        ];

        $scanFiles = [
            app_path('Domain/Election/Security/NetworkTrustEvidence.php'),
        ];

        $violations = [];

        foreach ($scanFiles as $file) {
            if (!file_exists($file)) {
                continue;
            }

            $content = file_get_contents($file);

            foreach ($forbiddenPatterns as $pattern => $reason) {
                if (strpos($content, $pattern) !== false) {
                    $violations[] = "{$file}: {$reason}";
                }
            }
        }

        $this->assertEmpty(
            $violations,
            "Floating-point operations found in threshold code:\n" . implode("\n", $violations)
        );
    }

    /**
     * AUDIT 4.15: Threshold Reproducibility Across Multiple Invocations
     *
     * Verifies that the same NetworkTrustEvidence instance always produces
     * identical threshold values when queried multiple times.
     */
    public function test_same_evidence_always_produces_same_threshold_values(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: hash('sha256', '192.168.1.99'),
            registeredIpHash: null,
            whitelist: null,
            maxVotesPerIp: 13,
            votesFromThisIp: 5,
            restrictionEnabled: true,
            bindingStrategy: 'ip_strict',
        );

        // Query same thresholds 10 times
        $results = [];
        for ($i = 0; $i < 10; $i++) {
            $results[$i] = [
                'remaining_unverified' => $evidence->remainingVotes(TrustLevel::Unverified),
                'remaining_attested' => $evidence->remainingVotes(TrustLevel::Attested),
                'remaining_continuity' => $evidence->remainingVotes(TrustLevel::ContinuityVerified),
                'remaining_registrar' => $evidence->remainingVotes(TrustLevel::RegistrarAttested),
                'exceeds_unverified' => $evidence->exceedsLimit(TrustLevel::Unverified),
                'exceeds_attested' => $evidence->exceedsLimit(TrustLevel::Attested),
                'exceeds_continuity' => $evidence->exceedsLimit(TrustLevel::ContinuityVerified),
                'exceeds_registrar' => $evidence->exceedsLimit(TrustLevel::RegistrarAttested),
            ];
        }

        // All results must be identical
        for ($i = 1; $i < 10; $i++) {
            $this->assertEquals(
                $results[0],
                $results[$i],
                "Threshold values diverged between invocation 1 and invocation {$i}"
            );
        }
    }

    /**
     * AUDIT 4.16: Trust Elevation Thresholds Respect Integer Boundaries
     *
     * Verifies that edge cases (odd numbers, minimum values) produce
     * correct integer boundaries without floating-point rounding anomalies.
     */
    public function test_trust_elevation_respects_integer_boundaries(): void
    {
        $edgeCases = [
            // maxVotesPerIp => [Unverified, Attested, ContinuityVerified, RegistrarAttested]
            1 => [1, 1, 1, 2],
            2 => [1, 2, 3, 4],
            3 => [1, 3, 4, 6],
            5 => [2, 5, 7, 10],
            7 => [3, 7, 10, 14],
            11 => [5, 11, 16, 22],
        ];

        foreach ($edgeCases as $maxVotes => [$expectedUnverified, $expectedAttested, $expectedContinuity, $expectedRegistrar]) {
            $evidence = new NetworkTrustEvidence(
                currentIpHash: hash('sha256', '192.168.1.1'),
                registeredIpHash: null,
                whitelist: null,
                maxVotesPerIp: $maxVotes,
                votesFromThisIp: 0,
                restrictionEnabled: true,
                bindingStrategy: 'ip_strict',
            );

            // Get thresholds (remaining votes when votesFromThisIp=0)
            $unverified = $evidence->remainingVotes(TrustLevel::Unverified);
            $attested = $evidence->remainingVotes(TrustLevel::Attested);
            $continuity = $evidence->remainingVotes(TrustLevel::ContinuityVerified);
            $registrar = $evidence->remainingVotes(TrustLevel::RegistrarAttested);

            $this->assertEquals(
                $expectedUnverified,
                $unverified,
                "Unverified threshold mismatch (maxVotesPerIp={$maxVotes})"
            );
            $this->assertEquals(
                $expectedAttested,
                $attested,
                "Attested threshold mismatch (maxVotesPerIp={$maxVotes})"
            );
            $this->assertEquals(
                $expectedContinuity,
                $continuity,
                "ContinuityVerified threshold mismatch (maxVotesPerIp={$maxVotes})"
            );
            $this->assertEquals(
                $expectedRegistrar,
                $registrar,
                "RegistrarAttested threshold mismatch (maxVotesPerIp={$maxVotes})"
            );
        }
    }

    /**
     * AUDIT 4.17: All Threshold Values Are Positive Integers
     *
     * Verifies that threshold calculations never produce negative, zero
     * (when not intended), or fractional values.
     */
    public function test_threshold_values_are_positive_integers(): void
    {
        $testRanges = [1, 2, 5, 10, 100, 1000];

        foreach ($testRanges as $maxVotesPerIp) {
            $evidence = new NetworkTrustEvidence(
                currentIpHash: hash('sha256', '192.168.1.1'),
                registeredIpHash: null,
                whitelist: null,
                maxVotesPerIp: $maxVotesPerIp,
                votesFromThisIp: 0,
                restrictionEnabled: true,
                bindingStrategy: 'ip_strict',
            );

            $unverified = $evidence->remainingVotes(TrustLevel::Unverified);
            $attested = $evidence->remainingVotes(TrustLevel::Attested);
            $continuity = $evidence->remainingVotes(TrustLevel::ContinuityVerified);
            $registrar = $evidence->remainingVotes(TrustLevel::RegistrarAttested);

            // All must be integers (no fractional parts)
            $this->assertIsInt($unverified, "Unverified threshold not integer");
            $this->assertIsInt($attested, "Attested threshold not integer");
            $this->assertIsInt($continuity, "ContinuityVerified threshold not integer");
            $this->assertIsInt($registrar, "RegistrarAttested threshold not integer");

            // All must be positive (except Unverified has minimum 1 guarantee)
            $this->assertGreaterThanOrEqual(1, $unverified, "Unverified threshold below minimum");
            $this->assertGreaterThanOrEqual($maxVotesPerIp, $attested, "Attested threshold below base");
            $this->assertGreaterThanOrEqual(
                intdiv($maxVotesPerIp * 3, 2),
                $continuity,
                "ContinuityVerified threshold below expected"
            );
            $this->assertGreaterThanOrEqual(
                $maxVotesPerIp * 2,
                $registrar,
                "RegistrarAttested threshold below 2x base"
            );
        }
    }
}
