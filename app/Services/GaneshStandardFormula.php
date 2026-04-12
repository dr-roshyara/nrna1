<?php

namespace App\Services;

class GaneshStandardFormula
{
    private const TRACK_CONFIG = [
        'micro'    => ['base_rate' => 10, 'tier_bonus' => 0,   'min_base' => 0,   'weekly_cap' => 100],
        'standard' => ['base_rate' => 10, 'tier_bonus' => 50,  'min_base' => 31,  'weekly_cap' => null],
        'major'    => ['base_rate' => 10, 'tier_bonus' => 200, 'min_base' => 201, 'weekly_cap' => null],
    ];

    /**
     * VERIFICATION WEIGHTS
     *
     * NOTE: These weights are ordinal (ranking is meaningful) but cardinal values
     * are heuristic. Future validation should calibrate based on:
     * - User perception surveys
     * - Correlation with contribution quality
     * - Fairness across user groups
     *
     * Current weights:
     * - self_report (0.5):           Unverified, lowest trust
     * - photo (0.7):                 Visual evidence, moderate trust
     * - document (0.8):              Written record, high trust
     * - third_party (1.0):           Independent confirmation, very high trust
     * - community_attestation (1.1): 3+ community members vouch, grassroots alternative
     * - institutional (1.2):         Official organisation letter, maximum trust
     */
    private const VERIFICATION_WEIGHTS = [
        'self_report'           => 0.5,
        'photo'                 => 0.7,
        'document'              => 0.8,
        'third_party'           => 1.0,
        'community_attestation' => 1.1,
        'institutional'         => 1.2,
    ];

    /**
     * MAX_COMBINED_MULTIPLIER
     *
     * Prevents multiplicative explosion where synergy × verification × sustainability
     * could reach 2.16× (1.5 × 1.2 × 1.2). Without a cap, contributions with all
     * maximum factors are unfairly amplified over those with slightly lower factors.
     *
     * Value 2.0 is heuristic. Should be validated by measuring:
     * - Distribution of combined multipliers in production
     * - User perception of fairness at different cap levels
     * - Correlation with actual contribution quality
     */
    private const MAX_COMBINED_MULTIPLIER = 2.0;

    /**
     * DIMINISHING_RETURNS_THRESHOLD
     *
     * After 20 hours, additional effort adds less value.
     * Based on cognitive fatigue and productivity research.
     *
     * Formula: effectiveHours = threshold + log(extraHours + 1) × 5
     *
     * Examples:
     * - 20 h → 20.0 effective
     * - 30 h → 25.5 effective
     * - 40 h → 29.7 effective
     * - 50 h → 33.0 effective
     */
    private const DIMINISHING_RETURNS_THRESHOLD = 20;

    public function calculate(array $input, int $weeklyPoints): int
    {
        $track        = $input['track'] ?? 'micro';
        $config       = self::TRACK_CONFIG[$track];
        $effortUnits  = $input['effort_units'] ?? 0;
        $proofType    = $input['proof_type'] ?? 'self_report';
        $isRecurring  = $input['is_recurring'] ?? false;
        $skills       = $input['team_skills'] ?? [];
        $outcomeBonus = $input['outcome_bonus'] ?? 0;

        $effectiveEffort = $this->calculateEffectiveEffort($effortUnits);

        $base     = $effectiveEffort * $config['base_rate'];
        $tier     = $base >= $config['min_base'] ? $config['tier_bonus'] : 0;
        $subtotal = $base + $tier;

        $synergy        = $this->calculateSkillDiversityBonus($skills);
        $verification   = self::VERIFICATION_WEIGHTS[$proofType] ?? 1.0;
        $sustainability = $isRecurring ? 1.2 : 1.0;

        $rawMultiplier    = $synergy * $verification * $sustainability;
        $cappedMultiplier = min($rawMultiplier, self::MAX_COMBINED_MULTIPLIER);

        $points = (int) floor($subtotal * $cappedMultiplier) + $outcomeBonus;

        if ($config['weekly_cap'] !== null) {
            $remaining = max(0, $config['weekly_cap'] - $weeklyPoints);
            $points    = min($points, $remaining);
        }

        return $points;
    }

    /**
     * Calculate skill diversity bonus (formerly calculateSynergy).
     *
     * This is a heuristic, NOT a true Shapley value calculation.
     * It rewards teams with diverse skills as a proxy for marginal contribution.
     *
     * Rules:
     * - 1 unique skill  → 1.0× (individual contribution)
     * - 2 unique skills → 1.2× (pairwise synergy)
     * - 3+ unique skills → 1.5× (team synergy / cross-pollination)
     *
     * TODO: Validate thresholds empirically with user surveys and project outcomes.
     */
    public function calculateSkillDiversityBonus(array $skills): float
    {
        $uniqueCount = count(array_unique($skills));

        if ($uniqueCount >= 3) return 1.5;
        if ($uniqueCount >= 2) return 1.2;
        return 1.0;
    }

    /**
     * Apply diminishing returns to effort hours.
     *
     * Hours up to DIMINISHING_RETURNS_THRESHOLD are counted linearly.
     * Hours beyond that follow: effectiveExtra = log(extraHours + 1) × 5
     *
     * Rationale: marginal productivity decreases with extended effort,
     * and extreme hour counts should not dominate point totals.
     */
    private function calculateEffectiveEffort(int $hours): float
    {
        if ($hours <= self::DIMINISHING_RETURNS_THRESHOLD) {
            return (float) $hours;
        }

        $extraHours    = $hours - self::DIMINISHING_RETURNS_THRESHOLD;
        $effectiveExtra = log($extraHours + 1) * 5;

        return self::DIMINISHING_RETURNS_THRESHOLD + $effectiveExtra;
    }
}
