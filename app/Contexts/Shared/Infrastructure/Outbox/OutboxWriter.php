<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Outbox;

use Illuminate\Support\Str;

final class OutboxWriter
{
    public function store(object $event, string $organisationId): void
    {
        OutboxEvent::create([
            'id' => (string) Str::uuid(),
            'event_id' => (string) Str::uuid(),
            'organisation_id' => $organisationId,
            'aggregate_type' => $this->getAggregateType($event),
            'aggregate_id' => $this->getAggregateId($event),
            'event_type' => class_basename($event),
            'payload' => json_encode($this->serialize($event)),
            'status' => 'pending',
            'available_at' => now(),
        ]);
    }

    private function getAggregateType(object $event): string
    {
        $eventClass = class_basename($event);

        return match($eventClass) {
            'FeePaid', 'FeeWaived' => 'Fee',
            'MemberActivated', 'MemberSuspended', 'MemberArchived' => 'Member',
            'ApplicationSubmitted', 'ApplicationApproved', 'ApplicationRejected' => 'Application',
            default => 'Unknown',
        };
    }

    private function getAggregateId(object $event): string
    {
        // Extract aggregate ID from event - adapt based on your event structure
        if (method_exists($event, 'getFeeId')) {
            return (string) $event->getFeeId()->value();
        }
        if (method_exists($event, 'getMemberId')) {
            return (string) $event->getMemberId()->value();
        }
        if (method_exists($event, 'getApplicationId')) {
            return (string) $event->getApplicationId()->value();
        }

        return 'unknown';
    }

    private function serialize(object $event): array
    {
        $reflection = new \ReflectionObject($event);
        $data = [];

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($event);

            // Convert domain objects to strings
            if (method_exists($value, 'value')) {
                $value = $value->value();
            } elseif (method_exists($value, 'toString')) {
                $value = $value->toString();
            } elseif ($value instanceof \DateTimeImmutable) {
                $value = $value->format('Y-m-d H:i:s');
            } elseif ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $data[$property->getName()] = $value;
        }

        return $data;
    }
}
