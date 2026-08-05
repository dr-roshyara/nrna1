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
$readJsonl = function (string $file): array {
    if (!is_file($file)) {
        return [];
    }
    return array_values(array_filter(array_map(
        fn ($l) => json_decode($l, true),
        array_filter(explode("\n", trim((string) file_get_contents($file))))
    )));
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

// Funnel (unique recommendations per stage) + per-rule effectiveness
$recs = $readJsonl($obsDir . '/recommendations.jsonl');
if ($recs !== []) {
    $ruleOf = [];
    foreach ($recs as $r) {
        $ruleOf[$r['id']] = $r['rule'];
    }
    $decidedBy = [];   // rec-id => latest decision
    foreach ($readJsonl($obsDir . '/decisions.jsonl') as $dRec) {
        if (($dRec['type'] ?? '') === 'decision') {
            $decidedBy[$dRec['recommendation_id']] = $dRec['decision'];
        }
    }
    $outcomeIds = array_unique(array_column($readJsonl($obsDir . '/outcomes.jsonl'), 'recommendation_id'));
    $assessBy = [];    // rec-id => latest verdict
    foreach ($readJsonl($obsDir . '/assessments.jsonl') as $a) {
        $assessBy[$a['recommendation_id']] = $a['verdict'];
    }
    $data['funnel'] = [
        'issued'      => count($recs),
        'decided'     => count($decidedBy),
        'outcomes'    => count($outcomeIds),
        'assessments' => count($assessBy),
    ];

    // Stage lead times from EXISTING timestamps (earliest per stage per rec)
    $firstTs = function (array $rows, string $idKey = 'recommendation_id'): array {
        $m = [];
        foreach ($rows as $r) {
            $id = $r[$idKey] ?? null;
            if ($id !== null && isset($r['ts']) && (!isset($m[$id]) || $r['ts'] < $m[$id])) {
                $m[$id] = $r['ts'];
            }
        }
        return $m;
    };
    $issuedTs = [];
    foreach ($recs as $r) {
        $issuedTs[$r['id']] = $r['ts'];
    }
    $decTs = $firstTs(array_filter($readJsonl($obsDir . '/decisions.jsonl'), fn ($r) => ($r['type'] ?? '') === 'decision'));
    $outTs = $firstTs($readJsonl($obsDir . '/outcomes.jsonl'));
    $assTs = $firstTs($readJsonl($obsDir . '/assessments.jsonl'));
    $pairHours = function (array $from, array $to): ?array {
        $ds = [];
        foreach ($to as $id => $ts) {
            if (isset($from[$id])) {
                $ds[] = (strtotime($ts) - strtotime($from[$id])) / 3600;
            }
        }
        return $ds === [] ? null : ['mean_hours' => array_sum($ds) / count($ds), 'n' => count($ds)];
    };
    $leadTimes = array_filter([
        'recommendation_to_decision' => $pairHours($issuedTs, $decTs),
        'decision_to_outcome'        => $pairHours($decTs, $outTs),
        'outcome_to_assessment'      => $pairHours($outTs, $assTs),
    ]);
    if ($leadTimes !== []) {
        $data['lead_times'] = $leadTimes;
    }
    // Loop Completion: stage-gap buckets per recommendation (latest decision classifies it;
    // IGNORED ends the lifecycle at the decision — never counted as a missing outcome).
    $loop = ['needs_decision' => 0, 'needs_outcome' => 0, 'needs_assessment' => 0,
             'complete' => 0, 'closed_ignored' => 0, 'deferred' => 0];
    $outcomeSet = array_flip($outcomeIds);
    foreach (array_keys($ruleOf) as $id) {
        $decision = $decidedBy[$id] ?? null;
        $hasOutcome = isset($outcomeSet[$id]);
        $hasAssessment = isset($assessBy[$id]);
        if ($decision === null) {
            $loop['needs_decision']++;
        } elseif ($decision === 'IGNORED') {
            $loop['closed_ignored']++;
        } elseif ($decision === 'DEFERRED') {
            $loop['deferred']++;
        } elseif (!$hasOutcome) {
            $loop['needs_outcome']++;
        } elseif (!$hasAssessment) {
            $loop['needs_assessment']++;
        } else {
            $loop['complete']++;
        }
    }
    $data['loop_completion'] = $loop;

    // Evidence Velocity: completed cycles (rec present in all four streams) over the observed window.
    $completed = 0;
    foreach (array_keys($issuedTs) as $id) {
        if (isset($decTs[$id], $outTs[$id], $assTs[$id])) {
            $completed++;
        }
    }
    $allTs = array_merge(array_values($issuedTs), array_values($decTs), array_values($outTs), array_values($assTs));
    if ($allTs !== []) {
        $windowDays = (strtotime(max($allTs)) - strtotime(min($allTs))) / 86400;
        $data['evidence_velocity'] = [
            'completed_cycles' => $completed,
            'window_days'      => $windowDays,
            'per_week'         => $windowDays >= 7 ? $completed / ($windowDays / 7) : null,
        ];
    }

    $eff = [];
    foreach ($ruleOf as $id => $rule) {
        $eff[$rule]['issued'] = ($eff[$rule]['issued'] ?? 0) + 1;
        if (isset($decidedBy[$id])) {
            $key = strtolower($decidedBy[$id]);      // accepted/ignored/deferred
            $eff[$rule][$key] = ($eff[$rule][$key] ?? 0) + 1;
        }
        if (isset($assessBy[$id])) {
            $eff[$rule][$assessBy[$id]] = ($eff[$rule][$assessBy[$id]] ?? 0) + 1;
        }
    }
    ksort($eff);
    $data['effectiveness'] = $eff;
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
