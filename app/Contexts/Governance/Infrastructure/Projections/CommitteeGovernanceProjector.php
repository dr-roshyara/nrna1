<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Infrastructure\Projections;

use App\Contexts\Governance\Application\Ports\CommitteeGovernanceProjectorInterface;
use App\Contexts\Governance\Domain\Committee\CommitteeGovernanceInterpreter;
use App\Contexts\Governance\Domain\Committee\ViewModels\CommitteeGovernanceProjection;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeFacts;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\TermPeriod;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use DateTimeImmutable;

final class CommitteeGovernanceProjector implements CommitteeGovernanceProjectorInterface
{
    public function __construct(
        private readonly CommitteeGovernanceInterpreter $interpreter,
    ) {}

    /**
     * Rebuild governance projection for a single committee.
     *
     * @param CommitteeId $committeeId UUID-based committee ID
     * @param DateTimeImmutable $now Evaluation timestamp
     * @param string $eventId Source event identifier
     * @param DateTimeImmutable $eventOccurredAt When the source event occurred
     * @param string|null $generation Projection generation (UUIDv7 per rebuild session)
     */
    // Projection fields are eventually consistent — NOT authoritative domain truth. See docs/ARCHITECTURE.md
    public function rebuild(
        CommitteeId $committeeId,
        DateTimeImmutable $now,
        string $eventId,
        DateTimeImmutable $eventOccurredAt,
        ?string $generation = null,
    ): void {
        $committee = CommitteeModel::withoutGlobalScopes()->find($committeeId->value());

        if ($committee === null) {
            return;
        }

        $facts = $this->toCommitteeFacts($committee);

        $projection = $this->interpreter->interpret($facts, $now);

        $this->upsertProjection($committeeId, $projection, $eventId, $eventOccurredAt, $now, $generation);
    }

    /**
     * Process a domain event with idempotency check.
     */
    public function onEvent(
        CommitteeId $committeeId,
        DateTimeImmutable $now,
        string $eventId,
        string $eventType,
        DateTimeImmutable $eventOccurredAt,
        ?string $generation = null,
    ): void {
        if ($this->alreadyProcessed($eventId)) {
            return;
        }

        $this->rebuild($committeeId, $now, $eventId, $eventOccurredAt, $generation);
        $this->markProcessed($eventId, $eventType, $committeeId->value());
    }

    /**
     * Rebuild all committees.
     *
     * @param DateTimeImmutable $now Evaluation timestamp
     * @param string|null $generation Projection generation (UUIDv7 per rebuild session)
     */
    public function rebuildAll(DateTimeImmutable $now, ?string $generation = null): void
    {
        $committees = CommitteeModel::withoutGlobalScopes()->get();

        foreach ($committees as $committee) {
            $committeeId = CommitteeId::fromString($committee->id);

            $this->rebuild(
                $committeeId,
                $now,
                'rebuild-all',
                $now,
                $generation,
            );
        }
    }

    private function toCommitteeFacts(CommitteeModel $committee): CommitteeFacts
    {
        $term = null;
        if ($committee->formation_date !== null && $committee->term_end_date !== null) {
            $term = new TermPeriod(
                start: DateTimeImmutable::createFromMutable($committee->formation_date->toDateTime()),
                end: DateTimeImmutable::createFromMutable($committee->term_end_date->toDateTime()),
            );
        }

        $operationalState = strtoupper($committee->status);

        return new CommitteeFacts(
            id: CommitteeId::fromString($committee->id),
            operationalState: $operationalState,
            term: $term,
            parentId: $committee->parent_committee_id !== null
                ? CommitteeId::fromString($committee->parent_committee_id)
                : null,
        );
    }

    // Projection fields are eventually consistent — NOT authoritative domain truth. See docs/ARCHITECTURE.md
    private function upsertProjection(
        CommitteeId $committeeId,
        CommitteeGovernanceProjection $projection,
        string $eventId,
        DateTimeImmutable $eventOccurredAt,
        DateTimeImmutable $now,
        ?string $generation = null,
    ): void {
        CommitteeGovernanceProjectionModel::updateOrCreate(
            ['committee_id' => $committeeId->value()],
            [
                'projection_version' => 1,
                'last_event_id' => $eventId,
                'last_event_occurred_at' => $eventOccurredAt,
                'operational_state' => $projection->operationalState->value,
                'temporal_state' => $projection->temporalState->value,
                'legitimacy' => $projection->legitimacy->value,
                'can_act' => $projection->canAct(),
                'is_fully_operational' => $projection->isFullyOperational(),
                'evaluated_at' => $now,
                'rebuilt_at' => $now,
                'projection_generation' => $generation,
                'projection_schema_version' => 1,
            ],
        );
    }

    private function alreadyProcessed(string $eventId): bool
    {
        return ProcessedEventModel::where('event_id', $eventId)->exists();
    }

    private function markProcessed(string $eventId, string $eventType, string $committeeId): void
    {
        ProcessedEventModel::create([
            'event_id' => $eventId,
            'event_type' => $eventType,
            'committee_id' => $committeeId,
            'processed_at' => new DateTimeImmutable(),
        ]);
    }
}
