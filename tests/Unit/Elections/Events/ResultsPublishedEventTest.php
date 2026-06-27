<?php

namespace Tests\Unit\Elections\Events;

use App\Contexts\Elections\Domain\Events\ResultsPublishedEvent;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ResultsPublishedEventTest extends TestCase
{
    public function test_event_can_be_created_with_all_fields(): void
    {
        $electionId = 'election-id-123';
        $publishedBy = 'user-id-456';
        $publishedAt = new DateTimeImmutable('2026-05-30 12:00:00');
        $state = 'results_published';

        $event = new ResultsPublishedEvent(
            electionId: $electionId,
            publishedBy: $publishedBy,
            publishedAt: $publishedAt,
            state: $state
        );

        $this->assertEquals($electionId, $event->electionId());
        $this->assertEquals($publishedBy, $event->publishedBy());
        $this->assertEquals($publishedAt, $event->publishedAt());
        $this->assertEquals($state, $event->state());
    }

    public function test_event_is_immutable(): void
    {
        $event = new ResultsPublishedEvent(
            electionId: 'election-123',
            publishedBy: 'user-456',
            publishedAt: new DateTimeImmutable(),
            state: 'results_published'
        );

        $reflection = new \ReflectionClass($event);

        foreach (['electionId', 'publishedBy', 'publishedAt', 'state'] as $property) {
            $prop = $reflection->getProperty($property);
            $this->assertTrue($prop->isReadOnly(), "Property {$property} should be readonly");
        }

        $this->assertNotNull($event);
    }

    public function test_event_contains_only_election_publication_concepts(): void
    {
        $event = new ResultsPublishedEvent(
            electionId: 'election-123',
            publishedBy: 'user-456',
            publishedAt: new DateTimeImmutable(),
            state: 'results_published'
        );

        $this->assertFalse(method_exists($event, 'organisationId'), 'Event should not contain organisationId');
        $this->assertFalse(method_exists($event, 'reason'), 'Event should not contain reason');
        $this->assertFalse(method_exists($event, 'approvalRequired'), 'Event should not contain approval metadata');

        $this->assertTrue(method_exists($event, 'electionId'), 'Event must contain electionId');
        $this->assertTrue(method_exists($event, 'publishedBy'), 'Event must contain publishedBy');
        $this->assertTrue(method_exists($event, 'publishedAt'), 'Event must contain publishedAt');
        $this->assertTrue(method_exists($event, 'state'), 'Event must contain state');
    }
}
