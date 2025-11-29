<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ElectionService;
use Inertia\Inertia;
use App\Models\Vote;
use App\Models\User;
use App\Models\Post;
use App\Models\Code;
use Illuminate\Support\Facades\DB;

class ElectionManagementController extends Controller
{
    /**
     * Display election management dashboard
     */
    public function index()
    {
        // Check if user has permission to manage election settings
        if (!auth()->user()->can('manage-election-settings')) {
            abort(403, 'You do not have permission to access election management.');
        }

        $electionStatus = ElectionService::getElectionStatus();
        $statistics = $this->getVotingStatistics();

        return Inertia::render('Election/Management', [
            'electionStatus' => $electionStatus,
            'statistics' => $statistics,
            'permissions' => [
                'canPublishResults' => auth()->user()->can('publish-election-results'),
                'canViewResults' => auth()->user()->can('view-election-results'),
                'canManageSettings' => auth()->user()->can('manage-election-settings'),
            ]
        ]);
    }

    /**
     * Publish election results via web interface
     */
    public function publishResults(Request $request)
    {
        // Check permission
        if (!auth()->user()->can('publish-election-results')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if already published
        if (ElectionService::areResultsPublished()) {
            return response()->json(['error' => 'Results are already published'], 400);
        }

        try {
            if (ElectionService::publishResults()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Election results have been successfully published!'
                ]);
            } else {
                return response()->json(['error' => 'Failed to publish results'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to publish results: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display election viewboard (limited management dashboard for viewers)
     */
    public function viewboard()
    {
        // Check if user has permission to view election data
        if (!auth()->user()->can('view-election-results')) {
            abort(403, 'You do not have permission to access election viewboard.');
        }

        $electionStatus = ElectionService::getElectionStatus();
        $statistics = $this->getVotingStatistics();

        // Determine if user can view results based on voting period
        $canViewResult = ElectionService::canViewResultsDuringVotingPeriod(auth()->user());

        return Inertia::render('Election/Viewboard', [
            'electionStatus' => $electionStatus,
            'statistics' => $statistics,
            'can_view_result' => $canViewResult,
            'permissions' => [
                'canPublishResults' => false, // Viewboard users cannot publish
                'canViewResults' => auth()->user()->can('view-election-results'),
                'canManageSettings' => auth()->user()->can('manage-election-settings'), // Allow if user has manage permission
            ]
        ]);
    }

    /**
     * Unpublish election results via web interface
     */
    public function unpublishResults(Request $request)
    {
        // Check permission
        if (!auth()->user()->can('publish-election-results')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if already unpublished
        if (!ElectionService::areResultsPublished()) {
            return response()->json(['error' => 'Results are already unpublished'], 400);
        }

        try {
            if (ElectionService::unpublishResults()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Election results have been successfully unpublished!'
                ]);
            } else {
                return response()->json(['error' => 'Failed to unpublish results'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to unpublish results: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Start voting period via web interface
     */
    public function startVoting(Request $request)
    {
        // Check permission
        if (!auth()->user()->can('manage-election-settings')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if already active
        if (ElectionService::isVotingPeriodActive()) {
            return response()->json(['error' => 'Voting period is already active'], 400);
        }

        try {
            if (ElectionService::startVotingPeriod()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Voting period has been started successfully!'
                ]);
            } else {
                return response()->json(['error' => 'Failed to start voting period'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to start voting period: ' . $e->getMessage()], 500);
        }
    }

    /**
     * End voting period via web interface
     */
    public function endVoting(Request $request)
    {
        // Check permission
        if (!auth()->user()->can('manage-election-settings')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if already inactive
        if (!ElectionService::isVotingPeriodActive()) {
            return response()->json(['error' => 'Voting period is already inactive'], 400);
        }

        try {
            if (ElectionService::endVotingPeriod()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Voting period has been ended successfully!'
                ]);
            } else {
                return response()->json(['error' => 'Failed to end voting period'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to end voting period: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Bulk approve all voters via web interface
     */
    public function bulkApproveVoters(Request $request)
    {
        // Check permission
        if (!auth()->user()->can('manage-election-settings')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get IP check setting from global config (CONTROL_IP_ADDRESS in .env)
        // This ensures consistent IP control across all voter approvals
        $enableIpCheck = config('voting_security.control_ip_address', 1) == 1;
        $excludeVoted = $request->boolean('exclude_voted', false);

        try {
            // Build query for registered voters
            $query = User::where('is_voter', true);

            // Option to exclude already voted users
            if ($excludeVoted) {
                $query->where('has_voted', false);
            }

            // Get voters who are not yet approved
            $votersToApprove = $query->where('can_vote', false)->get();

            if ($votersToApprove->count() === 0) {
                return response()->json(['error' => 'All eligible voters are already approved'], 400);
            }

            $approved = 0;
            $adminName = auth()->user()->name ?? 'System Admin';

            foreach ($votersToApprove as $voter) {
                try {
                    // Prepare update data
                    $updateData = [
                        'can_vote' => true,
                        'approvedBy' => $adminName,
                        'suspendedBy' => null,
                        'suspended_at' => null
                    ];

                    // Conditionally set voting_ip based on global CONTROL_IP_ADDRESS setting
                    if ($enableIpCheck) {
                        $updateData['voting_ip'] = $voter->user_ip; // Enable IP restriction
                    } else {
                        $updateData['voting_ip'] = null; // Disable IP restriction
                    }

                    // Update voter approval
                    $voter->update($updateData);
                    $approved++;
                } catch (\Exception $e) {
                    \Log::error("Failed to approve voter {$voter->name} (ID: {$voter->id}): " . $e->getMessage());
                }
            }

            $message = "Successfully approved {$approved} voters";
            if ($enableIpCheck) {
                $message .= " with IP restriction enabled (CONTROL_IP_ADDRESS=1)";
            } else {
                $message .= " without IP restriction (CONTROL_IP_ADDRESS=0)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'approved_count' => $approved,
                'ip_check_enabled' => $enableIpCheck,
                'control_ip_setting' => config('voting_security.control_ip_address')
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to bulk approve voters: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Bulk disapprove all voters via web interface
     */
    public function bulkDisapproveVoters(Request $request)
    {
        // Check permission
        if (!auth()->user()->can('manage-election-settings')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $includeVoted = $request->boolean('include_voted', false);

        try {
            // Build query for registered voters
            $query = User::where('is_voter', true);

            // By default, exclude users who have already voted (safety measure)
            if (!$includeVoted) {
                $query->where('has_voted', false);
            }

            // Get voters who are currently approved
            $votersToDisapprove = $query->where('can_vote', true)->get();

            if ($votersToDisapprove->count() === 0) {
                return response()->json(['error' => 'No eligible voters to disapprove'], 400);
            }

            $disapproved = 0;
            $adminName = auth()->user()->name ?? 'System Admin';

            foreach ($votersToDisapprove as $voter) {
                try {
                    // Update voter disapproval
                    $voter->update([
                        'can_vote' => false,
                        'suspendedBy' => $adminName,
                        'suspended_at' => now(),
                        'approvedBy' => null,
                        // Note: We keep voting_ip for audit trail
                    ]);
                    $disapproved++;
                } catch (\Exception $e) {
                    \Log::error("Failed to disapprove voter {$voter->name} (ID: {$voter->id}): " . $e->getMessage());
                }
            }

            $message = "Successfully disapproved {$disapproved} voters";
            if (!$includeVoted) {
                $message .= " (voters who have already voted were preserved)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'disapproved_count' => $disapproved,
                'included_voted' => $includeVoted
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to bulk disapprove voters: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get current election status
     */
    public function status()
    {
        return response()->json([
            'electionStatus' => ElectionService::getElectionStatus(),
            'permissions' => [
                'canPublishResults' => auth()->user()->can('publish-election-results'),
                'canViewResults' => auth()->user()->can('view-election-results'),
                'canManageSettings' => auth()->user()->can('manage-election-settings'),
            ]
        ]);
    }

    /**
     * Get comprehensive voting statistics
     */
    private function getVotingStatistics()
    {
        try {
            // Basic counts
            $totalVoters = User::where('is_voter', true)->count();
            $approvedVoters = User::where('is_voter', true)->where('can_vote', true)->count();
            $totalVotes = Vote::count();
            $totalCodes = Code::count();
            $completedVotes = Code::where('has_voted', true)->count();

            // Posts and positions
            $totalPosts = Post::count();

            // Voting sessions
            $activeSessions = Code::where('can_vote_now', true)->count();
            $expiredSessions = Code::where('can_vote_now', false)
                                  ->whereNotNull('voting_started_at')
                                  ->where('has_voted', false)
                                  ->count();

            // Voter participation rate
            $participationRate = $approvedVoters > 0 ? round(($completedVotes / $approvedVoters) * 100, 2) : 0;

            // Vote completion rate (for those who started)
            $codeStarted = Code::whereNotNull('voting_started_at')->count();
            $completionRate = $codeStarted > 0 ? round(($completedVotes / $codeStarted) * 100, 2) : 0;

            // IP Address statistics
            $uniqueIPs = User::whereNotNull('user_ip')->distinct('user_ip')->count();

            // Time-based statistics
            $votesToday = Vote::whereDate('created_at', today())->count();
            $codesCreatedToday = Code::whereDate('created_at', today())->count();

            // Recent activity (last 24 hours)
            $recentVotes = Vote::where('created_at', '>=', now()->subHours(24))->count();
            $recentCodes = Code::where('created_at', '>=', now()->subHours(24))->count();

            return [
                'voters' => [
                    'total_registered' => $totalVoters,
                    'approved_to_vote' => $approvedVoters,
                    'participation_rate' => $participationRate
                ],
                'votes' => [
                    'total_cast' => $totalVotes,
                    'completed_today' => $votesToday,
                    'recent_24h' => $recentVotes,
                    'completion_rate' => $completionRate
                ],
                'sessions' => [
                    'total_codes_generated' => $totalCodes,
                    'active_voting_sessions' => $activeSessions,
                    'expired_sessions' => $expiredSessions,
                    'codes_today' => $codesCreatedToday,
                    'recent_codes_24h' => $recentCodes
                ],
                'system' => [
                    'total_posts' => $totalPosts,
                    'unique_ip_addresses' => $uniqueIPs,
                    'votes_completed' => $completedVotes
                ],
                'summary' => [
                    'voter_turnout' => "{$completedVotes}/{$approvedVoters}",
                    'participation_percentage' => $participationRate,
                    'active_now' => $activeSessions
                ]
            ];
        } catch (\Exception $e) {
            // Return basic stats if there's an error
            return [
                'voters' => ['total_registered' => 0, 'approved_to_vote' => 0, 'participation_rate' => 0],
                'votes' => ['total_cast' => 0, 'completed_today' => 0, 'recent_24h' => 0, 'completion_rate' => 0],
                'sessions' => ['total_codes_generated' => 0, 'active_voting_sessions' => 0, 'expired_sessions' => 0, 'codes_today' => 0, 'recent_codes_24h' => 0],
                'system' => ['total_posts' => 0, 'unique_ip_addresses' => 0, 'votes_completed' => 0],
                'summary' => ['voter_turnout' => '0/0', 'participation_percentage' => 0, 'active_now' => 0],
                'error' => 'Unable to load statistics: ' . $e->getMessage()
            ];
        }
    }
}
