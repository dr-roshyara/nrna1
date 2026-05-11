<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application\Services;

use App\Contexts\Governance\Application\DTOs\CommitteeHierarchyRecord;
use App\Contexts\Governance\Application\DTOs\CommitteeTreeNode;
use App\Contexts\Governance\Application\Services\CommitteeHierarchyBuilder;
use App\Contexts\Governance\Domain\Committee\ViewModels\CommitteeGovernanceProjection;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId as UuidCommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId as UlidCommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\ConstitutionalLegitimacy;
use App\Contexts\Membership\Domain\ValueObjects\StructuralOperationalState;
use App\Contexts\Membership\Domain\ValueObjects\TemporalGovernanceState;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;

final class CommitteeHierarchyBuilderTest extends TestCase
{
    private CommitteeHierarchyBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new CommitteeHierarchyBuilder();
    }

    public function test_builds_tree_from_flat_records(): void
    {
        $records = $this->createTreeRecords();

        $roots = $this->builder->buildTree($records);

        $this->assertCount(1, $roots, 'Should have one root node');
        $this->assertInstanceOf(CommitteeTreeNode::class, $roots[0]);
        $this->assertSame('ICC Global', $roots[0]->name);
        $this->assertTrue($roots[0]->hasChildren());
    }

    public function test_builds_correct_parent_child_relationships(): void
    {
        $records = $this->createTreeRecords();

        $roots = $this->builder->buildTree($records);

        $this->assertCount(1, $roots);
        /** @var CommitteeTreeNode $root */
        $root = $roots[0];
        $this->assertCount(2, $root->children);

        $childNames = array_map(fn(CommitteeTreeNode $n) => $n->name, $root->children);
        sort($childNames);
        $this->assertSame(['Asia Continent', 'Europe Continent'], $childNames);

        /** @var CommitteeTreeNode $asia */
        $asia = $root->children[0]->name === 'Asia Continent' ? $root->children[0] : $root->children[1];
        $this->assertCount(1, $asia->children);
        $this->assertSame('Japan Country', $asia->children[0]->name);
    }

    public function test_detects_direct_cycle_and_throws(): void
    {
        $cycle = [
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA1'),
                name: 'Root',
                level: 0,
                parentId: null,
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA2'),
                name: 'Child',
                level: 1,
                parentId: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA3'),
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA3'),
                name: 'Backlink',
                level: 2,
                parentId: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA2'),
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
        ];

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Cycle detected in committee hierarchy');

        $this->builder->buildTree($cycle);
    }

    public function test_detects_transitive_cycle_and_throws(): void
    {
        $cycle = [
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA1'),
                name: 'A',
                level: 0,
                parentId: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA3'),
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA2'),
                name: 'B',
                level: 1,
                parentId: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA1'),
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA3'),
                name: 'C',
                level: 2,
                parentId: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA2'),
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
        ];

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Cycle detected in committee hierarchy');

        $this->builder->buildTree($cycle);
    }

    public function test_handles_orphans_per_quarantine_policy(): void
    {
        $records = [
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA1'),
                name: 'Valid Root',
                level: 0,
                parentId: null,
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA2'),
                name: 'Orphan Child',
                level: 1,
                parentId: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5F99'),
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
        ];

        $roots = $this->builder->buildTree($records);

        $this->assertCount(1, $roots, 'Orphan should be quarantined, not attached as root');
        $this->assertSame('Valid Root', $roots[0]->name);
        $this->assertCount(0, $roots[0]->children, 'Orphan should not be attached as child of root');
    }

    public function test_maintains_order_by_level_then_name(): void
    {
        $records = [
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA3'),
                name: 'Z Level 2',
                level: 2,
                parentId: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA2'),
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA2'),
                name: 'M Level 1',
                level: 1,
                parentId: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA1'),
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA1'),
                name: 'A Root',
                level: 0,
                parentId: null,
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
        ];

        $roots = $this->builder->buildTree($records);

        $this->assertCount(1, $roots);
        $this->assertSame('A Root', $roots[0]->name);
    }

    public function test_returns_empty_array_for_empty_input(): void
    {
        $roots = $this->builder->buildTree([]);
        $this->assertIsArray($roots);
        $this->assertEmpty($roots);
    }

    public function test_tree_nodes_are_fully_immutable(): void
    {
        $records = $this->createTreeRecords();

        $roots = $this->builder->buildTree($records);

        $this->assertCount(1, $roots);
        $node = $roots[0];

        $reflection = new \ReflectionClass($node);
        $this->assertTrue($reflection->isReadOnly());

        foreach ($reflection->getProperties() as $prop) {
            $this->assertTrue(
                $prop->isReadOnly(),
                sprintf('Property $%s must be readonly', $prop->getName())
            );
        }
    }

    public function test_governance_projection_is_attached_to_nodes(): void
    {
        $records = $this->createTreeRecords();

        $roots = $this->builder->buildTree($records);

        $this->assertInstanceOf(CommitteeGovernanceProjection::class, $roots[0]->governance);
        $this->assertSame('ICC Global', $roots[0]->name);
        $this->assertSame(
            $records[0]->id->value(),
            $roots[0]->governance->committeeId->value()
        );
        $this->assertSame('ACTIVE', $roots[0]->governance->operationalState->value);
        $this->assertSame('VALID', $roots[0]->governance->temporalState->value);
        $this->assertSame('LEGITIMATE', $roots[0]->governance->legitimacy->value);
    }

    /**
     * @return CommitteeHierarchyRecord[]
     */
    private function createTreeRecords(): array
    {
        return [
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA1'),
                name: 'ICC Global',
                level: 0,
                parentId: null,
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA2'),
                name: 'Asia Continent',
                level: 1,
                parentId: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA1'),
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA3'),
                name: 'Europe Continent',
                level: 1,
                parentId: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA1'),
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
            new CommitteeHierarchyRecord(
                id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA4'),
                name: 'Japan Country',
                level: 2,
                parentId: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FA2'),
                operationalState: 'ACTIVE',
                temporalState: 'VALID',
                legitimacy: 'LEGITIMATE',
                canAct: true,
                isFullyOperational: true,
                projectionVersion: 1,
                termStart: null,
                termEnd: null,
            ),
        ];
    }
}
