<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Provenance;

use App\Contexts\Membership\Domain\Committee\Constitutional\Provenance\GovernanceProvenance;
use PHPUnit\Framework\TestCase;

class GovernanceProvenanceTest extends TestCase
{
    /**
     * @test
     * Provenance holds all origin fields
     */
    public function test_provenance_holds_all_fields(): void
    {
        $provenance = new GovernanceProvenance(
            producedByKernel: 'ConstitutionalArbitrationKernel',
            doctrineVersion: '1.0',
            replayEngineVersion: '3.2',
            migratedFromVersion: null,
            certificationId: null,
            provenanceCreatedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z')
        );

        $this->assertSame('ConstitutionalArbitrationKernel', $provenance->producedByKernel);
        $this->assertSame('1.0', $provenance->doctrineVersion);
        $this->assertSame('3.2', $provenance->replayEngineVersion);
    }

    /**
     * @test
     * GovernanceProvenance::wasMigrated() returns true when version set
     */
    public function test_was_migrated_true_when_version_set(): void
    {
        $provenance = new GovernanceProvenance(
            producedByKernel: 'Kernel',
            doctrineVersion: '2.0',
            replayEngineVersion: '3.2',
            migratedFromVersion: '1.0',
            certificationId: null,
            provenanceCreatedAt: new \DateTimeImmutable()
        );

        $this->assertTrue($provenance->wasMigrated());
    }

    /**
     * @test
     * GovernanceProvenance::isCertified() returns false when id null
     */
    public function test_is_certified_false_when_id_null(): void
    {
        $provenance = new GovernanceProvenance(
            producedByKernel: 'Kernel',
            doctrineVersion: '1.0',
            replayEngineVersion: '3.2',
            migratedFromVersion: null,
            certificationId: null,
            provenanceCreatedAt: new \DateTimeImmutable()
        );

        $this->assertFalse($provenance->isCertified());
    }
}
