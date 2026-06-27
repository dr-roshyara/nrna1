<?php

namespace Tests\Unit\Domain\Election\Replay;

use App\Domain\Election\Replay\ReplayEvidenceEnvelope;
use App\Domain\Election\Replay\ReplayCompatibilityVersion;
use PHPUnit\Framework\TestCase;

class ReplayEvidenceEnvelopeTest extends TestCase
{
    private ReplayCompatibilityVersion $compat;

    protected function setUp(): void
    {
        parent::setUp();
        $this->compat = ReplayCompatibilityVersion::current();
    }

    public function test_envelope_creates_deterministic_hash(): void
    {
        $now = new \DateTimeImmutable('2026-05-29 12:00:00');

        $envelope1 = new ReplayEvidenceEnvelope(
            evidence: ['ip_hash' => 'abc123', 'device_hash' => 'def456'],
            compatibility: $this->compat,
            frozenAt: $now,
            electionIdentifier: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
        );

        $envelope2 = new ReplayEvidenceEnvelope(
            evidence: ['ip_hash' => 'abc123', 'device_hash' => 'def456'],
            compatibility: $this->compat,
            frozenAt: $now,
            electionIdentifier: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
        );

        $this->assertSame($envelope1->envelopeHash, $envelope2->envelopeHash,
            'REPLAY CONTRACT: identical evidence must produce identical envelope hash');
    }

    public function test_different_evidence_produces_different_hash(): void
    {
        $now = new \DateTimeImmutable('2026-05-29 12:00:00');

        $envelope1 = new ReplayEvidenceEnvelope(
            evidence: ['ip_hash' => 'abc123'],
            compatibility: $this->compat,
            frozenAt: $now,
            electionIdentifier: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
        );

        $envelope2 = new ReplayEvidenceEnvelope(
            evidence: ['ip_hash' => 'xyz789'],
            compatibility: $this->compat,
            frozenAt: $now,
            electionIdentifier: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
        );

        $this->assertNotSame($envelope1->envelopeHash, $envelope2->envelopeHash,
            'Different evidence must produce different envelope hash');
    }

    public function test_different_election_identifier_produces_different_hash(): void
    {
        $now = new \DateTimeImmutable('2026-05-29 12:00:00');

        $envelope1 = new ReplayEvidenceEnvelope(
            evidence: ['ip_hash' => 'abc123'],
            compatibility: $this->compat,
            frozenAt: $now,
            electionIdentifier: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
        );

        $envelope2 = new ReplayEvidenceEnvelope(
            evidence: ['ip_hash' => 'abc123'],
            compatibility: $this->compat,
            frozenAt: $now,
            electionIdentifier: 'election_002',
            voterIdentifier: hash('sha256', 'voter_001'),
        );

        $this->assertNotSame($envelope1->envelopeHash, $envelope2->envelopeHash,
            'Election-scoped hashing: different elections must produce different hashes');
    }

    public function test_verify_integrity_with_correct_hash(): void
    {
        $now = new \DateTimeImmutable('2026-05-29 12:00:00');
        $envelope = new ReplayEvidenceEnvelope(
            evidence: ['ip_hash' => 'abc123'],
            compatibility: $this->compat,
            frozenAt: $now,
            electionIdentifier: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
        );

        $this->assertTrue($envelope->verifyIntegrity($envelope->envelopeHash));
    }

    public function test_verify_integrity_with_incorrect_hash(): void
    {
        $now = new \DateTimeImmutable('2026-05-29 12:00:00');
        $envelope = new ReplayEvidenceEnvelope(
            evidence: ['ip_hash' => 'abc123'],
            compatibility: $this->compat,
            frozenAt: $now,
            electionIdentifier: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
        );

        $this->assertFalse($envelope->verifyIntegrity('fake_hash'));
    }

    public function test_envelope_is_readonly(): void
    {
        $now = new \DateTimeImmutable('2026-05-29 12:00:00');
        $envelope = new ReplayEvidenceEnvelope(
            evidence: ['ip_hash' => 'abc123'],
            compatibility: $this->compat,
            frozenAt: $now,
            electionIdentifier: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
        );

        $this->assertTrue(
            (new \ReflectionClass($envelope))->isReadOnly(),
            'ReplayEvidenceEnvelope must be readonly — evidence must remain frozen'
        );
    }

    public function test_evidence_count(): void
    {
        $now = new \DateTimeImmutable('2026-05-29 12:00:00');
        $envelope = new ReplayEvidenceEnvelope(
            evidence: ['a' => 1, 'b' => 2, 'c' => 3],
            compatibility: $this->compat,
            frozenAt: $now,
            electionIdentifier: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
        );

        $this->assertSame(3, $envelope->evidenceCount());
    }
}
