<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee\ValueObjects;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeFacts;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\StructuralOperationalState;
use App\Contexts\Membership\Domain\Committee\ValueObjects\TermPeriod;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CommitteeFactsTest extends TestCase
{
    public function test_it_contains_complete_policy_snapshot(): void
    {
        $id = CommitteeId::generate();
        $state = StructuralOperationalState::ACTIVE;
        $term = TermPeriod::from(
            new DateTimeImmutable('2026-01-01T00:00:00Z'),
            new DateTimeImmutable('2026-12-31T23:59:59Z'),
        );
        $parentId = CommitteeId::generate();

        $facts = new CommitteeFacts(
            id: $id,
            operationalState: $state->value,
            term: $term,
            parentId: $parentId,
        );

        $this->assertTrue($facts->id->equals($id));
        $this->assertEquals('ACTIVE', $facts->operationalState);
        $this->assertTrue($facts->term->equals($term));
        $this->assertTrue($facts->parentId->equals($parentId));
    }

    public function test_it_represents_committee_without_term(): void
    {
        $id = CommitteeId::generate();

        $facts = new CommitteeFacts(
            id: $id,
            operationalState: StructuralOperationalState::ACTIVE->value,
            term: null,
            parentId: null,
        );

        $this->assertTrue($facts->id->equals($id));
        $this->assertNull($facts->term);
        $this->assertNull($facts->parentId);
    }

    public function test_it_represents_suspended_committee(): void
    {
        $id = CommitteeId::generate();

        $facts = new CommitteeFacts(
            id: $id,
            operationalState: StructuralOperationalState::SUSPENDED->value,
            term: null,
            parentId: null,
        );

        $this->assertEquals('SUSPENDED', $facts->operationalState);
    }

    public function test_it_represents_dissolved_committee(): void
    {
        $id = CommitteeId::generate();

        $facts = new CommitteeFacts(
            id: $id,
            operationalState: StructuralOperationalState::DISSOLVED->value,
            term: null,
            parentId: null,
        );

        $this->assertEquals('DISSOLVED', $facts->operationalState);
    }

    public function test_it_does_not_expose_mutability(): void
    {
        $id = CommitteeId::generate();
        $facts = new CommitteeFacts(
            id: $id,
            operationalState: StructuralOperationalState::ACTIVE->value,
            term: null,
            parentId: null,
        );

        $this->assertIsObject($facts);
        $this->assertEquals('ACTIVE', $facts->operationalState);
    }
}
