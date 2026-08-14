<?php
/**
 * workflow-state.php — KOS-AI-ORCH-001 Increment 1 reference implementation.
 * CMP-004 (workflow_engine) first implementation asset — AST-015.
 *
 * Authoritative per-work-item workflow state record: one JSON document per
 * governed work item, TWO never-merged records (sessionRegistry ≠
 * authorityState), append-only, current state = the FOLD of the transition
 * log. An illegal write is a REFUSED transition (exit 65, nothing appended) —
 * the fold refuses; nothing is physically prevented (Increment 2 is not
 * authorized: no lock, no lease, no hook, no enforcement).
 *
 * Boundary: docs/publicdigit/reviews/2026-08-14-KOS-AI-ORCH-001-implementation-boundary-proposal.md
 * (HUMAN-APPROVED WITH R8, D-2). Contract pinned by
 * tests/Unit/Platform/WorkflowEngine/WorkflowStateRecordContractTest.php (R1–R8).
 *
 * Usage:
 *   php workflow-state.php init  <workItem> --workflow=<id> --roles=r1,r2 --dir=<base>
 *   php workflow-state.php append <workItem> --json='{"type":"REGISTER",...}' --dir=<base>
 *   php workflow-state.php grant  <workItem> --writer-role=<role> --json='{...}' --dir=<base>
 *   php workflow-state.php fold  <workItem> --dir=<base>
 *   php workflow-state.php identity <workItem> --session=<id> --dir=<base>
 *   php workflow-state.php authorized <workItem> --session=<id> --scope=<s> --dir=<base>
 *
 * Default --dir is .claude/runtime/workflow relative to the repository root.
 * Exit codes: 0 accepted · 65 refused by the contract · 64 usage error.
 *
 * The engine exposes queries and contains NO decision logic (Inv I):
 * preconditions check that recorded facts exist; they never supply them.
 */

declare(strict_types=1);

const EX_USAGE = 64;
const EX_REFUSED = 65;

const SESSION_STATES = ['CREATED', 'ACTIVE', 'HANDED_OFF', 'COMPLETED', 'STOPPED', 'FAILED', 'CANCELLED'];
const GRANT_STATES = ['PROPOSED', 'AUTHORIZED', 'CONSUMED', 'CLOSED', 'REVOKED'];

function usage(string $msg): never
{
    fwrite(STDERR, "usage error: {$msg}\n");
    exit(EX_USAGE);
}

function refuse(string $msg): never
{
    fwrite(STDERR, "refused: {$msg}\n");
    exit(EX_REFUSED);
}

function emit(mixed $payload): never
{
    fwrite(STDOUT, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    exit(0);
}

/** @return array{cmd:string, workItem:string, opts:array<string,string>} */
function parseArgv(array $argv): array
{
    array_shift($argv);
    $cmd = $argv[0] ?? usage('missing command');
    $workItem = $argv[1] ?? usage('missing work-item id');
    $opts = [];
    foreach (array_slice($argv, 2) as $arg) {
        if (!str_starts_with($arg, '--') || !str_contains($arg, '=')) {
            usage("unrecognized argument: {$arg}");
        }
        [$k, $v] = explode('=', substr($arg, 2), 2);
        $opts[$k] = $v;
    }
    if (preg_match('/[^A-Za-z0-9._-]/', $workItem)) {
        usage('work-item id may contain only [A-Za-z0-9._-]');
    }

    return ['cmd' => $cmd, 'workItem' => $workItem, 'opts' => $opts];
}

function recordPath(array $opts, string $workItem): string
{
    $dir = $opts['dir'] ?? (dirname(__DIR__) . '/runtime/workflow');

    return rtrim($dir, '/') . '/' . $workItem . '.json';
}

function loadRecord(string $path): array
{
    if (!is_file($path)) {
        refuse("no record exists for this work item ({$path}) — absence never implies ownership (C-1); escalate to Governance");
    }
    $decoded = json_decode((string) file_get_contents($path), true);
    if (!is_array($decoded)) {
        refuse('record unreadable/corrupt — stop and escalate to Governance (no derivable owner)');
    }

    return $decoded;
}

function saveRecord(string $path, array $record): void
{
    if (!is_dir(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }
    $tmp = $path . '.tmp.' . getmypid();
    file_put_contents($tmp, json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    rename($tmp, $path);
}

/**
 * The FOLD: derive current state from the append-only transition log.
 * Session Registry only — authorityState is folded separately (never merged).
 */
function foldSessions(array $record): array
{
    $sessions = [];
    $owner = null;
    $itemState = 'OPEN';
    $handoffsTo = []; // sessionId => true (a recorded handoff exists towards it)

    foreach ($record['transitions'] as $t) {
        switch ($t['type']) {
            case 'REGISTER':
                $sessions[$t['session']] = [
                    'role' => $t['role'],
                    'predecessor' => $t['predecessor'] ?? null,
                    'executionContext' => $t['executionContext'],
                    'state' => 'CREATED',
                ];
                break;
            case 'HANDOFF':
                if ($t['from'] !== null && isset($sessions[$t['from']])) {
                    $sessions[$t['from']]['state'] = 'HANDED_OFF';
                }
                $owner = null; // held for the successor; ownership passes only at START
                $handoffsTo[$t['to']] = true;
                break;
            case 'START':
            case 'CONTINUATION':
                $sessions[$t['session']]['state'] = 'ACTIVE';
                $owner = $t['session'];
                $itemState = 'OPEN';
                break;
            case 'STOP':
                $sessions[$t['session']]['state'] = 'STOPPED';
                $itemState = 'STOPPED';
                break;
            case 'COMPLETE':
                $sessions[$t['session']]['state'] = 'COMPLETED';
                if ($owner === $t['session']) {
                    $owner = null;
                }
                break;
            case 'FAIL':
                $sessions[$t['session']]['state'] = 'FAILED';
                break;
            case 'CANCEL':
                $sessions[$t['session']]['state'] = 'CANCELLED';
                break;
        }
    }

    return [
        'sessions' => $sessions,
        'mutationOwner' => $owner,
        'workItemState' => $itemState,
        'handoffsTo' => $handoffsTo,
    ];
}

/**
 * Contract preconditions — the fold REFUSES an illegal transition.
 * Checks that recorded facts exist; never supplies them (Inv I).
 */
function assertTransitionAllowed(array $record, array $t): void
{
    $fold = foldSessions($record);

    $type = $t['type'] ?? refuse('transition has no type');
    if (($t['recordedBy'] ?? '') === '') {
        refuse('every transition records who recorded it');
    }

    // Inv E / R4 — STOPPED is sticky: the ONLY edge out is explicit CONTINUATION.
    if ($fold['workItemState'] === 'STOPPED' && $type !== 'CONTINUATION') {
        refuse('work item is STOPPED — no transition except an explicit CONTINUATION recorded by Governance/Human exists (Inv E)');
    }

    switch ($type) {
        case 'REGISTER':
            $id = $t['session'] ?? refuse('REGISTER requires a session id');
            if (isset($fold['sessions'][$id])) {
                // R8 — role is immutable per SessionAssignment; a role change is a NEW assignment.
                refuse('session assignment already registered — role is immutable; a role change is a new assignment (R8)');
            }
            $role = $t['role'] ?? refuse('REGISTER requires a role (Inv B)');
            if (!in_array($role, $record['roles'], true)) {
                refuse("role '{$role}' is not in the workflow's declared role set");
            }
            if (($t['executionContext'] ?? '') === '') {
                refuse('REGISTER requires the execution context (Inv B)');
            }
            if (!array_key_exists('predecessor', $t)) {
                refuse('REGISTER requires the predecessor field (Inv B; null is a value)');
            }
            return;

        case 'HANDOFF':
            // Inv D / R3a — a handoff without its token-reference is NOT a transition.
            if (($t['token'] ?? '') === '' || ($t['tokenRef'] ?? '') === '') {
                refuse('a handoff must carry its token AND tokenRef — no token, no handoff (Inv D)');
            }
            $to = $t['to'] ?? refuse('HANDOFF requires a successor');
            if (!isset($fold['sessions'][$to])) {
                refuse('HANDOFF successor is not a registered session');
            }
            $from = $t['from'] ?? null;
            if ($from === null) {
                if ($fold['mutationOwner'] !== null) {
                    refuse('bootstrap handoff (from=null) is only valid while no owner exists');
                }
            } elseif ($fold['mutationOwner'] !== $from) {
                refuse('only the current mutation owner can hand off (Inv C)');
            }
            return;

        case 'START':
            $id = $t['session'] ?? refuse('START requires a session id');
            if (!isset($fold['sessions'][$id])) {
                refuse('START of an unregistered session');
            }
            // Inv F / G-3 — the conjunction, both directions (R3b, R3c):
            if (($t['humanAct'] ?? '') === '') {
                refuse('START requires the recorded human start act — a handoff alone never yields ACTIVE (G-3)');
            }
            if (!isset($fold['handoffsTo'][$id])) {
                refuse('START requires the predecessor\'s recorded handoff (token attached) — a human act alone never yields ACTIVE (G-3)');
            }
            return;

        case 'CONTINUATION':
            $id = $t['session'] ?? refuse('CONTINUATION requires a session id');
            if (!isset($fold['sessions'][$id])) {
                refuse('CONTINUATION of an unregistered session');
            }
            if (!in_array($t['recordedBy'], ['governance', 'human'], true)) {
                refuse('CONTINUATION is recorded by Governance or the Human — no other exit from STOPPED exists (Inv E)');
            }
            return;

        case 'STOP':
            $id = $t['session'] ?? refuse('STOP requires a session id');
            if (!isset($fold['sessions'][$id])) {
                refuse('STOP of an unregistered session');
            }
            if (($t['reason'] ?? '') === '') {
                refuse('STOP requires a recorded reason');
            }
            return;

        case 'COMPLETE':
            if (!in_array($t['recordedBy'], ['governance', 'human'], true)) {
                refuse('closure is a governance act (G-1)');
            }
            return;

        case 'FAIL':
        case 'CANCEL':
            return;

        default:
            // R1 (no CLAIM_OWNERSHIP), R8 (no ROLE_CHANGE) — the machine has no such edges.
            refuse("unknown transition type '{$type}' — ownership and role move only by governed transitions; no such edge exists in the machine");
    }
}

// ─── main ───────────────────────────────────────────────────────────────────

$in = parseArgv($argv);
$path = recordPath($in['opts'], $in['workItem']);

switch ($in['cmd']) {
    case 'init':
        if (is_file($path)) {
            refuse('record already exists — exactly ONE authoritative record per work item (Inv A)');
        }
        $workflow = $in['opts']['workflow'] ?? usage('init requires --workflow');
        $roles = array_values(array_filter(explode(',', $in['opts']['roles'] ?? '')));
        if ($roles === []) {
            usage('init requires --roles (the workflow declares its role set at creation)');
        }
        saveRecord($path, [
            'schema' => 1,
            'workItem' => $in['workItem'],
            'workflow' => $workflow,
            'roles' => $roles,
            'transitions' => [],   // ═ RECORD 1 source — Session Registry (fold)
            'grants' => [],        // ═ RECORD 2 — Authority State (never merged)
        ]);
        emit(['ok' => true]);

        // no fallthrough — emit() exits
    case 'append':
        $record = loadRecord($path);
        $t = json_decode($in['opts']['json'] ?? usage('append requires --json'), true);
        if (!is_array($t)) {
            usage('append --json must be a JSON object');
        }
        assertTransitionAllowed($record, $t);
        $t['seq'] = count($record['transitions']) + 1;
        $record['transitions'][] = $t;
        saveRecord($path, $record);
        emit(['ok' => true, 'seq' => $t['seq']]);

    case 'grant':
        $record = loadRecord($path);
        $writer = $in['opts']['writer-role'] ?? usage('grant requires --writer-role');
        $g = json_decode($in['opts']['json'] ?? usage('grant requires --json'), true);
        if (!is_array($g)) {
            usage('grant --json must be a JSON object');
        }
        // Inv H / G-2 — sole writer, registering a recorded human act:
        if ($writer !== 'governance') {
            refuse('the Authority State has exactly one writer: the Governance role (G-2/R5a)');
        }
        if (($g['humanActRef'] ?? '') === '') {
            refuse('a grant registers a recorded human act by reference — the record never manufactures authority (G-2/R5b)');
        }
        foreach (['grantId', 'status', 'authority', 'scope'] as $required) {
            if (($g[$required] ?? '') === '') {
                refuse("grant requires '{$required}'");
            }
        }
        if (!in_array($g['status'], GRANT_STATES, true)) {
            refuse("unknown grant status '{$g['status']}'");
        }
        $g['registeredBy'] = 'governance';
        $record['grants'][] = $g;
        saveRecord($path, $record);
        emit(['ok' => true]);

    case 'fold':
        $record = loadRecord($path);
        $fold = foldSessions($record);
        emit([
            'workItem' => $record['workItem'],
            'workflow' => $record['workflow'],
            'roles' => $record['roles'],
            'sessions' => $fold['sessions'],
            'mutationOwner' => $fold['mutationOwner'],
            'workItemState' => $fold['workItemState'],
            'grants' => $record['grants'],
        ]);

    case 'identity':
        $record = loadRecord($path);
        $id = $in['opts']['session'] ?? usage('identity requires --session');
        $fold = foldSessions($record);
        $s = $fold['sessions'][$id] ?? refuse('unknown session — identity is answerable only for registered sessions');
        $linkage = null;
        foreach ($record['grants'] as $g) {
            if (($g['status'] ?? '') === 'AUTHORIZED') {
                $linkage = $g['grantId'];   // links to, never contains, authority (Inv B/R6)
            }
        }
        emit([
            'workflow' => $record['workflow'],
            'workItem' => $record['workItem'],
            'role' => $s['role'],
            'predecessor' => $s['predecessor'],
            'state' => $s['state'],
            'authorizationLinkage' => $linkage,
            'executionContext' => $s['executionContext'],
        ]);

    case 'authorized':
        $record = loadRecord($path);
        $id = $in['opts']['session'] ?? usage('authorized requires --session');
        $scope = $in['opts']['scope'] ?? usage('authorized requires --scope');
        $fold = foldSessions($record);
        if (!isset($fold['sessions'][$id])) {
            refuse('unknown session');
        }
        $answer = false;
        foreach ($record['grants'] as $g) {
            if (($g['status'] ?? '') === 'AUTHORIZED' && ($g['scope'] ?? null) === $scope) {
                $answer = true;
            }
        }
        // R6: ACTIVE-with-no-covering-grant is a first-class answer, not an error.
        emit(['authorized' => $answer]);

    default:
        usage("unknown command '{$in['cmd']}'");
}
