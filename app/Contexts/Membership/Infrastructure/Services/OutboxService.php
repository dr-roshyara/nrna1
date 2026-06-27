<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Str;

final class OutboxService
{
    public function store(
        object $event,
        TenantId $tenantId,
    ): void {
        \DB::table('outbox_events')->insert([
            'id' => (string) Str::uuid(),
            'organisation_id' => $tenantId->value(),
            'type' => class_basename($event),
            'payload' => json_encode($this->serializeEvent($event)),
            'status' => 'pending',
            'created_at' => now(),
        ]);
    }

    public function markAsProcessed(string $eventId): void
    {
        \DB::table('outbox_events')
            ->where('id', $eventId)
            ->update([
                'status' => 'completed',
                'processed_at' => now(),
            ]);
    }

    public function markAsFailed(string $eventId): void
    {
        \DB::table('outbox_events')
            ->where('id', $eventId)
            ->update([
                'status' => 'failed',
                'processed_at' => now(),
            ]);
    }

    public function getPendingEvents(TenantId $tenantId): array
    {
        return \DB::table('outbox_events')
            ->where('organisation_id', $tenantId->value())
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->get()
            ->toArray();
    }

    private function serializeEvent(object $event): array
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
            }

            $data[$property->getName()] = $value;
        }

        return $data;
    }
}
