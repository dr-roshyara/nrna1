<?php

declare(strict_types=1);

/**
 * Runner: live developer feedback — the FileSaveTrigger's instrumentation.
 *
 * Polls app/ for saved .php files (default every 2s), builds a ChangeSet,
 * invokes the Observation Runtime, and prints advisory feedback in the
 * terminal within seconds of a save. No commit required.
 *
 *   php scripts/observations/watch.php            # watch loop (Ctrl+C to stop)
 *   php scripts/observations/watch.php --once     # one poll cycle (scriptable)
 *
 * Publication decision: live results are EPHEMERAL (displayed, never written
 * to the evidence streams) — a save is not yet an engineering event worth
 * recording; the CommitTrigger pipeline remains the stream writer. Identical
 * code produces identical recommendations on both triggers (pinned by test).
 * Advisory, deterministic, non-blocking — never blocks typing.
 */

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/ChangeSet.php';
require_once __DIR__ . '/FileSaveTrigger.php';
require_once __DIR__ . '/ObservationRuntime.php';

use Symfony\Component\Yaml\Yaml;

$root = dirname(__DIR__, 2);
chdir($root);
$rules = Yaml::parseFile(__DIR__ . '/recommendation-rules.yaml')['rules'];
$once = in_array('--once', $argv, true);
$intervalSeconds = 2;

$snapshot = function () use ($root): array {
    $mtimes = [];
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root . '/app', FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        if ($file->getExtension() === 'php') {
            $rel = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
            $mtimes[$rel] = $file->getMTime();
        }
    }
    return $mtimes;
};

echo "KnowledgeOS watch — live advisory feedback on save (app/**/*.php, poll {$intervalSeconds}s)\n";
echo "advisory · deterministic · non-blocking · ephemeral (streams are written by the commit trigger)\n\n";

$previous = $snapshot();

do {
    if (!$once) {
        sleep($intervalSeconds);
    }
    $current = $snapshot();
    $changed = FileSaveTrigger::detect($previous, $current);
    $previous = $current;

    if ($changed === []) {
        continue;
    }

    $startedAt = microtime(true);
    $result = ObservationRuntime::run(FileSaveTrigger::changeSet($changed, date('c')), $rules);
    $latencyMs = (int) round((microtime(true) - $startedAt) * 1000);

    // Usage measurement (NOT recommendation records — content stays ephemeral):
    // latency + counts only. This is the evidence source the Real-Time
    // ObservationTrigger register row names as its activation criterion.
    file_put_contents(
        $root . '/engineering/verification/observations/watch-usage.jsonl',
        json_encode([
            'ts'              => date('c'),
            'trigger'         => 'file-save',
            'files'           => count($changed),
            'latency_ms'      => $latencyMs,
            'poll_interval_s' => $intervalSeconds,
            'advisories'      => count($result['recommendations']),
            'collectors'      => $result['collectors'] ?? [],
        ], JSON_UNESCAPED_SLASHES) . "\n",
        FILE_APPEND
    );

    printf("[%s] saved: %s  (runtime: %dms · worst-case with poll: %ds + %dms)\n",
        date('H:i:s'), implode(' · ', $changed), $latencyMs, $intervalSeconds, $latencyMs);
    if ($result['recommendations'] === []) {
        echo "  ✓ no advisories\n\n";
        continue;
    }
    foreach ($result['recommendations'] as $r) {
        printf("  ⚠ [%s] %s\n     → %s\n", $r['rule'], $r['subject'], $r['text']);
    }
    echo "\n";
} while (!$once);

exit(0);
