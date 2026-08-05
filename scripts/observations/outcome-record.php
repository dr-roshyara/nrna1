<?php

declare(strict_types=1);

/**
 * Runner: record the outcome for a decided recommendation.
 *
 * Usage: php scripts/observations/outcome-record.php <REC-id>
 *
 * v1 supports R1 (LCOM4) recommendations: finds the subject's file from the
 * lcom4 observations, re-collects the CURRENT value, takes the BASELINE from
 * the latest observation at-or-before issuance, counts commits since the
 * decision, and appends one raw outcome record. Refuses when premature.
 * OBS_DIR overrides the observations directory for tests.
 */

require_once __DIR__ . '/OutcomeRecorder.php';
require_once __DIR__ . '/Lcom4Collector.php';

$recId = $argv[1] ?? null;
if ($recId === null) {
    fwrite(STDERR, "usage: php scripts/observations/outcome-record.php <REC-id>\n");
    exit(2);
}

$obsDir = getenv('OBS_DIR') ?: __DIR__ . '/../../engineering/verification/observations';
$readJsonl = function (string $file): array {
    if (!is_file($file)) {
        return [];
    }
    return array_values(array_filter(array_map(
        fn ($l) => json_decode($l, true),
        array_filter(explode("\n", trim((string) file_get_contents($file))))
    )));
};

// 1. the recommendation
$rec = null;
foreach ($readJsonl($obsDir . '/recommendations.jsonl') as $r) {
    if ($r['id'] === $recId) {
        $rec = $r;
        break;
    }
}
if ($rec === null) {
    fwrite(STDERR, "unknown recommendation: {$recId}\n");
    exit(2);
}
if ($rec['rule'] !== 'R1') {
    fwrite(STDERR, "outcome recording v1 supports R1 (LCOM4) recommendations only — {$recId} is {$rec['rule']} (honest limitation, extend on evidence of need)\n");
    exit(2);
}

// 2. the latest decision for it
$decision = null;
foreach ($readJsonl($obsDir . '/decisions.jsonl') as $d) {
    if (($d['type'] ?? '') === 'decision' && $d['recommendation_id'] === $recId) {
        $decision = $d; // last wins for lookup purposes (RD-7 semantics still undecided)
    }
}
if ($decision === null) {
    fwrite(STDERR, "no decision recorded for {$recId} — outcomes join recommendations to DECISIONS; decide first\n");
    exit(2);
}

// 3. subject file + baseline from lcom4 observations
$subject = $rec['subject'];
$file = null;
$baseline = null;
foreach ($readJsonl($obsDir . '/lcom4.jsonl') as $run) {
    if (($run['ts'] ?? '') > $rec['ts']) {
        continue; // baseline = state at-or-before issuance
    }
    foreach ($run['observations'] ?? [] as $o) {
        if ($o['class'] === $subject) {
            $baseline = $o['value'];
            $file = $o['file'] ?? null;
        }
    }
}
if ($baseline === null || $file === null) {
    fwrite(STDERR, "no baseline LCOM4 observation found for {$subject} at-or-before issuance\n");
    exit(2);
}

// 4. current value — re-collect now
$current = null;
foreach (Lcom4Collector::collect((string) file_get_contents($file)) as $o) {
    if ($o['class'] === $subject) {
        $current = $o['value'];
    }
}
if ($current === null) {
    fwrite(STDERR, "class {$subject} no longer found in {$file} — subject moved or renamed; record manually\n");
    exit(2);
}

// 5. commits since the decision commit (falls back to decision ts when no commit field)
$commitsSince = 0;
if (!empty($decision['commit'])) {
    $commitsSince = (int) trim((string) shell_exec(sprintf('git rev-list --count %s..HEAD', escapeshellarg($decision['commit']))));
} else {
    $commitsSince = (int) trim((string) shell_exec(sprintf('git rev-list --count --since=%s HEAD', escapeshellarg($decision['ts']))));
}

// 6. record (the recorder refuses premature recording itself)
try {
    $outcome = OutcomeRecorder::record(
        $recId,
        sprintf('%s@%s', $decision['decision'], $decision['ts']),
        'LCOM4',
        $subject,
        $baseline,
        $current,
        $commitsSince
    );
} catch (\InvalidArgumentException $e) {
    echo "not recorded: {$e->getMessage()}\n";
    exit(0); // refusing is a correct, non-error outcome
}

file_put_contents($obsDir . '/outcomes.jsonl', json_encode($outcome, JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);
printf(
    "outcome recorded: %s %s %s → %s (Δ%+d after %d commits) → observations/outcomes.jsonl\n",
    $recId,
    $subject,
    (string) $baseline,
    (string) $current,
    $outcome['delta'],
    $commitsSince
);
echo "assessment (did this support the recommendation?) is a separate stage — not this tool's question\n";
exit(0);
