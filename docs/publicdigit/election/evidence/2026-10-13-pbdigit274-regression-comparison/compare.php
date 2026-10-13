<?php
// Compare two PHPUnit JUnit reports per test (key = class::method incl. data set).
// Outcome per test: pass | fail | error | skip. Prints counts per category and lists regressions in full.
[$_, $baseFile, $headFile, $outDir] = $argv;

function outcomes(string $file): array {
    if (str_ends_with($file, '.events.txt')) {
        // PHPUnit --log-events-text: "Test <Outcome> (Class::method[ with data set ...])"; survives a fatal error.
        $rank = ['pass' => 0, 'skip' => 1, 'fail' => 2, 'error' => 3];
        $out = [];
        $current = null;
        foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
            if (preg_match('/^Test (Prepared|Passed|Failed|Errored|Skipped|Marked Incomplete) \((.+)\)\s*$/', $line, $m)
                || preg_match('/^Test (Failed|Errored|Skipped) \((.+?)\)$/', $line, $m)) {
                [$_, $event, $key] = $m;
                $state = match ($event) {
                    'Prepared' => null, 'Passed' => 'pass', 'Failed' => 'fail', 'Errored' => 'error', default => 'skip',
                };
                $current = $key;
                if ($state !== null && $rank[$state] >= $rank[$out[$key]['state'] ?? 'pass']) {
                    $out[$key] = ['state' => $state, 'msg' => '', 'file' => ''];
                } elseif ($state === null && ! isset($out[$key])) {
                    $out[$key] = ['state' => 'pass', 'msg' => '', 'file' => '', 'prepared_only' => true];
                }
                if ($state !== null) {
                    unset($out[$key]['prepared_only']);
                }
            } elseif ($current !== null && isset($out[$current]) && in_array($out[$current]['state'], ['fail', 'error'], true)
                && $out[$current]['msg'] === '' && trim($line) !== '') {
                $out[$current]['msg'] = trim($line);
            }
        }
        // A test that was prepared but never reported an outcome did not finish (e.g. a fatal error).
        foreach ($out as $k => $o) {
            if (! empty($o['prepared_only'])) {
                $out[$k] = ['state' => 'error', 'msg' => 'no outcome recorded (process ended)', 'file' => ''];
            }
        }

        return $out;
    }
    $xml = simplexml_load_file($file);
    $out = [];
    foreach ($xml->xpath('//testcase') as $tc) {
        $key = ((string) $tc['class'] ?: (string) $tc['classname']).'::'.(string) $tc['name'];
        $state = 'pass';
        $msg = '';
        foreach (['error' => 'error', 'failure' => 'fail', 'skipped' => 'skip'] as $tag => $s) {
            if (isset($tc->$tag)) {
                $state = $s;
                $msg = trim(strtok((string) $tc->$tag[0], "\n") ?: (string) ($tc->$tag[0]['message'] ?? ''));
                break;
            }
        }
        $out[$key] = ['state' => $state, 'msg' => $msg, 'file' => (string) $tc['file']];
    }
    return $out;
}

$base = outcomes($baseFile);
$head = outcomes($headFile);
$cats = ['regression' => [], 'still_failing' => [], 'fixed' => [], 'pass_both' => [], 'skip_any' => [], 'only_base' => [], 'only_head' => []];
foreach ($base + $head as $key => $_) {
    $b = $base[$key]['state'] ?? null;
    $h = $head[$key]['state'] ?? null;
    $bad = fn ($s) => in_array($s, ['fail', 'error'], true);
    $cat = match (true) {
        $b === null => 'only_head',
        $h === null => 'only_base',
        $b === 'skip' || $h === 'skip' => 'skip_any',
        $b === 'pass' && $bad($h) => 'regression',
        $bad($b) && $bad($h) => 'still_failing',
        $bad($b) && $h === 'pass' => 'fixed',
        default => 'pass_both',
    };
    $cats[$cat][$key] = ['base' => $b, 'head' => $h, 'msg' => $head[$key]['msg'] ?? $base[$key]['msg'] ?? ''];
}

$summary = sprintf("tests: baseline=%d head=%d\n", count($base), count($head));
foreach ($cats as $cat => $rows) {
    $summary .= sprintf("%-14s %d\n", $cat, count($rows));
}
$state = fn (array $o) => array_count_values(array_column($o, 'state'));
$summary .= 'baseline states: '.json_encode($state($base))."\nhead states:     ".json_encode($state($head))."\n";
file_put_contents("$outDir/summary.txt", $summary);
foreach ($cats as $cat => $rows) {
    $lines = [];
    foreach ($rows as $key => $r) {
        $lines[] = "$key\t{$r['base']}\t{$r['head']}\t".mb_substr($r['msg'], 0, 300);
    }
    sort($lines);
    file_put_contents("$outDir/$cat.tsv", implode("\n", $lines)."\n");
}
echo $summary;
