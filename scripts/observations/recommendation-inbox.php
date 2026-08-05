<?php

declare(strict_types=1);

/**
 * Runner: the developer's decision inbox — the "implement NOW" slice of the
 * EDA-proposal verdict (2026-08-04). Lists open recommendations; with
 * --decide, prompts [a]ccept / [i]gnore / [d]efer / [s]kip per item and
 * appends the same decision+rationale records as recommendation-decide.php.
 *
 * Usage:
 *   php scripts/observations/recommendation-inbox.php            # list only
 *   php scripts/observations/recommendation-inbox.php --decide   # interactive
 *
 * OBS_DIR overrides the observations directory (tests never touch evidence).
 */

require_once __DIR__ . '/RecommendationInbox.php';

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

$recs = $readJsonl($obsDir . '/recommendations.jsonl');
$decisions = $readJsonl($obsDir . '/decisions.jsonl');
$inbox = RecommendationInbox::classify($recs, $decisions);

$ageHours = fn (string $ts): float => (time() - strtotime($ts)) / 3600;

printf("Decision inbox — needs decision: %d · deferred (awaiting re-decision): %d\n\n", count($inbox['needs_decision']), count($inbox['deferred']));
foreach ($inbox['needs_decision'] as $i => $r) {
    printf("%2d. %s  [%s] %s — %s  (age: %.1fh)\n", $i + 1, $r['id'], $r['rule'], $r['subject'], $r['text'], $ageHours($r['ts']));
}
foreach ($inbox['deferred'] as $r) {
    printf(" ~  %s  [%s] %s — deferred\n", $r['id'], $r['rule'], $r['subject']);
}

if (!in_array('--decide', $argv, true)) {
    if ($inbox['needs_decision'] !== []) {
        echo "\nrun with --decide to record decisions interactively\n";
    }
    exit(0);
}

$codes = ['ALREADY_PLANNED', 'IMMEDIATE_VALUE', 'DEADLINE_PRESSURE', 'FALSE_POSITIVE', 'DUPLICATE', 'WAITING_DEPENDENCY', 'OTHER'];
$map = ['a' => 'ACCEPTED', 'i' => 'IGNORED', 'd' => 'DEFERRED'];
$actor = trim((string) shell_exec('git config user.name')) ?: 'unknown';
$commit = trim((string) shell_exec('git rev-parse --short HEAD')) ?: null;
$decisionsFile = $obsDir . '/decisions.jsonl';

foreach ($inbox['needs_decision'] as $r) {
    printf("\n%s  [%s] %s — %s\n[a]ccept / [i]gnore / [d]efer / [s]kip: ", $r['id'], $r['rule'], $r['subject'], $r['text']);
    $choice = strtolower(trim((string) fgets(STDIN)));
    if (!isset($map[$choice])) {
        echo "skipped\n";
        continue;
    }
    printf("reason code (%s): ", implode(' ', $codes));
    $reason = strtoupper(trim((string) fgets(STDIN)));
    if (!in_array($reason, $codes, true)) {
        echo "invalid code — skipped (a skipped prompt records nothing; the recommendation stays open)\n";
        continue;
    }
    echo 'comment' . ($reason === 'OTHER' ? ' (required)' : ' (optional)') . ': ';
    $comment = trim((string) fgets(STDIN));
    if ($reason === 'OTHER' && $comment === '') {
        echo "OTHER requires a comment — skipped\n";
        continue;
    }
    $records = [
        ['type' => 'decision',  'recommendation_id' => $r['id'], 'decision' => $map[$choice], 'ts' => date('c'), 'actor' => $actor, 'commit' => $commit],
        ['type' => 'rationale', 'recommendation_id' => $r['id'], 'reason_code' => $reason, 'comment' => $comment !== '' ? $comment : null],
    ];
    file_put_contents(
        $decisionsFile,
        implode("\n", array_map(fn ($x) => json_encode($x, JSON_UNESCAPED_SLASHES), $records)) . "\n",
        FILE_APPEND
    );
    printf("recorded: %s → %s (%s)\n", $r['id'], $map[$choice], $reason);
}

echo "\ndone — regenerate the dashboard: php scripts/observations/dashboard-renderer.php\n";
exit(0);
