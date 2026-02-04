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

        \Log::info('🔵 VoterProgressService::advanceFrom START', [
            'vslug_id' => $vslug->id,
            'fromRoute' => $fromRoute,
            'fromStep' => $fromStep,
            'map_keys' => array_keys($map),
        ]);

        // If route doesn't exist in config, ignore
        if ($fromStep === false) {
            \Log::warning('🔴 Route not found in election_steps config', [
                'fromRoute' => $fromRoute,
            ]);
            return;
        }

        DB::transaction(function () use ($vslug, $fromStep, $map, $stepMeta) {
            $vslug->refresh(); // Get latest values

            \Log::info('🟡 Inside transaction', [
                'current_step_before' => $vslug->current_step,
                'fromStep' => $fromStep,
            ]);

            // Only advance if we're exactly at fromStep
            if ((int)$vslug->current_step !== (int)$fromStep) {
                \Log::warning('⚠️ Current step does not match fromStep', [
                    'current_step' => (int)$vslug->current_step,
                    'fromStep' => (int)$fromStep,
                ]);
                return; // Idempotent - ignore invalid transitions
            }

            $nextStep = $fromStep + 1;

            // Don't advance beyond final step
            if (!isset($map[$nextStep])) {
                \Log::warning('⚠️ Next step not in map', ['nextStep' => $nextStep]);
                return;
            }

            // Merge new step meta with existing meta
            $updatedMeta = array_merge($vslug->step_meta ?? [], $stepMeta);

            \Log::info('🟢 Updating voter_slug', [
                'current_step' => $fromStep,
                'next_step' => $nextStep,
                'vslug_id' => $vslug->id,
            ]);

            $vslug->update([
                'current_step' => $nextStep,
                'step_meta' => $updatedMeta,
            ]);

            \Log::info('✅ Update complete', ['new_current_step' => $vslug->current_step]);
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