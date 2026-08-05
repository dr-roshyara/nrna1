<?php

declare(strict_types=1);

/**
 * Static Engineering Metrics — spike runner v3 (advisory only).
 *
 * v3 (review 2026-08-03): configuration file (weights + per-stereotype
 * threshold bands) · band labels (OK / WATCH / WARN) · hotspots =
 * watchlist ∩ git change frequency (the first dynamic observation).
 * v2: risk score · stereotype baselines · watchlist trend · commit deltas.
 *
 * Usage:
 *   php scripts/metrics/metrics-report.php <summary.xml> [topN=5]
 *
 * Scope guard: OBSERVES and WARNS only. Never fails a build. No assessment
 * or recommendation logic — bands label numbers, humans judge them
 * (spike plan 20260803-2130, REV 3: "human judgment still decides").
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$summaryPath = $argv[1] ?? null;
$topN = (int) ($argv[2] ?? 5);

if ($summaryPath === null || !is_file($summaryPath)) {
    fwrite(STDERR, "usage: php scripts/metrics/metrics-report.php <summary.xml> [topN]\n");
    exit(2);
}

$config = Yaml::parseFile(__DIR__ . '/metrics-config.yaml');
$w = $config['risk']['weights'];
$bands = $config['thresholds'];
$watchCfg = $config['watchlist'];
$hotCfg = $config['hotspots'];

$xml = simplexml_load_file($summaryPath);
if ($xml === false) {
    fwrite(STDERR, "cannot parse {$summaryPath}\n");
    exit(2);
}

function stereotype(string $fqcn): string
{
    return match (true) {
        str_contains($fqcn, 'ServiceProvider') || str_contains($fqcn, '\Providers\\') => 'provider',
        str_contains($fqcn, '\Http\Controllers\\')                                    => 'controller',
        str_contains($fqcn, '\Models\\')                                              => 'model',
        str_contains($fqcn, '\Domain\\') || str_contains($fqcn, '\Contexts\\')        => 'domain',
        default                                                                        => 'other',
    };
}

function band(int $cbo, array $bands): string
{
    return match (true) {
        $cbo <= $bands['ok']    => 'OK',
        $cbo <= $bands['watch'] => 'WATCH',
        default                 => 'WARN',
    };
}

$repoRoot = realpath(__DIR__ . '/../..');
$classes = [];
foreach ($xml->xpath('//class') as $class) {
    $attrs = $class->attributes();
    $name = (string) $attrs['fqname'] ?: (string) $attrs['name'];
    $file = isset($class->file) ? (string) $class->file->attributes()['name'] : '';
    if ($file !== '' && $repoRoot !== false && str_starts_with($file, $repoRoot)) {
        $file = str_replace('\\', '/', substr($file, strlen($repoRoot) + 1));
    }
    $ca = (int) $attrs['ca'];
    $ce = (int) $attrs['ce'];
    $st = stereotype($name);
    $classes[] = [
        'name' => $name,
        'file' => $file,
        'st'   => $st,
        'cbo'  => (int) $attrs['cbo'],
        'inst' => ($ca + $ce) > 0 ? $ce / ($ca + $ce) : 0.0,
        'wmc'  => (int) $attrs['wmc'],
        'band' => band((int) $attrs['cbo'], $bands[$st]),
    ];
}

if ($classes === []) {
    fwrite(STDERR, "no classes found in summary — wrong input?\n");
    exit(2);
}

$byStereotype = [];
foreach ($classes as $c) {
    $byStereotype[$c['st']][] = $c;
}
foreach ($byStereotype as $st => &$group) {
    $maxCbo = max(1, max(array_column($group, 'cbo')));
    $maxWmc = max(1, max(array_column($group, 'wmc')));
    foreach ($group as &$c) {
        $c['risk'] = round(
            $w['cbo'] * ($c['cbo'] / $maxCbo) + $w['wmc'] * ($c['wmc'] / $maxWmc) + $w['instability'] * $c['inst'],
            3
        );
    }
    unset($c);
    usort($group, fn (array $a, array $b) => $b['risk'] <=> $a['risk']);
}
unset($group);

$count = count($classes);
$meanCbo = round(array_sum(array_column($classes, 'cbo')) / $count, 2);
$bandCounts = array_count_values(array_column($classes, 'band'));

echo "\n== Static Engineering Metrics v3 (advisory — never fails the build) ==\n";
printf(
    "classes: %d · mean CBO: %.2f · bands: OK %d / WATCH %d / WARN %d\n",
    $count,
    $meanCbo,
    $bandCounts['OK'] ?? 0,
    $bandCounts['WATCH'] ?? 0,
    $bandCounts['WARN'] ?? 0
);
echo "risk = {$w['cbo']}·CBO + {$w['wmc']}·WMC (normalized within stereotype) + {$w['instability']}·instability · bands from metrics-config.yaml\n";

$order = ['domain', 'model', 'controller', 'other', 'provider'];
foreach ($order as $st) {
    if (!isset($byStereotype[$st])) {
        continue;
    }
    $group = $byStereotype[$st];
    $n = count($group);
    $stMean = round(array_sum(array_column($group, 'cbo')) / $n, 1);
    $b = $bands[$st];
    echo "\n[{$st}] n={$n} · mean CBO {$stMean} · bands ok≤{$b['ok']} watch≤{$b['watch']}"
        . ($st === 'provider' ? '  (wiring — coupling is their job)' : '') . "\n";
    foreach (array_slice($group, 0, $topN) as $c) {
        printf(
            "  %-5s risk=%.2f  CBO=%-3d WMC=%-3d I=%.2f  %s\n",
            $c['band'],
            $c['risk'],
            $c['cbo'],
            $c['wmc'],
            $c['inst'],
            $c['name']
        );
    }
}

// --- watchlist (per-class trend basis) ---
$watch = [];
$watchFiles = [];
foreach ($classes as $c) {
    if ($c['cbo'] >= $watchCfg['min_cbo'] || $c['wmc'] >= $watchCfg['min_wmc']) {
        $watch[$c['name']] = [$c['cbo'], $c['wmc']];
        if ($c['file'] !== '') {
            $watchFiles[$c['name']] = $c['file'];
        }
    }
}
ksort($watch);

// --- hotspots: watchlist ∩ git change frequency (first DYNAMIC observation) ---
$freq = [];
$gitOut = (string) shell_exec(sprintf('git log --name-only --pretty=format: -n %d -- app 2>&1', (int) $hotCfg['git_commits']));
foreach (explode("\n", $gitOut) as $line) {
    $line = trim(str_replace('\\', '/', $line));
    if ($line !== '') {
        $freq[$line] = ($freq[$line] ?? 0) + 1;
    }
}
$hotspots = [];
foreach ($watchFiles as $name => $file) {
    $changes = $freq[$file] ?? 0;
    if ($changes >= (int) $hotCfg['min_changes']) {
        $hotspots[$name] = $changes;
    }
}
arsort($hotspots);

if ($hotspots !== []) {
    echo "\n🔥 hotspots (watchlist class AND file changed ≥{$hotCfg['min_changes']}× in last {$hotCfg['git_commits']} commits):\n";
    foreach ($hotspots as $name => $changes) {
        printf("  %-3d changes · CBO=%-3d WMC=%-4d %s\n", $changes, $watch[$name][0], $watch[$name][1], $name);
    }
} else {
    echo "\nhotspots: none (no watchlist file changed ≥{$hotCfg['min_changes']}× in last {$hotCfg['git_commits']} commits)\n";
}

// --- trend + deltas ---
// METRICS_TREND_DIR override exists ONLY so tests never append to the real
// evidence file (bug-fix class: evidence integrity). Default is unchanged.
$trendDir = getenv('METRICS_TREND_DIR') ?: __DIR__ . '/../../engineering/verification/metrics';
if (!is_dir($trendDir)) {
    mkdir($trendDir, 0777, true);
}
$trendFile = $trendDir . '/trend.jsonl';

$previous = null;
if (is_file($trendFile)) {
    $lines = array_filter(explode("\n", trim((string) file_get_contents($trendFile))));
    for ($i = count($lines) - 1; $i >= 0; $i--) {
        $decoded = json_decode($lines[$i], true);
        if (isset($decoded['v'], $decoded['watch']) && $decoded['v'] >= 2) {
            $previous = $decoded;
            break;
        }
    }
}
if ($previous !== null) {
    $deltas = [];
    foreach ($watch as $name => [$cbo, $wmc]) {
        if (isset($previous['watch'][$name])) {
            [$pCbo, $pWmc] = $previous['watch'][$name];
            if ($cbo !== $pCbo || $wmc !== $pWmc) {
                $deltas[] = sprintf("  Δ %s  CBO %d → %d · WMC %d → %d", $name, $pCbo, $cbo, $pWmc, $wmc);
            }
        } else {
            $deltas[] = sprintf("  + %s entered the watchlist (CBO=%d, WMC=%d)", $name, $cbo, $wmc);
        }
    }
    foreach (array_diff_key($previous['watch'], $watch) as $name => $m) {
        $deltas[] = sprintf("  − %s left the watchlist", $name);
    }
    echo "\nchanges since {$previous['commit']} ({$previous['ts']}):\n";
    echo $deltas === [] ? "  none — watchlist unchanged\n" : implode("\n", $deltas) . "\n";
}

$snapshot = [
    'v'        => 3,
    'ts'       => date('c'),
    'commit'   => trim((string) shell_exec('git rev-parse --short HEAD')),
    'classes'  => $count,
    'mean_cbo' => $meanCbo,
    'bands'    => $bandCounts,
    'st_mean'  => array_map(
        fn (array $g) => round(array_sum(array_column($g, 'cbo')) / count($g), 1),
        $byStereotype
    ),
    'hotspots' => $hotspots,
    'watch'    => $watch,
];
file_put_contents($trendFile, json_encode($snapshot, JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);

echo "\nsnapshot v3 appended (" . count($watch) . " watchlist · " . count($hotspots) . " hotspots) → engineering/verification/metrics/trend.jsonl\n";
exit(0);
