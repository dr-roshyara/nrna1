<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Timeline;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\Timeline\GovernanceTimelineProjection;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;

final class GovernanceTimelineProjectionTest extends TestCase
{
    private function makeProjection(GovernanceLegitimacy $legitimacy = GovernanceLegitimacy::LEGITIMATE): GovernanceTimelineProjection
    {
        return new GovernanceTimelineProjection(
            decisionId:        'decision-uuid-1',
            legitimacy:        $legitimacy,
            winningAuthorityId: 'node-1',
            capabilityType:    'committee_creation',
            decidedAt:         new \DateTimeImmutable('2026-05-08T10:00:00Z'),
            persistedAt:       new \DateTimeImmutable('2026-05-08T11:00:00Z'),
            replayFingerprint: 'fingerprint-hash-abc',
            doctrineVersion:   '1.0',
            schemaVersion:     '1.0',
        );
    }

    public function test_projection_holds_all_fields(): void
    {
        $projection = $this->makeProjection();

        $this->assertSame('decision-uuid-1', $projection->decisionId);
        $this->assertSame(GovernanceLegitimacy::LEGITIMATE, $projection->legitimacy);
        $this->assertSame('node-1', $projection->winningAuthorityId);
        $this->assertSame('committee_creation', $projection->capabilityType);
        $this->assertSame('fingerprint-hash-abc', $projection->replayFingerprint);
        $this->assertSame('1.0', $projection->doctrineVersion);
        $this->assertSame('1.0', $projection->schemaVersion);
    }

    public function test_was_constitutionally_valid_true_for_legitimate(): void
    {
        $projection = $this->makeProjection(GovernanceLegitimacy::LEGITIMATE);

        $this->assertTrue($projection->wasConstitutionallyValid());
    }

    public function test_was_constitutionally_valid_false_for_expired(): void
    {
        $projection = $this->makeProjection(GovernanceLegitimacy::EXPIRED);

        $this->assertFalse($projection->wasConstitutionallyValid());
    }
}
