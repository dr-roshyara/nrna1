<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application\DTOs;

use App\Contexts\Governance\Application\DTOs\CommitteeHierarchyRecord;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CommitteeHierarchyRecordTest extends TestCase
{
    public function test_creates_record_with_all_fields(): void
    {
        $id = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $termStart = new DateTimeImmutable('2025-01-01');
        $termEnd = new DateTimeImmutable('2027-12-31');

        $record = new CommitteeHierarchyRecord(
            id: $id,
            name: 'ICC Committee',
            level: 0,
            parentId: null,
            operationalState: 'ACTIVE',
            temporalState: 'VALID',
            legitimacy: 'LEGITIMATE',
            canAct: true,
            isFullyOperational: true,
            projectionVersion: 1,
            termStart: $termStart,
            termEnd: $termEnd,
            pendingApprovals: 0,
        );

        $this->assertTrue($record->id->equals($id));
        $this->assertSame('ICC Committee', $record->name);
        $this->assertSame(0, $record->level);
        $this->assertNull($record->parentId);
        $this->assertSame('ACTIVE', $record->operationalState);
        $this->assertSame('VALID', $record->temporalState);
        $this->assertSame('LEGITIMATE', $record->legitimacy);
        $this->assertTrue($record->canAct);
        $this->assertTrue($record->isFullyOperational);
        $this->assertSame(1, $record->projectionVersion);
        $this->assertSame($termStart, $record->termStart);
        $this->assertSame($termEnd, $record->termEnd);
        $this->assertSame(0, $record->pendingApprovals);
    }

    public function test_equals_compares_all_fields(): void
    {
        $id1 = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $id2 = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FBW');

        $termStart = new DateTimeImmutable('2025-01-01');
        $termEnd = new DateTimeImmutable('2027-12-31');

        $record1 = new CommitteeHierarchyRecord(
            id: $id1,
            name: 'ICC Committee',
            level: 0,
            parentId: null,
            operationalState: 'ACTIVE',
            temporalState: 'VALID',
            legitimacy: 'LEGITIMATE',
            canAct: true,
            isFullyOperational: true,
            projectionVersion: 1,
            termStart: $termStart,
            termEnd: $termEnd,
            pendingApprovals: 0,
        );

        $record2 = new CommitteeHierarchyRecord(
            id: $id1,
            name: 'ICC Committee',
            level: 0,
            parentId: null,
            operationalState: 'ACTIVE',
            temporalState: 'VALID',
            legitimacy: 'LEGITIMATE',
            canAct: true,
            isFullyOperational: true,
            projectionVersion: 1,
            termStart: $termStart,
            termEnd: $termEnd,
            pendingApprovals: 0,
        );

        $record3 = new CommitteeHierarchyRecord(
            id: $id2,
            name: 'ICC Committee',
            level: 0,
            parentId: null,
            operationalState: 'ACTIVE',
            temporalState: 'VALID',
            legitimacy: 'LEGITIMATE',
            canAct: true,
            isFullyOperational: true,
            projectionVersion: 1,
            termStart: $termStart,
            termEnd: $termEnd,
            pendingApprovals: 0,
        );

        $this->assertTrue($record1->equals($record2));
        $this->assertFalse($record1->equals($record3));
    }

    public function test_is_immutable_readonly(): void
    {
        $reflection = new \ReflectionClass(CommitteeHierarchyRecord::class);

        $this->assertTrue($reflection->isReadOnly());

        foreach ($reflection->getProperties() as $prop) {
            $this->assertTrue(
                $prop->isReadOnly(),
                sprintf('Property $%s must be readonly', $prop->getName())
            );
        }

        $methods = array_map(
            fn(\ReflectionMethod $m) => $m->getName(),
            $reflection->getMethods()
        );

        $setterPatterns = ['set', 'add', 'remove', 'update'];
        foreach ($methods as $method) {
            foreach ($setterPatterns as $pattern) {
                $this->assertStringNotContainsString(
                    $pattern,
                    $method,
                    sprintf('Method "%s" looks like a mutator on a read-only DTO', $method)
                );
            }
        }
    }
}
