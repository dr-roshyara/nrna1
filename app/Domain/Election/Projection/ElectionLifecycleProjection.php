<?php

declare(strict_types=1);

namespace App\Domain\Election\Projection;

use App\Domain\Election\Enum\ElectionLifecycleState;

/**
 * Projects lifecycle progression semantics from constitutional state position.
 *
 * REJECTED: terminal branch state — excluded from linear progression (no states after it).
 * SUSPENDED: overlay state — pre-suspension position restoration deferred to C.2.9.
 *
 * @see ADR-005: Projection Sovereignty
 */
final class ElectionLifecycleProjection
{
    /**
     * The canonical constitutional linear progression order.
     * States before currentState in this array are "completed."
     */
    public const PROGRESSION = [
        ElectionLifecycleState::Draft,
        ElectionLifecycleState::SubmittedForApproval,
        ElectionLifecycleState::Approved,
        ElectionLifecycleState::SetupAdministration,
        ElectionLifecycleState::SetupNomination,
        ElectionLifecycleState::ReadyForVoting,
        ElectionLifecycleState::VotingActive,
        ElectionLifecycleState::Counting,
        ElectionLifecycleState::ResultsPublished,
        ElectionLifecycleState::Archived,
    ];

    /**
     * Returns lifecycle state strings that precede $currentState in constitutional progression.
     * Returns [] if $currentState is not in the linear progression (REJECTED, SUSPENDED, unknown).
     *
     * @return string[]
     */
    public static function completedStatesFor(string $currentState): array
    {
        $values = array_map(fn (ElectionLifecycleState $s) => $s->value, self::PROGRESSION);
        $index = array_search($currentState, $values, strict: true);

        return $index !== false
            ? array_slice($values, 0, (int) $index)
            : [];
    }

    /**
     * Returns true when $currentState participates in linear constitutional progression.
     * Returns false for overlay states (SUSPENDED, REJECTED) and unknown states.
     */
    public static function isProjectionAvailable(string $currentState): bool
    {
        $values = array_map(fn (ElectionLifecycleState $s) => $s->value, self::PROGRESSION);

        return in_array($currentState, $values, strict: true);
    }
}
