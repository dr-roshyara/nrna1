<?php

declare(strict_types=1);

/**
 * Decision capture: record a developer's DECISION and RATIONALE for a
 * recommendation — two separate records per the approved contract chain
 * (Decision = the response · Rationale = the why).
 *
 * Usage:
 *   php scripts/observations/recommendation-decide.php <REC-id> accepted|ignored|deferred <REASON_CODE> [comment]
 *
 * Reason codes: ALREADY_PLANNED · IMMEDIATE_VALUE · DEADLINE_PRESSURE ·
 *               FALSE_POSITIVE · DUPLICATE · WAITING_DEPENDENCY · OTHER (comment required)
 */

$id = $argv[1] ?? null;
$decision = strtoupper($argv[2] ?? '');
$reason = strtoupper($argv[3] ?? '');
$comment = $argv[4] ?? null;

$codes = ['ALREADY_PLANNED', 'IMMEDIATE_VALUE', 'DEADLINE_PRESSURE', 'FALSE_POSITIVE', 'DUPLICATE', 'WAITING_DEPENDENCY', 'OTHER'];

if ($id === null || !in_array($decision, ['ACCEPTED', 'IGNORED', 'DEFERRED'], true) || !in_array($reason, $codes, true)) {
    fwrite(STDERR, "usage: recommendation-decide.php <REC-id> accepted|ignored|deferred <reason_code> [comment]\n  codes: " . implode(' ', $codes) . "\n");
    exit(2);
}
if ($reason === 'OTHER' && ($comment === null || trim($comment) === '')) {
    fwrite(STDERR, "OTHER requires a comment.\n");
    exit(2);
}

$obsDir = getenv('OBS_DIR') ?: __DIR__ . '/../../engineering/verification/observations';
$decisionsFile = $obsDir . '/decisions.jsonl';

$actor = trim((string) shell_exec('git config user.name')) ?: 'unknown';
$ts = date('c');

// commit_id: where the codebase stood when the decision was made — the
// traceability link Outcome Recording joins on (review 2026-08-04).
$commit = trim((string) shell_exec('git rev-parse --short HEAD')) ?: null;

$records = [
    ['type' => 'decision',  'recommendation_id' => $id, 'decision' => $decision, 'ts' => $ts, 'actor' => $actor, 'commit' => $commit],
    ['type' => 'rationale', 'recommendation_id' => $id, 'reason_code' => $reason, 'comment' => $comment],
];
if (!is_dir($obsDir)) {
    mkdir($obsDir, 0777, true);
}
file_put_contents(
    $decisionsFile,
    implode("\n", array_map(fn ($r) => json_encode($r, JSON_UNESCAPED_SLASHES), $records)) . "\n",
    FILE_APPEND
);

printf("recorded: %s → %s (%s%s) by %s\n", $id, $decision, $reason, $comment ? ': ' . $comment : '', $actor);
exit(0);
