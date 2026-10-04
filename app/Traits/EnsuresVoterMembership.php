<?php

namespace App\Traits;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Infrastructure\Queries\EloquentVotingEntitlementQuery;
use App\Models\Election;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait EnsuresVoterMembership
{
    /**
     * Layer 0: Membership check for real elections (defense-in-depth).
     *
     * Returns a RedirectResponse if the user is ineligible, null if they pass.
     *
     * @param  bool  $useCache      true  = 5-min cache (acceptable for early steps)
     *                              false = fresh DB query (required for verify/store)
     * @param  bool  $inTransaction true  = rollback open transaction on block
     */
    protected function ensureVoterMembership(
        Election $election,
        User $user,
        bool $useCache = true,
        bool $inTransaction = false
    ): ?RedirectResponse {
        // Demo elections use the legacy code-based system — bypass entirely.
        if ($election->type === 'demo') {
            return null;
        }

        // One authoritative decision (PBDIGIT-213 C1). The uncached path asks the
        // Eloquent query directly, so scope is derived from the election — never
        // from the ambient tenant — and no cached entry can authorise a ballot.
        $query = $useCache
            ? app(VotingEntitlementQuery::class)
            : app(EloquentVotingEntitlementQuery::class);

        $isEligible = $query->standingOf($user->id, $election->id)->isEntitled();

        if (! $isEligible) {
            Log::channel('voting_security')->warning('⛔ Layer 0 (controller): Voter membership check failed', [
                'user_id'     => $user->id,
                'election_id' => $election->id,
                'use_cache'   => $useCache,
                'url'         => request()->fullUrl(),
                'ip'          => request()->ip(),
            ]);

            if ($inTransaction && DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return redirect()->route('dashboard')
                ->with('error', 'You are not assigned to vote in this election.');
        }

        return null;
    }
}
