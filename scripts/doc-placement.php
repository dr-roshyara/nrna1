<?php

declare(strict_types=1);

/**
 * doc-placement — resolve a documentation location from an artifact's classification.
 *
 * Behaviour only. Policy lives in
 *   docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md
 * Configuration lives in
 *   docs/knowledge/schema/documentation-placement.yaml
 *
 * Usage:
 *   php scripts/doc-placement.php --scope=<product-specific|cross-product|session-state>
 *                                 [--maturity=<research|qualified|adopted>] [--domain=<id>]
 *   php scripts/doc-placement.php --list | --self-test | --verify
 *
 * Exit: 0 resolved | 1 usage/unknown | 2 PENDING (unruled) | 3 self-test/verify failed.
 */

use Symfony\Component\Yaml\Yaml;

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

$registryPath = $root . '/docs/knowledge/schema/documentation-placement.yaml';

if (!is_file($registryPath)) {
    fwrite(STDERR, "doc-placement: registry not found at docs/knowledge/schema/documentation-placement.yaml\n");
    exit(1);
}

$registry = Yaml::parseFile($registryPath) ?? [];
$domains  = $registry['domains'] ?? [];
$rules    = $registry['rules'] ?? [];

/** Parse --key=value arguments. */
function args(array $argv): array
{
    $out = [];
    foreach (array_slice($argv, 1) as $arg) {
        if (preg_match('/^--([a-z0-9-]+)(?:=(.*))?$/i', $arg, $m)) {
            $out[strtolower($m[1])] = $m[2] ?? true;
        }
    }
    return $out;
}

/**
 * Derive a location from a classification. First matching rule wins.
 *
 * @return array{status:string, location:?string, rule:?string, note:?string}
 */
function resolve(array $classification, array $rules, array $domains): array
{
    foreach ($rules as $rule) {
        $matches = true;
        foreach ($rule['match'] ?? [] as $property => $expected) {
            $actual = $classification[$property] ?? null;
            $accepted = is_array($expected) ? $expected : [$expected];
            if ($actual === null || !in_array($actual, $accepted, true)) {
                $matches = false;
                break;
            }
        }
        if (!$matches) {
            continue;
        }

        $location = $rule['location'] ?? null;
        $note     = isset($rule['ref']) ? (string) $rule['ref'] : null;

        if ($location === 'PENDING') {
            return ['status' => 'pending', 'location' => null, 'rule' => $rule['id'], 'note' => $note];
        }

        if ($location === 'domain_root') {
            $domain = $classification['domain'] ?? null;
            if ($domain === null) {
                return ['status' => 'error', 'location' => null, 'rule' => $rule['id'],
                        'note' => 'product-specific artifacts require --domain'];
            }
            if (!isset($domains[$domain])) {
                return ['status' => 'error', 'location' => null, 'rule' => $rule['id'],
                        'note' => "unknown domain '{$domain}'; known: " . implode(', ', array_keys($domains))];
            }
            return ['status' => 'resolved', 'location' => $domains[$domain]['root'], 'rule' => $rule['id'], 'note' => $note];
        }

        return ['status' => 'resolved', 'location' => $location, 'rule' => $rule['id'], 'note' => $note];
    }

    return ['status' => 'error', 'location' => null, 'rule' => null,
            'note' => 'no rule matched this classification'];
}

$args = args($argv);

// ---- --list -----------------------------------------------------------------
if (isset($args['list'])) {
    echo "Documentation roots (registry: docs/knowledge/schema/documentation-placement.yaml)\n\n";
    foreach ($domains as $id => $d) {
        printf("  %-14s %-22s %s\n", $id, $d['root'] ?? '?', $d['label'] ?? '');
    }
    echo "\nDerivation rules (first match wins):\n\n";
    foreach ($rules as $rule) {
        $match = [];
        foreach ($rule['match'] ?? [] as $k => $v) {
            $match[] = $k . '=' . (is_array($v) ? implode('|', $v) : $v);
        }
        printf("  %-26s %-22s %s\n", $rule['id'], $rule['location'] ?? '?', implode(' ', $match));
    }
    exit(0);
}

// ---- --self-test ------------------------------------------------------------
if (isset($args['self-test'])) {
    $cases = [
        ['PublicDigit product doc',        ['scope' => 'product-specific', 'domain' => 'publicdigit'], 'resolved', 'docs/publicdigit'],
        ['KnowledgeOS domain doc',         ['scope' => 'product-specific', 'domain' => 'knowledgeos'], 'resolved', 'docs/knowledgeos'],
        ['PKS domain doc',                 ['scope' => 'product-specific', 'domain' => 'pks'],         'resolved', 'docs/pks'],
        ['Adopted cross-product standard', ['scope' => 'cross-product', 'maturity' => 'adopted'],      'resolved', 'engineering'],
        ['Qualified cross-product method', ['scope' => 'cross-product', 'maturity' => 'qualified'],    'resolved', 'engineering'],
        ['Cross-product RESEARCH',         ['scope' => 'cross-product', 'maturity' => 'research'],     'pending',  null],
        ['Active session state',           ['scope' => 'session-state'],                              'resolved', '.claude'],
        ['Unknown domain',                 ['scope' => 'product-specific', 'domain' => 'nope'],        'error',    null],
        ['Missing domain',                 ['scope' => 'product-specific'],                            'error',    null],
    ];

    $failed = 0;
    echo "doc-placement self-test\n\n";
    foreach ($cases as [$label, $classification, $expectStatus, $expectLocation]) {
        $r  = resolve($classification, $rules, $domains);
        $ok = $r['status'] === $expectStatus && $r['location'] === $expectLocation;
        $failed += $ok ? 0 : 1;
        printf(
            "  %s  %-33s -> %-9s %s\n",
            $ok ? '✅' : '❌',
            $label,
            $r['status'],
            $r['location'] ?? ($r['status'] === 'pending' ? '(unruled — caller must stop)' : '—')
        );
    }
    echo "\n" . ($failed === 0 ? "All " . count($cases) . " cases pass.\n" : "{$failed} case(s) FAILED.\n");
    exit($failed === 0 ? 0 : 3);
}

// ---- --verify ---------------------------------------------------------------
// Registry/root integrity only. A per-file validator needs declared classifications (Phase 1 map).
if (isset($args['verify'])) {
    $problems = [];
    echo "doc-placement --verify (registry and root integrity)\n\n";

    foreach ($domains as $id => $d) {
        $rootPath = $d['root'] ?? null;
        if ($rootPath === null) {
            $problems[] = "domain '{$id}' declares no root";
            continue;
        }
        $exists   = is_dir($root . '/' . $rootPath);
        $hasFirst = $exists && glob($root . '/' . $rootPath . '/*') !== [];
        printf("  %s  %-14s %-22s %s\n", $exists ? '✅' : '❌', $id, $rootPath,
            $exists ? ($hasFirst ? 'ok' : 'EMPTY') : 'MISSING');
        if (!$exists)   { $problems[] = "root '{$rootPath}' does not exist"; }
        if ($exists && !$hasFirst) { $problems[] = "root '{$rootPath}' is empty"; }
    }

    $pending = array_values(array_filter($rules, static fn ($r) => ($r['location'] ?? null) === 'PENDING'));
    echo "\n  Unruled classifications: " . count($pending) . "\n";
    foreach ($pending as $rule) {
        echo "    ⚠️  {$rule['id']}  (ref: " . ($rule['ref'] ?? '—') . ")\n";
    }

    echo "\n" . ($problems === [] ? "Registry and roots consistent.\n" : "Problems:\n  - " . implode("\n  - ", $problems) . "\n");
    exit($problems === [] ? 0 : 3);
}

// ---- resolve ----------------------------------------------------------------
if (!isset($args['scope'])) {
    fwrite(STDERR, "Usage: php scripts/doc-placement.php --scope=<product-specific|cross-product|session-state>"
        . " [--maturity=<research|qualified|adopted>] [--domain=<id>]\n"
        . "       php scripts/doc-placement.php --list | --self-test\n");
    exit(1);
}

$result = resolve([
    'scope'    => is_string($args['scope']) ? $args['scope'] : null,
    'maturity' => isset($args['maturity']) && is_string($args['maturity']) ? $args['maturity'] : null,
    'domain'   => isset($args['domain']) && is_string($args['domain']) ? $args['domain'] : null,
], $rules, $domains);

switch ($result['status']) {
    case 'resolved':
        echo $result['location'] . "\n";
        exit(0);
    case 'pending':
        fwrite(STDERR, "PENDING — placement unruled (rule: {$result['rule']}, ref: {$result['note']}).\n"
            . "  Record PENDING and escalate. Do not guess a location.\n");
        exit(2);
    default:
        fwrite(STDERR, "doc-placement: cannot derive a location — {$result['note']}\n");
        exit(1);
}
