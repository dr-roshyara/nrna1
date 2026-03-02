<?php

/**
 * Compare OLD vs NEW migration columns
 * Identify missing columns in new consolidated migrations
 */

$oldData = json_decode(file_get_contents(__DIR__ . '/old_columns.json'), true);
$newData = json_decode(file_get_contents(__DIR__ . '/new_columns.json'), true);

echo "═══════════════════════════════════════════════════════════════\n";
echo "MIGRATION COLUMN COMPARISON REPORT\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$missingColumns = [];
$extraColumns = [];
$totalOldColumns = 0;
$totalNewColumns = 0;

// Check each table in old migrations
foreach ($oldData as $tableName => $oldInfo) {
    $totalOldColumns += count($oldInfo['columns']);

    if (!isset($newData[$tableName])) {
        echo "⚠️  TABLE NOT FOUND IN NEW MIGRATIONS: $tableName\n";
        echo "   Old file: {$oldInfo['file']}\n";
        echo "   Columns: " . count($oldInfo['columns']) . "\n\n";
        continue;
    }

    $newInfo = $newData[$tableName];
    $oldColumns = array_keys($oldInfo['columns']);
    $newColumns = array_keys($newInfo['columns']);

    // Find missing columns
    $missing = array_diff($oldColumns, $newColumns);
    if (count($missing) > 0) {
        $missingColumns[$tableName] = [
            'missing' => $missing,
            'old_file' => $oldInfo['file'],
            'new_file' => $newInfo['file']
        ];

        echo "❌ MISSING COLUMNS IN: $tableName\n";
        echo "   Old file: {$oldInfo['file']}\n";
        echo "   New file: {$newInfo['file']}\n";
        echo "   Missing columns (" . count($missing) . "):\n";
        foreach ($missing as $col) {
            echo "     - $col ({$oldInfo['columns'][$col]})\n";
        }
        echo "\n";
    }

    // Find extra columns (shouldn't happen, but good to know)
    $extra = array_diff($newColumns, $oldColumns);
    if (count($extra) > 0) {
        $extraColumns[$tableName] = [
            'extra' => $extra,
            'old_file' => $oldInfo['file'],
            'new_file' => $newInfo['file']
        ];
    }
}

// Count columns in new migrations
foreach ($newData as $table => $info) {
    $totalNewColumns += count($info['columns']);
}

// Summary
echo "═══════════════════════════════════════════════════════════════\n";
echo "SUMMARY\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "Old migrations total columns: $totalOldColumns\n";
echo "New migrations total columns: $totalNewColumns\n";
echo "Missing column definitions: " . count($missingColumns) . " tables affected\n";
echo "Extra columns in new: " . count($extraColumns) . "\n\n";

if (count($missingColumns) === 0) {
    echo "✅ ALL COLUMNS PRESENT - No columns are missing!\n";
} else {
    echo "⚠️  ACTION REQUIRED - Missing columns detected above\n";
}

if (count($extraColumns) > 0) {
    echo "\n📝 NEW COLUMNS ADDED:\n";
    foreach ($extraColumns as $tableName => $info) {
        echo "   $tableName: " . implode(", ", $info['extra']) . "\n";
    }
}

// Save detailed report
$report = [
    'timestamp' => date('Y-m-d H:i:s'),
    'total_old_columns' => $totalOldColumns,
    'total_new_columns' => $totalNewColumns,
    'tables_with_missing_columns' => count($missingColumns),
    'missing_columns' => $missingColumns,
    'extra_columns' => $extraColumns
];

file_put_contents(__DIR__ . '/comparison_report.json', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo "\n✓ Detailed report saved to database/audit_scripts/comparison_report.json\n";
