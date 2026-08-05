<?php

declare(strict_types=1);

/**
 * Runner: emit LCOM4 observations for one PHP file or a directory.
 *
 * Usage:
 *   php scripts/observations/lcom4-observer.php <file-or-dir> [maxFiles=200]
 *
 * Advisory only — prints observations and appends them to the append-only
 * observation file. Never exits non-zero on findings.
 * OBS_DIR overrides the output directory so tests never pollute evidence.
 */

require_once __DIR__ . '/Lcom4Collector.php';

$target = $argv[1] ?? null;
$maxFiles = (int) ($argv[2] ?? 200);

if ($target === null || (!is_file($target) && !is_dir($target))) {
    fwrite(STDERR, "usage: php scripts/observations/lcom4-observer.php <file-or-dir> [maxFiles]\n");
    exit(2);
}

$files = [];
if (is_file($target)) {
    $files = [$target];
} else {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $f) {
        if ($f->getExtension() === 'php' && !str_ends_with($f->getFilename(), '.blade.php')) {
            $files[] = $f->getPathname();
            if (count($files) >= $maxFiles) {
                break;
            }
        }
    }
}

$all = [];
foreach ($files as $file) {
    try {
        foreach (Lcom4Collector::collect((string) file_get_contents($file)) as $obs) {
            $obs['file'] = str_replace('\\', '/', $file);
            $all[] = $obs;
        }
    } catch (\Throwable $e) {
        fwrite(STDERR, "parse skipped: {$file} ({$e->getMessage()})\n");
    }
}

usort($all, fn (array $a, array $b) => $b['value'] <=> $a['value']);

echo "\n== LCOM4 observations (advisory — never blocks) ==\n";
printf("files analysed: %d · classes observed: %d\n", count($files), count($all));
foreach (array_slice($all, 0, 10) as $obs) {
    printf("  LCOM4=%-3d %s — %s\n", $obs['value'], $obs['class'], $obs['interpretation']);
}

$dir = getenv('OBS_DIR') ?: __DIR__ . '/../../engineering/verification/observations';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
$snapshot = [
    'ts'           => date('c'),
    'commit'       => trim((string) shell_exec('git rev-parse --short HEAD')),
    'collector'    => 'lcom4',
    'target'       => str_replace('\\', '/', $target),
    'observations' => $all,
];
file_put_contents($dir . '/lcom4.jsonl', json_encode($snapshot, JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);

echo 'observations appended → engineering/verification/observations/lcom4.jsonl' . "\n";
exit(0);
