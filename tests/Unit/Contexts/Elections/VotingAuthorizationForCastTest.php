<?php

namespace Tests\Unit\Contexts\Elections;

use App\Contexts\Elections\Application\DTO\CastAttempt;
use App\Contexts\Elections\Application\DTO\CastDecision;
use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Application\Services\VotingAuthorization;
use App\Contexts\Elections\Domain\ValueObjects\CastDecisionReason;
use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use PHPUnit\Framework\TestCase;

/**
 * Phase 4 · E2 · R2 — VotingAuthorization::forCast() → CastDecision.
 *
 * Plan: docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md §2.1, §4 R2
 * ADR_20261005_0950 §3.2 (forCast = fresh authorization + process validation),
 * §3.3 (reason vocabulary, Allowed the only success), I1, I5.
 * PO decisions: Q2 = Deny (review required is a denial), Q5 = A (no agreement reason),
 * P4-D1 = B (no IP predicate in forCast), P4-D2 = A (CodeMissing, BallotMissing).
 *
 * forCast() is pure composition: it reads the entitlement port once (decide():
 * standing ∧ lifecycle) and evaluates the facts in the CastAttempt. It writes nothing
 * and never decides "already voted" authoritatively (only the CAS does — E4).
 *
 * Order (first failure wins): entitlement → security → process; process order:
 * CodeMissing → AlreadyVotedPreCheck → CodeMismatch → CodeStepIncomplete → BallotMissing.
 */
final class VotingAuthorizationForCastTest extends TestCase
{
    private const VOTER = '0198a1b2-0000-7000-8000-000000000001';
    private const ELECTION = '0198a1b2-0000-7000-8000-0000000000e1';

    // ── Fixtures ─────────────────────────────────────────────────────────────

    /** Entitlement port double that answers decide() with $reason and records every call. */
    private function entitlement(VotingEntitlementReason $reason): VotingEntitlementQuery
    {
        return new class($reason) implements VotingEntitlementQuery {
            /** @var list<array{string,string,string}> */
            public array $calls = [];

            public function __construct(private VotingEntitlementReason $reason) {}

            public function standingOf(string $userId, string $electionId): VotingEntitlementReason
            {
                $this->calls[] = ['standingOf', $userId, $electionId];

                return $this->reason;
            }

            public function decide(string $userId, string $electionId): VotingEntitlementReason
            {
                $this->calls[] = ['decide', $userId, $electionId];

                return $this->reason;
            }
        };
    }

    /** A cast attempt whose facts all pass, with selected facts overridden. */
    private function attempt(array $override = []): CastAttempt
    {
        $facts = array_merge([
            'voterId'              => self::VOTER,
            'electionId'           => self::ELECTION,
            'security'             => SecurityVerdict::Allowed,
            'codeExists'           => true,
            'submittedCodeMatches' => true,
            'codeStepCompleted'    => true,
            'alreadyVotedPreCheck' => false,
            'ballotPresent'        => true,
        ], $override);

        return new CastAttempt(...$facts);
    }

    private function forCast(CastAttempt $attempt, VotingEntitlementReason $entitlement = VotingEntitlementReason::Entitled): CastDecision
    {
        return (new VotingAuthorization($this->entitlement($entitlement)))->forCast($attempt);
    }

    // ── Allowed is the only success ──────────────────────────────────────────

    public function test_an_entitled_secure_and_valid_attempt_is_allowed(): void
    {
        $decision = $this->forCast($this->attempt());

        $this->assertSame(CastDecisionReason::Allowed, $decision->reason);
        $this->assertTrue($decision->isAllowed());
    }

    public function test_allowed_is_the_only_reason_that_is_allowed(): void
    {
        foreach (CastDecisionReason::cases() as $reason) {
            $this->assertSame(
                $reason === CastDecisionReason::Allowed,
                (new CastDecision($reason))->isAllowed(),
                "{$reason->name} must " . ($reason === CastDecisionReason::Allowed ? '' : 'not ') . 'be allowed'
            );
        }
    }

    // ── Authorization: entitlement (forwarded 1:1) ───────────────────────────

    public function test_every_entitlement_denial_is_forwarded_one_to_one(): void
    {
        foreach (VotingEntitlementReason::cases() as $entitlement) {
            if ($entitlement === VotingEntitlementReason::Entitled) {
                continue;
            }

            $decision = $this->forCast($this->attempt(), $entitlement);

            $this->assertSame($entitlement->name, $decision->reason->name, "{$entitlement->name} forwarded by name");
            $this->assertSame($entitlement->value, $decision->reason->value, "{$entitlement->name} forwarded by value");
            $this->assertFalse($decision->isAllowed());
        }
    }

    public function test_entitlement_is_read_once_through_decide_which_includes_the_lifecycle(): void
    {
        $port = $this->entitlement(VotingEntitlementReason::Entitled);

        (new VotingAuthorization($port))->forCast($this->attempt());

        $this->assertSame([['decide', self::VOTER, self::ELECTION]], $port->calls,
            'exactly one decide() call for this voter and election; standingOf() (no lifecycle) is never used');
    }

    // ── Authorization: security (the existing chain's verdict) ──────────────

    public function test_a_security_denial_is_security_denied(): void
    {
        $decision = $this->forCast($this->attempt(['security' => SecurityVerdict::Denied]));

        $this->assertSame(CastDecisionReason::SecurityDenied, $decision->reason);
        $this->assertFalse($decision->isAllowed());
    }

    public function test_a_security_review_requirement_is_a_denial_q2(): void
    {
        $decision = $this->forCast($this->attempt(['security' => SecurityVerdict::ReviewRequired]));

        $this->assertSame(CastDecisionReason::SecurityReviewRequired, $decision->reason);
        $this->assertFalse($decision->isAllowed(), 'Q2 = Deny: Deferred / Investigate never allow a cast');
    }

    // ── Process validation ───────────────────────────────────────────────────

    public function test_a_missing_code_is_code_missing(): void
    {
        $this->assertSame(CastDecisionReason::CodeMissing, $this->forCast($this->attempt(['codeExists' => false]))->reason);
    }

    public function test_an_already_voted_code_is_the_non_authoritative_pre_check(): void
    {
        $this->assertSame(CastDecisionReason::AlreadyVotedPreCheck, $this->forCast($this->attempt(['alreadyVotedPreCheck' => true]))->reason);
    }

    public function test_a_wrong_submitted_code_is_code_mismatch(): void
    {
        $this->assertSame(CastDecisionReason::CodeMismatch, $this->forCast($this->attempt(['submittedCodeMatches' => false]))->reason);
    }

    public function test_an_incomplete_code_step_is_code_step_incomplete(): void
    {
        $this->assertSame(CastDecisionReason::CodeStepIncomplete, $this->forCast($this->attempt(['codeStepCompleted' => false]))->reason);
    }

    public function test_a_missing_ballot_is_ballot_missing(): void
    {
        $this->assertSame(CastDecisionReason::BallotMissing, $this->forCast($this->attempt(['ballotPresent' => false]))->reason);
    }

    // ── Order: first failure wins ────────────────────────────────────────────

    public function test_entitlement_is_evaluated_before_security_and_process(): void
    {
        $everythingFails = $this->attempt([
            'security'             => SecurityVerdict::Denied,
            'codeExists'           => false,
            'submittedCodeMatches' => false,
            'codeStepCompleted'    => false,
            'alreadyVotedPreCheck' => true,
            'ballotPresent'        => false,
        ]);

        $this->assertSame(CastDecisionReason::NotAdmitted, $this->forCast($everythingFails, VotingEntitlementReason::NotAdmitted)->reason);
    }

    public function test_security_is_evaluated_before_process(): void
    {
        $securityAndProcessFail = $this->attempt([
            'security'             => SecurityVerdict::ReviewRequired,
            'codeExists'           => false,
            'ballotPresent'        => false,
        ]);

        $this->assertSame(CastDecisionReason::SecurityReviewRequired, $this->forCast($securityAndProcessFail)->reason);
    }

    public function test_process_reasons_follow_the_fixed_order(): void
    {
        $order = [
            [CastDecisionReason::CodeMissing,          ['codeExists' => false]],
            [CastDecisionReason::AlreadyVotedPreCheck, ['alreadyVotedPreCheck' => true]],
            [CastDecisionReason::CodeMismatch,         ['submittedCodeMatches' => false]],
            [CastDecisionReason::CodeStepIncomplete,   ['codeStepCompleted' => false]],
            [CastDecisionReason::BallotMissing,        ['ballotPresent' => false]],
        ];

        // With this failure AND every later one present, this one is reported.
        foreach ($order as $i => [$expected, $_]) {
            $failures = [];
            foreach (array_slice($order, $i) as [, $override]) {
                $failures += $override;
            }

            $this->assertSame($expected, $this->forCast($this->attempt($failures))->reason, "{$expected->name} precedes the later process reasons");
        }
    }

    // ── Vocabulary (ADR §3.3 + P4-D2; Q5 = A; P4-D1 = B) ────────────────────

    public function test_the_reason_vocabulary_is_exactly_the_approved_set(): void
    {
        $names = array_map(fn (CastDecisionReason $r) => $r->name, CastDecisionReason::cases());
        sort($names);

        $expected = [
            'Allowed',
            // forwarded entitlement reasons
            'NotRegistered', 'NotVoterRole', 'OrganisationMismatch', 'NotActive', 'NotAdmitted', 'ElectionNotVotable',
            // security (the existing Trust → Legitimacy chain's verdict)
            'SecurityDenied', 'SecurityReviewRequired',
            // process (P4-D2: CodeMissing, BallotMissing)
            'CodeMissing', 'AlreadyVotedPreCheck', 'CodeMismatch', 'CodeStepIncomplete', 'BallotMissing',
        ];
        sort($expected);

        $this->assertSame($expected, $names,
            'no AgreementMissing (Q5 = A), no IP-density reason (P4-D1 = B), no "already consumed" (CAS outcome, not a decision)');
    }

    public function test_the_security_verdict_has_exactly_three_values(): void
    {
        $this->assertSame(
            ['Allowed', 'Denied', 'ReviewRequired'],
            array_map(fn (SecurityVerdict $v) => $v->name, SecurityVerdict::cases())
        );
    }
}
