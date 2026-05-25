<?php

namespace App\Http\Middleware;

use App\Models\Election;
use App\Application\Election\Facades\ElectionLifecycle;
use Closure;
use Illuminate\Http\Request;

/**
 * EnsureElectionState Middleware — Phase C.2.5 Refactored
 *
 * REFACTORING CHANGE (Step 2, P3):
 * OLD: Called $election->getStateMachine()->allowsAction() [deprecated bridge]
 * NEW: Asks ElectionLifecycle facade for snapshot, uses OperationCapabilityMapper
 *
 * This middleware is a constitutional enforcement point — it verifies operations
 * are permitted based on the election's current lifecycle state. It does NOT
 * perform authorization (auth is already done by Authorizable policies).
 *
 * CRITICAL INVARIANT:
 * This middleware asks the RESOLVER (via ElectionLifecycle facade) for the
 * current capability snapshot, then checks the operation against that snapshot.
 * It NEVER derives permissions itself.
 */
class EnsureElectionState
{
    public function handle(Request $request, Closure $next, string $operation): mixed
    {
        $election = $request->route('election');

        if (!$election) {
            abort(404, 'Election not found');
        }

        // If election is a string (slug), resolve it to a model
        if (is_string($election)) {
            $election = Election::where('slug', $election)->first();

            if (!$election) {
                abort(404, 'Election not found');
            }
        }

        // Store election in request for downstream middleware
        $request->attributes->set('election', $election);

        // Demo elections: return 404 for candidacy operations
        if ($election->type === 'demo' && in_array($operation, ['apply_candidacy', 'approve_candidacy'], true)) {
            abort(404, 'Candidacy operations are not available for demo elections.');
        }

        // STEP 1: Ask resolver for current lifecycle snapshot (SSOT)
        $lifecycle = ElectionLifecycle::of($election);
        $snapshot = $lifecycle->snapshot();

        // STEP 2: Use mapper to check if operation is allowed by snapshot
        // (mapper is TEMPORARY — it bridges old operation pattern to new capability model)
        $isOperationAllowed = OperationCapabilityMapper::isOperationAllowed($operation, $snapshot);

        if (!$isOperationAllowed) {
            // Get denial reason from resolver (not from operation mapper)
            $denialReason = $lifecycle->blockedReason() ??
                           OperationCapabilityMapper::denialReasonForOperation($operation, $snapshot) ??
                           'Operation not permitted in current election state';

            // Get state display info for error message
            $stateInfo = OperationCapabilityMapper::getStateInfo($snapshot);

            abort(403, sprintf(
                '%s Operation "%s" is not allowed during the "%s" phase.',
                $denialReason ? $denialReason . '.' : '',
                $operation,
                $stateInfo['name']
            ));
        }

        return $next($request);
    }
}
