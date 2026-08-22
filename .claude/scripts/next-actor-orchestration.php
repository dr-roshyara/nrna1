<?php
/**
 * next-actor-orchestration.php — KOS-NEXT-ACTOR-ORCHESTRATION-001.
 * CMP-004 (workflow_engine) FOURTH implementation asset — AST-018.
 *
 * Next-Actor Orchestration / Business-Language Handoff. Translates a HUMAN
 * business decision into governed workflow mechanics so the human never has to
 * know REGISTER / HANDOFF / START / UUIDs / predecessors / mutation owners /
 * transition JSON.
 *
 * WHAT IT IS NOT: not the authority model · not a second workflow engine · not
 * a session creator · not a replacement for AST-015 or AST-017.
 *
 * Boundary: docs/knowledgeos/reviews/2026-08-22-KOS-NEXT-ACTOR-ORCHESTRATION-001-implementation-prompt.md
 * (verbatim commission) + …-AMENDMENT-001-PrepareNextActorSession.md (Use Case 3).
 * Contract pinned by tests/Unit/Platform/WorkflowEngine/NextActorOrchestrationContractTest.php.
 *
 * Commands:
 *   next-actor           <workItem>   UC1 DetermineNextActorAction        (read-only)
 *   appoint              <workItem>   UC2 AppointReviewer / Option 1      (writes — human act required)
 *   prepare-prompt       <workItem>   PrepareReviewerPrompt / Option 2    (read-only, advisory)
 *   prepare-next-session <workItem>   UC3 PrepareNextActorSession         (read-only, advisory)
 *   stop                 <workItem>   Option 3                            (no state change)
 *
 * Options: --dir=<base> --json --candidate=<id> (repeatable) --role=<role>
 *          --human-act=<verbatim> --scope=<text> --exclude=<id> (repeatable)
 *          --show-mechanics
 *
 * Exit codes: 0 report produced · 64 usage error · 65 REFUSED (no transition).
 *
 * THE SINGLE-AUTHORITY RULE (AST-015): every workflow fact is obtained by
 * invoking workflow-state.php as a subprocess, and every write goes through its
 * `append` command. This file performs NO fold, NO raw-record read, NO raw-record
 * write, and knows NO store path — `--dir` is forwarded verbatim or omitted so
 * AST-015 applies its own default.
 *
 * THE HUMAN-AUTHORITY RULE (G-3): `--human-act` is an INPUT, never an inference.
 * No AI message becomes a humanAct; "Yes" is never assumed; nothing auto-starts.
 *
 * THE IDENTITY RULE (INV-ATTR-1/2): a process identity is never manufactured.
 * Candidates arrive as explicit `--candidate` inputs; the next actor has no
 * identity until it declares one. Independence exclusions are derived from the
 * fold's REGISTERED SESSION KEYS — never by scanning free-text executionContext,
 * which would turn a historical mention into a false assignment.
 */

declare(strict_types=1);

const EX_USAGE = 64;
const EX_REFUSED = 65;

const MECHANISM = __DIR__ . '/workflow-state.php';

const CAVEAT = 'A recommendation is not an appointment; an appointment is not '
    . 'activation. This tool executes governed mechanics only after a recorded '
    . 'human authority act.';

/**
 * The declared next-actor progression. This is a NAMED POLICY, not a silent
 * inference: when the last completed lane's role is not in this table the result
 * is AMBIGUOUS and the human decides.
 */
const PROGRESSION = [
    'architecture' => 'verification',
    'implementation' => 'verification',
    'verification' => 'governance',
    'governance' => null,   // the adoption decision is the human's — no AI successor
];

function usage(string $msg): never
{
    fwrite(STDERR, "usage error: {$msg}\n");
    exit(EX_USAGE);
}

// ═══ PORT · AST-015 adapter — the ONLY workflow interpreter ═════════════════

/** @return array{code:int,out:mixed,raw:string,err:string} */
function mechanism(array $args, ?string $dir): array
{
    $argv = array_merge(['php', MECHANISM], $args);
    if ($dir !== null) {
        $argv[] = '--dir=' . $dir;
    }
    $proc = proc_open($argv, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    if (!is_resource($proc)) {
        usage('the workflow mechanism could not be invoked');
    }
    $raw = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    return ['code' => proc_close($proc), 'out' => json_decode($raw, true), 'raw' => $raw, 'err' => trim($err)];
}

/** The authoritative state — AST-015's fold, never a local reconstruction. */
function foldOf(string $workItem, ?string $dir): array
{
    $r = mechanism(['fold', $workItem], $dir);
    if ($r['code'] !== 0 || !is_array($r['out'])) {
        usage("no authoritative record answers for work item '{$workItem}'"
            . ($r['err'] === '' ? '' : ' — ' . $r['err']));
    }

    return $r['out'];
}

/** @return array{ok:bool,err:string} */
function appendTransition(string $workItem, array $transition, ?string $dir): array
{
    $r = mechanism(['append', $workItem, '--json=' . json_encode($transition)], $dir);

    return ['ok' => $r['code'] === 0, 'err' => $r['err']];
}

// ═══ DOMAIN · pure decisions over recorded facts ════════════════════════════

/** Registered lanes, in registration order, as id => role. */
function lanes(array $fold): array
{
    $out = [];
    foreach ($fold['sessions'] ?? [] as $id => $s) {
        $out[$id] = $s['role'];
    }

    return $out;
}

/**
 * Independence exclusions = every process the record ATTRIBUTES to a lane.
 * Derived from session keys only. A free-text mention of a process inside another
 * lane's executionContext is history, not an assignment, and must never widen
 * this set (it would manufacture false ambiguity).
 */
function exclusionsFrom(array $fold): array
{
    return array_keys($fold['sessions'] ?? []);
}

/** Short, evidence-derived reference — the form the amendment's example uses. */
function shortRef(string $id): string
{
    return substr($id, 0, 8);
}

/** The last-registered lane in a given state. */
function lastLaneInState(array $fold, string $state): ?array
{
    $found = null;
    foreach ($fold['sessions'] ?? [] as $id => $s) {
        if ($s['state'] === $state) {
            $found = ['session' => $id, 'role' => $s['role']];
        }
    }

    return $found;
}

function anyLaneInState(array $fold, string $state): ?array
{
    foreach ($fold['sessions'] ?? [] as $id => $s) {
        if ($s['state'] === $state) {
            return ['session' => $id, 'role' => $s['role']];
        }
    }

    return null;
}

/** UC1's decision — business level, derived only from the fold. */
function determineNextActor(array $fold): array
{
    $wi = $fold['workItem'];

    if (($fold['workItemState'] ?? 'OPEN') === 'STOPPED') {
        return [
            'result' => 'WORK_ITEM_STOPPED',
            'role' => null,
            'freshIndependentActorRequired' => false,
            'reason' => 'The work item is stopped. Only an explicit continuation decision reopens it.',
            'businessExplanation' => "Work on {$wi} was deliberately halted. Nothing may proceed until a "
                . 'person decides to continue it — the system will not restart it on its own.',
            'requiredHumanDecision' => true,
            'options' => ['CONTINUE', 'LEAVE_STOPPED'],
        ];
    }

    if (($fold['sessions'] ?? []) === []) {
        return [
            'result' => 'HUMAN_DECISION_REQUIRED',
            'role' => null,
            'freshIndependentActorRequired' => true,
            'reason' => 'No actor has been assigned to this work item yet.',
            'businessExplanation' => "Nobody is assigned to {$wi}. Which kind of actor should begin is a "
                . 'business decision, so the system will not choose one.',
            'requiredHumanDecision' => true,
            'options' => ['APPOINT', 'DRAFT_PROMPT', 'STOP'],
        ];
    }

    if ($active = anyLaneInState($fold, 'ACTIVE')) {
        return [
            'result' => 'LANE_ACTIVE',
            'role' => $active['role'],
            'freshIndependentActorRequired' => false,
            'reason' => 'An assigned actor is already active and owns the work.',
            'businessExplanation' => 'The ' . $active['role'] . ' actor currently holds this work and simply '
                . 'continues. No new actor is needed and no decision is required.',
            'requiredHumanDecision' => false,
            'options' => ['CONTINUE'],
        ];
    }

    foreach (['CREATED', 'HANDED_OFF'] as $waiting) {
        if ($lane = anyLaneInState($fold, $waiting)) {
            return [
                'result' => 'ACTIVATION_PENDING',
                'role' => $lane['role'],
                'freshIndependentActorRequired' => false,
                'reason' => 'An actor is assigned but has not been activated by a person.',
                'businessExplanation' => 'The ' . $lane['role'] . ' actor is assigned and waiting. Activation '
                    . 'is a human act — the system cannot start it.',
                'requiredHumanDecision' => true,
                'options' => ['ACTIVATE', 'STOP'],
            ];
        }
    }

    $last = lastLaneInState($fold, 'COMPLETED');
    if ($last === null) {
        return [
            'result' => 'AMBIGUOUS',
            'role' => null,
            'freshIndependentActorRequired' => false,
            'reason' => 'Every assigned actor has finished without completing, and no successor is implied.',
            'businessExplanation' => "The assignments on {$wi} ended in states that do not imply who comes "
                . 'next. A person must decide.',
            'requiredHumanDecision' => true,
            'options' => ['APPOINT', 'DRAFT_PROMPT', 'STOP'],
        ];
    }

    $done = $last['role'];
    if (!array_key_exists($done, PROGRESSION)) {
        return [
            'result' => 'AMBIGUOUS',
            'role' => null,
            'freshIndependentActorRequired' => false,
            'reason' => "No declared progression follows a completed '{$done}' assignment.",
            'businessExplanation' => "The {$done} work is finished, but what should follow it is not a "
                . 'settled rule. A person must decide rather than let the system guess.',
            'requiredHumanDecision' => true,
            'options' => ['APPOINT', 'DRAFT_PROMPT', 'STOP'],
        ];
    }

    $next = PROGRESSION[$done];
    if ($next === null) {
        return [
            'result' => 'HUMAN_DECISION_REQUIRED',
            'role' => null,
            'freshIndependentActorRequired' => false,
            'reason' => 'The governance review is complete; the decision itself belongs to a person.',
            'businessExplanation' => "Everything that can be prepared for {$wi} has been prepared. The "
                . 'remaining step is a human decision and cannot be delegated.',
            'requiredHumanDecision' => true,
            'options' => ['DECIDE', 'STOP'],
        ];
    }

    return [
        'result' => 'NEXT_ACTOR_REQUIRED',
        'role' => $next,
        'freshIndependentActorRequired' => true,
        'reason' => ucfirst($done) . ' work is complete, so a fresh independent ' . $next
            . ' actor is the next governed step.',
        'businessExplanation' => 'The ' . $done . ' work on ' . $wi . ' is finished. The next step needs a '
            . $next . " actor who has taken no part in the work so far, so the check is genuinely independent.",
        'requiredHumanDecision' => true,
        'options' => ['APPOINT', 'DRAFT_PROMPT', 'STOP'],
    ];
}

/** Eligibility — a fact about the candidate, never an authorization. */
function eligibilityOf(string $candidate, array $fold, array $extraExclusions): array
{
    $exclusions = array_merge(exclusionsFrom($fold), $extraExclusions);
    if (in_array($candidate, $exclusions, true)) {
        return [
            'eligible' => false,
            'because' => 'the candidate is not independent — the record already attributes a lane on this '
                . 'work item to it, so it would be reviewing work it took part in',
        ];
    }

    return ['eligible' => true, 'because' => 'the candidate holds no lane on this work item'];
}

// ═══ APPLICATION · use cases ═══════════════════════════════════════════════

function refuse(string $result, string $missing, string $why, string $who, array $options, array $extra = []): never
{
    render(array_merge([
        'result' => $result,
        'transitionWritten' => false,
        'whatIsMissing' => $missing,
        'whyItMatters' => $why,
        'whoMustActNext' => $who,
        'humanOptions' => $options,
    ], $extra), EX_REFUSED);
}

/** UC2 — the governed appointment. Pre-flight is total; the log cannot be rolled back. */
function appointReviewer(string $wi, array $opts, ?string $dir): never
{
    $fold = foldOf($wi, $dir);

    if (($fold['workItemState'] ?? 'OPEN') === 'STOPPED') {
        refuse('NO_TRANSITION', 'the work item is stopped',
            'A stopped work item may only be reopened by an explicit human continuation decision.',
            'the person who stopped it, or governance', ['CONTINUE', 'LEAVE_STOPPED']);
    }

    $humanAct = trim((string) ($opts['human-act'] ?? ''));
    if ($humanAct === '') {
        refuse('NO_TRANSITION', 'the human authority act that appoints the actor',
            'Appointing an actor is an authority decision. Nothing in this tool may stand in for a person '
                . 'saying so, and an assistant\'s own message can never be recorded as a human act.',
            'the person who holds authority over this work item (PO/ARB)',
            ['Say who to appoint, in your own words', 'Ask for a prompt instead', 'Stop']);
    }

    $candidates = $opts['candidate'] ?? [];
    if ($candidates === []) {
        refuse('NO_ELIGIBLE_CANDIDATE', 'a candidate actor identity',
            'An actor identity cannot be invented. A fresh actor has no identity until its own process '
                . 'reports one, so there is nothing to appoint yet.',
            'the person, who must start a fresh process so it can declare its identity',
            ['Prepare a kickoff prompt for a fresh actor', 'Stop']);
    }
    if (count($candidates) > 1) {
        refuse('AMBIGUOUS', 'a single candidate — ' . count($candidates) . ' were offered',
            'Choosing between candidates is a business decision. Picking one silently would manufacture an '
                . 'authority act that nobody made.',
            'the person, who must name exactly one candidate',
            ['Name one candidate', 'Stop'], ['candidates' => $candidates]);
    }
    $candidate = $candidates[0];

    $role = (string) ($opts['role'] ?? '');
    if ($role === '') {
        refuse('NO_TRANSITION', 'the role the actor is appointed to',
            'A role is part of the assignment and is immutable once recorded; it cannot be guessed.',
            'the person', ['Name the role', 'Stop']);
    }
    if (!in_array($role, $fold['roles'] ?? [], true)) {
        refuse('NO_TRANSITION', "the role '{$role}' is not one this work item declares",
            'A workflow declares its roles when it is created. Recording an undeclared role would corrupt '
                . 'the assignment model.',
            'the person, who must choose a declared role',
            ['Choose a declared role', 'Stop'], ['declaredRoles' => $fold['roles'] ?? []]);
    }

    if (isset($fold['sessions'][$candidate])) {
        refuse('NOT_ELIGIBLE', 'independence — the candidate already holds a lane on this work item',
            'An actor that took part in the work cannot independently check it. Independence is what makes '
                . 'the later review worth anything.',
            'the person, who must name an actor that has taken no part in this work',
            ['Name an independent candidate', 'Prepare a kickoff prompt for a fresh actor', 'Stop']);
    }

    $eligibility = eligibilityOf($candidate, $fold, $opts['exclude'] ?? []);
    if (!$eligibility['eligible']) {
        refuse('NOT_ELIGIBLE', 'independence — ' . $eligibility['because'],
            'An actor that took part in the work cannot independently check it.',
            'the person, who must name an independent actor',
            ['Name an independent candidate', 'Stop']);
    }

    // ── pre-flight passed · execute the governed sequence ──
    $owner = $fold['mutationOwner'] ?? null;
    $scope = trim((string) ($opts['scope'] ?? '')) ?: ($role . ' work on ' . $wi);
    $token = 'T-' . $wi . '-' . strtoupper($role);
    $written = [];

    $register = appendTransition($wi, [
        'type' => 'REGISTER',
        'session' => $candidate,
        'role' => $role,
        'predecessor' => $owner,
        'executionContext' => strtoupper($role) . ' of ' . $wi . '. SCOPE: ' . $scope
            . '. APPOINTED BY the recorded human act: ' . $humanAct
            // The label must be followed by whitespace, never punctuation: AST-017's
            // extractor accepts `.` inside a label, so a trailing period is captured
            // as part of the id and the lane stops being attributable to its actor.
            . ' IDENTITY, SELF-DECLARED AND NOT ATTESTABLE (INV-ATTR-2): claude-code-session:' . $candidate
            . ' (runtime CLAUDE_CODE_SESSION_ID, self-declared)'
            . '. INDEPENDENCE: ' . $eligibility['because'] . '.',
        'recordedBy' => 'governance',
    ], $dir);
    if (!$register['ok']) {
        refuse('NO_TRANSITION', 'the assignment could not be recorded: ' . $register['err'],
            'The governed mechanism refused the assignment, so nothing was written.',
            'governance', ['Review the refusal', 'Stop']);
    }
    $written[] = 'REGISTER';

    $handoff = appendTransition($wi, [
        'type' => 'HANDOFF',
        'from' => $owner,
        'to' => $candidate,
        'token' => $token,
        'tokenRef' => 'Recorded human authority act: ' . $humanAct,
        'recordedBy' => 'governance',
    ], $dir);
    if (!$handoff['ok']) {
        render([
            'result' => 'INCOMPLETE_SEQUENCE',
            'transitionWritten' => true,
            'written' => $written,
            'whatIsMissing' => 'the handoff could not be recorded: ' . $handoff['err'],
            'whyItMatters' => 'The assignment exists but the work was not passed to it. The record is '
                . 'append-only, so this cannot be undone — governance must resolve it explicitly.',
            'whoMustActNext' => 'governance',
            'humanOptions' => ['Escalate to governance'],
        ], EX_REFUSED);
    }
    $written[] = 'HANDOFF';

    $start = appendTransition($wi, [
        'type' => 'START',
        'session' => $candidate,
        'humanAct' => $humanAct,
        'recordedBy' => 'human',
    ], $dir);
    if (!$start['ok']) {
        render([
            'result' => 'INCOMPLETE_SEQUENCE',
            'transitionWritten' => true,
            'written' => $written,
            'whatIsMissing' => 'the activation could not be recorded: ' . $start['err'],
            'whyItMatters' => 'The actor is assigned and the work was passed to it, but it is not active.',
            'whoMustActNext' => 'governance',
            'humanOptions' => ['Escalate to governance'],
        ], EX_REFUSED);
    }
    $written[] = 'START';

    // ── verify the outcome from the authoritative fold, never assume it ──
    $after = foldOf($wi, $dir);
    $state = $after['sessions'][$candidate]['state'] ?? 'UNKNOWN';

    render([
        'result' => 'APPOINTED',
        'transitionWritten' => true,
        'role' => $role,
        'session' => $candidate,
        'state' => $state,
        'activationVerified' => $state === 'ACTIVE',
        'businessSummary' => ucfirst($role) . ' actor appointed successfully. Assignment: ' . $scope
            . '. The actor is now active and holds the work.',
        'nextAction' => 'The ' . $role . ' actor performs ' . $scope . ', then stops and reports.',
        'mechanics' => $written,
        'caveat' => CAVEAT,
    ], $state === 'ACTIVE' ? 0 : EX_REFUSED);
}

/** Option 2 — advisory reviewer prompt. Writes nothing. */
function prepareReviewerPrompt(string $wi, array $opts, ?string $dir): never
{
    $fold = foldOf($wi, $dir);
    $decision = determineNextActor($fold);
    $role = (string) ($opts['role'] ?? $decision['role'] ?? 'verification');
    $candidates = $opts['candidate'] ?? [];
    $scope = trim((string) ($opts['scope'] ?? '')) ?: ($role . ' of ' . $wi);
    $exclusions = array_map('shortRef', array_merge(exclusionsFrom($fold), $opts['exclude'] ?? []));

    $lines = [];
    $lines[] = 'You are the ' . $role . ' actor for ' . $wi . '.';
    $lines[] = '';
    $lines[] = 'SCOPE';
    $lines[] = '    ' . $scope;
    $lines[] = '';
    $lines[] = 'WHY YOU ARE NEEDED';
    $lines[] = '    ' . $decision['reason'];
    $lines[] = '';
    $lines[] = 'INDEPENDENCE — you must not be any of:';
    foreach ($exclusions as $e) {
        $lines[] = '    - ' . $e;
    }
    $lines[] = '';
    $lines[] = 'CURRENT STATE';
    foreach (lanes($fold) as $id => $laneRole) {
        $lines[] = '    ' . shortRef($id) . ' [' . $laneRole . '] = ' . $fold['sessions'][$id]['state'];
    }
    $lines[] = '';
    $lines[] = 'YOUR TASK';
    $lines[] = '    Perform the ' . $role . ' work named above, and nothing else.';
    $lines[] = '    Report evidence. Never accept your own work.';
    $lines[] = '';
    $lines[] = 'STOP when the ' . $role . ' work is reported.';

    render([
        'result' => 'REVIEWER_PROMPT_PREPARED',
        'transitionWritten' => false,
        'role' => $role,
        'candidates' => $candidates,
        'prompt' => implode("\n", $lines),
        'advisory' => true,
        'caveat' => 'This branch is advisory: nobody has been appointed and nothing has been recorded.',
    ], 0);
}

/**
 * UC3 (AMENDMENT-001) — PrepareNextActorSession.
 *
 * Produces the exact prompt a human pastes into a NEW process, built from
 * governed facts. It does NOT create a session and NEVER invents an identity:
 * a spawned subagent inherits its parent's CLAUDE_CODE_SESSION_ID, so the fresh
 * actor exists only once a person starts a new process and it declares itself.
 */
function prepareNextActorSession(string $wi, array $opts, ?string $dir): never
{
    $fold = foldOf($wi, $dir);
    $decision = determineNextActor($fold);

    /*
     * A kickoff prompt is only meaningful when a FRESH actor is actually the next
     * governed step. If a lane still holds the work — or is assigned and awaiting
     * activation, or the item is stopped — emitting a prompt would invite the human
     * to start a process for a role that is already held. Fail closed and say who
     * must act instead.
     */
    if (in_array($decision['result'], ['LANE_ACTIVE', 'ACTIVATION_PENDING', 'WORK_ITEM_STOPPED'], true)) {
        render([
            'result' => 'NO_FRESH_ACTOR_REQUIRED',
            'transitionWritten' => false,
            'freshProcessRequired' => false,
            'prompt' => null,
            'whatIsMissing' => 'nothing — a fresh actor is not the next step yet ('
                . strtolower(str_replace('_', ' ', $decision['result'])) . ')',
            'whyItMatters' => 'Preparing a kickoff prompt now would invite you to start a new process for '
                . 'work that is already assigned. ' . $decision['businessExplanation'],
            'whoMustActNext' => $decision['requiredHumanDecision']
                ? 'you — see the options below'
                : 'the actor that currently holds the work',
            'humanOptions' => $decision['options'],
            'caveat' => 'Nothing was recorded.',
        ], 0);
    }

    $role = (string) ($opts['role'] ?? $decision['role'] ?? '');

    $exclusions = array_values(array_unique(array_map(
        'shortRef', array_merge(exclusionsFrom($fold), $opts['exclude'] ?? [])
    )));

    $currentState = [];
    foreach (lanes($fold) as $id => $laneRole) {
        $currentState[] = shortRef($id) . ' [' . $laneRole . '] = ' . $fold['sessions'][$id]['state'];
    }

    $scope = trim((string) ($opts['scope'] ?? ''))
        ?: ($role === '' ? 'to be determined by a person' : $role . ' work on ' . $wi);

    $doFirst = [
        'Determine your actual process identity from the runtime mechanism.',
        'Verify that you are independent of every process listed below.',
        'Read the authoritative governance state.',
        'Report your identity, independence, eligibility and current authorization.',
    ];
    $doNot = [
        'Do not modify code.',
        'Do not create a workflow record.',
        'Do not REGISTER yourself.',
        'Do not HANDOFF.',
        'Do not START.',
    ];
    $stopCondition = 'STOP after the declaration. Implementation is not your first task.';
    $nextGovernedStep = 'After you return your identity, governance continues the appointment: the '
        . 'assignment is recorded, the work is passed to you, and a person activates you. Only then do you work.';

    // ── the kickoff prompt, from governed facts ──
    $p = [];
    $p[] = 'You are the candidate ' . ($role === '' ? '' : $role . ' ') . 'actor for';
    $p[] = '';
    $p[] = '    ' . $wi;
    $p[] = '';
    $p[] = 'Your first task is NOT the work itself.';
    $p[] = '';
    $p[] = 'SCOPE OF THE ASSIGNMENT YOU ARE A CANDIDATE FOR';
    $p[] = '    ' . $scope;
    $p[] = '';
    $p[] = 'WHY THIS ACTOR IS NEEDED';
    $p[] = '    ' . $decision['reason'];
    $p[] = '';
    $p[] = 'CURRENT STATE OF THE WORK ITEM';
    foreach ($currentState as $line) {
        $p[] = '    ' . $line;
    }
    $p[] = '';
    $p[] = 'WHAT TO DO FIRST';
    foreach ($doFirst as $i => $step) {
        $p[] = '    ' . ($i + 1) . '. ' . $step;
    }
    $p[] = '';
    $p[] = 'INDEPENDENCE — verify that you are none of:';
    if ($exclusions === []) {
        $p[] = '    (no process holds a lane on this work item yet)';
    }
    foreach ($exclusions as $e) {
        $p[] = '    - ' . $e;
    }
    $p[] = '';
    $p[] = 'REPORT';
    $p[] = '    PROCESS IDENTITY:';
    $p[] = '    INDEPENDENCE:';
    $p[] = '    ELIGIBILITY:';
    $p[] = '    CURRENT AUTHORIZATION:';
    $p[] = '';
    $p[] = 'WHAT YOU MUST NOT DO';
    foreach ($doNot as $prohibition) {
        $p[] = '    ' . $prohibition;
    }
    $p[] = '';
    $p[] = $stopCondition;

    render([
        'result' => 'KICKOFF_PROMPT_PREPARED',
        'transitionWritten' => false,
        'role' => $role === '' ? null : $role,
        'freshProcessRequired' => true,
        'prompt' => implode("\n", $p),
        'facts' => [
            'workItem' => $wi,
            'role' => $role === '' ? null : $role,
            'scope' => $scope,
            'whyNeeded' => $decision['reason'],
            'independenceExclusions' => $exclusions,
            'currentState' => $currentState,
            'doFirst' => $doFirst,
            'doNot' => $doNot,
            'stopCondition' => $stopCondition,
            'nextGovernedStep' => $nextGovernedStep,
            'candidateIdentity' => null,
        ],
        'humanOptions' => [
            '1. Yes — use this prompt for the new session.',
            '2. Show the prompt and explain what it will do.',
            '3. Stop.',
        ],
        'caveat' => 'This prepares instructions only. No process was created, no identity was assigned, '
            . 'nothing was recorded. A person starts the new process.',
    ], 0);
}

// ═══ PRESENTATION · business language by default ════════════════════════════

function renderHuman(array $payload, bool $showMechanics): string
{
    $out = [];
    $line = static function (string $s = '') use (&$out) { $out[] = $s; };

    switch ($payload['result']) {
        case 'NEXT_ACTOR_REQUIRED':
            $line('WHAT HAPPENED');
            $line('    ' . $payload['reason']);
            $line();
            $line('WHAT IS NEEDED');
            $line('    A fresh independent ' . $payload['role'] . ' actor.');
            $line();
            $line('WHY');
            $line('    ' . $payload['businessExplanation']);
            $line();
            $line('YOUR DECISION IS REQUIRED');
            $line('    1. Yes — appoint one now, and handle the governed setup for me.');
            $line('    2. Write the prompt for the new actor instead.');
            $line('    3. Stop.');
            $line();
            $line('AFTER YOU CHOOSE');
            $line('    Option 1 assigns the actor and activates it on your say-so.');
            $line('    Option 2 only drafts instructions — nothing is assigned.');
            $line('    Option 3 changes nothing.');
            break;

        case 'KICKOFF_PROMPT_PREPARED':
            $line('NEXT ACTOR');
            $line('    A fresh independent ' . ($payload['role'] ?? '') . ' process is required.');
            $line();
            $line('WHAT YOU NEED TO DO');
            $line('    Open a new session and paste the prompt below into it.');
            $line();
            $line('────────────────────────────────────');
            foreach (explode("\n", $payload['prompt']) as $l) {
                $line($l);
            }
            $line('────────────────────────────────────');
            $line();
            $line('YOUR OPTIONS');
            foreach ($payload['humanOptions'] as $opt) {
                $line('    ' . $opt);
            }
            $line();
            $line('AFTER THE NEW SESSION REPORTS ITS IDENTITY');
            $line('    ' . $payload['facts']['nextGovernedStep']);
            break;

        case 'APPOINTED':
            $line('DONE');
            $line('    ' . $payload['businessSummary']);
            $line();
            $line('NEXT');
            $line('    ' . $payload['nextAction']);
            break;

        case 'REVIEWER_PROMPT_PREPARED':
            $line('PROMPT PREPARED — nobody has been appointed.');
            $line();
            foreach (explode("\n", $payload['prompt']) as $l) {
                $line($l);
            }
            break;

        default:
            $line(str_replace('_', ' ', $payload['result']));
            $line();
            if (isset($payload['whatIsMissing'])) {
                $line('WHAT IS MISSING');
                $line('    ' . $payload['whatIsMissing']);
                $line();
                $line('WHY IT MATTERS');
                $line('    ' . $payload['whyItMatters']);
                $line();
                $line('WHO MUST ACT NEXT');
                $line('    ' . $payload['whoMustActNext']);
                $line();
                $line('WHAT YOU CAN CHOOSE');
                foreach ($payload['humanOptions'] as $opt) {
                    $line('    - ' . $opt);
                }
            } else {
                $line('    ' . ($payload['businessExplanation'] ?? ''));
                if (!empty($payload['options'])) {
                    $line();
                    $line('OPTIONS');
                    foreach ($payload['options'] as $i => $opt) {
                        $line('    ' . ($i + 1) . '. ' . $opt);
                    }
                }
            }
            break;
    }

    if ($showMechanics) {
        $out[] = '';
        $out[] = '── technical detail (requested) ──';
        $out[] = 'governed sequence: REGISTER -> HANDOFF -> START (human act required at START)';
        $out[] = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    return implode("\n", $out) . "\n";
}

function render(array $payload, int $exit): never
{
    global $AS_JSON, $SHOW_MECHANICS;
    fwrite(STDOUT, $AS_JSON
        ? json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
        : renderHuman($payload, $SHOW_MECHANICS));
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
$opts = ['candidate' => [], 'exclude' => []];
$dir = null;

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
    if ($k === 'candidate' || $k === 'exclude') {
        $opts[$k][] = $v;
        continue;
    }
    if (!in_array($k, ['role', 'human-act', 'scope'], true)) {
        usage("unknown option '--{$k}'");
    }
    $opts[$k] = $v;
}

switch ($command) {
    case 'next-actor':
        $fold = foldOf($workItem, $dir);
        $decision = determineNextActor($fold);
        render(array_merge(['workItem' => $workItem], $decision, [
            'transitionWritten' => false,
            'caveat' => CAVEAT,
        ]), 0);

    case 'appoint':
        appointReviewer($workItem, $opts, $dir);

    case 'prepare-prompt':
        prepareReviewerPrompt($workItem, $opts, $dir);

    case 'prepare-next-session':
        prepareNextActorSession($workItem, $opts, $dir);

    case 'stop':
        foldOf($workItem, $dir);   // confirm the work item exists; change nothing
        render([
            'result' => 'STOPPED_BY_HUMAN_CHOICE',
            'transitionWritten' => false,
            'businessExplanation' => 'Nothing was changed. No actor was appointed and no state moved.',
            'options' => [],
        ], 0);

    default:
        usage("unknown command '{$command}'");
}
