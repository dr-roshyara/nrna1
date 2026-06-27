<?php

namespace App\Services;

use App\Models\Contribution;
use App\Models\PointsLedger;

class ContributionPointsService
{
    public function __construct(
        private readonly GaneshStandardFormula $formula,
    ) {}

    /**
     * Calculate and award points for an approved contribution.
     * Writes an immutable ledger entry regardless of the resulting points (including zero).
     */
    public function awardPoints(Contribution $contribution): int
    {
        $weeklyPoints = $this->getWeeklyPoints(
            $contribution->user_id,
            $contribution->organisation_id,
        );

        $points = $this->formula->calculate(
            [
                'track'         => $contribution->track,
                'effort_units'  => $contribution->effort_units,
                'proof_type'    => $contribution->proof_type,
                'is_recurring'  => $contribution->is_recurring,
                'team_skills'   => $contribution->team_skills ?? [],
                'outcome_bonus' => $contribution->outcome_bonus,
            ],
            $weeklyPoints,
        );

        // Update the contribution's calculated_points for display
        $contribution->update(['calculated_points' => $points]);

        // Write immutable ledger entry (even zero points for audit trail)
        PointsLedger::create([
            'organisation_id' => $contribution->organisation_id,
            'user_id'         => $contribution->user_id,
            'contribution_id' => $contribution->id,
            'points'          => $points,
            'action'          => 'earned',
            'reason'          => "Track: {$contribution->track}, Proof: {$contribution->proof_type}",
            'created_by'      => $contribution->created_by,
        ]);

        return $points;
    }

    /**
     * Sum all 'earned' ledger points for a user within the current ISO week,
     * scoped to the given organisation (tenant-safe).
     */
    public function getWeeklyPoints(string $userId, string $organisationId): int
    {
        return (int) PointsLedger::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->where('organisation_id', $organisationId)
            ->where('action', 'earned')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('points');
    }
}
