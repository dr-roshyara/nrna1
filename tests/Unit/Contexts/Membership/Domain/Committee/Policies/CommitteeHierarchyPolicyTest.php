<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\Policies\CommitteeHierarchyPolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DomainException;
use PHPUnit\Framework\TestCase;

final class CommitteeHierarchyPolicyTest extends TestCase
{
    private CommitteeHierarchyPolicy $policy;

    protected function setUp(): void
    {
        $this->policy = new CommitteeHierarchyPolicy();
    }

    public function test_it_rejects_self_cycle(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('INV-05');

        $committeeId = CommitteeId::generate();

        $this->policy->assertCanAttach(
            childId: $committeeId,
            parentId: $committeeId,
            ancestors: [],
        );
    }

    public function test_it_allows_valid_parent_attachment(): void
    {
        $childId = CommitteeId::generate();
        $parentId = CommitteeId::generate();

        $this->policy->assertCanAttach(
            childId: $childId,
            parentId: $parentId,
            ancestors: [],
        );

        $this->assertTrue(true);
    }

    public function test_it_rejects_graph_cycle_with_direct_ancestor(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('INV-06');

        $childId = CommitteeId::generate();
        $ancestorId = CommitteeId::generate();

        $ancestorFacts = $this->createMockAncestorFact($ancestorId);

        $this->policy->assertCanAttach(
            childId: $childId,
            parentId: $ancestorId,
            ancestors: [$ancestorFacts],
        );
    }

    public function test_it_rejects_graph_cycle_with_grandparent(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('INV-06');

        $childId = CommitteeId::generate();
        $parentId = CommitteeId::generate();
        $grandparentId = CommitteeId::generate();

        $ancestorFacts1 = $this->createMockAncestorFact($parentId);
        $ancestorFacts2 = $this->createMockAncestorFact($grandparentId);

        $this->policy->assertCanAttach(
            childId: $childId,
            parentId: $grandparentId,
            ancestors: [$ancestorFacts1, $ancestorFacts2],
        );
    }

    public function test_it_allows_attachment_when_child_is_not_in_ancestors(): void
    {
        $childId = CommitteeId::generate();
        $parentId = CommitteeId::generate();
        $existingAncestorId = CommitteeId::generate();

        $ancestorFacts = $this->createMockAncestorFact($existingAncestorId);

        $this->policy->assertCanAttach(
            childId: $childId,
            parentId: $parentId,
            ancestors: [$ancestorFacts],
        );

        $this->assertTrue(true);
    }

    public function test_it_handles_complex_hierarchy(): void
    {
        $childId = CommitteeId::generate();
        $parentId = CommitteeId::generate();
        $ancestor1Id = CommitteeId::generate();
        $ancestor2Id = CommitteeId::generate();
        $ancestor3Id = CommitteeId::generate();

        $ancestorFacts1 = $this->createMockAncestorFact($ancestor1Id);
        $ancestorFacts2 = $this->createMockAncestorFact($ancestor2Id);
        $ancestorFacts3 = $this->createMockAncestorFact($ancestor3Id);

        $this->policy->assertCanAttach(
            childId: $childId,
            parentId: $parentId,
            ancestors: [$ancestorFacts1, $ancestorFacts2, $ancestorFacts3],
        );

        $this->assertTrue(true);
    }

    private function createMockAncestorFact(CommitteeId $id): object
    {
        return new class ($id) {
            public function __construct(private CommitteeId $id) {}

            public function id(): CommitteeId
            {
                return $this->id;
            }
        };
    }
}
