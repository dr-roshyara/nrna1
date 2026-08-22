<?php

namespace Tests\Unit\Platform\WorkflowEngine;

use PHPUnit\Framework\TestCase;

/**
 * KOS-NEXT-ACTOR-ORCHESTRATION-001 — Next-Actor Orchestration / Business-Language
 * Handoff contract, N-1…N-15 + R-1 (real-world regression) + P-1…P-6
 * (AMENDMENT-001 · Use Case 3 PrepareNextActorSession).
 *
 * Work item: KOS-NEXT-ACTOR-ORCHESTRATION-001 · Asset: AST-018
 * (next-actor-orchestration.php) · Component: CMP-004 (workflow_engine).
 *
 * Core contract — the distinctions that must never collapse:
 *   Responsibility ≠ Authority · Eligibility ≠ Authorization
 *   Recommendation ≠ Appointment · Appointment ≠ Activation
 *   Workflow state ≠ Business decision · Evidence ≠ Authority · Identity ≠ Authority
 *
 * The orchestrator translates a HUMAN decision into governed mechanics. It never
 * invents authority, never becomes the authority model, never duplicates AST-015,
 * never becomes a second workflow engine.
 *
 * Fail-closed: a missing human act, an absent candidate, an ambiguous candidate
 * set, an ineligible candidate, or a failed precondition yields NO TRANSITION and
 * a report naming what is missing · why it matters · who must act next · what the
 * human can choose.
 *
 * Binding: ALL workflow interpretation comes from AST-015 (workflow-state.php) via
 * subprocess — no second fold (N-13), no raw-record state parsing (N-14). Every
 * write goes through the canonical mechanism.
 *
 * Hermetic: synthetic records in temp dirs, built THROUGH the qualified mechanism;
 * `.claude/runtime/workflow/` is never read or written.
 *
 * RED TODAY by absence: `.claude/scripts/next-actor-orchestration.php` does not exist.
 */
class NextActorOrchestrationContractTest extends TestCase
{
    private const ORCHESTRATOR = '.claude/scripts/next-actor-orchestration.php';
    private const MECHANISM = '.claude/scripts/workflow-state.php';

    private string $repoRoot;
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repoRoot = \dirname(__DIR__, 4);
        $this->dir = sys_get_temp_dir() . '/kos-nao-' . bin2hex(random_bytes(6));
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

    private function requireOrchestrator(): void
    {
        $this->assertFileExists($this->repoRoot . '/' . self::ORCHESTRATOR,
            'Next-Actor Orchestrator does not exist yet — RED by absence');
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

    private function mechanism(array $args, bool $expectOk = true): int
    {
        $cmd = array_merge(['php', $this->repoRoot . '/' . self::MECHANISM], $args, ['--dir=' . $this->dir]);
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        stream_get_contents($pipes[1]);
        $err = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $code = proc_close($proc);
        if ($expectOk) {
            $this->assertSame(0, $code, 'fixture setup via the qualified mechanism failed: ' . $err);
        }

        return $code;
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
        // `predecessor` records lineage; HANDOFF.from must be the CURRENT owner
        // (Inv C). In these fixtures the prior lane has already completed, so no
        // owner exists and the bootstrap form (from=null) is the correct one.
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
        return file_get_contents($this->repoRoot . '/' . self::ORCHESTRATOR);
    }

    // ═══ N-1 … N-7 · the human-authority boundary and fail-closed behaviour ═══

    public function test_n1_no_human_decision_yields_no_appointment_and_no_transition(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-1');
        $this->lane('WI-1', 'S-arch', 'architecture', 'COMPLETED');
        $before = $this->fingerprint();

        $r = $this->orchestrate('appoint', 'WI-1', ['--candidate=S-new', '--role=verification']);

        $this->assertSame(65, $r['code'], 'a missing human act must REFUSE the appointment');
        $this->assertSame('NO_TRANSITION', $r['out']['result']);
        $this->assertSame($before, $this->fingerprint(), 'the record must be byte-unchanged');
        $this->assertStringContainsStringIgnoringCase('human', $r['out']['whatIsMissing']);
        $this->assertNotEmpty($r['out']['whyItMatters']);
        $this->assertNotEmpty($r['out']['whoMustActNext']);
        $this->assertNotEmpty($r['out']['humanOptions']);
    }

    public function test_n2_human_yes_executes_the_governed_appointment_path(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-2');
        $this->lane('WI-2', 'S-arch', 'architecture', 'COMPLETED');

        $r = $this->orchestrate('appoint', 'WI-2', ['--candidate=S-new', '--role=verification',
            '--human-act=PO/ARB: I appoint S-new as the independent verifier.',
            '--scope=independent verification of WI-2']);

        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertSame('APPOINTED', $r['out']['result']);
        $this->assertSame('ACTIVE', $r['out']['state']);

        $fold = $this->fold('WI-2');
        $this->assertSame('ACTIVE', $fold['sessions']['S-new']['state']);
        $this->assertSame('verification', $fold['sessions']['S-new']['role']);
        $this->assertSame('S-new', $fold['mutationOwner']);
    }

    public function test_n3_no_candidate_yields_no_eligible_candidate_and_no_transition(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-3');
        $this->lane('WI-3', 'S-arch', 'architecture', 'COMPLETED');
        $before = $this->fingerprint();

        $r = $this->orchestrate('appoint', 'WI-3', ['--role=verification', '--human-act=PO/ARB: appoint one.']);

        $this->assertSame(65, $r['code']);
        $this->assertSame('NO_ELIGIBLE_CANDIDATE', $r['out']['result']);
        $this->assertSame($before, $this->fingerprint());
    }

    public function test_n4_multiple_candidates_yield_ambiguous_and_never_silently_choose(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-4');
        $this->lane('WI-4', 'S-arch', 'architecture', 'COMPLETED');
        $before = $this->fingerprint();

        $r = $this->orchestrate('appoint', 'WI-4', ['--candidate=S-a', '--candidate=S-b',
            '--role=verification', '--human-act=PO/ARB: appoint one.']);

        $this->assertSame(65, $r['code']);
        $this->assertSame('AMBIGUOUS', $r['out']['result']);
        $this->assertSame($before, $this->fingerprint(), 'ambiguity must never resolve itself by choosing');
    }

    public function test_n5_ineligible_candidate_yields_no_transition(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-5');
        $this->lane('WI-5', 'S-arch', 'architecture', 'COMPLETED');
        $before = $this->fingerprint();

        // The candidate is the very session whose work is under review — excluded.
        $r = $this->orchestrate('appoint', 'WI-5', ['--candidate=S-arch', '--role=verification',
            '--human-act=PO/ARB: appoint S-arch.']);

        $this->assertSame(65, $r['code']);
        $this->assertSame('NOT_ELIGIBLE', $r['out']['result']);
        $this->assertStringContainsStringIgnoringCase('independen', $r['out']['whatIsMissing']);
        $this->assertSame($before, $this->fingerprint());
    }

    public function test_n6_human_stop_changes_nothing(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-6');
        $this->lane('WI-6', 'S-arch', 'architecture', 'COMPLETED');
        $before = $this->fingerprint();

        $r = $this->orchestrate('stop', 'WI-6', []);

        $this->assertSame(0, $r['code']);
        $this->assertSame('STOPPED_BY_HUMAN_CHOICE', $r['out']['result']);
        $this->assertFalse($r['out']['transitionWritten']);
        $this->assertSame($before, $this->fingerprint());
    }

    public function test_n7_write_reviewer_prompt_branch_is_advisory_and_writes_nothing(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-7');
        $this->lane('WI-7', 'S-arch', 'architecture', 'COMPLETED');
        $before = $this->fingerprint();

        $r = $this->orchestrate('prepare-prompt', 'WI-7', ['--candidate=S-new', '--role=verification']);

        $this->assertSame(0, $r['code']);
        $this->assertSame('REVIEWER_PROMPT_PREPARED', $r['out']['result']);
        $this->assertFalse($r['out']['transitionWritten']);
        $this->assertNotEmpty($r['out']['prompt']);
        $this->assertSame($before, $this->fingerprint(), 'the advisory branch must never write');
    }

    // ═══ N-8 … N-12 · governed mechanics correctness ═════════════════════════

    public function test_n8_register_records_the_current_mutation_owner_as_predecessor(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-8');
        $this->lane('WI-8', 'S-own', 'architecture', 'ACTIVE');   // still the owner

        $r = $this->orchestrate('appoint', 'WI-8', ['--candidate=S-new', '--role=verification',
            '--human-act=PO/ARB: appoint S-new.']);

        $this->assertSame(0, $r['code'], $r['err']);
        $fold = $this->fold('WI-8');
        $this->assertSame('S-own', $fold['sessions']['S-new']['predecessor'],
            'the predecessor must be the recorded mutation owner, not invented');
    }

    public function test_n9_handoff_comes_from_the_current_owner(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-9');
        $this->lane('WI-9', 'S-own', 'architecture', 'ACTIVE');

        $r = $this->orchestrate('appoint', 'WI-9', ['--candidate=S-new', '--role=verification',
            '--human-act=PO/ARB: appoint S-new.']);
        $this->assertSame(0, $r['code'], $r['err']);

        $raw = json_decode(file_get_contents($this->dir . '/WI-9.json'), true);
        $handoffs = array_values(array_filter($raw['transitions'],
            fn ($t) => $t['type'] === 'HANDOFF' && $t['to'] === 'S-new'));
        $this->assertCount(1, $handoffs);
        $this->assertSame('S-own', $handoffs[0]['from'], 'Inv C — only the current owner hands off');
        $this->assertNotSame('', $handoffs[0]['token'] ?? '');
        $this->assertNotSame('', $handoffs[0]['tokenRef'] ?? '');

        $fold = $this->fold('WI-9');
        $this->assertSame('HANDED_OFF', $fold['sessions']['S-own']['state']);
    }

    public function test_n10_start_records_the_human_act_verbatim(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-10');
        $this->lane('WI-10', 'S-arch', 'architecture', 'COMPLETED');
        $act = 'PO/ARB 2026-08-22, verbatim: I appoint S-new as the independent verifier.';

        $this->orchestrate('appoint', 'WI-10', ['--candidate=S-new', '--role=verification',
            '--human-act=' . $act]);

        $raw = json_decode(file_get_contents($this->dir . '/WI-10.json'), true);
        $starts = array_values(array_filter($raw['transitions'],
            fn ($t) => $t['type'] === 'START' && $t['session'] === 'S-new'));
        $this->assertCount(1, $starts);
        $this->assertStringContainsString($act, $starts[0]['humanAct'],
            'the human act must be recorded verbatim, never paraphrased or manufactured');
        $this->assertSame('human', $starts[0]['recordedBy']);
    }

    public function test_n11_precondition_failure_fails_closed_with_no_partial_write(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-11');
        $this->lane('WI-11', 'S-arch', 'architecture', 'COMPLETED');
        $before = $this->fingerprint();

        // 'reviewer' is not in the workflow's declared role set.
        $r = $this->orchestrate('appoint', 'WI-11', ['--candidate=S-new', '--role=reviewer',
            '--human-act=PO/ARB: appoint S-new.']);

        $this->assertSame(65, $r['code']);
        $this->assertSame($before, $this->fingerprint(),
            'pre-flight must reject before the first append — no partial transition sequence');
    }

    public function test_n12_the_resulting_lane_resolves_active_through_ast015(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-12');
        $this->lane('WI-12', 'S-arch', 'architecture', 'COMPLETED');

        $r = $this->orchestrate('appoint', 'WI-12', ['--candidate=S-new', '--role=verification',
            '--human-act=PO/ARB: appoint S-new.']);

        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertTrue($r['out']['activationVerified'],
            'the orchestrator must verify the resulting ACTIVE state, not assume it');
        $this->assertSame('ACTIVE', $this->fold('WI-12')['sessions']['S-new']['state']);
    }

    /**
     * N-16 · THE POINT OF THE WHOLE CAPABILITY: an appointment must leave the
     * appointed actor genuinely operable. The lane's executionContext must carry
     * the canonical `claude-code-session:<id>` label so AST-017 attributes the
     * lane to that process. Regression: AST-017's extractor accepts `.` inside a
     * label, so a label followed by a period is captured WITH the period and the
     * lane silently stops being attributable — an appointed actor that resolves
     * UNRESOLVED, which is the exact dead end this work item exists to remove.
     */
    public function test_n16_the_appointed_actor_is_attributable_and_operable_via_ast017(): void
    {
        $this->requireOrchestrator();
        $uuid = '7f3ac1e2-45bd-4a91-8c22-0d1e9b7a6c31';
        $this->init('WI-16');
        $this->lane('WI-16', 'S-arch', 'architecture', 'COMPLETED');

        $r = $this->orchestrate('appoint', 'WI-16', ['--candidate=' . $uuid, '--role=verification',
            '--human-act=PO/ARB: I appoint the independent verifier.']);
        $this->assertSame(0, $r['code'], $r['err']);

        $ctx = $this->fold('WI-16')['sessions'][$uuid]['executionContext'];
        $this->assertStringContainsString('claude-code-session:' . $uuid . ' ', $ctx,
            'the canonical label must be present and NOT followed by punctuation');

        // AST-017, running as that process, must find the lane and authorize it.
        $boot = ['php', $this->repoRoot . '/.claude/scripts/session-bootstrap.php',
            '--dir=' . $this->dir, '--work-item=WI-16', '--json'];
        $proc = proc_open($boot, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null,
            array_merge(getenv(), ['CLAUDE_CODE_SESSION_ID' => $uuid]));
        $raw = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);
        $report = json_decode($raw, true);

        $this->assertSame('RESOLVED', $report['verdict'] ?? null,
            'the appointed actor must resolve to its own lane');
        $this->assertTrue($report['gates']['authorized_to_act'] ?? false,
            'an appointment that leaves the actor unauthorized has not appointed anything');
    }

    // ═══ N-13 / N-14 · AST-015 remains the single interpreter ════════════════

    public function test_n13_state_comes_from_the_fold_never_from_decoy_raw_fields(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-13');
        $this->lane('WI-13', 'S-arch', 'architecture', 'COMPLETED');

        // Poison the raw record with decoys a second fold would believe.
        $path = $this->dir . '/WI-13.json';
        $raw = json_decode(file_get_contents($path), true);
        $raw['mutationOwner'] = 'DECOY-OWNER';
        $raw['workItemState'] = 'DECOY-STATE';
        $raw['sessions'] = ['DECOY-LANE' => ['role' => 'governance', 'state' => 'ACTIVE']];
        file_put_contents($path, json_encode($raw));

        $r = $this->orchestrate('next-actor', 'WI-13');

        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertStringNotContainsString('DECOY', $r['raw'],
            'no decoy may surface — all state must be fold-derived through AST-015');
    }

    public function test_n14_no_raw_workflow_state_parsing_outside_the_bounded_interface(): void
    {
        $this->requireOrchestrator();
        $src = $this->source();

        $this->assertStringNotContainsString('runtime/workflow', $src,
            'the orchestrator must not hard-code or read the authoritative store path');
        foreach (['file_get_contents', 'fopen', 'fread', 'file(', 'SplFileObject'] as $readCall) {
            $this->assertStringNotContainsString($readCall, $src,
                "raw record access ({$readCall}) is forbidden — AST-015 is the only interpreter");
        }
        foreach (['file_put_contents', 'fwrite($', 'rename('] as $writeCall) {
            $this->assertStringNotContainsString($writeCall, $src,
                "direct store mutation ({$writeCall}) is forbidden — every write goes through AST-015");
        }
        $this->assertStringNotContainsString('function foldSessions', $src, 'no second fold');
    }

    // ═══ N-15 · the business-language contract ═══════════════════════════════

    public function test_n15_human_output_hides_the_mechanics_unless_asked(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-15');
        $this->lane('WI-15', 'S-arch', 'architecture', 'COMPLETED');
        $this->lane('WI-15', 'S-ver', 'verification', 'COMPLETED', 'S-arch');

        $human = $this->orchestrate('next-actor', 'WI-15', [], false)['raw'];

        foreach (['REGISTER', 'HANDOFF', 'mutationOwner', 'transition JSON', 'workflow-state.php'] as $mechanic) {
            $this->assertStringNotContainsString($mechanic, $human,
                "business-language output must not expose '{$mechanic}'");
        }
        $this->assertStringContainsStringIgnoringCase('governance', $human);

        $technical = $this->orchestrate('next-actor', 'WI-15', ['--show-mechanics'], false)['raw'];
        $this->assertStringContainsString('REGISTER', $technical,
            'mechanics appear only when explicitly requested');
    }

    // ═══ R-1 · the real-world regression scenario ════════════════════════════

    public function test_r1_architecture_then_verification_complete_requires_a_fresh_governance_reviewer(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-R');
        $this->lane('WI-R', 'S-arch', 'architecture', 'COMPLETED');
        $this->lane('WI-R', 'S-ver', 'verification', 'COMPLETED', 'S-arch');

        $r = $this->orchestrate('next-actor', 'WI-R');

        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertSame('NEXT_ACTOR_REQUIRED', $r['out']['result']);
        $this->assertSame('governance', $r['out']['role']);
        $this->assertTrue($r['out']['freshIndependentActorRequired']);
        $this->assertTrue($r['out']['requiredHumanDecision']);
        $this->assertSame(['APPOINT', 'DRAFT_PROMPT', 'STOP'], $r['out']['options']);
        $this->assertNotEmpty($r['out']['reason']);
        $this->assertNotEmpty($r['out']['businessExplanation']);
    }

    public function test_r2_a_stopped_work_item_is_reported_and_never_auto_continued(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-S');
        $this->lane('WI-S', 'S-a', 'implementation', 'STOPPED');
        $before = $this->fingerprint();

        $r = $this->orchestrate('next-actor', 'WI-S');

        $this->assertSame(0, $r['code']);
        $this->assertSame('WORK_ITEM_STOPPED', $r['out']['result']);
        $this->assertTrue($r['out']['requiredHumanDecision']);
        $this->assertSame($before, $this->fingerprint());
    }

    public function test_r3_an_active_lane_is_not_a_next_actor_recommendation(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-A');
        $this->lane('WI-A', 'S-a', 'implementation', 'ACTIVE');

        $r = $this->orchestrate('next-actor', 'WI-A');

        $this->assertSame('LANE_ACTIVE', $r['out']['result']);
        $this->assertFalse($r['out']['requiredHumanDecision'],
            'an active lane simply continues — no human decision is manufactured');
    }

    // ═══ P-1 … P-6 · AMENDMENT-001 · Use Case 3 PrepareNextActorSession ══════

    public function test_p1_prepare_next_session_writes_nothing(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-P1');
        $this->lane('WI-P1', 'S-arch', 'architecture', 'COMPLETED');
        $this->lane('WI-P1', 'S-ver', 'verification', 'COMPLETED', 'S-arch');
        $before = $this->fingerprint();

        $r = $this->orchestrate('prepare-next-session', 'WI-P1');

        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertSame('KICKOFF_PROMPT_PREPARED', $r['out']['result']);
        $this->assertFalse($r['out']['transitionWritten']);
        $this->assertSame($before, $this->fingerprint());
    }

    public function test_p2_the_kickoff_prompt_carries_every_governed_fact(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-P2');
        $this->lane('WI-P2', 'S-arch', 'architecture', 'COMPLETED');
        $this->lane('WI-P2', 'S-ver', 'verification', 'COMPLETED', 'S-arch');

        $out = $this->orchestrate('prepare-next-session', 'WI-P2')['out'];
        $prompt = $out['prompt'];

        $this->assertStringContainsString('WI-P2', $prompt, 'work item');
        $this->assertStringContainsStringIgnoringCase('governance', $prompt, 'role');
        $this->assertNotEmpty($out['facts']['scope'], 'scope');
        $this->assertNotEmpty($out['facts']['whyNeeded'], 'why the actor is needed');
        $this->assertNotEmpty($out['facts']['independenceExclusions'], 'independence exclusions');
        $this->assertNotEmpty($out['facts']['currentState'], 'current state');
        $this->assertNotEmpty($out['facts']['doFirst'], 'what to do first');
        $this->assertNotEmpty($out['facts']['doNot'], 'what it must NOT do');
        $this->assertNotEmpty($out['facts']['stopCondition'], 'where it must stop');
        $this->assertNotEmpty($out['facts']['nextGovernedStep'], 'next governed step');
    }

    public function test_p3_the_prompt_is_generated_from_governed_facts_not_a_static_template(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-P3a');
        $this->lane('WI-P3a', 'S-arch', 'architecture', 'COMPLETED');
        $this->lane('WI-P3a', 'S-ver', 'verification', 'COMPLETED', 'S-arch');
        $a = $this->orchestrate('prepare-next-session', 'WI-P3a')['out']['prompt'];

        $this->init('WI-P3b');
        $this->lane('WI-P3b', 'S-impl', 'implementation', 'COMPLETED');
        $b = $this->orchestrate('prepare-next-session', 'WI-P3b')['out']['prompt'];

        $this->assertNotSame($a, $b, 'a different governed state must yield a different prompt');
        $this->assertStringContainsString('WI-P3a', $a);
        $this->assertStringContainsString('WI-P3b', $b);
        $this->assertStringNotContainsString('WI-P3b', $a);
    }

    public function test_p4_exclusions_are_derived_from_the_records_registered_sessions(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-P4');
        $this->lane('WI-P4', 'S-arch', 'architecture', 'COMPLETED');
        $this->lane('WI-P4', 'S-ver', 'verification', 'COMPLETED', 'S-arch');

        $out = $this->orchestrate('prepare-next-session', 'WI-P4')['out'];

        $this->assertContains('S-arch', $out['facts']['independenceExclusions']);
        $this->assertContains('S-ver', $out['facts']['independenceExclusions']);
        $this->assertStringContainsString('S-arch', $out['prompt']);
        $this->assertStringContainsString('S-ver', $out['prompt']);
    }

    public function test_p5_the_prompt_forbids_self_registration_and_stops_after_declaration(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-P5');
        $this->lane('WI-P5', 'S-arch', 'architecture', 'COMPLETED');
        $this->lane('WI-P5', 'S-ver', 'verification', 'COMPLETED', 'S-arch');

        $prompt = $this->orchestrate('prepare-next-session', 'WI-P5')['out']['prompt'];

        foreach (['Do not REGISTER', 'Do not HANDOFF', 'Do not START'] as $prohibition) {
            $this->assertStringContainsString($prohibition, $prompt);
        }
        $this->assertStringContainsStringIgnoringCase('STOP after the declaration', $prompt);
        $this->assertStringContainsStringIgnoringCase('PROCESS IDENTITY', $prompt);
        $this->assertStringContainsStringIgnoringCase('ELIGIBILITY', $prompt);
    }

    public function test_p6_no_process_identity_is_ever_manufactured(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-P6');
        $this->lane('WI-P6', 'S-arch', 'architecture', 'COMPLETED');
        $this->lane('WI-P6', 'S-ver', 'verification', 'COMPLETED', 'S-arch');

        $out = $this->orchestrate('prepare-next-session', 'WI-P6')['out'];

        $this->assertNull($out['facts']['candidateIdentity'],
            'the next actor has no identity until it declares one — it must never be invented');
        $this->assertDoesNotMatchRegularExpression(
            '/\b[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\b/',
            $out['prompt'],
            'no manufactured session UUID may appear in the kickoff prompt');
    }

    public function test_p8_no_kickoff_prompt_is_offered_while_a_lane_still_holds_the_work(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-P8');
        $this->lane('WI-P8', 'S-a', 'implementation', 'ACTIVE');
        $before = $this->fingerprint();

        $r = $this->orchestrate('prepare-next-session', 'WI-P8');

        $this->assertSame(0, $r['code'], $r['err']);
        $this->assertSame('NO_FRESH_ACTOR_REQUIRED', $r['out']['result'],
            'a kickoff prompt for a role that is already actively held is a misleading output');
        $this->assertNull($r['out']['prompt']);
        $this->assertFalse($r['out']['freshProcessRequired']);
        $this->assertNotEmpty($r['out']['whyItMatters']);
        $this->assertNotEmpty($r['out']['whoMustActNext']);
        $this->assertSame($before, $this->fingerprint());
    }

    public function test_p9_no_kickoff_prompt_for_a_stopped_work_item(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-P9');
        $this->lane('WI-P9', 'S-a', 'implementation', 'STOPPED');

        $r = $this->orchestrate('prepare-next-session', 'WI-P9');

        $this->assertSame('NO_FRESH_ACTOR_REQUIRED', $r['out']['result']);
        $this->assertNull($r['out']['prompt']);
    }

    public function test_p7_the_human_choice_is_presented_as_three_business_options(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-P7');
        $this->lane('WI-P7', 'S-arch', 'architecture', 'COMPLETED');
        $this->lane('WI-P7', 'S-ver', 'verification', 'COMPLETED', 'S-arch');

        $human = $this->orchestrate('prepare-next-session', 'WI-P7', [], false)['raw'];

        $this->assertStringContainsString('1.', $human);
        $this->assertStringContainsString('2.', $human);
        $this->assertStringContainsString('3.', $human);
        $this->assertStringContainsStringIgnoringCase('paste', $human,
            'the human must be told exactly what to do with the prompt');
    }

    // ═══ usage / exit-code contract ══════════════════════════════════════════

    public function test_usage_errors_exit_64_and_unknown_commands_are_refused(): void
    {
        $this->requireOrchestrator();
        $this->init('WI-U');

        $this->assertSame(64, $this->orchestrate('not-a-command', 'WI-U')['code']);
        $this->assertSame(64, $this->orchestrate('next-actor', 'WI-does-not-exist')['code']);
    }
}
