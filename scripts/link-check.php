<?php

declare(strict_types=1);

/**
 * link-check — classify broken documentation links by EVIDENCE and confidence.
 *
 * Behaviour only. Documented migrations are configuration:
 *   docs/knowledge/schema/repository-migrations.yaml
 *
 * Confidence model — only >= 99 may be applied:
 *   100  git rename record | documented migration | exact existing target
 *    99  exactly one file in the repository carries that basename
 *   <99  several candidates (ambiguous) | no candidate (missing)
 *
 * Usage:
 *   php scripts/link-check.php                 # report
 *   php scripts/link-check.php --json=out.json # machine-readable
 *   php scripts/link-check.php --apply         # applies confidence >= 99 only
 *
 *   # Track-2 --anchors mode (Phase 0, S7 D-1): S2 intra-document references
 *   php scripts/link-check.php --anchors=<dir> # warn-only, exit 0
 *
 * The --anchors mode is an ADAPTER over CAP-004's ValidateIntraDocumentReferences:
 * it resolves §-references and step-references WITHIN each document under <dir>.
 * ⛔ Warn-only (D-3): exits 0 regardless of verdict. ⛔ D-4: report ends with the
 * NOT-CHECKED statement.
 */

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application\ValidateIntraDocumentReferences;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Infrastructure\MarkdownIntraDocumentReader;
use EngineeringKnowledge\Shared\Infrastructure\StructuralCliReporter;
use Symfony\Component\Yaml\Yaml;

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';
chdir($root);

$args = [];
foreach (array_slice($argv, 1) as $a) {
    if (preg_match('/^--([a-z0-9-]+)(?:=(.*))?$/i', $a, $m)) {
        $args[strtolower($m[1])] = $m[2] ?? true;
    }
}
$apply = isset($args['apply']);

// ── Track-2 --anchors mode (S7, plan D-1): intra-document references over a directory ──
if (isset($args['anchors'])) {
    $anchorsDir = $args['anchors'];
    if (! is_string($anchorsDir) || $anchorsDir === '') {
        fwrite(STDERR, "link-check: --anchors needs a directory, e.g. --anchors=docs/knowledgeos/architecture\n");
        exit(3);
    }
    exit(run_anchors_profile($anchorsDir, $root));
}

/**
 * S7 — the S2 intra-document reference check over a directory of markdown documents.
 *
 * ADAPTER only (D-1): every verdict comes from CAP-004's application service;
 * this function scans, invokes, and reports. It owns no rule. Warn-only (D-3).
 */
function run_anchors_profile(string $dir, string $repoRoot): int
{
    $service = new ValidateIntraDocumentReferences(new MarkdownIntraDocumentReader());
    $reporter = new StructuralCliReporter();

    if (! is_dir($dir)) {
        fwrite(STDERR, "link-check: --anchors '{$dir}' is not a directory.\n");

        return 3;
    }

    $files = [];
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($rii as $f) {
        if ($f->getExtension() === 'md') {
            $files[] = $f->getPathname();
        }
    }
    sort($files);

    $rows = [];
    echo "link-check — anchors (S2 intra-document reference resolution, Phase 0)\n";
    echo 'Root: ' . $dir . ' · ' . count($files) . " markdown document(s)\n\n";

    foreach ($files as $file) {
        $rel = str_replace($repoRoot . '/', '', $file);
        $assessment = $service->handle($file);
        $rows[] = ['file' => $rel, 'assessment' => $assessment];
    }

    foreach ($rows as $row) {
        if ($row['assessment']->isClean()) {
            continue;
        }
        echo $reporter->sliceLine('S2', $row['file'], $row['assessment']) . "\n";
    }

    echo "\n" . $reporter->notCheckedStatement() . "\n";

    // D-3: warn-only — exit 0 always in Phase 0.
    return 0;
}

$migrations = (Yaml::parseFile('docs/knowledge/schema/repository-migrations.yaml')
    ?? [])['repository_migrations'] ?? [];

// ---- evidence: git rename records -------------------------------------------
$renames = [];
exec('git log --diff-filter=R --name-status --format=%H -M -n 200 2>&1', $lines);
foreach ($lines as $line) {
    if ($line !== '' && $line[0] === 'R') {
        $p = explode("\t", $line);
        if (count($p) === 3) {
            $old = trim($p[1], '"');
            if (!isset($renames[$old])) {
                $renames[$old] = trim($p[2], '"');
            }
        }
    }
}

// ---- evidence: basename index -----------------------------------------------
$skipDirs = ['.git', 'node_modules', 'vendor', 'storage', 'bootstrap', 'public', '.worktrees'];
$index = [];
$it = new RecursiveIteratorIterator(
    new RecursiveCallbackFilterIterator(
        new RecursiveDirectoryIterator('.', FilesystemIterator::SKIP_DOTS),
        static function ($f) use ($skipDirs) {
            return !($f->isDir() && in_array($f->getFilename(), $skipDirs, true));
        }
    )
);
foreach ($it as $f) {
    if ($f->isFile()) {
        $p = str_replace('\\', '/', $f->getPathname());
        $p = preg_replace('#^\./#', '', $p);
        $index[$f->getFilename()][] = $p;
    }
}

/** Resolve a broken reference to (target, evidence, confidence). */
function classify(string $resolved, string $body, array $renames, array $migrations, array $index): array
{
    // 100 — git says exactly where this file went
    if (isset($renames[$resolved]) && file_exists($renames[$resolved])) {
        return [$renames[$resolved], 'git-rename', 100];
    }

    // 100 — a documented migration, always existence-verified
    foreach ($migrations as $m) {
        $from = (string) ($m['from'] ?? '');
        $to   = (string) ($m['to'] ?? '');
        if (($m['kind'] ?? '') === 'directory' && str_starts_with($resolved, $from . '/')) {
            $cand = $to . substr($resolved, strlen($from));
            if (file_exists($cand)) {
                return [$cand, 'documented-migration:' . $m['id'], 100];
            }
        }
        if (($m['kind'] ?? '') === 'root-normalization' && !str_contains($resolved, '/')) {
            $cand = $to . '/' . $resolved;
            if (is_file($cand)) {
                $others = array_values(array_filter($index[basename($resolved)] ?? [],
                    static fn ($p) => $p !== $cand));
                if ($others === []) {
                    return [$cand, 'documented-migration:' . $m['id'], 100];
                }
            }
        }
    }

    // 100 — the link was written repo-root-relative and that exact path exists
    $rootRel = ltrim(rawurldecode($body), './');
    if ($rootRel !== '' && file_exists($rootRel)) {
        return [$rootRel, 'exact-existing-target', 100];
    }

    // 99 / <99 — basename candidates
    $base = basename(rawurldecode($body));
    $cands = array_values(array_unique($index[$base] ?? []));
    if (count($cands) === 1) {
        return [$cands[0], 'unique-basename', 99];
    }
    if (count($cands) > 1) {
        return [null, 'ambiguous', 75];
    }
    return [null, 'missing', 0];
}

// ---- scan --------------------------------------------------------------------
$rows = [];
foreach ($index as $base => $paths) {
    if (!str_ends_with($base, '.md')) {
        continue;
    }
    foreach ($paths as $src) {
        if (str_starts_with($src, '.worktrees/')) {
            continue;
        }
        $txt = @file_get_contents($src);
        if ($txt === false) {
            continue;
        }
        if (!preg_match_all('/\[([^\]]*)\]\(([^)\s]+?)(?:\s+"[^"]*")?\)/', $txt, $ms, PREG_SET_ORDER)) {
            continue;
        }
        foreach ($ms as $m) {
            $target = $m[2];
            if (preg_match('#^(https?:|mailto:|tel:|data:|ftp:|\#)#', $target)
                || str_starts_with($target, '/') || str_contains($target, '$')
                || str_contains($target, 'OneDrive')) {
                continue;
            }
            [$body] = array_pad(explode('#', $target, 2), 2, '');
            $frag = str_contains($target, '#') ? '#' . explode('#', $target, 2)[1] : '';
            if ($body === '') {
                continue;
            }
            $dir = dirname($src) === '.' ? '' : dirname($src);
            $abs = $dir === '' ? rawurldecode($body) : $dir . '/' . rawurldecode($body);
            $resolved = [];
            foreach (explode('/', $abs) as $seg) {
                if ($seg === '.' || $seg === '') { continue; }
                if ($seg === '..') { array_pop($resolved); continue; }
                $resolved[] = $seg;
            }
            $resolvedPath = implode('/', $resolved);
            if (file_exists($resolvedPath)) {
                continue;
            }
            [$winner, $evidence, $conf] = classify($resolvedPath, $body, $renames, $migrations, $index);
            $rows[] = ['src' => $src, 'target' => $target, 'frag' => $frag,
                       'winner' => $winner, 'evidence' => $evidence, 'confidence' => $conf];
        }
    }
}

// ---- report ------------------------------------------------------------------
$byEvidence = [];
foreach ($rows as $r) {
    $key = explode(':', $r['evidence'])[0] === 'documented-migration' ? $r['evidence'] : $r['evidence'];
    $byEvidence[$key] = ($byEvidence[$key] ?? 0) + 1;
}
ksort($byEvidence);

echo "link-check — broken documentation references by evidence\n\n";
printf("  %-42s %6s %6s\n", 'EVIDENCE SOURCE', 'COUNT', 'CONF');
foreach ($byEvidence as $ev => $n) {
    $conf = match (true) {
        $ev === 'ambiguous' => '75',
        $ev === 'missing'   => '0',
        $ev === 'unique-basename' => '99',
        default => '100',
    };
    printf("  %-42s %6d %6s\n", $ev, $n, $conf);
}
$deterministic = array_values(array_filter($rows, static fn ($r) => $r['confidence'] >= 99));
$ambiguous     = array_values(array_filter($rows, static fn ($r) => $r['evidence'] === 'ambiguous'));
$missing       = array_values(array_filter($rows, static fn ($r) => $r['evidence'] === 'missing'));

echo "\n  total broken: " . count($rows)
    . "  |  deterministic (>=99): " . count($deterministic)
    . "  |  ambiguous: " . count($ambiguous)
    . "  |  missing: " . count($missing) . "\n";

if (isset($args['json']) && is_string($args['json'])) {
    file_put_contents($args['json'], json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    echo "  json: {$args['json']}\n";
}

if ($apply) {
    $edits = [];
    foreach ($deterministic as $r) {
        $dir = dirname($r['src']) === '.' ? '' : dirname($r['src']);
        $new = $dir === '' ? $r['winner'] : rtrim(implode('/', array_map(
            static fn () => '..', array_filter(explode('/', $dir)))), '/') . '/' . $r['winner'];
        $edits[$r['src']][] = [$r['target'], str_replace(' ', '%20', $new) . $r['frag']];
    }
    foreach ($edits as $src => $pairs) {
        $txt = file_get_contents($src);
        foreach ($pairs as [$old, $new]) {
            $txt = str_replace('](' . $old . ')', '](' . $new . ')', $txt);
        }
        file_put_contents($src, $txt);
    }
    echo "\n  APPLIED " . count($deterministic) . " deterministic repairs across " . count($edits) . " files.\n";
    echo "  Ambiguous and missing references were NOT touched.\n";
} else {
    echo "\n  (report only — nothing changed; --apply repairs confidence >= 99 only)\n";
}
