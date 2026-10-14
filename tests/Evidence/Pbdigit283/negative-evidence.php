<?php

declare(strict_types=1);

/*
 * PBDIGIT-283 R6 negative evidence (G-RUN-NEG). Plan docs/plans/20261014-1347-pbdigit283-test-database-safety-
 * remediation-plan.md §6 R6 (D-283-4, F6, Option A); guide developer_guide/testing/06_negative_evidence.md.
 *
 * Reproduces the 2026-10-13 incident's shape: a Tests\TestCase (RefreshDatabase) run with `--no-configuration` and a
 * non-test database (`publicdigit`) configured, and proves the R3 pre-provider check refuses it before any query.
 * Fail-safe: every target the run can influence is a non-existent socket with a closed port, so a broken guard
 * meets nothing. Steps, each aborting on any doubt:
 *   1. refuse if a configuration cache exists or APP_CONFIG_CACHE is set;
 *   2. build the child environment from scratch (only PATH and HOME are inherited);
 *   3. pre-run check: preflight.php loads environment + configuration only (no providers), and every connection must
 *      be at the dead socket, except the one Option A connection, matched exactly;
 *   4. run the canary, ONLY then, in a separate process with `--no-configuration`;
 *   5. accept only: exactly the canary ran, refused by R3 for `publicdigit`, no connection attempted.
 * Exit codes: 0 evidence, 1 aborted before the canary, 2 the canary run is not the evidence.
 *
 * Run (G-RUN-NEG only): php tests/Evidence/Pbdigit283/negative-evidence.php
 */

use Tests\Support\NegativeEvidence;

$root = dirname(__DIR__, 3);
require $root.'/vendor/autoload.php';

$abort = static function (string $why): never {
    fwrite(STDERR, "PBDIGIT-283 R6 negative: ABORTED before the canary: {$why}".PHP_EOL);
    exit(1);
};

/** @return array{0: int, 1: string, 2: string} exit code, stdout, stderr */
$run = static function (array $command, array $env) use ($root): array {
    $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, $root, $env);
    if (! is_resource($process)) {
        return [-1, '', 'could not start: '.implode(' ', $command)];
    }
    $stdout = (string) stream_get_contents($pipes[1]);
    $stderr = (string) stream_get_contents($pipes[2]);

    return [proc_close($process), $stdout, $stderr];
};

// 1. No configuration cache: it would ignore every variable set below.
if (file_exists($root.'/bootstrap/cache/config.php') || getenv('APP_CONFIG_CACHE') !== false) {
    $abort('a configuration cache exists (bootstrap/cache/config.php) or APP_CONFIG_CACHE is set');
}

// 2. The child environment, from scratch.
$env = NegativeEvidence::childEnvironment((string) getenv('PATH'), (string) getenv('HOME'));

// 3. Pre-run check.
[$code, $stdout, $stderr] = $run([PHP_BINARY, 'tests/Evidence/Pbdigit283/preflight.php'], $env);
if ($code !== 0) {
    $abort("the pre-run check could not load the configuration (exit {$code}): ".trim($stderr));
}
try {
    $report = json_decode($stdout, true, flags: JSON_THROW_ON_ERROR);
} catch (JsonException) {
    $abort('the pre-run check printed no valid report');
}
$problems = is_array($report) ? NegativeEvidence::preflightProblems($report) : ['the report is not an object'];
if ($problems !== []) {
    $abort(implode(' | ', $problems));
}
echo 'PBDIGIT-283 R6 negative: pre-run check OK. Effective connections (no passwords):', PHP_EOL;
foreach ($report['connections'] as $name => $config) {
    printf("  %-12s driver=%s host=%s port=%s database=%s%s\n", $name, $config['driver'] ?? '-', $config['host'] ?? '-',
        $config['port'] ?? '-', $config['database'] ?? '-', $name === NegativeEvidence::OPTION_A_EXCEPTION['name'] ? '  [Option A exception, PBDIGIT-292]' : '');
}
printf("  default=%s env=%s cached=%s db-manager-bound=%s\n", $report['default'], $report['env'],
    var_export($report['cached'], true), var_export($report['bound_db'], true));

// 4. The canary, only now, in a separate process.
$canary = [PHP_BINARY, 'vendor/bin/phpunit', '--no-configuration', '--bootstrap', 'vendor/autoload.php', 'tests/Evidence/Pbdigit283/ResetCanaryTest.php'];
echo PHP_EOL, 'PBDIGIT-283 R6 negative: running ', implode(' ', $canary), PHP_EOL;
[$code, $stdout, $stderr] = $run($canary, $env);
$output = $stdout.$stderr;
echo $output, PHP_EOL, "canary exit code: {$code}", PHP_EOL;

// 5. Only the expected refusal is evidence.
$problems = NegativeEvidence::refusalProblems($code, $output);
if ($problems !== []) {
    fwrite(STDERR, 'PBDIGIT-283 R6 negative: NOT EVIDENCE: '.implode(' | ', $problems).PHP_EOL);
    exit(2);
}
echo 'PBDIGIT-283 R6 negative: EVIDENCE. The R3 check refused the unsafe configuration before any provider; no connection was attempted.', PHP_EOL;
exit(0);
