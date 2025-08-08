<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Post;
use App\Models\Result;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
class ResultController extends Controller
{
    public function index() {
    // Load posts with basic information
    $posts = Post::get(['id', 'post_id', 'name', 'state_name', 'required_number']);

    // Check if results should be published
    $electionCompleted = true; /* your election completion logic */
    
    if (!$electionCompleted) {
        return redirect()->back()->with('error', 
            'Election results will be available after the election is completed.'
        );
    }

    // Initialize results array
    $results = [
        'total_votes' => Vote::count(),
        'posts' => []
    ];

    // Process votes for each post
    foreach ($posts as $post) {
        $postResults = [
            'post_id' => $post->post_id,
            'post_name' => $post->name,
            'candidates' => [],
            'total_votes_for_post' => 0
        ];

        // Temporary array to aggregate candidate votes
        $candidateVotes = [];

        // Get all votes that contain this post in any candidate field
        $votes = Vote::where(function($query) use ($post) {
            for ($i = 1; $i <= 60; $i++) { // Adjust to 60 fields as per your schema
                $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                $query->orWhereJsonContains($field, ['post_id' => $post->post_id]);
            }
        })->get();

        foreach ($votes as $vote) {
            // Check all candidate fields for this post
            for ($i = 1; $i <= 60; $i++) {
                $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                $candidateData = json_decode($vote->$field, true);

                // Skip if this field doesn't contain data for our current post
                if (!$candidateData || ($candidateData['post_id'] ?? null) !== $post->post_id) {
                    continue;
                }

                // Process each candidate in the candidates array
                foreach ($candidateData['candidates'] ?? [] as $candidate) {
                    $candidateId = $candidate['candidacy_id'] ?? null;
                    $candidateName = $candidate['name'] ?? 'Unknown';

                    if ($candidateId) {
                        if (!isset($candidateVotes[$candidateId])) {
                            $candidateVotes[$candidateId] = [
                                'name' => $candidateName,
                                'count' => 0
                            ];
                        }
                        $candidateVotes[$candidateId]['count']++;
                        $postResults['total_votes_for_post']++;
                    }
                }
            }
        }

        // Format results for this post
        foreach ($candidateVotes as $candidateId => $data) {
            $postResults['candidates'][] = [
                'candidacy_id' => $candidateId,
                'name' => $data['name'],
                'vote_count' => $data['count'],
                'vote_percent' => $results['total_votes'] > 0 
                    ? round(($data['count'] / $results['total_votes']) * 100, 2)
                    : 0
            ];
        }

        $results['posts'][] = $postResults;
    }

    return Inertia::render('Result/Index', [
        'final_result' => $results,
        'posts' => $posts
    ]);
  }

 //
 public function statisticalVerification($postId)
{
    // 1. Check total votes consistency
    $officialTotal = DB::table('results')
        ->where('post_id', $postId)
        ->count(DB::raw('DISTINCT vote_id'));
    
    $voteTableTotal = Vote::count();
    
    // 2. Check vote distribution
    $candidates = DB::table('results')
        ->select('candidacy_id', DB::raw('COUNT(*) as votes'))
        ->where('post_id', $postId)
        ->groupBy('candidacy_id')
        ->get();
    
    $stats = [
        'vote_counts' => $candidates->pluck('votes', 'candidacy_id'),
        'average' => $candidates->avg('votes'),
        'standard_deviation' => $this->calculateStdDev($candidates->pluck('votes')->toArray())
    ];
    
    return [
        'total_votes_match' => $officialTotal == $voteTableTotal,
        'official_total' => $officialTotal,
        'vote_table_total' => $voteTableTotal,
        'statistics' => $stats,
        'anomalies' => $this->detectAnomalies($stats)
    ];
}

private function calculateStdDev($array)
{
    $count = count($array);
    if ($count === 0) return 0;
    
    $average = array_sum($array) / $count;
    $sum = 0;
    
    foreach ($array as $value) {
        $sum += pow($value - $average, 2);
    }
    
    return sqrt($sum / $count);
}

private function detectAnomalies($stats)
{
    $anomalies = [];
    $threshold = $stats['average'] + (2 * $stats['standard_deviation']);
    
    foreach ($stats['vote_counts'] as $candId => $votes) {
        if ($votes > $threshold) {
            $anomalies[$candId] = [
                'votes' => $votes,
                'threshold' => $threshold
            ];
        }
    }
    
    return $anomalies;
}
 
  //Verification: 
  public function verifyResults($postId)
  {
      // 1. Get official results
      $officialResults = DB::table('results')
          ->select('candidacy_id', DB::raw('COUNT(*) as vote_count'))
          ->where('post_id', $postId)
          ->groupBy('candidacy_id')
          ->get()
          ->keyBy('candidacy_id');

      // 2. Get raw votes from votes table
      $rawVotes = [];
      $votes = Vote::whereNotNull('candidate_01')->get(); // Adjust based on your structure
      
      foreach ($votes as $vote) {
          for ($i = 1; $i <= 20; $i++) {
              $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
              $data = json_decode($vote->$field, true);
              
              if ($data && $data['post_id'] === $postId) {
                  foreach ($data['candidates'] as $candidate) {
                      $candidacyId = $candidate['candidacy_id'];
                      $rawVotes[$candidacyId] = ($rawVotes[$candidacyId] ?? 0) + 1;
                  }
              }
          }
      }

      // 3. Compare results
      $discrepancies = [];
      foreach ($officialResults as $candidacyId => $official) {
          if (($rawVotes[$candidacyId] ?? 0) !== $official->vote_count) {
              $discrepancies[$candidacyId] = [
                  'official' => $official->vote_count,
                  'raw' => $rawVotes[$candidacyId] ?? 0
              ];
          }
      }

      return [
          'official_results' => $officialResults,
          'raw_vote_counts' => $rawVotes,
          'discrepancies' => $discrepancies,
          'match' => empty($discrepancies)
      ];
  } 

}
