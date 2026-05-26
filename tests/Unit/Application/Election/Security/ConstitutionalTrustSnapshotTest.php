<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\ConstitutionalTrustSnapshot;
use App\Domain\Election\Security\TrustLevel;
use PHPUnit\Framework\TestCase;

class ConstitutionalTrustSnapshotTest extends TestCase
{
    public function test_snapshot_is_readonly(): void
    {
        $snapshot = new ConstitutionalTrustSnapshot(
            trusted: true,
            trustLevel: TrustLevel::Attested,
            authorizationProtocol: 'single_code',
            requiresViewToken: false,
            requiresSeparateCommit: false,
            attestationValid: true,
            attestationSource: 'session',
            continuityPreserved: true,
            activeOverlay: null,
            overlayInfluence: null,
            denialReason: '',
            trustProvenance: ['verification_attestation_policy' => 'passed_attested'],
        );

        $this->assertTrue($snapshot->trusted);
        $this->assertEquals(TrustLevel::Attested, $snapshot->trustLevel);
        $this->assertFalse($snapshot->requiresViewToken);
    }

    public function test_split_authorization_protocol_flags(): void
    {
        $snapshot = new ConstitutionalTrustSnapshot(
            trusted: true,
            trustLevel: TrustLevel::Attested,
            authorizationProtocol: 'dual_code',
            requiresViewToken: true,
            requiresSeparateCommit: true,
            attestationValid: true,
            attestationSource: 'session',
            continuityPreserved: true,
            activeOverlay: null,
            overlayInfluence: null,
            denialReason: '',
            trustProvenance: [],
        );

        $this->assertTrue($snapshot->requiresViewToken);
        $this->assertTrue($snapshot->requiresSeparateCommit);
    }

    public function test_denial_reason_populated(): void
    {
        $snapshot = new ConstitutionalTrustSnapshot(
            trusted: false,
            trustLevel: TrustLevel::Unverified,
            authorizationProtocol: 'single_code',
            requiresViewToken: false,
            requiresSeparateCommit: false,
            attestationValid: false,
            attestationSource: 'none',
            continuityPreserved: true,
            activeOverlay: null,
            overlayInfluence: null,
            denialReason: 'verification_required',
            trustProvenance: [],
        );

        $this->assertFalse($snapshot->trusted);
        $this->assertEquals('verification_required', $snapshot->denialReason);
    }

    public function test_trust_provenance_contains_policy_outcomes(): void
    {
        $provenance = [
            'verification_attestation_policy' => 'passed_attested',
            'network_binding_policy' => ['outcome' => 'passed', 'remaining_votes' => 5],
            'device_binding_policy' => 'passed_exact_match',
        ];

        $snapshot = new ConstitutionalTrustSnapshot(
            trusted: true,
            trustLevel: TrustLevel::Attested,
            authorizationProtocol: 'single_code',
            requiresViewToken: false,
            requiresSeparateCommit: false,
            attestationValid: true,
            attestationSource: 'session',
            continuityPreserved: true,
            activeOverlay: null,
            overlayInfluence: null,
            denialReason: '',
            trustProvenance: $provenance,
        );

        $this->assertArrayHasKey('verification_attestation_policy', $snapshot->trustProvenance);
        $this->assertEquals('passed_attested', $snapshot->trustProvenance['verification_attestation_policy']);
    }

    public function test_snapshot_no_behavior_methods(): void
    {
        $snapshot = new ConstitutionalTrustSnapshot(
            trusted: true,
            trustLevel: TrustLevel::Attested,
            authorizationProtocol: 'single_code',
            requiresViewToken: false,
            requiresSeparateCommit: false,
            attestationValid: true,
            attestationSource: 'session',
            continuityPreserved: true,
            activeOverlay: null,
            overlayInfluence: null,
            denialReason: '',
            trustProvenance: [],
        );

        // Invariant 6: no recalculate(), no grantVotingAccess(), no authority methods
        $this->assertFalse(method_exists($snapshot, 'recalculate'));
        $this->assertFalse(method_exists($snapshot, 'grantVotingAccess'));
        $this->assertFalse(method_exists($snapshot, 'canVote'));
        $this->assertFalse(method_exists($snapshot, 'isAuthorized'));
    }
}
