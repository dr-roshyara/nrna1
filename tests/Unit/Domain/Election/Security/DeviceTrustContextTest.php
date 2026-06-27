<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use PHPUnit\Framework\TestCase;

class DeviceTrustContextTest extends TestCase
{
    public function test_exact_match_satisfies_device_attestation(): void
    {
        $context = new DeviceTrustContext(
            fingerprintHash: 'hash123',
            registeredFingerprintHash: 'hash123',
            matchType: FingerprintMatchType::ExactMatch,
            captureMethod: 'browser_api',
            volatility: 'stable',
        );

        $this->assertTrue($context->satisfiesDeviceAttestation());
    }

    public function test_no_match_does_not_satisfy_device_attestation(): void
    {
        $context = new DeviceTrustContext(
            fingerprintHash: 'hash123',
            registeredFingerprintHash: 'hash456',
            matchType: FingerprintMatchType::NoMatch,
            captureMethod: 'browser_api',
            volatility: 'stable',
        );

        $this->assertFalse($context->satisfiesDeviceAttestation());
    }

    public function test_not_required_satisfies_device_attestation(): void
    {
        $context = new DeviceTrustContext(
            fingerprintHash: null,
            registeredFingerprintHash: null,
            matchType: FingerprintMatchType::NotRequired,
            captureMethod: 'none',
            volatility: 'volatile',
        );

        $this->assertTrue($context->satisfiesDeviceAttestation());
    }

    public function test_is_volatile_returns_true_for_volatile(): void
    {
        $context = new DeviceTrustContext(
            fingerprintHash: 'hash123',
            registeredFingerprintHash: 'hash123',
            matchType: FingerprintMatchType::ExactMatch,
            captureMethod: 'canvas',
            volatility: 'volatile',
        );

        $this->assertTrue($context->isVolatile());
    }

    public function test_is_volatile_returns_false_for_stable(): void
    {
        $context = new DeviceTrustContext(
            fingerprintHash: 'hash123',
            registeredFingerprintHash: 'hash123',
            matchType: FingerprintMatchType::ExactMatch,
            captureMethod: 'browser_api',
            volatility: 'stable',
        );

        $this->assertFalse($context->isVolatile());
    }
}
