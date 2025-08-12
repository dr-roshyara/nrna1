<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;
use App\Models\ResultAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class VerificationController extends Controller
{
    /**
     * Show verification committee dashboard
     */
    public function dashboard()
    {
        $election = Election::current();
        
        if (!$election) {
            return Inertia::render('Verification/Dashboard', [
                'error' => 'No active election found.'
            ]);
        }

        // Get verification statistics
        $stats = $this->getVerificationStats($election);
        
        // Get recent verification activities
        $recentActivities = $this->getRecentActivities($election);
        
        // Get pending verification items
        $pendingItems = $this->getPendingVerifications($election);

        return Inertia::render('Verification/Dashboard', [
            'election' => [
                'id' => $election->id,
                'name' => $election->name,
                'phase' => $election->getCurrentPhase(),
                'status' => $election->status,
            ],
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'pendingItems' => $pendingItems,
            'user' => [
                'name' => Auth::user()->name,
                'role' => Auth::user()->getRoleNames()->first(),
            ],
        ]);
    }

    /**
     * Approve verification item
     */
    public function approve(Request $request)
    {
        $request->validate([
            'item_type' => 'required|in:vote,result,authorization',
            'item_id' => 'required|integer',
            'verification_notes' => 'nullable|string|max:500',
        ]);

        $result = $this->processApproval(
            $request->item_type,
            $request->item_id,
            $request->verification_notes
        );

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Get verification statistics
     */
    private function getVerificationStats($election)
    {
        return [
            'total_votes' => Vote::where('election_id', $election->id)->count(),
            'verified_votes' => Vote::where('election_id', $election->id)
                                   ->where('verification_status', 'verified')->count(),
            'pending_votes' => Vote::where('election_id', $election->id)
                                  ->where('verification_status', 'pending')->count(),
            'flagged_votes' => Vote::where('election_id', $election->id)
                                  ->where('verification_status', 'flagged')->count(),
            'authorization_progress' => ResultAuthorization::getAuthorizationProgress(
                $election->id,
                $election->authorization_session_id
            ),
        ];
    }

    /**
     * Get recent verification activities
     */
    private function getRecentActivities($election)
    {
        // This would typically come from an audit/activity log table
        // For now, return sample data structure
        return [
            [
                'id' => 1,
                'type' => 'vote_verification',
                'description' => 'Vote #12345 verified successfully',
                'user' => 'Committee Member A',
                'timestamp' => now()->subMinutes(15),
                'status' => 'approved',
            ],
            [
                'id' => 2,
                'type' => 'authorization',
                'description' => 'Publisher authorization completed',
                'user' => 'System',
                'timestamp' => now()->subMinutes(30),
                'status' => 'completed',
            ],
            // Add more activities as needed
        ];
    }

    /**
     * Get pending verification items
     */
    private function getPendingVerifications($election)
    {
        $pendingVotes = Vote::where('election_id', $election->id)
                           ->where('verification_status', 'pending')
                           ->with('user:id,name,email')
                           ->latest()
                           ->take(10)
                           ->get()
                           ->map(function($vote) {
                               return [
                                   'id' => $vote->id,
                                   'type' => 'vote',
                                   'description' => "Vote from {$vote->user->name}",
                                   'submitted_at' => $vote->created_at,
                                   'priority' => 'normal',
                               ];
                           });

        return $pendingVotes;
    }

    /**
     * Process verification approval
     */
    private function processApproval($itemType, $itemId, $notes = null)
    {
        try {
            DB::beginTransaction();

            switch ($itemType) {
                case 'vote':
                    $vote = Vote::findOrFail($itemId);
                    $vote->update([
                        'verification_status' => 'verified',
                        'verified_by' => Auth::id(),
                        'verified_at' => now(),
                        'verification_notes' => $notes,
                    ]);
                    $message = 'Vote verified successfully.';
                    break;

                case 'result':
                    // Handle result verification logic
                    $message = 'Result verified successfully.';
                    break;

                case 'authorization':
                    // Handle authorization verification logic
                    $message = 'Authorization verified successfully.';
                    break;

                default:
                    throw new \InvalidArgumentException('Invalid item type');
            }

            // Log the verification activity
            \Log::info('Verification approved', [
                'item_type' => $itemType,
                'item_id' => $itemId,
                'verified_by' => Auth::id(),
                'notes' => $notes,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => $message,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Verification approval failed', [
                'item_type' => $itemType,
                'item_id' => $itemId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage(),
            ];
        }
    }
}