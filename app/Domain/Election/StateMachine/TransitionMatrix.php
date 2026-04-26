<?php

namespace App\Domain\Election\StateMachine;

class TransitionMatrix
{
    public const TRANSITIONS = [
        'draft' => ['administration'],
        'administration' => ['nomination'],
        'nomination' => ['voting'],
        'voting' => ['results_pending'],
        'results_pending' => ['results'],
        'results' => [],
    ];

    public static function canTransition(string $fromState, string $toState): bool
    {
        return in_array($toState, self::TRANSITIONS[$fromState] ?? []);
    }

    public static function getAllowedTransitions(string $state): array
    {
        return self::TRANSITIONS[$state] ?? [];
    }

    public static function isValidState(string $state): bool
    {
        return array_key_exists($state, self::TRANSITIONS);
    }

    public static function getAllStates(): array
    {
        return array_keys(self::TRANSITIONS);
    }
}
