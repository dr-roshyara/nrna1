<?php

namespace App\Services;

use App\Models\PointsLedger;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LeaderboardService
{
    /**
     * Return the contribution leaderboard for an organisation.
     *
     * Privacy rules:
     *  - public    → display real name
     *  - anonymous → display "Contributor #N" (rank-based)
     *  - private   → excluded entirely
     *
     * Returns a Collection of arrays:
     *   [ user_id, display_name, total_points, rank ]
     */
    public function get(string $organisationId): Collection
    {
        // Sum all earned points per user for this org, excluding private users
        $rows = DB::table('points_ledger as pl')
            ->join('users as u', 'u.id', '=', 'pl.user_id')
            ->where('pl.organisation_id', $organisationId)
            ->where('pl.action', 'earned')
            ->whereIn('u.leaderboard_visibility', ['public', 'anonymous'])
            ->groupBy('pl.user_id', 'u.name', 'u.leaderboard_visibility')
            ->orderByDesc('total_points')
            ->select([
                'pl.user_id',
                'u.name',
                'u.leaderboard_visibility',
                DB::raw('SUM(pl.points) as total_points'),
            ])
            ->get();

        $anonymousCounter = 0;

        return $rows->values()->map(function ($row, $index) use (&$anonymousCounter) {
            if ($row->leaderboard_visibility === 'anonymous') {
                $anonymousCounter++;
                $displayName = "Contributor #{$anonymousCounter}";
            } else {
                $displayName = $row->name;
            }

            return [
                'user_id'      => $row->user_id,
                'display_name' => $displayName,
                'total_points' => (int) $row->total_points,
                'rank'         => $index + 1,
            ];
        });
    }
}
