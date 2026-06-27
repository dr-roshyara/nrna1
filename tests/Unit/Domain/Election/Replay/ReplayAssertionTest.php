<?php

namespace Tests\Unit\Domain\Election\Replay;

use App\Domain\Election\Replay\ReplayAssertion;
use App\Domain\Election\Replay\ReplayCompatibilityVersion;
use App\Domain\Election\Replay\ReplayEvidenceEnvelope;
use PHPUnit\Framework\TestCase;

class ReplayAssertionTest extends TestCase
{
    private ReplayEvidenceEnvelope $envelope;

    protected function setUp(): void
    {
        parent::setUp();

        $this->envelope = new ReplayEvidenceEnvelope(
            evidence: ['ip_hash' => 'abc123'],
            compatibility: ReplayCompatibilityVersion::current(),
            frozenAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
            electionIdentifier: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
        );
    }

    public function test_assertion_creates_deterministic_hash(): void
    {
        $ts = new \DateTimeImmutable('2026-05-29 12:00:00');

        $a1 = new ReplayAssertion(
            envelope: $this->envelope,
            expectedOutcome: 'SUFFICIENT_EVIDENCE',
            policySequenceHash: hash('sha256', 'policy_v1'),
            assertedAt: $ts,
        );

        $a2 = new ReplayAssertion(
            envelope: $this->envelope,
            expectedOutcome: 'SUFFICIENT_EVIDENCE',
            policySequenceHash: hash('sha256', 'policy_v1'),
            assertedAt: $ts,
        );

        $this->assertSame($a1->assertionHash, $a2->assertionHash,
            'REPLAY CONTRACT: identical assertions must produce identical hash');
    }

    public function test_verify_matched_outcome(): void
    {
        $assertion = new ReplayAssertion(
            envelope: $this->envelope,
            expectedOutcome: 'SUFFICIENT_EVIDENCE',
            policySequenceHash: hash('sha256', 'policy_v1'),
            assertedAt: new \DateTimeImmutable(),
        );

        $this->assertTrue($assertion->verify('SUFFICIENT_EVIDENCE'));
    }

    public function test_verify_mismatched_outcome(): void
    {
        $assertion = new ReplayAssertion(
            envelope: $this->envelope,
            expectedOutcome: 'SUFFICIENT_EVIDENCE',
            policySequenceHash: hash('sha256', 'policy_v1'),
            assertedAt: new \DateTimeImmutable(),
        );

        $this->assertFalse($assertion->verify('INSUFFICIENT_EVIDENCE'));
    }

    public function test_assertion_is_readonly(): void
    {
        $assertion = new ReplayAssertion(
            envelope: $this->envelope,
            expectedOutcome: 'SUFFICIENT_EVIDENCE',
            policySequenceHash: hash('sha256', 'policy_v1'),
            assertedAt: new \DateTimeImmutable(),
        );

        $this->assertTrue(
            (new \ReflectionClass($assertion))->isReadOnly(),
            'ReplayAssertion must be readonly — contract must be immutable'
        );
    }
}
