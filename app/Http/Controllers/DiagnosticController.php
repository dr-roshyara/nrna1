<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\DemoElectionResolver;
use Illuminate\Support\Facades\Log;

class DiagnosticController extends Controller
{
    /**
     * Diagnose /election/demo/start issues
     */
    public function diagnoseDemo(DemoElectionResolver $demoResolver)
    {
        $user = Auth::user();
        $status = [];

        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $status['authenticated'] = true;
        $status['user_id'] = $user->id;
        $status['user_organisation_id'] = $user->organisation_id;

        try {
            $demoElection = $demoResolver->getDemoElectionForUser($user);
            $status['demo_election_found'] = $demoElection ? true : false;
            
            if ($demoElection) {
                $status['demo_election'] = [
                    'id' => $demoElection->id,
                    'name' => $demoElection->name,
                    'type' => $demoElection->type,
                    'organisation_id' => $demoElection->organisation_id,
                ];
            }
        } catch (\Exception $e) {
            $status['demo_election_error'] = $e->getMessage();
            Log::error('Diagnostic: Demo election error', ['error' => $e->getMessage()]);
        }

        return response()->json($status, 200);
    }
}
