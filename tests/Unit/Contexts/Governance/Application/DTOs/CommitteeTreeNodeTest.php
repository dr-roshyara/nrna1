<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application\DTOs;

use App\Contexts\Governance\Application\DTOs\CommitteeTreeNode;
use App\Contexts\Governance\Domain\Committee\ViewModels\CommitteeGovernanceProjection;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\ConstitutionalLegitimacy;
use App\Contexts\Membership\Domain\ValueObjects\StructuralOperationalState;
use App\Contexts\Membership\Domain\ValueObjects\TemporalGovernanceState;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CommitteeTreeNodeTest extends TestCase
{
    private CommitteeId $id;
    private CommitteeGovernanceProjection $governance;

    protected function setUp(): void
    {
        $this->id = CommitteeId::fromString('test-node-id');
        $this->governance = new CommitteeGovernanceProjection(
            committeeId: CommitteeId::fromString('test-node-id'),
            operationalState: StructuralOperationalState::ACTIVE,
            temporalState: TemporalGovernanceState::VALID,
            legitimacy: ConstitutionalLegitimacy::LEGITIMATE,
            evaluatedAt: new DateTimeImmutable('2026-05-10'),
        );
    }

    public function test_creates_immutable_tree_node(): void
    {
        $node = new CommitteeTreeNode(
            id: $this->id,
            name: 'ICC Committee',
            level: 0,
            parentId: null,
            governance: $this->governance,
            children: [],
        );

        $this->assertSame($this->id, $node->id);
        $this->assertSame('ICC Committee', $node->name);
        $this->assertSame(0, $node->level);
        $this->assertNull($node->parentId);
        $this->assertSame($this->governance, $node->governance);
        $this->assertSame([], $node->children);
    }

    public function test_all_properties_are_readonly(): void
    {
        $reflection = new \ReflectionClass(CommitteeTreeNode::class);
        $this->assertTrue($reflection->isReadOnly());

        foreach ($reflection->getProperties() as $prop) {
            $this->assertTrue(
                $prop->isReadOnly(),
                sprintf('Property $%s must be readonly', $prop->getName())
            );
        }
    }

    public function test_no_add_child_method_exists(): void
    {
        $methods = array_map(
            fn(\ReflectionMethod $m) => $m->getName(),
            (new \ReflectionClass(CommitteeTreeNode::class))->getMethods()
        );

        $this->assertNotContains('addChild', $methods);
        $this->assertNotContains('removeChild', $methods);
        $this->assertNotContains('setChildren', $methods);
    }

    public function test_has_children_returns_true_when_children_not_empty(): void
    {
        $child = new CommitteeTreeNode(
            id: CommitteeId::fromString('child-id'),
            name: 'Child',
            level: 1,
            parentId: $this->id,
            governance: $this->governance,
            children: [],
        );

        $parent = new CommitteeTreeNode(
            id: $this->id,
            name: 'Parent',
            level: 0,
            parentId: null,
            governance: $this->governance,
            children: [$child],
        );

        $this->assertTrue($parent->hasChildren());
    }

    public function test_has_children_returns_false_when_children_empty(): void
    {
        $node = new CommitteeTreeNode(
            id: $this->id,
            name: 'Leaf',
            level: 2,
            parentId: null,
            governance: $this->governance,
            children: [],
        );

        $this->assertFalse($node->hasChildren());
    }

    public function test_can_create_nested_tree_immutably(): void
    {
        $child = new CommitteeTreeNode(
            id: CommitteeId::fromString('child-id'),
            name: 'Japan Country',
            level: 1,
            parentId: $this->id,
            governance: $this->governance,
            children: [],
        );

        $parent = new CommitteeTreeNode(
            id: $this->id,
            name: 'Asia Continent',
            level: 0,
            parentId: null,
            governance: $this->governance,
            children: [$child],
        );

        $this->assertCount(1, $parent->children);
        $this->assertSame($child, $parent->children[0]);
        $this->assertEquals('Japan Country', $parent->children[0]->name);
    }
}
