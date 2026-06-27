<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Doctrine;

use App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine\DoctrineProvenance;
use PHPUnit\Framework\TestCase;

class DoctrineProvenanceTest extends TestCase
{
    /**
     * @test
     * DoctrineProvenance holds all fields
     */
    public function test_provenance_holds_all_fields(): void
    {
        $approvedAt = new \DateTimeImmutable('2026-05-08T10:00:00Z');
        $provenance = new DoctrineProvenance(
            approvedBy: 'admin@nrna-eu.org',
            approvedAt: $approvedAt,
            supersedesDoctrineId: 'doc-v0',
            supersededByDoctrineId: null,
            reason: 'Constitutional amendment approved by central committee'
        );

        $this->assertSame('admin@nrna-eu.org', $provenance->approvedBy);
        $this->assertSame($approvedAt, $provenance->approvedAt);
        $this->assertSame('doc-v0', $provenance->supersedesDoctrineId);
        $this->assertNull($provenance->supersededByDoctrineId);
        $this->assertSame('Constitutional amendment approved by central committee', $provenance->reason);
    }

    /**
     * @test
     * DoctrineProvenance::supersedes() returns true when supersedesDoctrineId set
     */
    public function test_supersedes_true_when_doctrine_id_set(): void
    {
        $provenance = new DoctrineProvenance(
            approvedBy: 'admin@nrna-eu.org',
            approvedAt: new \DateTimeImmutable(),
            supersedesDoctrineId: 'doc-v1',
            supersededByDoctrineId: null,
            reason: 'Updated doctrine version'
        );

        $this->assertTrue($provenance->supersedes());
    }

    /**
     * @test
     * DoctrineProvenance::isSuperseded() returns false when supersededByDoctrineId null
     */
    public function test_is_superseded_false_when_null(): void
    {
        $provenance = new DoctrineProvenance(
            approvedBy: 'admin@nrna-eu.org',
            approvedAt: new \DateTimeImmutable(),
            supersedesDoctrineId: null,
            supersededByDoctrineId: null,
            reason: 'Current doctrine version'
        );

        $this->assertFalse($provenance->isSuperseded());
    }
}
