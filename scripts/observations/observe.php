<?php

declare(strict_types=1);

/**
 * Runner: knowledgeos observe — the JSON API for trigger adapters.
 *
 * Any adapter (IDE extension, editor plugin, CLI) passes changed files and
 * receives structured recommendations. Same runtime, same rules, same
 * determinism as every other trigger — only the rendering differs.
 *
 *   php scripts/observations/observe.php --json app/Models/Election.php [...]
 *   php scripts/observations/observe.php app/Models/Election.php          # human-readable
 *
 * Output (ephemeral by design — nothing is written to the evidence streams;
 * severity is always "advisory": collectors never judge, developers decide):
 *   { trigger, files, latency_ms, advisories, recommendations[], collectors[] }
 */

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/ChangeSet.php';
require_once __DIR__ . '/ObservationRuntime.php';

use Symfony\Component\Yaml\Yaml;

$root = dirname(__DIR__, 2);
chdir($root);

$json = in_array('--json', $argv, true);
$source = 'ide-save';
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--source=')) {
        $source = substr($arg, 9);
    }
}
$files = array_values(array_filter(array_slice($argv, 1), fn ($a) => !str_starts_with($a, '--')));

if ($files === []) {
    fwrite(STDERR, "usage: observe.php [--json] <changed-file> [...]\n");
    exit(2);
}

$files = array_map(fn ($f) => str_replace('\\', '/', $f), $files);
$rules = Yaml::parseFile(__DIR__ . '/recommendation-rules.yaml')['rules'];

$startedAt = microtime(true);
$result = ObservationRuntime::run(new ChangeSet($files, $source, date('c')), $rules);
$latencyMs = (int) round((microtime(true) - $startedAt) * 1000);

if ($json) {
    echo json_encode([
        'trigger'         => $source,
        'files'           => $files,
        'latency_ms'      => $latencyMs,
        'advisories'      => count($result['recommendations']),
        'recommendations' => array_map(fn ($r) => [
            'rule'     => $r['rule'],
            'subject'  => $r['subject'],
            'severity' => 'advisory',
            'text'     => $r['text'],
        ], $result['recommendations']),
        'collectors'      => $result['collectors'],
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n";
    exit(0);
}

printf("observed: %s (%dms)\n", implode(' · ', $files), $latencyMs);
foreach ($result['recommendations'] as $r) {
    printf("  ⚠ [%s] %s\n     → %s\n", $r['rule'], $r['subject'], $r['text']);
}
if ($result['recommendations'] === []) {
    echo "  ✓ no advisories\n";
}
exit(0);
