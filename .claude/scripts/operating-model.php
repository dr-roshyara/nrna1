<?php

/**
 * operating-model.php — Operating Model (KOS-OPERATING-MODEL-001 · CMP-004).
 *
 * The minimal supporting capability of the FINAL GOVERNANCE + COMMUNICATION
 * OPERATING MODEL: a thin, READ-ONLY presenter that renders the exactly-one
 * business outcome (§11) and the six human cases (§29) in business language,
 * over the canonical mechanisms — it consumes, never replaces, never re-opens:
 *
 *   AST-018  next-actor-orchestration.php  — next-actor decision, appointment,
 *                                             kickoff-prompt preparation
 *   AST-017  session-bootstrap.php         — process identity / attribution /
 *                                             eligibility / gates
 *   AST-015  workflow-state.php            — transitively, via the two above
 *
 * Commands:
 *   outcome <workItem>   exactly one of the five business outcomes (§11):
 *                        CONTINUE / PERMISSION_REQUIRED / FRESH_SESSION_REQUIRED /
 *                        GOVERNANCE_DECISION_REQUIRED / STOP — rendered from the
 *                        AST-018 next-actor decision (+ the §29 case text).
 *   session <workItem>   the fresh-session presentation (§18–§20): MATCH / MISMATCH /
 *                        CANDIDATE / governance escalation, from the AST-017
 *                        bootstrap verdict + the AST-018 next-actor decision.
 *
 * Core contract (§1, §29, §30): the human speaks BUSINESS LANGUAGE; the human
 * never operates workflow mechanics. There is NO write path here — every write
 * (appointment, prompt preparation, stop) is an AST-018 command invoked by the
 * human or the Communication Engineer per the operating-model document.
 *
 * D-3: this script knows no store path, performs no fold, reads no raw record,
 * and invokes the mechanisms only as subprocesses. `--dir` is forwarded verbatim
 * when the caller supplies it; otherwise the mechanisms' own defaults apply.
 *
 * Work item: KOS-OPERATING-MODEL-001 · commissioned prompt (verbatim):
 * docs/knowledgeos/reviews/2026-08-22-KOS-OPERATING-MODEL-001-implementation-prompt.md
 */

declare(strict_types=1);

const CAVEAT = 'The operating model is a read-only presentation over the canonical '
    . 'mechanisms (AST-015 via AST-017/AST-018). Nothing was recorded by this command.';

/** Exit 64 = usage error (mirrors the mechanism estate). */
function usage(string $message): never
{
    fwrite(STDERR, "usage: php .claude/scripts/operating-model.php <outcome|session> <workItem> "
        . "[--dir=<records>] [--process-label=<label>] [--json] [--show-mechanics]\n"
        . $message . "\n");
    exit(64);
}

/** Run a canonical mechanism as a subprocess; return its JSON payload. */
function runPhp(string $script, ?string $dir, array $args): array
{
    $cmd = array_merge(['php', $script], $args);
    if ($dir !== null) {
        $cmd[] = '--dir=' . $dir;
    }
    $cmd[] = '--json';
    $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    $raw = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $code = proc_close($proc);

    return ['code' => $code, 'json' => json_decode($raw, true), 'raw' => $raw, 'err' => $err];
}

function nextActor(string $wi, ?string $dir): array
{
    return runPhp(__DIR__ . '/next-actor-orchestration.php', $dir, ['next-actor', $wi]);
}

function prepareNextSession(string $wi, ?string $dir): array
{
    return runPhp(__DIR__ . '/next-actor-orchestration.php', $dir, ['prepare-next-session', $wi]);
}

function sessionBootstrap(string $wi, ?string $label, ?string $dir): array
{
    $args = ['--work-item=' . $wi];
    if ($label !== null && $label !== '') {
        $args[] = '--process-label=' . $label;
    }

    return runPhp(__DIR__ . '/session-bootstrap.php', $dir, $args);
}

/** AST-018 option tokens → business-language phrases (never a second vocabulary). */
function renderOptions(array $tokens): array
{
    $map = [
        'APPOINT' => 'Yes — appoint an actor',
        'DRAFT_PROMPT' => 'Write the prompt instead',
        'STOP' => 'Stop',
        'CONTINUE' => 'Yes — continue the work',
        'LEAVE_STOPPED' => 'Leave it stopped',
        'ACTIVATE' => 'Yes — activate',
        'DECIDE' => 'Decide',
    ];

    return array_map(
        static fn (string $t): string => $map[$t] ?? str_replace('_', ' ', strtolower($t)),
        $tokens
    );
}

// ═══ outcome — exactly one of the five business outcomes (§11–§17) ═══════════

function cmdOutcome(string $wi, ?string $dir): never
{
    $decision = nextActor($wi, $dir);
    if ($decision['code'] !== 0) {
        render(['command' => 'outcome', 'workItem' => $wi, 'outcome' => 'GOVERNANCE_DECISION_REQUIRED',
            'case' => 5, 'message' => 'A Governance decision is required.', 'transitionWritten' => false,
            'caveat' => 'The canonical mechanism refused: ' . trim($decision['err'] !== '' ? $decision['err'] : $decision['raw'])], 65);
    }
    $d = $decision['json'] ?? [];
    $result = $d['result'] ?? '';

    $out = [
        'command' => 'outcome',
        'workItem' => $wi,
        'decision' => $d,
        'transitionWritten' => false,
        'requiredHumanDecision' => (bool) ($d['requiredHumanDecision'] ?? false),
        'caveat' => CAVEAT,
    ];

    switch ($result) {
        case 'LANE_ACTIVE':
            $out['outcome'] = 'CONTINUE';
            $out['case'] = 1;
            $out['message'] = 'I can perform the next step.';
            break;

        case 'ACTIVATION_PENDING':
            $out['outcome'] = 'PERMISSION_REQUIRED';
            $out['case'] = 2;
            $out['message'] = 'I can perform the next step, but I need your permission.';
            break;

        case 'NEXT_ACTOR_REQUIRED':
            $out['outcome'] = 'FRESH_SESSION_REQUIRED';
            $out['case'] = 3;
            $out['message'] = 'The next step requires a fresh session.';
            $prompt = prepareNextSession($wi, $dir);
            if (($prompt['json']['result'] ?? '') === 'KICKOFF_PROMPT_PREPARED') {
                $out['prompt'] = $prompt['json']['prompt'] ?? null;
            }
            break;

        case 'HUMAN_DECISION_REQUIRED':
            if (in_array('DECIDE', $d['options'] ?? [], true)) {
                // Governance review complete — the remaining step is the human's (CASE 6).
                $out['outcome'] = 'GOVERNANCE_DECISION_REQUIRED';
                $out['case'] = 6;
                $out['message'] = 'The work is ready for adoption.';
                $out['humanOptions'] = ['Accept / Adopt', 'Return', 'Stop'];
            } else {
                $out['outcome'] = 'GOVERNANCE_DECISION_REQUIRED';
                $out['case'] = 5;
                $out['message'] = 'A Governance decision is required.';
                $out['humanOptions'] = renderOptions($d['options'] ?? []);
            }
            break;

        case 'AMBIGUOUS':
            $out['outcome'] = 'GOVERNANCE_DECISION_REQUIRED';
            $out['case'] = 5;
            $out['message'] = 'A Governance decision is required.';
            $out['humanOptions'] = renderOptions($d['options'] ?? []);
            break;

        case 'WORK_ITEM_STOPPED':
            $out['outcome'] = 'STOP';
            $out['case'] = null; // §35 stop model — not a §29 case
            $out['message'] = $d['businessExplanation'] ?? 'The work is stopped.';
            $out['reason'] = $d['reason'] ?? '';
            $out['whoMustActNext'] = 'you — the person who stopped it, or governance';
            $out['humanOptions'] = renderOptions($d['options'] ?? []);
            break;

        default:
            $out['outcome'] = 'GOVERNANCE_DECISION_REQUIRED';
            $out['case'] = 5;
            $out['message'] = 'A Governance decision is required.';
            break;
    }

    render($out, 0);
}

// ═══ session — MATCH / MISMATCH / CANDIDATE / escalation (§18–§20) ═══════════

/**
 * Classify the session situation from reported facts ONLY (D-4): the AST-017
 * bootstrap verdict/attribution plus the AST-018 next-actor decision. Nothing
 * here is derived — everything is mapped from the mechanisms' outputs.
 *
 * @return array{0:string,1:string,2:int|null,3:string,4:?array}
 */
function classifySession(array $report, array $decision): array
{
    $verdict = $report['verdict'] ?? '';
    $attribution = $report['identity']['attribution'] ?? null;
    $state = $report['assignment']['workflow_state'] ?? null;
    $authorized = (bool) ($report['gates']['authorized_to_act'] ?? false);
    $result = $decision['result'] ?? '';
    $options = $decision['options'] ?? [];

    if ($verdict === 'RESOLVED') {
        if ($attribution === 'MATCH') {
            if ($state === 'ACTIVE' && $authorized) {
                return ['MATCH', 'CONTINUE', 1, 'This is the assigned session. You may continue.', null];
            }
            // Assigned but not yet activated — the human permission is the next step (G-3).
            return ['MATCH', 'PERMISSION_REQUIRED', 2,
                'This is the assigned session. I can perform the next step, but I need your permission.', null];
        }
        // Attribution MISMATCH (a lane forced via --session that this process is not).
        return ['MISMATCH', 'STOP', 4, 'This session is not assigned to this work.', recoveryOptions()];
    }

    if ($verdict === 'AMBIGUOUS' || $verdict === 'UNRESOLVABLE') {
        return ['GOVERNANCE_DECISION_REQUIRED', 'GOVERNANCE_DECISION_REQUIRED', 5,
            'A Governance decision is required.', null];
    }

    // UNRESOLVED — no lane attributes this process.
    if (in_array($result, ['LANE_ACTIVE', 'ACTIVATION_PENDING'], true)) {
        // Someone already holds (or is assigned) this work — this is not that session.
        return ['MISMATCH', 'STOP', 4, 'This session is not assigned to this work.', recoveryOptions()];
    }
    if ($result === 'HUMAN_DECISION_REQUIRED') {
        if (in_array('DECIDE', $options, true)) {
            return ['GOVERNANCE_DECISION_REQUIRED', 'GOVERNANCE_DECISION_REQUIRED', 6,
                'The work is ready for adoption.', null];
        }
        // No existing appointment. Candidate status is NEVER inferred here: it rests on
        // authoritative eligibility/appointment facts that follow identity declaration.
        return ['GOVERNANCE_DECISION_REQUIRED', 'GOVERNANCE_DECISION_REQUIRED', 5,
            'A fresh independent actor is required for this work, and this session is not assigned to it. '
            . 'Appointment follows identity declaration and eligibility review.', null];
    }
    if ($result === 'NEXT_ACTOR_REQUIRED') {
        // The WORK needs a fresh independent actor — a fact about the work, never a claim
        // that this session is the eligible candidate (§20: no inference from UNRESOLVED).
        $role = (string) ($decision['role'] ?? 'actor');
        return ['GOVERNANCE_DECISION_REQUIRED', 'GOVERNANCE_DECISION_REQUIRED', 5,
            'The next step needs a fresh independent ' . $role
            . ' actor. This session is not assigned to it — the appointment is a Governance decision '
            . 'after the fresh session declares its identity.', null];
    }

    return ['GOVERNANCE_DECISION_REQUIRED', 'GOVERNANCE_DECISION_REQUIRED', 5,
        'A Governance decision is required.', null];
}

function recoveryOptions(): array
{
    return ['Open the assigned session.', 'Prepare the exact prompt for this work.', 'Stop.'];
}

function cmdSession(string $wi, ?string $dir, ?string $label): never
{
    if ($label === null || $label === '') {
        $uuid = getenv('CLAUDE_CODE_SESSION_ID');
        $label = ($uuid !== false && $uuid !== '') ? $uuid : null;
    }

    $boot = sessionBootstrap($wi, $label, $dir);
    if ($boot['code'] !== 0) {
        render(['command' => 'session', 'workItem' => $wi, 'situation' => 'UNRESOLVABLE',
            'outcome' => 'GOVERNANCE_DECISION_REQUIRED', 'case' => 5,
            'message' => 'A Governance decision is required.', 'transitionWritten' => false,
            'caveat' => 'The canonical mechanism refused: ' . trim($boot['err'] !== '' ? $boot['err'] : $boot['raw'])], 65);
    }
    $report = $boot['json'] ?? [];
    $decision = nextActor($wi, $dir)['json'] ?? [];

    [$situation, $outcome, $case, $message, $recovery] = classifySession($report, $decision);

    $out = [
        'command' => 'session',
        'workItem' => $wi,
        'situation' => $situation,
        'outcome' => $outcome,
        'case' => $case,
        'message' => $message,
        'bootstrap' => $report,
        'decision' => $decision,
        'transitionWritten' => false,
        'caveat' => CAVEAT,
    ];
    if ($recovery !== null) {
        $out['recoveryOptions'] = $recovery;
    }

    render($out, 0);
}

// ═══ presentation — business language by default (D-8) ═══════════════════════

function renderHuman(array $p): string
{
    global $SHOW_MECHANICS;
    $out = [];
    $line = static function (string $s = '') use (&$out): void { $out[] = $s; };
    $situation = $p['situation'] ?? '';
    $decision = $p['decision'] ?? [];

    $line($p['message'] ?? '');

    if ($situation === 'MISMATCH') {
        $line();
        $line('WHAT YOU CAN DO');
        foreach ($p['recoveryOptions'] ?? [] as $i => $opt) {
            $line('    ' . ($i + 1) . '. ' . $opt);
        }
    } else {
        switch ($p['outcome'] ?? '') {
            case 'PERMISSION_REQUIRED':
                $line('1. Yes');
                $line('2. Write a prompt');
                break;

            case 'FRESH_SESSION_REQUIRED':
                // FRESH_SESSION_REQUIRED = prepare the prompt + start a fresh session.
                // No appointment options: the future process identity is not yet known (§16).
                $line();
                $line('Start a new session and paste this prompt:');
                $line('────────────────────────────────────');
                foreach (explode("\n", $p['prompt'] ?? '') as $l) {
                    $line($l);
                }
                $line('────────────────────────────────────');
                $line('You may use this prompt unchanged or edit it.');
                break;

            case 'GOVERNANCE_DECISION_REQUIRED':
                $line();
                $line('WHY');
                $line('    ' . ($decision['businessExplanation'] ?? ''));
                if (($p['case'] ?? null) === 6) {
                    $line();
                    $line('1. Accept / Adopt');
                    $line('2. Return');
                    $line('3. Stop');
                } else {
                    $line();
                    $line('YOUR OPTIONS');
                    foreach ($p['humanOptions'] ?? [] as $i => $opt) {
                        $line('    ' . ($i + 1) . '. ' . $opt);
                    }
                }
                break;

            case 'STOP':
                $line();
                $line('WHY');
                $line('    ' . ($decision['businessExplanation'] ?? ''));
                $line();
                $line('WHO MUST ACT NEXT');
                $line('    ' . ($p['whoMustActNext'] ?? ''));
                $line();
                $line('WHAT YOU CAN CHOOSE');
                foreach ($p['humanOptions'] ?? [] as $i => $opt) {
                    $line('    ' . ($i + 1) . '. ' . $opt);
                }
                break;
        }
    }

    if ($SHOW_MECHANICS) {
        $out[] = '';
        $out[] = '── technical detail (requested) ──';
        $out[] = 'governed sequence: REGISTER -> HANDOFF -> START (human act required at START)';
        $out[] = json_encode($p, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    return implode("\n", $out) . "\n";
}

function render(array $payload, int $exit): never
{
    global $AS_JSON;
    fwrite(STDOUT, $AS_JSON
        ? json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
        : renderHuman($payload));
    exit($exit);
}

// ═══ MAIN ═══════════════════════════════════════════════════════════════════

$argvRest = array_slice($argv, 1);
$command = $argvRest[0] ?? usage('missing command');
$workItem = $argvRest[1] ?? usage('missing work-item id');
if (preg_match('/[^A-Za-z0-9._-]/', $workItem)) {
    usage('work-item id may contain only [A-Za-z0-9._-]');
}

$AS_JSON = false;
$SHOW_MECHANICS = false;
$dir = null;
$label = null;

foreach (array_slice($argvRest, 2) as $arg) {
    if ($arg === '--json') {
        $AS_JSON = true;
        continue;
    }
    if ($arg === '--show-mechanics') {
        $SHOW_MECHANICS = true;
        continue;
    }
    if (!str_starts_with($arg, '--') || !str_contains($arg, '=')) {
        usage("unrecognized argument '{$arg}'");
    }
    [$k, $v] = explode('=', substr($arg, 2), 2);
    if ($k === 'dir') {
        $dir = $v;
        continue;
    }
    if ($k === 'process-label') {
        $label = $v;
        continue;
    }
    usage("unknown option '--{$k}'");
}

if ($command === 'outcome') {
    cmdOutcome($workItem, $dir);
}
if ($command === 'session') {
    cmdSession($workItem, $dir, $label);
}

usage("unknown command '{$command}'");
