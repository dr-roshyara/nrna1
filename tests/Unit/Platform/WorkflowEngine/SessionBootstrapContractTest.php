<?php

namespace Tests\Unit\Platform\WorkflowEngine;

use PHPUnit\Framework\TestCase;

/**
 * KOS-SESSION-BOOTSTRAP-001 — Session Bootstrap & Responsibility Resolution
 * contract, S-1…S-17, plus CORRECTION-001 regressions S-18…S-20
 * (V-1 recorded_human_start_act · V-3 disambiguation truthfulness · V-5
 * canonical field contract).
 *
 * Work item: KOS-SESSION-BOOTSTRAP-001 · Asset: AST-017
 * (session-bootstrap.php) · Component: CMP-004 (workflow_engine).
 * PO/ARB-commissioned minimal operational correction for EKS-07 — a coordination
 * legibility defect, NOT a governance-rule gap, and NOT an EKS-07 implementation.
 *
 * Core contract:
 *   IDENTITY ≠ ROLE ≠ ELIGIBILITY ≠ AUTHORIZATION ≠ OWNERSHIP ≠ CONTINUATION —
 *   the six are never collapsed into one "agent identity".
 *   Fail-closed: every AMBIGUOUS / UNRESOLVED / UNRESOLVABLE report exits 0
 *   with `operable=false`, `authorized_to_act=false`,
 *   `current_session_can_continue=false`, and an actionable `unresolved_message`
 *   (missing fact · source · responsible next actor). The bootstrap never
 *   invents identity/authorization and never creates a transition.
 *
 * Binding precedence (AMENDMENT 2 + V-3): ALL workflow interpretation comes from
 * the qualified mechanism AST-015 (workflow-state.php) via subprocess. AST-017
 * performs no second fold. Its ONE bounded raw read is the V-3 handoff fact —
 * presence of a HANDOFF to/from this lane with token + tokenRef — because no
 * AST-015 read command exposes it. S-16 poisons the raw record and proves the
 * bootstrap reports the fold-derived truth, never the decoys; S-2b proves the
 * handoff line is truthful (a recorded handoff is NOT listed as missing).
 *
 * PO/ARB acceptance conditions (2026-08-22) pinned here:
 *   2. V-3 exception = exactly one thing → S-16.
 *   3. Cross-provider conformance → S-17: same repo/records/process-label/
 *      work-item/CLI, Claude-shaped env vs DeepSeek-shaped env, ASSERT
 *      byte-identical JSON stdout (the harness, not the model endpoint,
 *      determines the result).
 *
 * Exit contract: a produced report is a SUCCESS — all four verdicts exit 0;
 * 64 is reserved for usage — S-15 pins this.
 *
 * Hermetic: synthetic records in temp dirs, built THROUGH the qualified
 * mechanism; `.claude/runtime/workflow/` is never read or written.
 *
 * RED TODAY by absence: `.claude/scripts/session-bootstrap.php` does not exist.
 */
class SessionBootstrapContractTest extends TestCase
{
    private const BOOTSTRAP = '.claude/scripts/session-bootstrap.php';
    private const MECHANISM = '.claude/scripts/workflow-state.php';

    private string $repoRoot;
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repoRoot = \dirname(__DIR__, 4);
        $this->dir = sys_get_temp_dir() . '/kos-boot-' . bin2hex(random_bytes(6));
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

    private function requireBootstrap(): void
    {
        $this->assertFileExists($this->repoRoot . '/' . self::BOOTSTRAP,
            'Session Bootstrap does not exist yet — RED by absence (boundary §7 step 2)');
    }

    /** @return array{code:int,out:?array,raw:string,err:string} */
    private function bootstrap(array $args = [], array $env = [], ?string $dir = null): array
    {
        $cmd = array_merge(
            ['php', $this->repoRoot . '/' . self::BOOTSTRAP, '--dir=' . ($dir ?? $this->dir), '--json'],
            $args
        );
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null,
            $env === [] ? null : array_merge(getenv(), $env));
        $raw = stream_get_contents($pipes[1]);
        $err = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return ['code' => proc_close($proc), 'out' => json_decode($raw, true), 'raw' => $raw, 'err' => $err];
    }

    private function mechanism(array $args): void
    {
        $cmd = array_merge(['php', $this->repoRoot . '/' . self::MECHANISM], $args, ['--dir=' . $this->dir]);
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        stream_get_contents($pipes[1]);
        $err = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $this->assertSame(0, proc_close($proc), 'fixture setup via the qualified mechanism failed: ' . $err);
    }

    /**
     * Build a record THROUGH the qualified mechanism — never by hand-writing
     * JSON. $label, when given, is attributed to the lane as its registered
     * process label (executionContext embeds `claude-code-session:<label>`).
     */
    private function record(string $wi, string $session, string $role, string $finalState = 'ACTIVE', ?string $label = null): void
    {
        $this->mechanism(['init', $wi, '--workflow=platform-implementation',
            '--roles=governance,architecture,implementation,verification']);
        $this->mechanism(['append', $wi, '--json=' . json_encode(['type' => 'REGISTER', 'session' => $session,
            'role' => $role, 'predecessor' => null,
            'executionContext' => $label === null ? 'shared-worktree' : 'claude-code-session:' . $label,
            'recordedBy' => 'governance'])]);

        if ($finalState === 'CREATED') {
            return;
        }

        $this->mechanism(['append', $wi, '--json=' . json_encode(['type' => 'HANDOFF', 'from' => null,
            'to' => $session, 'token' => 'T-1', 'tokenRef' => 'ref', 'recordedBy' => 'governance'])]);
        $this->mechanism(['append', $wi, '--json=' . json_encode(['type' => 'START', 'session' => $session,
            'humanAct' => 'recorded human start act', 'recordedBy' => 'human'])]);

        $terminal = [
            'STOPPED' => ['type' => 'STOP', 'session' => $session, 'reason' => 'r', 'recordedBy' => 'governance'],
            'COMPLETED' => ['type' => 'COMPLETE', 'session' => $session, 'recordedBy' => 'governance'],
            'CANCELLED' => ['type' => 'CANCEL', 'session' => $session, 'recordedBy' => 'governance'],
            'FAILED' => ['type' => 'FAIL', 'session' => $session, 'recordedBy' => 'governance'],
        ];
        if (isset($terminal[$finalState])) {
            $this->mechanism(['append', $wi, '--json=' . json_encode($terminal[$finalState])]);
        }
    }

    private function grantScope(string $wi, string $grantId, string $scope): void
    {
        $this->mechanism(['grant', $wi, '--writer-role=governance', '--json=' . json_encode([
            'grantId' => $grantId, 'status' => 'AUTHORIZED', 'authority' => 'PO',
            'humanActRef' => 'artifact#1', 'scope' => $scope])]);
    }

    private function directoryFingerprint(): array
    {
        $out = [];
        foreach (glob($this->dir . '/*') ?: [] as $f) {
            $out[basename($f)] = hash_file('sha256', $f);
        }
        ksort($out);

        return $out;
    }

    private function caveat(string $report): bool
    {
        return str_contains(strtolower($report), 'resolution is not activation');
    }

    // ─── S-1 … S-7 · state → verdict → authorization (mission T-1…T-7) ───────

    public function test_s1_active_lane_resolves_attribution_matches_and_is_authorized_with_scope(): void
    {
        $this->requireBootstrap();
        $this->record('WI-1', 'S-a', 'implementation', 'ACTIVE', 'S1LABEL');
        $this->grantScope('WI-1', 'G-1', 'Scope-A');

        $r = $this->bootstrap(['--process-label=S1LABEL', '--work-item=WI-1', '--scope=Scope-A']);

        $this->assertSame('RESOLVED', $r['out']['verdict']);
        $this->assertTrue($r['out']['operable']);
        $this->assertSame('MATCH', $r['out']['identity']['attribution']);
        $this->assertSame('S1LABEL', $r['out']['identity']['current_process_label']);
        $this->assertSame('S-a', $r['out']['assignment']['lane']);
        $this->assertSame('implementation', $r['out']['assignment']['role']);
        $this->assertSame('ACTIVE', $r['out']['assignment']['workflow_state']);
        $this->assertSame('S-a', $r['out']['mutation_owner']['session']);
        $this->assertTrue($r['out']['mutation_owner']['is_this_lane']);
        $this->assertTrue($r['out']['grant']['authorized_within_scope']);
        $this->assertTrue($r['out']['gates']['authorized_to_act']);
        $this->assertFalse($r['out']['gates']['human_decision_required']);
        $this->assertTrue($r['out']['continuation']['current_session_can_continue']);
        $this->assertTrue($this->caveat($r['raw']), 'the "Resolution is not activation" caveat is unconditional');
    }

    public function test_s2_created_lane_is_not_authorized_names_both_g3_conjuncts_and_returns_to_po_arb(): void
    {
        $this->requireBootstrap();
        $this->record('WI-2', 'S-b', 'verification', 'CREATED', 'S2LABEL');

        $r = $this->bootstrap(['--process-label=S2LABEL', '--work-item=WI-2']);

        $this->assertSame('RESOLVED', $r['out']['verdict']);
        $this->assertFalse($r['out']['operable']);
        $this->assertFalse($r['out']['gates']['authorized_to_act']);
        $this->assertTrue($r['out']['gates']['human_decision_required']);
        $this->assertFalse($r['out']['activation_prerequisites']['predecessor_handoff_present']);
        $this->assertFalse($r['out']['activation_prerequisites']['recorded_human_start_act']);
        $missing = strtolower(implode(' ', $r['out']['activation_prerequisites']['missing_for_start'] ?? []));
        $this->assertStringContainsString('handoff', $missing, 'G-3 conjunct 1: the predecessor handoff must be listed');
        $this->assertStringContainsString('start', $missing, 'G-3 conjunct 2: the human START act must be listed');
        $this->assertSame('po/arb', $r['out']['continuation']['recommended_next_actor']['role']);
    }

    public function test_s2b_created_lane_with_recorded_handoff_lists_only_the_human_start_as_missing(): void
    {
        $this->requireBootstrap();
        $this->record('WI-2b', 'S-p', 'implementation', 'ACTIVE', 'S2P');
        $this->mechanism(['append', 'WI-2b', '--json=' . json_encode(['type' => 'REGISTER', 'session' => 'S-n',
            'role' => 'verification', 'predecessor' => 'S-p', 'executionContext' => 'claude-code-session:S2N',
            'recordedBy' => 'governance'])]);
        $this->mechanism(['append', 'WI-2b', '--json=' . json_encode(['type' => 'HANDOFF', 'from' => 'S-p',
            'to' => 'S-n', 'token' => 'T-2', 'tokenRef' => 'ref2', 'recordedBy' => 'governance'])]);

        $r = $this->bootstrap(['--process-label=S2N', '--session=S-n']);

        $this->assertSame('RESOLVED', $r['out']['verdict']);
        $this->assertFalse($r['out']['operable']);
        $this->assertTrue($r['out']['activation_prerequisites']['predecessor_handoff_present'],
            'V-3: the handoff fact IS read — presence');
        $this->assertSame('ref2', $r['out']['activation_prerequisites']['predecessor_handoff_token_ref'],
            'V-3: the handoff tokenRef IS read — the only raw-derived fact');
        $this->assertFalse($r['out']['activation_prerequisites']['recorded_human_start_act']);
        $missing = strtolower(implode(' ', $r['out']['activation_prerequisites']['missing_for_start'] ?? []));
        $this->assertStringNotContainsString('handoff', $missing,
            'V-3 truthfulness: a recorded handoff must never be listed as missing (S-2b)');
        $this->assertStringContainsString('start', $missing);
    }

    public function test_s3_scope_coverage_is_authorization_r6(): void
    {
        $this->requireBootstrap();
        $this->record('WI-3', 'S-e', 'architecture', 'ACTIVE', 'S3LABEL');
        $this->grantScope('WI-3', 'G-3', 'Repair-X');

        $covered = $this->bootstrap(['--process-label=S3LABEL', '--session=S-e', '--scope=Repair-X']);
        $this->assertTrue($covered['out']['grant']['authorized_within_scope']);
        $this->assertTrue($covered['out']['gates']['authorized_to_act']);

        $other = $this->bootstrap(['--process-label=S3LABEL', '--session=S-e', '--scope=Other']);
        $this->assertFalse($other['out']['grant']['authorized_within_scope'],
            'R6: scope coverage is a gate, not a suggestion');
        $this->assertFalse($other['out']['gates']['authorized_to_act']);
        $this->assertTrue($other['out']['gates']['human_decision_required']);
    }

    public function test_s4_shared_label_yields_ambiguous_with_no_silent_selection(): void
    {
        $this->requireBootstrap();
        $this->record('WI-4a', 'S-h', 'implementation', 'ACTIVE', 'SHARED');
        $this->record('WI-4b', 'S-i', 'architecture', 'ACTIVE', 'SHARED');

        $r = $this->bootstrap(['--process-label=SHARED']);

        $this->assertSame('AMBIGUOUS', $r['out']['verdict']);
        $this->assertFalse($r['out']['operable']);
        $this->assertCount(2, $r['out']['meta']['candidates'], 'every matching lane must be listed');
        $this->assertNull($r['out']['assignment']['lane'] ?? null, 'no silent selection (P-3)');
        $this->assertNotNull($r['out']['meta']['disambiguation_required']);
        $this->assertFalse($r['out']['gates']['authorized_to_act']);
        $this->assertFalse($r['out']['continuation']['current_session_can_continue']);
    }

    public function test_s5_process_mismatch_never_grants_authorization(): void
    {
        $this->requireBootstrap();
        $this->record('WI-5', 'S-f', 'implementation', 'ACTIVE', 'REGISTERED1');

        $r = $this->bootstrap(['--session=S-f', '--process-label=INTRUDER']);

        $this->assertSame('RESOLVED', $r['out']['verdict']);
        $this->assertSame('MISMATCH', $r['out']['identity']['attribution']);
        $this->assertFalse($r['out']['gates']['authorized_to_act']);
        $this->assertStringContainsStringIgnoringCase('governance',
            json_encode($r['out']['identity']['attribution_caveat']),
            'identity is evidence-only and never impersonates: a mismatch routes to a governance act');
        $this->assertSame('governance', $r['out']['continuation']['recommended_next_actor']['role']);
    }

    public function test_s6_active_lane_without_covering_grant_is_blocked(): void
    {
        $this->requireBootstrap();
        $this->record('WI-6', 'S-g', 'implementation', 'ACTIVE', 'S6LABEL');
        $this->grantScope('WI-6', 'G-6', 'Granted-Scope');

        $r = $this->bootstrap(['--process-label=S6LABEL', '--session=S-g', '--scope=Uncovered']);

        $this->assertFalse($r['out']['grant']['authorized_within_scope']);
        $this->assertFalse($r['out']['gates']['authorized_to_act']);
        $this->assertTrue($r['out']['gates']['human_decision_required']);
        $this->assertSame('implementation', $r['out']['continuation']['recommended_next_actor']['role'],
            'deterministic table: ACTIVE without a covering grant blocks on the role, grant gap named');
    }

    public function test_s7_completed_session_is_never_operable_and_returns_to_po_arb(): void
    {
        $this->requireBootstrap();
        $this->record('WI-7', 'S-j', 'implementation', 'COMPLETED', 'S7LABEL');

        $r = $this->bootstrap(['--process-label=S7LABEL', '--session=S-j']);

        $this->assertSame('RESOLVED', $r['out']['verdict']);
        $this->assertFalse($r['out']['operable']);
        $this->assertFalse($r['out']['continuation']['current_session_can_continue']);
        $this->assertTrue($r['out']['gates']['human_decision_required']);
        $this->assertSame('po/arb', $r['out']['continuation']['recommended_next_actor']['role'],
            'R8: a terminal state means a NEW assignment — the PO/ARB decides');
    }

    // ─── S-8 · determinism (byte-identical, JSON + human) ────────────────────

    public function test_s8_output_is_byte_identical_across_runs(): void
    {
        $this->requireBootstrap();
        $this->record('WI-8', 'S-k', 'implementation', 'ACTIVE', 'S8LABEL');
        $this->grantScope('WI-8', 'G-8', 'Scope-8');
        $args = ['--process-label=S8LABEL', '--session=S-k', '--scope=Scope-8'];

        $a = $this->bootstrap($args);
        $b = $this->bootstrap($args);
        $this->assertSame($a['raw'], $b['raw'], 'JSON rendering must be byte-identical across runs');

        $cmd = ['php', $this->repoRoot . '/' . self::BOOTSTRAP, '--dir=' . $this->dir, '--process-label=S8LABEL',
            '--session=S-k', '--scope=Scope-8'];
        $h1 = $this->runRaw($cmd);
        $h2 = $this->runRaw($cmd);
        $this->assertSame($h1, $h2, 'human rendering must be byte-identical across runs');
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

    // ─── S-9 · read purity — the strongest single assertion ─────────────────

    public function test_s9_record_directory_is_byte_identical_across_every_verdict_path(): void
    {
        $this->requireBootstrap();
        $this->record('WI-9', 'S-l', 'implementation', 'ACTIVE', 'S9LABEL');
        $this->record('WI-9b', 'S-m', 'implementation', 'ACTIVE', 'S9B');
        $this->record('WI-9c', 'S-n', 'verification', 'ACTIVE', 'S9LABEL'); // shares label → AMBIGUOUS later

        $before = $this->directoryFingerprint();

        $this->bootstrap(['--process-label=S9LABEL', '--work-item=WI-9']);   // RESOLVED
        $this->bootstrap(['--process-label=S9B', '--work-item=WI-9b']);       // RESOLVED
        $this->bootstrap(['--process-label=NOPE']);                           // UNRESOLVED
        $this->bootstrap(['--process-label=NOPE'], ['KOS_MECHANISM_PATH' => '/nonexistent/workflow-state.php']); // UNRESOLVABLE
        $this->bootstrap(['--process-label=S9LABEL']);                        // AMBIGUOUS

        $this->assertSame($before, $this->directoryFingerprint(),
            'read purity: the bootstrap never writes — not on any verdict, not beside any record');
    }

    // ─── S-10 … S-12 · fail-closed paths ────────────────────────────────────

    public function test_s10_unknown_process_is_unresolved_with_actionable_message(): void
    {
        $this->requireBootstrap();
        $this->record('WI-10', 'S-o', 'implementation', 'ACTIVE', 'REG10');

        $r = $this->bootstrap(['--process-label=GHOST']);

        $this->assertSame('UNRESOLVED', $r['out']['verdict']);
        $this->assertFalse($r['out']['operable']);
        $msg = strtolower(json_encode($r['out']['meta']['unresolved_message'] ?? ''));
        $this->assertStringContainsString('register', $msg,
            'the missing fact is named: no REGISTER attributes this process to a lane');
        $this->assertStringContainsString('governance', $msg, 'the responsible next actor is named');
        $this->assertStringContainsString('reg10', $msg, 'registered lanes + their labels are listed');
    }

    public function test_s11_absent_mechanism_is_unresolvable_and_names_the_mechanism(): void
    {
        $this->requireBootstrap();
        $this->record('WI-11', 'S-p', 'implementation', 'ACTIVE', 'S11LABEL');

        $r = $this->bootstrap(['--process-label=S11LABEL'], ['KOS_MECHANISM_PATH' => '/nonexistent/workflow-state.php']);

        $this->assertSame('UNRESOLVABLE', $r['out']['verdict']);
        $this->assertStringContainsStringIgnoringCase('mechanism', json_encode($r['out']['meta']['reasons'] ?? []),
            'AMENDMENT 2: without the qualified interpreter the bootstrap cannot determine workflow state');
        $this->assertSame('workflow-state.php', basename($r['out']['meta']['interpreter']['path'] ?? ''),
            'C-1: even on refusal the intended interpreter is named');
    }

    public function test_s12_absent_record_directory_is_unresolvable(): void
    {
        $this->requireBootstrap();
        $empty = sys_get_temp_dir() . '/kos-empty-' . bin2hex(random_bytes(4));
        mkdir($empty, 0777, true);

        try {
            $r = $this->bootstrap([], [], $empty);

            $this->assertSame('UNRESOLVABLE', $r['out']['verdict']);
            $this->assertFalse($r['out']['operable']);
        } finally {
            @rmdir($empty);
        }
    }

    // ─── S-13 · C-1 interpreter identity, every verdict ─────────────────────

    public function test_s13_interpreter_identity_is_reported_for_every_verdict(): void
    {
        $this->requireBootstrap();
        $this->record('WI-13', 'S-q', 'implementation', 'ACTIVE', 'S13A');
        $this->record('WI-13b', 'S-r', 'architecture', 'ACTIVE', 'S13A');   // shares → AMBIGUOUS
        file_put_contents($this->dir . '/WI-13c.json', '{ corrupt');

        $expected = $this->repoRoot . '/' . self::MECHANISM;
        $paths = [
            'RESOLVED'     => ['--process-label=S13A', '--work-item=WI-13'],
            'AMBIGUOUS'    => ['--process-label=S13A'],
            'UNRESOLVED'   => ['--process-label=NOPE'],
            'UNRESOLVABLE' => ['--work-item=WI-13c'],
        ];

        foreach ($paths as $verdict => $args) {
            $json = $this->bootstrap($args);
            $this->assertSame($verdict, $json['out']['verdict'], "precondition: {$verdict} path");
            $this->assertSame($expected, $json['out']['meta']['interpreter']['path'] ?? null,
                "C-1: machine output must name the interpreter on {$verdict}");
        }
    }

    // ─── S-14 · forced session, no process identity → UNKNOWN, never granted ─

    public function test_s14_forced_session_without_process_identity_has_unknown_attribution(): void
    {
        $this->requireBootstrap();
        $this->record('WI-14', 'S-s', 'implementation', 'ACTIVE', 'REG14');

        $r = $this->bootstrap(['--session=S-s'], ['CLAUDE_CODE_SESSION_ID' => '']);

        $this->assertSame('RESOLVED', $r['out']['verdict']);
        $this->assertSame('UNKNOWN', $r['out']['identity']['attribution']);
        $this->assertFalse($r['out']['gates']['authorized_to_act'],
            'INV-ATTR-2: self-declared until attested — no label means no authorization');
        $this->assertTrue($r['out']['gates']['human_decision_required']);
        $this->assertStringContainsStringIgnoringCase('governance',
            json_encode($r['out']['identity']['attribution_caveat']));
    }

    // ─── S-15 · exit contract ───────────────────────────────────────────────

    public function test_s15_every_produced_verdict_succeeds_and_usage_errors_exit_64(): void
    {
        $this->requireBootstrap();
        $this->record('WI-15', 'S-t', 'implementation', 'ACTIVE', 'S15A');

        $this->assertSame(0, $this->bootstrap(['--process-label=S15A', '--work-item=WI-15'])['code'], 'RESOLVED');
        $this->assertSame(0, $this->bootstrap(['--process-label=NOPE'])['code'], 'UNRESOLVED');
        $this->assertSame(0, $this->bootstrap([], ['KOS_MECHANISM_PATH' => '/nonexistent/workflow-state.php'])['code'],
            'UNRESOLVABLE');

        $this->record('WI-15b', 'S-u', 'verification', 'ACTIVE', 'S15A');
        $this->assertSame(0, $this->bootstrap(['--process-label=S15A'])['code'], 'AMBIGUOUS');

        $this->assertSame(64, $this->bootstrap(['--nonsense-flag=1'])['code'],
            'non-zero 64 is reserved for usage/refusal');
    }

    // ─── S-16 · PO/ARB condition 2 — V-3 boundary is the ONLY raw derivation ─

    public function test_s16_v3_boundary_fold_derived_facts_ignore_raw_decoys(): void
    {
        $this->requireBootstrap();
        $this->record('WI-16', 'S-v', 'implementation', 'ACTIVE', 'S16LABEL');
        $this->grantScope('WI-16', 'G-16', 'Scope-16');

        // Poison the raw record: decoy top-level facts + a fake sessions dict.
        // The mechanism folds from transitions only, so it tolerates the poison.
        $path = $this->dir . '/WI-16.json';
        $rec = json_decode((string) file_get_contents($path), true);
        $rec['mutationOwner'] = 'DECOY-OWNER';
        $rec['workItemState'] = 'DECOY-STATE';
        $rec['sessions'] = ['S-v' => ['state' => 'COMPLETED', 'role' => 'decoy-role']];
        file_put_contents($path, json_encode($rec, JSON_PRETTY_PRINT));

        $r = $this->bootstrap(['--process-label=S16LABEL', '--session=S-v', '--scope=Scope-16']);

        $this->assertSame('RESOLVED', $r['out']['verdict'], 'the poisoned record is tolerated (fold ignores decoys)');
        $this->assertSame('ACTIVE', $r['out']['assignment']['workflow_state'],
            'state comes from fold, NOT from the raw decoy sessions.S-v.state');
        $this->assertSame('implementation', $r['out']['assignment']['role'],
            'role comes from fold, NOT from the raw decoy sessions.S-v.role');
        $this->assertSame('S-v', $r['out']['mutation_owner']['session'],
            'owner comes from fold, NOT from the raw decoy mutationOwner');
        $this->assertSame('OPEN', $r['out']['assignment']['work_item_state'],
            'work-item state comes from fold (OPEN), NOT from the raw decoy workItemState (DECOY-STATE)');

        // And the ONE bounded raw read still works: the handoff family.
        $this->assertTrue($r['out']['activation_prerequisites']['predecessor_handoff_present']);
        $this->assertSame('ref', $r['out']['activation_prerequisites']['predecessor_handoff_token_ref']);
    }

    // ─── S-17 · PO/ARB condition 3 — cross-provider conformance ─────────────

    public function test_s17_cross_provider_conformance_is_byte_identical(): void
    {
        $this->requireBootstrap();
        $this->record('WI-17', 'S-w', 'architecture', 'ACTIVE', 'S17LABEL');
        $this->grantScope('WI-17', 'G-17', 'Scope-17');

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
        $args = ['--process-label=S17LABEL', '--session=S-w', '--scope=Scope-17', '--work-item=WI-17'];

        $a = $this->bootstrap($args, $claude);
        $b = $this->bootstrap($args, $deepseek);

        $this->assertSame('RESOLVED', $a['out']['verdict'], 'precondition: both providers resolve');
        $this->assertSame('RESOLVED', $b['out']['verdict']);
        $this->assertSame($a['raw'], $b['raw'],
            'PO/ARB acceptance criterion: same repo/records/process-label/work-item/CLI under either provider '
            . 'shape → byte-identical JSON. The harness — not the model endpoint — determines the result.');
    }

    // ─── CORRECTION-001 · S-18 = V-1 — a human START is never inferred ───────

    public function test_s18_v1_cancelled_lane_without_start_never_claims_human_start(): void
    {
        $this->requireBootstrap();
        // REGISTER → CANCEL with NO HANDOFF and NO START: the only lawfully-correct
        // answer is that no human START was recorded (G-3). A terminal lane that
        // merely LEFT CREATED must not be reported as having a human start act.
        $this->mechanism(['init', 'WI-V1', '--workflow=platform-implementation',
            '--roles=governance,architecture,implementation,verification']);
        $this->mechanism(['append', 'WI-V1', '--json=' . json_encode(['type' => 'REGISTER', 'session' => 'S-v1',
            'role' => 'implementation', 'predecessor' => null, 'executionContext' => 'claude-code-session:V1LABEL',
            'recordedBy' => 'governance'])]);
        $this->mechanism(['append', 'WI-V1', '--json=' . json_encode(['type' => 'CANCEL', 'session' => 'S-v1',
            'recordedBy' => 'governance'])]);

        $r = $this->bootstrap(['--process-label=V1LABEL', '--work-item=WI-V1']);

        $this->assertSame('RESOLVED', $r['out']['verdict'], 'precondition: the lane is attributable');
        $this->assertSame('CANCELLED', $r['out']['assignment']['workflow_state'],
            'precondition: the lane reached the CANCELLED terminal state');
        $this->assertFalse($r['out']['activation_prerequisites']['recorded_human_start_act'],
            'V-1: leaving CREATED is NOT a human START — a CREATED→CANCELLED lane must report false');
        $this->assertFalse($r['out']['gates']['authorized_to_act'],
            'V-1: G-3 is not weakened — no START means no authorization');
    }

    // ─── CORRECTION-001 · S-19 = V-3 — disambiguation names ONLY the matches ──

    public function test_s19_v3_disambiguation_lists_only_actual_matches(): void
    {
        $this->requireBootstrap();
        $this->record('WI-V3a', 'V3LANE-A', 'implementation', 'ACTIVE', 'V3LABEL');   // matches
        $this->record('WI-V3b', 'V3LANE-B', 'architecture', 'ACTIVE', 'V3LABEL');    // matches
        $this->record('WI-V3c', 'V3LANE-C', 'verification', 'ACTIVE', 'OTHERLABEL'); // does NOT match

        $r = $this->bootstrap(['--process-label=V3LABEL']);

        $this->assertSame('AMBIGUOUS', $r['out']['verdict'], 'precondition: two lanes match the selector');
        $this->assertCount(3, $r['out']['meta']['candidates'],
            'precondition: all three lanes are discovered candidates');
        $msg = (string) ($r['out']['meta']['disambiguation_required'] ?? '');
        $this->assertNotSame('', $msg, 'an AMBIGUOUS report must require disambiguation');
        $this->assertStringContainsString('V3LANE-A', $msg, 'matching lane A is named for disambiguation');
        $this->assertStringContainsString('V3LANE-B', $msg, 'matching lane B is named for disambiguation');
        $this->assertStringNotContainsString('V3LANE-C', $msg,
            'V-3 truthfulness: the non-matching lane MUST NOT be listed as a disambiguation candidate');
        $this->assertNull($r['out']['assignment']['lane'] ?? null, 'no silent selection (P-3)');
        $this->assertFalse($r['out']['gates']['authorized_to_act'], 'fail-closed unchanged');
    }

    // ─── CORRECTION-001 · S-20 = V-5 — ONE canonical bootstrap field name ─────

    public function test_s20_v5_canonical_field_is_activation_prerequisites(): void
    {
        $this->requireBootstrap();
        $this->record('WI-V5', 'S-v5', 'implementation', 'ACTIVE', 'V5LABEL');
        $this->grantScope('WI-V5', 'G-V5', 'Scope-V5');

        $r = $this->bootstrap(['--process-label=V5LABEL', '--session=S-v5', '--scope=Scope-V5']);

        // The canonical field name — exactly ONE (V-5). No duplicate alias.
        $this->assertArrayHasKey('activation_prerequisites', $r['out'],
            'V-5: the documented field `activation_prerequisites` MUST exist in the report');
        $this->assertArrayNotHasKey('bootstrapping_status', $r['out'],
            'V-5: the stale `bootstrapping_status` name MUST NOT be emitted');

        // Promised semantics (boundary §5): the block carries the G-3
        // prerequisites, not a status string.
        $p = $r['out']['activation_prerequisites'];
        $this->assertTrue($p['predecessor_handoff_present'], 'G-3 conjunct 1 is reported');
        $this->assertSame('ref', $p['predecessor_handoff_token_ref'], 'the V-3 handoff tokenRef is reported');
        $this->assertTrue($p['recorded_human_start_act'], 'G-3 conjunct 2 is reported for an ACTIVE lane');
        $this->assertFalse($p['successor_handoff_present']);
        $this->assertNull($p['successor_lane']);
        $this->assertIsArray($p['missing_for_start']);
        $this->assertSame([], $p['missing_for_start'], 'an activated lane has no missing G-3 conjunct');
    }
}
