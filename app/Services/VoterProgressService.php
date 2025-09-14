<?php

namespace App\Services;

use App\Models\VoterSlug;
use Illuminate\Support\Facades\DB;

class VoterProgressService
{
    /**
     * Advance voter to the next step if currently on the specified step
     */
    public function advanceFrom(VoterSlug $vslug, string $fromRoute, array $stepMeta = []): void
    {
        $map = config('election_steps');
        $fromStep = array_search($fromRoute, $map, true);

        // If route doesn't exist in config, ignore
        if ($fromStep === false) {
            return;
        }

        DB::transaction(function () use ($vslug, $fromStep, $map, $stepMeta) {
            $vslug->refresh(); // Get latest values

            // Only advance if we're exactly at fromStep
            if ((int)$vslug->current_step !== (int)$fromStep) {
                return; // Idempotent - ignore invalid transitions
            }

            $nextStep = $fromStep + 1;

            // Don't advance beyond final step
            if (!isset($map[$nextStep])) {
                return;
            }

            // Merge new step meta with existing meta
            $updatedMeta = array_merge($vslug->step_meta ?? [], $stepMeta);

            $vslug->update([
                'current_step' => $nextStep,
                'step_meta' => $updatedMeta,
            ]);
        });
    }

    /**
     * Reset voter to a specific step and clear meta
     */
    public function resetToStep(VoterSlug $vslug, int $step, array $stepMeta = []): void
    {
        $map = config('election_steps');

        // Ensure step is valid
        if (!isset($map[$step])) {
            return;
        }

        $vslug->update([
            'current_step' => $step,
            'step_meta' => $stepMeta,
        ]);
    }

    /**
     * Get the route name for the next step
     */
    public function getNextRouteName(VoterSlug $vslug): ?string
    {
        $map = config('election_steps');
        $nextStep = $vslug->current_step + 1;

        return $map[$nextStep] ?? null;
    }

    /**
     * Get the route name for the current step
     */
    public function getCurrentRouteName(VoterSlug $vslug): string
    {
        $map = config('election_steps');
        return $map[$vslug->current_step] ?? reset($map);
    }

    /**
     * Check if voter can advance from current step
     */
    public function canAdvanceFrom(VoterSlug $vslug, string $fromRoute): bool
    {
        $map = config('election_steps');
        $fromStep = array_search($fromRoute, $map, true);

        if ($fromStep === false) {
            return false;
        }

        // Can only advance if currently on this step
        return (int)$vslug->current_step === (int)$fromStep;
    }

    /**
     * Get all available steps
     */
    public function getAllSteps(): array
    {
        return config('election_steps');
    }

    /**
     * Check if at final step
     */
    public function isAtFinalStep(VoterSlug $vslug): bool
    {
        $map = config('election_steps');
        $maxStep = max(array_keys($map));

        return $vslug->current_step >= $maxStep;
    }
}