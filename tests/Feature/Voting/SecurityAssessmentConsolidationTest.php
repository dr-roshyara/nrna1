<?php

namespace Tests\Feature\Voting;

use App\Application\Election\Security\OverlayAggregator;
use App\Application\Election\Security\PolicySequence;
use App\Application\Election\Security\SecurityEventRecorder;
use App\Application\Election\Security\TrustPolicyEvaluator;
use App\Application\Election\Security\TrustSnapshotAssembler;
use App\Contexts\Elections\Application\Ports\VotingSecurityVerdictQuery;
use App\Domain\Election\Security\TrustEvaluationEnvelope;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\TrustEvidencePrivacyPolicy;
use App\Domain\Election\Security\VotingTrustResult;
use App\Http\Controllers\VoteController;
use App\Models\Code;
use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\TestCase;

/**
 * PBDIGIT-240 — RED: one security assessment for the final cast (I3).
 *
 * Plan: docs/plans/20261005-2244-pbdigit-240-security-assessment-red-plan.md
 * Design: docs/publicdigit/reviews/2026-10-05-pbdigit-240-security-authority-design-proposal.md (D-240-1 … 4)
 *
 *   P1   no voting controller (demo excluded, S2 / Q4) uses the Trust → Legitimacy chain itself      RED at HEAD
 *   P1b  IP-density queries in controllers only in the known 2B debt methods                           RED at HEAD
 *   P2   one final cast → exactly one evaluation, and it comes through the Security Verdict Authority  RED at HEAD
 *   C1   D.0.3a messages for Denied / Deferred / Investigate                                           characterization
 *   C2   the audit line "Trust evaluation completed in vote submission"                                characterization
 *   C3   sovereignty-divergence records at the gate, the lifecycle and the membership denial          characterization
 *
 * Test-only seam: a recording subclass of TrustPolicyEvaluator (non-final, container singleton) counts
 * evaluate() calls, records the call stack and can replace the trust state to reach each D.0.3a outcome.
 * Synthetic data only. No production change.
 */
class SecurityAssessmentConsolidationTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

    private const DENIED_MESSAGE      = 'Your vote cannot be submitted based on constitutional verification. Please contact the election committee.';
    private const DEFERRED_MESSAGE    = 'Your vote requires additional verification before submission. Please contact the election committee.';
    private const INVESTIGATE_MESSAGE = 'Your vote has been flagged for manual review. Please contact the election committee.';
    private const DIVERGENCE          = 'Sovereignty divergence detected';

    /** Known 2B debt (Q3): controller methods that still run their own IP-density query. */
    private const KNOWN_IP_DENSITY_METHODS = [
        'CodeController::verifyCode',
        'VoteController::vote_post_check',
        'ElectionVotingController::evaluateIpCount',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeVotingElection();
    }

    protected function tearDown(): void
    {
        $this->tearDownCast();
        parent::tearDown();
    }

    // ── Test-only seam ───────────────────────────────────────────────────────

    /** Bind a recording evaluator; $forced replaces the trust state of every evaluation. */
    private function installRecordingEvaluator(?TrustEvaluationState $forced = null): TrustPolicyEvaluator
    {
        $evaluator = new class(
            app(OverlayAggregator::class),
            app(PolicySequence::class),
            app(SecurityEventRecorder::class),
            app(TrustEvidencePrivacyPolicy::class),
            app(TrustSnapshotAssembler::class),
        ) extends TrustPolicyEvaluator {
            public ?TrustEvaluationState $forced = null;
            public int $calls = 0;
            /** @var list<?string> */
            public array $directCallers = [];
            /** @var list<list<string>> */
            public array $stacks = [];

            public function evaluate(
                ?Election $election,
                ?User $user,
                string $rawIp,
                ?string $rawFingerprint,
                string $sessionId,
                ?string $registeredIpHash = null,
                ?int $votesFromThisIp = null,
                ?bool $ipBindingSatisfied = null,   // PBDIGIT-247 added this to the parent; without it the file fails to load
            ): TrustEvaluationEnvelope {
                $this->calls++;
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 40);
                $this->directCallers[] = $trace[1]['class'] ?? null;
                $this->stacks[] = array_values(array_filter(array_map(fn (array $f) => $f['class'] ?? null, $trace)));

                $envelope = parent::evaluate($election, $user, $rawIp, $rawFingerprint, $sessionId, $registeredIpHash, $votesFromThisIp, $ipBindingSatisfied);
                if ($this->forced === null) {
                    return $envelope;
                }

                $r = $envelope->result;
                $result = match ($this->forced) {
                    TrustEvaluationState::INSUFFICIENT_EVIDENCE => VotingTrustResult::insufficientEvidence('test double: forced', $r->trustLevel, $r->auditContext, $r->policyOutcomeSequence),
                    TrustEvaluationState::REVIEW_REQUIRED       => VotingTrustResult::reviewRequired('test double: forced', $r->auditContext, $r->policyOutcomeSequence),
                    TrustEvaluationState::INCONCLUSIVE          => VotingTrustResult::inconclusive('test double: forced', $r->auditContext, $r->policyOutcomeSequence),
                    default                                     => $r,
                };

                return new TrustEvaluationEnvelope($result, $envelope->overlayObservations, $envelope->snapshot, $envelope->eligibility);
            }
        };

        $evaluator->forced = $forced;
        $this->app->instance(TrustPolicyEvaluator::class, $evaluator);

        return $evaluator;
    }

    private function castWith(User $voter, VoterSlug $slug, Code $code, array $extraSession = [])
    {
        return $this->actingAs($voter)
            ->withSession(array_merge([
                'current_organisation_id' => $this->org->id,
                $code->session_name       => $this->ballotSessionData(),
            ], $extraSession))
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), [
                'voting_code' => $code->code_to_open_voting_form,
            ]);
    }

    private function assertNothingConsumed(Code $code): void
    {
        $this->assertSame(0, DB::table('votes')->where('election_id', $this->election->id)->count(), 'no vote is written');
        $this->assertFalse((bool) $code->fresh()->has_voted, 'has_voted is not consumed');
    }

    /** @return list<string> PHP files under app/Http/Controllers, demo controllers excluded (S2 / Q4). */
    private function votingControllerFiles(): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(app_path('Http/Controllers'), \FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            $path = $file->getPathname();
            if ($file->getExtension() === 'php' && ! str_contains($path, DIRECTORY_SEPARATOR . 'Demo' . DIRECTORY_SEPARATOR)) {
                $files[] = $path;
            }
        }
        sort($files);

        return $files;
    }

    // ── P1 / P1b: properties ─────────────────────────────────────────────────

    public function test_p1_no_voting_controller_runs_the_trust_legitimacy_chain_itself(): void
    {
        $violations = [];
        foreach ($this->votingControllerFiles() as $path) {
            $source = file_get_contents($path);
            foreach (['TrustPolicyEvaluator', 'ConstitutionalLegitimacyDecision'] as $type) {
                if (str_contains($source, $type)) {
                    $violations[] = basename($path) . " references {$type}";
                }
            }
        }

        $this->assertSame([], $violations, 'I3 / D-240: only the Security Verdict Authority evaluates the Trust → Legitimacy chain');
    }

    public function test_p1b_ip_density_queries_in_controllers_exist_only_in_the_known_2b_debt_methods(): void
    {
        $found = [];
        foreach ($this->votingControllerFiles() as $path) {
            $source = file_get_contents($path);
            preg_match_all('/->where\(\s*[\'"](client_ip|step_1_ip)[\'"]/', $source, $matches, PREG_OFFSET_CAPTURE);
            foreach ($matches[0] as [, $offset]) {
                preg_match_all('/function\s+(\w+)\s*\(/', substr($source, 0, $offset), $functions);
                $found[] = basename($path, '.php') . '::' . (end($functions[1]) ?: '?');
            }
        }

        $unexpected = array_values(array_diff(array_unique($found), self::KNOWN_IP_DENSITY_METHODS));
        $this->assertSame([], $unexpected, 'I3: no new IP-density evaluation outside the authority (known 2B debt is pinned)');
    }

    // ── P2: one evaluation, through the authority ────────────────────────────

    public function test_p2_a_final_cast_evaluates_the_chain_once_through_the_security_verdict_authority(): void
    {
        $evaluator = $this->installRecordingEvaluator();
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $response = $this->castWith($voter, $slug, $code);

        $response->assertSessionHasNoErrors();
        $this->assertTrue((bool) $code->fresh()->has_voted, 'precondition: the cast succeeded');
        $this->assertSame(1, $evaluator->calls, 'exactly one Trust → Legitimacy evaluation per cast');

        $throughAuthority = (bool) array_filter(
            $evaluator->stacks[0] ?? [],
            fn (string $class) => is_subclass_of($class, VotingSecurityVerdictQuery::class)
        );
        $this->assertTrue($throughAuthority, 'the evaluation comes through the Security Verdict Authority (D-240-3)');
        $this->assertNotSame(VoteController::class, $evaluator->directCallers[0] ?? null, 'VoteController does not call the evaluator itself');
    }

    // ── C1: D.0.3a messages (characterization) ───────────────────────────────

    /** @return array<string, array{TrustEvaluationState, string}> */
    public static function gateOutcomes(): array
    {
        return [
            'Denied'      => [TrustEvaluationState::INSUFFICIENT_EVIDENCE, self::DENIED_MESSAGE],
            'Deferred'    => [TrustEvaluationState::REVIEW_REQUIRED, self::DEFERRED_MESSAGE],
            'Investigate' => [TrustEvaluationState::INCONCLUSIVE, self::INVESTIGATE_MESSAGE],
        ];
    }

    /** @dataProvider gateOutcomes */
    public function test_c1_the_d03a_gate_refuses_with_its_outcome_specific_message(TrustEvaluationState $state, string $message): void
    {
        $this->installRecordingEvaluator($state);
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $response = $this->castWith($voter, $slug, $code);

        $this->assertNotSame(500, $response->status());
        $response->assertSessionHasErrors(['vote' => $message]);
        $this->assertNothingConsumed($code);
    }

    // ── C2: audit line (characterization) ────────────────────────────────────

    public function test_c2_the_cast_writes_the_trust_evaluation_audit_line(): void
    {
        $this->captureLogs();
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $this->castWith($voter, $slug, $code)->assertSessionHasNoErrors();

        $lines = array_values(array_filter($this->logged, fn (array $e) => $e['message'] === 'Trust evaluation completed in vote submission'));
        $this->assertCount(1, $lines, 'one audit line per cast');
        $this->assertSame(['election_id', 'user_id', 'trust_result', 'constitutional_outcome', 'ip'], array_keys($lines[0]['context']));
        $this->assertSame('allowed', strtolower((string) $lines[0]['context']['constitutional_outcome']));
    }

    // ── C3: sovereignty-divergence records (characterization) ────────────────

    private function divergenceRecords(): int
    {
        return count(array_filter($this->logged, fn (array $e) => $e['message'] === self::DIVERGENCE));
    }

    /** @return array<string, array{TrustEvaluationState, int}> */
    public static function gateDivergences(): array
    {
        return [
            'Denied (outcomes match)' => [TrustEvaluationState::INSUFFICIENT_EVIDENCE, 0],
            'Deferred'                => [TrustEvaluationState::REVIEW_REQUIRED, 1],
            'Investigate'             => [TrustEvaluationState::INCONCLUSIVE, 1],
        ];
    }

    /** @dataProvider gateDivergences */
    public function test_c3_the_gate_records_divergence_only_when_the_outcomes_differ(TrustEvaluationState $state, int $expected): void
    {
        $this->captureLogs();
        $this->installRecordingEvaluator($state);
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $this->castWith($voter, $slug, $code)->assertSessionHasErrors('vote');

        $this->assertSame($expected, $this->divergenceRecords());
    }

    public function test_c3_a_membership_denial_records_one_divergence(): void
    {
        $this->captureLogs();
        [$voter, $slug, $code] = $this->voterReadyToCast();
        // Cached standing "allowed", then the membership ends without model events (FinalCastExitSafetyTest pattern).
        app(\App\Contexts\Elections\Application\Ports\VotingEntitlementQuery::class)->standingOf($voter->id, $this->election->id);
        DB::table('election_memberships')->where('user_id', $voter->id)->where('election_id', $this->election->id)->update(['status' => 'inactive']);

        $response = $this->castWith($voter, $slug, $code);

        $response->assertSessionHas('error', 'You are not assigned to vote in this election.'); // precondition: membership exit
        $this->assertSame(1, $this->divergenceRecords());
    }

    public function test_c3_store_lifecycle_site_is_not_reached_on_the_slug_route(): void
    {
        // Characterization (2026-10-05): store()'s own lifecycle denial (and its divergence record) is
        // not reachable through slug.vote.store — the route's 'voting.active' middleware (EnsureVotingActive,
        // no demo bypass) refuses a non-votable election first, even with the leftover demo session flag
        // that skips 'vote.eligibility' (F-C2 / PBDIGIT-217). store() is not entered: no evaluation, no record.
        $this->captureLogs();
        $evaluator = $this->installRecordingEvaluator();
        [$voter, $slug, $code] = $this->voterReadyToCast();
        DB::table('elections')->where('id', $this->election->id)->update(['suspended_at' => now()]);

        $response = $this->castWith($voter, $slug, $code, ['selected_election_type' => 'demo']);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'Election is suspended - resume to continue');
        $this->assertSame(0, $evaluator->calls, 'store() is not entered');
        $this->assertSame(0, $this->divergenceRecords());
        $this->assertNothingConsumed($code);
    }
}
