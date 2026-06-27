<?php

namespace Tests\Unit\Domain\Election\Replay;

use App\Domain\Election\Replay\ReplayCompatibilityVersion;
use App\Domain\Election\Replay\ReplayEvidenceEnvelope;
use App\Domain\Election\Replay\ReplaySession;
use PHPUnit\Framework\TestCase;

class ReplaySessionTest extends TestCase
{
    private ReplayEvidenceEnvelope $envelope;

    protected function setUp(): void
    {
        parent::setUp();

        $this->envelope = new ReplayEvidenceEnvelope(
            evidence: ['ip_hash' => 'abc123', 'device_hash' => 'def456'],
            compatibility: ReplayCompatibilityVersion::current(),
            frozenAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
            electionIdentifier: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
        );
    }

    public function test_session_starts_in_sealed_state(): void
    {
        $session = new ReplaySession($this->envelope);

        $this->assertSame('sealed', $session->state());
        $this->assertSame($this->envelope, $session->envelope());
        $this->assertNull($session->assertion());
        $this->assertNull($session->certification());
    }

    public function test_session_id_is_deterministic(): void
    {
        $session1 = new ReplaySession($this->envelope);
        $session2 = new ReplaySession($this->envelope);

        $this->assertSame($session1->sessionId(), $session2->sessionId(),
            'REPLAY CONTRACT: same envelope must produce same session ID');
    }

    public function test_session_lifecycle_sealed_to_certified(): void
    {
        $session = new ReplaySession($this->envelope);

        // Sealed → record assertion → replayed
        $assertion = $session->recordAssertion(
            expectedOutcome: 'SUFFICIENT_EVIDENCE',
            policySequenceHash: hash('sha256', 'policy_v1'),
        );

        $this->assertSame('replayed', $session->state());
        $this->assertNotNull($session->assertion());
        $this->assertSame($assertion, $session->assertion());

        // Replayed → certify → certified
        $certification = $session->certify('SUFFICIENT_EVIDENCE');

        $this->assertSame('certified', $session->state());
        $this->assertNotNull($session->certification());
        $this->assertSame($certification, $session->certification());
        $this->assertTrue($session->isCertified());
        $this->assertFalse($session->hasDiverged());
    }

    public function test_session_detects_divergence(): void
    {
        $session = new ReplaySession($this->envelope);

        $session->recordAssertion(
            expectedOutcome: 'SUFFICIENT_EVIDENCE',
            policySequenceHash: hash('sha256', 'policy_v1'),
        );

        // Actual outcome differs from expected → divergence
        $certification = $session->certify('INSUFFICIENT_EVIDENCE');

        $this->assertSame('diverged', $session->state());
        $this->assertFalse($session->isCertified());
        $this->assertTrue($session->hasDiverged());
        $this->assertFalse($certification->matched);
    }

    public function test_cannot_record_assertion_twice(): void
    {
        $session = new ReplaySession($this->envelope);

        $session->recordAssertion('SUFFICIENT_EVIDENCE', hash('sha256', 'policy_v1'));

        $this->expectException(\RuntimeException::class);
        $session->recordAssertion('SUFFICIENT_EVIDENCE', hash('sha256', 'policy_v1'));
    }

    public function test_cannot_certify_without_assertion(): void
    {
        $session = new ReplaySession($this->envelope);

        $this->expectException(\RuntimeException::class);
        $session->certify('SUFFICIENT_EVIDENCE');
    }

    public function test_cannot_certify_twice(): void
    {
        $session = new ReplaySession($this->envelope);

        $session->recordAssertion('SUFFICIENT_EVIDENCE', hash('sha256', 'policy_v1'));
        $session->certify('SUFFICIENT_EVIDENCE');

        $this->expectException(\RuntimeException::class);
        $session->certify('SUFFICIENT_EVIDENCE');
    }

    public function test_certification_carries_envelope_hash(): void
    {
        $session = new ReplaySession($this->envelope);

        $session->recordAssertion('SUFFICIENT_EVIDENCE', hash('sha256', 'policy_v1'));
        $cert = $session->certify('SUFFICIENT_EVIDENCE');

        $this->assertSame($this->envelope->envelopeHash, $cert->envelopeHash);
    }
}
