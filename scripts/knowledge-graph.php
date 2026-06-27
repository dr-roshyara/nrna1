<?php

declare(strict_types=1);

/**
 * Engineering Knowledge Platform (EKP) — knowledge-graph generator.
 *
 * Reads the typed relationships in every governed document's frontmatter and
 * emits a semantic knowledge graph (Mermaid) under docs/knowledge/portal/graph/.
 * Edges are LABELLED by relationship type, so the graph shows not just THAT two
 * documents connect but WHY (implements / documents / supersedes / ...).
 *
 * Usage: php scripts/knowledge-graph.php
 */

use Symfony\Component\Yaml\Yaml;

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

$knowledgeDir = $root . '/docs/knowledge';
$schemaDir = $knowledgeDir . '/schema';

$rel = Yaml::parseFile($schemaDir . '/knowledge-relationships.yaml');
$relFields = array_merge(array_keys($rel['relationships'] ?? []), array_keys($rel['shortcuts'] ?? []));

// collect docs
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($knowledgeDir, FilesystemIterator::SKIP_DOTS));
$nodes = [];   // id => ['type'=>, 'context'=>]
$edges = [];   // [from, rel, to]
foreach ($rii as $f) {
    if ($f->getExtension() !== 'md') {
        continue;
    }
    $rel2 = str_replace('\\', '/', substr($f->getPathname(), strlen($root) + 1));
    if (str_contains($rel2, '/archive/') || str_ends_with($rel2, '.template.md') || str_ends_with($rel2, 'README.scaffold.md')) {
        continue;
    }
    $raw = file_get_contents($f->getPathname());
    if (!preg_match('/^---\R(.*?)\R---\R/s', $raw, $m)) {
        continue;
    }
    try {
        $fm = Yaml::parse($m[1]) ?: [];
    } catch (\Throwable) {
        continue;
    }
    $id = $fm['knowledge_id'] ?? null;
    if (!$id) {
        continue;
    }
    $nodes[$id] = ['type' => $fm['knowledge_type'] ?? '?', 'context' => $fm['bounded_context'] ?? 'global'];
    foreach ($relFields as $rf) {
        foreach ((array) ($fm[$rf] ?? []) as $t) {
            $edges[] = [$id, $rf, $t];
        }
    }
}
// also register package ids as nodes
foreach (glob($knowledgeDir . '/portal/packages/*.yaml') as $pkg) {
    $y = Yaml::parseFile($pkg) ?: [];
    if (!empty($y['knowledge_id'])) {
        $nodes[$y['knowledge_id']] = ['type' => 'package', 'context' => 'global'];
        foreach (array_merge($y['includes'] ?? [], $y['includes_optional'] ?? []) as $inc) {
            $edges[] = [$y['knowledge_id'], 'includes', $inc];
        }
    }
}

ksort($nodes);
$sanitize = fn (string $id): string => preg_replace('/[^A-Za-z0-9]/', '_', $id);

// group nodes by context for subgraphs
$byContext = [];
foreach ($nodes as $id => $meta) {
    $byContext[$meta['context']][$id] = $meta;
}
ksort($byContext);

$lines = ['graph LR'];
foreach ($byContext as $ctx => $ns) {
    $lines[] = '  subgraph ' . $sanitize($ctx) . '["' . $ctx . '"]';
    foreach ($ns as $id => $meta) {
        $lines[] = '    ' . $sanitize($id) . '["' . $id . '<br/><small>' . $meta['type'] . '</small>"]';
    }
    $lines[] = '  end';
}
foreach ($edges as [$from, $r, $to]) {
    if (!isset($nodes[$from]) || !isset($nodes[$to])) {
        continue; // skip dangling (knowledge-lint reports these)
    }
    $lines[] = '  ' . $sanitize($from) . ' -->|' . $r . '| ' . $sanitize($to);
}
$mermaid = implode("\n", $lines);

$generated = date('Y-m-d');
$graphDir = $knowledgeDir . '/portal/graph';
if (!is_dir($graphDir)) {
    mkdir($graphDir, 0777, true);
}

$validEdges = array_filter($edges, fn ($e) => isset($nodes[$e[0]]) && isset($nodes[$e[2]]));
$nodeCount = count($nodes);
$edgeCount = count($validEdges);

file_put_contents($graphDir . '/knowledge-graph.md', <<<MD
---
knowledge_id: GRAPH-FULL
title: Knowledge Graph (generated)
knowledge_type: portal
bounded_context: global
status: approved
authority: generated
audience: [architect, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [graph, generated, traceability]
related_to: [PORTAL-INDEX]
---

# Knowledge Graph

> **Generated** by `npm run knowledge-graph` on {$generated}. Do not hand-edit.
> Nodes = governed documents (grouped by bounded context); edges = typed relationships.

```mermaid
{$mermaid}
```

*Nodes:* {$nodeCount} · *edges:* {$edgeCount}.
MD);

file_put_contents($graphDir . '/README.md', <<<MD
---
knowledge_id: GRAPH-README
title: Knowledge Graph — index
knowledge_type: portal
bounded_context: global
status: approved
authority: derived
audience: [architect, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [graph, index]
related_to: [PORTAL-INDEX]
---

# Knowledge Graph

Auto-generated views of the typed relationships across the knowledge base.

- [Full knowledge graph](knowledge-graph.md)

Regenerate with `npm run knowledge-graph` (or `php scripts/knowledge-graph.php`).
MD);

echo "knowledge-graph: " . count($nodes) . " nodes, " . count($validEdges) . " edges -> docs/knowledge/portal/graph/\n";
