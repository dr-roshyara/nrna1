<?php

declare(strict_types=1);

/**
 * Runner: observe a git range and emit a test-presence observation.
 *
 * Usage:
 *   php scripts/observations/test-presence-observer.php [git-range]
 *   (default range: HEAD~1..HEAD)
 *
 * Advisory only — prints the observation and appends one line to the
 * append-only observation file. Never exits non-zero on findings.
 * OBS_DIR overrides the output directory so tests never pollute evidence.
 */

require_once __DIR__ . '/TestPresenceCollector.php';

$range = $argv[1] ?? 'HEAD~1..HEAD';

exec(sprintf('git diff --name-only %s', escapeshellarg($range)), $files, $code);
if ($code !== 0) {
    fwrite(STDERR, "git diff failed for range {$range}\n");
    exit(2);
}

$obs = TestPresenceCollector::classify($files);

echo "\n== Test-presence observation (advisory — never blocks) ==\n";
printf("range: %s · production files changed: %d · test files changed: %d\n", $range, $obs['production_changed'], $obs['tests_changed']);
if ($obs['production_without_tests']) {
    echo "⚠ observation: production behavior changed without accompanying test changes:\n";
    foreach ($obs['production_files'] as $f) {
        echo "    {$f}\n";
    }
} elseif ($obs['production_changed'] > 0) {
    echo "✓ observation: production changes accompanied by test changes\n";
} else {
    echo "· observation: no production behavior changed in this range\n";
}

$dir = getenv('OBS_DIR') ?: __DIR__ . '/../../engineering/verification/observations';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
$snapshot = [
    'ts'     => date('c'),
    'range'  => $range,
    'commit' => trim((string) shell_exec('git rev-parse --short HEAD')),
] + $obs;
file_put_contents($dir . '/test-presence.jsonl', json_encode($snapshot, JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);

echo "observation appended → engineering/verification/observations/test-presence.jsonl\n";
exit(0);
