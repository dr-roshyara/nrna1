<?php

/**
 * Extract all columns from OLD migration files
 * (Before consolidation on 2026-03-01)
 *
 * This script reads all migration files EXCEPT the new 2026_03_01_* migrations
 * and extracts table and column definitions.
 */

// Get all migration files
$migrationPath = __DIR__ . '/../migrations';
$files = scandir($migrationPath);

$oldMigrations = [];
$tables = [];

foreach ($files as $file) {
    // Skip . and .. and new consolidated migrations
    if ($file === '.' || $file === '..' || strpos($file, '2026_03_01_') === 0) {
        continue;
    }

    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
        continue;
    }

    $filePath = $migrationPath . '/' . $file;
    $content = file_get_contents($filePath);

    // Extract table name from Schema::create('table_name', ...)
    if (preg_match('/Schema::create\([\'"]([^\'"]+)/', $content, $matches)) {
        $tableName = $matches[1];

        // Extract all column definitions
        preg_match('/function\s+up\(\).*?\{(.*?)\}/s', $content, $upMatch);
        if (!isset($upMatch[1])) {
            continue;
        }

        $upCode = $upMatch[1];

        // Find all $table->* column definitions
        preg_match_all('/\$table->([a-zA-Z]+)\([\'"]?([^\'"(),]+)[\'"]?[^;]*\);/', $upCode, $columnMatches, PREG_SET_ORDER);

        $columns = [];
        foreach ($columnMatches as $match) {
            $columnType = $match[1];
            $columnName = $match[2];
            $columns[$columnName] = $columnType;
        }

        if (count($columns) > 0) {
            $tables[$tableName] = [
                'file' => $file,
                'columns' => $columns
            ];
        }
    }
}

// Output results
echo "═══════════════════════════════════════════════════════════════\n";
echo "OLD MIGRATIONS COLUMN EXTRACTION\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

ksort($tables);

foreach ($tables as $tableName => $info) {
    echo "TABLE: $tableName\n";
    echo "File: {$info['file']}\n";
    echo "Columns: " . count($info['columns']) . "\n";

    foreach (array_keys($info['columns']) as $column) {
        echo "  - $column\n";
    }
    echo "\n";
}

// Save as JSON for comparison
file_put_contents(__DIR__ . '/old_columns.json', json_encode($tables, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo "✓ Data saved to database/audit_scripts/old_columns.json\n";
