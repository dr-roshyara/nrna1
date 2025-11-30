<?php

/**
 * Script to fix corrupted vote data
 *
 * Bug Pattern: {no_vote: false, candidates: []}
 * Fix: Change to {no_vote: true, candidates: []}
 *
 * Run this script ONCE on production to fix existing corrupted votes
 *
 * Usage: php fix-corrupted-votes.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  VOTE DATA CORRUPTION FIX SCRIPT                              ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

echo "⚠️  WARNING: This script will modify vote data in the database!\n";
echo "Please ensure you have a backup before proceeding.\n\n";

// Ask for confirmation
echo "Do you want to continue? (yes/no): ";
$handle = fopen ("php://stdin","r");
$line = trim(fgets($handle));

if(strtolower($line) !== 'yes'){
    echo "\n❌ Script cancelled.\n";
    exit;
}
fclose($handle);

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "Starting corruption scan and fix...\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Get all votes
$votes = DB::table('votes')->get();

$totalVotes = $votes->count();
$totalCorruptions = 0;
$totalFixed = 0;
$votesAffected = [];

echo "Found {$totalVotes} total votes to check.\n\n";

foreach ($votes as $vote) {
    $voteId = $vote->id;
    $hasCorruption = false;
    $fixedColumns = [];

    // Check all candidate columns
    for ($i = 1; $i <= 60; $i++) {
        $colName = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);

        if (isset($vote->$colName) && $vote->$colName !== null) {
            $data = json_decode($vote->$colName, true);

            if ($data) {
                $no_vote = $data['no_vote'] ?? null;
                $candidates = $data['candidates'] ?? null;

                // Detect bug pattern: no_vote=false with empty candidates array
                if ($no_vote === false && is_array($candidates) && count($candidates) === 0) {
                    $hasCorruption = true;
                    $totalCorruptions++;

                    echo "🐛 Corruption found in Vote ID {$voteId}, Column: {$colName}\n";
                    echo "   Post: " . ($data['post_name'] ?? 'Unknown') . "\n";
                    echo "   Before: " . json_encode($data) . "\n";

                    // Fix the data
                    $data['no_vote'] = true;

                    echo "   After:  " . json_encode($data) . "\n";

                    // Update the database
                    DB::table('votes')
                        ->where('id', $voteId)
                        ->update([
                            $colName => json_encode($data)
                        ]);

                    $fixedColumns[] = $colName;
                    $totalFixed++;

                    echo "   ✅ Fixed!\n\n";
                }
            }
        }
    }

    if ($hasCorruption) {
        $votesAffected[] = [
            'vote_id' => $voteId,
            'columns' => $fixedColumns,
            'count' => count($fixedColumns)
        ];
    }
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "SUMMARY\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "Total votes scanned: {$totalVotes}\n";
echo "Total corruptions found: {$totalCorruptions}\n";
echo "Total fields fixed: {$totalFixed}\n";
echo "Votes affected: " . count($votesAffected) . "\n\n";

if (count($votesAffected) > 0) {
    echo "Affected Vote IDs:\n";
    foreach ($votesAffected as $affected) {
        echo "  - Vote ID {$affected['vote_id']}: {$affected['count']} field(s) fixed (" . implode(', ', $affected['columns']) . ")\n";
    }
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "FIX COMPLETE!\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

if ($totalFixed > 0) {
    echo "✅ All corrupted data has been fixed.\n";
    echo "   The bug pattern {no_vote: false, candidates: []} has been\n";
    echo "   corrected to {no_vote: true, candidates: []}.\n\n";
    echo "📝 RECOMMENDATION:\n";
    echo "   1. Verify the fixes in your database\n";
    echo "   2. Re-run vote counting/results generation if needed\n";
    echo "   3. Deploy the frontend/backend fixes to prevent future occurrences\n\n";
} else {
    echo "✅ No corruptions found. Your data is clean!\n\n";
}

echo "Script finished at " . date('Y-m-d H:i:s') . "\n";
