<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Services;

use App\Contexts\Governance\Application\DTOs\CommitteeHierarchyRecord;
use App\Contexts\Governance\Application\DTOs\CommitteeTreeNode;
use App\Contexts\Governance\Domain\Committee\ViewModels\CommitteeGovernanceProjection;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId as UuidCommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\ConstitutionalLegitimacy;
use App\Contexts\Membership\Domain\ValueObjects\StructuralOperationalState;
use App\Contexts\Membership\Domain\ValueObjects\TemporalGovernanceState;
use DateTimeImmutable;
use DomainException;

final class CommitteeHierarchyBuilder
{
    /** @var array<string, CommitteeHierarchyRecord> */
    private array $nodesById = [];

    /** @var array<string, CommitteeTreeNode> */
    private array $nodes = [];

    /** @var array<string, list<string>> children by parent ID */
    private array $childrenByParentId = [];

    /** @var string[] */
    private array $orphanLog = [];

    /**
     * Build a tree from flat records with embedded governance state.
     *
     * Algorithm: O(n) three-pass approach:
     * 1. Cycle detection (DFS)
     * 2. Create all nodes (immutable, empty children)
     * 3. Link children to parents (recreate parents with children attached)
     *
     * Governance state is read directly from each CommitteeHierarchyRecord,
     * which was pre-computed by the projector — no interpretation at query time.
     *
     * @param CommitteeHierarchyRecord[] $records
     * @return CommitteeTreeNode[]
     */
    public function buildTree(array $records): array
    {
        if ($records === []) {
            return [];
        }

        $this->nodesById = [];
        $this->nodes = [];
        $this->childrenByParentId = [];
        $this->orphanLog = [];

        // Build record lookup
        foreach ($records as $record) {
            $this->nodesById[$record->id->value()] = $record;
        }

        // Pass 1: Cycle detection (DFS with recursion stack)
        $visited = [];
        $recursionStack = [];
        foreach ($records as $record) {
            $this->detectCycle($record, $visited, $recursionStack);
        }

        // Pass 2: Create all nodes with empty children
        foreach ($records as $record) {
            $recordId = $record->id->value();
            $governance = $this->toGovernanceProjection($record);

            $this->nodes[$recordId] = new CommitteeTreeNode(
                id: UuidCommitteeId::fromString($recordId),
                name: $record->name,
                level: $record->level,
                parentId: $record->parentId !== null
                    ? UuidCommitteeId::fromString($record->parentId->value())
                    : null,
                governance: $governance,
                children: [],
            );

            // Build parent-child index
            if ($record->parentId !== null) {
                $this->childrenByParentId[$record->parentId->value()][] = $recordId;
            }
        }

        // Pass 3: Link children to parents (sorted by parent level descending)
        // so children (higher level) are linked before their own parents
        $parentLevels = [];
        foreach ($this->childrenByParentId as $parentId => $childIds) {
            $parentRecord = $this->nodesById[$parentId] ?? null;
            $parentLevels[$parentId] = $parentRecord?->level ?? 0;
        }
        uksort($this->childrenByParentId, function (string $a, string $b) use ($parentLevels): int {
            return ($parentLevels[$b] ?? 0) <=> ($parentLevels[$a] ?? 0);
        });

        $quarantined = [];
        foreach ($this->childrenByParentId as $parentId => $childIds) {
            $parentNode = $this->nodes[$parentId] ?? null;

            // If parent doesn't exist, quarantine all its children
            if ($parentNode === null) {
                foreach ($childIds as $childId) {
                    $childRecord = $this->nodesById[$childId] ?? null;
                    if ($childRecord !== null) {
                        $this->orphanLog[] = sprintf(
                            'Orphan quarantined: committee "%s" (%s) references non-existent parent "%s"',
                            $childRecord->name,
                            $childId,
                            $parentId
                        );
                    }
                    $quarantined[$childId] = true;
                    unset($this->nodes[$childId]);
                }
                continue;
            }

            // Collect valid (non-quarantined) children
            $childNodes = [];
            foreach ($childIds as $childId) {
                $childNode = $this->nodes[$childId] ?? null;
                if ($childNode !== null) {
                    $childNodes[] = $childNode;
                }
            }

            if ($childNodes === []) {
                continue;
            }

            // Recreate parent with children attached
            $this->nodes[$parentId] = new CommitteeTreeNode(
                id: $parentNode->id,
                name: $parentNode->name,
                level: $parentNode->level,
                parentId: $parentNode->parentId,
                governance: $parentNode->governance,
                children: $childNodes,
            );
        }

        // Collect roots (records with no parent or whose parent was quarantined)
        $roots = [];
        foreach ($records as $record) {
            $recordId = $record->id->value();
            if (isset($quarantined[$recordId])) {
                continue;
            }
            if ($record->parentId === null || !isset($this->nodesById[$record->parentId->value()])) {
                $node = $this->nodes[$recordId] ?? null;
                if ($node !== null) {
                    $roots[] = $node;
                }
            }
        }

        return $roots;
    }

    /**
     * @return string[]
     */
    public function getOrphanLog(): array
    {
        return $this->orphanLog;
    }

    /**
     * Construct a governance projection from a record's pre-computed fields.
     *
     * This is a pure data mapping — NOT policy interpretation.
     * The governance state was already computed by the projector.
     * Here we only hydrate enum types from stored string values.
     */
    private function toGovernanceProjection(CommitteeHierarchyRecord $record): CommitteeGovernanceProjection
    {
        return new CommitteeGovernanceProjection(
            committeeId: UuidCommitteeId::fromString($record->id->value()),
            operationalState: StructuralOperationalState::from($record->operationalState),
            temporalState: TemporalGovernanceState::from($record->temporalState),
            legitimacy: ConstitutionalLegitimacy::from($record->legitimacy),
            evaluatedAt: new DateTimeImmutable(),
        );
    }

    /**
     * DFS-based cycle detection.
     *
     * @param array<string, bool> $visited
     * @param array<string, bool> $recursionStack
     */
    private function detectCycle(
        CommitteeHierarchyRecord $record,
        array &$visited,
        array &$recursionStack,
    ): void {
        $id = $record->id->value();

        if (isset($recursionStack[$id])) {
            throw new DomainException('Cycle detected in committee hierarchy');
        }

        if (isset($visited[$id])) {
            return;
        }

        $visited[$id] = true;
        $recursionStack[$id] = true;

        if ($record->parentId !== null) {
            $parentId = $record->parentId->value();
            $parentRecord = $this->nodesById[$parentId] ?? null;

            if ($parentRecord !== null) {
                $this->detectCycle($parentRecord, $visited, $recursionStack);
            }
        }

        unset($recursionStack[$id]);
    }
}
