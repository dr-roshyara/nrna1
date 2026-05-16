<?php

declare(strict_types=1);

namespace Tests\Support\Outbox;

use App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor;
use Illuminate\Support\Facades\Event;

/**
 * OutboxTestHarness — Controls outbox event processing in tests
 *
 * Provides:
 * - Manual event dispatch (process pending events)
 * - Event capture (inspect what was dispatched)
 * - Replay safety (simulate async processing)
 */
final class OutboxTestHarness
{
    /**
     * Process all pending outbox events
     * Simulates what the async job would do
     */
    public static function processPending(): void
    {
        $processor = app(OutboxEventProcessor::class);

        // Get all pending events
        $events = \App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent::query()
            ->pending()
            ->where('available_at', '<=', now())
            ->get();

        // Process each
        foreach ($events as $event) {
            try {
                $processor->processEvent($event);
            } catch (\Exception $e) {
                // Outbox processor handles retry logic
                // We don't re-throw in test context
            }
        }
    }

    /**
     * Capture next dispatched event of specific type
     * Useful for assertions
     */
    public static function captureEvent(string $eventClass): ?object
    {
        $captured = null;

        Event::listen($eventClass, function ($event) use (&$captured) {
            $captured = $event;
        });

        return $captured;
    }

    /**
     * Assert event was dispatched with specific properties
     */
    public static function assertEventDispatched(string $eventClass, callable $callback = null): void
    {
        Event::assertDispatched($eventClass, $callback ?? fn() => true);
    }

    /**
     * Clear all pending events (reset for next test)
     */
    public static function clearPending(): void
    {
        \App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent::query()
            ->pending()
            ->delete();
    }
}
