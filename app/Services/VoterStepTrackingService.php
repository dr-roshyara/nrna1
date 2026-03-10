<?php

namespace App\Services;

use App\Models\VoterSlug;
use App\Models\DemoVoterSlug;
use App\Models\VoterSlugStep;
use App\Models\DemoVoterSlugStep;
use App\Models\Election;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * VoterStepTrackingService
 *
 * Manages voter step completion and routing.
 * Supports both real elections (voter_slug_steps) and demo elections (demo_voter_slug_steps)
 */
class VoterStepTrackingService
{
    /**
     * Complete a step for a voter in an election
     *
     * @param VoterSlug|DemoVoterSlug $voterSlug
     * @param Election $election
     * @param int $step
     * @param array $stepData
     * @return VoterSlugStep|DemoVoterSlugStep
     */
    public function completeStep($voterSlug, Election $election, int $step, array $stepData = [])
    {
        // Determine which model to use based on slug type
        $isDemo = $voterSlug instanceof DemoVoterSlug;
        $StepModel = $isDemo ? DemoVoterSlugStep::class : VoterSlugStep::class;

        // ✅ Both demo and real voter slug steps use 'voter_slug_id' column
        $slugForeignKey = 'voter_slug_id';

        Log::info('✅ Completing step', [
            'voter_slug_id' => $voterSlug->id,
            'election_id' => $election->id,
            'step' => $step,
            'is_demo' => $isDemo,
            'foreign_key_column' => $slugForeignKey,
        ]);

        // Check if step already completed
        // ✅ FIXED: Use correct foreign key column name
        $existingStep = $StepModel::where($slugForeignKey, $voterSlug->id)
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
        // ✅ FIXED: Use correct foreign key column name
        $data = [
            $slugForeignKey => $voterSlug->id,
            'election_id' => $election->id,
            'step' => $step,
            'step_data' => $stepData,
            'completed_at' => now(),
        ];

        // Add organisation_id if present
        if ($voterSlug->organisation_id) {
            $data['organisation_id'] = $voterSlug->organisation_id;
        }

        $voterSlugStep = $StepModel::create($data);

        Log::info('🟢 Step completed and recorded', [
            'voter_slug_step_id' => $voterSlugStep->id,
            'step' => $step,
        ]);

        return $voterSlugStep;
    }

    /**
     * Get the highest completed step for a voter in an election
     *
     * @param VoterSlug|DemoVoterSlug $voterSlug
     * @param Election $election
     * @return int (0 if no steps completed)
     */
    public function getHighestCompletedStep($voterSlug, Election $election): int
    {
        // Determine which model to use based on slug type
        $isDemo = $voterSlug instanceof DemoVoterSlug;
        $StepModel = $isDemo ? DemoVoterSlugStep::class : VoterSlugStep::class;

        // ✅ Both demo and real voter slug steps use 'voter_slug_id' column
        $slugForeignKey = 'voter_slug_id';

        $highest = $StepModel::where($slugForeignKey, $voterSlug->id)
            ->where('election_id', $election->id)
            ->max('step');

        return $highest ?? 0;
    }

    /**
     * Get the next step a voter should proceed to
     *
     * @param VoterSlug|DemoVoterSlug $voterSlug
     * @param Election $election
     * @return int (1-5, or null if all steps completed)
     */
    public function getNextStep($voterSlug, Election $election): ?int
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
     * @param VoterSlug|DemoVoterSlug $voterSlug
     * @param Election $election
     * @param int $step
     * @return bool
     */
    public function hasCompletedStep($voterSlug, Election $election, int $step): bool
    {
        // Determine which model to use based on slug type
        $isDemo = $voterSlug instanceof DemoVoterSlug;
        $StepModel = $isDemo ? DemoVoterSlugStep::class : VoterSlugStep::class;

        // ✅ Both demo and real voter slug steps use 'voter_slug_id' column
        $slugForeignKey = 'voter_slug_id';

        return $StepModel::where($slugForeignKey, $voterSlug->id)
            ->where('election_id', $election->id)
            ->where('step', '<=', $step)
            ->exists();
    }

    /**
     * Get all completed steps for a voter in an election
     *
     * @param VoterSlug|DemoVoterSlug $voterSlug
     * @param Election $election
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCompletedSteps($voterSlug, Election $election)
    {
        // Determine which model to use based on slug type
        $isDemo = $voterSlug instanceof DemoVoterSlug;
        $StepModel = $isDemo ? DemoVoterSlugStep::class : VoterSlugStep::class;

        // ✅ Both demo and real voter slug steps use 'voter_slug_id' column
        $slugForeignKey = 'voter_slug_id';

        $query = $StepModel::where($slugForeignKey, $voterSlug->id)
            ->where('election_id', $election->id)
            ->orderBy('step', 'asc');

        // Use ordered() scope if available (VoterSlugStep has it, DemoVoterSlugStep also has it)
        if (method_exists($StepModel, 'ordered')) {
            $query = $StepModel::where($slugForeignKey, $voterSlug->id)
                ->where('election_id', $election->id)
                ->ordered();
        }

        return $query->get();
    }

    /**
     * Get the route name for the next step
     * Based on config/election_steps.php
     *
     * @param VoterSlug|DemoVoterSlug $voterSlug
     * @param Election $election
     * @return string|null
     */
    public function getNextStepRoute($voterSlug, Election $election): ?string
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
    public function getStepTimeline($voterSlug, Election $election): array
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
