<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DemoVote;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DemoResultController extends Controller
{
    /**
     * MODE 2: Organisation-scoped demo results (organisation_id = X)
     * Accessible only to users within that organisation context
     */
    public function index()
    {
        // BelongsToTenant scope automatically filters by organisation_id
        $posts = DemoPost::get(['id as post_id', 'name', 'state_name', 'required_number']);

        // Get results data (scoped to current organisation)
        $results = $this->getElectionResultsData($posts, 'organisation');

        return Inertia::render('Demo/Result/Index', [
            'final_result' => $results,
            'posts' => $posts,
            'mode' => 'organisation',
            'organisation_id' => session('current_organisation_id'),
            'is_demo' => true,
            'page_title' => 'Organisation Demo Results'
        ]);
    }

    /**
     * MODE 1: Global/public demo results (organisation_id = NULL)
     * Accessible to any authenticated user
     */
    public function indexGlobal()
    {
        // Explicitly query NULL organisation_id records
        $posts = DemoPost::withoutGlobalScopes()
            ->whereNull('organisation_id')
            ->get(['id as post_id', 'name', 'state_name', 'required_number']);

        // Get results data (global demo only)
        $results = $this->getElectionResultsData($posts, 'global');

        return Inertia::render('Demo/Result/Index', [
            'final_result' => $results,
            'posts' => $posts,
            'mode' => 'global',
            'organisation_id' => null,
            'is_demo' => true,
            'page_title' => 'Global Demo Results'
        ]);
    }

    /**
     * Get demo election results data
     * Handles both MODE 1 (global) and MODE 2 (organisation) data isolation
     */
    private function getElectionResultsData($posts, $mode = 'organisation')
    {
        // Count votes based on mode
        if ($mode === 'global') {
            $totalVotes = DemoVote::withoutGlobalScopes()
                ->whereNull('organisation_id')
                ->count();
        } else {
            $totalVotes = DemoVote::count(); // BelongsToTenant auto-scopes
        }

        $results = [
            'total_votes' => $totalVotes,
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

            // Get candidates for this post (scoped by mode)
            if ($mode === 'global') {
                $allCandidates = DemoCandidacy::withoutGlobalScopes()
                    ->where('post_id', $post->post_id)
                    ->whereNull('organisation_id')
                    ->with('user')
                    ->get();
            } else {
                $allCandidates = DemoCandidacy::where('post_id', $post->post_id)
                    ->with('user')
                    ->get();
            }

            // Initialize candidate votes array
            $candidateVotes = [];
            $noVoteCount = 0;

            foreach ($allCandidates as $candidacy) {
                $candidateName = $candidacy->user->name ?? $candidacy->user_name ?? 'Unknown';
                // Key by `id` (UUID) — votes store the UUID in candidacy_id, not the short candidacy_id code
                $candidateVotes[$candidacy->id] = [
                    'name' => $candidateName,
                    'count' => 0
                ];
            }

            // Get all votes for this post (scoped by mode)
            // Use JSON_EXTRACT syntax: 'field->key' instead of ['key' => value]
            if ($mode === 'global') {
                $votes = DemoVote::withoutGlobalScopes()
                    ->where(function($query) use ($post) {
                        for ($i = 1; $i <= 60; $i++) {
                            $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                            $query->orWhereRaw("JSON_EXTRACT(`$field`, '$.post_id') = ?", [$post->post_id]);
                        }
                    })
                    ->whereNull('organisation_id')
                    ->get();
            } else {
                $votes = DemoVote::where(function($query) use ($post) {
                    for ($i = 1; $i <= 60; $i++) {
                        $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                        $query->orWhereRaw("JSON_EXTRACT(`$field`, '$.post_id') = ?", [$post->post_id]);
                    }
                })->get(); // BelongsToTenant auto-scopes
            }

            // Count votes for each candidate
            foreach ($votes as $vote) {
                for ($i = 1; $i <= 60; $i++) {
                    $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $candidateData = json_decode($vote->$field, true);

                    if (!$candidateData || ($candidateData['post_id'] ?? null) !== $post->post_id) {
                        continue;
                    }

                    // Handle "no vote" selections
                    if (isset($candidateData['no_vote']) && $candidateData['no_vote'] === true) {
                        $noVoteCount++;
                        $postResults['total_votes_for_post']++;
                        continue;
                    }

                    // Count candidate votes
                    foreach ($candidateData['candidates'] ?? [] as $candidate) {
                        $candidateId = $candidate['candidacy_id'] ?? null;

                        if ($candidateId && isset($candidateVotes[$candidateId])) {
                            $candidateVotes[$candidateId]['count']++;
                            $postResults['total_votes_for_post']++;
                        }
                    }
                }
            }

            // Format and sort candidate results
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

            $postResults['no_vote_count'] = $noVoteCount;

            // Sort by votes (highest first) then alphabetically
            usort($postResults['candidates'], function($a, $b) {
                if ($a['vote_count'] == $b['vote_count']) {
                    return strcmp($a['name'], $b['name']);
                }
                return $b['vote_count'] - $a['vote_count'];
            });

            $results['posts'][] = $postResults;
        }

        return $results;
    }

    /**
     * Download PDF for MODE 2 (organisation-scoped demo results)
     */
    public function downloadPDF()
    {
        try {
            // CRITICAL: Clear all output buffers to prevent PDF corruption
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            $posts = DemoPost::get(['id as post_id', 'name', 'state_name', 'required_number']);
            $results = $this->getElectionResultsData($posts, 'organisation');

            $pdf = $this->generateResultsPDF($results, $posts, 'MODE 2: Organisation Demo Results');

            // Generate filename
            $filename = 'demo_election_results_org_' . date('Y-m-d_H-i-s') . '.pdf';

            // Save to temporary file (most reliable method)
            $tempFile = tempnam(sys_get_temp_dir(), 'pdf_');
            $pdf->Output($tempFile, 'F');

            // Validate temp file was created
            if (!file_exists($tempFile) || filesize($tempFile) === 0) {
                throw new \Exception('PDF file was not created or is empty. File: ' . $tempFile);
            }

            // Log success
            \Log::debug('PDF Generated Successfully (MODE 2)', [
                'filename' => $filename,
                'temp_file' => $tempFile,
                'file_size' => filesize($tempFile),
                'pdf_header' => substr(file_get_contents($tempFile), 0, 10)
            ]);

            // Return file download response
            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, must-revalidate, public',
                'Pragma' => 'public',
                'Expires' => '0',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            \Log::error('PDF Download Error (MODE 2)', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'error' => 'PDF Generation Failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download PDF for MODE 1 (global demo results)
     */
    public function downloadGlobalPDF()
    {
        try {
            // CRITICAL: Clear all output buffers to prevent PDF corruption
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            $posts = DemoPost::withoutGlobalScopes()
                ->whereNull('organisation_id')
                ->get(['id as post_id', 'name', 'state_name', 'required_number']);

            $results = $this->getElectionResultsData($posts, 'global');

            $pdf = $this->generateResultsPDF($results, $posts, 'MODE 1: Global Demo Results');

            // Generate filename
            $filename = 'demo_election_results_global_' . date('Y-m-d_H-i-s') . '.pdf';

            // Save to temporary file (most reliable method)
            $tempFile = tempnam(sys_get_temp_dir(), 'pdf_');
            $pdf->Output($tempFile, 'F');

            // Validate temp file was created
            if (!file_exists($tempFile) || filesize($tempFile) === 0) {
                throw new \Exception('PDF file was not created or is empty. File: ' . $tempFile);
            }

            // Log success
            \Log::debug('PDF Generated Successfully (MODE 1)', [
                'filename' => $filename,
                'temp_file' => $tempFile,
                'file_size' => filesize($tempFile),
                'pdf_header' => substr(file_get_contents($tempFile), 0, 10)
            ]);

            // Return file download response
            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, must-revalidate, public',
                'Pragma' => 'public',
                'Expires' => '0',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            \Log::error('PDF Download Error (MODE 1)', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'error' => 'PDF Generation Failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF document with election results
     */
    private function generateResultsPDF($results, $posts, $modeTitle = 'Demo Election Results')
    {
        try {
            // Create PDF object with UTF-8 encoding
            // Note: TCPDF is automatically loaded by Composer's autoloader
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // Disable header and footer to keep output clean
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Set document metadata
            $pdf->SetCreator('Public Digit - Demo Election System');
            $pdf->SetAuthor('Public Digit');
            $pdf->SetTitle('Demo Election Results');
            $pdf->SetSubject('Election Results');
            $pdf->SetKeywords('election, results, demo');

            // Set margins (left, top, right)
            $pdf->SetMargins(12, 12, 12);
            $pdf->SetAutoPageBreak(true, 15);

            // Add first page
            $pdf->AddPage('P', 'A4');

            // ===== PAGE CONTENT =====

            // Title section
            $pdf->SetFont('Helvetica', 'B', 18);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 12, $modeTitle, 0, 1, 'C');
            $pdf->SetLineWidth(0.5);
            $pdf->Line(12, $pdf->GetY(), 198, $pdf->GetY());
            $pdf->Ln(3);

            // Header info
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetTextColor(60, 60, 60);
            $pdf->Cell(0, 6, 'Generated: ' . date('Y-m-d H:i:s'), 0, 1, 'C');
            $pdf->Cell(0, 6, 'Total Votes Cast: ' . intval($results['total_votes'] ?? 0), 0, 1, 'C');
            $pdf->Ln(5);

            // Results section
            if (empty($results['posts']) || count($results['posts']) === 0) {
                $pdf->SetFont('Helvetica', '', 12);
                $pdf->SetTextColor(100, 100, 100);
                $pdf->Cell(0, 10, 'No election results available yet.', 0, 1, 'C');
            } else {
                // Process each post/position
                foreach ($results['posts'] as $postResult) {
                    // Skip if no candidates
                    if (empty($postResult['candidates']) || count($postResult['candidates']) === 0) {
                        continue;
                    }

                    // Position title
                    $pdf->SetFont('Helvetica', 'B', 13);
                    $pdf->SetTextColor(20, 20, 80);
                    $postName = !empty($postResult['post_name']) ? (string)$postResult['post_name'] : 'Position';
                    $pdf->Cell(0, 9, $postName, 0, 1, 'L');

                    // Region and vote info
                    $pdf->SetFont('Helvetica', '', 9);
                    $pdf->SetTextColor(80, 80, 80);
                    if (!empty($postResult['state_name'])) {
                        $pdf->Cell(0, 6, 'Region: ' . (string)$postResult['state_name'], 0, 1, 'L');
                    }
                    $pdf->Cell(0, 6, 'Total Votes for Position: ' . intval($postResult['total_votes_for_post'] ?? 0), 0, 1, 'L');
                    $pdf->Ln(1);

                    // Table header
                    $pdf->SetFont('Helvetica', 'B', 9);
                    $pdf->SetTextColor(255, 255, 255);
                    $pdf->SetFillColor(40, 40, 100);
                    $pdf->Cell(10, 7, '#', 1, 0, 'C', true);
                    $pdf->Cell(70, 7, 'Candidate Name', 1, 0, 'L', true);
                    $pdf->Cell(25, 7, 'Votes', 1, 0, 'C', true);
                    $pdf->Cell(23, 7, 'Percent', 1, 1, 'R', true);

                    // Table rows
                    $pdf->SetFont('Helvetica', '', 8);
                    $pdf->SetTextColor(0, 0, 0);
                    $rank = 1;
                    $rowColor = 1;

                    foreach ($postResult['candidates'] as $candidate) {
                        // Alternate row colors
                        if ($rowColor % 2 === 0) {
                            $pdf->SetFillColor(240, 240, 245);
                            $fill = true;
                        } else {
                            $fill = false;
                        }

                        $name = !empty($candidate['name']) ? substr((string)$candidate['name'], 0, 40) : 'Unknown';
                        $votes = intval($candidate['vote_count'] ?? 0);
                        $percent = floatval($candidate['vote_percent'] ?? 0);

                        $pdf->Cell(10, 6, (string)$rank, 1, 0, 'C', $fill);
                        $pdf->Cell(70, 6, $name, 1, 0, 'L', $fill);
                        $pdf->Cell(25, 6, (string)$votes, 1, 0, 'C', $fill);
                        $pdf->Cell(23, 6, round($percent, 1) . '%', 1, 1, 'R', $fill);
                        $rank++;
                        $rowColor++;
                    }

                    // Abstentions row
                    if (!empty($postResult['no_vote_count'])) {
                        $pdf->SetFont('Helvetica', 'B', 8);
                        $pdf->SetFillColor(255, 240, 240);
                        $pdf->Cell(80, 6, 'Abstentions', 1, 0, 'L', true);
                        $pdf->Cell(25, 6, (string)intval($postResult['no_vote_count']), 1, 0, 'C', true);
                        $pdf->Cell(23, 6, '', 1, 1, 'R', true);
                    }

                    $pdf->Ln(4);
                }
            }

            // Footer
            $pdf->SetFont('Helvetica', 'I', 8);
            $pdf->SetTextColor(120, 120, 120);
            $pdf->Cell(0, 6, 'This is a demo election result - Not for official use', 0, 1, 'C');

            return $pdf;

        } catch (\Exception $e) {
            \Log::error('PDF Generation Error', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Verify demo results (verify official count matches raw votes)
     */
    public function verifyResults($postId)
    {
        $officialResults = DB::table('demo_results')
            ->select('candidacy_id', DB::raw('COUNT(*) as vote_count'))
            ->where('post_id', $postId)
            ->groupBy('candidacy_id')
            ->get()
            ->keyBy('candidacy_id');

        $rawVotes = [];
        $votes = DemoVote::whereNotNull('candidate_01')->get();

        foreach ($votes as $vote) {
            for ($i = 1; $i <= 60; $i++) {
                $field = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                $data = json_decode($vote->$field, true);

                if ($data && $data['post_id'] === $postId) {
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

        $discrepancies = [];
        foreach ($officialResults as $candidacyId => $official) {
            if (($rawVotes[$candidacyId] ?? 0) !== $official->vote_count) {
                $discrepancies[$candidacyId] = [
                    'official' => $official->vote_count,
                    'raw' => $rawVotes[$candidacyId] ?? 0
                ];
            }
        }

        return response()->json([
            'official_results' => $officialResults,
            'raw_vote_counts' => $rawVotes,
            'discrepancies' => $discrepancies,
            'match' => empty($discrepancies)
        ]);
    }

    /**
     * Statistical verification of results
     */
    public function statisticalVerification($postId)
    {
        $officialTotal = DB::table('demo_results')
            ->where('post_id', $postId)
            ->count(DB::raw('DISTINCT vote_id'));

        $voteTableTotal = DemoVote::count();

        $candidates = DB::table('demo_results')
            ->select('candidacy_id', DB::raw('COUNT(*) as votes'))
            ->where('post_id', $postId)
            ->groupBy('candidacy_id')
            ->get();

        $stats = [
            'vote_counts' => $candidates->pluck('votes', 'candidacy_id'),
            'average' => $candidates->avg('votes'),
            'standard_deviation' => $this->calculateStdDev($candidates->pluck('votes')->toArray())
        ];

        return response()->json([
            'total_votes_match' => $officialTotal == $voteTableTotal,
            'official_total' => $officialTotal,
            'vote_table_total' => $voteTableTotal,
            'statistics' => $stats,
            'anomalies' => $this->detectAnomalies($stats)
        ]);
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

}
