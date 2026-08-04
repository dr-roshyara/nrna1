<?php

declare(strict_types=1);

/**
 * Runner: KnowledgeOS doctor — gather real environment facts, diagnose, report.
 *
 * Usage: php scripts/observations/doctor.php
 * Exit:  0 ready · 1 not ready (usable as a CI/onboarding gate later)
 */

require_once __DIR__ . '/KnowledgeOsDoctor.php';

$root = dirname(__DIR__, 2);
chdir($root);

$obsDir = $root . '/engineering/verification/observations';

$streamsOk = true;
foreach (glob($obsDir . '/*.jsonl') ?: [] as $stream) {
    foreach (array_filter(explode("\n", trim((string) file_get_contents($stream)))) as $line) {
        if (json_decode($line, true) === null) {
            $streamsOk = false;
            break 2;
        }
    }
}

$state = [
    'git_present'        => trim((string) shell_exec('git rev-parse --is-inside-work-tree 2>&1')) === 'true',
    'hooks_path'         => trim((string) shell_exec('git config --get core.hooksPath')),
    'husky_post_commit'  => is_file($root . '/.husky/post-commit'),
    'canonical_hook'     => is_file($root . '/scripts/observations/git-hooks/post-commit'),
    'php_present'        => PHP_VERSION !== '',
    'obs_dir_writable'   => is_dir($obsDir) && is_writable($obsDir),
    'collectors_present' => array_map('basename', array_filter([
        $root . '/scripts/observations/test-presence-observer.php',
        $root . '/scripts/observations/lcom4-observer.php',
        $root . '/scripts/observations/recommendation-observer.php',
    ], 'is_file')),
    'streams_parseable'  => $streamsOk,
];

$report = KnowledgeOsDoctor::diagnose($state);

echo "KnowledgeOS Doctor\n\n";
foreach ($report['checks'] as $c) {
    printf("%s %s — %s\n", $c['ok'] ? '✓' : '✗', $c['name'], $c['detail']);
}
printf("\n%s\n", $report['ready'] ? 'KnowledgeOS READY' : 'KnowledgeOS NOT READY — fix the ✗ checks above');

if (!in_array('--live', $argv, true)) {
    exit($report['ready'] ? 0 : 1);
}

// ---- --live: verify the complete live developer experience ----
require_once $root . '/vendor/autoload.php';
require_once __DIR__ . '/ChangeSet.php';
require_once __DIR__ . '/ObservationRuntime.php';

$tasksFile = $root . '/.vscode/tasks.json';
$tasks = is_file($tasksFile) ? (json_decode((string) file_get_contents($tasksFile), true) ?: []) : [];
$taskCmds = implode(' ', array_column($tasks['tasks'] ?? [], 'command'));

$extDirs = glob((getenv('USERPROFILE') ?: getenv('HOME')) . '/.vscode/extensions/*knowledgeos*') ?: [];

$chainRecs = null;
try {
    $rules = Symfony\Component\Yaml\Yaml::parseFile(__DIR__ . '/recommendation-rules.yaml')['rules'];
    $probe = is_file($root . '/app/Models/Election.php') ? 'app/Models/Election.php' : (glob('app/Models/*.php')[0] ?? null);
    if ($probe !== null) {
        $chainRecs = count(ObservationRuntime::run(new ChangeSet([$probe], 'doctor-live', date('c')), $rules)['recommendations']);
    }
} catch (Throwable) {
}

$live = KnowledgeOsDoctor::diagnoseLive([
    'task_installed'      => is_file($tasksFile),
    'task_runs_dev'       => str_contains($taskCmds, 'dev.php'),
    'extension_source'    => is_file(__DIR__ . '/vscode-knowledgeos/extension.js'),
    'extension_installed' => $extDirs !== [],
    'chain_proven'        => $chainRecs !== null,
    'chain_recs'          => (int) $chainRecs,
]);

echo "\nLive developer experience\n\n";
foreach ($live['checks'] as $c) {
    printf("%s %s — %s\n", $c['ok'] ? '✓' : '✗', $c['name'], $c['detail']);
}
printf("\n%s\n", $live['ready']
    ? 'Live developer experience READY'
    : 'Live experience NOT fully ready — architectural pieces in place; the ✗ items are the operational gap');
exit($report['ready'] && $live['ready'] ? 0 : 1);
