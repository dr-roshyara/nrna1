<?php

namespace App\Http\Middleware;

use App\Models\Election;
use Closure;
use Illuminate\Http\Request;

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

        $allowsAction = $election->getStateMachine()->allowsAction($operation);

        if (!$allowsAction) {
            $stateInfo = $election->state_info;

            abort(403, sprintf(
                'Operation "%s" is not allowed during the "%s" phase.',
                $operation,
                $stateInfo['name']
            ));
        }

        return $next($request);
    }
}
