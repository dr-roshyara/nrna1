<?php
namespace App\Http\Controllers\Election;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Post;
use App\Models\Result;
use App\Models\Election;
use App\Models\Setting;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ElectionResultController extends Controller
{
    /**
     * Display election results
     */
    public function index()
    {
        // 🎯 ONE LINE: Ask the model
        if (!$this->canShowResults()) {
            return $this->showResultsBlockedMessage();
        }

        // Show results
        return $this->renderResults();
    }

 

    private function showResultsBlockedMessage()
        {
            $election = Election::current();
            $phase = $election ? $election->getCurrentPhase() : 'unknown';
            
            $messages = [
                'sealed' => 'परिणामहरू सील गरिएको छ। | Results are sealed.',
                'voting' => 'मतदान चलिरहेको छ। | Voting is in progress.',
                'unsealing' => 'प्रकाशकहरूले परिणाम खोल्ने प्रतीक्षामा छ। | Waiting for publishers to unseal results.',
                'unknown' => 'परिणामहरू उपलब्ध छैनन्। | Results not available.'
            ];

            return redirect()->back()->with('error', $messages[$phase] ?? $messages['unknown']);
        }

        private function renderResults()
        {
            $electionData = Cache::remember('election_results_data', 600, function () {
                return $this->getElectionResultsData();
            });

            return Inertia::render('Result/ResultPublish', $electionData);
        }

    /**
     * 🎯 MAIN LOGIC: Check if results are published (with seal-unlock)
     */
    private function areResultsPublished(): bool
{
    // Manual override (highest priority)
    if (Setting::isEnabled('results_published')) {
        return true;
    }

    $election = Election::current();
    if (!$election) {
        return false;
    }

    // 🎯 KEY: Use your phase system directly
    $phase = $election->getCurrentPhase();
    
    // Block during voting phase (your seal requirement)
    if ($phase === 'voting') {
        return false;
    }
    
    // Must be in 'published' phase
    return $phase === 'published';
}
    /**
     * Get comprehensive election results data
     */
    private function getElectionResultsData(): array
    {
        // Get all posts (positions) with required information
        $posts = Post::select(['id', 'post_id', 'name', 'state_name', 'required_number'])
            ->with(['candidates.user:id,name,email'])
            ->get();

        // Get vote statistics
        $voteStats = $this->getVoteStatistics();

        // Calculate results for each post
        $results = [];
        $candidateResults = [];

        foreach ($posts as $post) {
            $postResults = $this->calculatePostResults($post);
            $results[$post->post_id] = $postResults;
            
            // Merge candidate results
            $candidateResults = array_merge($candidateResults, $postResults['candidates']);
        }

        return [
            'election_info' => $this->getElectionInfo(),
            'vote_statistics' => $voteStats,
            'posts' => $posts,
            'results' => $results,
            'candidates' => $candidateResults,
            'total_votes_cast' => $voteStats['total_votes'],
            'results_published_at' => now()->toDateTimeString(),
        ];
    }
    /**
     * 🎯 SIMPLE: Just ask the model
     */
    private function canShowResults(): bool
    {
        $election = Election::current();
        return $election ? $election->canViewResults() : false;
    }


    /**
     * Calculate results for a specific post
     */
    private function calculatePostResults(Post $post): array
    {
        // Get vote counts for this post
        $voteQuery = DB::table('results')
            ->select('candidacy_id', DB::raw('COUNT(*) as vote_count'))
            ->where('post_id', $post->post_id)
            ->groupBy('candidacy_id')
            ->orderBy('vote_count', 'DESC');

        $voteCounts = $voteQuery->get()->keyBy('candidacy_id');

        // Process candidates with their vote counts
        $candidates = [];
        $totalVotes = 0;

        foreach ($post->candidates as $candidate) {
            $voteCount = $voteCounts->get($candidate->candidacy_id)?->vote_count ?? 0;
            $totalVotes += $voteCount;

            $candidates[] = [
                'candidacy_id' => $candidate->candidacy_id,
                'user' => $candidate->user,
                'vote_count' => $voteCount,
                'percentage' => 0, // Will calculate after getting total
                'position' => 0,   // Will calculate after sorting
                'is_winner' => false, // Will determine after sorting
            ];
        }

        // Sort candidates by vote count
        usort($candidates, function ($a, $b) {
            return $b['vote_count'] <=> $a['vote_count'];
        });

        // Calculate percentages, positions, and winners
        foreach ($candidates as $index => &$candidate) {
            $candidate['percentage'] = $totalVotes > 0 
                ? round(($candidate['vote_count'] / $totalVotes) * 100, 2) 
                : 0;
            
            $candidate['position'] = $index + 1;
            
            // Determine winners based on required number
            $candidate['is_winner'] = ($index + 1) <= $post->required_number;
        }

        return [
            'post_id' => $post->post_id,
            'post_name' => $post->name,
            'state_name' => $post->state_name,
            'required_number' => $post->required_number,
            'total_votes_for_post' => $totalVotes,
            'candidates' => $candidates,
            'winners' => array_filter($candidates, fn($c) => $c['is_winner']),
        ];
    }

    /**
     * Get overall vote statistics
     */
    private function getVoteStatistics(): array
    {
        // Get total unique votes cast
        $totalVotes = DB::table('results')
            ->distinct('vote_id')
            ->count();

        // Get total eligible voters
        $totalEligibleVoters = DB::table('users')
            ->where('is_voter', true)
            ->where('can_vote', true)
            ->count();

        // Get votes by post
        $votesByPost = DB::table('results')
            ->select('post_id', DB::raw('COUNT(*) as vote_count'))
            ->groupBy('post_id')
            ->get()
            ->keyBy('post_id');

        // Calculate turnout
        $turnoutPercentage = $totalEligibleVoters > 0 
            ? round(($totalVotes / $totalEligibleVoters) * 100, 2) 
            : 0;

        return [
            'total_votes' => $totalVotes,
            'total_eligible_voters' => $totalEligibleVoters,
            'turnout_percentage' => $turnoutPercentage,
            'votes_by_post' => $votesByPost,
        ];
    }

    /**
     * Get election information
     */
    private function getElectionInfo(): array
    {
        $election = Election::current();
        
        if (!$election) {
            return [
                'name' => 'NRNA Election',
                'voting_start' => null,
                'voting_end' => null,
                'status' => 'No Election Data',
            ];
        }

        return [
            'id' => $election->id,
            'name' => $election->name ?? 'NRNA Election',
            'voting_start' => $election->voting_start_time,
            'voting_end' => $election->voting_end_time,
            'status' => 'Completed',
            'results_verified_at' => $election->results_verified_at,
        ];
    }

    /**
     * 🔧 ADMIN: Manually publish results (Committee only)
     */
    public function publishResults(Request $request)
    {
        // Check permissions
        if (!$request->user()->hasRole('election-committee')) {
            abort(403, 'Unauthorized');
        }

        try {
            // Validate that voting has ended
            $election = Election::current();
            if (!$election || $election->voting_end_time > now()) {
                return response()->json([
                    'error' => 'Cannot publish results while voting is active'
                ], 400);
            }

            // Set publication flag
            Setting::updateOrCreate(
                ['key' => 'results_published'],
                ['value' => 'true']
            );

            // Clear cache
            Cache::forget('election_results_data');

            // Log the action
            Log::info('Election results published manually', [
                'user_id' => $request->user()->id,
                'election_id' => $election->id,
                'timestamp' => now(),
            ]);

            return response()->json([
                'message' => 'Results published successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error publishing results: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to publish results'
            ], 500);
        }
    }

    /**
     * 🔧 ADMIN: Verify results before publication
     */
    public function verifyResults(Request $request)
    {
        if (!$request->user()->hasRole('election-committee')) {
            abort(403, 'Unauthorized');
        }

        try {
            $election = Election::current();
            
            if (!$election) {
                return response()->json(['error' => 'No active election'], 400);
            }

            // Mark results as verified
            $election->update([
                'results_verified_at' => now(),
                'verified_by' => $request->user()->id,
            ]);

            // Clear cache
            Cache::forget('election_results_data');

            return response()->json([
                'message' => 'Results verified successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error verifying results: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to verify results'
            ], 500);
        }
    }

    /**
     * Export results (PDF, Excel, etc.)
     */
    public function export(Request $request, $format = 'pdf')
    {
        if (!$this->areResultsPublished()) {
            abort(403, 'Results not yet published');
        }

        // Implementation for PDF/Excel export
        // This would use packages like Laravel-PDF or Laravel-Excel
        
        return response()->json([
            'message' => 'Export functionality to be implemented'
        ]);
    }
}