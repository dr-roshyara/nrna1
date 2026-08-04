<?php

declare(strict_types=1);

/**
 * Runner: gather observation-stream data and regenerate the Evidence Dashboard.
 *
 * Usage: php scripts/observations/dashboard-renderer.php
 * Output: engineering/verification/observations/DASHBOARD.md (overwritten — it is a projection)
 * OBS_DIR overrides the observations directory (tests never touch evidence).
 */

require_once __DIR__ . '/EvidenceDashboardRenderer.php';

$obsDir = getenv('OBS_DIR') ?: __DIR__ . '/../../engineering/verification/observations';
$metricsTrend = __DIR__ . '/../../engineering/verification/metrics/trend.jsonl';

$lastLine = function (string $file): ?array {
    if (!is_file($file)) {
        return null;
    }
    $lines = array_filter(explode("\n", trim((string) file_get_contents($file))));
    return $lines === [] ? null : (json_decode(end($lines), true) ?: null);
};
$countLines = function (string $file): int {
    if (!is_file($file)) {
        return 0;
    }
    return count(array_filter(explode("\n", trim((string) file_get_contents($file)))));
};

$data = ['oe_entries' => 0];

if (($snap = $lastLine($metricsTrend)) !== null) {
    $data['metrics'] = [
        'snapshots' => $countLines($metricsTrend),
        'mean_cbo'  => $snap['mean_cbo'] ?? '—',
        'warn'      => $snap['bands']['WARN'] ?? '—',
        'hotspots'  => isset($snap['hotspots']) ? count($snap['hotspots']) : '—',
        'commit'    => $snap['commit'] ?? '—',
    ];
}

$tpFile = $obsDir . '/test-presence.jsonl';
if (is_file($tpFile)) {
    $without = 0;
    foreach (array_filter(explode("\n", trim((string) file_get_contents($tpFile)))) as $line) {
        $o = json_decode($line, true);
        if (($o['production_without_tests'] ?? false) === true) {
            $without++;
        }
    }
    $data['test_presence'] = ['observations' => $countLines($tpFile), 'production_without_tests' => $without];
}

$lcFile = $obsDir . '/lcom4.jsonl';
if (($lcSnap = $lastLine($lcFile)) !== null) {
    $worst = [];
    foreach (array_slice($lcSnap['observations'] ?? [], 0, 3) as $o) {
        $worst[$o['class']] = $o['value'];
    }
    $data['lcom4'] = ['runs' => $countLines($lcFile), 'worst' => $worst];
}

// Recommendations + decisions
$recFile = $obsDir . '/recommendations.jsonl';
$decFile = $obsDir . '/decisions.jsonl';
if (is_file($recFile)) {
    $issued = $countLines($recFile);
    $counts = ['ACCEPTED' => 0, 'IGNORED' => 0, 'DEFERRED' => 0];
    if (is_file($decFile)) {
        foreach (array_filter(explode("\n", trim((string) file_get_contents($decFile)))) as $line) {
            $r = json_decode($line, true);
            if (($r['type'] ?? '') === 'decision' && isset($counts[$r['decision']])) {
                $counts[$r['decision']]++;
            }
        }
    }
    $data['recommendations'] = [
        'issued'   => $issued,
        'decided'  => array_sum($counts),
        'accepted' => $counts['ACCEPTED'],
        'ignored'  => $counts['IGNORED'],
        'deferred' => $counts['DEFERRED'],
    ];
}

// OE entries: count "## OE-KOS-" headings in the register.
$register = __DIR__ . '/../../docs/knowledgeos/KnowledgeOS_Operational_Evidence_Register.md';
if (is_file($register)) {
    $data['oe_entries'] = preg_match_all('/^## OE-KOS-\d+/m', (string) file_get_contents($register));
}

$md = EvidenceDashboardRenderer::render($data);
$target = $obsDir . '/DASHBOARD.md';
if (!is_dir(dirname($target))) {
    mkdir(dirname($target), 0777, true);
}
file_put_contents($target, $md);
echo "dashboard regenerated → engineering/verification/observations/DASHBOARD.md\n";
exit(0);
