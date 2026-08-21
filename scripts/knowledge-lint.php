<?php

declare(strict_types=1);

/**
 * Engineering Knowledge Platform (EKP) — knowledge-lint.
 *
 * "PHPStan for knowledge." Validates every governed document under docs/knowledge/
 * against the data-driven schema in docs/knowledge/schema/*.yaml.
 *
 * Usage:
 *   php scripts/knowledge-lint.php            # report; exit 0 (warn-only)
 *   php scripts/knowledge-lint.php --strict   # exit 1 if any ERROR
 *   php scripts/knowledge-lint.php --json     # machine-readable output
 *
 *   # Track-2 structural profile (Phase 0 — S1–S5 over a directory, D-1)
 *   php scripts/knowledge-lint.php --profile=structural --root=<dir> [--vocabulary=<path>]
 *   php scripts/knowledge-lint.php --profile=structural --root=<dir> --strict
 *
 * Rules (severity from knowledge-schema.yaml): frontmatter present/parses,
 * required fields, knowledge_id pattern + uniqueness, enum validity,
 * relationship key validity, relationship target existence, link resolution,
 * single-authoritative-per-topic, traceability completeness, orphan detection,
 * circular dependencies, boundary consistency, review overdue, code_refs exist.
 *
 * Structural profile (the ADAPTER over the S1–S5 capability-library services):
 *   S1 document-local identifier uniqueness + ordering (CAP-001 / DP-1)
 *   S2 intra-document §/step reference resolution  (CAP-004 / DP-4)
 *   S3 declared vocabulary + confusable identifiers (CAP-003 / DP-3)
 *   S4 table column-count consistency
 *   S5 unlabelled-superseded disposition → WARN
 *   ⛔ Warn-only (D-3): exits 0 regardless of verdict unless --strict is passed.
 *   ⛔ D-4: the report ends with the NOT-CHECKED statement.
 */

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application\ValidateDocumentLocalIntegrity;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure\MarkdownDocumentContentsReader;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application\ValidateIntraDocumentReferences;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application\ValidateTableColumnCount;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Infrastructure\MarkdownIntraDocumentReader;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Infrastructure\MarkdownTableReader;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\ValidateCompetingCurrentDefinitions;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\ValidateVocabularyIntegrity;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure\MarkdownDispositionReader;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure\MarkdownVocabularyReader;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure\YamlVocabularySource;
use EngineeringKnowledge\Shared\Domain\Verdict;
use EngineeringKnowledge\Shared\Infrastructure\StructuralCliReporter;
use Symfony\Component\Yaml\Yaml;

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

$args = $argv;
$strict = in_array('--strict', $args, true);
$asJson = in_array('--json', $args, true);

$profile = null;
$rootDir = null;
$vocabularyPath = null;
foreach (array_slice($argv, 1) as $a) {
    if (preg_match('/^--profile=(.+)$/', $a, $m)) {
        $profile = $m[1];
    } elseif (preg_match('/^--root=(.+)$/', $a, $m)) {
        $rootDir = $m[1];
    } elseif (preg_match('/^--vocabulary=(.+)$/', $a, $m)) {
        $vocabularyPath = $m[1];
    }
}

// ── Track-2 structural profile (S7, plan D-1) ──────────────────────────────
if ($profile !== null) {
    if ($profile !== 'structural') {
        fwrite(STDERR, "knowledge-lint: unknown --profile '{$profile}'. Known profiles: structural.\n");
        exit(3);
    }
    if ($rootDir === null) {
        fwrite(STDERR, "knowledge-lint: --profile=structural requires --root=<dir>.\n");
        exit(3);
    }

    exit(run_structural_profile($rootDir, $vocabularyPath, $strict, $root));
}

/**
 * S7 — the S1–S5 structural checks over a directory of markdown documents.
 *
 * ADAPTER only (D-1): every verdict comes from the capability-library application
 * services; this function scans, invokes, and reports. It owns no rule.
 *
 * Warn-only (D-3): returns 0 regardless of verdict unless --strict is passed, and
 * --strict is wired into no gate, hook, or CI. Fail-closed (D-2): S3 without a
 * readable vocabulary config is INCONCLUSIVE, never PASS.
 */
function run_structural_profile(string $rootDir, ?string $vocabularyPath, bool $strict, string $repoRoot): int
{
    $services = [
        'S1' => new ValidateDocumentLocalIntegrity(new MarkdownDocumentContentsReader()),
        'S2' => new ValidateIntraDocumentReferences(new MarkdownIntraDocumentReader()),
        'S3' => new ValidateVocabularyIntegrity(
            new YamlVocabularySource($vocabularyPath ?: $repoRoot . '/docs/knowledge/schema/vocabulary-integrity.yaml'),
            new MarkdownVocabularyReader(),
        ),
        'S4' => new ValidateTableColumnCount(new MarkdownTableReader()),
        'S5' => new ValidateCompetingCurrentDefinitions(new MarkdownDispositionReader()),
    ];

    if (! is_dir($rootDir)) {
        fwrite(STDERR, "knowledge-lint: --root '{$rootDir}' is not a directory.\n");

        return 3;
    }

    $files = [];
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootDir, FilesystemIterator::SKIP_DOTS));
    foreach ($rii as $f) {
        if ($f->getExtension() === 'md') {
            $files[] = $f->getPathname();
        }
    }
    sort($files);

    $reporter = new StructuralCliReporter();
    $rows = [];
    $counts = [];
    foreach ($files as $file) {
        $rel = str_replace($repoRoot . '/', '', $file);
        foreach ($services as $slice => $service) {
            $assessment = $service->handle($file);
            $rows[] = ['slice' => $slice, 'file' => $rel, 'assessment' => $assessment];
            $counts[$slice][$assessment->verdict()->value] = ($counts[$slice][$assessment->verdict()->value] ?? 0) + 1;
        }
    }

    echo "Engineering Knowledge Platform — knowledge-lint — structural profile\n";
    echo 'Root: ' . $rootDir . ' · ' . count($files) . ' markdown document(s) · '
        . (5 * count($files)) . " assessments (S1–S5)\n\n";

    foreach ($rows as $row) {
        if ($row['assessment']->isClean()) {
            continue;
        }
        echo $reporter->sliceLine($row['slice'], $row['file'], $row['assessment']) . "\n";
    }

    echo "\nSummary by slice:\n";
    foreach ($counts as $slice => $c) {
        echo sprintf(
            "  %s  PASS %d · FAIL %d · WARN %d · INCONCLUSIVE %d\n",
            $slice,
            $c[Verdict::PASS->value] ?? 0,
            $c[Verdict::FAIL->value] ?? 0,
            $c[Verdict::WARN->value] ?? 0,
            $c[Verdict::INCONCLUSIVE->value] ?? 0,
        );
    }

    echo "\n" . $reporter->notCheckedStatement() . "\n";

    if ($strict) {
        foreach ($rows as $row) {
            if (in_array($row['assessment']->verdict(), [Verdict::FAIL, Verdict::INCONCLUSIVE], true)) {
                return 1;
            }
        }
    }

    return 0;
}

$schemaDir = $root . '/docs/knowledge/schema';
$knowledgeDir = $root . '/docs/knowledge';

function loadYaml(string $path): array
{
    return Yaml::parseFile($path) ?? [];
}

// ---- load vocabularies ------------------------------------------------------
$schema       = loadYaml($schemaDir . '/knowledge-schema.yaml');
$types        = array_keys(loadYaml($schemaDir . '/knowledge-types.yaml')['types'] ?? []);
$statuses     = array_keys(loadYaml($schemaDir . '/statuses.yaml')['statuses'] ?? []);
$contexts     = array_keys(loadYaml($schemaDir . '/bounded-contexts.yaml')['contexts'] ?? []);
$authorities  = array_keys(loadYaml($schemaDir . '/authorities.yaml')['authorities'] ?? []);
$audiences    = array_keys(loadYaml($schemaDir . '/knowledge-audiences.yaml')['audiences'] ?? []);
$relSchema    = loadYaml($schemaDir . '/knowledge-relationships.yaml');
$relKeys      = array_keys($relSchema['relationships'] ?? []);
$shortcutKeys = array_keys($relSchema['shortcuts'] ?? []);
$relationshipFields = array_merge($relKeys, $shortcutKeys);

$typesMeta = loadYaml($schemaDir . '/knowledge-types.yaml')['types'] ?? [];
$crossOk   = $schema['cross_context_relationships'] ?? ['related_to', 'implements', 'derived_from'];
$required  = ['knowledge_id', 'title', 'knowledge_type', 'bounded_context', 'status', 'authority', 'owner'];
$idPattern = '/^[A-Z][A-Z0-9-]*-[A-Za-z0-9-]+$/';

// ---- collect files ----------------------------------------------------------
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($knowledgeDir, FilesystemIterator::SKIP_DOTS));
$mdFiles = [];
foreach ($rii as $f) {
    if ($f->getExtension() !== 'md') {
        continue;
    }
    $rel = str_replace('\\', '/', substr($f->getPathname(), strlen($root) + 1));
    if (str_contains($rel, 'docs/knowledge/archive/')) {
        continue; // archive excluded
    }
    if (str_ends_with($rel, 'README.scaffold.md') || str_ends_with($rel, '.template.md')) {
        continue; // templates are not governed documents
    }
    $mdFiles[] = $f->getPathname();
}
sort($mdFiles);

$errors = [];
$warnings = [];
$add = function (string $sev, string $file, string $rule, string $msg) use ($root, &$errors, &$warnings) {
    $rel = str_replace('\\', '/', substr($file, strlen($root) + 1));
    $entry = ['file' => $rel, 'rule' => $rule, 'msg' => $msg];
    if ($sev === 'error') {
        $errors[] = $entry;
    } else {
        $warnings[] = $entry;
    }
};

// ---- parse frontmatter ------------------------------------------------------
/** @var array<string,array> $docs  keyed by absolute path */
$docs = [];
$idIndex = [];   // knowledge_id => path
foreach ($mdFiles as $path) {
    $raw = file_get_contents($path);
    if (!preg_match('/^---\R(.*?)\R---\R/s', $raw, $m)) {
        $add('error', $path, 'frontmatter_present', 'No YAML frontmatter block at top of file.');
        continue;
    }
    try {
        $fm = Yaml::parse($m[1]) ?: [];
    } catch (\Throwable $e) {
        $add('error', $path, 'frontmatter_parses', 'Frontmatter YAML failed to parse: ' . $e->getMessage());
        continue;
    }
    $docs[$path] = $fm;
    $id = $fm['knowledge_id'] ?? null;
    if (is_string($id) && $id !== '') {
        if (isset($idIndex[$id])) {
            $add('error', $path, 'knowledge_id_unique', "Duplicate knowledge_id '$id' (also in {$idIndex[$id]}).");
        } else {
            $idIndex[$id] = str_replace('\\', '/', substr($path, strlen($root) + 1));
        }
    }
}

// also register knowledge_ids declared in portal/packages/*.yaml (they are valid targets)
foreach (glob($knowledgeDir . '/portal/packages/*.yaml') as $pkg) {
    $y = loadYaml($pkg);
    if (!empty($y['knowledge_id'])) {
        $idIndex[$y['knowledge_id']] = str_replace('\\', '/', substr($pkg, strlen($root) + 1));
    }
    foreach (array_merge($y['includes'] ?? [], $y['includes_optional'] ?? []) as $inc) {
        if (!isset($idIndex[$inc]) && !in_array($inc, array_keys($idIndex), true)) {
            // defer: checked after full id index built (see package check below)
        }
    }
}

$knownIds = array_keys($idIndex);
$today = date('Y-m-d');

// ---- per-document rules -----------------------------------------------------
$graph = [];        // id => ['requires'=>[], 'depends_on'=>[], 'all'=>[]]
$incoming = [];     // id => count of inbound references
$topicAuth = [];    // "context|topic" => [ids]

foreach ($docs as $path => $fm) {
    $id = $fm['knowledge_id'] ?? '(missing)';

    // required fields
    foreach ($required as $field) {
        if (!isset($fm[$field]) || $fm[$field] === '' || $fm[$field] === null) {
            $add('error', $path, 'required_fields_present', "Missing required field '$field'.");
        }
    }
    // id pattern
    if (isset($fm['knowledge_id']) && !preg_match($idPattern, (string) $fm['knowledge_id'])) {
        $add('error', $path, 'knowledge_id_pattern', "knowledge_id '{$fm['knowledge_id']}' does not match PREFIX-ID pattern.");
    }
    // enums
    if (isset($fm['knowledge_type']) && !in_array($fm['knowledge_type'], $types, true)) {
        $add('error', $path, 'enum_values_valid', "knowledge_type '{$fm['knowledge_type']}' not in knowledge-types.yaml.");
    }
    if (isset($fm['status']) && !in_array($fm['status'], $statuses, true)) {
        $add('error', $path, 'enum_values_valid', "status '{$fm['status']}' not in statuses.yaml.");
    }
    if (isset($fm['bounded_context']) && !in_array($fm['bounded_context'], $contexts, true)) {
        $add('error', $path, 'enum_values_valid', "bounded_context '{$fm['bounded_context']}' not in bounded-contexts.yaml.");
    }
    if (isset($fm['authority']) && !in_array($fm['authority'], $authorities, true)) {
        $add('error', $path, 'enum_values_valid', "authority '{$fm['authority']}' not in authorities.yaml.");
    }
    foreach ((array) ($fm['audience'] ?? []) as $aud) {
        if (!in_array($aud, $audiences, true)) {
            $add('error', $path, 'enum_values_valid', "audience '$aud' not in knowledge-audiences.yaml.");
        }
    }
    // recommended
    foreach (['audience', 'tags', 'version'] as $rec) {
        if (empty($fm[$rec])) {
            $add('warning', $path, 'recommended_fields_present', "Recommended field '$rec' is empty.");
        }
    }
    // relationships
    $outAll = [];
    foreach ($relationshipFields as $rf) {
        if (!array_key_exists($rf, $fm)) {
            continue;
        }
        foreach ((array) $fm[$rf] as $target) {
            $outAll[] = $target;
            if (!in_array($target, $knownIds, true)) {
                $add('error', $path, 'relationship_targets_exist', "$rf -> '$target' is not a known knowledge_id.");
                continue;
            }
            $incoming[$target] = ($incoming[$target] ?? 0) + 1;
            // boundary consistency
            $targetPath = $idIndex[$target];
            $targetFm = null;
            foreach ($docs as $dp => $dfm) {
                if (($dfm['knowledge_id'] ?? null) === $target) { $targetFm = $dfm; break; }
            }
            if ($targetFm) {
                $a = $fm['bounded_context'] ?? null;
                $b = $targetFm['bounded_context'] ?? null;
                if ($a && $b && $a !== $b && $a !== 'global' && $b !== 'global' && !in_array($rf, $crossOk, true)) {
                    $add('warning', $path, 'boundary_consistency', "Cross-context '$rf' from $a to $b ($target) is not an allowed cross-context relationship.");
                }
            }
        }
    }
    if (is_string($id)) {
        $graph[$id] = [
            'requires' => array_merge((array) ($fm['requires'] ?? []), (array) ($fm['depends_on'] ?? [])),
            'out' => $outAll,
        ];
    }
    // single authoritative per topic (only when explicit `topic` present)
    if (($fm['authority'] ?? null) === 'authoritative' && !empty($fm['topic'])) {
        $key = ($fm['bounded_context'] ?? '?') . '|' . $fm['topic'];
        $topicAuth[$key][] = $id;
    }
    // review overdue
    if (!empty($fm['next_review']) && (string) $fm['next_review'] < $today) {
        $add('warning', $path, 'review_overdue', "next_review {$fm['next_review']} is in the past.");
    }
    // traceability completeness — only a gate for settled docs, not drafts/discovery
    $reqTypes = $typesMeta[$fm['knowledge_type'] ?? '']['traceability_requires'] ?? [];
    if (in_array($fm['status'] ?? '', ['draft', 'discovery'], true)) {
        $reqTypes = [];
    }
    if ($reqTypes) {
        $linkedTypes = [];
        foreach ($outAll as $t) {
            foreach ($docs as $dfm) {
                if (($dfm['knowledge_id'] ?? null) === $t) { $linkedTypes[] = $dfm['knowledge_type'] ?? null; }
            }
        }
        foreach ($reqTypes as $needed) {
            if (!in_array($needed, $linkedTypes, true)) {
                $add('warning', $path, 'traceability_complete', "Type '{$fm['knowledge_type']}' should link to a '$needed' doc (none found).");
            }
        }
    }
    // code_refs / test_refs existence
    foreach (['code_refs', 'test_refs'] as $cr) {
        foreach ((array) ($fm[$cr] ?? []) as $p) {
            if (!file_exists($root . '/' . $p)) {
                $add('warning', $path, 'code_refs_exist', "$cr path '$p' does not exist in the repo.");
            }
        }
    }
    // markdown link resolution
    $raw = file_get_contents($path);
    if (preg_match_all('/\[[^\]]*\]\(([^)]+)\)/', $raw, $lm)) {
        foreach ($lm[1] as $link) {
            $link = trim($link);
            if (preg_match('~^(https?:|mailto:|#)~', $link)) {
                continue;
            }
            $target = preg_replace('/#.*$/', '', $link); // strip anchor
            if ($target === '') {
                continue;
            }
            $resolved = realpath(dirname($path) . '/' . $target);
            if ($resolved === false) {
                $add('error', $path, 'links_resolve', "Broken link: $link");
            }
        }
    }
}

// single authoritative
foreach ($topicAuth as $key => $ids) {
    if (count($ids) > 1) {
        [$ctx, $topic] = explode('|', $key, 2);
        $add('error', $idIndex[$ids[0]] ? $root . '/' . $idIndex[$ids[0]] : $knowledgeDir, 'single_authoritative',
            "Multiple authoritative docs for topic '$topic' in context '$ctx': " . implode(', ', $ids));
    }
}

// orphan detection (no inbound, no outbound) — skip the portal index itself
foreach ($docs as $path => $fm) {
    $id = $fm['knowledge_id'] ?? null;
    if (!$id || $id === 'PORTAL-INDEX') {
        continue;
    }
    $hasOut = !empty($graph[$id]['out']);
    $hasIn = !empty($incoming[$id]);
    if (!$hasOut && !$hasIn) {
        $add('warning', $path, 'orphan_document', "Orphan: '$id' links to nothing and nothing links to it.");
    }
}

// circular dependency over requires/depends_on
$visiting = [];
$visited = [];
$cycleFound = [];
$dfs = function (string $node, array $stack) use (&$dfs, &$graph, &$visited, &$cycleFound) {
    if (in_array($node, $stack, true)) {
        $cycleFound[] = implode(' -> ', array_merge($stack, [$node]));
        return;
    }
    if (isset($visited[$node])) {
        return;
    }
    $visited[$node] = true;
    foreach ($graph[$node]['requires'] ?? [] as $next) {
        if (isset($graph[$next])) {
            $dfs($next, array_merge($stack, [$node]));
        }
    }
};
foreach (array_keys($graph) as $node) {
    $dfs($node, []);
}
foreach (array_unique($cycleFound) as $cyc) {
    $add('warning', $knowledgeDir, 'circular_dependency', "Cycle: $cyc");
}

// package include resolution
foreach (glob($knowledgeDir . '/portal/packages/*.yaml') as $pkg) {
    $y = loadYaml($pkg);
    foreach (array_merge($y['includes'] ?? [], $y['includes_optional'] ?? []) as $inc) {
        if (!in_array($inc, $knownIds, true)) {
            $add('error', $pkg, 'relationship_targets_exist', "package include '$inc' is not a known knowledge_id.");
        }
    }
}

// ---- report -----------------------------------------------------------------
if ($asJson) {
    echo json_encode(['errors' => $errors, 'warnings' => $warnings, 'files' => count($docs)], JSON_PRETTY_PRINT), "\n";
} else {
    echo "Engineering Knowledge Platform — knowledge-lint\n";
    echo "Scanned " . count($docs) . " governed documents.\n\n";
    foreach (['error' => $errors, 'warning' => $warnings] as $sev => $list) {
        if (!$list) {
            continue;
        }
        $icon = $sev === 'error' ? '❌ ERROR' : '⚠️  WARN';
        echo strtoupper($sev) . "S (" . count($list) . "):\n";
        foreach ($list as $e) {
            echo "  $icon  [{$e['rule']}] {$e['file']}\n          {$e['msg']}\n";
        }
        echo "\n";
    }
    if (!$errors && !$warnings) {
        echo "✅ All documents pass.\n";
    } else {
        echo "Summary: " . count($errors) . " error(s), " . count($warnings) . " warning(s).\n";
    }
}

exit($strict && $errors ? 1 : 0);
