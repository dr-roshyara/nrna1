<?php
/**
 * activate-commissioned-fresh-session.php — ActivateCommissionedFreshSession (AST-019).
 * KOS-OPERATING-MODEL-001 · AMENDMENT-001 · CMP-004's FIFTH implementation asset.
 * Domain concept: BindRuntimeToRequestedResponsibility (commission §16).
 *
 * HUMAN GIVES BUSINESS ORDER → GOVERNANCE ENGINEER LISTENS → ANALYZES HONESTLY
 * → DETERMINES LAWFUL EXECUTION PATH → CURRENT/FRESH SESSION BINDS ITS REAL
 * RUNTIME IDENTITY TO THE REQUESTED RESPONSIBILITY → CANONICAL WORKFLOW
 * MECHANICS → GOVERNANCE ENGINEER ACTIVE → HUMAN ORDER EXECUTED.
 *
 * Unified binding model (PO/ARB 2026-08-22):
 *   HUMAN declares intended responsibility · RUNTIME declares process identity
 *   (CLAUDE_CODE_SESSION_ID env ONLY, never a CLI argument) · this capability
 *   binds the two through the canonical mechanism.
 *   Invariant: a fresh session may self-bind identity; it may never self-choose
 *   role, scope, work item, or authority. The role comes from the authoritative
 *   commission (AST-018 next-actor) or the human business order (first binding),
 *   never from the prompt alone — prompt ≠ commission → MISMATCH → STOP.
 *
 * The ONLY allowed write: REGISTER { session = own runtime identity, role =
 * commissioned role } → governed HANDOFF → human START (G-3), all through
 * AST-015 `append` (the sole workflow writer). The capability NEVER writes a
 * continuation (Inv E); a STOPPED work item is an honest blocker, never a
 * bypass.
 *
 * Mechanism discipline (mirrors AST-016/017/018): every workflow fact is
 * obtained by invoking the qualified mechanism as a subprocess — AST-015
 * (fold/append) is the sole interpreter and sole writer; AST-017
 * (session-bootstrap) supplies the read-only fresh-session declaration;
 * AST-018 (next-actor) supplies the read-only authoritative commission. No
 * second fold, no raw-record read, no raw-record write, no store-path
 * knowledge. `--dir` is forwarded verbatim.
 *
 * Usage:
 *   php ... activate --work-item=<id> --requested-role=<role>
 *        [--human-act=<verbatim human business instruction>]
 *        [--exclude=<barred identity>]... [--dir=<base>] [--json] [--show-mechanics]
 *   php ... check    --work-item=<id> --requested-role=<role>
 *        [--human-act=<verbatim>] [--exclude=<barred identity>]...
 *        [--dir=<base>] [--json]
 *
 * The runtime identity is NEVER a CLI argument (GO-02): any --session/--identity
 * option is rejected by the generic unknown-option path. `--human-act` is the
 * recorded human business instruction (G-3) required for any write; without it
 * the capability fails closed. `check` is the read-only validation sibling of
 * `activate` (the vehicle GO-23/24/25 use without mutating state).
 *
 * Exit codes: 0 activated / produced check report · 64 usage error · 65
 * refused / INCOMPLETE_SEQUENCE.
 *
 * Contract pinned by tests/Unit/Platform/WorkflowEngine/
 * ActivateCommissionedFreshSessionContractTest.php (GO-01..GO-25).
 */

declare(strict_types=1);

const EX_USAGE = 64;
const EX_REFUSED = 65;

const MECHANISM = __DIR__ . '/workflow-state.php';
const BOOTSTRAP = __DIR__ . '/session-bootstrap.php';
const NEXT_ACTOR = __DIR__ . '/next-actor-orchestration.php';

const CAVEAT = 'Activation is not adoption, and no authority is claimed; '
    . 'adoption and authorization remain human decisions.';

function usage(string $msg): never
{
    fwrite(STDERR, "usage error: {$msg}\n");
    exit(EX_USAGE);
}

/** @return array{code:int,out:?array,raw:string,err:string} */
function run(array $cmd): array
{
    $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    if (!is_resource($proc)) {
        return ['code' => EX_REFUSED, 'out' => null, 'raw' => '', 'err' => 'a governed subprocess could not be invoked'];
    }
    $raw = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    return ['code' => proc_close($proc), 'out' => json_decode($raw, true), 'raw' => $raw, 'err' => trim($err)];
}

function mechanismPath(): string
{
    return getenv('KOS_MECHANISM_PATH') ?: MECHANISM;
}

/** AST-015 adapter — the ONLY workflow interpreter and writer (--dir forwarded). */
function mechanism(array $args, ?string $dir): array
{
    $argv = array_merge(['php', mechanismPath()], $args);
    if ($dir !== null) {
        $argv[] = '--dir=' . $dir;
    }

    return run($argv);
}

/**
 * The authoritative fold — never a local reconstruction. Returns the fold, or a
 * fail-closed refusal token naming what the qualified mechanism reported.
 */
function foldOf(string $wi, ?string $dir): array
{
    $mech = mechanismPath();
    if (!is_file($mech)) {
        return ['ok' => false, 'reason' => 'MECHANISM_UNAVAILABLE', 'detail' => $mech];
    }
    $r = mechanism(['fold', $wi], $dir);
    if ($r['code'] === 0 && is_array($r['out'])) {
        return ['ok' => true, 'fold' => $r['out']];
    }
    $err = $r['err'];
    if (str_contains($err, 'no record exists')) {
        return ['ok' => false, 'reason' => 'WORK_ITEM_UNKNOWN', 'detail' => $err];
    }

    return ['ok' => false, 'reason' => 'WORK_ITEM_UNREADABLE', 'detail' => $err === '' ? 'mechanism refused without detail' : $err];
}

/** @return array{ok:bool,err:string,seq:?int} */
function appendTransition(string $wi, array $transition, ?string $dir): array
{
    $r = mechanism(['append', $wi, '--json=' . json_encode($transition)], $dir);

    return ['ok' => $r['code'] === 0, 'err' => $r['err'], 'seq' => $r['out']['seq'] ?? null];
}

/** AST-018 next-actor — the authoritative commission, consumed read-only. */
function commissionOf(string $wi, ?string $dir): array
{
    $argv = ['php', NEXT_ACTOR, 'next-actor', $wi];
    if ($dir !== null) {
        $argv[] = '--dir=' . $dir;
    }
    $argv[] = '--json';
    $r = run($argv);
    if ($r['code'] !== 0 || !is_array($r['out'])) {
        return ['ok' => false, 'detail' => $r['err']];
    }

    return ['ok' => true, 'decision' => $r['out']];
}

/** AST-017 fresh-session declaration — read-only, consumed for the eligibility fact. */
function freshDeclaration(string $wi, string $identity, ?string $dir): array
{
    $argv = ['php', BOOTSTRAP];
    if ($dir !== null) {
        $argv[] = '--dir=' . $dir;
    }
    $argv[] = '--json';
    $argv[] = '--work-item=' . $wi;
    $argv[] = '--process-label=' . $identity;
    $r = run($argv);
    if ($r['code'] !== 0 || !is_array($r['out'])) {
        return ['ok' => false];
    }

    return ['ok' => true, 'verdict' => $r['out']['verdict'] ?? 'UNRESOLVABLE'];
}

// ─── domain · fail-closed analysis over recorded facts ───────────────────────

/** @return array{ready:bool,exit:int,payload:array} a structured refusal */
function refusal(string $wi, string $reason, string $missing, string $why, string $who, array $options): array
{
    return [
        'ready' => false,
        'exit' => EX_REFUSED,
        'payload' => [
            'result' => 'REFUSED',
            'reason' => $reason,
            'transitionWritten' => false,
            'workItem' => $wi,
            'whatIsMissing' => $missing,
            'whyItMatters' => $why,
            'businessExplanation' => $why,
            'whoMustActNext' => $who,
            'humanOptions' => $options,
            'caveat' => CAVEAT,
        ],
    ];
}

/** @return array{ready:bool,exit:int,payload:array} an honest mid-sequence report */
function incompleteSequence(string $wi, array $written, string $missing, string $err): array
{
    return [
        'ready' => false,
        'exit' => EX_REFUSED,
        'payload' => [
            'result' => 'INCOMPLETE_SEQUENCE',
            'transitionWritten' => true,
            'written' => $written,
            'workItem' => $wi,
            'whatIsMissing' => $missing . ($err === '' ? '' : ' — ' . $err),
            'whyItMatters' => 'The record is append-only; the part already written cannot be undone. '
                . 'Governance must resolve it explicitly.',
            'whoMustActNext' => 'governance',
            'humanOptions' => ['Escalate to governance', 'Stop'],
            'caveat' => CAVEAT,
        ],
    ];
}

/**
 * The constrained binding analysis. Every conjunct must hold; any failure is a
 * structured refusal with nothing written. Validation order V1 → V2 → V3 → V5a
 * → V5b → commission resolution (V4/V6/V7) → V8 — eligibility (V5a) runs BEFORE
 * commission resolution so a barred identity is NOT_ELIGIBLE (GO-12), never a
 * conflicting-assignment, and role-declaredness (V3) runs before the commission
 * so an undeclared role is ROLE_UNDECLARED (GO-06).
 */
function analyze(string $wi, string $role, ?string $humanAct, array $excludes, ?string $dir): array
{
    // V1 — runtime identity, evidence the runtime declares (INV-ATTR-2).
    $identity = getenv('CLAUDE_CODE_SESSION_ID');
    if ($identity === false || $identity === '') {
        return refusal($wi, 'NO_RUNTIME_IDENTITY', 'the runtime process identity',
            'A session has no governed identity to bind until the runtime mechanism (CLAUDE_CODE_SESSION_ID) '
                . 'supplies one. The identity is evidence the runtime declares; it is never passed as an argument.',
            'the human, who must start the session through a runtime that supplies its identity',
            ['Start a fresh governed session', 'Stop']);
    }

    // V2 — the work item must exist; absence never implies ownership.
    $f = foldOf($wi, $dir);
    if (!$f['ok']) {
        if ($f['reason'] === 'MECHANISM_UNAVAILABLE') {
            return refusal($wi, 'MECHANISM_UNAVAILABLE', 'the qualified workflow mechanism',
                "The qualified workflow mechanism could not be reached at {$f['detail']} — this capability "
                    . 'cannot determine workflow state independently, so it fails closed.',
                'governance', ['Restore the mechanism', 'Escalate to governance', 'Stop']);
        }
        if ($f['reason'] === 'WORK_ITEM_UNKNOWN') {
            return refusal($wi, 'WORK_ITEM_UNKNOWN', 'an authoritative workflow record for this work item',
                'No workflow record exists for this work item. Absence never implies ownership — an unrecorded '
                    . 'work item is ungoverned, and binding a session to it would manufacture an assignment '
                    . 'nobody commissioned.',
                'governance, which must register the work item first', ['Ask governance to create the record', 'Stop']);
        }
        return refusal($wi, 'WORK_ITEM_UNREADABLE', 'a readable authoritative workflow record for this work item',
            'The qualified mechanism reported the record unreadable or corrupt. This capability never repairs or '
                . 'hand-derives a record — the fold refuses, so nothing was written.',
            'governance', ['Escalate to governance', 'Stop']);
    }
    $fold = $f['fold'];

    // V3 — the requested role must be one the work item declares.
    if (!in_array($role, $fold['roles'] ?? [], true)) {
        return refusal($wi, 'ROLE_UNDECLARED', "the role '{$role}'",
            'A workflow declares its roles when it is created. Binding to an undeclared role would corrupt the '
                . 'assignment model; the role must be one the work item declares.',
            'the human, who must choose a declared role', ['Choose a declared role', 'Stop']);
    }

    // V5a — eligibility / independence: the identity must hold no lane on this
    // work item and must not be human-declared excluded.
    $foldKeys = array_keys($fold['sessions'] ?? []);
    $exclusionSet = array_values(array_unique(array_merge($foldKeys, $excludes)));
    if (in_array($identity, $foldKeys, true) || in_array($identity, $excludes, true)) {
        return refusal($wi, 'NOT_ELIGIBLE', 'independence — the identity already holds a lane on this work item or is excluded',
            'An identity that already holds a lane on this work item — or is excluded — cannot bind again. '
                . 'Separation of duties (producer ≠ verifier ≠ adoption reviewer) is preserved; the role is '
                . 'immutable once recorded.',
            'the human, who must start a genuinely fresh session for the responsibility',
            ['Start a fresh session', 'Stop']);
    }

    // V5b — AST-017 fresh-session declaration (read-only, authoritative).
    $decl = freshDeclaration($wi, $identity, $dir);
    if (!$decl['ok']) {
        return refusal($wi, 'MECHANISM_UNAVAILABLE', 'the fresh-session declaration',
            'The fresh-session declaration could not be obtained from the qualified bootstrap — this '
                . 'capability cannot confirm eligibility, so it fails closed.',
            'governance', ['Restore the mechanism', 'Escalate to governance', 'Stop']);
    }
    if ($decl['verdict'] === 'RESOLVED') {
        return refusal($wi, 'NOT_ELIGIBLE', 'a fresh, unattributed identity',
            'The qualified bootstrap already attributes this identity to a lane — a fresh session binds only '
                . 'when the declaration is unattributed (UNRESOLVED).',
            'the human, who must start a genuinely fresh session', ['Start a fresh session', 'Stop']);
    }
    if ($decl['verdict'] === 'AMBIGUOUS') {
        return refusal($wi, 'AMBIGUOUS_COMMISSION', 'a single attribution',
            'The qualified bootstrap reports the identity attributable to more than one lane — none is '
                . 'chosen, and this capability will not pick one.',
            'governance', ['Escalate to governance', 'Stop']);
    }
    if ($decl['verdict'] === 'UNRESOLVABLE') {
        return refusal($wi, 'MECHANISM_UNAVAILABLE', 'a readable authoritative record',
            'The qualified bootstrap cannot determine workflow state — this capability cannot confirm '
                . 'eligibility, so it fails closed.',
            'governance', ['Escalate to governance', 'Stop']);
    }

    // Commission resolution — the authoritative next step from AST-018 (V4/V6/V7).
    $c = commissionOf($wi, $dir);
    if (!$c['ok']) {
        return refusal($wi, 'MECHANISM_UNAVAILABLE', 'the authoritative commission',
            'The authoritative commission could not be obtained from the next-actor mechanism — this '
                . 'capability never guesses the next step.',
            'governance', ['Restore the mechanism', 'Escalate to governance', 'Stop']);
    }
    $decision = $c['decision'];
    $result = $decision['result'] ?? 'AMBIGUOUS';
    $commissionRole = $decision['role'] ?? null;
    $options = $decision['options'] ?? [];

    $commissionSource = null;

    if ($result === 'NEXT_ACTOR_REQUIRED') {
        $commissionSource = 'NEXT_ACTOR_REQUIRED';
        if ($role !== $commissionRole) {
            return refusal($wi, 'MISMATCH', 'a requested role that matches the authoritative commission',
                'The requested role does not match the commission the governed mechanism reports. '
                    . 'Prompt ≠ commission → stop; the role is never self-chosen.',
                'the human, who must either request the commissioned role or let governance record a different commission',
                ['Request the commissioned role', 'Ask governance', 'Stop']);
        }
    } elseif ($result === 'HUMAN_DECISION_REQUIRED') {
        if (in_array('DECIDE', $options, true)) {
            return refusal($wi, 'ADOPTION_IS_HUMAN_DECISION', 'a human adoption decision',
                'The governance work is complete; the decision itself belongs to a person. Nothing in this '
                    . 'capability may turn that decision into a fresh binding.',
                'the human (PO/ARB)', ['Decide adoption', 'Stop']);
        }
        // First-binding: no workflow-derived role — the commission is the human
        // business order itself. The requested role must be NAMED in that order
        // (the mechanical encoding of prompt≠commission for the first session).
        $commissionSource = 'HUMAN_BUSINESS_ORDER';
        if ($humanAct === null || trim($humanAct) === '') {
            return refusal($wi, 'HUMAN_DECISION_REQUIRED', 'a recorded human instruction',
                'No actor has been assigned to this work item yet, and no human instruction was provided — '
                    . 'which responsibility should begin is a business decision the system will not make.',
                'the human', ['Say which responsibility to bind, in your own words', 'Stop']);
        }
        if (!preg_match('/\b' . preg_quote($role, '/') . '\b/i', $humanAct)) {
            return refusal($wi, 'MISMATCH', 'a human instruction that names the requested responsibility',
                'The recorded human instruction does not name the requested responsibility. Prompt ≠ order → '
                    . 'stop; a first binding must come from the human business order, never from the prompt alone.',
                'the human', ['Say which responsibility to bind, in your own words', 'Stop']);
        }
    } elseif ($result === 'WORK_ITEM_STOPPED') {
        return refusal($wi, 'WORK_ITEM_STOPPED', 'a continuation decision by a person or governance',
            'The work item is stopped. Only an explicit continuation recorded by governance or the human can '
                . 'reopen it — this capability never writes a continuation itself, so nothing was written.',
            'the human, who decides to continue or leave it stopped', ['Continue the work item', 'Leave it stopped']);
    } elseif ($result === 'LANE_ACTIVE' || $result === 'ACTIVATION_PENDING') {
        return refusal($wi, 'CONFLICTING_ASSIGNMENT', 'an unassigned, activatable step',
            'The work item already has an assignment that is active or awaiting activation by a person. A '
                . 'fresh session cannot bind over it; activation of the assigned actor is a human act.',
            'the human, who may activate the assigned actor, or governance',
            ['Activate the assigned actor', 'Escalate to governance', 'Stop']);
    } else {
        return refusal($wi, 'AMBIGUOUS_COMMISSION', 'a single authoritative commission',
            'The governed mechanism reports the next step as ambiguous — no single commission is derivable. '
                . 'A person must decide rather than let the system guess.',
            'governance', ['Escalate to governance', 'Stop']);
    }

    // V8 — the recorded human business instruction (G-3) is required for any write.
    if ($humanAct === null || trim($humanAct) === '') {
        return refusal($wi, 'HUMAN_DECISION_REQUIRED', 'a recorded human instruction (--human-act)',
            'Activation requires a genuine human act (G-3). No human instruction was provided — nothing in '
                . 'this capability may stand in for a person saying so.',
            'the human', ['Say which responsibility to bind, in your own words', 'Stop']);
    }

    return [
        'ready' => true,
        'identity' => $identity,
        'role' => $role,
        'commissionSource' => $commissionSource,
        'independentOf' => $exclusionSet,
        'humanAct' => $humanAct,
    ];
}

// ─── the governed write — REGISTER → HANDOFF → START through AST-015 ─────────

/**
 * The ONLY allowed write. Every transition goes through AST-015 `append`; the
 * outcome is verified from the authoritative fold, never assumed. Mid-sequence
 * refusal is honestly reported as INCOMPLETE_SEQUENCE (what was written + who
 * acts next).
 *
 * @return array{ok:bool,written:array,payload?:array,exit?:int}
 */
function writeActivation(array $a, string $wi, string $humanAct, ?string $dir): array
{
    $identity = $a['identity'];
    $role = $a['role'];
    $fold = $a['fold'];
    $owner = $fold['mutationOwner'] ?? null;
    $written = [];

    $context = strtoupper($role) . ' of ' . $wi . '. '
        . 'COMMISSIONED BY ' . $a['commissionSource'] . ' on the recorded human business order: ' . $humanAct
        . ' IDENTITY, SELF-DECLARED AND NOT ATTESTABLE (INV-ATTR-2): claude-code-session:' . $identity
        . ' (runtime CLAUDE_CODE_SESSION_ID, self-declared; bound by this governed bootstrap)'
        . ' INDEPENDENCE: this identity holds no lane on ' . $wi . ' and is not excluded.'
        . ' BOUND THROUGH the canonical mechanism (AST-015 append); the human never operates workflow mechanics.';

    $r = appendTransition($wi, [
        'type' => 'REGISTER',
        'session' => $identity,
        'role' => $role,
        'predecessor' => $owner,
        'executionContext' => $context,
        'recordedBy' => 'governance',
    ], $dir);
    if (!$r['ok']) {
        return incompleteSequence($wi, $written, 'the assignment could not be recorded', $r['err']);
    }
    $written[] = 'REGISTER';

    $h = appendTransition($wi, [
        'type' => 'HANDOFF',
        'from' => $owner,
        'to' => $identity,
        'token' => 'T-' . $wi . '-' . strtoupper($role),
        'tokenRef' => 'Recorded human business order: ' . $humanAct,
        'recordedBy' => 'governance',
    ], $dir);
    if (!$h['ok']) {
        return incompleteSequence($wi, $written, 'the handoff could not be recorded', $h['err']);
    }
    $written[] = 'HANDOFF';

    $s = appendTransition($wi, [
        'type' => 'START',
        'session' => $identity,
        'humanAct' => $humanAct,
        'recordedBy' => 'human',
    ], $dir);
    if (!$s['ok']) {
        return incompleteSequence($wi, $written, 'the activation could not be recorded', $s['err']);
    }
    $written[] = 'START';

    // Verify the outcome from the authoritative fold, never assume it.
    $after = foldOf($wi, $dir);
    if (!$after['ok'] || ($after['fold']['sessions'][$identity]['state'] ?? null) !== 'ACTIVE'
        || ($after['fold']['mutationOwner'] ?? null) !== $identity) {
        return incompleteSequence($wi, $written, 'the recorded state could not be verified as ACTIVE',
            'the authoritative fold does not report this session as the active mutation owner');
    }

    return ['ok' => true, 'written' => $written, 'after' => $after['fold']];
}

// ─── presentation · business language by default ─────────────────────────────

function renderHuman(array $payload, bool $showMechanics): string
{
    $out = [];
    $line = static function (string $s = '') use (&$out) { $out[] = $s; };

    if (($payload['result'] ?? '') === 'ACTIVATED') {
        $line('WHAT HAPPENED');
        $line('    The ' . $payload['role'] . ' responsibility is now active for this session.');
        $line();
        $line('WHY IT WAS ALLOWED');
        $line('    ' . ($payload['commissionSource'] === 'HUMAN_BUSINESS_ORDER'
            ? 'The recorded human instruction names this responsibility, and no actor had been assigned yet.'
            : 'The next governed step for this work item is this responsibility, and the recorded human instruction activates it.'));
        $line();
        $line('WHAT HAPPENS NEXT');
        $line('    You may now perform the ' . $payload['role'] . ' work. When it is complete, report your evidence and stop.');
        $line();
        $line('NOTE');
        $line('    ' . $payload['caveat']);
    } elseif (($payload['result'] ?? '') === 'READY') {
        $line('READY');
        $line('    The ' . $payload['role'] . ' responsibility for this work item may be activated.');
        $line();
        $line('NOTHING WAS WRITTEN');
        $line('    This is a read-only check. Re-run with the activate command to bind the responsibility.');
    } else {
        $line('NOT POSSIBLE');
        $line();
        $line('WHY');
        $line('    ' . ($payload['businessExplanation'] ?? $payload['whyItMatters'] ?? $payload['result']));
        $line();
        $line('WHO MUST ACT NEXT');
        $line('    ' . ($payload['whoMustActNext'] ?? 'governance'));
        $line();
        $line('WHAT YOU CAN CHOOSE');
        foreach ($payload['humanOptions'] ?? [] as $opt) {
            $line('    - ' . $opt);
        }
        if (($payload['transitionWritten'] ?? false) === true) {
            $line();
            $line('NOTE');
            $line('    The record is append-only; the part already written cannot be undone.');
        }
    }

    if ($showMechanics) {
        $out[] = '';
        $out[] = '── technical detail (requested) ──';
        $out[] = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    return implode("\n", $out) . "\n";
}

function render(array $payload, int $exit, bool $asJson, bool $showMechanics): never
{
    fwrite(STDOUT, $asJson
        ? json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
        : renderHuman($payload, $showMechanics));
    exit($exit);
}

// ─── main ───────────────────────────────────────────────────────────────────

$argvRest = array_slice($argv, 1);
$asJson = false;
$showMechanics = false;
$dir = null;
$command = null;
$workItem = null;
$role = null;
$humanAct = null;
$excludes = [];

foreach ($argvRest as $arg) {
    if ($arg === '--json') {
        $asJson = true;
        continue;
    }
    if ($arg === '--show-mechanics') {
        $showMechanics = true;
        continue;
    }
    if (!str_starts_with($arg, '--')) {
        if ($command !== null || ($arg !== 'activate' && $arg !== 'check')) {
            usage("unrecognized argument '{$arg}'");
        }
        $command = $arg;
        continue;
    }
    if (!str_contains($arg, '=')) {
        usage("unrecognized argument '{$arg}'");
    }
    [$k, $v] = explode('=', substr($arg, 2), 2);
    if ($k === 'dir') {
        $dir = $v;
        continue;
    }
    if ($k === 'exclude') {
        $excludes[] = $v;
        continue;
    }
    if ($k === 'work-item') {
        $workItem = $v;
        continue;
    }
    if ($k === 'requested-role') {
        $role = $v;
        continue;
    }
    if ($k === 'human-act') {
        $humanAct = $v;
        continue;
    }
    // the runtime identity is NEVER a CLI argument (GO-02)
    usage("unknown option '--{$k}'");
}

if ($command === null) {
    usage('missing command (activate | check)');
}
if ($workItem === null) {
    usage('missing --work-item');
}
if (preg_match('/[^A-Za-z0-9._-]/', $workItem)) {
    usage('work-item id may contain only [A-Za-z0-9._-]');
}
if ($role === null || $role === '') {
    usage('missing --requested-role');
}
if ($humanAct !== null) {
    $humanAct = trim($humanAct);
}

$analysis = analyze($workItem, $role, $humanAct, $excludes, $dir);

if ($command === 'check') {
    // read-only validation sibling: any produced report exits 0 (GO-23/24/25).
    if ($analysis['ready']) {
        render([
            'result' => 'READY',
            'transitionWritten' => false,
            'workItem' => $workItem,
            'session' => $analysis['identity'],
            'role' => $role,
            'commissionSource' => $analysis['commissionSource'],
            'independentOf' => $analysis['independentOf'],
            'caveat' => CAVEAT,
        ], 0, $asJson, $showMechanics);
    }
    render($analysis['payload'], 0, $asJson, $showMechanics);
}

if (!$analysis['ready']) {
    render($analysis['payload'], $analysis['exit'], $asJson, $showMechanics);
}

$written = writeActivation($analysis, $workItem, $humanAct, $dir);
if (!$written['ok']) {
    render($written['payload'], $written['exit'], $asJson, $showMechanics);
}

render([
    'result' => 'ACTIVATED',
    'transitionWritten' => true,
    'session' => $analysis['identity'],
    'role' => $role,
    'commissionSource' => $analysis['commissionSource'],
    'independentOf' => $analysis['independentOf'],
    'transitions' => $written['written'],
    'recordedHumanAct' => $humanAct,
    'workItem' => $workItem,
    'state' => 'ACTIVE',
    'caveat' => CAVEAT,
], 0, $asJson, $showMechanics);
