<?php

declare(strict_types=1);

/**
 * Runner: assess the latest outcome for a recommendation.
 *
 * Usage: php scripts/observations/assess.php <REC-id>
 * Appends one assessment record to observations/assessments.jsonl.
 * OBS_DIR overrides the observations directory for tests.
 */

require_once __DIR__ . '/AssessmentService.php';

$recId = $argv[1] ?? null;
if ($recId === null) {
    fwrite(STDERR, "usage: php scripts/observations/assess.php <REC-id>\n");
    exit(2);
}

$obsDir = getenv('OBS_DIR') ?: __DIR__ . '/../../engineering/verification/observations';
$outcome = null;
if (is_file($obsDir . '/outcomes.jsonl')) {
    foreach (array_filter(explode("\n", trim((string) file_get_contents($obsDir . '/outcomes.jsonl')))) as $line) {
        $o = json_decode($line, true);
        if (($o['recommendation_id'] ?? '') === $recId) {
            $outcome = $o; // latest wins
        }
    }
}
if ($outcome === null) {
    fwrite(STDERR, "no outcome recorded for {$recId} — assessment interprets outcomes; record one first\n");
    exit(2);
}

$assessment = AssessmentService::evaluate($outcome);
file_put_contents($obsDir . '/assessments.jsonl', json_encode($assessment, JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);

printf(
    "assessment: %s %s → %s (%s)\n→ observations/assessments.jsonl\n",
    $recId,
    $assessment['subject'],
    $assessment['verdict'],
    $assessment['basis']
);
exit(0);
