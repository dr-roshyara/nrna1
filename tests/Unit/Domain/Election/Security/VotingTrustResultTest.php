<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VotingTrustResult;
use PHPUnit\Framework\TestCase;

class VotingTrustResultTest extends TestCase
{
    public function test_allow_creates_trusted_result(): void
    {
        $result = VotingTrustResult::allow(
            TrustLevel::Attested,
            ['evidence' => 'verified'],
            ['verification' => 'passed']
        );

        $this->assertTrue($result->trusted);
        $this->assertEquals(TrustLevel::Attested, $result->trustLevel);
    }

    public function test_deny_creates_untrusted_result(): void
    {
        $result = VotingTrustResult::deny(
            'IP limit exceeded',
            ['votes_from_ip' => 15],
            ['network' => 'denied']
        );

        $this->assertFalse($result->trusted);
        $this->assertEquals('IP limit exceeded', $result->reason);
    }

    public function test_audit_context_is_preserved(): void
    {
        $context = ['network_evidence' => 'hashed_ip'];
        $result = VotingTrustResult::allow(TrustLevel::RegistrarAttested, $context, []);

        $this->assertEquals($context, $result->auditContext);
    }

    public function test_policy_outcome_sequence_is_preserved(): void
    {
        $sequence = ['verification' => 'passed', 'network' => 'allowed'];
        $result = VotingTrustResult::allow(TrustLevel::Attested, [], $sequence);

        $this->assertEquals($sequence, $result->policyOutcomeSequence);
    }

    public function test_deny_with_unverified_trust_level(): void
    {
        $result = VotingTrustResult::deny(
            'verification required',
            [],
            ['verification' => 'not_verified']
        );

        $this->assertFalse($result->trusted);
    }
}
