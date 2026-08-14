<?php

namespace Tests\Unit\Platform\WorkflowEngine;

use PHPUnit\Framework\TestCase;

/**
 * KOS-AI-ORCH-001 Increment 1 — contract tests R1–R8.
 *
 * Boundary (HUMAN-APPROVED WITH R8, ruling D-2 2026-08-14):
 * docs/publicdigit/reviews/2026-08-14-KOS-AI-ORCH-001-implementation-boundary-proposal.md
 * §4 state model · §5 lifecycle · §6 authority · §7 transitions · §8 concurrency ·
 * §15 RED contract boundary. R8 per the role-bound execution addendum + D-2/A-1.
 *
 * These tests pin the record CONTRACT, not an implementation shape (§15):
 * they exercise the CMP-004 reference helper through its command-line
 * interface — the helper's path/language is Session 3's delegated
 * implementation detail INSIDE the §10 placement constraint (platform-side,
 * .claude/scripts/-style; NOT the product's app/ namespace). No production
 * PHP namespace, class name, or storage layout is asserted. "Rejected"
 * always means the fold/contract refuses (non-zero exit, state unchanged) —
 * never a lock, permission, or hook (Increment 2, NOT authorized).
 *
 * RED TODAY by absence of the mechanism: the reference helper does not
 * exist. Tests are hermetic (temp directory); .claude/runtime/ is never
 * touched (§15).
 */
class WorkflowStateRecordContractTest extends TestCase
{
    private const HELPER = '.claude/scripts/workflow-state.php';

    private string $repoRoot;
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repoRoot = \dirname(__DIR__, 4);
        $this->dir = sys_get_temp_dir() . '/kos-orch-' . bin2hex(random_bytes(6));
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

    // ─── contract gateway ────────────────────────────────────────────────────

    /** RED-by-absence guard: the Increment-1 mechanism does not exist yet. */
    private function requireMechanism(): void
    {
        $this->assertFileExists(
            $this->repoRoot . '/' . self::HELPER,
            'KOS-AI-ORCH-001 Increment-1 record mechanism (CMP-004 reference helper) '
            . 'does not exist yet — RED by absence (boundary §12/§15)'
        );
    }

    /** @return array{code:int, out:mixed, err:string} */
    private function cli(array $args): array
    {
        $cmd = array_merge(
            ['php', $this->repoRoot . '/' . self::HELPER],
            $args,
            ['--dir=' . $this->dir]
        );
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $code = proc_close($proc);

        return ['code' => $code, 'out' => json_decode($stdout, true), 'err' => (string) $stderr];
    }

    private function ok(array $args): mixed
    {
        $r = $this->cli($args);
        $this->assertSame(0, $r['code'], 'expected accepted operation, got refusal: ' . $r['err']);

        return $r['out'];
    }

    private function refused(array $args, string $why): void
    {
        $r = $this->cli($args);
        $this->assertNotSame(0, $r['code'],
            "expected the fold/contract to REFUSE this operation — {$why}");
    }

    private function append(string $workItem, array $transition): mixed
    {
        return $this->ok(['append', $workItem, '--json=' . json_encode($transition)]);
    }

    private function fold(string $workItem): array
    {
        return $this->ok(['fold', $workItem]);
    }

    /** A work item with session A registered, handed to, and human-started (ACTIVE owner). */
    private function itemWithActiveOwnerA(string $workItem): void
    {
        $this->ok(['init', $workItem, '--workflow=platform-implementation',
            '--roles=governance,architecture,implementation,verification']);

        $this->append($workItem, ['type' => 'REGISTER', 'session' => 'A',
            'role' => 'implementation', 'predecessor' => null,
            'executionContext' => 'shared-worktree', 'recordedBy' => 'governance']);
        $this->append($workItem, ['type' => 'HANDOFF', 'from' => null, 'to' => 'A',
            'token' => 'T-0001', 'tokenRef' => 'docs/…commission.md#15',
            'recordedBy' => 'governance']);
        $this->append($workItem, ['type' => 'START', 'session' => 'A',
            'humanAct' => 'PO start act (committed artifact ref)', 'recordedBy' => 'human']);
    }

    // ─── R1 · single mutation owner (Inv C, §8, §15-R1) ─────────────────────

    public function test_r1_second_simultaneous_ownership_claim_is_refused(): void
    {
        $this->requireMechanism();
        $this->itemWithActiveOwnerA('WI-R1');
        $this->append('WI-R1', ['type' => 'REGISTER', 'session' => 'B',
            'role' => 'verification', 'predecessor' => 'A',
            'executionContext' => 'shared-worktree', 'recordedBy' => 'governance']);

        $this->assertSame('A', $this->fold('WI-R1')['mutationOwner']);

        // B claims ownership with no intervening HANDOFF or STOP+CONTINUATION.
        $this->refused(['append', 'WI-R1',
            '--json=' . json_encode(['type' => 'CLAIM_OWNERSHIP', 'session' => 'B',
                'recordedBy' => 'verification'])],
            'ownership moves only by governed transitions, never by assertion (Inv C)');

        $fold = $this->fold('WI-R1');
        $this->assertSame('A', $fold['mutationOwner'],
            'R1: the fold still yields exactly one owner — A');
        $this->assertIsString($fold['mutationOwner'],
            'R1: ownership is single-valued — two simultaneous owners are unrepresentable');
    }

    // ─── R2 · complete session identity (Inv B, §4-A, §15-R2) ───────────────

    public function test_r2_session_identity_is_answerable_from_the_record_alone(): void
    {
        $this->requireMechanism();
        $this->itemWithActiveOwnerA('WI-R2');

        $identity = $this->ok(['identity', 'WI-R2', '--session=A']);

        foreach (['workflow', 'workItem', 'role', 'predecessor', 'state',
            'authorizationLinkage', 'executionContext'] as $dimension) {
            $this->assertArrayHasKey($dimension, $identity,
                "R2: identity dimension '{$dimension}' must come from the record — "
                . 'no CONTEXT.md or prose interpretation');
        }
        $this->assertSame('WI-R2', $identity['workItem']);
        $this->assertSame('implementation', $identity['role']);
        $this->assertSame('ACTIVE', $identity['state']);
    }

    // ─── R3 · ACTIVE = handoff token AND human act, all directions (Inv D+F) ─

    public function test_r3a_handoff_without_token_is_not_a_transition(): void
    {
        $this->requireMechanism();
        $this->itemWithActiveOwnerA('WI-R3A');
        $this->append('WI-R3A', ['type' => 'REGISTER', 'session' => 'B',
            'role' => 'verification', 'predecessor' => 'A',
            'executionContext' => 'shared-worktree', 'recordedBy' => 'governance']);

        $this->refused(['append', 'WI-R3A',
            '--json=' . json_encode(['type' => 'HANDOFF', 'from' => 'A', 'to' => 'B',
                'recordedBy' => 'governance'])],
            'a handoff without its token-reference is not a small handoff; it is not a transition (Inv D)');

        $fold = $this->fold('WI-R3A');
        $this->assertSame('ACTIVE', $fold['sessions']['A']['state'], 'A remains ACTIVE');
        $this->assertSame('A', $fold['mutationOwner'], 'ownership unmoved');
    }

    public function test_r3b_start_without_human_act_cannot_yield_active(): void
    {
        $this->requireMechanism();
        $this->itemWithActiveOwnerA('WI-R3B');
        $this->append('WI-R3B', ['type' => 'REGISTER', 'session' => 'B',
            'role' => 'verification', 'predecessor' => 'A',
            'executionContext' => 'shared-worktree', 'recordedBy' => 'governance']);
        $this->append('WI-R3B', ['type' => 'HANDOFF', 'from' => 'A', 'to' => 'B',
            'token' => 'T-0002', 'tokenRef' => 'docs/…boundary.md#16',
            'recordedBy' => 'governance']);

        $this->refused(['append', 'WI-R3B',
            '--json=' . json_encode(['type' => 'START', 'session' => 'B',
                'recordedBy' => 'implementation'])],
            'START without the recorded human start act must be refused — G-3 conjunction');

        $this->assertNotSame('ACTIVE', $this->fold('WI-R3B')['sessions']['B']['state'],
            'R3b: a handoff alone never yields ACTIVE');
    }

    public function test_r3c_start_without_predecessor_handoff_cannot_yield_active(): void
    {
        $this->requireMechanism();
        $this->itemWithActiveOwnerA('WI-R3C');
        $this->append('WI-R3C', ['type' => 'REGISTER', 'session' => 'B',
            'role' => 'verification', 'predecessor' => 'A',
            'executionContext' => 'shared-worktree', 'recordedBy' => 'governance']);

        $this->refused(['append', 'WI-R3C',
            '--json=' . json_encode(['type' => 'START', 'session' => 'B',
                'humanAct' => 'human act recorded — but no handoff to B exists',
                'recordedBy' => 'human'])],
            'START without the predecessor\'s recorded handoff must be refused — the conjunction binds both ways');

        $this->assertNotSame('ACTIVE', $this->fold('WI-R3C')['sessions']['B']['state'],
            'R3c: a human act alone never yields ACTIVE');
    }

    // ─── R4 · STOPPED is sticky (Inv E, §5, §15-R4) ─────────────────────────

    public function test_r4_stopped_accepts_no_change_without_explicit_continuation(): void
    {
        $this->requireMechanism();
        $this->itemWithActiveOwnerA('WI-R4');
        $this->append('WI-R4', ['type' => 'STOP', 'session' => 'A',
            'reason' => 'governance stop', 'recordedBy' => 'governance']);
        $this->assertSame('STOPPED', $this->fold('WI-R4')['workItemState'],
            'Precondition: the fold yields STOPPED');

        $this->refused(['append', 'WI-R4',
            '--json=' . json_encode(['type' => 'START', 'session' => 'A',
                'humanAct' => 'human act — but no CONTINUATION', 'recordedBy' => 'human'])],
            'no implicit exit from STOPPED exists (Inv E)');

        $this->refused(['append', 'WI-R4',
            '--json=' . json_encode(['type' => 'HANDOFF', 'from' => 'A', 'to' => 'A',
                'token' => 'T-0003', 'tokenRef' => 'docs/…', 'recordedBy' => 'governance'])],
            'ownership cannot move while STOPPED without CONTINUATION');

        $this->assertSame('STOPPED', $this->fold('WI-R4')['workItemState'],
            'R4: stickiness is structural — still STOPPED');
    }

    // ─── R5 · authority writes: Governance + human-act reference (Inv H, §6) ─

    public function test_r5a_non_governance_role_cannot_write_authority(): void
    {
        $this->requireMechanism();
        $this->itemWithActiveOwnerA('WI-R5A');

        $this->refused(['grant', 'WI-R5A', '--writer-role=implementation',
            '--json=' . json_encode(['grantId' => 'G-X', 'status' => 'AUTHORIZED',
                'authority' => 'PO', 'humanActRef' => 'docs/…#15', 'scope' => 'scope-S'])],
            'the Authority State has exactly one writer: the Governance role (G-2)');

        $this->assertSame([], $this->fold('WI-R5A')['grants'], 'no grant row was created');
    }

    public function test_r5b_governance_without_human_act_reference_is_refused(): void
    {
        $this->requireMechanism();
        $this->itemWithActiveOwnerA('WI-R5B');

        $this->refused(['grant', 'WI-R5B', '--writer-role=governance',
            '--json=' . json_encode(['grantId' => 'G-Y', 'status' => 'AUTHORIZED',
                'authority' => 'PO', 'scope' => 'scope-S'])],
            'the record REGISTERS recorded human acts; it never manufactures authority (G-2)');

        $this->assertSame([], $this->fold('WI-R5B')['grants'],
            'authority cannot be created by a write that references no human act');
    }

    // ─── R6 · ACTIVE ≠ AUTHORIZED (Inv G, §6, §15-R6) ───────────────────────

    public function test_r6_active_without_covering_grant_is_not_authorized_and_not_an_error(): void
    {
        $this->requireMechanism();
        $this->itemWithActiveOwnerA('WI-R6');

        $this->assertSame('ACTIVE', $this->fold('WI-R6')['sessions']['A']['state']);

        $answer = $this->ok(['authorized', 'WI-R6', '--session=A', '--scope=scope-S']);
        $this->assertFalse($answer['authorized'],
            'R6: no grant covers scope-S → NO — a state read, not an interpretation, not an error');

        $this->assertSame('ACTIVE', $this->fold('WI-R6')['sessions']['A']['state'],
            'R6: the session REMAINS ACTIVE — lifecycle state and authority state never merge');
    }

    // ─── R7 · work-item isolation (Inv A/J, §8, §15-R7) ─────────────────────

    public function test_r7_transition_on_one_item_leaves_every_other_record_unchanged(): void
    {
        $this->requireMechanism();
        $this->itemWithActiveOwnerA('WI-ISO-A');
        $this->itemWithActiveOwnerA('WI-ISO-B');

        $recordOfBBefore = $this->fold('WI-ISO-B');

        $this->append('WI-ISO-A', ['type' => 'STOP', 'session' => 'A',
            'reason' => 'isolation probe', 'recordedBy' => 'governance']);

        // §15-R7: record-CONTENT equality — not byte-equality of a prescribed file.
        $this->assertSame($recordOfBBefore, $this->fold('WI-ISO-B'),
            'R7: zero cross-work-item coupling — state belongs to the work item');
        $this->assertSame('STOPPED', $this->fold('WI-ISO-A')['workItemState'],
            'the transition really applied to A');
    }

    // ─── R8 · role immutability per SessionAssignment (D-2/A-1, addendum §11) ─

    public function test_r8_no_transition_mutates_an_assignments_role(): void
    {
        $this->requireMechanism();
        $this->itemWithActiveOwnerA('WI-R8');

        // (a) re-registering the same assignment under another role is refused.
        $this->refused(['append', 'WI-R8',
            '--json=' . json_encode(['type' => 'REGISTER', 'session' => 'A',
                'role' => 'verification', 'predecessor' => null,
                'executionContext' => 'shared-worktree', 'recordedBy' => 'governance'])],
            'R8: role is immutable for a SessionAssignment — a role change is a NEW assignment');

        // (b) no role-mutating transition type exists in the machine at all.
        $this->refused(['append', 'WI-R8',
            '--json=' . json_encode(['type' => 'ROLE_CHANGE', 'session' => 'A',
                'role' => 'verification', 'recordedBy' => 'governance'])],
            'R8: there is no transition that mutates role — immutability is structural');

        $this->assertSame('implementation', $this->fold('WI-R8')['sessions']['A']['role'],
            'R8: the fold still shows the role the assignment was registered with');
    }
}
