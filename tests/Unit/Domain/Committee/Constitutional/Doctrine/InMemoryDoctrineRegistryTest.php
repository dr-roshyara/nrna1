<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Doctrine;

use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalScope;
use App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine\DoctrineArtifact;
use App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine\DoctrineProvenance;
use App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine\InMemoryDoctrineRegistry;
use PHPUnit\Framework\TestCase;

class InMemoryDoctrineRegistryTest extends TestCase
{
    /**
     * @test
     * Registry stores and retrieves artifact by version
     */
    public function test_registry_stores_and_retrieves_by_version(): void
    {
        $registry = new InMemoryDoctrineRegistry();

        $provenance = new DoctrineProvenance(
            approvedBy: 'admin@nrna-eu.org',
            approvedAt: new \DateTimeImmutable(),
            supersedesDoctrineId: null,
            supersededByDoctrineId: null,
            reason: 'Initial doctrine'
        );

        $artifact = new DoctrineArtifact(
            doctrineId: 'doctrine-2026',
            version: '1.0',
            constitutionalScope: ConstitutionalScope::NATIONAL,
            effectiveFrom: new \DateTimeImmutable('2026-01-01'),
            effectiveUntil: null,
            doctrineRules: ['rule-1'],
            doctrineHash: 'hash',
            provenance: $provenance
        );

        $registry->register($artifact);

        $retrieved = $registry->resolveVersion('1.0');
        $this->assertSame($artifact, $retrieved);
    }

    /**
     * @test
     * Registry returns null for unknown version
     */
    public function test_registry_returns_null_for_unknown_version(): void
    {
        $registry = new InMemoryDoctrineRegistry();

        $retrieved = $registry->resolveVersion('nonexistent');
        $this->assertNull($retrieved);
    }

    /**
     * @test
     * Registry finds artifact effective at given time and scope
     */
    public function test_find_effective_at_returns_artifact_in_scope_and_time(): void
    {
        $registry = new InMemoryDoctrineRegistry();

        $provenance = new DoctrineProvenance(
            approvedBy: 'admin@nrna-eu.org',
            approvedAt: new \DateTimeImmutable(),
            supersedesDoctrineId: null,
            supersededByDoctrineId: null,
            reason: 'Current doctrine'
        );

        $artifact = new DoctrineArtifact(
            doctrineId: 'doctrine-2026',
            version: '1.0',
            constitutionalScope: ConstitutionalScope::NATIONAL,
            effectiveFrom: new \DateTimeImmutable('2026-01-01'),
            effectiveUntil: new \DateTimeImmutable('2026-12-31'),
            doctrineRules: ['rule-1'],
            doctrineHash: 'hash',
            provenance: $provenance
        );

        $registry->register($artifact);

        $now = new \DateTimeImmutable('2026-06-15');
        $found = $registry->findEffectiveAt('national', $now);

        $this->assertNotNull($found);
        $this->assertSame($artifact, $found);
    }
}
