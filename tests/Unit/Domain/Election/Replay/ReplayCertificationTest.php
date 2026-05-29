<?php

namespace Tests\Unit\Domain\Election\Replay;

use App\Domain\Election\Replay\ReplayCertification;
use App\Domain\Election\Replay\ReplayCompatibilityVersion;
use PHPUnit\Framework\TestCase;

class ReplayCertificationTest extends TestCase
{
    private ReplayCompatibilityVersion $compat;

    protected function setUp(): void
    {
        parent::setUp();
        $this->compat = ReplayCompatibilityVersion::current();
    }

    public function test_matched_certification_factory(): void
    {
        $cert = ReplayCertification::matched(
            sessionId: 'session_001',
            envelopeHash: hash('sha256', 'envelope_content'),
            outcome: 'SUFFICIENT_EVIDENCE',
            version: $this->compat,
        );

        $this->assertTrue($cert->matched);
        $this->assertSame('SUFFICIENT_EVIDENCE', $cert->expectedOutcome);
        $this->assertSame('SUFFICIENT_EVIDENCE', $cert->actualOutcome);
    }

    public function test_diverged_certification_factory(): void
    {
        $cert = ReplayCertification::diverged(
            sessionId: 'session_001',
            envelopeHash: hash('sha256', 'envelope_content'),
            expectedOutcome: 'SUFFICIENT_EVIDENCE',
            actualOutcome: 'INSUFFICIENT_EVIDENCE',
            version: $this->compat,
        );

        $this->assertFalse($cert->matched);
        $this->assertSame('SUFFICIENT_EVIDENCE', $cert->expectedOutcome);
        $this->assertSame('INSUFFICIENT_EVIDENCE', $cert->actualOutcome);
    }

    public function test_certification_hash_is_deterministic(): void
    {
        $ts = new \DateTimeImmutable('2026-05-29 12:00:00');

        $c1 = new ReplayCertification(
            sessionId: 'session_001',
            envelopeHash: hash('sha256', 'envelope_content'),
            expectedOutcome: 'SUFFICIENT_EVIDENCE',
            actualOutcome: 'SUFFICIENT_EVIDENCE',
            matched: true,
            certifiedAt: $ts,
            compatibilityVersion: $this->compat,
        );

        $c2 = new ReplayCertification(
            sessionId: 'session_001',
            envelopeHash: hash('sha256', 'envelope_content'),
            expectedOutcome: 'SUFFICIENT_EVIDENCE',
            actualOutcome: 'SUFFICIENT_EVIDENCE',
            matched: true,
            certifiedAt: $ts,
            compatibilityVersion: $this->compat,
        );

        $this->assertSame($c1->certificationHash, $c2->certificationHash,
            'REPLAY CONTRACT: identical certifications must produce identical hash');
    }

    public function test_certification_is_readonly(): void
    {
        $cert = ReplayCertification::matched(
            sessionId: 'session_001',
            envelopeHash: hash('sha256', 'envelope_content'),
            outcome: 'SUFFICIENT_EVIDENCE',
            version: $this->compat,
        );

        $this->assertTrue(
            (new \ReflectionClass($cert))->isReadOnly(),
            'ReplayCertification must be readonly — certification result must be immutable'
        );
    }
}
