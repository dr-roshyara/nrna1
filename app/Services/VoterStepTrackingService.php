<?php

namespace App\Services;

use App\Models\VoterSlug;
use App\Models\VoterSlugStep;
use App\Models\Election;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * VoterStepTrackingService
 *
 * Manages voter step completion and routing.
 * Single source of truth: voter_slug_steps table
 */
class VoterStepTrackingService
{
    /**
     * Complete a step for a voter in an election
     *
     * @param VoterSlug $voterSlug
     * @param Election $election
     * @param int $step
     * @param array $stepData
     * @return VoterSlugStep
     */
    public function completeStep(VoterSlug $voterSlug, Election $election, int $step, array $stepData = []): VoterSlugStep
    {
        Log::info('✅ Completing step', [
            'voter_slug_id' => $voterSlug->id,
            'election_id' => $election->id,
            'step' => $step,
        ]);

        // Check if step already completed
        $existingStep = VoterSlugStep::where('voter_slug_id', $voterSlug->id)
            ->where('election_id', $election->id)
            ->where('step', $step)
            ->first();

        if ($existingStep) {
            Log::info('⚠️ Step already completed', [
                'voter_slug_id' => $voterSlug->id,
                'step' => $step,
                'completed_at' => $existingStep->completed_at,
            ]);
            return $existingStep;
        }

        // Create new step completion record
        $voterSlugStep = VoterSlugStep::create([
            'voter_slug_id' => $voterSlug->id,
            'election_id' => $election->id,
            'step' => $step,
            'step_data' => $stepData,
            'completed_at' => now(),
        ]);

        Log::info('🟢 Step completed and recorded', [
            'voter_slug_step_id' => $voterSlugStep->id,
            'step' => $step,
        ]);

        return $voterSlugStep;
    }

    /**
     * Get the highest completed step for a voter in an election
     *
     * @param VoterSlug $voterSlug
     * @param Election $election
     * @return int (0 if no steps completed)
     */
    public function getHighestCompletedStep(VoterSlug $voterSlug, Election $election): int
    {
        $highest = VoterSlugStep::where('voter_slug_id', $voterSlug->id)
            ->where('election_id', $election->id)
            ->max('step');

        return $highest ?? 0;
    }

    /**
     * Get the next step a voter should proceed to
     *
     * @param VoterSlug $voterSlug
     * @param Election $election
     * @return int (1-5, or null if all steps completed)
     */
    public function getNextStep(VoterSlug $voterSlug, Election $election): ?int
    {
        $highestCompleted = $this->getHighestCompletedStep($voterSlug, $election);
        $nextStep = $highestCompleted + 1;

        // Check if next step is valid (1-5)
        $maxStep = 5; // Matches config/election_steps.php
        if ($nextStep <= $maxStep) {
            return $nextStep;
        }

        return null; // All steps completed
    }

    /**
     * Check if a specific step has been completed
     *
     * @param VoterSlug $voterSlug
     * @param Election $election
     * @param int $step
     * @return bool
     */
    public function hasCompletedStep(VoterSlug $voterSlug, Election $election, int $step): bool
    {
        return VoterSlugStep::where('voter_slug_id', $voterSlug->id)
            ->where('election_id', $election->id)
            ->where('step', '<=', $step)
            ->exists();
    }

    /**
     * Get all completed steps for a voter in an election
     *
     * @param VoterSlug $voterSlug
     * @param Election $election
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCompletedSteps(VoterSlug $voterSlug, Election $election)
    {
        return VoterSlugStep::where('voter_slug_id', $voterSlug->id)
            ->where('election_id', $election->id)
            ->ordered()
            ->get();
    }

    /**
     * Get the route name for the next step
     * Based on config/election_steps.php
     *
     * @param VoterSlug $voterSlug
     * @param Election $election
     * @return string|null
     */
    public function getNextStepRoute(VoterSlug $voterSlug, Election $election): ?string
    {
        $nextStep = $this->getNextStep($voterSlug, $election);
        if (!$nextStep) {
            return null;
        }

        $stepMap = config('election_steps');
        return $stepMap[$nextStep] ?? null;
    }

    /**
     * Audit: Get step completion timeline for a voter
     *
     * @param VoterSlug $voterSlug
     * @param Election $election
     * @return array
     */
    public function getStepTimeline(VoterSlug $voterSlug, Election $election): array
    {
        $steps = $this->getCompletedSteps($voterSlug, $election);
        $stepNames = config('election_steps');

        return $steps->map(function ($step) use ($stepNames) {
            return [
                'step' => $step->step,
                'step_name' => $stepNames[$step->step] ?? "Unknown",
                'completed_at' => $step->completed_at->toIso8601String(),
                'data' => $step->step_data,
            ];
        })->toArray();
    }
}
