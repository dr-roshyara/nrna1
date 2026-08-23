<?php

namespace Tests\Unit\Platform\WorkflowEngine;

use PHPUnit\Framework\TestCase;

/**
 * KOS-OPERATING-MODEL-001 AMENDMENT-001 — ActivateCommissionedFreshSession
 * (domain concept: BindRuntimeToRequestedResponsibility) contract, GO-01…GO-25
 * (commission §28 minimum acceptance tests + §29 current-problem case as GO-05).
 *
 * Work item: KOS-OPERATING-MODEL-001 (FINAL GOVERNANCE + COMMUNICATION
 * OPERATING MODEL) · FOLLOW-UP/AMENDMENT slice · Asset: AST-019
 * (activate-commissioned-fresh-session.php) · Component: CMP-004 (workflow_engine).
 * Commission: HUMAN GIVES BUSINESS ORDER → GOVERNANCE ENGINEER LISTENS → ANALYZES
 * HONESTLY → DETERMINES LAWFUL EXECUTION PATH → CURRENT/FRESH SESSION BINDS ITS
 * REAL RUNTIME IDENTITY TO THE REQUESTED RESPONSIBILITY → CANONICAL WORKFLOW
 * MECHANICS → GOVERNANCE ENGINEER ACTIVE → HUMAN ORDER EXECUTED.
 *
 * Core contract (unified binding model, PO/ARB 2026-08-22):
 *   HUMAN declares intended responsibility · RUNTIME declares process identity
 *   (CLAUDE_CODE_SESSION_ID env ONLY, never a CLI arg) · the GOVERNED BOOTSTRAP
 *   binds the two.
 *   Invariant: "A fresh session may self-bind identity; it may never self-choose
 *   role, scope, work item, or authority." The role comes from the authoritative
 *   commission (AST-018 next-actor) or the human business order (first binding),
 *   never from the prompt alone — prompt ≠ commission → MISMATCH → STOP.
 *   The ONLY allowed write: REGISTER {session = own runtime identity, role =
 *   commissioned role} → governed HANDOFF → human START (G-3). The capability
 *   NEVER writes CONTINUATION (Inv E); a STOPPED item is an honest blocker.
 *
 * Mechanism discipline (mirrors AST-016/017/018): the capability obtains ALL
 * workflow interpretation by invoking AST-015 (fold), AST-017 (fresh-session
 * declaration) and AST-018 (next-actor commission) as subprocesses — no second
 * fold, no raw-record write, no store-path knowledge; AST-015 append is the sole
 * writer. GO-15/16/17/18/25 pin the boundaries; AST-015/016/017/018 and
 * operating-model.php must be byte-unchanged vs HEAD.
 *
 * Exit contract (plan §3.3): activate → 0 activated · 65 refused /
 * INCOMPLETE_SEQUENCE · 64 usage. check → 0 for any produced report (read-only
 * sibling, the GO-23/24/25 determinism/cross-provider/read-purity vehicle) ·
 * 64 usage.
 *
 * Hermetic: synthetic records in temp dirs, built THROUGH the qualified
 * mechanism (never by hand-writing JSON); `.claude/runtime/workflow/` is never
 * read or written. Identity flows ONLY via `CLAUDE_CODE_SESSION_ID` in the
 * subprocess env.
 *
 * RED TODAY by absence: `.claude/scripts/activate-commissioned-fresh-session.php`
 * does not exist.
 */
class ActivateCommissionedFreshSessionContractTest extends TestCase
{
    private const CAPABILITY = '.claude/scripts/activate-commissioned-fresh-session.php';
    private const MECHANISM = '.claude/scripts/workflow-state.php';
    private const BOOTSTRAP = '.claude/scripts/session-bootstrap.php';
    private const NEXT_ACTOR = '.claude/scripts/next-actor-orchestration.php';
    private const OPERATING_MODEL = '.claude/scripts/operating-model.php';

    private string $repoRoot;
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repoRoot = \dirname(__DIR__, 4);
        $this->dir = sys_get_temp_dir() . '/kos-act-' . bin2hex(random_bytes(6));
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

    private function requireCapability(): void
    {
        $this->assertFileExists($this->repoRoot . '/' . self::CAPABILITY,
            'ActivateCommissionedFreshSession does not exist yet — RED by absence (plan §6 step 3)');
    }

    /** @return array{code:int,out:?array,raw:string,err:string} */
    private function activate(array $args, array $env = []): array
    {
        $cmd = array_merge(
            ['php', $this->repoRoot . '/' . self::CAPABILITY, '--dir=' . $this->dir, '--json'],
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

    /** @return array{code:int,raw:string,err:string} */
    private function runRaw(array $cmd, array $env = []): array
    {
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null,
            $env === [] ? null : array_merge(getenv(), $env));
        $raw = stream_get_contents($pipes[1]);
        $err = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return ['code' => proc_close($proc), 'raw' => $raw, 'err' => $err];
    }

    /** AST-015 fixture builder — asserts exit 0 (never hand-writes JSON). */
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

    /** AST-015 fold → parsed authoritative state. */
    private function foldOf(string $wi): array
    {
        $cmd = ['php', $this->repoRoot . '/' . self::MECHANISM, 'fold', $wi, '--dir=' . $this->dir];
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $raw = stream_get_contents($pipes[1]);
        $err = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $this->assertSame(0, proc_close($proc), 'fold failed: ' . $err);

        return json_decode($raw, true) ?? [];
    }

    /** init a fresh record with the standard role set (no lane). */
    private function initEmpty(string $wi): void
    {
        $this->mechanism(['init', $wi, '--workflow=platform-implementation',
            '--roles=governance,architecture,implementation,verification']);
    }

    /** Append one lane (REGISTER→HANDOFF→START±terminal) to an EXISTING record. */
    private function recordLane(string $wi, string $session, string $role, string $finalState = 'ACTIVE', ?string $label = null): void
    {
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

    /** init + one lane (single-lane fixtures). */
    private function record(string $wi, string $session, string $role, string $finalState = 'ACTIVE', ?string $label = null): void
    {
        $this->initEmpty($wi);
        $this->recordLane($wi, $session, $role, $finalState, $label);
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

    /** AST-017 bootstrap (read-only fresh-session / attribution checks). */
    private function bootstrap17(array $args = []): array
    {
        $cmd = array_merge(
            ['php', $this->repoRoot . '/' . self::BOOTSTRAP, '--dir=' . $this->dir, '--json'],
            $args
        );
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $raw = stream_get_contents($pipes[1]);
        $err = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return ['code' => proc_close($proc), 'out' => json_decode($raw, true), 'raw' => $raw, 'err' => $err];
    }

    private function nextActorRaw(string $wi): string
    {
        $cmd = ['php', $this->repoRoot . '/' . self::NEXT_ACTOR, 'next-actor', $wi, '--dir=' . $this->dir, '--json'];
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $raw = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);

        return $raw;
    }

    private function bootstrap17Raw(array $args): string
    {
        $cmd = array_merge(
            ['php', $this->repoRoot . '/' . self::BOOTSTRAP, '--dir=' . $this->dir, '--json'],
            $args
        );
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $raw = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);

        return $raw;
    }

    private function operatingModelRaw(string $command, string $wi): string
    {
        $cmd = ['php', $this->repoRoot . '/' . self::OPERATING_MODEL, $command, $wi, '--dir=' . $this->dir, '--json'];
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $raw = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);

        return $raw;
    }

    private function gitClean(string $repoRelativePath): bool
    {
        $cmd = ['git', '-C', $this->repoRoot, 'diff', '--quiet', 'HEAD', '--', $repoRelativePath];
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        stream_get_contents($pipes[1]);
        stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return proc_close($proc) === 0;
    }

    private const UUID = '/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/';

    // ─── REPAIR-001 harness (GO-26…GO-30 only; the harness above is untouched) ─

    /**
     * The ONE state that arms F-1: a live `mutationOwner` with no ACTIVE,
     * CREATED or HANDED_OFF lane. In AST-015's fold `COMPLETE` and `HANDOFF`
     * clear the owner — `FAIL` does not — so a lane that STARTed and then FAILed
     * keeps ownership while AST-018 still reports NEXT_ACTOR_REQUIRED. Built
     * through the qualified mechanism only, never by hand-writing JSON.
     */
    private function armedOwnerFixture(string $wi): void
    {
        $this->record($wi, 'IMPL', 'implementation', 'COMPLETED', 'IMPLARM'); // COMPLETE clears the owner
        $this->recordLane($wi, 'OWNER', 'architecture', 'FAILED');            // START→FAIL keeps it: owner = OWNER
    }

    /** Test-side read of the hermetic temp record (the capability itself never reads a record). */
    private function recordedTransitions(string $wi): array
    {
        return json_decode((string) file_get_contents($this->dir . '/' . $wi . '.json'), true)['transitions'] ?? [];
    }

    private function lastTransitionOfType(string $wi, string $type): ?array
    {
        $found = null;
        foreach ($this->recordedTransitions($wi) as $t) {
            if (($t['type'] ?? '') === $type) {
                $found = $t;
            }
        }

        return $found;
    }

    /**
     * A forwarding proxy over AST-015, injected through the same
     * KOS_MECHANISM_PATH seam GO-20 already uses. It forwards every invocation
     * verbatim and writes nothing of its own; it only refuses one chosen
     * `append` type, or doctors the post-write `fold` so the outcome
     * verification disagrees. A test vehicle — it neither repairs nor mitigates
     * F-5.
     *
     * The post-write fold is identified by CONTENT, not by call order: it is the
     * only fold reporting $doctorWhenActive as ACTIVE. Ordering would be wrong —
     * AST-017 honours KOS_MECHANISM_PATH too and folds through this proxy while
     * the capability is still analysing.
     */
    private function stubMechanism(?string $failAppendType, ?string $doctorWhenActive = null): string
    {
        $path = $this->dir . '/proxy-mechanism.php';
        $head = '<?php' . "\n"
            . '$real = ' . var_export($this->repoRoot . '/' . self::MECHANISM, true) . ";\n"
            . '$fail = ' . var_export($failAppendType, true) . ";\n"
            . '$doctor = ' . var_export($doctorWhenActive, true) . ";\n";
        $body = <<<'STUB'
            $args = array_slice($argv, 1);
            if ($fail !== null && ($args[0] ?? '') === 'append') {
                foreach ($args as $a) {
                    if (str_starts_with($a, '--json=')) {
                        $t = json_decode(substr($a, 7), true)['type'] ?? '';
                        if ($t === $fail) {
                            fwrite(STDERR, "injected failure: the {$t} append was refused by the test proxy\n");
                            exit(65);
                        }
                    }
                }
            }
            $p = proc_open(array_merge(['php', $real], $args), [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
            $out = stream_get_contents($pipes[1]);
            $err = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $code = proc_close($p);
            if ($doctor !== null && ($args[0] ?? '') === 'fold' && $code === 0) {
                $fold = json_decode($out, true);
                if (($fold['sessions'][$doctor]['state'] ?? null) === 'ACTIVE') {
                    $fold['mutationOwner'] = 'SOMEONE-ELSE';  // the post-write fold disagrees
                    $out = json_encode($fold);
                }
            }
            fwrite(STDOUT, $out);
            fwrite(STDERR, $err);
            exit($code);
            STUB;
        file_put_contents($path, $head . $body . "\n");

        return $path;
    }

    /**
     * Run the capability under explicit php.ini flags. `display_errors` is an
     * environment default, so the --json contract must be asserted under it
     * rather than under whatever this host's php.ini happens to be (F-2).
     *
     * @return array{code:int,out:?array,raw:string,err:string}
     */
    private function activateIni(array $ini, array $args, array $env = []): array
    {
        $flags = [];
        foreach ($ini as $k => $v) {
            $flags[] = '-d';
            $flags[] = $k . '=' . $v;
        }
        $r = $this->runRaw(array_merge(['php'], $flags,
            [$this->repoRoot . '/' . self::CAPABILITY, '--dir=' . $this->dir, '--json'], $args), $env);
        $r['out'] = json_decode($r['raw'], true);

        return $r;
    }

    /** php.ini flags that put every diagnostic on STDERR, deterministically. */
    private const INI_ERRORS_TO_STDERR = ['display_errors' => 'stderr', 'error_reporting' => '-1', 'log_errors' => '0'];

    /** php.ini flags of an ordinary dev/CI host: diagnostics on STDOUT (the F-2 condition). */
    private const INI_ERRORS_TO_STDOUT = ['display_errors' => '1', 'error_reporting' => '-1', 'log_errors' => '0'];

    // ─── GO-01 … GO-05 · the unified binding model — first + subsequent ─────

    public function test_go01_first_governance_session_binds_from_human_business_order(): void
    {
        $this->requireCapability();
        $this->initEmpty('WI-GO01');

        $r = $this->activate(['activate', '--work-item=WI-GO01', '--requested-role=governance',
            '--human-act=I want Governance Engineer.'], ['CLAUDE_CODE_SESSION_ID' => 'GO1']);

        $this->assertSame(0, $r['code'], 'first-binding self-activation must succeed: ' . $r['err']);
        $this->assertSame('ACTIVATED', $r['out']['result']);
        $this->assertSame('governance', $r['out']['role']);
        $this->assertSame('HUMAN_BUSINESS_ORDER', $r['out']['commissionSource']);
        $this->assertSame('GO1', $r['out']['session'], 'the runtime identity is bound, never a CLI arg');
        $this->assertSame([], $r['out']['independentOf'], 'no registered lane and no declared exclusion');

        $fold = $this->foldOf('WI-GO01');
        $this->assertSame('governance', $fold['sessions']['GO1']['role']);
        $this->assertSame('ACTIVE', $fold['sessions']['GO1']['state']);
        $this->assertNull($fold['sessions']['GO1']['predecessor'], 'first-binding bootstrap lineage: predecessor null');
        $this->assertSame('GO1', $fold['mutationOwner']);
        $this->assertSame('OPEN', $fold['workItemState']);
    }

    public function test_go02_human_never_supplies_a_session_uuid(): void
    {
        $this->requireCapability();
        $this->initEmpty('WI-GO02');

        // a session identity option is rejected by the generic unknown-option path (exit 64)
        $r = $this->activate(['activate', '--work-item=WI-GO02', '--requested-role=governance',
            '--session=GO2', '--human-act=I want Governance Engineer.'], ['CLAUDE_CODE_SESSION_ID' => 'GO2']);
        $this->assertSame(64, $r['code'], 'session identity is never a CLI argument');

        // successful activation's human rendering carries no UUID
        $cmd = ['php', $this->repoRoot . '/' . self::CAPABILITY, '--dir=' . $this->dir,
            'activate', '--work-item=WI-GO02', '--requested-role=governance',
            '--human-act=I want Governance Engineer.'];
        $h = $this->runRaw($cmd, ['CLAUDE_CODE_SESSION_ID' => 'GO2']);
        $this->assertSame(0, $h['code'], $h['err']);
        $this->assertDoesNotMatchRegularExpression(self::UUID, $h['raw'],
            'the human output never exposes a process identity / UUID');
    }

    public function test_go03_runtime_identity_discovered_automatically_and_attributable_after_binding(): void
    {
        $this->requireCapability();
        $this->initEmpty('WI-GO03');

        $r = $this->activate(['activate', '--work-item=WI-GO03', '--requested-role=governance',
            '--human-act=I want Governance Engineer.'], ['CLAUDE_CODE_SESSION_ID' => 'GO3']);
        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertSame('GO3', $r['out']['session'], 'identity discovered from the runtime, never a CLI arg');

        $fold = $this->foldOf('WI-GO03');
        $this->assertArrayHasKey('GO3', $fold['sessions'], 'REGISTER.session === the env identity');
        $this->assertSame('GO3', $fold['mutationOwner']);

        // after binding, AST-017 attributes THIS process to the governance lane
        $b = $this->bootstrap17(['--process-label=GO3', '--work-item=WI-GO03']);
        $this->assertSame('RESOLVED', $b['out']['verdict']);
        $this->assertSame('MATCH', $b['out']['identity']['attribution']);
        $this->assertSame('GO3', $b['out']['assignment']['lane']);
        $this->assertSame('governance', $b['out']['assignment']['role']);
        $this->assertSame('ACTIVE', $b['out']['assignment']['workflow_state']);
    }

    public function test_go04_human_order_creates_intended_responsibility_on_subsequent_commission(): void
    {
        $this->requireCapability();
        $this->record('WI-GO04', 'IMPL', 'implementation', 'COMPLETED', 'IMPL04');

        $r = $this->activate(['activate', '--work-item=WI-GO04', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO04.'], ['CLAUDE_CODE_SESSION_ID' => 'GO4']);

        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertSame('verification', $r['out']['role']);
        $this->assertSame('NEXT_ACTOR_REQUIRED', $r['out']['commissionSource']);
        $this->assertSame('I want a Verification session for WI-GO04.', $r['out']['recordedHumanAct'],
            'the verbatim human business instruction is recorded');

        $fold = $this->foldOf('WI-GO04');
        $this->assertSame('verification', $fold['sessions']['GO4']['role']);
        $this->assertSame('ACTIVE', $fold['sessions']['GO4']['state']);
    }

    public function test_go05_governance_adoption_review_starts_from_human_order_not_a_lane(): void
    {
        $this->requireCapability();
        // the commission §29 current-problem case: architecture + verification COMPLETED
        $this->initEmpty('WI-GO05');
        $this->recordLane('WI-GO05', 'ARCH', 'architecture', 'COMPLETED', 'ARCH05');
        $this->recordLane('WI-GO05', 'VER', 'verification', 'COMPLETED', 'VER05');

        $r = $this->activate(['activate', '--work-item=WI-GO05', '--requested-role=governance',
            '--human-act=Start the Governance adoption review of KOS-OPERATING-MODEL-001'],
            ['CLAUDE_CODE_SESSION_ID' => 'GO5']);

        $this->assertSame(0, $r['code'],
            '"no lane" must NOT mean "refuse" — a fresh eligible governance session binds: ' . $r['err']);
        $this->assertSame('ACTIVATED', $r['out']['result']);
        $this->assertSame('governance', $r['out']['role']);
        $this->assertSame('NEXT_ACTOR_REQUIRED', $r['out']['commissionSource']);

        $fold = $this->foldOf('WI-GO05');
        $this->assertSame('governance', $fold['sessions']['GO5']['role']);
        $this->assertSame('ACTIVE', $fold['sessions']['GO5']['state']);
        $this->assertSame('GO5', $fold['mutationOwner']);
    }

    // ─── GO-06 … GO-14 · constrained binding + fail-closed validation ───────

    public function test_go06_subsequent_verification_self_binds_and_undeclared_role_fails(): void
    {
        $this->requireCapability();
        $this->record('WI-GO06', 'IMPL', 'implementation', 'COMPLETED', 'IMPL06');

        $r = $this->activate(['activate', '--work-item=WI-GO06', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO06.'], ['CLAUDE_CODE_SESSION_ID' => 'GO6']);
        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertSame('verification', $r['out']['role']);

        // a role NOT in the work item's declared role set fails closed, nothing written
        $this->record('WI-GO06b', 'IMPL6b', 'implementation', 'COMPLETED', 'IMPL6B');
        $before = $this->directoryFingerprint();
        $bad = $this->activate(['activate', '--work-item=WI-GO06b', '--requested-role=not-a-role',
            '--human-act=I want this.'], ['CLAUDE_CODE_SESSION_ID' => 'GO6B']);
        $this->assertSame(65, $bad['code']);
        $this->assertSame('ROLE_UNDECLARED', $bad['out']['reason']);
        $this->assertFalse($bad['out']['transitionWritten']);
        $this->assertSame($before, $this->directoryFingerprint());
    }

    public function test_go07_existing_commission_is_honored_and_duplicate_register_is_refused(): void
    {
        $this->requireCapability();
        $this->record('WI-GO07', 'IMPL', 'implementation', 'COMPLETED', 'IMPL07');

        $r = $this->activate(['activate', '--work-item=WI-GO07', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO07.'], ['CLAUDE_CODE_SESSION_ID' => 'GO7']);
        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertSame('verification', $r['out']['role'],
            'the binding uses the commissioned next role, not a self-chosen role');

        // the SAME runtime identity attempting a second lane on the same work item → NOT_ELIGIBLE (R8 backstop)
        $second = $this->activate(['activate', '--work-item=WI-GO07', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO07.'], ['CLAUDE_CODE_SESSION_ID' => 'GO7']);
        $this->assertSame(65, $second['code']);
        $this->assertSame('NOT_ELIGIBLE', $second['out']['reason']);
        $this->assertFalse($second['out']['transitionWritten']);

        $fold = $this->foldOf('WI-GO07');
        $verification = array_values(array_filter($fold['sessions'], fn ($s) => $s['role'] === 'verification'));
        $this->assertCount(1, $verification, 'no second verification lane (R8 — role immutable)');
    }

    public function test_go08_requested_role_mismatch_fails_closed_prompt_vs_commission(): void
    {
        $this->requireCapability();
        $this->record('WI-GO08', 'IMPL', 'implementation', 'COMPLETED', 'IMPL08');
        $before = $this->directoryFingerprint();

        $r = $this->activate(['activate', '--work-item=WI-GO08', '--requested-role=architecture',
            '--human-act=I want a Verification session for WI-GO08.'], ['CLAUDE_CODE_SESSION_ID' => 'GO8']);

        $this->assertSame(65, $r['code']);
        $this->assertSame('MISMATCH', $r['out']['reason'],
            'prompt says architecture, the commission says verification → MISMATCH → STOP');
        $this->assertFalse($r['out']['transitionWritten']);
        $this->assertSame($before, $this->directoryFingerprint());
    }

    public function test_go09_wrong_work_item_fails_closed(): void
    {
        $this->requireCapability();

        // missing --work-item → usage 64
        $r = $this->activate(['activate', '--requested-role=governance', '--human-act=x'],
            ['CLAUDE_CODE_SESSION_ID' => 'GO9']);
        $this->assertSame(64, $r['code'], 'a missing work-item is a usage error');

        // record absent → exit 65, absence never implies ownership
        $r2 = $this->activate(['activate', '--work-item=WI-NOPE', '--requested-role=governance', '--human-act=x'],
            ['CLAUDE_CODE_SESSION_ID' => 'GO9']);
        $this->assertSame(65, $r2['code']);
        $this->assertSame('WORK_ITEM_UNKNOWN', $r2['out']['reason']);
        $this->assertFalse($r2['out']['transitionWritten']);
    }

    public function test_go10_empty_runtime_identity_fails_closed_with_nothing_written(): void
    {
        $this->requireCapability();
        $this->initEmpty('WI-GO10');

        $r = $this->activate(['activate', '--work-item=WI-GO10', '--requested-role=governance',
            '--human-act=I want Governance Engineer.'], ['CLAUDE_CODE_SESSION_ID' => '']);

        $this->assertSame(65, $r['code']);
        $this->assertSame('NO_RUNTIME_IDENTITY', $r['out']['reason']);
        $this->assertFalse($r['out']['transitionWritten']);
        $this->assertSame(['WI-GO10.json' => $this->directoryFingerprint()['WI-GO10.json']] ?? [],
            array_intersect_key($this->directoryFingerprint(), ['WI-GO10.json' => true]),
            'nothing was written — the record is unchanged');
    }

    public function test_go11_no_human_order_and_no_commission_fails_closed(): void
    {
        $this->requireCapability();
        $this->initEmpty('WI-GO11');
        $before = $this->directoryFingerprint();

        $r = $this->activate(['activate', '--work-item=WI-GO11', '--requested-role=governance'],
            ['CLAUDE_CODE_SESSION_ID' => 'GO11']);

        $this->assertSame(65, $r['code']);
        $this->assertSame('HUMAN_DECISION_REQUIRED', $r['out']['reason']);
        $this->assertFalse($r['out']['transitionWritten']);
        $this->assertSame($before, $this->directoryFingerprint());
    }

    public function test_go12_barred_identity_fails_eligibility(): void
    {
        $this->requireCapability();
        // the identity IS already a registered lane on this work item (producer/verifier bar)
        $this->record('WI-GO12', 'GO12', 'implementation', 'ACTIVE', 'GO12LABEL');

        $r = $this->activate(['activate', '--work-item=WI-GO12', '--requested-role=verification',
            '--human-act=I want a Verification session.'], ['CLAUDE_CODE_SESSION_ID' => 'GO12']);

        $this->assertSame(65, $r['code']);
        $this->assertSame('NOT_ELIGIBLE', $r['out']['reason']);
        $this->assertFalse($r['out']['transitionWritten']);
    }

    public function test_go13_conflicting_assignment_fails_closed_activation_pending(): void
    {
        $this->requireCapability();
        $this->record('WI-GO13', 'S13', 'implementation', 'CREATED', 'S13LABEL'); // assigned, not started
        $before = $this->directoryFingerprint();

        $r = $this->activate(['activate', '--work-item=WI-GO13', '--requested-role=implementation',
            '--human-act=I want to implement.'], ['CLAUDE_CODE_SESSION_ID' => 'GO13']);

        $this->assertSame(65, $r['code']);
        $this->assertSame('CONFLICTING_ASSIGNMENT', $r['out']['reason'],
            'an assigned-but-not-activated lane means ACTIVATION_PENDING — a fresh session cannot bind over it');
        $this->assertFalse($r['out']['transitionWritten']);
        $this->assertSame($before, $this->directoryFingerprint());
    }

    public function test_go14_human_act_is_never_fabricated(): void
    {
        $this->requireCapability();
        $this->record('WI-GO14', 'IMPL', 'implementation', 'COMPLETED', 'IMPL14');
        $before = $this->directoryFingerprint();

        // no --human-act → no write at all, fail closed
        $r = $this->activate(['activate', '--work-item=WI-GO14', '--requested-role=verification'],
            ['CLAUDE_CODE_SESSION_ID' => 'GO14']);
        $this->assertSame(65, $r['code']);
        $this->assertSame('HUMAN_DECISION_REQUIRED', $r['out']['reason']);
        $this->assertFalse($r['out']['transitionWritten']);
        $this->assertSame($before, $this->directoryFingerprint());
        $fold = $this->foldOf('WI-GO14');
        $this->assertArrayNotHasKey('GO14', $fold['sessions'], 'no REGISTER without a human act (G-3)');
        $this->assertNull($fold['mutationOwner']);

        // with the verbatim act → START.humanAct === verbatim, recordedBy human
        $r2 = $this->activate(['activate', '--work-item=WI-GO14', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO14.'], ['CLAUDE_CODE_SESSION_ID' => 'GO14']);
        $this->assertSame(0, $r2['code'], $r2['err']);
        $this->assertSame('I want a Verification session for WI-GO14.', $r2['out']['recordedHumanAct']);

        $rec = json_decode((string) file_get_contents($this->dir . '/WI-GO14.json'), true);
        $start = $rec['transitions'][count($rec['transitions']) - 1];
        $this->assertSame('START', $start['type']);
        $this->assertSame('human', $start['recordedBy']);
        $this->assertSame('I want a Verification session for WI-GO14.', $start['humanAct']);
    }

    // ─── GO-15 … GO-18 · mechanism discipline — canonical AST-015 sole writer ─

    public function test_go15_canonical_ast015_mechanism_is_the_sole_writer(): void
    {
        $this->requireCapability();
        $src = (string) file_get_contents($this->repoRoot . '/' . self::CAPABILITY);

        $this->assertStringContainsString('workflow-state.php', $src, 'invokes the canonical mechanism');
        $this->assertStringNotContainsString('/runtime/workflow', $src, 'no store-path knowledge');
        $this->assertStringNotContainsString('file_put_contents', $src, 'no direct record write');
        $this->assertStringNotContainsString('rename(', $src, 'no atomic-replace write');

        // behavioral: the only mutation is AST-015's append — post-activate fold shows ACTIVE
        $this->initEmpty('WI-GO15');
        $r = $this->activate(['activate', '--work-item=WI-GO15', '--requested-role=governance',
            '--human-act=I want Governance Engineer.'], ['CLAUDE_CODE_SESSION_ID' => 'GO15']);
        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertSame('ACTIVE', $this->foldOf('WI-GO15')['sessions']['GO15']['state']);
    }

    public function test_go16_ast017_remains_read_only_and_byte_unchanged(): void
    {
        $this->requireCapability();
        $this->assertTrue($this->gitClean('.claude/scripts/session-bootstrap.php'),
            'AST-017 must be byte-unchanged vs HEAD — the capability consumes it read-only');

        $this->record('WI-GO16', 'IMPL', 'implementation', 'COMPLETED', 'IMPL16');
        $before = $this->directoryFingerprint();
        $this->bootstrap17(['--process-label=FRESH16', '--work-item=WI-GO16']);
        $this->assertSame($before, $this->directoryFingerprint(),
            'a bootstrap run on the fixture leaves the record directory byte-identical');
    }

    public function test_go17_ast018_stays_within_its_boundary_no_appointment_engine(): void
    {
        $this->requireCapability();
        $this->assertTrue($this->gitClean('.claude/scripts/next-actor-orchestration.php'),
            'AST-018 must be byte-unchanged vs HEAD');

        $src = (string) file_get_contents($this->repoRoot . '/' . self::CAPABILITY);
        $this->assertStringContainsString('next-actor', $src,
            'consumes AST-018 next-actor (read-only authoritative commission)');
        $this->assertStringNotContainsString("'appoint'", $src,
            'the capability must NOT invoke AST-018 appoint — it is a constrained binding, not a second appointment engine');
        $this->assertStringNotContainsString('"appoint"', $src);
    }

    public function test_go18_no_direct_workflow_json_mutation_decoys_are_tolerated(): void
    {
        $this->requireCapability();
        $this->record('WI-GO18', 'IMPL', 'implementation', 'COMPLETED', 'IMPL18');

        // poison the raw record with decoy top-level facts (S-16 pattern)
        $path = $this->dir . '/WI-GO18.json';
        $rec = json_decode((string) file_get_contents($path), true);
        $rec['mutationOwner'] = 'DECOY-OWNER';
        $rec['workItemState'] = 'DECOY-STATE';
        $rec['sessions'] = ['IMPL' => ['state' => 'COMPLETED', 'role' => 'decoy-role']];
        file_put_contents($path, json_encode($rec, JSON_PRETTY_PRINT));

        $r = $this->activate(['activate', '--work-item=WI-GO18', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO18.'], ['CLAUDE_CODE_SESSION_ID' => 'GO18']);

        $this->assertSame(0, $r['code'], 'the fold-derived truth wins over the decoys: ' . $r['err']);
        $fold = $this->foldOf('WI-GO18');
        $this->assertSame('ACTIVE', $fold['sessions']['GO18']['state']);
        $this->assertSame('GO18', $fold['mutationOwner']);
    }

    // ─── GO-19 … GO-22 · human-facing contract + honest escalation ──────────

    public function test_go19_human_facing_output_has_no_uuid_mechanics(): void
    {
        $this->requireCapability();
        $this->record('WI-GO19', 'IMPL', 'implementation', 'COMPLETED', 'IMPL19');

        $cmd = ['php', $this->repoRoot . '/' . self::CAPABILITY, '--dir=' . $this->dir,
            'activate', '--work-item=WI-GO19', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO19.'];
        $h = $this->runRaw($cmd, ['CLAUDE_CODE_SESSION_ID' => 'GO19']);

        $this->assertSame(0, $h['code'], $h['err']);
        foreach (['REGISTER', 'HANDOFF', 'START', 'mutationOwner', 'predecessor', 'claude-code-session:', 'GO19'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $h['raw'],
                "the human rendering must not expose '{$forbidden}'");
        }
        $this->assertDoesNotMatchRegularExpression(self::UUID, $h['raw']);
    }

    public function test_go20_honest_capability_gap_reporting(): void
    {
        $this->requireCapability();

        // corrupt record → WORK_ITEM_UNREADABLE, names the mechanism's refusal, nothing written
        file_put_contents($this->dir . '/WI-GO20.json', '{ corrupt');
        $before = $this->directoryFingerprint();
        $r = $this->activate(['activate', '--work-item=WI-GO20', '--requested-role=governance',
            '--human-act=x'], ['CLAUDE_CODE_SESSION_ID' => 'GO20']);
        $this->assertSame(65, $r['code']);
        $this->assertSame('WORK_ITEM_UNREADABLE', $r['out']['reason']);
        $this->assertFalse($r['out']['transitionWritten']);
        $this->assertSame($before, $this->directoryFingerprint());

        // missing mechanism → MECHANISM_UNAVAILABLE, names the mechanism + responsible next actor
        $this->initEmpty('WI-GO20b');
        $r2 = $this->activate(['activate', '--work-item=WI-GO20b', '--requested-role=governance',
            '--human-act=I want Governance Engineer.'], ['CLAUDE_CODE_SESSION_ID' => 'GO20',
            'KOS_MECHANISM_PATH' => '/nonexistent/workflow-state.php']);
        $this->assertSame(65, $r2['code']);
        $this->assertSame('MECHANISM_UNAVAILABLE', $r2['out']['reason']);
        $this->assertStringContainsStringIgnoringCase('workflow-state.php', json_encode($r2['out']));
        $this->assertFalse($r2['out']['transitionWritten']);
    }

    public function test_go21_stopped_work_item_is_an_honest_inv_e_blocker_never_a_continuation_write(): void
    {
        $this->requireCapability();

        // STOPPED work item → WORK_ITEM_STOPPED; the required CONTINUATION is named, nothing written
        $this->record('WI-GO21', 'IMPL', 'implementation', 'STOPPED', 'IMPL21');
        $before = $this->directoryFingerprint();
        $r = $this->activate(['activate', '--work-item=WI-GO21', '--requested-role=verification',
            '--human-act=Continue this.'], ['CLAUDE_CODE_SESSION_ID' => 'GO21']);
        $this->assertSame(65, $r['code']);
        $this->assertSame('WORK_ITEM_STOPPED', $r['out']['reason']);
        $this->assertStringContainsStringIgnoringCase('continuation', json_encode($r['out']),
            'the honest Inv-E blocker names the required CONTINUATION by governance or a person');
        $this->assertFalse($r['out']['transitionWritten']);
        $this->assertSame($before, $this->directoryFingerprint());

        $src = (string) file_get_contents($this->repoRoot . '/' . self::CAPABILITY);
        $this->assertStringNotContainsString("'CONTINUATION'", $src,
            'the capability never writes CONTINUATION — Inv E is reserved to governance/human');

        // DECIDE-option (governance COMPLETED; adoption is the human's decision) → refusal
        $this->record('WI-GO21b', 'GOV', 'governance', 'COMPLETED', 'GOV21');
        $r2 = $this->activate(['activate', '--work-item=WI-GO21b', '--requested-role=governance',
            '--human-act=I want Governance.'], ['CLAUDE_CODE_SESSION_ID' => 'GO21B']);
        $this->assertSame(65, $r2['code']);
        $this->assertSame('ADOPTION_IS_HUMAN_DECISION', $r2['out']['reason']);
        $this->assertFalse($r2['out']['transitionWritten']);
    }

    public function test_go22_adoption_stays_separate_from_activation(): void
    {
        $this->requireCapability();
        $this->record('WI-GO22', 'IMPL', 'implementation', 'COMPLETED', 'IMPL22');

        $r = $this->activate(['activate', '--work-item=WI-GO22', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO22.'], ['CLAUDE_CODE_SESSION_ID' => 'GO22']);

        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertSame('ACTIVATED', $r['out']['result'], 'the bound lane reports ACTIVATED, never ADOPTED/AUTHORIZED');
        $this->assertStringContainsStringIgnoringCase('not adoption', $r['out']['caveat'] ?? '',
            'the caveat states activation ≠ adoption');
        $this->assertStringNotContainsString('adopted', strtolower($r['raw']));
        $this->assertStringNotContainsString('authorized', strtolower($r['raw']));
    }

    // ─── GO-23 … GO-25 · determinism · provider-independence · read purity ──

    public function test_go23_cross_provider_check_is_byte_identical(): void
    {
        $this->requireCapability();
        $this->record('WI-GO23', 'IMPL', 'implementation', 'COMPLETED', 'IMPL23');

        $args = ['check', '--work-item=WI-GO23', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO23.'];
        $claude = ['CLAUDE_CODE_SESSION_ID' => 'GO23',
            'ANTHROPIC_MODEL' => 'claude-sonnet-4-6', 'ANTHROPIC_BASE_URL' => 'https://api.anthropic.com',
            'ANTHROPIC_AUTH_TOKEN' => 'claude-token'];
        $deepseek = ['CLAUDE_CODE_SESSION_ID' => 'GO23',
            'ANTHROPIC_MODEL' => 'deepseek-chat', 'ANTHROPIC_BASE_URL' => 'https://api.deepseek.com',
            'ANTHROPIC_AUTH_TOKEN' => 'deepseek-token'];

        $a = $this->activate($args, $claude);
        $b = $this->activate($args, $deepseek);

        $this->assertSame(0, $a['code'], $a['err']);
        $this->assertSame('READY', $a['out']['result'], 'precondition: an eligible fresh session checks READY');
        $this->assertSame($a['raw'], $b['raw'],
            'the harness — not the model endpoint — determines the result (byte-identical JSON)');
    }

    public function test_go24_check_is_deterministic_and_writes_nothing(): void
    {
        $this->requireCapability();
        $this->record('WI-GO24', 'IMPL', 'implementation', 'COMPLETED', 'IMPL24');
        $before = $this->directoryFingerprint();

        $args = ['check', '--work-item=WI-GO24', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO24.'];
        $a = $this->activate($args, ['CLAUDE_CODE_SESSION_ID' => 'GO24']);
        $b = $this->activate($args, ['CLAUDE_CODE_SESSION_ID' => 'GO24']);

        $this->assertSame(0, $a['code'], $a['err']);
        $this->assertSame($a['raw'], $b['raw'], 'JSON rendering must be byte-identical across runs');
        $this->assertSame($before, $this->directoryFingerprint(),
            'check is read-only — the record directory is byte-identical after two checks');
    }

    public function test_go25_read_only_paths_remain_byte_identical(): void
    {
        $this->requireCapability();
        $this->record('WI-GO25', 'IMPL', 'implementation', 'COMPLETED', 'IMPL25');

        $bootBefore = $this->bootstrap17Raw(['--process-label=FRESH25', '--work-item=WI-GO25']);
        $naBefore = $this->nextActorRaw('WI-GO25');
        $omBefore = $this->operatingModelRaw('outcome', 'WI-GO25');

        $this->activate(['check', '--work-item=WI-GO25', '--requested-role=verification', '--human-act=x'],
            ['CLAUDE_CODE_SESSION_ID' => 'GO25']);

        $this->assertSame($bootBefore, $this->bootstrap17Raw(['--process-label=FRESH25', '--work-item=WI-GO25']),
            'AST-017 bootstrap output is byte-identical before/after check');
        $this->assertSame($naBefore, $this->nextActorRaw('WI-GO25'),
            'AST-018 next-actor output is byte-identical before/after check');
        $this->assertSame($omBefore, $this->operatingModelRaw('outcome', 'WI-GO25'),
            'operating-model outcome output is byte-identical before/after check');

        foreach (['.claude/scripts/session-bootstrap.php', '.claude/scripts/next-actor-orchestration.php',
            '.claude/scripts/operating-model.php'] as $canonical) {
            $this->assertTrue($this->gitClean($canonical),
                "{$canonical} must be byte-unchanged vs HEAD (read-only paths stay untouched)");
        }
    }

    // ─── GO-26 … GO-30 · REPAIR-001 · the findings of the independent
    //     verification (F-1 blocking · F-3 · F-4 · F-2) and the structural
    //     coverage gap O-1 that hid F-1 from GO-01…GO-25 ────────────────────

    /**
     * GO-26 · O-1 + F-1 — the write path, reached with a LIVE mutationOwner.
     *
     * O-1 is why 25/25 green could not see F-1: GO-01/03/05/15/18 use an empty
     * or first-lane item, GO-04/06/23/24/25 use a COMPLETED predecessor (which
     * clears the owner), and every other non-null-owner shape is refused earlier
     * by GO-13/GO-21. The write path was exercised only where owner is
     * legitimately null — exactly where the defect is invisible.
     */
    public function test_go26_repair001_live_mutation_owner_is_derived_from_the_authoritative_fold(): void
    {
        $this->requireCapability();
        $this->armedOwnerFixture('WI-GO26');

        // the fixture is genuinely armed, asserted rather than assumed
        $fold = $this->foldOf('WI-GO26');
        $this->assertSame('OWNER', $fold['mutationOwner'], 'the fixture must present a LIVE mutation owner');
        $this->assertSame('OPEN', $fold['workItemState']);
        $this->assertSame('COMPLETED', $fold['sessions']['IMPL']['state']);
        $this->assertSame('FAILED', $fold['sessions']['OWNER']['state'],
            'no ACTIVE/CREATED/HANDED_OFF lane — otherwise GO-13 refuses before the write path');
        $na = json_decode($this->nextActorRaw('WI-GO26'), true);
        $this->assertSame('NEXT_ACTOR_REQUIRED', $na['result']);
        $this->assertSame('verification', $na['role']);

        // the read-only sibling exists to PREDICT the write — it must not mispredict
        $check = $this->activate(['check', '--work-item=WI-GO26', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO26.'], ['CLAUDE_CODE_SESSION_ID' => 'GO26']);
        $this->assertSame('READY', $check['out']['result']);

        $r = $this->activate(['activate', '--work-item=WI-GO26', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO26.'], ['CLAUDE_CODE_SESSION_ID' => 'GO26']);

        $this->assertSame(0, $r['code'],
            'check promised READY; activate must not half-write and stop — the record is append-only: ' . $r['raw']);
        $this->assertSame('ACTIVATED', $r['out']['result']);
        $this->assertSame(['REGISTER', 'HANDOFF', 'START'], $r['out']['transitions']);

        $after = $this->foldOf('WI-GO26');
        $this->assertSame('GO26', $after['mutationOwner']);
        $this->assertSame('ACTIVE', $after['sessions']['GO26']['state']);
        $this->assertSame('OWNER', $after['sessions']['GO26']['predecessor'],
            'REGISTER.predecessor must derive from the live mutationOwner, never from a forced null (F-1)');

        $handoff = $this->lastTransitionOfType('WI-GO26', 'HANDOFF');
        $this->assertSame('OWNER', $handoff['from'],
            'HANDOFF.from must derive from the live mutationOwner (F-1) — AST-015 refuses from=null while an owner exists');
        $this->assertSame('GO26', $handoff['to']);
    }

    /**
     * GO-27 · F-3 — one machine-readable result shape on BOTH paths.
     * The docblock promises array{ok:bool,…} and the caller tests $written['ok'];
     * the failure path returned the refusal() shape, which has no 'ok'. The
     * observable symptom is an undefined-key read on every partial write.
     */
    public function test_go27_repair001_failure_paths_return_the_documented_result_shape(): void
    {
        $this->requireCapability();

        $cases = [['REGISTER', []], ['HANDOFF', ['REGISTER']], ['START', ['REGISTER', 'HANDOFF']]];
        foreach ($cases as [$failAt, $expectWritten]) {
            $wi = 'WI-GO27-' . $failAt;
            $this->record($wi, 'IMPL', 'implementation', 'COMPLETED', 'IMPL27');

            $r = $this->activateIni(self::INI_ERRORS_TO_STDERR,
                ['activate', '--work-item=' . $wi, '--requested-role=verification',
                    '--human-act=I want a Verification session for ' . $wi . '.'],
                ['CLAUDE_CODE_SESSION_ID' => 'GO27' . $failAt,
                    'KOS_MECHANISM_PATH' => $this->stubMechanism($failAt)]);

            $this->assertSame(65, $r['code'], $r['err']);
            $this->assertIsArray($r['out'], 'the payload must stay machine-readable: ' . $r['raw']);
            $this->assertSame('INCOMPLETE_SEQUENCE', $r['out']['result']);
            $this->assertSame($expectWritten, $r['out']['written']);
            $this->assertSame('governance', $r['out']['whoMustActNext']);
            $this->assertStringNotContainsString('Undefined array key', $r['err'],
                "injected {$failAt} failure: the caller must not read an undefined key — the failure path owes the documented shape (F-3)");
            $this->assertStringNotContainsString('Undefined array key', $r['raw']);
        }

        // the fourth case the verification exercised: all three written, post-write fold disagrees
        $this->record('WI-GO27-FOLD', 'IMPL', 'implementation', 'COMPLETED', 'IMPL27F');
        $r = $this->activateIni(self::INI_ERRORS_TO_STDERR,
            ['activate', '--work-item=WI-GO27-FOLD', '--requested-role=verification',
                '--human-act=I want a Verification session for WI-GO27-FOLD.'],
            ['CLAUDE_CODE_SESSION_ID' => 'GO27FOLD',
                'KOS_MECHANISM_PATH' => $this->stubMechanism(null, 'GO27FOLD')]);

        $this->assertSame(65, $r['code'], $r['err']);
        $this->assertSame('INCOMPLETE_SEQUENCE', $r['out']['result'],
            'three successful appends still yield INCOMPLETE_SEQUENCE when the authoritative fold disagrees');
        $this->assertSame(['REGISTER', 'HANDOFF', 'START'], $r['out']['written']);
        $this->assertStringNotContainsString('Undefined array key', $r['err']);
    }

    /** GO-28 · F-4 — transitionWritten states what was actually persisted, never a hard-coded true. */
    public function test_go28_repair001_transition_written_reflects_what_was_actually_persisted(): void
    {
        $this->requireCapability();

        // nothing written
        $this->record('WI-GO28a', 'IMPL', 'implementation', 'COMPLETED', 'IMPL28A');
        $a = $this->activate(['activate', '--work-item=WI-GO28a', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO28a.'],
            ['CLAUDE_CODE_SESSION_ID' => 'GO28A', 'KOS_MECHANISM_PATH' => $this->stubMechanism('REGISTER')]);
        $this->assertSame(65, $a['code']);
        $this->assertSame([], $a['out']['written']);
        $this->assertFalse($a['out']['transitionWritten'],
            'nothing was persisted — a governance-record field must not claim a mutation that never happened (F-4)');

        // part written
        $this->record('WI-GO28b', 'IMPL', 'implementation', 'COMPLETED', 'IMPL28B');
        $b = $this->activate(['activate', '--work-item=WI-GO28b', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO28b.'],
            ['CLAUDE_CODE_SESSION_ID' => 'GO28B', 'KOS_MECHANISM_PATH' => $this->stubMechanism('HANDOFF')]);
        $this->assertSame(65, $b['code']);
        $this->assertSame(['REGISTER'], $b['out']['written']);
        $this->assertTrue($b['out']['transitionWritten'],
            'part of an append-only sequence was persisted and cannot be undone — that must be reported');

        // fully written
        $this->record('WI-GO28c', 'IMPL', 'implementation', 'COMPLETED', 'IMPL28C');
        $c = $this->activate(['activate', '--work-item=WI-GO28c', '--requested-role=verification',
            '--human-act=I want a Verification session for WI-GO28c.'], ['CLAUDE_CODE_SESSION_ID' => 'GO28C']);
        $this->assertSame(0, $c['code'], $c['err']);
        $this->assertTrue($c['out']['transitionWritten']);
    }

    /** GO-29 · F-2 — the --json contract holds under display_errors=On (success path). */
    public function test_go29_repair001_json_contract_holds_under_display_errors_on_success(): void
    {
        $this->requireCapability();
        $this->record('WI-GO29', 'IMPL', 'implementation', 'COMPLETED', 'IMPL29');

        $r = $this->activateIni(self::INI_ERRORS_TO_STDOUT,
            ['activate', '--work-item=WI-GO29', '--requested-role=verification',
                '--human-act=I want a Verification session for WI-GO29.'],
            ['CLAUDE_CODE_SESSION_ID' => 'GO29']);

        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertIsArray($r['out'],
            'display_errors=On is an ordinary dev/CI default; a diagnostic ahead of the payload makes a SUCCESSFUL '
                . 'activation unparseable to every programmatic consumer, including the capability\'s own run() idiom (F-2): '
                . $r['raw']);
        $this->assertSame('ACTIVATED', $r['out']['result']);
        foreach (['PHP Warning', 'PHP Notice', 'PHP Deprecated', 'Undefined array key'] as $diagnostic) {
            $this->assertStringNotContainsString($diagnostic, $r['raw'], "STDOUT must carry no {$diagnostic}");
            $this->assertStringNotContainsString($diagnostic, $r['err'], "STDERR must carry no {$diagnostic}");
        }
    }

    /** GO-30 · F-2 — the same contract on the partial-write path. */
    public function test_go30_repair001_json_contract_holds_under_display_errors_on_partial_write(): void
    {
        $this->requireCapability();
        $this->record('WI-GO30', 'IMPL', 'implementation', 'COMPLETED', 'IMPL30');

        $r = $this->activateIni(self::INI_ERRORS_TO_STDOUT,
            ['activate', '--work-item=WI-GO30', '--requested-role=verification',
                '--human-act=I want a Verification session for WI-GO30.'],
            ['CLAUDE_CODE_SESSION_ID' => 'GO30', 'KOS_MECHANISM_PATH' => $this->stubMechanism('HANDOFF')]);

        $this->assertSame(65, $r['code']);
        $this->assertIsArray($r['out'],
            'a partial write is exactly when a consumer most needs a parseable answer (F-2): ' . $r['raw']);
        $this->assertSame('INCOMPLETE_SEQUENCE', $r['out']['result']);
        $this->assertSame(['REGISTER'], $r['out']['written']);
        foreach (['PHP Warning', 'PHP Notice', 'PHP Deprecated', 'Undefined array key'] as $diagnostic) {
            $this->assertStringNotContainsString($diagnostic, $r['raw'], "STDOUT must carry no {$diagnostic}");
            $this->assertStringNotContainsString($diagnostic, $r['err'], "STDERR must carry no {$diagnostic}");
        }
    }
}
