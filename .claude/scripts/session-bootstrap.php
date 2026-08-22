<?php
/**
 * session-bootstrap.php — Session Bootstrap & Responsibility Resolution (AST-017).
 * KOS-SESSION-BOOTSTRAP-001 · CMP-004's third implementation asset.
 *
 * READ-ONLY bootstrap. Answers one question at session start:
 *   "Which registered lane / role / workflow state / authority does the
 *    authoritative record expose for THIS process — and what may it do?"
 *
 * The six are NEVER collapsed into one "agent identity":
 *   IDENTITY ≠ ROLE ≠ ELIGIBILITY ≠ AUTHORIZATION ≠ OWNERSHIP ≠ CONTINUATION.
 *
 * ── Binding architectural rule (AMENDMENT 2 + V-3) ───────────────────────────
 * AST-015 (workflow-state.php) is the AUTHORITATIVE interpretation of workflow
 * records. This script contains NO fold loop, NO transition state machine, NO
 * state derivation and NO record-schema interpretation: it invokes the
 * qualified mechanism as a subprocess (fold / identity / authorized) and reports
 * what that mechanism says. If the mechanism is unavailable, this script CANNOT
 * determine workflow state and fails closed (UNRESOLVABLE).
 *
 * ── The ONE bounded exception (V-3) ──────────────────────────────────────────
 * Open finding V-3: no AST-015 read command exposes the predecessor-handoff fact.
 * AST-017 performs exactly one bounded raw read for ITS OWN consumption — the
 * single HANDOFF-to/from-lane fact (presence + token/tokenRef + successor target)
 * — in the named function v3HandoffRead(), cited to the finding. No other fact
 * is derived from raw JSON. Pinned by regression S-16 (raw decoys must never
 * leak into the report) and S-2b (the handoff line is truthful). The FULL remedy
 * — an AST-015 read command — is EKS-07 FOLLOW-UP, not this increment.
 *
 * ── Fail-closed ──────────────────────────────────────────────────────────────
 * Every produced report (RESOLVED · AMBIGUOUS · UNRESOLVED · UNRESOLVABLE)
 * exits 0. On any non-RESOLVED verdict: operable=false, authorized_to_act=false,
 * current_session_can_continue=false, and `meta.unresolved_message` names the
 * missing fact · its source · the responsible next actor. The bootstrap never
 * invents identity/authorization and never creates a transition.
 *
 * ── Structural read-only ─────────────────────────────────────────────────────
 * No write call anywhere: no file_put_contents, no mkdir, no rename, no unlink.
 * The only record access is the read inside v3HandoffRead(). Read purity is a
 * property of the code (pinned by S-9).
 *
 * Usage:
 *   php .claude/scripts/session-bootstrap.php [--dir=<records>]
 *        [--work-item=<id>] [--process-label=<label>] [--session=<lane>]
 *        [--role=<role>] [--scope=<s>] [--json]
 *
 * Default --dir is .claude/runtime/workflow relative to the repository root.
 * Exit codes: 0 report produced · 64 usage error.
 *
 * Reports are ephemeral: re-run before acting, never cache (staleness is
 * uncomputable — the record carries no transition timestamps, D-1).
 */

declare(strict_types=1);

const EX_USAGE = 64;

const CAVEAT = 'Resolution is not activation. This report creates no authority, '
    . 'no ownership, no state change. G-3 gates are untouched.';

// ─── argument parsing (mirrors AST-016's contract) ───────────────────────────

$opts = [
    'dir' => null,
    'work-item' => null,
    'process-label' => null,
    'session' => null,
    'role' => null,
    'scope' => null,
];
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
 * Path to the workflow interpreter. Default: the sibling AST-015 script.
 * KOS_MECHANISM_PATH is a runtime-selectable mechanism path (C-2) — its purpose
 * is verification/testing; the bootstrap REPORTS which interpreter answered
 * (C-1) so substitution is visible, never silent, and performs no legitimacy
 * assessment on that basis.
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

/**
 * Extract the process labels referenced by a lane's registered executionContext.
 * Accepts `claude-code-session:<id>` / `claude-code-session:<id>` and bare
 * hex-ish session ids. Deterministic order — used for matching and for the
 * identity block (INV-ATTR-1: evidence-only, reported never a grant).
 */
function processLabelsReferenced(string $context): array
{
    $labels = [];
    if (preg_match_all('/claude-code-session[:=]([A-Za-z0-9._-]+)/', $context, $m)) {
        foreach ($m[1] as $x) {
            $labels[] = $x;
        }
    }
    if (preg_match_all('/\b([a-f0-9]{6,16})\b/i', $context, $m2)) {
        foreach ($m2[1] as $x) {
            $labels[] = $x;
        }
    }

    return array_values(array_unique($labels));
}

/**
 * V-3 bounded read — the ONLY fact derived from raw JSON.
 *
 * Open finding V-3 (docs/knowledgeos/backlog/EKS-07-multi-process-coordination.md
 * §7): no AST-015 read command exposes the predecessor-handoff fact. This
 * function reads the append-only transition log to answer exactly one question:
 *   "is there a HANDOFF to/from this lane, with token + tokenRef?"
 * It derives NOTHING else: no workflow state, no ownership, no authorization, no
 * role, no lifecycle, no predecessor semantics. Everything else comes from
 * askMechanism(fold/identity). Partial remedy chosen 2026-08-22; the FULL remedy
 * (an AST-015 read command) is EKS-07 FOLLOW-UP.
 *
 * @return array{predecessor:?array{token:string,tokenRef:string,from:?string},successor:?array{token:string,tokenRef:string,to:?string}}
 */
function v3HandoffRead(string $recordPath, string $lane): array
{
    $decoded = json_decode((string) file_get_contents($recordPath), true);
    if (!is_array($decoded) || !isset($decoded['transitions']) || !is_array($decoded['transitions'])) {
        return ['predecessor' => null, 'successor' => null];
    }
    $predecessor = null; // last HANDOFF to==lane carrying token + tokenRef
    $successor = null;   // last HANDOFF from==lane carrying token + tokenRef
    foreach ($decoded['transitions'] as $t) {
        if (($t['type'] ?? '') !== 'HANDOFF') {
            continue;
        }
        if (($t['token'] ?? '') === '' || ($t['tokenRef'] ?? '') === '') {
            continue; // Inv D / R3a — a handoff without its token-reference is NOT a handoff
        }
        if (($t['to'] ?? null) === $lane) {
            $predecessor = ['token' => $t['token'], 'tokenRef' => $t['tokenRef'], 'from' => $t['from'] ?? null];
        }
        if (($t['from'] ?? null) === $lane) {
            $successor = ['token' => $t['token'], 'tokenRef' => $t['tokenRef'], 'to' => $t['to'] ?? null];
        }
    }

    return ['predecessor' => $predecessor, 'successor' => $successor];
}

/** Human-readable lane roster for unresolved/ambiguous messages (labels included). */
function describeLanes(array $candidates): string
{
    $parts = [];
    foreach ($candidates as $c) {
        $labels = processLabelsReferenced((string) ($c['execution_context'] ?? ''));
        $parts[] = ($c['work_item'] ?? '?') . ' :: ' . ($c['lane'] ?? '?') . ' [' . ($c['role'] ?? '?') . ']'
            . ($labels === [] ? ' (no process label referenced)' : ' {' . implode(', ', $labels) . '}');
    }

    return $parts === [] ? '(none — no lanes readable)' : implode('; ', $parts);
}

// ─── current process identity (evidence-only, INV-ATTR-2) ───────────────────

$uuid = getenv('CLAUDE_CODE_SESSION_ID');
$currentProcessUuid = ($uuid !== false && $uuid !== '') ? $uuid : null;
$currentProcessLabel = $opts['process-label'] !== null ? $opts['process-label'] : $currentProcessUuid;

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

// ─── fold each record THROUGH the qualified mechanism ────────────────────────

$mechanismAvailable = is_file($mechanism);
if (!$mechanismAvailable) {
    $reasons[] = "qualified workflow mechanism unavailable at {$mechanism} — "
        . 'this bootstrap cannot determine workflow state independently (AMENDMENT 2)';
}

$candidates = [];
$readableRecords = 0;

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

    foreach ($fold['sessions'] ?? [] as $laneId => $s) {
        if ($opts['role'] !== null && ($s['role'] ?? null) !== $opts['role']) {
            continue;
        }
        if ($opts['session'] !== null && $laneId !== $opts['session']) {
            continue;
        }
        $candidates[] = [
            'work_item' => $workItem,
            'lane' => $laneId,
            'role' => $s['role'] ?? null,
            'state' => $s['state'] ?? null,
            'predecessor' => $s['predecessor'] ?? null,
            'execution_context' => $s['executionContext'] ?? null,
            'work_item_state' => $fold['workItemState'] ?? null,
            'mutation_owner' => $fold['mutationOwner'] ?? null,
        ];
    }
}

// ─── verdict & selection ─────────────────────────────────────────────────────

$selected = null;
$verdict = null;
$ambiguousMatches = []; // V-3: ONLY the candidates that actually matched the selector

if ($readableRecords === 0) {
    $verdict = 'UNRESOLVABLE';
    $reasons[] = 'no readable authoritative record — STOP and escalate; '
        . '"no record = ungoverned = free" is forbidden reasoning';
} elseif (count($candidates) === 0) {
    $verdict = 'UNRESOLVED';
    $reasons[] = 'no governed lane matches the requested filters — absence is not permission; '
        . 'request Governance registration of an assignment';
} elseif ($opts['session'] !== null) {
    if (count($candidates) > 1) {
        // Same lane id in more than one record — never a silent pick.
        $verdict = 'AMBIGUOUS';
        $ambiguousMatches = $candidates; // every record carrying that lane id IS a match
        $reasons[] = "lane id '{$opts['session']}' occurs in more than one record — "
            . 'specify --work-item to disambiguate';
    } else {
        $selected = $candidates[0];
        $verdict = 'RESOLVED';
    }
} elseif ($currentProcessLabel === null) {
    $verdict = 'UNRESOLVED';
    $reasons[] = 'no process identity supplied (--process-label or CLAUDE_CODE_SESSION_ID) and no --session — '
        . 'the bootstrap cannot determine which lane this process belongs to; provide an explicit label or ask Governance';
} else {
    $matching = array_values(array_filter($candidates, function ($c) use ($currentProcessLabel) {
        return in_array($currentProcessLabel, processLabelsReferenced((string) ($c['execution_context'] ?? '')), true);
    }));
    if (count($matching) === 0) {
        $verdict = 'UNRESOLVED';
        $reasons[] = "no lane references process label '{$currentProcessLabel}' — "
            . 'the REGISTER that attributes this process is the missing fact (Inv B)';
    } elseif (count($matching) > 1) {
        $verdict = 'AMBIGUOUS';
        $ambiguousMatches = $matching; // ONLY the lanes that reference the current process label (V-3)
        $reasons[] = 'more than one lane references the current process label — all are listed and NONE is '
            . 'chosen; narrow with --session/--work-item or ask Governance which assignment applies';
    } else {
        $selected = $matching[0];
        $verdict = 'RESOLVED';
    }
}

// ─── RESOLVED: derive the six blocks from mechanism output + the one V-3 fact ─

$identityBlock = null;
$assignmentBlock = null;
$activationBlock = null;
$grantBlock = null;
$ownerBlock = null;
$gatesBlock = null;
$continuationBlock = null;

if ($verdict === 'RESOLVED' && $selected !== null) {
    $lane = $selected['lane'];
    $referenced = processLabelsReferenced((string) ($selected['execution_context'] ?? ''));

    // Attribution — evidence-only, never a grant (INV-ATTR-1 / INV-ATTR-2).
    if ($currentProcessLabel === null) {
        $attribution = 'UNKNOWN';
        $attributionCaveat = 'No process identity was provided (no --process-label, no CLAUDE_CODE_SESSION_ID). '
            . 'Identity is self-declared until attested (INV-ATTR-2) — authorization is not granted on UNKNOWN '
            . 'attribution. Route to a Governance act or re-run with the explicit process label.';
    } elseif (in_array($currentProcessLabel, $referenced, true)) {
        $attribution = 'MATCH';
        $attributionCaveat = "The current process label '{$currentProcessLabel}' is referenced by this lane's "
            . 'registered executionContext. Identity is evidence-only (INV-ATTR-1) — it is reported, never itself a grant.';
    } else {
        $attribution = 'MISMATCH';
        $attributionCaveat = "The current process label '{$currentProcessLabel}' does NOT appear in this lane's "
            . 'registered executionContext. This bootstrap will not adopt another process\'s identity — route to a '
            . 'Governance act to correct the REGISTER attribution; no impersonation is performed.';
    }

    // V-3 — the ONE bounded raw read.
    $v3 = v3HandoffRead($recordDir . '/' . $selected['work_item'] . '.json', $lane);
    $predecessorHandoff = $v3['predecessor'];
    $successorHandoff = $v3['successor'];

    // G-3 conjunction: START requires BOTH the predecessor's recorded handoff AND
    // a recorded human START act. A lane is ACTIVE only if both were recorded.
    // A human START is therefore NEVER inferred from "the lane left CREATED" —
    // a CREATED→CANCELLED lane has no start act (V-1). ACTIVE is the fold state
    // AST-015 sets only on START/CONTINUATION — the authoritative fact (no raw read).
    $recordedHumanStartAct = $selected['state'] === 'ACTIVE';
    $missingForStart = [];
    if ($predecessorHandoff === null) {
        $missingForStart[] = 'a recorded predecessor HANDOFF carrying its token + tokenRef (G-3)';
    }
    if (!$recordedHumanStartAct) {
        $missingForStart[] = 'a recorded human START act (G-3)';
    }

    // Grant facts — linked, never contained (D-2 / R6).
    $scopeRequested = $opts['scope'];
    $scopeEvaluable = $scopeRequested !== null && $scopeRequested !== '';
    $authorizedWithinScope = null;
    if ($scopeEvaluable) {
        $a = askMechanism($mechanism, [
            'authorized', $selected['work_item'], '--session=' . $lane, '--scope=' . $scopeRequested,
            '--dir=' . $recordDir,
        ]);
        $authorizedWithinScope = ($a['authorized'] ?? false) === true;
    }
    $idn = askMechanism($mechanism, [
        'identity', $selected['work_item'], '--session=' . $lane, '--dir=' . $recordDir,
    ]);
    $authorizationLinkage = $idn['authorizationLinkage'] ?? null;

    // Gates — the actor conjunction, fail-closed on any unknown conjunct.
    $activated = $selected['state'] === 'ACTIVE' && $predecessorHandoff !== null && $recordedHumanStartAct;
    $attributionOk = $attribution === 'MATCH';
    $scopeOk = !$scopeEvaluable || $authorizedWithinScope === true;
    $authorizedToAct = $attributionOk && $activated && $scopeOk;

    $humanDecision = false;
    $detail = '';
    switch ($selected['state']) {
        case 'CREATED':
            $humanDecision = true;
            $detail = 'Lane not yet activated: G-3 requires BOTH a predecessor handoff (token + tokenRef) '
                . 'and a recorded human START act — the PO/ARB decides the START.';
            break;
        case 'ACTIVE':
            if ($attribution !== 'MATCH') {
                $humanDecision = true;
                $detail = "Identity is not attested to this lane (attribution {$attribution}) — "
                    . 'a Governance act is required before any authorization.';
            } elseif ($scopeEvaluable && !$authorizedWithinScope) {
                $humanDecision = true;
                $detail = 'Active, but the requested scope is not covered by an AUTHORIZED grant (R6/D-2) — '
                    . 'a Governance grant act is required.';
            }
            break;
        case 'HANDED_OFF':
            $humanDecision = true;
            $detail = 'Work was passed to a successor; continuation is the successor\'s (G-3).';
            break;
        case 'STOPPED':
            $humanDecision = true;
            $detail = 'STOPPED is sticky (Inv E) — the only exit is an explicit CONTINUATION recorded by '
                . 'Governance/Human. This bootstrap states the requirement; it cannot perform it.';
            break;
        case 'COMPLETED':
        case 'CANCELLED':
        case 'FAILED':
            $humanDecision = true;
            $detail = 'Terminal state — a new need is a NEW assignment (R8); the PO/ARB decides.';
            break;
        default:
            $humanDecision = true;
            $detail = "Unrecognised state '{$selected['state']}' — fail closed to a Governance act.";
    }

    // Deterministic next actor.
    $successorRole = null;
    if ($successorHandoff !== null && $successorHandoff['to'] !== null) {
        foreach ($candidates as $c) {
            if ($c['work_item'] === $selected['work_item'] && $c['lane'] === $successorHandoff['to']) {
                $successorRole = $c['role'];
                break;
            }
        }
    }
    switch ($selected['state']) {
        case 'CREATED':
            $nextActor = [
                'role' => 'po/arb',
                'reason' => 'G-3 activation: only a human PO/ARB START act (with the predecessor handoff) moves the lane to ACTIVE.',
                'blocking_condition' => 'recorded predecessor handoff (token + tokenRef) and recorded human START act',
            ];
            break;
        case 'ACTIVE':
            if ($attribution !== 'MATCH') {
                $nextActor = [
                    'role' => 'governance',
                    'reason' => 'Identity mismatch/unknown — attribution is evidence-only and never impersonates (INV-ATTR-1).',
                    'blocking_condition' => 'a Governance act attesting/correcting the REGISTER attribution',
                ];
            } elseif ($scopeEvaluable && !$authorizedWithinScope) {
                $nextActor = [
                    'role' => $selected['role'],
                    'reason' => 'Active lane, but the requested scope is not covered by an AUTHORIZED grant (R6).',
                    'blocking_condition' => 'a Governance grant act covering the scope',
                ];
            } else {
                $nextActor = [
                    'role' => $selected['role'],
                    'reason' => 'Active lane with matching identity and covered scope — the lane may continue.',
                    'blocking_condition' => 'none',
                ];
            }
            break;
        case 'HANDED_OFF':
            $nextActor = [
                'role' => $successorRole ?? 'po/arb',
                'reason' => 'Work was passed to a successor (G-3); continuation is the successor\'s.',
                'blocking_condition' => 'successor START (G-3) or a Governance act',
            ];
            break;
        case 'STOPPED':
            $nextActor = [
                'role' => 'governance',
                'reason' => 'STOPPED is sticky (Inv E) — the only exit is an explicit CONTINUATION recorded by Governance/Human.',
                'blocking_condition' => 'an explicit CONTINUATION transition',
            ];
            break;
        case 'COMPLETED':
        case 'CANCELLED':
        case 'FAILED':
            $nextActor = [
                'role' => 'po/arb',
                'reason' => 'Terminal — a new need is a NEW assignment (R8); the PO/ARB decides.',
                'blocking_condition' => 'a PO/ARB decision',
            ];
            break;
        default:
            $nextActor = [
                'role' => 'governance',
                'reason' => "Unrecognised state '{$selected['state']}' — fail closed.",
                'blocking_condition' => 'a Governance act',
            ];
    }

    $identityBlock = [
        'current_process_uuid' => $currentProcessUuid,
        'current_process_label' => $currentProcessLabel,
        'registered_process_label' => $referenced[0] ?? null,
        'process_labels_referenced' => $referenced,
        'attribution' => $attribution,
        'attribution_caveat' => $attributionCaveat,
    ];
    $assignmentBlock = [
        'work_item' => $selected['work_item'],
        'lane' => $lane,
        'role' => $selected['role'],
        'predecessor' => $selected['predecessor'],
        'workflow_state' => $selected['state'],
        'work_item_state' => $selected['work_item_state'],
    ];
    $activationBlock = [
        'predecessor_handoff_present' => $predecessorHandoff !== null,
        'predecessor_handoff_token_ref' => $predecessorHandoff['tokenRef'] ?? null,
        'successor_handoff_present' => $successorHandoff !== null,
        'successor_lane' => $successorHandoff['to'] ?? null,
        'recorded_human_start_act' => $recordedHumanStartAct,
        'missing_for_start' => $missingForStart,
    ];
    $grantBlock = [
        'authorization_linkage' => $authorizationLinkage,
        'linkage_caveat' => 'Grants carry no session/role linkage (D-2); authorizationLinkage is the last '
            . 'AUTHORIZED grant id recorded — linked to, never contained.',
        'scope_requested' => $scopeRequested,
        'scope_coverage_evaluable' => $scopeEvaluable,
        'authorized_within_scope' => $authorizedWithinScope,
        'grant_caveat' => 'R6: ACTIVE-with-no-covering-grant is a first-class answer, never an error. '
            . 'Scope exists only as a string, compared by equality.',
    ];
    $ownerBlock = [
        'session' => $selected['mutation_owner'],
        'is_this_lane' => $selected['mutation_owner'] === $lane,
    ];
    $gatesBlock = [
        'authorized_to_act' => $authorizedToAct,
        'rationale' => "attribution={$attribution}; workflow_state={$selected['state']}; "
            . 'predecessor_handoff_present=' . ($predecessorHandoff !== null ? 'true' : 'false') . '; '
            . 'recorded_human_start_act=' . ($recordedHumanStartAct ? 'true' : 'false') . '; '
            . ($scopeEvaluable ? 'authorized_within_scope=' . ($authorizedWithinScope ? 'true' : 'false')
                : 'scope not evaluated (no --scope)'),
        'human_decision_required' => $humanDecision,
        'detail' => $detail,
    ];
    $continuationBlock = [
        'current_session_can_continue' => $selected['state'] === 'ACTIVE' && $attribution === 'MATCH',
        'recommended_next_actor' => $nextActor,
    ];
} else {
    // Fail-closed blocks for AMBIGUOUS / UNRESOLVED / UNRESOLVABLE.
    $identityBlock = [
        'current_process_uuid' => $currentProcessUuid,
        'current_process_label' => $currentProcessLabel,
        'registered_process_label' => null,
        'process_labels_referenced' => [],
        'attribution' => null,
        'attribution_caveat' => 'No single lane resolved — identity is not attributable.',
    ];
    $assignmentBlock = [
        'work_item' => null, 'lane' => null, 'role' => null, 'predecessor' => null,
        'workflow_state' => null, 'work_item_state' => null,
    ];
    $activationBlock = [
        'predecessor_handoff_present' => false, 'predecessor_handoff_token_ref' => null,
        'successor_handoff_present' => false, 'successor_lane' => null,
        'recorded_human_start_act' => false, 'missing_for_start' => [],
    ];
    $grantBlock = [
        'authorization_linkage' => null,
        'linkage_caveat' => 'Grants carry no session/role linkage (D-2).',
        'scope_requested' => $opts['scope'],
        'scope_coverage_evaluable' => $opts['scope'] !== null && $opts['scope'] !== '',
        'authorized_within_scope' => null,
        'grant_caveat' => 'R6: not evaluated — no single lane resolved.',
    ];
    $ownerBlock = ['session' => null, 'is_this_lane' => false];
    $gatesBlock = [
        'authorized_to_act' => false,
        'rationale' => 'no single lane resolved — fail closed',
        'human_decision_required' => true,
        'detail' => $verdict === 'UNRESOLVABLE'
            ? 'The bootstrap cannot determine workflow state — escalate to Governance.'
            : ($verdict === 'AMBIGUOUS'
                ? 'More than one lane applies — a Governance decision or explicit disambiguation is required.'
                : 'No governed lane is attributable to this process — a Governance REGISTER is required.'),
    ];
    $continuationBlock = [
        'current_session_can_continue' => false,
        'recommended_next_actor' => [
            'role' => 'governance',
            'reason' => 'Fail-closed: no single lane resolved; the responsibility to resolve the state rests with Governance.',
            'blocking_condition' => $verdict === 'UNRESOLVABLE'
                ? 'a readable, interpretable authoritative record'
                : ($verdict === 'AMBIGUOUS'
                    ? 'an explicit --session/--work-item or a Governance decision'
                    : 'a Governance REGISTER attributing this process to a lane'),
        ],
    ];
}

// ─── meta — always present, verdict-independent ──────────────────────────────

$unresolvedMessage = null;
if ($verdict === 'UNRESOLVED') {
    $unresolvedMessage = 'UNRESOLVED — no governed lane is attributable to this process. '
        . 'Missing fact: a REGISTER transition attributing the process to a lane (Inv B: role + '
        . 'executionContext + predecessor). Source: the authoritative record; only Governance REGISTERs '
        . 'assignments. Registered lanes and their process labels: ' . describeLanes($candidates)
        . '. Responsible next actor: governance.';
} elseif ($verdict === 'AMBIGUOUS') {
    $unresolvedMessage = 'AMBIGUOUS — more than one lane references the current process label; NONE is chosen. '
        . 'Disambiguate with --session=<lane> and/or --work-item=<id>, or ask Governance which assignment '
        . 'applies. Responsible next actor: governance.';
} elseif ($verdict === 'UNRESOLVABLE') {
    $unresolvedMessage = 'UNRESOLVABLE — the bootstrap cannot determine workflow state. Reasons: '
        . implode('; ', $reasons) . '. Responsible next actor: governance.';
}

$report = [
    'verdict' => $verdict,
    'operable' => $verdict === 'RESOLVED' && $selected !== null && $selected['state'] === 'ACTIVE',
    'identity' => $identityBlock,
    'assignment' => $assignmentBlock,
    'activation_prerequisites' => $activationBlock,
    'grant' => $grantBlock,
    'mutation_owner' => $ownerBlock,
    'gates' => $gatesBlock,
    'continuation' => $continuationBlock,
    'meta' => [
        'record_directory' => $recordDir,
        'work_items_scanned' => $readableRecords,
        'candidates' => $candidates,
        'disambiguation_required' => $verdict === 'AMBIGUOUS'
            ? 'Specify --session=<lane> and/or --work-item=<id> to select exactly one lane: '
                . describeLanes($ambiguousMatches) // V-3: ONLY the actual matches, never every candidate
            : null,
        'unresolved_message' => $unresolvedMessage,
        'resolution_source' => 'AST-015 fold/identity/authorized via subprocess (AMENDMENT 2) + the single '
            . 'V-3 handoff read (open finding V-3; AST-017 bounded exception).',
        'interpreter' => [
            'path' => $mechanism,
            'available' => $mechanismAvailable,
            'is_default' => $mechanism === (__DIR__ . '/workflow-state.php'),
            'note' => 'Identity is reported, not judged (C-1) — the bootstrap makes no legitimacy assessment '
                . 'of the interpreter and refuses nothing on its account.',
        ],
        'read_only_guarantee' => true,
        'reasons' => $reasons,
        'caveat' => CAVEAT,
    ],
];

// ─── output ─────────────────────────────────────────────────────────────────

if ($asJson) {
    fwrite(STDOUT, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    exit(0);
}

fwrite(STDOUT, "Session Bootstrap & Responsibility Resolution\n");
fwrite(STDOUT, str_repeat('=', 60) . "\n");
fwrite(STDOUT, "verdict:  {$verdict}\n");
fwrite(STDOUT, 'operable: ' . ($report['operable'] ? 'true' : 'false') . "\n");
fwrite(STDOUT, 'interpreter: ' . $mechanism
    . ($mechanismAvailable ? '' : ' (unavailable)')
    . ($report['meta']['interpreter']['is_default'] ? ' [default AST-015]' : ' [substituted]') . "\n");
if ($verdict === 'RESOLVED' && $selected !== null) {
    fwrite(STDOUT, "lane:     {$selected['work_item']} :: {$selected['lane']} [{$selected['role']}] = {$selected['state']}\n");
    fwrite(STDOUT, "identity: attribution={$identityBlock['attribution']}"
        . " · referenced={" . implode(',', $identityBlock['process_labels_referenced']) . "}\n");
    fwrite(STDOUT, "owner:    " . ($ownerBlock['session'] ?? 'null')
        . ($ownerBlock['is_this_lane'] ? ' (this lane)' : '') . "\n");
    fwrite(STDOUT, 'authorized_to_act: ' . ($gatesBlock['authorized_to_act'] ? 'true' : 'false')
        . ' · human_decision_required: ' . ($gatesBlock['human_decision_required'] ? 'true' : 'false') . "\n");
    foreach ($activationBlock['missing_for_start'] as $m) {
        fwrite(STDOUT, "  missing for start: {$m}\n");
    }
    fwrite(STDOUT, '  recommended next actor: ' . $continuationBlock['recommended_next_actor']['role']
        . ' — ' . $continuationBlock['recommended_next_actor']['reason'] . "\n");
} else {
    fwrite(STDOUT, 'candidates: ' . (count($candidates)) . "\n");
    foreach ($candidates as $c) {
        $labels = processLabelsReferenced((string) ($c['execution_context'] ?? ''));
        fwrite(STDOUT, "  · {$c['work_item']} :: {$c['lane']} [{$c['role']}] = {$c['state']}"
            . ($labels === [] ? '' : ' {' . implode(', ', $labels) . '}') . "\n");
    }
    if ($unresolvedMessage !== null) {
        fwrite(STDOUT, "  unresolved: {$unresolvedMessage}\n");
    }
}
foreach ($reasons as $r) {
    fwrite(STDOUT, "  reason: {$r}\n");
}
fwrite(STDOUT, str_repeat('-', 60) . "\n" . CAVEAT . "\n");
exit(0);
