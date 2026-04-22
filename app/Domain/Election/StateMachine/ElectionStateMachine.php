<?php

namespace App\Domain\Election\StateMachine;

use App\Domain\Election\StateMachine\Exceptions\InvalidTransitionException;
use App\Models\Election;
use App\Models\ElectionStateTransition;
use Illuminate\Support\Facades\DB;

class ElectionStateMachine
{
    private const TRANSITIONS = [
        'administration' => ['nomination'],
        'nomination' => ['voting'],
        'voting' => ['results_pending'],
        'results_pending' => ['results'],
        'results' => [],
    ];

    public function __construct(
        private readonly Election $election
    ) {
    }

    public function getCurrentState(): string
    {
        return $this->election->current_state;
    }

    public function canTransition(string $toState): bool
    {
        $fromState = $this->getCurrentState();
        $validTransitions = self::TRANSITIONS[$fromState] ?? [];

        return in_array($toState, $validTransitions);
    }

    public function validateTransition(string $toState): void
    {
        if (!$this->canTransition($toState)) {
            $fromState = $this->getCurrentState();
            $validTransitions = self::TRANSITIONS[$fromState] ?? [];
            $validStr = count($validTransitions) > 0
                ? implode(', ', $validTransitions)
                : 'none';

            throw new InvalidTransitionException(
                "Cannot transition from '{$fromState}' to '{$toState}'. Valid transitions: [{$validStr}]"
            );
        }
    }

    public function transitionTo(
        string $toState,
        string $trigger,
        ?string $reason = null,
        ?string $actorId = null
    ): ElectionStateTransition {
        $this->validateTransition($toState);

        return DB::transaction(function () use ($toState, $trigger, $reason, $actorId) {
            return ElectionStateTransition::create([
                'election_id' => $this->election->id,
                'from_state' => $this->getCurrentState(),
                'to_state' => $toState,
                'trigger' => $trigger,
                'actor_id' => $actorId,
                'reason' => $reason,
            ]);
        });
    }

    public function allowsAction(string $action): bool
    {
        return $this->election->allowsAction($action);
    }
}
