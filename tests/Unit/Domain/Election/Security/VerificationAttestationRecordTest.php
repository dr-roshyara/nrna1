<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use PHPUnit\Framework\TestCase;

class VerificationAttestationRecordTest extends TestCase
{
    public function test_is_satisfied_when_required_but_not_attested(): void
    {
        $record = new VerificationAttestationRecord(
            required: true,
            attested: false,
            registrarId: null,
            attestationTimestamp: null,
            protocol: 'none',
            networkEvidenceHash: null,
            deviceEvidenceHash: null,
            revoked: false,
            validityScope: TrustValidityScope::ElectionScoped,
        );

        $this->assertFalse($record->isSatisfied());
    }

    public function test_is_satisfied_when_required_and_attested(): void
    {
        $now = new \DateTimeImmutable();
        $record = new VerificationAttestationRecord(
            required: true,
            attested: true,
            registrarId: 'registrar_1',
            attestationTimestamp: $now,
            protocol: 'both',
            networkEvidenceHash: 'hash_network',
            deviceEvidenceHash: 'hash_device',
            revoked: false,
            validityScope: TrustValidityScope::ElectionScoped,
        );

        $this->assertTrue($record->isSatisfied());
    }

    public function test_is_satisfied_when_not_required(): void
    {
        $record = new VerificationAttestationRecord(
            required: false,
            attested: false,
            registrarId: null,
            attestationTimestamp: null,
            protocol: 'none',
            networkEvidenceHash: null,
            deviceEvidenceHash: null,
            revoked: false,
            validityScope: TrustValidityScope::ElectionScoped,
        );

        $this->assertTrue($record->isSatisfied());
    }

    public function test_network_mismatch_when_hashes_differ(): void
    {
        $record = new VerificationAttestationRecord(
            required: true,
            attested: true,
            registrarId: 'registrar_1',
            attestationTimestamp: new \DateTimeImmutable(),
            protocol: 'ip_only',
            networkEvidenceHash: 'hash_old',
            deviceEvidenceHash: null,
            revoked: false,
            validityScope: TrustValidityScope::ElectionScoped,
        );

        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash_new',
            registeredIpHash: 'hash_new',
            whitelist: null,
            maxVotesPerIp: 5,
            votesFromThisIp: 1,
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->assertTrue($record->networkMismatch($evidence));
    }

    public function test_network_mismatch_when_hashes_match(): void
    {
        $record = new VerificationAttestationRecord(
            required: true,
            attested: true,
            registrarId: 'registrar_1',
            attestationTimestamp: new \DateTimeImmutable(),
            protocol: 'ip_only',
            networkEvidenceHash: 'hash_old',
            deviceEvidenceHash: null,
            revoked: false,
            validityScope: TrustValidityScope::ElectionScoped,
        );

        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash_old',
            registeredIpHash: 'hash_old',
            whitelist: null,
            maxVotesPerIp: 5,
            votesFromThisIp: 1,
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->assertFalse($record->networkMismatch($evidence));
    }

    public function test_device_mismatch_when_hashes_differ(): void
    {
        $record = new VerificationAttestationRecord(
            required: true,
            attested: true,
            registrarId: 'registrar_1',
            attestationTimestamp: new \DateTimeImmutable(),
            protocol: 'fingerprint_only',
            networkEvidenceHash: null,
            deviceEvidenceHash: 'fp_hash_old',
            revoked: false,
            validityScope: TrustValidityScope::ElectionScoped,
        );

        $context = new DeviceTrustContext(
            fingerprintHash: 'fp_hash_new',
            registeredFingerprintHash: 'fp_hash_new',
            matchType: FingerprintMatchType::ExactMatch,
            captureMethod: 'canvas',
            volatility: 'stable',
        );

        $this->assertTrue($record->deviceMismatch($context));
    }

    public function test_device_mismatch_when_hashes_match(): void
    {
        $record = new VerificationAttestationRecord(
            required: true,
            attested: true,
            registrarId: 'registrar_1',
            attestationTimestamp: new \DateTimeImmutable(),
            protocol: 'fingerprint_only',
            networkEvidenceHash: null,
            deviceEvidenceHash: 'fp_hash_old',
            revoked: false,
            validityScope: TrustValidityScope::ElectionScoped,
        );

        $context = new DeviceTrustContext(
            fingerprintHash: 'fp_hash_old',
            registeredFingerprintHash: 'fp_hash_old',
            matchType: FingerprintMatchType::ExactMatch,
            captureMethod: 'canvas',
            volatility: 'stable',
        );

        $this->assertFalse($record->deviceMismatch($context));
    }

    public function test_trust_level_unverified_when_not_attested(): void
    {
        $record = new VerificationAttestationRecord(
            required: true,
            attested: false,
            registrarId: null,
            attestationTimestamp: null,
            protocol: 'none',
            networkEvidenceHash: null,
            deviceEvidenceHash: null,
            revoked: false,
            validityScope: TrustValidityScope::ElectionScoped,
        );

        $this->assertEquals(TrustLevel::Unverified, $record->trustLevel());
    }

    public function test_trust_level_attested_when_attested_without_registrar(): void
    {
        $record = new VerificationAttestationRecord(
            required: true,
            attested: true,
            registrarId: null,
            attestationTimestamp: new \DateTimeImmutable(),
            protocol: 'both',
            networkEvidenceHash: 'hash_network',
            deviceEvidenceHash: 'hash_device',
            revoked: false,
            validityScope: TrustValidityScope::ElectionScoped,
        );

        $this->assertEquals(TrustLevel::Attested, $record->trustLevel());
    }

    public function test_trust_level_registrar_attested_when_attested_with_registrar(): void
    {
        $record = new VerificationAttestationRecord(
            required: true,
            attested: true,
            registrarId: 'registrar_1',
            attestationTimestamp: new \DateTimeImmutable(),
            protocol: 'both',
            networkEvidenceHash: 'hash_network',
            deviceEvidenceHash: 'hash_device',
            revoked: false,
            validityScope: TrustValidityScope::ManualClearRequired,
        );

        $this->assertEquals(TrustLevel::RegistrarAttested, $record->trustLevel());
    }
}
