<?php

declare(strict_types=1);

/**
 * Runner: assemble facts from the observation streams, evaluate the rules,
 * append NEW recommendations (dedup by stable id) to recommendations.jsonl.
 *
 * Usage: php scripts/observations/recommendation-observer.php
 * Advisory only. OBS_DIR overrides the observations directory for tests.
 */

require_once __DIR__ . '/RecommendationEngine.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$obsDir = getenv('OBS_DIR') ?: __DIR__ . '/../../engineering/verification/observations';
$metricsTrend = __DIR__ . '/../../engineering/verification/metrics/trend.jsonl';
$rules = Yaml::parseFile(__DIR__ . '/recommendation-rules.yaml')['rules'];

$readJsonl = function (string $file): array {
    if (!is_file($file)) {
        return [];
    }
    $rows = [];
    foreach (array_filter(explode("\n", trim((string) file_get_contents($file)))) as $line) {
        if (($row = json_decode($line, true)) !== null) {
            $rows[] = $row;
        }
    }
    return $rows;
};

// ---- assemble facts from the streams (the engine never reads files) ----
$facts = [];

$lcomRuns = $readJsonl($obsDir . '/lcom4.jsonl');
if ($lcomRuns !== []) {
    $latestPerClass = [];
    foreach ($lcomRuns as $run) {
        foreach ($run['observations'] ?? [] as $o) {
            $latestPerClass[$o['class']] = ['subject' => $o['class'], 'value' => $o['value'], 'evidence_refs' => ['lcom4.jsonl']];
        }
    }
    $facts['lcom4'] = array_values($latestPerClass);
}

$tp = $readJsonl($obsDir . '/test-presence.jsonl');
if ($tp !== []) {
    $last = end($tp);
    $facts['test_presence'] = [[
        'subject' => $last['range'] ?? 'unknown-range',
        'production_without_tests' => (bool) ($last['production_without_tests'] ?? false),
        'evidence_refs' => ['test-presence.jsonl'],
    ]];
}

$snapshots = $readJsonl($metricsTrend);
if (count($snapshots) >= 2) {
    $prev = $snapshots[count($snapshots) - 2];
    $last = end($snapshots);
    $facts['cbo_trend'] = [[
        'subject' => sprintf('%s → %s', $prev['commit'] ?? '?', $last['commit'] ?? '?'),
        'rising'  => ($last['mean_cbo'] ?? 0) > ($prev['mean_cbo'] ?? 0),
        'evidence_refs' => ['metrics/trend.jsonl'],
    ]];
    // persistent hotspot: present in the last two snapshots that carry hotspots
    $h1 = array_keys($prev['hotspots'] ?? []);
    $h2 = array_keys($last['hotspots'] ?? []);
    foreach (array_intersect($h1, $h2) as $name) {
        $facts['persistent_hotspot'][] = ['subject' => $name, 'flag' => true, 'evidence_refs' => ['metrics/trend.jsonl']];
    }
}
if ($snapshots !== []) {
    $last = end($snapshots);
    // WARN + hotspot: hotspot classes whose latest watch CBO exceeds their stereotype WARN bar
    // v1 approximation: any hotspot with CBO >= 25 (controller watch bar) counts; refine via config later.
    foreach (($last['hotspots'] ?? []) as $name => $changes) {
        $cbo = $last['watch'][$name][0] ?? 0;
        if ($cbo >= 25) {
            $facts['warn_hotspot'][] = ['subject' => $name, 'flag' => true, 'evidence_refs' => ['metrics/trend.jsonl']];
        }
    }
}

// ---- evaluate + dedup against the existing log ----
$logFile = $obsDir . '/recommendations.jsonl';
$known = array_column($readJsonl($logFile), 'id');
$drafts = RecommendationEngine::evaluate($facts, $rules);
$new = array_values(array_filter($drafts, fn (array $r) => !in_array($r['id'], $known, true)));

echo "\n== Recommendation Engine v1 (advisory — the developer decides) ==\n";
printf("rules: %d · facts sources: %d · drafts: %d · new (after dedup): %d\n", count($rules), count($facts), count($drafts), count($new));
foreach ($new as $r) {
    printf("  %s [%s] %s\n      → %s\n", $r['id'], $r['rule'], $r['subject'], $r['text']);
}
if ($new !== []) {
    if (!is_dir($obsDir)) {
        mkdir($obsDir, 0777, true);
    }
    $lines = implode("\n", array_map(fn ($r) => json_encode($r, JSON_UNESCAPED_SLASHES), $new)) . "\n";
    file_put_contents($logFile, $lines, FILE_APPEND);
    echo count($new) . " recommendation(s) appended → observations/recommendations.jsonl\n";
} else {
    echo "no new recommendations (all open items already issued)\n";
}
echo "decide with: php scripts/observations/recommendation-decide.php <id> accepted|ignored|deferred <reason_code> [comment]\n";
exit(0);
