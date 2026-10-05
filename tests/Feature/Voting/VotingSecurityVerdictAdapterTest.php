<?php

namespace Tests\Feature\Voting;

use App\Application\Election\Security\ConstitutionalLegitimacyDecision;
use App\Application\Election\Security\TrustPolicyEvaluator;
use App\Contexts\Elections\Application\DTO\SecurityAttemptContext;
use App\Contexts\Elections\Application\Ports\VotingSecurityVerdictQuery;
use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;
use App\Domain\Election\Security\LegitimacyOutcome;
use App\Domain\Election\Security\TrustEvidencePrivacyPolicy;
use App\Models\Code;
use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-239 · Phase 5 Round 2A · RED — EXTRACTION CHARACTERIZATION of the one Security Verdict Authority (Stage A).
 *
 * CHARACTERIZATION INVARIANT: for every characterized valid security context, the adapter behind
 * VotingSecurityVerdictQuery produces EXACTLY the same SecurityVerdict as the existing inline Phase-4 path in
 * VoteController::store() — without introducing or removing evidence, policy, threshold or exception semantics.
 * This proves Stage A (extract) and Stage C (VoteController consumes the adapter) behaviour-preserving.
 *
 * CONTEXT CONTRACT: SecurityAttemptContext is the security-attempt input (userId, electionId, clientIp,
 * deviceFingerprint, sessionId). The inline path additionally holds the authenticated User and the request's
 * Election; the adapter resolves THE SAME rows from the context IDs (the user; the election without global scopes)
 * and reads only what the inline path reads from them (users.voting_ip; the election's max_votes_per_ip,
 * network_binding_strategy, ip_whitelist, voter_verification_required inside the evaluator). Callers set
 * clientIp = request()->ip(), deviceFingerprint = request input 'device_fingerprint', sessionId = session id —
 * exactly the inline sources (1495/1507, 1508, 1509). Oracle and adapter receive the SAME context object.
 *
 * ORACLE = a faithful test-side copy of the production path at HEAD (verified line by line) — no cleanup, no change:
 *   evidence   VoteController.php 1486–1491  registered-IP hash from $auth_user->voting_ip (column missing → null)
 *              VoteController.php 1495–1499  density: \App\Models\Code::where(election_id)->where(client_ip)
 *                                            ->where(has_voted, true)->count() — TENANT-SCOPED (BelongsToTenant:
 *                                            TenantContext ?? session('current_organisation_id'))
 *   evaluate   VoteController.php 1504–1512  TrustPolicyEvaluator::evaluate() (injected; container singleton)
 *   decide     VoteController.php 1516–1518  ConstitutionalLegitimacyDecision::decide() (injected; stateless)
 *   mapping    VoteController.php 1985–1993  securityVerdictOf(), used at 1679 for CastAttempt::$security
 * The ambient tenant here is the election's organisation (CastFixtures sets the session), as in VoteController::store().
 * Independent of CodeController::verifyCode() (its legacy threshold is NOT part of E2).
 *
 * Whitelist: see the separate KNOWN-DEFECT section at the end — not part of the equivalence contract.
 * Synthetic data only.
 */
class VotingSecurityVerdictAdapterTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

    private const IP = '10.77.0.1';
    private const OTHER_IP = '10.77.0.2';
    private const SESSION_ID = 'characterization-session';
    private const FINGERPRINT = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeVotingElection();   // real election; DB defaults: network_binding_strategy 'ip_count', max_votes_per_ip 6
    }

    protected function tearDown(): void
    {
        $this->tearDownCast();
        parent::tearDown();
    }

    // ── fixtures ────────────────────────────────────────────────────────────

    private function voter(): User
    {
        return User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);
    }

    /** $n other voters whose consumed participation (has_voted) was recorded from $ip in $election. */
    private function votedFrom(string $ip, int $n, ?Election $election = null): void
    {
        $election ??= $this->election;
        for ($i = 0; $i < $n; $i++) {
            Code::factory()->create([
                'organisation_id'          => $this->org->id,
                'user_id'                  => $this->voter()->id,
                'election_id'              => $election->id,
                'client_ip'                => $ip,
                'has_voted'                => 1,
                'code_to_open_voting_form' => sprintf('VTD%05d', $i),
            ]);
        }
    }

    /** ONE security context per case — the oracle and the adapter receive exactly this. */
    private function contextFor(User $user, Election $election, string $ip = self::IP): SecurityAttemptContext
    {
        return new SecurityAttemptContext(
            userId: $user->id,
            electionId: $election->id,
            clientIp: $ip,
            deviceFingerprint: self::FINGERPRINT,
            sessionId: self::SESSION_ID,
        );
    }

    /** ORACLE — faithful copy of VoteController::store() at HEAD (see class doc for line references). */
    private function inlineVerdict(SecurityAttemptContext $context, Election $election, User $auth_user): SecurityVerdict
    {
        $registeredIpHash = null;
        if ($auth_user && $auth_user->voting_ip) {
            $privacyPolicy = app(TrustEvidencePrivacyPolicy::class);
            $registeredIpHash = $privacyPolicy->hashIp($auth_user->voting_ip, $election->id);
        }

        $currentIp = $context->clientIp;
        $votesFromThisIp = Code::where('election_id', $election->id)
            ->where('client_ip', $currentIp)
            ->where('has_voted', true)
            ->count();

        $trustEnvelope = app(TrustPolicyEvaluator::class)->evaluate(
            election: $election,
            user: $auth_user,
            rawIp: $context->clientIp,
            rawFingerprint: $context->deviceFingerprint,
            sessionId: $context->sessionId,
            registeredIpHash: $registeredIpHash,
            votesFromThisIp: $votesFromThisIp,
        );

        $constitutionalOutcome = app(ConstitutionalLegitimacyDecision::class)->decide($trustEnvelope->result);

        return match ($constitutionalOutcome) {
            LegitimacyOutcome::Allowed => SecurityVerdict::Allowed,
            LegitimacyOutcome::Denied  => SecurityVerdict::Denied,
            LegitimacyOutcome::Deferred,
            LegitimacyOutcome::Investigate => SecurityVerdict::ReviewRequired,
        };
    }

    private function adapterVerdict(SecurityAttemptContext $context): SecurityVerdict
    {
        return app(VotingSecurityVerdictQuery::class)->verdictFor($context);
    }

    /** Tenant-scoped density exactly as the oracle counts it — for explicit preconditions. */
    private function densityOf(Election $election, string $ip): int
    {
        return Code::where('election_id', $election->id)->where('client_ip', $ip)->where('has_voted', true)->count();
    }

    private function assertEquivalent(SecurityVerdict $expected, User $user, Election $election, string $case): void
    {
        $context = $this->contextFor($user, $election);

        $inline = $this->inlineVerdict($context, $election, $user);
        $this->assertSame($expected, $inline, "{$case}: precondition — today's inline path yields {$expected->name}");
        $this->assertSame($inline, $this->adapterVerdict($context), "{$case}: the adapter yields exactly the inline verdict for the same context");
    }

    // ── equivalence with today's inline path ────────────────────────────────

    public function test_a01_no_prior_participation_from_the_ip_is_allowed(): void
    {
        $this->assertSame(0, $this->densityOf($this->election, self::IP));
        $this->assertEquivalent(SecurityVerdict::Allowed, $this->voter(), $this->election, 'a01');
    }

    public function test_a02_participation_at_the_effective_unverified_limit_is_still_allowed(): void
    {
        // max_votes_per_ip 6 → effective limit 3 for unverified voters (NetworkTrustEvidence); denial only when votes > 3.
        $this->votedFrom(self::IP, 3);
        $this->assertSame(3, $this->densityOf($this->election, self::IP));
        $this->assertEquivalent(SecurityVerdict::Allowed, $this->voter(), $this->election, 'a02');
    }

    public function test_a03_participation_above_the_effective_limit_is_denied(): void
    {
        $this->votedFrom(self::IP, 4);
        $this->assertSame(4, $this->densityOf($this->election, self::IP));
        $this->assertEquivalent(SecurityVerdict::Denied, $this->voter(), $this->election, 'a03');
    }

    public function test_a04_strategy_none_disables_the_density_rule(): void
    {
        $this->election->forceFill(['network_binding_strategy' => 'none'])->save();
        $this->votedFrom(self::IP, 4);
        $this->assertSame(4, $this->densityOf($this->election, self::IP));
        $this->assertEquivalent(SecurityVerdict::Allowed, $this->voter(), $this->election->fresh(), 'a04');
    }

    public function test_a05_participation_from_another_ip_or_another_election_does_not_count(): void
    {
        $other = ElectionScenarioFactory::votingActive($this->org);   // same organisation, same default policy
        $this->assertNotSame($this->election->id, $other->id);

        $this->votedFrom(self::OTHER_IP, 4);
        $this->votedFrom(self::IP, 4, $other);

        $this->assertSame(0, $this->densityOf($this->election, self::IP), 'precondition: main election + current IP has no participation');
        $this->assertSame(4, $this->densityOf($other, self::IP), 'precondition: the other election has 4 from the same IP');
        $this->assertEquivalent(SecurityVerdict::Allowed, $this->voter(), $this->election, 'a05');
    }

    public function test_a06_e2_follows_the_trust_legitimacy_election_policy_not_the_legacy_verify_code_threshold(): void
    {
        // CodeController::verifyCode() (legacy duplicate, retired in 2B) uses config('app.max_use_clientIP').
        // E2 uses elections.max_votes_per_ip via the Trust → Legitimacy chain — a lower env value must not matter.
        config(['app.max_use_clientIP' => 1]);
        $this->votedFrom(self::IP, 2);
        $this->assertSame(2, $this->densityOf($this->election, self::IP));
        $this->assertEquivalent(SecurityVerdict::Allowed, $this->voter(), $this->election, 'a06');
    }

    // ── KNOWN DEFECTS — characterization only · NOT target behaviour · NOT a security policy · NOT a GREEN acceptance criterion

    public function test_known_defect_whitelisted_ip_allowed_policy_is_not_characterizable_today(): void
    {
        // Approved policy intent: a whitelisted IP is Allowed (NetworkBindingPolicy).
        // Not reachable on this path at HEAD, for two reasons read in source:
        //  (1) TrustPolicyEvaluator.php:140 json_decode()s ip_whitelist, which Election.php:214 casts to array → TypeError;
        //  (2) NetworkTrustEvidence.php:19–23 compares the HASHED current IP against the raw whitelist entries
        //      (in_array strict) — even a decodable whitelist could never match.
        $this->markTestSkipped('Whitelist policy not characterizable on the inline path at HEAD — two known defects (see test body); separate backlog item.');
    }

    public function test_known_defect_a_non_empty_ip_whitelist_makes_the_chain_throw_and_the_adapter_must_not_hide_it(): void
    {
        // KNOWN DEFECT CHARACTERIZATION ONLY. Pinned so that Stage A cannot change it silently; fixing it is a separate,
        // decided item — when it is fixed, this test is replaced, not "kept green".
        // Fixture written with the query builder: the election is voting, and the PBDIGIT-244 model guard
        // (I9) refuses security-policy writes on a locked election (PO-approved fixture adaptation 2026-10-05).
        \Illuminate\Support\Facades\DB::table('elections')->where('id', $this->election->id)
            ->update(['ip_whitelist' => json_encode(['10.77.0.0/16'])]);
        $election = $this->election->fresh();
        $user = $this->voter();
        $context = $this->contextFor($user, $election);

        $inlineError = null;
        try {
            $this->inlineVerdict($context, $election, $user);
        } catch (\Throwable $e) {
            $inlineError = get_class($e);
        }
        $this->assertSame(\TypeError::class, $inlineError, 'precondition: today the inline chain throws a TypeError');

        $this->expectException(\TypeError::class);
        $this->adapterVerdict($context);
    }
}
