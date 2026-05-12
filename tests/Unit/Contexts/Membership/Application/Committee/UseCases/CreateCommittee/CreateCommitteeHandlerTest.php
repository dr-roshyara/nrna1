<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Committee\UseCases\CreateCommittee;

use App\Contexts\Membership\Application\Committee\Ports\CommitteeRepositoryPort;
use App\Contexts\Membership\Application\Committee\Ports\EventBusPort;
use App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeCommand;
use App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeHandler;
use App\Contexts\Membership\Domain\Committee\Events\CommitteeEstablished;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\Jurisdiction;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\FakeEventBus;
use Tests\Doubles\InMemoryCommitteeRepository;

final class CreateCommitteeHandlerTest extends TestCase
{
    private InMemoryCommitteeRepository $repository;
    private FakeEventBus $eventBus;
    private CreateCommitteeHandler $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCommitteeRepository();
        $this->eventBus = new FakeEventBus();
        $this->handler = new CreateCommitteeHandler($this->repository, $this->eventBus);
    }

    public function test_it_creates_committee_and_publishes_event(): void
    {
        $committeeId = CommitteeId::generate();
        $jurisdiction = Jurisdiction::district('DIST-123');
        $establishedAt = new DateTimeImmutable('2024-01-01 10:00:00');

        $command = new CreateCommitteeCommand(
            committeeId: $committeeId,
            name: 'Ethics Committee',
            jurisdiction: $jurisdiction,
            establishedAt: $establishedAt,
        );

        $result = $this->handler->handle($command);

        $this->assertSame($committeeId->value(), $result->committee->getId()->value());
        $this->assertSame('Ethics Committee', $result->committee->getName());
        $this->assertTrue($jurisdiction->equals($result->committee->getJurisdiction()));

        // Check repository persistence
        $retrieved = $this->repository->get($committeeId);
        $this->assertSame($committeeId->value(), $retrieved->getId()->value());

        // Check event was published
        $this->assertCount(1, $this->eventBus->publishedEvents);
        $event = $this->eventBus->publishedEvents[0];
        $this->assertInstanceOf(CommitteeEstablished::class, $event);
        $this->assertSame($committeeId->value(), $event->committeeId);
        $this->assertSame('Ethics Committee', $event->name);
        $this->assertSame('district:DIST-123', $event->jurisdiction);
    }

    public function test_it_creates_national_committee(): void
    {
        $committeeId = CommitteeId::generate();
        $jurisdiction = Jurisdiction::national();
        $establishedAt = new DateTimeImmutable();

        $command = new CreateCommitteeCommand(
            committeeId: $committeeId,
            name: 'National Executive Committee',
            jurisdiction: $jurisdiction,
            establishedAt: $establishedAt,
        );

        $result = $this->handler->handle($command);

        $this->assertSame('National Executive Committee', $result->committee->getName());
        $this->assertSame('national', $result->committee->getJurisdiction()->scope);
        $this->assertNull($result->committee->getJurisdiction()->reference);

        $event = $this->eventBus->publishedEvents[0];
        $this->assertSame('national', $event->jurisdiction);
    }
}
