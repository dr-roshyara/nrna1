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
use App\Models\Election;
use App\Services\ElectionService;
class ResultController extends Controller
{
    public function index(string $election) {
        $election = Election::withoutGlobalScopes()
            ->where('slug', $election)
            ->firstOrFail();

        if (! $election->results_published) {
            abort(403, 'Election results have not been published yet.');
        }

        $totalVotes = Vote::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->count();

        // Load vote counts from results table (pre-aggregated at vote submission time)
        $voteCounts = DB::table('results')
            ->where('results.election_id', $election->id)
            ->whereNotNull('results.candidacy_id')
            ->select('results.post_id', 'results.candidacy_id', DB::raw('COUNT(*) as vote_count'))
            ->groupBy('results.post_id', 'results.candidacy_id')
            ->get()
            ->groupBy('post_id');

        $posts = Post::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->get(['id', 'name', 'state_name', 'required_number']);

        $postsData = [];
        foreach ($posts as $post) {
            $allCandidates = \App\Models\Candidacy::withoutGlobalScopes()
                ->where('post_id', $post->id)
                ->with('user')
                ->get();

            $postVoteCounts = $voteCounts->get($post->id, collect())->keyBy('candidacy_id');

            $candidates = $allCandidates->map(function ($candidacy) use ($postVoteCounts, $totalVotes) {
                $count = $postVoteCounts->get($candidacy->id)->vote_count ?? 0;
                return [
                    'candidacy_id' => $candidacy->id,
                    'name'         => $candidacy->user->name ?? $candidacy->name ?? 'Unknown',
                    'vote_count'   => $count,
                    'vote_percent' => $totalVotes > 0 ? round($count / $totalVotes * 100, 2) : 0,
                ];
            })->sortByDesc('vote_count')->values()->toArray();

            $noVoteCount = DB::table('results')
                ->where('election_id', $election->id)
                ->where('post_id', $post->id)
                ->whereNull('candidacy_id')
                ->count();

            $postsData[] = [
                'post_id'              => $post->id,
                'post_name'            => $post->name,
                'candidates'           => $candidates,
                'no_vote_count'        => $noVoteCount,
                'total_votes_for_post' => array_sum(array_column($candidates, 'vote_count')) + $noVoteCount,
            ];
        }

        $organisation = $election->organisation_id
            ? \App\Models\Organisation::withoutGlobalScopes()->find($election->organisation_id)
            : null;

        $results = [
            'total_votes'      => $totalVotes,
            'election_name'    => $election->name,
            'election_slug'    => $election->slug,
            'posts'            => $postsData,
            'logo_url'         => $organisation?->logo ? asset($organisation->logo) : null,
            'org_name'         => $organisation?->name,
            'org_slug'         => $organisation?->slug,
        ];

        $postsArray = $posts->map(fn($post) => [
            'id'              => $post->id,
            'name'            => $post->name,
            'state_name'      => $post->state_name,
            'required_number' => $post->required_number,
        ])->values()->toArray();

        return Inertia::render('Result/Index', [
            'final_result' => $results,
            'posts'        => $postsArray,
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
          for ($i = 1; $i <= 60; $i++) {
              $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
              $data = json_decode($vote->$field, true);

              if ($data && $data['post_id'] === $postId) {
                  // Skip "no vote" entries for candidate verification (they don't count toward specific candidates)
                  if (isset($data['no_vote']) && $data['no_vote'] === true) {
                      continue;
                  }

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

  /**
   * Generate and download PDF report of election results
   */
  public function downloadPDF(string $election)
  {
      // Get the election
      $election = Election::withoutGlobalScopes()
          ->where('slug', $election)
          ->firstOrFail();

      // Check if results can be viewed (election finished and published)
      if (!$election->results_published) {
          abort(403, 'Election results have not been published yet.');
      }

      // Get total votes for this election
      $totalVotes = Vote::withoutGlobalScopes()
          ->where('election_id', $election->id)
          ->count();

      // Load vote counts from results table (pre-aggregated at vote submission time)
      $voteCounts = DB::table('results')
          ->where('results.election_id', $election->id)
          ->whereNotNull('results.candidacy_id')
          ->select('results.post_id', 'results.candidacy_id', DB::raw('COUNT(*) as vote_count'))
          ->groupBy('results.post_id', 'results.candidacy_id')
          ->get()
          ->groupBy('post_id');

      $posts = Post::withoutGlobalScopes()
          ->where('election_id', $election->id)
          ->get(['id', 'name', 'state_name', 'required_number']);

      $postsData = [];
      foreach ($posts as $post) {
          $allCandidates = \App\Models\Candidacy::withoutGlobalScopes()
              ->where('post_id', $post->id)
              ->with('user')
              ->get();

          $postVoteCounts = $voteCounts->get($post->id, collect())->keyBy('candidacy_id');

          $candidates = $allCandidates->map(function ($candidacy) use ($postVoteCounts, $totalVotes) {
              $count = $postVoteCounts->get($candidacy->id)->vote_count ?? 0;
              return [
                  'candidacy_id' => $candidacy->id,
                  'name'         => $candidacy->user->name ?? $candidacy->name ?? 'Unknown',
                  'vote_count'   => $count,
                  'vote_percent' => $totalVotes > 0 ? round($count / $totalVotes * 100, 2) : 0,
              ];
          })->sortByDesc('vote_count')->values()->toArray();

          $noVoteCount = DB::table('results')
              ->where('election_id', $election->id)
              ->where('post_id', $post->id)
              ->whereNull('candidacy_id')
              ->count();

          $postsData[] = [
              'post_id'              => $post->id,
              'post_name'            => $post->name,
              'candidates'           => $candidates,
              'no_vote_count'        => $noVoteCount,
              'total_votes_for_post' => array_sum(array_column($candidates, 'vote_count')) + $noVoteCount,
          ];
      }

      $results = [
          'total_votes'   => $totalVotes,
          'election_name' => $election->name,
          'posts'         => $postsData,
      ];

      // Generate PDF
      $pdf = $this->generateResultsPDF($results, $posts);

      // Return PDF download
      return response($pdf->Output('PUBLIC_DIGIT_Election_Results_' . date('Y-m-d') . '.pdf', 'S'))
          ->header('Content-Type', 'application/pdf')
          ->header('Content-Disposition', 'attachment; filename="public_digit_election_results_' . date('Y-m-d') . '.pdf"');
  }

  /**
   * Get election results data (extracted from index method)
   */
  private function getElectionResultsData($posts)
  {
      $results = [
          'total_votes' => Vote::count(),
          'posts' => []
      ];

      foreach ($posts as $post) {
          $postResults = [
              'post_id' => $post->post_id,
              'post_name' => $post->name,
              'state_name' => $post->state_name,
              'candidates' => [],
              'no_vote_count' => 0,
              'total_votes_for_post' => 0
          ];

          // First, get ALL candidates for this post from candidacies table
          $allCandidates = \App\Models\Candidacy::where('post_id', $post->post_id)
              ->with('user')
              ->get();

          // Initialize candidate votes array with all candidates (starting with 0 votes)
          $candidateVotes = [];
          $noVoteCount = 0; // Track "no vote" selections

          foreach ($allCandidates as $candidacy) {
              $candidateName = $candidacy->user->name ?? $candidacy->name ?? $candidacy->user_name ?? 'Unknown';
              $candidateVotes[$candidacy->id] = [
                  'name' => $candidateName,
                  'count' => 0
              ];
          }

          // Then count actual votes
          $votes = Vote::where(function($query) use ($post) {
              for ($i = 1; $i <= 60; $i++) {
                  $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                  $query->orWhereJsonContains($field, ['post_id' => $post->id]);
              }
          })->get();

          foreach ($votes as $vote) {
              for ($i = 1; $i <= 60; $i++) {
                  $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                  $candidateData = json_decode($vote->$field, true);

                  if (!$candidateData || ($candidateData['post_id'] ?? null) !== $post->id) {
                      continue;
                  }

                  // Check if this is a "no vote" selection
                  if (isset($candidateData['no_vote']) && $candidateData['no_vote'] === true) {
                      $noVoteCount++;
                      $postResults['total_votes_for_post']++;
                      continue;
                  }

                  // Count actual candidate votes
                  foreach ($candidateData['candidates'] ?? [] as $candidate) {
                      $candidateId = $candidate['candidacy_id'] ?? null;

                      if ($candidateId && isset($candidateVotes[$candidateId])) {
                          $candidateVotes[$candidateId]['count']++;
                          $postResults['total_votes_for_post']++;
                      }
                  }
              }
          }

          // Format and sort results
          foreach ($candidateVotes as $candidateId => $data) {
              $postResults['candidates'][] = [
                  'candidacy_id' => $candidateId,
                  'name' => $data['name'],
                  'vote_count' => $data['count'],
                  'vote_percent' => $postResults['total_votes_for_post'] > 0
                      ? round(($data['count'] / $postResults['total_votes_for_post']) * 100, 2)
                      : 0
              ];
          }

          // Store the no vote count in results
          $postResults['no_vote_count'] = $noVoteCount;

          // Sort candidates by vote count (highest first, but include zero-vote candidates)
          usort($postResults['candidates'], function($a, $b) {
              if ($a['vote_count'] == $b['vote_count']) {
                  return strcmp($a['name'], $b['name']); // Alphabetical if same vote count
              }
              return $b['vote_count'] - $a['vote_count'];
          });

          $results['posts'][] = $postResults;
      }

      return $results;
  }

  /**
   * Generate PDF document with Public Digit Technology branding
   */
  private function generateResultsPDF($results, $posts)
  {
      // Include TCPDF autoloader if not already loaded
      if (!class_exists('\TCPDF')) {
          require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
      }

      // Define constants if not already defined
      if (!defined('PDF_PAGE_ORIENTATION')) {
          define('PDF_PAGE_ORIENTATION', 'P');
      }
      if (!defined('PDF_UNIT')) {
          define('PDF_UNIT', 'mm');
      }
      if (!defined('PDF_PAGE_FORMAT')) {
          define('PDF_PAGE_FORMAT', 'A4');
      }

      // Create new PDF document
      $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

      // Set document information
      $pdf->SetCreator('Public Digit Technology');
      $pdf->SetAuthor('Public Digit Election System');
      $pdf->SetTitle('Election Results - ' . date('Y-m-d'));
      $pdf->SetSubject('Official Election Results');
      $pdf->SetKeywords(', Election, Results, Official');

      // Set default header data
      $pdf->SetHeaderData('', 0, 'Election Results', 'Generated by Public Digit Technology on ' . date('F j, Y \a\t g:i A'));

      // Set header and footer fonts
      $pdf->setHeaderFont(Array('helvetica', '', 10));
      $pdf->setFooterFont(Array('helvetica', '', 8));

      // Set default monospaced font
      $pdf->SetDefaultMonospacedFont('courier');

      // Set margins
      $pdf->SetMargins(15, 27, 15);
      $pdf->SetHeaderMargin(5);
      $pdf->SetFooterMargin(10);

      // Set auto page breaks
      $pdf->SetAutoPageBreak(TRUE, 25);

      // Set image scale factor
      $pdf->setImageScale(1.25);

      // Add a page
      $pdf->AddPage();

      // Add logo
      $logoPath = public_path('images/logo_publicdigit.jpg');
      if (file_exists($logoPath)) {
          $pdf->Image($logoPath, 15, 30, 30, 30, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
      }

      // Title section
      $pdf->SetFont('helvetica', 'B', 20);
      $pdf->Cell(0, 15, 'Election Results', 0, 1, 'C');
      $pdf->Ln(5);

      // Date and verification
      $pdf->SetFont('helvetica', '', 12);
      $pdf->Cell(0, 10, 'Generated on: ' . date('F j, Y \a\t g:i A'), 0, 1, 'C');
      $pdf->Cell(0, 10, 'Total Votes Cast: ' . number_format($results['total_votes']), 0, 1, 'C');
      $pdf->Ln(10);

      // Public Digit Technology branding
      $pdf->SetFont('helvetica', 'I', 10);
      $pdf->Cell(0, 8, 'Verified and Generated by Public Digit Technology', 0, 1, 'C');
      $pdf->SetFont('helvetica', '', 10);
      $pdf->Cell(0, 8, 'Digital Election Management System', 0, 1, 'C');
      $pdf->Ln(10);

      // Results for each post
      foreach ($results['posts'] as $postResult) {
          if (empty($postResult['candidates'])) {
              continue;
          }

          // Post title
          $pdf->SetFont('helvetica', 'B', 16);
          $pdf->Cell(0, 12, $postResult['post_name'], 0, 1, 'L');

          if (!empty($postResult['state_name'])) {
              $pdf->SetFont('helvetica', '', 12);
              $pdf->Cell(0, 8, 'State/Region: ' . $postResult['state_name'], 0, 1, 'L');
          }

          $pdf->SetFont('helvetica', '', 12);
          $pdf->Cell(0, 8, 'Total Votes for this Post: ' . number_format($postResult['total_votes_for_post']), 0, 1, 'L');
          $pdf->Ln(5);

          // Table header
          $pdf->SetFont('helvetica', 'B', 11);
          $pdf->SetFillColor(220, 220, 220);
          $pdf->Cell(10, 10, '#', 1, 0, 'C', true);
          $pdf->Cell(80, 10, 'Candidate Name', 1, 0, 'L', true);
          $pdf->Cell(30, 10, 'Votes', 1, 0, 'C', true);
          $pdf->Cell(30, 10, 'Percentage', 1, 0, 'C', true);
          $pdf->Cell(30, 10, 'Status', 1, 1, 'C', true);

          // Table content
          $pdf->SetFont('helvetica', '', 10);
          $rank = 1;
          $highestVotes = isset($postResult['candidates'][0]) ? $postResult['candidates'][0]['vote_count'] : 0;

          foreach ($postResult['candidates'] as $index => $candidate) {
              // Check if this candidate has the highest vote count (tied winners)
              $isWinner = $candidate['vote_count'] == $highestVotes && $candidate['vote_count'] > 0;
              $status = $isWinner ? 'WINNER' : 'Candidate';
              $statusColor = $isWinner ? array(34, 139, 34) : array(0, 0, 0);

              // Calculate display rank (handle ties)
              if ($index > 0 && $postResult['candidates'][$index-1]['vote_count'] != $candidate['vote_count']) {
                  $rank = $index + 1;
              }

              $pdf->Cell(10, 8, $rank, 1, 0, 'C');
              $pdf->Cell(80, 8, $candidate['name'], 1, 0, 'L');
              $pdf->Cell(30, 8, number_format($candidate['vote_count']), 1, 0, 'C');
              $pdf->Cell(30, 8, $candidate['vote_percent'] . '%', 1, 0, 'C');

              // Status cell with color
              $pdf->SetTextColor($statusColor[0], $statusColor[1], $statusColor[2]);
              $pdf->Cell(30, 8, $status, 1, 1, 'C');
              $pdf->SetTextColor(0, 0, 0); // Reset to black
          }

          // Add "No Votes" information if any
          if (isset($postResult['no_vote_count']) && $postResult['no_vote_count'] > 0) {
              $pdf->Ln(5);
              $pdf->SetFont('helvetica', 'B', 11);
              $pdf->SetFillColor(240, 240, 240);
              $pdf->Cell(150, 8, 'No Votes (Abstentions)', 1, 0, 'L', true);
              $pdf->Cell(30, 8, number_format($postResult['no_vote_count']), 1, 1, 'C', true);
          }

          $pdf->Ln(10);
      }

      // Footer section
      $pdf->SetY(-50);
      $pdf->SetFont('helvetica', 'I', 8);
      $pdf->Cell(0, 5, 'This document is digitally verified and generated by Public Digit Technology', 0, 1, 'C');
      $pdf->Cell(0, 5, 'Election Management System - Secure & Transparent Elections', 0, 1, 'C');
      $pdf->Cell(0, 5, 'Document ID: public_digit-' . date('Ymd-His') . '-' . substr(md5(serialize($results)), 0, 8), 0, 1, 'C');

      return $pdf;
  }

}
