<?php

namespace Tests\Unit\Platform\WorkflowEngine;

use PHPUnit\Framework\TestCase;

/**
 * KOS-OPERATING-MODEL-001 — FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL.
 * Contract test, OM-1…OM-36 — the five business outcomes (§11), the six human
 * cases (§29), and the fresh-session MATCH/MISMATCH/candidate presentation
 * (§16–§20, §31), rendered over the canonical mechanisms.
 *
 * Work item: KOS-OPERATING-MODEL-001 · Asset: operating-model.php (CMP-004,
 * minimal supporting capability) · Component: CMP-004 (workflow_engine).
 * Commissioned prompt: docs/knowledgeos/reviews/2026-08-22-KOS-OPERATING-MODEL-001-implementation-prompt.md
 * (§36 — RED → implementation → GREEN, minimum 30 named tests, scoped naming
 * that does not collide with the AST-015/AST-016 suites: this suite uses `om`).
 *
 * Core contract:
 *   The human speaks BUSINESS LANGUAGE; the human never operates workflow
 *   mechanics (§1, §29, §30). The five business outcomes (§11) are a RENDERING
 *   of the existing result model — never a second workflow-state vocabulary.
 *
 * Binding (D-3): ALL workflow interpretation comes from AST-015 via the
 * AST-017/AST-018 subprocesses — no second fold, no raw-record access, no direct
 * store write, no store path. Every write path delegates to AST-018 (human-act
 * required, G-3). The operating model is READ-ONLY.
 *
 * Hermetic: synthetic records in temp dirs, built THROUGH the qualified
 * mechanism; `.claude/runtime/workflow/` is never read or written.
 *
 * RED TODAY by absence: `.claude/scripts/operating-model.php` does not exist.
 */
class OperatingModelContractTest extends TestCase
{
    private const OPERATING_MODEL = '.claude/scripts/operating-model.php';
    private const ORCHESTRATOR = '.claude/scripts/next-actor-orchestration.php';
    private const MECHANISM = '.claude/scripts/workflow-state.php';

    private string $repoRoot;
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repoRoot = \dirname(__DIR__, 4);
        $this->dir = sys_get_temp_dir() . '/kos-opm-' . bin2hex(random_bytes(6));
        mkdir($this->dir, 0777, true);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir . '/*') ?: [] as $f) {
            @unlink($f);
        }
        @rmdir($this->dir);
        parent::tearDown();
    }

    // ─── harness ────────────────────────────────────────────────────────────

    private function requireOperatingModel(): void
    {
        $this->assertFileExists($this->repoRoot . '/' . self::OPERATING_MODEL,
            'Operating Model does not exist yet — RED by absence (§36)');
    }

    /** @return array{code:int,out:?array,raw:string,err:string} */
    private function opm(string $cmd, string $wi, array $args = [], bool $json = true, array $env = []): array
    {
        $argv = array_merge(
            ['php', $this->repoRoot . '/' . self::OPERATING_MODEL, $cmd, $wi, '--dir=' . $this->dir],
            $json ? ['--json'] : [],
            $args
        );
        $proc = proc_open($argv, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null,
            $env === [] ? null : array_merge(getenv(), $env));
        $raw = stream_get_contents($pipes[1]);
        $err = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return ['code' => proc_close($proc), 'out' => json_decode($raw, true), 'raw' => $raw, 'err' => $err];
    }

    private function mechanism(array $args): int
    {
        $cmd = array_merge(['php', $this->repoRoot . '/' . self::MECHANISM], $args, ['--dir=' . $this->dir]);
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        stream_get_contents($pipes[1]);
        $err = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $code = proc_close($proc);
        $this->assertSame(0, $code, 'fixture setup via the qualified mechanism failed: ' . $err);

        return $code;
    }

    /** @return array{code:int,out:?array,raw:string,err:string} */
    private function orchestrate(string $cmd, string $wi, array $args = [], bool $json = true): array
    {
        $argv = array_merge(
            ['php', $this->repoRoot . '/' . self::ORCHESTRATOR, $cmd, $wi, '--dir=' . $this->dir],
            $json ? ['--json'] : [],
            $args
        );
        $proc = proc_open($argv, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $raw = stream_get_contents($pipes[1]);
        $err = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return ['code' => proc_close($proc), 'out' => json_decode($raw, true), 'raw' => $raw, 'err' => $err];
    }

    private function fold(string $wi): array
    {
        $cmd = ['php', $this->repoRoot . '/' . self::MECHANISM, 'fold', $wi, '--dir=' . $this->dir];
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $raw = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);

        return json_decode($raw, true) ?? [];
    }

    private function init(string $wi): void
    {
        $this->mechanism(['init', $wi, '--workflow=platform-capability',
            '--roles=governance,architecture,implementation,verification']);
    }

    /** Register + activate a lane through the qualified mechanism. */
    private function lane(string $wi, string $session, string $role, string $finalState = 'ACTIVE', ?string $from = null): void
    {
        $this->mechanism(['append', $wi, '--json=' . json_encode(['type' => 'REGISTER', 'session' => $session,
            'role' => $role, 'predecessor' => $from,
            'executionContext' => 'claude-code-session:' . $session,
            'recordedBy' => 'governance'])]);
        if ($finalState === 'CREATED') {
            return;
        }
        $this->mechanism(['append', $wi, '--json=' . json_encode(['type' => 'HANDOFF', 'from' => null,
            'to' => $session, 'token' => 'T-' . $session, 'tokenRef' => 'ref', 'recordedBy' => 'governance'])]);
        $this->mechanism(['append', $wi, '--json=' . json_encode(['type' => 'START', 'session' => $session,
            'humanAct' => 'recorded human start act', 'recordedBy' => 'human'])]);
        if ($finalState === 'COMPLETED') {
            $this->mechanism(['append', $wi, '--json=' . json_encode(['type' => 'COMPLETE',
                'session' => $session, 'recordedBy' => 'governance'])]);
        }
        if ($finalState === 'STOPPED') {
            $this->mechanism(['append', $wi, '--json=' . json_encode(['type' => 'STOP',
                'session' => $session, 'reason' => 'r', 'recordedBy' => 'governance'])]);
        }
    }

    private function fingerprint(): array
    {
        $out = [];
        foreach (glob($this->dir . '/*') ?: [] as $f) {
            $out[basename($f)] = hash_file('sha256', $f);
        }
        ksort($out);

        return $out;
    }

    private function source(): string
    {
        return file_get_contents($this->repoRoot . '/' . self::OPERATING_MODEL);
    }

    private function runRaw(array $cmd): string
    {
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $raw = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);

        return $raw;
    }

    // ═══ OM-1 … OM-4 · the human starts through Governance (§2, §5, §6) ═════

    public function test_om_01_human_starts_work_through_governance(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM1');

        $r = $this->opm('outcome', 'WI-OM1');

        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertSame('GOVERNANCE_DECISION_REQUIRED', $r['out']['outcome'],
            'a fresh request with no assignment lands in Governance (intake)');
        $this->assertSame(5, $r['out']['case']);
        $this->assertStringContainsString('Governance decision is required', $r['out']['message']);
        $this->assertFalse($r['out']['transitionWritten']);
    }

    public function test_om_02_governance_determines_architecture_required(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM2');

        // Intake: governance decides the starting role — the human appoints an
        // architecture actor through the canonical mechanism (delegation).
        $appointed = $this->orchestrate('appoint', 'WI-OM2', ['--candidate=S-arch', '--role=architecture',
            '--human-act=PO/ARB: I appoint the architecture actor.']);
        $this->assertSame(0, $appointed['code'], $appointed['err']);

        $r = $this->opm('outcome', 'WI-OM2');
        $this->assertSame('CONTINUE', $r['out']['outcome'], 'the appointed architecture actor is active and continues');
        $this->assertSame(1, $r['out']['case']);
        $this->assertSame('S-arch', $this->fold('WI-OM2')['mutationOwner']);
    }

    public function test_om_03_governance_determines_direct_implementation(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM3');

        // Direct implementation: the human appoints an implementation actor.
        $appointed = $this->orchestrate('appoint', 'WI-OM3', ['--candidate=S-impl', '--role=implementation',
            '--human-act=PO/ARB: I appoint the implementation actor directly.']);
        $this->assertSame(0, $appointed['code'], $appointed['err']);

        $r = $this->opm('outcome', 'WI-OM3');
        $this->assertSame('CONTINUE', $r['out']['outcome']);
        $this->assertSame('implementation', $this->fold('WI-OM3')['sessions']['S-impl']['role']);
    }

    public function test_om_04_role_transition_is_business_language(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM4');
        $this->lane('WI-OM4', 'S-arch', 'architecture', 'COMPLETED');

        $human = $this->opm('outcome', 'WI-OM4', [], false)['raw'];

        $this->assertStringContainsString('The next step requires a fresh session', $human);
        $this->assertStringContainsString('Start a new session and paste this prompt', $human);
        $this->assertStringContainsString('You may use this prompt unchanged or edit it', $human);
        $this->assertStringContainsString('verification', $human, 'the prompt names the role from governed facts');
        $this->assertStringNotContainsString('appoint and activate', $human,
            'FRESH_SESSION_REQUIRED is prepare-prompt + start-fresh-session, never an appointment invitation');
        foreach (['mutationOwner', 'predecessor', 'workflow-state.php', 'transition JSON'] as $mechanic) {
            $this->assertStringNotContainsString($mechanic, $human,
                "the human role-transition contract must never expose '{$mechanic}'");
        }
    }

    // ═══ OM-5 … OM-8 · the five business outcomes (§11–§14) ══════════════════

    public function test_om_05_current_actor_can_continue(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM5');
        $this->lane('WI-OM5', 'S-a', 'implementation', 'ACTIVE');

        $r = $this->opm('outcome', 'WI-OM5');

        $this->assertSame('CONTINUE', $r['out']['outcome']);
        $this->assertSame(1, $r['out']['case']);
        $this->assertStringContainsString('can perform the next step', $r['out']['message']);
        $this->assertFalse($r['out']['requiredHumanDecision'] ?? true);
    }

    public function test_om_06_assigned_actor_needs_permission(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM6');
        $this->lane('WI-OM6', 'S-a', 'verification', 'CREATED');

        $r = $this->opm('outcome', 'WI-OM6');

        $this->assertSame('PERMISSION_REQUIRED', $r['out']['outcome']);
        $this->assertSame(2, $r['out']['case']);
        $this->assertStringContainsString('need your permission', $r['out']['message']);
    }

    public function test_om_07_fresh_session_required(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM7');
        $this->lane('WI-OM7', 'S-arch', 'architecture', 'COMPLETED');

        $r = $this->opm('outcome', 'WI-OM7');

        $this->assertSame('FRESH_SESSION_REQUIRED', $r['out']['outcome']);
        $this->assertSame(3, $r['out']['case']);
        $this->assertStringContainsString('next step requires a fresh session', $r['out']['message']);
        $this->assertSame('verification', $r['out']['decision']['role']);
    }

    public function test_om_08_next_session_prompt_is_generated_and_pasteable(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM8');
        $this->lane('WI-OM8', 'S-arch', 'architecture', 'COMPLETED');

        $r = $this->opm('outcome', 'WI-OM8');

        $this->assertSame('FRESH_SESSION_REQUIRED', $r['out']['outcome']);
        $this->assertNotEmpty($r['out']['prompt'], 'the fresh-session kickoff prompt must be embedded');
        $this->assertStringContainsString('WI-OM8', $r['out']['prompt']);
        $this->assertStringContainsString('verification', $r['out']['prompt']);

        // The paste instruction is the HUMAN-facing rendering (§14, §16) — never the JSON message.
        $human = $this->opm('outcome', 'WI-OM8', [], false)['raw'];
        $this->assertStringContainsString('Start a new session and paste this prompt', $human);
        $this->assertStringContainsString('You may use this prompt unchanged or edit it.', $human);
    }

    // ═══ OM-9 … OM-16 · fresh-session identity & MATCH/MISMATCH (§15–§20) ════

    public function test_om_09_new_session_discovers_runtime_identity(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM9');
        $this->lane('WI-OM9', 'S-OWNER', 'implementation', 'ACTIVE');

        $r = $this->opm('session', 'WI-OM9', ['--process-label=S-OWNER']);

        $this->assertSame('MATCH', $r['out']['situation']);
        $this->assertSame('CONTINUE', $r['out']['outcome']);
        $this->assertStringContainsString('This is the assigned session', $r['out']['message']);
        $this->assertSame('S-OWNER', $r['out']['bootstrap']['identity']['current_process_label']);
    }

    public function test_om_10_fresh_actor_need_is_never_an_eligible_candidate_claim(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM10');
        $this->lane('WI-OM10', 'S-arch', 'architecture', 'COMPLETED'); // verification is the next step

        $r = $this->opm('session', 'WI-OM10', ['--process-label=NEWPROC']);

        $this->assertNotSame('CANDIDATE', $r['out']['situation'],
            'candidate status rests on authoritative eligibility/appointment facts — never inferred '
            . 'from UNRESOLVED + NEXT_ACTOR_REQUIRED');
        $this->assertSame('GOVERNANCE_DECISION_REQUIRED', $r['out']['situation']);
        $this->assertSame('GOVERNANCE_DECISION_REQUIRED', $r['out']['outcome']);
        $this->assertStringNotContainsString('eligible actor is available', $r['out']['message']);
        $this->assertStringContainsString('fresh independent verification actor', $r['out']['message'],
            'the work needs a fresh independent actor — a fact about the work, not a claim about this session');
    }

    public function test_om_11_human_approval_creates_the_appointment(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM11');
        $this->lane('WI-OM11', 'S-arch', 'architecture', 'COMPLETED');

        // The candidate presents; the human says YES — the authority act.
        $appointed = $this->orchestrate('appoint', 'WI-OM11', ['--candidate=S-ver', '--role=verification',
            '--human-act=PO/ARB, verbatim: I appoint S-ver as the independent verifier.']);
        $this->assertSame(0, $appointed['code'], $appointed['err']);
        $this->assertSame('APPOINTED', $appointed['out']['result']);
        $this->assertSame('ACTIVE', $appointed['out']['state']);

        $fold = $this->fold('WI-OM11');
        $this->assertSame('verification', $fold['sessions']['S-ver']['role']);
        $this->assertSame('S-ver', $fold['mutationOwner'], 'human choice 1 IS the authority act — recorded');
    }

    public function test_om_12_existing_appointment_matches(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM12');
        $this->lane('WI-OM12', 'S-a', 'implementation', 'ACTIVE');

        $r = $this->opm('session', 'WI-OM12', ['--process-label=S-a']);

        $this->assertSame('MATCH', $r['out']['situation']);
        $this->assertSame('CONTINUE', $r['out']['outcome']);
        $this->assertStringContainsString('You may continue', $r['out']['message']);
    }

    public function test_om_13_existing_appointment_mismatches(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM13');
        $this->lane('WI-OM13', 'S-a', 'implementation', 'ACTIVE');

        $r = $this->opm('session', 'WI-OM13', ['--process-label=INTRUDER']);

        $this->assertSame('MISMATCH', $r['out']['situation']);
        $this->assertSame('STOP', $r['out']['outcome']);
        $this->assertStringContainsString('This session is not assigned to this work', $r['out']['message']);
        $this->assertCount(3, $r['out']['recoveryOptions'] ?? []);
    }

    public function test_om_14_mismatch_creates_no_duplicate_appointment(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM14');
        $this->lane('WI-OM14', 'S-a', 'implementation', 'ACTIVE');
        $before = $this->fingerprint();

        $r = $this->opm('session', 'WI-OM14', ['--process-label=INTRUDER']);
        $this->assertSame('MISMATCH', $r['out']['situation']);

        // A second actor attempting to appoint itself without a human act must refuse.
        $attempt = $this->orchestrate('appoint', 'WI-OM14', ['--candidate=INTRUDER', '--role=verification']);
        $this->assertSame(65, $attempt['code'], 'no human act → no appointment (G-3)');
        $this->assertSame('NO_TRANSITION', $attempt['out']['result']);

        $this->assertSame($before, $this->fingerprint(), 'no write, no second appointment, byte-unchanged');
        $this->assertCount(1, $this->fold('WI-OM14')['sessions'] ?? [],
            'the mismatch must never create a duplicate appointment');
    }

    public function test_om_15_mismatch_changes_no_workflow_state(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM15');
        $this->lane('WI-OM15', 'S-a', 'implementation', 'ACTIVE');
        $before = $this->fingerprint();

        $this->opm('session', 'WI-OM15', ['--process-label=INTRUDER']);
        $this->opm('outcome', 'WI-OM15');

        $this->assertSame($before, $this->fingerprint(), 'reporting a wrong session never moves workflow state');
    }

    public function test_om_16_no_session_uuid_is_required_from_the_human(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM16');
        $this->lane('WI-OM16', 'S-arch', 'architecture', 'COMPLETED');

        $human = $this->opm('outcome', 'WI-OM16', [], false)['raw'];

        // The human is never asked to handle a session UUID (§15, §30).
        $this->assertDoesNotMatchRegularExpression('/claude-code-session:[0-9a-f-]{36}/', $human,
            'the human must never be handed a session UUID to act on (§30)');
        $this->assertStringNotContainsStringIgnoringCase('your session id', $human);
        $this->assertStringNotContainsStringIgnoringCase('please provide', $human);

        // The business framing (everything before the pasted prompt artifact) is mechanics-free.
        // REGISTER/HANDOFF/START may appear only inside the paste artifact the human relays to the
        // fresh session — the human is never required to know or construct them (§30).
        $delim = '────────────────────────────────────';
        $pos = strpos($human, $delim);
        $framing = $pos === false ? $human : substr($human, 0, $pos);
        foreach (['REGISTER', 'HANDOFF', 'START', 'mutationOwner', 'predecessor', 'transition JSON', 'workflow-state.php'] as $mechanic) {
            $this->assertStringNotContainsString($mechanic, $framing,
                "hard acceptance criterion: the human-facing framing never exposes '{$mechanic}'");
        }
        // Mechanics vocabulary that is not part of the prompt artifact must never appear anywhere.
        foreach (['mutationOwner', 'predecessor', 'transition JSON', 'workflow-state.php'] as $mechanic) {
            $this->assertStringNotContainsString($mechanic, $human);
        }
    }

    // ═══ OM-17 … OM-22 · mechanics are resolved mechanically, never by hand ══

    public function test_om_17_mutation_owner_is_resolved_mechanically(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM17');
        $this->lane('WI-OM17', 'S-OWNER', 'implementation', 'ACTIVE');

        // Poison the raw record: the fold (via AST-015) must win over any decoy.
        $path = $this->dir . '/WI-OM17.json';
        $rec = json_decode((string) file_get_contents($path), true);
        $rec['mutationOwner'] = 'DECOY-OWNER';
        file_put_contents($path, json_encode($rec, JSON_PRETTY_PRINT));

        $r = $this->opm('session', 'WI-OM17', ['--process-label=S-OWNER']);

        $this->assertSame('MATCH', $r['out']['situation']);
        $this->assertSame('S-OWNER', $r['out']['bootstrap']['mutation_owner']['session'],
            'mutation owner is fold-derived through AST-015 — never the raw decoy');
    }

    public function test_om_18_predecessor_is_resolved_mechanically(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM18');
        $this->lane('WI-OM18', 'S-own', 'architecture', 'ACTIVE');

        $this->orchestrate('appoint', 'WI-OM18', ['--candidate=S-ver', '--role=verification',
            '--human-act=PO/ARB: appoint the verifier.']);

        $fold = $this->fold('WI-OM18');
        $this->assertSame('S-own', $fold['sessions']['S-ver']['predecessor'],
            'the predecessor is the fold-reported mutation owner, never hand-invented');
    }

    public function test_om_19_transitions_execute_only_through_the_canonical_mechanism(): void
    {
        $this->requireOperatingModel();
        $src = $this->source();

        $this->assertStringNotContainsString('runtime/workflow', $src,
            'the operating model must not know or read the authoritative store path');
        foreach (['file_get_contents', 'fopen', 'fread', 'file(', 'SplFileObject'] as $readCall) {
            $this->assertStringNotContainsString($readCall, $src,
                "raw record access ({$readCall}) is forbidden");
        }
        foreach (['file_put_contents', 'fwrite($', 'rename('] as $writeCall) {
            $this->assertStringNotContainsString($writeCall, $src,
                "direct store mutation ({$writeCall}) is forbidden — writes go through AST-018/AST-015");
        }
    }

    public function test_om_20_human_start_is_never_fabricated(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM20');
        $this->lane('WI-OM20', 'S-arch', 'architecture', 'COMPLETED');
        $act = 'PO/ARB 2026-08-22, verbatim: I appoint the independent verifier.';

        $this->orchestrate('appoint', 'WI-OM20', ['--candidate=S-ver', '--role=verification', '--human-act=' . $act]);

        $raw = json_decode((string) file_get_contents($this->dir . '/WI-OM20.json'), true);
        $starts = array_values(array_filter($raw['transitions'],
            fn ($t) => $t['type'] === 'START' && $t['session'] === 'S-ver'));
        $this->assertCount(1, $starts);
        $this->assertSame('human', $starts[0]['recordedBy']);
        $this->assertStringContainsString($act, $starts[0]['humanAct'],
            'the human act is recorded verbatim — the operating model never writes a START itself');
    }

    public function test_om_21_no_second_workflow_engine(): void
    {
        $this->requireOperatingModel();
        $src = $this->source();

        $this->assertStringNotContainsString('function foldSessions', $src, 'no second fold');
        $this->assertStringNotContainsString('function determineNextActor', $src,
            'no re-implementation of AST-018 decision logic');
        $this->assertStringNotContainsString('workItemState', $src,
            'no second workflow-state vocabulary, no independent state derivation');
        $this->assertStringContainsString('next-actor-orchestration.php', $src,
            'the operating model must consume AST-018 by name');
        $this->assertStringContainsString('session-bootstrap.php', $src,
            'the operating model must consume AST-017 by name');
    }

    public function test_om_22_ast017_remains_read_only(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM22');
        $this->lane('WI-OM22', 'S-a', 'implementation', 'ACTIVE');
        $before = $this->fingerprint();

        $this->opm('session', 'WI-OM22', ['--process-label=S-a']);
        $this->opm('session', 'WI-OM22', ['--process-label=INTRUDER']);

        $this->assertSame($before, $this->fingerprint(),
            'invoking the bootstrap through the operating model never writes');
    }

    // ═══ OM-23 … OM-26 · review lifecycle (§21–§24) & wrong-session (§20) ════

    public function test_om_23_review_is_required_after_substantive_work(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM23');
        $this->lane('WI-OM23', 'S-impl', 'implementation', 'COMPLETED');

        $r = $this->opm('outcome', 'WI-OM23');

        $this->assertSame('FRESH_SESSION_REQUIRED', $r['out']['outcome']);
        $this->assertSame('verification', $r['out']['decision']['role'],
            'after substantive work the declared progression requires independent review');
        $this->assertTrue($r['out']['decision']['freshIndependentActorRequired']);
    }

    public function test_om_24_governance_escalation_for_substantive_conflicts(): void
    {
        $this->requireOperatingModel();
        // A lane cancelled before any completion → no successor is implied → AMBIGUOUS.
        $this->init('WI-OM24');
        $this->mechanism(['append', 'WI-OM24', '--json=' . json_encode(['type' => 'REGISTER', 'session' => 'S-x',
            'role' => 'verification', 'predecessor' => null, 'executionContext' => 'claude-code-session:S-x',
            'recordedBy' => 'governance'])]);
        $this->mechanism(['append', 'WI-OM24', '--json=' . json_encode(['type' => 'CANCEL', 'session' => 'S-x',
            'recordedBy' => 'governance'])]);

        $r = $this->opm('outcome', 'WI-OM24');

        $this->assertSame('GOVERNANCE_DECISION_REQUIRED', $r['out']['outcome']);
        $this->assertSame(5, $r['out']['case']);
        $this->assertStringContainsString('Governance decision is required', $r['out']['message']);
        $this->assertNotEmpty($r['out']['message']);
    }

    public function test_om_25_adoption_requires_an_explicit_human_decision(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM25');
        $this->lane('WI-OM25', 'S-arch', 'architecture', 'COMPLETED');
        $this->lane('WI-OM25', 'S-ver', 'verification', 'COMPLETED', 'S-arch');
        $this->lane('WI-OM25', 'S-gov', 'governance', 'COMPLETED', 'S-ver');
        $before = $this->fingerprint();

        $r = $this->opm('outcome', 'WI-OM25');

        $this->assertSame('GOVERNANCE_DECISION_REQUIRED', $r['out']['outcome']);
        $this->assertSame(6, $r['out']['case'], 'governance complete → the adoption presentation (CASE 6)');
        $this->assertStringContainsString('ready for adoption', $r['out']['message']);
        $this->assertSame($before, $this->fingerprint(),
            'review PASS is NOT adoption — nothing is adopted without the human decision (§25)');
    }

    public function test_om_26_wrong_session_recovery_is_business_language_readable(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM26');
        $this->lane('WI-OM26', 'S-a', 'implementation', 'ACTIVE');

        $human = $this->opm('session', 'WI-OM26', ['--process-label=INTRUDER'], false)['raw'];

        $this->assertStringContainsString('This session is not assigned to this work', $human);
        $this->assertStringContainsStringIgnoringCase('open the assigned session', $human);
        $this->assertStringContainsStringIgnoringCase('prepare', $human);
        $this->assertStringNotContainsString('UNRESOLVED', $human,
            '§35: never leave the human with a bare technical refusal');
    }

    // ═══ OM-27 … OM-30 · robustness: editing, providers, purity, determinism ══

    public function test_om_27_fresh_session_prompt_remains_usable_after_human_editing(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM27');
        $this->lane('WI-OM27', 'S-arch', 'architecture', 'COMPLETED');

        $prompt = $this->opm('outcome', 'WI-OM27')['out']['prompt'];

        $this->assertDoesNotMatchRegularExpression('/\b[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\b/',
            $prompt, 'no pre-known session UUID — so editing/pasting cannot carry a stale identity (P-6-style)');
        $this->assertStringContainsString('WI-OM27', $prompt, 'the governed facts survive an edit');

        // Editing the prompt (adding a human note) must not break its governed content.
        $edited = "Note from PO/ARB: please proceed.\n\n" . $prompt;
        $this->assertStringContainsString('WI-OM27', $edited);
        $this->assertStringContainsStringIgnoringCase('process identity', $edited);
        $this->assertStringContainsStringIgnoringCase('stop', $edited);
    }

    public function test_om_28_provider_independence_holds(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM28');
        $this->lane('WI-OM28', 'S-arch', 'architecture', 'COMPLETED');

        $claude = [
            'ANTHROPIC_MODEL' => 'claude-sonnet-4-6',
            'ANTHROPIC_BASE_URL' => 'https://api.anthropic.com',
            'ANTHROPIC_AUTH_TOKEN' => 'claude-token',
        ];
        $deepseek = [
            'ANTHROPIC_MODEL' => 'deepseek-chat',
            'ANTHROPIC_BASE_URL' => 'https://api.deepseek.com',
            'ANTHROPIC_AUTH_TOKEN' => 'deepseek-token',
        ];

        $a = $this->opm('outcome', 'WI-OM28', [], true, $claude);
        $b = $this->opm('outcome', 'WI-OM28', [], true, $deepseek);

        $this->assertSame('FRESH_SESSION_REQUIRED', $a['out']['outcome']);
        $this->assertSame($a['raw'], $b['raw'],
            'the operating model is pure PHP over governed facts — provider shape never changes the result');
    }

    public function test_om_29_read_only_paths_preserve_authoritative_records(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM29');
        $this->lane('WI-OM29', 'S-arch', 'architecture', 'COMPLETED');
        $this->lane('WI-OM29', 'S-ver', 'verification', 'COMPLETED', 'S-arch');
        $before = $this->fingerprint();

        $this->opm('outcome', 'WI-OM29');
        $this->opm('session', 'WI-OM29', ['--process-label=GHOST']);
        $this->opm('outcome', 'WI-OM29', [], false);   // human rendering
        $this->opm('session', 'WI-OM29', ['--process-label=S-arch'], false);

        $this->assertSame($before, $this->fingerprint(),
            'every read-only path — JSON and human — leaves the authoritative record byte-unchanged');
    }

    public function test_om_30_deterministic_identical_inputs(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM30');
        $this->lane('WI-OM30', 'S-arch', 'architecture', 'COMPLETED');

        $a = $this->opm('outcome', 'WI-OM30');
        $b = $this->opm('outcome', 'WI-OM30');

        $this->assertSame($a['raw'], $b['raw'], 'identical inputs must yield byte-identical JSON');
    }

    // ═══ OM-31 … OM-36 · operating-model invariants & hard contract ══════════

    public function test_om_31_outcome_is_exactly_one_of_the_five_vocabulary(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM31a');
        $this->lane('WI-OM31a', 'S-a', 'implementation', 'ACTIVE');
        $this->init('WI-OM31b');
        $this->lane('WI-OM31b', 'S-b', 'verification', 'CREATED');
        $this->init('WI-OM31c');
        $this->lane('WI-OM31c', 'S-c', 'architecture', 'COMPLETED');
        $this->init('WI-OM31d');
        $this->lane('WI-OM31d', 'S-d', 'implementation', 'STOPPED');

        $outcomes = array_map(fn ($wi) => $this->opm('outcome', $wi)['out']['outcome'],
            ['WI-OM31a', 'WI-OM31b', 'WI-OM31c', 'WI-OM31d']);

        $vocabulary = ['CONTINUE', 'PERMISSION_REQUIRED', 'FRESH_SESSION_REQUIRED', 'GOVERNANCE_DECISION_REQUIRED', 'STOP'];
        foreach ($outcomes as $o) {
            $this->assertContains($o, $vocabulary, "'{$o}' is outside the single §11 vocabulary");
        }
        $this->assertContains('CONTINUE', $outcomes);
        $this->assertContains('PERMISSION_REQUIRED', $outcomes);
        $this->assertContains('FRESH_SESSION_REQUIRED', $outcomes);
        $this->assertContains('STOP', $outcomes);
    }

    public function test_om_32_stop_outcome_names_what_why_who_and_recovery(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM32');
        $this->lane('WI-OM32', 'S-a', 'implementation', 'STOPPED');

        $r = $this->opm('outcome', 'WI-OM32');

        $this->assertSame('STOP', $r['out']['outcome']);
        $this->assertNotEmpty($r['out']['reason'], '§35: a stop identifies what happened and why');
        $this->assertNotEmpty($r['out']['whoMustActNext']);
        $this->assertNotEmpty($r['out']['humanOptions'], '§35: never a bare STOP without recovery');
    }

    public function test_om_33_mechanics_are_hidden_unless_requested(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM33');
        $this->lane('WI-OM33', 'S-arch', 'architecture', 'COMPLETED');

        $human = $this->opm('outcome', 'WI-OM33', [], false)['raw'];
        $this->assertStringNotContainsString('governed sequence:', $human,
            'the mechanical transition sequence is not shown by default (D-8)');
        $this->assertStringNotContainsString('technical detail (requested)', $human);

        $technical = $this->opm('outcome', 'WI-OM33', ['--show-mechanics'], false)['raw'];
        $this->assertStringContainsString('governed sequence:', $technical,
            'mechanics surface only when the human explicitly asks');
        $this->assertStringContainsString('REGISTER', $technical);
    }

    public function test_om_34_session_falls_back_to_the_runtime_identity(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM34');
        $this->lane('WI-OM34', 'S-RUN', 'implementation', 'ACTIVE');

        $r = $this->opm('session', 'WI-OM34', [], true, ['CLAUDE_CODE_SESSION_ID' => 'S-RUN']);

        $this->assertSame('MATCH', $r['out']['situation'],
            'process identity is DISCOVERED FROM THE RUNTIME (§15) — never supplied by a human');
        $this->assertSame('S-RUN', $r['out']['bootstrap']['identity']['current_process_label']);
    }

    public function test_om_35_usage_errors_exit_64(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM35');

        $this->assertSame(64, $this->opm('not-a-command', 'WI-OM35')['code']);
        $this->assertSame(64, $this->opm('outcome', 'WI-OM35', ['--nonsense=1'])['code']);
    }

    public function test_om_36_assigned_but_not_activated_presents_permission(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM36');
        $this->lane('WI-OM36', 'S-ASSIGNED', 'verification', 'CREATED');

        $r = $this->opm('session', 'WI-OM36', ['--process-label=S-ASSIGNED']);

        $this->assertSame('MATCH', $r['out']['situation'], 'the process IS the assigned session');
        $this->assertSame('PERMISSION_REQUIRED', $r['out']['outcome'],
            'assigned but not activated → the human permission is the next step (G-3)');
        $this->assertStringContainsString('need your permission', $r['out']['message']);
    }

    // ═══ PO/ARB load-bearing acceptance tests — OM-HUMAN-01 … OM-HUMAN-04 ═══
    // (explicit in the PO/ARB plan approval 2026-08-22; direct evidence that the
    //  operating layer solves the human-facing problem, not just the mechanics.)

    public function test_om_human_01_routine_next_action_is_one_outcome_and_no_mechanics(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-HUM1');
        $this->lane('WI-HUM1', 'S-a', 'implementation', 'ACTIVE');

        $j = $this->opm('outcome', 'WI-HUM1')['out'];
        $this->assertSame('CONTINUE', $j['outcome'], 'exactly one business outcome for a routine step');
        $this->assertContains($j['outcome'], ['CONTINUE', 'PERMISSION_REQUIRED', 'FRESH_SESSION_REQUIRED',
            'GOVERNANCE_DECISION_REQUIRED', 'STOP'], 'the outcome is from the single §11 vocabulary');

        $human = $this->opm('outcome', 'WI-HUM1', [], false)['raw'];
        foreach (['REGISTER', 'HANDOFF', 'START', 'mutationOwner', 'predecessor', 'workflow-state.php', 'claude-code-session:'] as $mechanic) {
            $this->assertStringNotContainsString($mechanic, $human,
                "OM-HUMAN-01: the routine step exposes no workflow mechanics ('{$mechanic}')");
        }
    }

    public function test_om_human_02_permission_required_renders_yes_and_write_a_prompt(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-HUM2');
        $this->lane('WI-HUM2', 'S-a', 'verification', 'CREATED');

        $j = $this->opm('outcome', 'WI-HUM2')['out'];
        $this->assertSame('PERMISSION_REQUIRED', $j['outcome']);

        $human = $this->opm('outcome', 'WI-HUM2', [], false)['raw'];
        $this->assertStringContainsString('need your permission', $human);
        $this->assertStringContainsString('1.', $human);
        $this->assertStringContainsString('Yes', $human);
        $this->assertStringContainsString('2.', $human);
        $this->assertStringContainsString('Write a prompt', $human);
        $this->assertStringNotContainsString('REGISTER', $human);
    }

    public function test_om_human_03_fresh_session_renders_paste_instruction_and_no_uuid(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-HUM3');
        $this->lane('WI-HUM3', 'S-arch', 'architecture', 'COMPLETED');

        $j = $this->opm('outcome', 'WI-HUM3')['out'];
        $this->assertSame('FRESH_SESSION_REQUIRED', $j['outcome']);

        $human = $this->opm('outcome', 'WI-HUM3', [], false)['raw'];
        $this->assertStringContainsString('Start a new session and paste this prompt', $human);
        $this->assertStringContainsString('You may use this prompt unchanged or edit it', $human);
        $this->assertStringContainsString('You are the candidate verification actor', $human,
            'OM-HUMAN-03: the generated prompt is embedded for pasting');
        $this->assertDoesNotMatchRegularExpression('/claude-code-session:[0-9a-f-]+/', $human,
            'OM-HUMAN-03: the human is never asked to handle a session UUID');
        $this->assertStringNotContainsStringIgnoringCase('your session id', $human);
        $this->assertStringNotContainsStringIgnoringCase('uuid', $human);
    }

    public function test_om_human_04_mismatch_renders_recovery_and_no_appointment_change(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-HUM4');
        $this->lane('WI-HUM4', 'S-a', 'implementation', 'ACTIVE');
        $before = $this->fingerprint();

        $human = $this->opm('session', 'WI-HUM4', ['--process-label=INTRUDER'], false)['raw'];
        $this->assertStringContainsString('This session is not assigned to this work', $human);
        $this->assertStringContainsStringIgnoringCase('open the assigned session', $human);

        // No appointment is created or replaced: exactly one lane remains, byte-unchanged.
        $this->assertSame($before, $this->fingerprint());
        $this->assertCount(1, $this->fold('WI-HUM4')['sessions'] ?? []);
    }

    public function test_om_human_05_fresh_session_is_paste_not_appointment(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-HUM5');
        $this->lane('WI-HUM5', 'S-arch', 'architecture', 'COMPLETED');

        $human = $this->opm('outcome', 'WI-HUM5', [], false)['raw'];

        $this->assertStringContainsString('The next step requires a fresh session.', $human);
        $this->assertStringContainsString('Start a new session and paste this prompt:', $human);
        $this->assertStringContainsString('You may use this prompt unchanged or edit it.', $human);
        $this->assertStringNotContainsString('appoint and activate', $human,
            'FRESH_SESSION_REQUIRED = prepare prompt + start fresh session, never an appointment invitation');
    }

    public function test_om_37_canonical_assets_are_not_modified(): void
    {
        $this->requireOperatingModel();
        $assets = [
            '.claude/scripts/workflow-state.php',               // AST-015
            '.claude/scripts/session-resolve.php',              // AST-016
            '.claude/scripts/session-bootstrap.php',            // AST-017
            '.claude/scripts/next-actor-orchestration.php',     // AST-018
        ];
        foreach ($assets as $asset) {
            $proc = proc_open(['git', 'diff', '--quiet', 'HEAD', '--', $asset],
                [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, $this->repoRoot);
            stream_get_contents($pipes[1]);
            stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $code = proc_close($proc);
            $this->assertSame(0, $code,
                "canonical asset {$asset} differs from HEAD — this work item must not modify it");
        }
    }

    public function test_om_38_intake_never_claims_an_eligible_candidate(): void
    {
        $this->requireOperatingModel();
        $this->init('WI-OM38'); // no lanes — intake (HUMAN_DECISION_REQUIRED, no appointment)

        $r = $this->opm('session', 'WI-OM38', ['--process-label=NEWPROC']);

        $this->assertSame('GOVERNANCE_DECISION_REQUIRED', $r['out']['situation']);
        $this->assertSame('GOVERNANCE_DECISION_REQUIRED', $r['out']['outcome']);
        $this->assertStringNotContainsString('eligible actor is available', $r['out']['message']);

        $human = $this->opm('session', 'WI-OM38', ['--process-label=NEWPROC'], false)['raw'];
        $this->assertStringNotContainsString('eligible actor is available', $human);
        $this->assertStringNotContainsString('appoint and activate', $human,
            'the human is not asked to appoint and activate from an unattributed session');
    }
}
