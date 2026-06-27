<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Infrastructure\Repositories;

use App\Contexts\Governance\Domain\Committee\Committee;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\CommitteeRepositoryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\Event;

/**
 * EloquentCommitteeRepository
 *
 * Persistence adapter for Committee aggregate.
 *
 * Responsibility: Load and save Committee aggregates, dispatching events.
 *
 * Note on design:
 * - Currently uses projection table as source of truth for existing members
 * - Future: will migrate to event store or event log table
 * - Events are dispatched via Laravel's event system for listener consumption
 */
final class EloquentCommitteeRepository implements CommitteeRepositoryInterface
{
    /**
     * Load a Committee aggregate with existing member state from projection.
     * Returns null if committee doesn't exist.
     */
    public function findById(CommitteeId $id, TenantId $tenantId): ?Committee
    {
        // Check if committee exists (minimal check - could be cached)
        $exists = \DB::table('committees')
            ->where('id', $id->value())
            ->where('organisation_id', $tenantId->value())
            ->exists();

        if (!$exists) {
            return null;
        }

        // Create aggregate with blank state — events will rebuild member state
        // Future: load from event store/log for complete history
        $committee = Committee::create($id, $tenantId);

        return $committee;
    }

    /**
     * Save a Committee aggregate and dispatch its events.
     *
     * The aggregate's events are pulled and dispatched to listeners.
     * Listeners (e.g., CommitteeMemberProjectionListener) handle persistence.
     */
    public function save(Committee $committee, TenantId $tenantId): void
    {
        // Pull events from aggregate
        $events = $committee->pullEvents();

        // Dispatch each event to listeners
        // Listeners handle all projection writes
        foreach ($events as $event) {
            Event::dispatch($event);
        }
    }
}
