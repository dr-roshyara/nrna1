<?php

namespace Tests\Unit\Platform\WorkflowEngine;

use PHPUnit\Framework\TestCase;

/**
 * KOS-AI-ORCH-001 Increment 1 — the §16 GREEN replay criterion.
 *
 * The commission's §11 acceptance: the record must be able to REPLAY a real,
 * manually-conducted governed work item from committed artifacts alone. The
 * PBDIGIT-65/69 repair track is that fixture: grant (f6bb5504) → boundary
 * (3499ea38) → RED (32215fea) → GREEN (5d46498e) → TE6 pin (ac313368) →
 * handoff to independent verification. Every authority row references a
 * committed artifact; every handoff carries its token; the fold answers the
 * questions that were answered by prose-reading during the real track.
 *
 * Hermetic: temp directory; .claude/runtime/ untouched.
 */
class Pbdigit6569ReplayTest extends TestCase
{
    private const HELPER = '.claude/scripts/workflow-state.php';

    private string $repoRoot;
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repoRoot = \dirname(__DIR__, 4);
        $this->dir = sys_get_temp_dir() . '/kos-replay-' . bin2hex(random_bytes(6));
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

    private function cli(array $args): array
    {
        $cmd = array_merge(['php', $this->repoRoot . '/' . self::HELPER], $args, ['--dir=' . $this->dir]);
        $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $code = proc_close($proc);
        $this->assertSame(0, $code, 'replay step refused: ' . $stderr);

        return json_decode($stdout, true);
    }

    public function test_the_6569_track_replays_from_committed_artifacts(): void
    {
        $this->assertFileExists($this->repoRoot . '/' . self::HELPER,
            'Increment-1 mechanism required for the replay criterion');

        $wi = 'PBDIGIT-6569';
        $this->cli(['init', $wi, '--workflow=election-only-repair',
            '--roles=governance,architecture,implementation,verification']);

        // 1 · PO authorizes Option A v5 — Governance registers the recorded act.
        $this->cli(['grant', $wi, '--writer-role=governance', '--json=' . json_encode([
            'grantId' => 'G-6569-OPTION-A', 'status' => 'AUTHORIZED', 'authority' => 'PO',
            'humanActRef' => 'docs/publicdigit/reviews/2026-08-13-6569-repair-disposition-package.md §2e · commit f6bb5504',
            'scope' => 'Option A v5 — repair the two confirmed voting-time entitlement violations',
        ])]);

        // 2 · Session 3 (implementation) enters via handoff + human start (G-3).
        $this->cli(['append', $wi, '--json=' . json_encode([
            'type' => 'REGISTER', 'session' => 'S3-impl', 'role' => 'implementation',
            'predecessor' => 'S2-governance', 'executionContext' => 'shared-worktree',
            'recordedBy' => 'governance'])]);
        $this->cli(['append', $wi, '--json=' . json_encode([
            'type' => 'HANDOFF', 'from' => null, 'to' => 'S3-impl',
            'token' => 'T-6569-GRANT',
            'tokenRef' => 'grant §5a (f6bb5504) + boundary v2 (3499ea38)',
            'recordedBy' => 'governance'])]);
        $this->cli(['append', $wi, '--json=' . json_encode([
            'type' => 'START', 'session' => 'S3-impl',
            'humanAct' => 'PO: boundary approved, proceed to RED (2026-08-14)',
            'recordedBy' => 'human'])]);

        // The questions answered by prose during the real track are now queries:
        $fold = $this->cli(['fold', $wi]);
        $this->assertSame('S3-impl', $fold['mutationOwner']);
        $this->assertTrue($this->cli(['authorized', $wi, '--session=S3-impl',
            '--scope=Option A v5 — repair the two confirmed voting-time entitlement violations'])['authorized']);
        $this->assertFalse($this->cli(['authorized', $wi, '--session=S3-impl',
            '--scope=EM-OPEN-021 fallback semantics'])['authorized'],
            'the record answers NO for the scope the track was fenced from — ACTIVE ≠ AUTHORIZED-for-everything');

        // 3 · Implementation done (RED 32215fea → GREEN 5d46498e → pin ac313368);
        //     handoff to independent verification (R-34).
        $this->cli(['append', $wi, '--json=' . json_encode([
            'type' => 'REGISTER', 'session' => 'S1-verify', 'role' => 'verification',
            'predecessor' => 'S3-impl', 'executionContext' => 'shared-worktree',
            'recordedBy' => 'governance'])]);
        $this->cli(['append', $wi, '--json=' . json_encode([
            'type' => 'HANDOFF', 'from' => 'S3-impl', 'to' => 'S1-verify',
            'token' => 'T-6569-EVIDENCE',
            'tokenRef' => 'commits 32215fea (RED) · 5d46498e (GREEN) · ac313368 (TE6)',
            'recordedBy' => 'governance'])]);
        $this->cli(['append', $wi, '--json=' . json_encode([
            'type' => 'START', 'session' => 'S1-verify',
            'humanAct' => 'PO verification commission to Session 1 (2026-08-14)',
            'recordedBy' => 'human'])]);

        $fold = $this->cli(['fold', $wi]);
        $this->assertSame('S1-verify', $fold['mutationOwner'],
            'ownership followed the recorded handoff chain');
        $this->assertSame('HANDED_OFF', $fold['sessions']['S3-impl']['state'],
            'the implementer no longer owns mutation while verification runs (R-34 visible in the record)');

        // Replay integrity: every authority row references a committed artifact.
        foreach ($fold['grants'] as $grant) {
            $this->assertNotEmpty($grant['humanActRef'],
                'replay criterion: authority is always a registered human act, reconstructable from git history');
        }

        // Identity of the verifier is answerable from the record alone (R2 in replay form).
        $identity = $this->cli(['identity', $wi, '--session=S1-verify']);
        $this->assertSame('verification', $identity['role']);
        $this->assertSame('S3-impl', $identity['predecessor']);
    }
}
