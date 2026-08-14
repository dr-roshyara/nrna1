<?php
/**
 * session-resolve.php — Session Assignment Resolver (AST-016).
 * KOS-SESSION-DISCOVERY-001 · CMP-004's second implementation asset.
 *
 * READ-ONLY DISCOVERY. Answers one question:
 *   "Which governed session assignment does the authoritative record currently
 *    expose for this execution host?"
 *
 *   Discovery ≠ Authorization ≠ Activation ≠ START ≠ Ownership.
 *
 * ── Binding architectural rule (AMENDMENT 2) ────────────────────────────────
 * AST-015 (workflow-state.php) is the AUTHORITATIVE interpretation of workflow
 * records. This script contains NO fold loop, NO transition state machine, NO
 * state derivation and NO record-schema interpretation: it invokes the
 * qualified mechanism as a subprocess and reports what that mechanism says.
 * If the mechanism is unavailable, this script CANNOT determine workflow state
 * and refuses (UNRESOLVABLE) — it never falls back to reading records itself.
 *
 * ── Structural read-only ────────────────────────────────────────────────────
 * There is no write call anywhere in this file: no fopen for writing, no
 * file_put_contents, no mkdir/rename/unlink, and the mechanism is only ever
 * invoked with its read subcommands (`fold`). Read purity is a property of the
 * code, not a promise in a comment (pinned by T-11).
 *
 * ── Exit contract (AMENDMENT 1) ─────────────────────────────────────────────
 * A produced ResolutionReport is a SUCCESS: RESOLVED · UNASSIGNED · AMBIGUOUS ·
 * UNRESOLVABLE all exit 0. Non-zero is reserved for usage errors (64) and for
 * refusal to produce a report at all (65). UNASSIGNED and AMBIGUOUS are valid
 * answers, never command failures (pinned by T-12).
 *
 * Usage:
 *   php .claude/scripts/session-resolve.php [--dir=<records>]
 *        [--work-item=<id>] [--role=<role>] [--session=<id>] [--json]
 *
 * Reports are ephemeral: re-run before acting, never cache (staleness is
 * uncomputable — the record carries no transition timestamps, D-1).
 */

declare(strict_types=1);

const EX_USAGE = 64;
const EX_REFUSED = 65;

const CAVEAT = 'Resolution is not activation. This report creates no authority, '
    . 'no ownership, no state change. G-3 gates are untouched.';

const READ_ONLY_PARTICIPATION =
    'NOT EXPRESSIBLE by the current record — governed by convention (O-1/O-4). '
    . 'Vocabulary cure is dependency D-6, separately governed.';

// ─── argument parsing ───────────────────────────────────────────────────────

$opts = ['dir' => null, 'work-item' => null, 'role' => null, 'session' => null];
$asJson = false;

foreach (array_slice($argv, 1) as $arg) {
    if ($arg === '--json') {
        $asJson = true;
        continue;
    }
    if (!str_starts_with($arg, '--') || !str_contains($arg, '=')) {
        fwrite(STDERR, "usage error: unrecognized argument '{$arg}'\n");
        exit(EX_USAGE);
    }
    [$k, $v] = explode('=', substr($arg, 2), 2);
    if (!array_key_exists($k, $opts)) {
        fwrite(STDERR, "usage error: unknown option '--{$k}'\n");
        exit(EX_USAGE);
    }
    $opts[$k] = $v;
}

$recordDir = rtrim($opts['dir'] ?? (dirname(__DIR__) . '/runtime/workflow'), '/');

/**
 * Path to the qualified mechanism (AST-015).
 *
 * Production default is the sibling AST-015 script. KOS_MECHANISM_PATH is a
 * TEST SEAM for dependency substitution only (it exists so T-13(b) can prove
 * this resolver cannot answer without the authoritative interpreter). It is
 * deliberately NOT a runtime configuration contract: it is undocumented for
 * operators, has no registry entry, and no platform concept depends on it.
 */
$mechanism = getenv('KOS_MECHANISM_PATH') ?: (__DIR__ . '/workflow-state.php');

// ─── the ONLY source of workflow interpretation ─────────────────────────────

/**
 * Invoke the qualified mechanism read-only and return its decoded output.
 * Returns null when the mechanism cannot answer — never a locally derived state.
 */
function askMechanism(string $mechanism, array $args): ?array
{
    if (!is_file($mechanism)) {
        return null;
    }
    $proc = proc_open(array_merge(['php', $mechanism], $args),
        [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    if (!is_resource($proc)) {
        return null;
    }
    $out = stream_get_contents($pipes[1]);
    stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $code = proc_close($proc);

    if ($code !== 0) {
        return null;
    }
    $decoded = json_decode((string) $out, true);

    return is_array($decoded) ? $decoded : null;
}

// ─── gather work items (names only — never their contents) ──────────────────

$reasons = [];
$workItems = [];

if (!is_dir($recordDir)) {
    $reasons[] = "record directory not readable: {$recordDir}";
} else {
    foreach (glob($recordDir . '/*.json') ?: [] as $path) {
        $workItems[] = basename($path, '.json');
    }
    sort($workItems);
}

if ($opts['work-item'] !== null) {
    $workItems = array_values(array_filter($workItems, fn ($w) => $w === $opts['work-item']));
    $reasons[] = "filter applied: work-item={$opts['work-item']}";
}

// ─── fold each record THROUGH the qualified mechanism ───────────────────────

$mechanismAvailable = is_file($mechanism);
if (!$mechanismAvailable) {
    $reasons[] = "qualified workflow mechanism unavailable at {$mechanism} — "
        . 'this resolver cannot determine workflow state independently (AMENDMENT 2)';
}

$candidates = [];
$grants = [];
$readableRecords = 0;
$resolvedOwner = null;

foreach ($workItems as $workItem) {
    $fold = $mechanismAvailable
        ? askMechanism($mechanism, ['fold', $workItem, '--dir=' . $recordDir])
        : null;

    if ($fold === null) {
        $reasons[] = "record not interpretable by the qualified mechanism: {$workItem}"
            . ' — never repaired, never hand-derived; escalate to Governance';
        continue;
    }

    $readableRecords++;

    foreach ($fold['grants'] ?? [] as $grant) {
        $grants[] = $grant + ['workItem' => $workItem];   // verbatim, never paraphrased
    }

    foreach ($fold['sessions'] ?? [] as $sessionId => $session) {
        if ($opts['role'] !== null && ($session['role'] ?? null) !== $opts['role']) {
            continue;
        }
        if ($opts['session'] !== null && $sessionId !== $opts['session']) {
            continue;
        }
        $candidates[] = [
            'workItem' => $workItem,
            'session' => $sessionId,
            'role' => $session['role'] ?? null,
            'state' => $session['state'] ?? null,
            'predecessor' => $session['predecessor'] ?? null,
            'executionContext' => $session['executionContext'] ?? null,
            'mutationOwner' => $fold['mutationOwner'] ?? null,
            'workItemState' => $fold['workItemState'] ?? null,
        ];
        $resolvedOwner = $fold['mutationOwner'] ?? null;
    }
}

foreach (['role', 'session'] as $filter) {
    if ($opts[$filter] !== null) {
        $reasons[] = "filter applied: {$filter}={$opts[$filter]}";
    }
}

// ─── verdict (architecture §G/§H) ───────────────────────────────────────────

$operable = false;
$missingForActivation = [];

if ($readableRecords === 0) {
    $verdict = 'UNRESOLVABLE';
    $reasons[] = 'no readable authoritative record — STOP and escalate; '
        . '"no record = ungoverned = free" is forbidden reasoning';
    $resolvedOwner = null;
} elseif (count($candidates) === 0) {
    $verdict = 'UNASSIGNED';
    $reasons[] = 'no assignment matches — absence is not permission; '
        . 'request Governance registration of an assignment';
    $resolvedOwner = null;
} elseif (count($candidates) > 1) {
    $verdict = 'AMBIGUOUS';
    $reasons[] = 'more than one assignment matches — all candidates are listed and NONE is chosen; '
        . 'narrow with --work-item/--role/--session or ask Governance which assignment applies';
    $resolvedOwner = null;
} else {
    $verdict = 'RESOLVED';
    $state = $candidates[0]['state'];
    $operable = $state === 'ACTIVE';

    switch ($state) {
        case 'ACTIVE':
            $reasons[] = 'assignment is ACTIVE — operable; operability is not authorization';
            break;
        case 'CREATED':
            $missingForActivation = [
                'a recorded predecessor HANDOFF carrying its token',
                'a recorded human START act',
            ];
            $reasons[] = 'not operable: activation requires BOTH recorded facts (G-3). '
                . '"recorded" is load-bearing — a live but unregistered act still counts as missing';
            break;
        case 'HANDED_OFF':
            $reasons[] = 'not operable: work was passed to a successor';
            break;
        case 'STOPPED':
            $reasons[] = 'not operable: STOPPED is sticky — the only exit is an explicit '
                . 'CONTINUATION transition recorded by Governance/Human. This resolver states '
                . 'that requirement; it cannot perform it';
            break;
        case 'COMPLETED':
        case 'CANCELLED':
        case 'FAILED':
            $reasons[] = "not operable: {$state} is terminal — a new need is a NEW assignment (R8)";
            break;
        default:
            $reasons[] = "not operable: unrecognised state '{$state}' reported by the mechanism";
    }
}

if ($candidates === [] || $verdict !== 'RESOLVED') {
    $resolvedOwner = $verdict === 'RESOLVED' ? $resolvedOwner : ($verdict === 'AMBIGUOUS' ? null : $resolvedOwner);
}

// ─── the six AuthorizationFacts — surfaced, never evaluated (§I) ────────────

$only = $verdict === 'RESOLVED' ? $candidates[0] : null;

$authorizationFacts = [
    ['fact' => 'correct session', 'value' => $only['session'] ?? 'UNKNOWN — no single assignment resolved'],
    ['fact' => 'correct role', 'value' => $only['role'] ?? 'UNKNOWN — no single assignment resolved'],
    ['fact' => 'state is ACTIVE', 'value' => $only === null ? 'UNKNOWN — no single assignment resolved'
        : ($operable ? 'yes' : 'no — state is ' . $only['state'])],
    ['fact' => 'holds mutation ownership', 'value' => $only === null ? 'UNKNOWN — no single assignment resolved'
        : (($only['mutationOwner'] ?? null) === $only['session'] ? 'yes' : 'no')],
    ['fact' => 'is the grant holder', 'value' => 'UNKNOWN — not evaluable from the record: '
        . 'grants carry no session/role linkage (D-2)'],
    ['fact' => 'the intended act is covered by grant scope', 'value' => 'UNKNOWN — not evaluable from the record: '
        . 'scope exists only as a string, compared by equality'],
];

// ─── report ─────────────────────────────────────────────────────────────────

$report = [
    'verdict' => $verdict,
    'operable' => $operable,
    'recordDirectory' => $recordDir,
    'workItemsFound' => $workItems,
    'candidates' => $candidates,
    'mutationOwner' => $resolvedOwner,
    'missingForActivation' => $missingForActivation,
    'grants' => $grants,
    'grantLifecycleCaveat' => 'Grant status is reported as recorded; lifecycle exercise/closure is E-15 (D-3).',
    'authorizationFacts' => $authorizationFacts,
    'readOnlyParticipation' => READ_ONLY_PARTICIPATION,
    'interpretationAuthority' => 'AST-015 workflow-state.php — this resolver performs no independent '
        . 'interpretation of workflow records',
    'reasons' => $reasons,
    'caveat' => CAVEAT,
];

if ($asJson) {
    fwrite(STDOUT, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    exit(0);
}

fwrite(STDOUT, "Session Assignment Resolution\n");
fwrite(STDOUT, str_repeat('=', 60) . "\n");
fwrite(STDOUT, "verdict:  {$report['verdict']}\n");
fwrite(STDOUT, 'operable: ' . ($report['operable'] ? 'true' : 'false') . "\n");
foreach ($candidates as $c) {
    fwrite(STDOUT, "  · {$c['workItem']} :: {$c['session']} [{$c['role']}] = {$c['state']}"
        . ($c['mutationOwner'] === $c['session'] ? ' (mutation owner)' : '') . "\n");
}
foreach ($missingForActivation as $m) {
    fwrite(STDOUT, "  missing for activation: {$m}\n");
}
foreach ($authorizationFacts as $f) {
    fwrite(STDOUT, "  authorization fact — {$f['fact']}: {$f['value']}\n");
}
fwrite(STDOUT, '  readOnlyParticipation: ' . READ_ONLY_PARTICIPATION . "\n");
foreach ($reasons as $r) {
    fwrite(STDOUT, "  reason: {$r}\n");
}
fwrite(STDOUT, str_repeat('-', 60) . "\n" . CAVEAT . "\n");
exit(0);
