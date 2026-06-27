<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Committee\UseCases\CreateCommittee;

use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeCommand;
use App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeHandler;
use App\Contexts\Membership\Domain\Committee\Policies\GovernanceMatrix;
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use DomainException;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\FakeEventBus;
use Tests\Doubles\InMemoryCommitteeRepository;

final class CreateCommitteeUseCaseTest extends TestCase
{
    public function test_creates_committee_with_valid_assignment(): void
    {
        $policy = new GovernancePolicy(
            GovernanceMatrix::fromRows([
                ['level' => 2, 'is_active' => true],
            ])
        );

        $handler = new CreateCommitteeHandler(
            new InMemoryCommitteeRepository(),
            new FakeEventBus(),
            $policy
        );

        $id = $handler->handle(new CreateCommitteeCommand(
            'Test Committee',
            new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
        ));

        $this->assertNotNull($id);
        $this->assertIsString($id);
    }

    public function test_rejects_invalid_assignment(): void
    {
        $policy = new GovernancePolicy(
            GovernanceMatrix::fromRows([
                ['level' => 2, 'is_active' => false],
            ])
        );

        $handler = new CreateCommitteeHandler(
            new InMemoryCommitteeRepository(),
            new FakeEventBus(),
            $policy
        );

        $this->expectException(DomainException::class);

        $handler->handle(new CreateCommitteeCommand(
            'Test Committee',
            new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
        ));
    }

    public function test_committee_emits_established_event(): void
    {
        $policy = new GovernancePolicy(
            GovernanceMatrix::fromRows([
                ['level' => 2, 'is_active' => true],
            ])
        );

        $eventBus = new FakeEventBus();
        $handler = new CreateCommitteeHandler(
            new InMemoryCommitteeRepository(),
            $eventBus,
            $policy
        );

        $handler->handle(new CreateCommitteeCommand(
            'Test Committee',
            new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
        ));

        $this->assertTrue($eventBus->hasPublished('CommitteeEstablished'));
    }
}
