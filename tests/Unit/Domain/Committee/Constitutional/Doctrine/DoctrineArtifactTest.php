<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Doctrine;

use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalScope;
use App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine\DoctrineArtifact;
use App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine\DoctrineArtifactHash;
use App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine\DoctrineProvenance;
use PHPUnit\Framework\TestCase;

class DoctrineArtifactTest extends TestCase
{
    private DoctrineProvenance $provenance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provenance = new DoctrineProvenance(
            approvedBy: 'admin@nrna-eu.org',
            approvedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z'),
            supersedesDoctrineId: null,
            supersededByDoctrineId: null,
            reason: 'Initial doctrine version'
        );
    }

    /**
     * @test
     * DoctrineArtifact holds all fields including provenance
     */
    public function test_artifact_holds_all_fields_including_provenance(): void
    {
        $effectiveFrom = new \DateTimeImmutable('2026-01-01T00:00:00Z');
        $effectiveUntil = new \DateTimeImmutable('2026-12-31T23:59:59Z');

        $artifact = new DoctrineArtifact(
            doctrineId: 'doctrine-2026',
            version: '1.0',
            constitutionalScope: ConstitutionalScope::NATIONAL,
            effectiveFrom: $effectiveFrom,
            effectiveUntil: $effectiveUntil,
            doctrineRules: ['rule-1', 'rule-2', 'rule-3'],
            doctrineHash: 'hash-value',
            provenance: $this->provenance
        );

        $this->assertSame('doctrine-2026', $artifact->doctrineId);
        $this->assertSame('1.0', $artifact->version);
        $this->assertSame(ConstitutionalScope::NATIONAL, $artifact->constitutionalScope);
        $this->assertSame($effectiveFrom, $artifact->effectiveFrom);
        $this->assertSame($effectiveUntil, $artifact->effectiveUntil);
        $this->assertSame(['rule-1', 'rule-2', 'rule-3'], $artifact->doctrineRules);
        $this->assertSame('hash-value', $artifact->doctrineHash);
        $this->assertSame($this->provenance, $artifact->provenance);
    }

    /**
     * @test
     * DoctrineArtifactHash is deterministic (same inputs produce same hash)
     */
    public function test_artifact_hash_is_deterministic(): void
    {
        $artifact1 = new DoctrineArtifact(
            doctrineId: 'doctrine-2026',
            version: '1.0',
            constitutionalScope: ConstitutionalScope::NATIONAL,
            effectiveFrom: new \DateTimeImmutable('2026-01-01T00:00:00Z'),
            effectiveUntil: new \DateTimeImmutable('2026-12-31T23:59:59Z'),
            doctrineRules: ['rule-1', 'rule-2'],
            doctrineHash: 'original-hash',
            provenance: $this->provenance
        );

        $artifact2 = new DoctrineArtifact(
            doctrineId: 'doctrine-2026',
            version: '1.0',
            constitutionalScope: ConstitutionalScope::NATIONAL,
            effectiveFrom: new \DateTimeImmutable('2026-01-01T00:00:00Z'),
            effectiveUntil: new \DateTimeImmutable('2026-12-31T23:59:59Z'),
            doctrineRules: ['rule-1', 'rule-2'],
            doctrineHash: 'original-hash',
            provenance: $this->provenance
        );

        $hash1 = DoctrineArtifactHash::fromArtifact($artifact1);
        $hash2 = DoctrineArtifactHash::fromArtifact($artifact2);

        $this->assertTrue($hash1->equals($hash2));
    }

    /**
     * @test
     * DoctrineArtifactHash verification fails for tampered artifacts
     */
    public function test_artifact_hash_fails_for_tampered_rules(): void
    {
        $artifact = new DoctrineArtifact(
            doctrineId: 'doctrine-2026',
            version: '1.0',
            constitutionalScope: ConstitutionalScope::NATIONAL,
            effectiveFrom: new \DateTimeImmutable('2026-01-01T00:00:00Z'),
            effectiveUntil: new \DateTimeImmutable('2026-12-31T23:59:59Z'),
            doctrineRules: ['rule-1', 'rule-2'],
            doctrineHash: 'original-hash',
            provenance: $this->provenance
        );

        $hash = DoctrineArtifactHash::fromArtifact($artifact);

        // Create tampered artifact with extra rule
        $tamperedArtifact = new DoctrineArtifact(
            doctrineId: 'doctrine-2026',
            version: '1.0',
            constitutionalScope: ConstitutionalScope::NATIONAL,
            effectiveFrom: new \DateTimeImmutable('2026-01-01T00:00:00Z'),
            effectiveUntil: new \DateTimeImmutable('2026-12-31T23:59:59Z'),
            doctrineRules: ['rule-1', 'rule-2', 'rule-3'], // Extra rule
            doctrineHash: 'original-hash',
            provenance: $this->provenance
        );

        $this->assertFalse($hash->verify($tamperedArtifact));
    }

    /**
     * @test
     * DoctrineArtifact::isEffectiveAt() returns true within time window
     */
    public function test_is_effective_at_true_within_window(): void
    {
        $artifact = new DoctrineArtifact(
            doctrineId: 'doctrine-2026',
            version: '1.0',
            constitutionalScope: ConstitutionalScope::NATIONAL,
            effectiveFrom: new \DateTimeImmutable('2026-01-01T00:00:00Z'),
            effectiveUntil: new \DateTimeImmutable('2026-12-31T23:59:59Z'),
            doctrineRules: ['rule-1'],
            doctrineHash: 'hash',
            provenance: $this->provenance
        );

        $now = new \DateTimeImmutable('2026-06-15T12:00:00Z');
        $this->assertTrue($artifact->isEffectiveAt($now));
    }

    /**
     * @test
     * DoctrineArtifact::isEffectiveAt() returns false after expiry
     */
    public function test_is_effective_at_false_after_expiry(): void
    {
        $artifact = new DoctrineArtifact(
            doctrineId: 'doctrine-2026',
            version: '1.0',
            constitutionalScope: ConstitutionalScope::NATIONAL,
            effectiveFrom: new \DateTimeImmutable('2026-01-01T00:00:00Z'),
            effectiveUntil: new \DateTimeImmutable('2026-06-30T23:59:59Z'),
            doctrineRules: ['rule-1'],
            doctrineHash: 'hash',
            provenance: $this->provenance
        );

        $future = new \DateTimeImmutable('2026-12-31T12:00:00Z');
        $this->assertFalse($artifact->isEffectiveAt($future));
    }

    /**
     * @test
     * Unordered rules produce same hash as sorted (canonical sorting)
     */
    public function test_unordered_rules_produce_same_hash_as_sorted(): void
    {
        $artifact1 = new DoctrineArtifact(
            doctrineId: 'doctrine-2026',
            version: '1.0',
            constitutionalScope: ConstitutionalScope::NATIONAL,
            effectiveFrom: new \DateTimeImmutable('2026-01-01T00:00:00Z'),
            effectiveUntil: new \DateTimeImmutable('2026-12-31T23:59:59Z'),
            doctrineRules: ['rule-3', 'rule-1', 'rule-2'], // Unsorted
            doctrineHash: 'hash',
            provenance: $this->provenance
        );

        $artifact2 = new DoctrineArtifact(
            doctrineId: 'doctrine-2026',
            version: '1.0',
            constitutionalScope: ConstitutionalScope::NATIONAL,
            effectiveFrom: new \DateTimeImmutable('2026-01-01T00:00:00Z'),
            effectiveUntil: new \DateTimeImmutable('2026-12-31T23:59:59Z'),
            doctrineRules: ['rule-1', 'rule-2', 'rule-3'], // Sorted
            doctrineHash: 'hash',
            provenance: $this->provenance
        );

        $hash1 = DoctrineArtifactHash::fromArtifact($artifact1);
        $hash2 = DoctrineArtifactHash::fromArtifact($artifact2);

        $this->assertTrue($hash1->equals($hash2));
    }
}
