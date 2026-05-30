<?php

namespace Tests\Unit\Elections\Events;

use App\Contexts\Elections\Domain\Events\ResultsUnpublishedEvent;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ResultsUnpublishedEventTest extends TestCase
{
    public function test_event_can_be_created_with_all_fields(): void
    {
        $electionId = 'election-id-123';
        $unpublishedBy = 'user-id-456';
        $unpublishedAt = new DateTimeImmutable('2026-05-30 12:00:00');
        $previousState = 'results_published';

        $event = new ResultsUnpublishedEvent(
            electionId: $electionId,
            unpublishedBy: $unpublishedBy,
            unpublishedAt: $unpublishedAt,
            previousState: $previousState
        );

        $this->assertEquals($electionId, $event->electionId());
        $this->assertEquals($unpublishedBy, $event->unpublishedBy());
        $this->assertEquals($unpublishedAt, $event->unpublishedAt());
        $this->assertEquals($previousState, $event->previousState());
    }

    public function test_event_is_immutable(): void
    {
        $event = new ResultsUnpublishedEvent(
            electionId: 'election-123',
            unpublishedBy: 'user-456',
            unpublishedAt: new DateTimeImmutable(),
            previousState: 'results_published'
        );

        $reflection = new \ReflectionClass($event);

        foreach (['electionId', 'unpublishedBy', 'unpublishedAt', 'previousState'] as $property) {
            $prop = $reflection->getProperty($property);
            $this->assertTrue($prop->isReadOnly(), "Property {$property} should be readonly");
        }

        $this->assertNotNull($event);
    }
}
