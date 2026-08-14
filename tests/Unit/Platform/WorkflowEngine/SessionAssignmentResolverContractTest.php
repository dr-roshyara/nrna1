<?php

namespace Tests\Unit\Platform\WorkflowEngine;

use PHPUnit\Framework\TestCase;

/**
 * KOS-SESSION-DISCOVERY-001 — Session Assignment Resolver contract, T-1…T-13.
 *
 * Grant: G-KOS-DISC-IMPL (AUTHORIZED, amended). Boundary:
 * docs/publicdigit/reviews/2026-08-14-KOS-SESSION-DISCOVERY-001-implementation-boundary-proposal.md
 * Architecture: …-fresh-architecture-proposal.md §§E–S.
 *
 * The capability is DISCOVERY ONLY:
 *   Discovery ≠ Authorization ≠ Activation ≠ START ≠ Ownership.
 *
 * Binding precedence (AMENDMENT 2): the qualified mechanism AST-015
 * (workflow-state.php) is the authoritative interpretation of workflow
 * records. The resolver obtains ALL interpretation from it and implements no
 * second fold — T-13 proves both halves (agreement, and inability to answer
 * independently when the qualified mechanism is unavailable).
 *
 * Exit contract (AMENDMENT 1): a produced ResolutionReport is a SUCCESS.
 * UNASSIGNED and AMBIGUOUS are valid answers, never command failures; non-zero
 * is reserved for usage/refusal — T-12 pins this.
 *
 * Hermetic: synthetic records in temp dirs; `.claude/runtime/workflow/` is
 * never read or written by this suite.
 *
 * RED TODAY by absence: `.claude/scripts/session-resolve.php` does not exist.
 */
class SessionAssignmentResolverContractTest extends TestCase
{
    private const RESOLVER = '.claude/scripts/session-resolve.php';
    private const MECHANISM = '.claude/scripts/workflow-state.php';

    private string $repoRoot;
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repoRoot = \dirname(__DIR__, 4);
        $this->dir = sys_get_temp_dir() . '/kos-disc-' . bin2hex(random_bytes(6));
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

    private function requireResolver(): void
    {
        $this->assertFileExists($this->repoRoot . '/' . self::RESOLVER,
            'Session Assignment Resolver does not exist yet — RED by absence (boundary §7 step 2)');
    }

    /** @return array{code:int,out:mixed,raw:string,err:string} */
    private function resolve(array $args = [], array $env = []): array
    {
        $cmd = array_merge(['php', $this->repoRoot . '/' . self::RESOLVER, '--dir=' . $this->dir, '--json'], $args);
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

    /** Build a record through the QUALIFIED mechanism — never by hand-writing JSON. */
    private function record(string $wi, string $session, string $role, string $finalState = 'ACTIVE'): void
    {
        $this->mechanism(['init', $wi, '--workflow=platform-implementation',
            '--roles=governance,architecture,implementation,verification']);
        $this->mechanism(['append', $wi, '--json=' . json_encode(['type' => 'REGISTER', 'session' => $session,
            'role' => $role, 'predecessor' => null, 'executionContext' => 'shared-worktree',
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

    private function directoryFingerprint(): array
    {
        $out = [];
        foreach (glob($this->dir . '/*') ?: [] as $f) {
            $out[basename($f)] = hash_file('sha256', $f);
        }
        ksort($out);

        return $out;
    }

    private function onlyCandidate(array $report): array
    {
        $this->assertArrayHasKey('candidates', $report, 'report must carry a candidate roster');
        $this->assertCount(1, $report['candidates']);

        return $report['candidates'][0];
    }

    // ─── T-1…T-6 · state → verdict → operability (architecture §H) ───────────

    public function test_t1_active_assignment_resolves_and_is_operable(): void
    {
        $this->requireResolver();
        $this->record('WI-1', 'S-a', 'implementation', 'ACTIVE');

        $r = $this->resolve();

        $this->assertSame('RESOLVED', $r['out']['verdict']);
        $this->assertTrue($r['out']['operable'], 'ACTIVE is operable — and operable still ≠ authorized');
        $c = $this->onlyCandidate($r['out']);
        $this->assertSame('S-a', $c['session']);
        $this->assertSame('implementation', $c['role']);
        $this->assertSame('S-a', $r['out']['mutationOwner']);
    }

    public function test_t2_created_assignment_is_not_operable_and_names_missing_recorded_start(): void
    {
        $this->requireResolver();
        $this->record('WI-2', 'S-b', 'implementation', 'CREATED');

        $r = $this->resolve();

        $this->assertSame('RESOLVED', $r['out']['verdict']);
        $this->assertFalse($r['out']['operable']);
        $missing = implode(' ', $r['out']['missingForActivation'] ?? []);
        $this->assertStringContainsStringIgnoringCase('recorded', $missing,
            '"recorded" is load-bearing: a live-but-unregistered act still counts as missing');
        $this->assertStringContainsStringIgnoringCase('start', $missing);
    }

    public function test_t3_handed_off_assignment_is_not_operable(): void
    {
        $this->requireResolver();
        $this->record('WI-3', 'S-c', 'implementation', 'ACTIVE');
        $this->mechanism(['append', 'WI-3', '--json=' . json_encode(['type' => 'REGISTER', 'session' => 'S-d',
            'role' => 'verification', 'predecessor' => 'S-c', 'executionContext' => 'shared-worktree',
            'recordedBy' => 'governance'])]);
        $this->mechanism(['append', 'WI-3', '--json=' . json_encode(['type' => 'HANDOFF', 'from' => 'S-c',
            'to' => 'S-d', 'token' => 'T-2', 'tokenRef' => 'ref', 'recordedBy' => 'governance'])]);

        $r = $this->resolve(['--session=S-c']);

        $this->assertSame('RESOLVED', $r['out']['verdict']);
        $this->assertFalse($r['out']['operable'], 'work passed to a successor is not operable');
        $this->assertSame('HANDED_OFF', $this->onlyCandidate($r['out'])['state']);
    }

    public function test_t4_stopped_is_reported_as_continuation_only(): void
    {
        $this->requireResolver();
        $this->record('WI-4', 'S-e', 'implementation', 'STOPPED');

        $r = $this->resolve();

        $this->assertFalse($r['out']['operable']);
        $this->assertStringContainsStringIgnoringCase('continuation', json_encode($r['out']),
            'STOPPED is sticky: the resolver states the CONTINUATION requirement and cannot perform it');
    }

    /**
     * @dataProvider terminalStates
     */
    public function test_t5_terminal_states_are_resolved_but_never_operable(string $state): void
    {
        $this->requireResolver();
        $this->record('WI-5-' . strtolower($state), 'S-f', 'implementation', $state);

        $r = $this->resolve();

        $this->assertSame('RESOLVED', $r['out']['verdict']);
        $this->assertFalse($r['out']['operable'], "{$state} is terminal — a new need is a NEW assignment (R8)");
    }

    public static function terminalStates(): array
    {
        return [['COMPLETED'], ['CANCELLED'], ['FAILED']];
    }

    public function test_t6_no_assignment_yields_unassigned_and_absence_is_not_permission(): void
    {
        $this->requireResolver();
        $this->record('WI-6', 'S-g', 'implementation', 'ACTIVE');

        $r = $this->resolve(['--role=governance']);   // filter matches nothing

        $this->assertSame('UNASSIGNED', $r['out']['verdict']);
        $this->assertNotTrue($r['out']['operable'] ?? false);
        $this->assertStringContainsStringIgnoringCase('governance', json_encode($r['out']['reasons'] ?? []),
            'absence is not permission: the report directs the caller to request Governance registration');
    }

    // ─── T-7 · ambiguity is a success verdict, never a silent choice ─────────

    public function test_t7_multiple_candidates_yield_ambiguous_with_all_listed_and_none_chosen(): void
    {
        $this->requireResolver();
        $this->record('WI-7a', 'S-h', 'implementation', 'ACTIVE');
        $this->record('WI-7b', 'S-i', 'implementation', 'ACTIVE');

        $r = $this->resolve(['--role=implementation']);

        $this->assertSame('AMBIGUOUS', $r['out']['verdict']);
        $this->assertCount(2, $r['out']['candidates'], 'every candidate must be listed');
        $this->assertArrayNotHasKey('selected', $r['out'],
            'silent selection among candidates is a contract violation (P-3)');
        $this->assertNotTrue($r['out']['operable'] ?? false);
    }

    // ─── T-8 · UNRESOLVABLE is STOP-shaped; corruption is never papered over ─

    public function test_t8_absent_and_corrupt_records_yield_unresolvable_with_reasons(): void
    {
        $this->requireResolver();

        $empty = $this->resolve();
        $this->assertSame('UNRESOLVABLE', $empty['out']['verdict'],
            'no readable record: "no record = ungoverned = free" is forbidden reasoning');

        file_put_contents($this->dir . '/WI-8.json', '{ this is not valid json');
        $corrupt = $this->resolve();

        $this->assertSame('UNRESOLVABLE', $corrupt['out']['verdict']);
        $this->assertStringContainsStringIgnoringCase('WI-8', json_encode($corrupt['out']['reasons'] ?? []),
            'invalid-record facts always appear in reasons — never a silent skip (P-7)');
    }

    // ─── T-9 · grant scope passes through verbatim ──────────────────────────

    public function test_t9_grant_scope_is_reported_verbatim(): void
    {
        $this->requireResolver();
        $this->record('WI-9', 'S-j', 'implementation', 'ACTIVE');
        $scope = 'Implement X strictly within the AMENDED boundary; NOT authorized: anything else';
        $this->mechanism(['grant', 'WI-9', '--writer-role=governance', '--json=' . json_encode([
            'grantId' => 'G-9', 'status' => 'AUTHORIZED', 'authority' => 'PO',
            'humanActRef' => 'artifact#1', 'scope' => $scope])]);

        $r = $this->resolve();

        $this->assertStringContainsString($scope, json_encode($r['out']),
            'scope is surfaced byte-for-byte — the resolver never paraphrases authority (O-2)');
    }

    // ─── T-10 · six facts, UNKNOWNs, gap note, unconditional caveat ─────────

    public function test_t10_authorization_facts_gap_note_and_caveat_are_surfaced(): void
    {
        $this->requireResolver();
        $this->record('WI-10', 'S-k', 'implementation', 'ACTIVE');

        $r = $this->resolve();
        $facts = $r['out']['authorizationFacts'] ?? [];

        $this->assertCount(6, $facts, 'the actor conjunction is surfaced as six separate facts');
        $unknown = array_filter($facts, fn ($f) => str_contains(strtoupper(json_encode($f)), 'UNKNOWN'));
        $this->assertGreaterThanOrEqual(2, count($unknown),
            'grant↔session/role linkage and scope coverage are NOT evaluable from the record — UNKNOWN is a first-class answer');
        $this->assertStringContainsStringIgnoringCase('NOT EXPRESSIBLE',
            json_encode($r['out']['readOnlyParticipation'] ?? ''),
            'the record cannot express read-only participation (O-1/O-4) — the gap is named, never invented');

        foreach ([[], ['--role=nobody']] as $args) {     // RESOLVED and UNASSIGNED paths
            $any = $this->resolve($args);
            $this->assertStringContainsStringIgnoringCase('Resolution is not activation',
                json_encode($any['out']), 'the caveat line is unconditional');
        }
    }

    // ─── T-11 · read purity — the strongest single assertion ────────────────

    public function test_t11_record_directory_is_byte_identical_across_every_verdict_path(): void
    {
        $this->requireResolver();
        $this->record('WI-11', 'S-l', 'implementation', 'ACTIVE');
        $this->record('WI-11b', 'S-m', 'verification', 'STOPPED');
        file_put_contents($this->dir . '/WI-11c.json', '{ corrupt');

        $before = $this->directoryFingerprint();

        $this->resolve();                        // AMBIGUOUS / UNRESOLVABLE mix
        $this->resolve(['--session=S-l']);       // RESOLVED
        $this->resolve(['--role=nobody']);       // UNASSIGNED
        $this->resolve(['--work-item=WI-11c']);  // UNRESOLVABLE (corrupt)

        $this->assertSame($before, $this->directoryFingerprint(),
            'read purity: the resolver never writes — not on any verdict, not even beside a corrupt record');
    }

    // ─── T-12 · AMENDMENT 1 exit contract ───────────────────────────────────

    public function test_t12_every_produced_report_succeeds_and_usage_errors_do_not(): void
    {
        $this->requireResolver();
        $this->record('WI-12', 'S-n', 'implementation', 'ACTIVE');

        $this->assertSame(0, $this->resolve()['code'], 'RESOLVED is a success');
        $this->assertSame(0, $this->resolve(['--role=nobody'])['code'],
            'UNASSIGNED is a valid answer, not a command failure (AMENDMENT 1)');

        $this->record('WI-12b', 'S-o', 'implementation', 'ACTIVE');
        $this->assertSame(0, $this->resolve(['--role=implementation'])['code'],
            'AMBIGUOUS is a valid answer, not a command failure (AMENDMENT 1)');

        $this->assertNotSame(0, $this->resolve(['--nonsense-flag=1'])['code'],
            'non-zero is reserved for usage/refusal');
    }

    // ─── T-14 · C-1 interpreter identity, every verdict, both renderings ────

    /**
     * The report must say WHICH workflow interpreter supplied the answer.
     * Identity is INFORMATION ONLY: reported, never validated, never compared
     * against the registry, never a ground for refusal (grant G-KOS-DISC-C12-IMPL).
     */
    public function test_t14_interpreter_identity_is_reported_for_every_verdict(): void
    {
        $this->requireResolver();
        $this->record('WI-14', 'S-q', 'implementation', 'ACTIVE');
        $this->record('WI-14b', 'S-r', 'implementation', 'ACTIVE');
        file_put_contents($this->dir . '/WI-14c.json', '{ corrupt');

        $expected = $this->repoRoot . '/' . self::MECHANISM;

        $paths = [
            'RESOLVED'     => ['--session=S-q'],
            'UNASSIGNED'   => ['--role=nobody'],
            'AMBIGUOUS'    => ['--role=implementation'],
            'UNRESOLVABLE' => ['--work-item=WI-14c'],
        ];

        foreach ($paths as $verdict => $args) {
            $json = $this->resolve($args);
            $this->assertSame($verdict, $json['out']['verdict'], "precondition: {$verdict} path");
            $this->assertSame($expected, $json['out']['interpreter']['path'] ?? null,
                "C-1: machine output must name the interpreter on {$verdict}");
        }

        // Human rendering (no --json) must name it too — same four verdicts.
        foreach ($paths as $verdict => $args) {
            $cmd = array_merge(['php', $this->repoRoot . '/' . self::RESOLVER, '--dir=' . $this->dir], $args);
            $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
            $human = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($proc);

            $this->assertStringContainsString($expected, $human,
                "C-1: human output must name the interpreter on {$verdict}");
        }
    }

    // ─── T-15 · C-1 identity tracks a deliberately substituted interpreter ──

    public function test_t15_reported_identity_changes_when_the_interpreter_is_substituted(): void
    {
        $this->requireResolver();
        $this->record('WI-15', 'S-s', 'implementation', 'ACTIVE');

        $substitute = sys_get_temp_dir() . '/kos-substitute-' . bin2hex(random_bytes(4)) . '.php';
        copy($this->repoRoot . '/' . self::MECHANISM, $substitute);

        try {
            $r = $this->resolve(['--work-item=WI-15'], ['KOS_MECHANISM_PATH' => $substitute]);

            $this->assertSame($substitute, $r['out']['interpreter']['path'] ?? null,
                'C-1: the reported identity must follow the interpreter actually used');
            $this->assertSame('RESOLVED', $r['out']['verdict'],
                'identity is information only — a substituted interpreter is reported, never refused');
        } finally {
            @unlink($substitute);
        }
    }

    // ─── T-13 · AMENDMENT 2 delegation / precedence integrity ───────────────

    public function test_t13_resolver_delegates_interpretation_to_the_qualified_mechanism(): void
    {
        $this->requireResolver();
        $this->record('WI-13', 'S-p', 'implementation', 'ACTIVE');

        // (a) AGREEMENT: the resolver's answer equals the qualified mechanism's output.
        $foldCmd = ['php', $this->repoRoot . '/' . self::MECHANISM, 'fold', 'WI-13', '--dir=' . $this->dir];
        $proc = proc_open($foldCmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $fold = json_decode(stream_get_contents($pipes[1]), true);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);

        $r = $this->resolve(['--work-item=WI-13']);
        $c = $this->onlyCandidate($r['out']);

        $this->assertSame($fold['sessions']['S-p']['state'], $c['state'],
            'state must equal the qualified mechanism\'s folded state — never a second interpretation');
        $this->assertSame($fold['mutationOwner'], $r['out']['mutationOwner']);
        $this->assertSame($fold['sessions']['S-p']['role'], $c['role']);

        // (b) INABILITY: with the qualified mechanism unavailable, the resolver
        //     cannot determine workflow state on its own — it refuses, it does
        //     not fall back to reading/interpreting the record itself.
        $degraded = $this->resolve(['--work-item=WI-13'], ['KOS_MECHANISM_PATH' => '/nonexistent/workflow-state.php']);

        $this->assertNotSame('RESOLVED', $degraded['out']['verdict'] ?? null,
            'without the authoritative interpreter the resolver must NOT produce a resolved state');
        $this->assertStringContainsStringIgnoringCase('mechanism', json_encode($degraded['out'] ?? $degraded['raw']),
            'the refusal must name the unavailable qualified mechanism');
    }
}
