<?php

/**
 * Verify: Which columns are in BASE migrations vs FIX migrations?
 * 
 * This determines whether we can delete old migrations or if we need
 * to update the base migrations themselves.
 */

echo "\n╔════════════════════════════════════════════════════════════════════════╗\n";
echo "║                  BASE vs FIX MIGRATION VERIFICATION                   ║\n";
echo "╚════════════════════════════════════════════════════════════════════════╝\n\n";

$migrationPath = __DIR__ . '/../migrations';

// Define critical columns that MUST exist somewhere
$criticalColumns = [
    'votes' => ['post_id', 'no_vote_option', 'voting_code', 'candidate_01'],
    'posts' => ['post_id', 'nepali_name'],
    'candidacies' => ['candidacy_id', 'candidacy_name'],
    'voter_slugs' => ['user_id', 'expires_at', 'is_active'],
    'codes' => ['code3', 'code4'],
    'results' => ['candidacy_id'],
    'demo_posts' => ['post_id', 'nepali_name', 'organisation_id'],
];

// Separate base (000001-000014) from fix (000015+) migrations
$baseMigrations = [];
$fixMigrations = [];

foreach (glob($migrationPath . '/2026_03_01_*.php') as $file) {
    $filename = basename($file);
    if (preg_match('/2026_03_01_00(000[1-9]|001[0-4])_/', $filename)) {
        $baseMigrations[$filename] = $file;
    } else {
        $fixMigrations[$filename] = $file;
    }
}

ksort($baseMigrations);
ksort($fixMigrations);

echo "Found: " . count($baseMigrations) . " base migrations (000001-000014)\n";
echo "Found: " . count($fixMigrations) . " fix migrations (000015+)\n\n";

// Function to extract columns from migration
function getColumnsFromMigration($filePath, $tableName) {
    $content = file_get_contents($filePath);
    
    // Extract the up() method
    if (!preg_match('/function\s+up\(\).*?\{(.*?)\}/s', $content, $matches)) {
        return [];
    }
    
    $upCode = $matches[1];
    
    // Look for the specific table creation/alteration
    if (preg_match('/Schema::(create|table)\([\'"]' . $tableName . '[\'"].*?\{(.*?)\}\);/s', $upCode, $tableMatches)) {
        $tableCode = $tableMatches[2];
        
        // Extract all column definitions
        preg_match_all('/\$table->([a-zA-Z]+)\([\'"]?([^\'"(),]+)[\'"]?/', $tableCode, $columnMatches, PREG_SET_ORDER);
        
        $columns = [];
        foreach ($columnMatches as $match) {
            $columns[$match[2]] = $match[1];
        }
        return $columns;
    }
    
    return [];
}

// Build a map of where each critical column exists
$columnLocations = [];

foreach ($criticalColumns as $tableName => $columns) {
    $columnLocations[$tableName] = [];
    
    foreach ($columns as $columnName) {
        $columnLocations[$tableName][$columnName] = [
            'in_base' => false,
            'base_file' => null,
            'in_fix' => false,
            'fix_file' => null,
        ];
    }
}

// Check base migrations
foreach ($baseMigrations as $filename => $filePath) {
    foreach ($criticalColumns as $tableName => $columns) {
        $tableColumns = getColumnsFromMigration($filePath, $tableName);
        foreach ($columns as $columnName) {
            if (isset($tableColumns[$columnName])) {
                $columnLocations[$tableName][$columnName]['in_base'] = true;
                $columnLocations[$tableName][$columnName]['base_file'] = $filename;
            }
        }
    }
}

// Check fix migrations
foreach ($fixMigrations as $filename => $filePath) {
    foreach ($criticalColumns as $tableName => $columns) {
        $tableColumns = getColumnsFromMigration($filePath, $tableName);
        foreach ($columns as $columnName) {
            if (isset($tableColumns[$columnName])) {
                $columnLocations[$tableName][$columnName]['in_fix'] = true;
                $columnLocations[$tableName][$columnName]['fix_file'] = $filename;
            }
        }
    }
}

// Generate report
echo "\n╔════════════════════════════════════════════════════════════════════════╗\n";
echo "║                        VERIFICATION RESULTS                            ║\n";
echo "╚════════════════════════════════════════════════════════════════════════╝\n\n";

$baseHasAll = true;
$fixAddedMissing = true;

foreach ($columnLocations as $tableName => $columns) {
    echo "TABLE: $tableName\n";
    echo str_repeat("─", 70) . "\n";
    
    foreach ($columns as $columnName => $location) {
        $status = "";
        
        if ($location['in_base']) {
            $status = "✅ IN BASE ({$location['base_file']})";
        } elseif ($location['in_fix']) {
            $status = "⚠️  ONLY IN FIX ({$location['fix_file']})";
            $baseHasAll = false;
        } else {
            $status = "❌ MISSING EVERYWHERE";
            $fixAddedMissing = false;
        }
        
        echo "  $columnName: $status\n";
    }
    echo "\n";
}

// Summary
echo "╔════════════════════════════════════════════════════════════════════════╗\n";
echo "║                           SUMMARY                                      ║\n";
echo "╚════════════════════════════════════════════════════════════════════════╝\n\n";

if ($baseHasAll && $fixAddedMissing) {
    echo "✅ ALL CRITICAL COLUMNS ARE IN BASE MIGRATIONS!\n\n";
    echo "Decision: ✅ Safe to delete old migrations\n";
    echo "Reason: Base migrations already have everything. Fix migrations are redundant.\n";
} else {
    echo "⚠️  SOME CRITICAL COLUMNS ARE MISSING FROM BASE MIGRATIONS\n\n";
    echo "Decision: ⚠️  Must KEEP fix migrations\n";
    echo "Reason: Base migrations are incomplete. Fix migrations add necessary columns.\n";
}

echo "\n";

// Generate detailed report file
$report = [
    'timestamp' => date('Y-m-d H:i:s'),
    'base_has_all_critical' => $baseHasAll,
    'fix_adds_missing' => $fixAddedMissing,
    'column_locations' => $columnLocations,
];

file_put_contents(__DIR__ . '/base_vs_fix_verification.json', json_encode($report, JSON_PRETTY_PRINT));
echo "✓ Detailed report saved to base_vs_fix_verification.json\n\n";

