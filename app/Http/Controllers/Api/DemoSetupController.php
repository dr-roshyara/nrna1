<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Organisation;
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
     * POST /api/organisations/{organisation}/demo-setup
     *
     * @param Request $request
     * @param Organisation $organisation
     * @return \Illuminate\Http\JsonResponse
     */
    public function setup(Request $request, Organisation $organisation)
    {
        // AUTHORIZATION: Check if user is member of this organisation
        $isMember = $organisation->users()
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
            session(['current_organisation_id' => $organisation->id]);

            // Determine if we should force recreate
            $force = $request->input('force', false);

            // Execute the demo:setup command
            // Use --clean flag when force=true to skip confirmation (web context has no STDIN)
            $exitCode = Artisan::call('demo:setup', [
                '--org' => $organisation->id,
                '--clean' => $force ? true : false,
            ]);

            $output = Artisan::output();

            // Log the action
            Log::channel('voting_audit')->info('Demo setup triggered via web', [
                'user_id' => auth()->id(),
                'organisation_id' => $organisation->id,
                'organisation_name' => $organisation->name,
                'exit_code' => $exitCode,
                'force' => $force,
                'ip' => $request->ip(),
            ]);

            if ($exitCode === 0) {
                // Get updated demo stats
                $demoStats = $this->getDemoStats($organisation);

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
                'organisation_id' => $organisation->id,
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
     * @param Organisation $organisation
     * @return array
     */
    private function getDemoStats(Organisation $organisation)
    {
        $election = Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $organisation->id)
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
            DemoPost::where('election_id', $election->id)->pluck('id')
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
