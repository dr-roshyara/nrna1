<?php

namespace App\Application\Election\Facades;

use App\Application\Election\Deprecation\ElectionReadModel;
use App\Application\Election\Deprecation\QueryPolicyGuard;
use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Application\Election\Monitoring\ConstitutionalMetricsContract;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Models\Election;

/**
 * ElectionLifecycle Facade: Unified Single Source of Truth API
 *
 * This is the sole consumption point for all election lifecycle state queries.
 * Aggregates three architectural layers:
 * 1. ElectionLifecycleEngine (SSOT computation)
 * 2. ElectionReadModel (deprecation enforcement)
 * 3. QueryPolicyGuard (SQL-level protection)
 *
 * All consumption flows through this facade. Controllers, repositories, and
 * queries MUST NOT bypass this to access legacy fields directly.
 *
 * Reports all lifecycle evaluations to ConstitutionalMetrics for observability.
 *
 * Usage:
 *   $lifecycle = ElectionLifecycle::of($election);
 *   if ($lifecycle->canVote()) { ... }
 *   $state = $lifecycle->state();
 *   $snapshot = $lifecycle->snapshot();
 */
final class ElectionLifecycle
{
    private readonly ElectionLifecycleSnapshot $snapshot;

    private readonly ElectionLifecycleEngineImpl $engine;

    private readonly QueryPolicyGuard $queryGuard;

    private function __construct(
        private readonly Election $election,
        ?ElectionLifecycleSnapshot $snapshot = null
    ) {
        $this->engine = app(ElectionLifecycleEngineImpl::class);
        $this->queryGuard = app(QueryPolicyGuard::class);
        $this->snapshot = $snapshot ?? $this->engine->compute($this->election);
    }

    /**
     * Factory: Wrap an election with lifecycle context.
     *
     * Emits lifecycle evaluation metric AFTER snapshot computation
     * to preserve domain purity (computation separated from side-effects).
     *
     * @param Election $election
     * @return self
     */
    public static function of(Election $election): self
    {
        $lifecycle = new self($election);

        // Side-effect: record metric AFTER pure computation
        app(ConstitutionalMetricsContract::class)?->recordLifecycleEvaluation();

        return $lifecycle;
    }

    /**
     * Factory: Directly provide a pre-computed snapshot (optimization for batch operations).
     *
     * @param Election $election
     * @param ElectionLifecycleSnapshot $snapshot
     * @return self
     */
    public static function withSnapshot(Election $election, ElectionLifecycleSnapshot $snapshot): self
    {
        return new self($election, $snapshot);
    }

    /**
     * ============================================================================
     * SNAPSHOT ACCESS (Preferred, SSOT)
     * ============================================================================
     */

    /**
     * Get the complete lifecycle snapshot.
     *
     * This is the authoritative state snapshot. All information comes from
     * deterministic computation via ElectionLifecycleEngine.
     *
     * @return ElectionLifecycleSnapshot
     */
    public function snapshot(): ElectionLifecycleSnapshot
    {
        return $this->snapshot;
    }

    /**
     * Get the current lifecycle state.
     *
     * @return ElectionLifecycleState
     */
    public function state(): ElectionLifecycleState
    {
        return $this->snapshot->state;
    }

    /**
     * Check if voting is currently allowed.
     *
     * @return bool
     */
    public function canVote(): bool
    {
        return $this->snapshot->canVote;
    }

    /**
     * Check if editing is currently allowed.
     *
     * @return bool
     */
    public function canEdit(): bool
    {
        return $this->snapshot->canEdit;
    }

    /**
     * Check if voter management is allowed.
     *
     * @return bool
     */
    public function canManageVoters(): bool
    {
        return $this->snapshot->canManageVoters;
    }

    /**
     * Check if results can be published.
     *
     * @return bool
     */
    public function canPublishResults(): bool
    {
        return $this->snapshot->canPublishResults;
    }

    /**
     * Check if timeline (election dates) can be edited.
     *
     * Constitutional capability: Editorial authority over voting windows and setup timeline.
     * Allowed during: Draft, Approved, Rejected, Setup, ReadyForVoting (if not started)
     * Blocked during: Voting, Counting, Results, Terminal states
     *
     * @return bool
     */
    public function canEditTimeline(): bool
    {
        return $this->snapshot->canEditTimeline;
    }

    /**
     * Check if the election can be activated (transition to voting).
     *
     * CONSTITUTIONAL FIX for Phase 3.1.C: Replaces deprecated $election->status checks.
     *
     * @return bool
     */
    public function canActivate(): bool
    {
        return $this->isActionAllowed('open_voting');
    }

    /**
     * Check if this state is terminal (election is finished).
     *
     * @return bool
     */
    public function isTerminal(): bool
    {
        return $this->snapshot->state->isTerminal();
    }

    /**
     * Check if election is in setup phase.
     *
     * @return bool
     */
    public function isInSetup(): bool
    {
        return $this->snapshot->state->isInSetup();
    }

    /**
     * Check if election is in voting phase.
     *
     * @return bool
     */
    public function isVotingPhase(): bool
    {
        return $this->snapshot->state->isVotingPhase();
    }

    /**
     * Check if the election is locked (terminal state, no mutations allowed).
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->snapshot->isLocked;
    }

    /**
     * Check if the election is currently active (in voting phase and not yet concluded).
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->snapshot->state->value === 'voting_active';
    }

    /**
     * Get the reason this election is blocked from any action.
     *
     * Returns null if election is not blocked.
     *
     * @return string|null
     */
    public function blockedReason(): ?string
    {
        return $this->snapshot->blockedReason;
    }

    /**
     * Get allowed actions from current state.
     *
     * @return array
     */
    public function allowedActions(): array
    {
        return $this->snapshot->allowedActions;
    }

    /**
     * Check if a specific action is allowed.
     *
     * CONSTITUTIONAL AUTHORITY CHECK: Determines if an action is permitted in the current state.
     * This is NOT orchestration (state machine transitions). This is AUTHORITY (what may the user do?).
     *
     * @param string $action
     * @return bool
     */
    public function isActionAllowed(string $action): bool
    {
        return $this->snapshot->isActionAllowed($action);
    }


    /**
     * ============================================================================
     * QUERY GUARD ACCESS (SQL-level Protection)
     * ============================================================================
     */

    /**
     * Guard a query against deprecated field usage.
     *
     * Use this when constructing queries to prevent SQL-level bypassing
     * of the SSOT architecture.
     *
     * @param array $criteria Query criteria array (field => value)
     * @param string $context Human-readable context for logging
     * @return void
     * @throws \App\Exceptions\DeprecatedQueryException
     */
    public function assertQueryAllowed(array $criteria, string $context): void
    {
        $this->queryGuard->assertAllowedQuery($criteria, $context);
    }

    /**
     * ============================================================================
     * ELECTION ACCESS (Passthrough)
     * ============================================================================
     */

    /**
     * Get the wrapped election.
     *
     * @return Election
     */
    public function election(): Election
    {
        return $this->election;
    }

    /**
     * Get the election ID.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->election->id;
    }

    /**
     * Get the election name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->election->name;
    }
}
