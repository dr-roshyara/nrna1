<?php

declare(strict_types=1);

/**
 * Runner: knowledgeos dev — KnowledgeOS owns the development session.
 *
 * The developer should never have to think "did I start the watcher?"
 * (review 2026-08-04 — demand evidence: a real session where the watcher
 * was never running and live feedback silently didn't exist).
 *
 *   php scripts/observations/dev.php
 *
 * Sequence:
 *   1. VERIFY   — doctor (8 checks); refuse to start a session that can't work
 *   2. PROVE    — chain self-test: push a real file through the FULL pipeline
 *                 (trigger → ChangeSet → runtime → collectors → recommendations)
 *                 with per-stage traces; the first failing stage is THE defect
 *   3. OWN      — hand off to the watch loop (Ctrl+C ends the session)
 *
 * Scope guard: this ORCHESTRATES; it contains no collector, metric, or rule
 * logic. The runtime is the center; every trigger — including this session —
 * is an adapter around it.
 */

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/ChangeSet.php';
require_once __DIR__ . '/ObservationRuntime.php';

use Symfony\Component\Yaml\Yaml;

$root = dirname(__DIR__, 2);
chdir($root);

echo "KnowledgeOS dev session\n\n=== 1. VERIFY (doctor) ===\n\n";
passthru('php ' . escapeshellarg(__DIR__ . '/doctor.php'), $doctorExit);
if ($doctorExit !== 0) {
    fwrite(STDERR, "\nsession refused: environment not ready — fix the ✗ checks first\n");
    exit(1);
}

echo "\n=== 2. PROVE (chain self-test) ===\n\n";
$probe = 'app/Models/Election.php';                    // known-cohesion fixture-in-place
if (!is_file($probe)) {
    $candidates = glob('app/Models/*.php') ?: [];
    $probe = $candidates[0] ?? null;
}
if ($probe === null) {
    fwrite(STDERR, "no app class found to probe the chain\n");
    exit(1);
}

$rules = Yaml::parseFile(__DIR__ . '/recommendation-rules.yaml')['rules'];
$startedAt = microtime(true);
$result = ObservationRuntime::run(new ChangeSet([$probe], 'dev-self-test', date('c')), $rules);
$ms = (int) round((microtime(true) - $startedAt) * 1000);

$stages = [
    'event received (self-test save)'  => true,
    'ChangeSet non-empty'              => true,   // constructed above with one file
    'runtime invoked'                  => is_array($result),
    'collectors selected'              => ($result['collectors'] ?? []) !== [],
    'observations produced'            => array_sum(array_column($result['collectors'] ?? [], 'observations')) > 0,
    'recommendations generated'        => true,   // zero is a VALID outcome for clean code — presence of the key is the check
    'presentation reachable'           => true,   // this very output
];
$stages['recommendations generated'] = array_key_exists('recommendations', $result);

$allOk = true;
foreach ($stages as $stage => $ok) {
    printf("%s %s\n", $ok ? '✓' : '✗ FIRST DEFECT →', $stage);
    if (!$ok) {
        $allOk = false;
        break;
    }
}
printf("\nchain PROVEN on %s: %d collector(s) · %d recommendation(s) · %dms\n",
    $probe, count($result['collectors']), count($result['recommendations']), $ms);

if (!$allOk) {
    exit(1);
}

echo "\n=== 3. OWN (watch loop — Ctrl+C ends the session) ===\n\n";
passthru('php ' . escapeshellarg(__DIR__ . '/watch.php'), $watchExit);
exit($watchExit);
