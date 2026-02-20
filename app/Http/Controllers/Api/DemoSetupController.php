<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Organization;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoCode;
use App\Models\DemoVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DemoSetupController extends Controller
{
    /**
     * Trigger demo setup for an organisation
     *
     * POST /api/organizations/{organization}/demo-setup
     *
     * @param Request $request
     * @param Organization $organization
     * @return \Illuminate\Http\JsonResponse
     */
    public function setup(Request $request, Organization $organization)
    {
        // AUTHORIZATION: Check if user is member of this organisation
        $isMember = $organization->users()
            ->where('users.id', auth()->id())
            ->exists();

        if (!$isMember) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this organisation.'
            ], 403);
        }

        try {
            // Set session context for the command
            session(['current_organisation_id' => $organization->id]);

            // Determine if we should force recreate
            $force = $request->input('force', false);

            // Execute the demo:setup command
            $exitCode = Artisan::call('demo:setup', [
                '--org' => $organization->id,
                '--force' => $force ? true : false,
            ]);

            $output = Artisan::output();

            // Log the action
            Log::channel('voting_audit')->info('Demo setup triggered via web', [
                'user_id' => auth()->id(),
                'organization_id' => $organization->id,
                'organization_name' => $organization->name,
                'exit_code' => $exitCode,
                'force' => $force,
                'ip' => $request->ip(),
            ]);

            if ($exitCode === 0) {
                // Get updated demo stats
                $demoStats = $this->getDemoStats($organization);

                return response()->json([
                    'success' => true,
                    'message' => $force
                        ? 'Demo election recreated successfully!'
                        : 'Demo election setup completed successfully!',
                    'demoStatus' => [
                        'exists' => true,
                        'stats' => $demoStats
                    ]
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Demo setup failed. Please check logs.',
                    'output' => $output
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Demo setup failed', [
                'error' => $e->getMessage(),
                'organization_id' => $organization->id,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get demo election statistics for an organisation
     *
     * @param Organization $organization
     * @return array
     */
    private function getDemoStats(Organization $organization)
    {
        $election = Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $organization->id)
            ->first();

        if (!$election) {
            return [
                'posts' => 0,
                'candidates' => 0,
                'codes' => 0,
                'votes' => 0,
            ];
        }

        $posts = DemoPost::where('election_id', $election->id)->count();
        $candidates = DemoCandidacy::whereIn('post_id',
            DemoPost::where('election_id', $election->id)->pluck('post_id')
        )->count();
        $codes = DemoCode::where('election_id', $election->id)->count();
        $votes = DemoVote::where('election_id', $election->id)->count();

        return [
            'posts' => $posts,
            'candidates' => $candidates,
            'codes' => $codes,
            'votes' => $votes,
            'election_id' => $election->id,
            'election_name' => $election->name,
        ];
    }
}
