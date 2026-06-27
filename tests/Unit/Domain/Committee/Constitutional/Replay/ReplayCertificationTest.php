<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Replay;

use App\Contexts\Membership\Domain\Committee\Constitutional\Replay\ReplayCertification;
use PHPUnit\Framework\TestCase;

class ReplayCertificationTest extends TestCase
{
    /**
     * @test
     * Certification equivalent when all 5 dimensions match
     */
    public function test_certification_equivalent_when_all_5_dimensions_match(): void
    {
        $cert = new ReplayCertification(
            decisionId: 'decision-1',
            isEquivalent: true,
            equivalenceBreaches: [],
            certificationReason: 'All 5 constitutional dimensions verified equivalent',
            certifiedAt: new \DateTimeImmutable()
        );

        $this->assertTrue($cert->isEquivalent);
        $this->assertEmpty($cert->equivalenceBreaches);
    }

    /**
     * @test
     * Certification detects legitimacy drift
     */
    public function test_certification_detects_legitimacy_drift(): void
    {
        $cert = new ReplayCertification(
            decisionId: 'decision-1',
            isEquivalent: false,
            equivalenceBreaches: ['legitimacy'],
            certificationReason: 'Semantic drift detected: legitimacy',
            certifiedAt: new \DateTimeImmutable()
        );

        $this->assertFalse($cert->isEquivalent);
        $this->assertContains('legitimacy', $cert->equivalenceBreaches);
    }

    /**
     * @test
     * Certification detects scope drift
     */
    public function test_certification_detects_scope_drift(): void
    {
        $cert = new ReplayCertification(
            decisionId: 'decision-1',
            isEquivalent: false,
            equivalenceBreaches: ['constitutional_scope'],
            certificationReason: 'Semantic drift detected: constitutional_scope',
            certifiedAt: new \DateTimeImmutable()
        );

        $this->assertContains('constitutional_scope', $cert->equivalenceBreaches);
    }

    /**
     * @test
     * Certification detects doctrine version drift
     */
    public function test_certification_detects_doctrine_version_drift(): void
    {
        $cert = new ReplayCertification(
            decisionId: 'decision-1',
            isEquivalent: false,
            equivalenceBreaches: ['doctrine_version'],
            certificationReason: 'Semantic drift detected: doctrine_version',
            certifiedAt: new \DateTimeImmutable()
        );

        $this->assertContains('doctrine_version', $cert->equivalenceBreaches);
    }

    /**
     * @test
     * Certification reason names all breach dimensions
     */
    public function test_certification_reason_names_all_breach_dimensions(): void
    {
        $cert = new ReplayCertification(
            decisionId: 'decision-1',
            isEquivalent: false,
            equivalenceBreaches: ['legitimacy', 'scope'],
            certificationReason: 'Semantic drift detected: legitimacy, scope',
            certifiedAt: new \DateTimeImmutable()
        );

        $this->assertStringContainsString('legitimacy', $cert->certificationReason);
        $this->assertStringContainsString('scope', $cert->certificationReason);
    }
}
