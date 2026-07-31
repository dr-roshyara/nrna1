<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Repositories;

use App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessId;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessState;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessStatus;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Infrastructure\Models\AdjudicationProcessModel;
use App\Contexts\Adjudication\Infrastructure\Persistence\AdjudicationProcessMapper;
use App\Services\TenantContext;
use DateTimeImmutable;
use Illuminate\Support\Str;

/**
 * Eloquent realization of the process store (WP-2, EPIC-004K §11).
 *
 * Tenant scope is resolved here, keeping the Application layer tenant-free
 * (ADR-T16). The surface is exactly the conduct's needs — no query zoo.
 */
final class EloquentAdjudicationProcessStore implements AdjudicationProcessStore
{
    public function __construct(
        private readonly AdjudicationProcessMapper $mapper,
        private readonly AdjudicationProcessModel $model,
    ) {
    }

    public function nextIdentity(): AdjudicationProcessId
    {
        return AdjudicationProcessId::fromString((string) Str::uuid());
    }

    public function activeForChallenge(ChallengeRef $challenge): ?AdjudicationProcessState
    {
        $row = $this->nonTerminalQuery()
            ->where('challenge_ref', $challenge->toString())
            ->first();

        return $row === null ? null : $this->mapper->toState($row);
    }

    public function latestForChallenge(ChallengeRef $challenge): ?AdjudicationProcessState
    {
        $row = $this->model->newQuery()
            ->where('challenge_ref', $challenge->toString())
            ->orderByDesc('opened_at')
            ->first();

        return $row instanceof AdjudicationProcessModel ? $this->mapper->toState($row) : null;
    }

    public function save(AdjudicationProcessState $state): void
    {
        $row = $this->mapper->toRow($state, TenantContext::require());

        // One write per conduct step — the conclusion, its considered set and the
        // deciding authority commit together (PM-5).
        $this->model->newQuery()->updateOrInsert(
            ['id' => $row['id']],
            $this->forStorage($row),
        );
    }

    /** @return list<AdjudicationProcessState> */
    public function dueForHorizon(DateTimeImmutable $asOf): array
    {
        // The horizon's DURATION is Q-2's business policy; this store answers only
        // which non-terminal processes opened before the cut-off the caller passes.
        // Timer execution + the configured MAD land in WP-6.
        $rows = $this->nonTerminalQuery()
            ->where('opened_at', '<=', $asOf)
            ->get();

        $due = [];
        foreach ($rows as $row) {
            $due[] = $this->mapper->toState($row);
        }

        return $due;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function forStorage(array $row): array
    {
        $storable = $row;
        unset($storable['id']);

        $storable['admitted_evidence'] = json_encode($row['admitted_evidence']);
        $storable['considered_evidence'] = $row['considered_evidence'] === null
            ? null
            : json_encode($row['considered_evidence']);
        $openedAt = $row['opened_at'];
        $concludedAt = $row['concluded_at'];
        $storable['opened_at'] = $openedAt instanceof DateTimeImmutable ? $openedAt->format(DATE_ATOM) : null;
        $storable['concluded_at'] = $concludedAt instanceof DateTimeImmutable ? $concludedAt->format(DATE_ATOM) : null;
        $storable['updated_at'] = now();
        $storable['created_at'] = now();

        return $storable;
    }

    /**
     * Tenant-scoped query restricted to NON-TERMINAL processes — the "active"
     * scope PM-1 and §6's guard are stated in terms of.
     *
     * Expressed as chained `!=` rather than `whereNotIn` so the Eloquent builder
     * keeps its model type (the array forms are forwarded to the query builder,
     * which erases it).
     *
     * @return \Illuminate\Database\Eloquent\Builder<AdjudicationProcessModel>
     */
    private function nonTerminalQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->model->newQuery()
            ->where('organisation_id', TenantContext::require());

        foreach (AdjudicationProcessStatus::cases() as $status) {
            if ($status->isTerminal()) {
                $query->where('status', '!=', $status->value);
            }
        }

        return $query;
    }
}
