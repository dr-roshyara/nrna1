<?php

declare(strict_types=1);

/*
 * C-1 acceptance-correction sensitivity kit (PBDIGIT-268, plan docs/plans/20261010-1144-…, CP-1).
 *
 * For each mutant: copy app/ and tests/ into a disposable scratch directory, run the named test there
 * unpatched (control: must pass), apply the mutant patch THERE, run it again (must fail = KILLED).
 * A bootstrap maps App\ and Tests\ to the scratch copy ahead of Composer, so the mutated copy is the code
 * under test; the canary M0 proves it. The working tree is never modified.
 *
 * Usage (repository root): php docs/publicdigit/membership/architecture/models/c1_sensitivity/run.php [M0 M1 …]
 * Exit code 0 only if every control passes, every mutant is killed and app/ tests/ are unchanged.
 */

$root = trim((string) shell_exec('git rev-parse --show-toplevel'));
$kit = $root . '/docs/publicdigit/membership/architecture/models/c1_sensitivity';
chdir($root);

$mutants = [];
foreach (file($kit . '/mutants.tsv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $row) {
    if (str_starts_with($row, '#')) {
        continue;
    }
    [$id, $testFile, $filter, $intent] = explode("\t", $row);
    $mutants[$id] = compact('testFile', 'filter', 'intent');
}
$selected = array_slice($argv, 1) ?: array_keys($mutants);

$status = static fn (): string => (string) shell_exec('git status --porcelain -- app tests');
$before = $status();
$work = getenv('C1_SENSITIVITY_SCRATCH') ?: sys_get_temp_dir() . '/c1-sensitivity-' . getmypid();
$ok = true;

$bootstrap = static fn (string $dir): string => <<<PHP
<?php
require '{$root}/vendor/autoload.php';
spl_autoload_register(static function (string \$class): void {
    foreach (['App\\\\' => '{$dir}/app/', 'Tests\\\\' => '{$dir}/tests/'] as \$prefix => \$base) {
        if (str_starts_with(\$class, \$prefix)) {
            \$file = \$base . str_replace('\\\\', '/', substr(\$class, strlen(\$prefix))) . '.php';
            if (is_file(\$file)) {
                require \$file;
            }
            return;
        }
    }
}, true, true);
PHP;

$run = static function (string $dir, string $testFile, string $filter) use ($root): array {
    $cmd = sprintf('%s --no-configuration --bootstrap %s %s --filter %s 2>&1',
        escapeshellarg($root . '/vendor/bin/phpunit'), escapeshellarg($dir . '/bootstrap.php'),
        escapeshellarg($dir . '/' . $testFile), escapeshellarg('/' . $filter . '$/'));
    exec($cmd, $out, $code);
    $summary = '';
    foreach ($out as $line) {
        if (preg_match('/^(OK \(|Tests: |No tests executed)/', $line)) {
            $summary = $line;
        }
    }

    return [$code, $summary];
};

foreach ($selected as $id) {
    if (!isset($mutants[$id])) {
        echo "$id: UNKNOWN\n";
        $ok = false;
        continue;
    }
    ['testFile' => $testFile, 'filter' => $filter, 'intent' => $intent] = $mutants[$id];
    $dir = "$work/$id";
    exec('rm -rf ' . escapeshellarg($dir));
    mkdir($dir, 0777, true);
    exec(sprintf('cp -r app tests %s', escapeshellarg($dir)));
    file_put_contents("$dir/bootstrap.php", $bootstrap($dir));

    [$code, $summary] = $run($dir, $testFile, $filter);
    if ($code !== 0 || !str_starts_with($summary, 'OK (')) {
        echo "$id: CONTROL FAILED ($filter): $summary\n";
        $ok = false;
        continue;
    }
    exec(sprintf('patch -s -p1 -d %s < %s 2>&1', escapeshellarg($dir), escapeshellarg("$kit/$id.patch")), $pout, $pcode);
    if ($pcode !== 0) {
        echo "$id: PATCH DID NOT APPLY\n";
        $ok = false;
        continue;
    }
    [$code, $summary] = $run($dir, $testFile, $filter);
    if ($code === 0) {
        echo "$id: SURVIVED  $filter  [$intent]\n";
        $ok = false;
    } else {
        echo "$id: KILLED    $filter  [$intent]  control OK, mutant: $summary\n";
    }
}

if (!getenv('C1_SENSITIVITY_SCRATCH')) {
    exec('rm -rf ' . escapeshellarg($work));
}
if ($status() === $before) {
    echo "working tree app/ tests/: unchanged\n";
} else {
    echo "WORKING TREE CHANGED\n";
    $ok = false;
}
exit($ok ? 0 : 1);
