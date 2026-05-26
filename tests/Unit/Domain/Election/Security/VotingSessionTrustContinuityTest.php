<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\VotingSessionTrustContinuity;
use PHPUnit\Framework\TestCase;

class VotingSessionTrustContinuityTest extends TestCase
{
    public function test_is_preserved_when_ip_and_device_unchanged(): void
    {
        $continuity = new VotingSessionTrustContinuity(
            sessionId: 'session_123',
            ipHashAtStart: 'hash_ip_1',
            ipHashCurrent: 'hash_ip_1',
            deviceChanged: false,
            continuityState: 'continuous',
        );

        $this->assertTrue($continuity->isPreserved());
    }

    public function test_requires_re_attestation_when_ip_changes(): void
    {
        $continuity = new VotingSessionTrustContinuity(
            sessionId: 'session_123',
            ipHashAtStart: 'hash_ip_1',
            ipHashCurrent: 'hash_ip_2',
            deviceChanged: false,
            continuityState: 'interrupted',
        );

        $this->assertTrue($continuity->requiresReAttestation());
    }

    public function test_requires_re_attestation_when_device_changes(): void
    {
        $continuity = new VotingSessionTrustContinuity(
            sessionId: 'session_123',
            ipHashAtStart: 'hash_ip_1',
            ipHashCurrent: 'hash_ip_1',
            deviceChanged: true,
            continuityState: 'interrupted',
        );

        $this->assertTrue($continuity->requiresReAttestation());
    }

    public function test_is_invalidated_when_continuity_state_invalidated(): void
    {
        $continuity = new VotingSessionTrustContinuity(
            sessionId: 'session_123',
            ipHashAtStart: 'hash_ip_1',
            ipHashCurrent: 'hash_ip_1',
            deviceChanged: false,
            continuityState: 'invalidated',
        );

        $this->assertTrue($continuity->isInvalidated());
    }
}
