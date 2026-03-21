<?php

namespace App\Console\Commands;

use App\Models\ElectionMembership;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

/**
 * FlushExpiringVoterCaches
 *
 * Clears voter count and stats caches for elections that have memberships
 * which have already expired or will expire within the next hour.
 *
 * This addresses the gap in event-based cache invalidation: Eloquent model
 * events fire when rows are saved or deleted, but NOT when a timestamp column
 * naturally passes into the past. A membership whose expires_at passes at
 * 14:00 will never trigger a `saved` event — so without this command the
 * cached counts would remain stale until the TTL expires naturally (up to 5
 * minutes) or until a manual cache clear.
 *
 * Schedule: hourly (see routes/console.php).
 *
 * @see architecture/election/voter/20260317_2225_implement_cashing_strategy.md
 */
class FlushExpiringVoterCaches extends Command
{
    protected $signature   = 'elections:flush-expiring-caches';
    protected $description = 'Clear voter caches for elections with memberships expiring within the next hour';

    public function handle(): int
    {
        // Find all distinct election IDs that have memberships which have
        // already expired OR will expire within the next hour.
        $electionIds = ElectionMembership::whereNotNull('expires_at')
            ->where('expires_at', '<=', now()->addHour())
            ->distinct()
            ->pluck('election_id');

        if ($electionIds->isEmpty()) {
            $this->line('No expiring voter memberships found — nothing to flush.');
            return self::SUCCESS;
        }

        foreach ($electionIds as $electionId) {
            Cache::forget("election.{$electionId}.voter_count");
            Cache::forget("election.{$electionId}.voter_stats");
        }

        $this->info("Flushed voter caches for {$electionIds->count()} election(s).");

        return self::SUCCESS;
    }
}
