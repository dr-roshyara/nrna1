<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Traits;

/**
 * RecordsEvents Trait
 *
 * Allows domain models to record domain events.
 * Events are stored in memory and dispatched explicitly.
 *
 * DDD Pattern: This trait handles event RECORDING (domain concern).
 * The model handles event DISPATCHING (infrastructure concern).
 */
trait RecordsEvents
{
    /**
     * Domain events waiting to be dispatched
     *
     * @var array
     */
    protected array $recordedEvents = [];

    /**
     * Record a domain event
     *
     * @param object $event Domain event object
     * @return void
     */
    protected function recordThat(object $event): void
    {
        $this->recordedEvents[] = $event;
    }

    /**
     * Get all recorded events
     *
     * @return array
     */
    public function getRecordedEvents(): array
    {
        return $this->recordedEvents;
    }

    /**
     * Dispatch all recorded events
     * Called by model after successful save
     *
     * @return void
     */
    public function dispatchRecordedEvents(): void
    {
        foreach ($this->recordedEvents as $event) {
            event($event);
        }

        $this->recordedEvents = [];
    }

    /**
     * Clear recorded events without dispatching
     * Useful for testing
     *
     * @return void
     */
    public function clearRecordedEvents(): void
    {
        $this->recordedEvents = [];
    }
}
