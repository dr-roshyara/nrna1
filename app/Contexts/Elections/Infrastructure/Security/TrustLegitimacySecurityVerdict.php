<?php

namespace App\Contexts\Elections\Infrastructure\Security;

use App\Application\Election\Security\ConstitutionalLegitimacyDecision;
use App\Application\Election\Security\TrustPolicyEvaluator;
use App\Contexts\Elections\Application\DTO\SecurityAssessment;
use App\Contexts\Elections\Application\DTO\SecurityAttemptContext;
use App\Contexts\Elections\Application\Ports\VotingSecurityAssessmentQuery;
use App\Contexts\Elections\Application\Ports\VotingSecurityVerdictQuery;
use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;
use App\Domain\Election\Security\LegitimacyOutcome;
use App\Domain\Election\Security\TrustEvidencePrivacyPolicy;
use App\Models\Code;
use App\Models\Election;
use App\Models\User;

/**
 * TrustLegitimacySecurityVerdict — the one Security Verdict Authority (PBDIGIT-239 Stage A; full assessment
 * PBDIGIT-240). It is now the ONLY place the Trust → Legitimacy chain is evaluated on the voting path: the
 * code step (forEntry) and the final cast (VoteController::store) both consume it.
 *
 * INVARIANT: for every characterized valid SecurityAttemptContext, this adapter produces exactly the same
 * outcome and SecurityVerdict as the former Phase-4 inline security assembly in VoteController::store() (frozen
 * as the oracle in VotingSecurityVerdictAdapterTest), with no evidence, policy, threshold, tenant or exception
 * semantics added or removed.
 *
 * The only transformations of that assembly: request()->ip() → $context->clientIp; request input
 * 'device_fingerprint' → $context->deviceFingerprint; the session id → $context->sessionId; the authenticated
 * User → the User identified by $context->userId; the request's Election → the Election identified by
 * $context->electionId (without global scopes).
 *
 * Tenant: the density count is tenant-scoped (BelongsToTenant: TenantContext ?? session('current_organisation_id')),
 * exactly as inline. This adapter establishes no tenant — callers' tenant context applies (transitional dependency,
 * decision (a); on the voting path the session tenant is carried over from earlier steps).
 * Known defect kept: a non-empty ip_whitelist makes the evaluator throw — not fixed here.
 */
final class TrustLegitimacySecurityVerdict implements VotingSecurityVerdictQuery, VotingSecurityAssessmentQuery
{
    public function __construct(
        private readonly TrustPolicyEvaluator $trustEvaluator,
        private readonly ConstitutionalLegitimacyDecision $legitimacyDecision,
    ) {}

    /** Derived from the one assessment — no separate evaluation (PBDIGIT-240, D-240-3). */
    public function verdictFor(SecurityAttemptContext $context): SecurityVerdict
    {
        return $this->assess($context)->verdict;
    }

    /** The one evaluation of the Trust → Legitimacy chain for this attempt (PBDIGIT-240, D-240-1). */
    public function assess(SecurityAttemptContext $context): SecurityAssessment
    {
        $election = Election::withoutGlobalScopes()->findOrFail($context->electionId);
        $auth_user = User::findOrFail($context->userId);

        // Registered IP from the user account (users.voting_ip has no column → null, PBDIGIT-234).
        $registeredIpHash = null;
        if ($auth_user && $auth_user->voting_ip) {
            $privacyPolicy = app(TrustEvidencePrivacyPolicy::class);
            $registeredIpHash = $privacyPolicy->hashIp($auth_user->voting_ip, $election->id);
        }

        // Participation from the current IP in this election (election-scoped, tenant-scoped density).
        $currentIp = $context->clientIp;
        $votesFromThisIp = Code::where('election_id', $election->id)
            ->where('client_ip', $currentIp)
            ->where('has_voted', true)
            ->count();

        $trustEnvelope = $this->trustEvaluator->evaluate(
            election: $election,
            user: $auth_user,
            rawIp: $context->clientIp,
            rawFingerprint: $context->deviceFingerprint,
            sessionId: $context->sessionId,
            registeredIpHash: $registeredIpHash,
            votesFromThisIp: $votesFromThisIp,
        );

        $outcome = $this->legitimacyDecision->decide($trustEnvelope->result);

        return new SecurityAssessment($this->verdictOf($outcome), $outcome, $trustEnvelope);
    }

    /** Deferred / Investigate are a review requirement — a denial (Q2). Same mapping as securityVerdictOf(). */
    private function verdictOf(LegitimacyOutcome $outcome): SecurityVerdict
    {
        return match ($outcome) {
            LegitimacyOutcome::Allowed => SecurityVerdict::Allowed,
            LegitimacyOutcome::Denied  => SecurityVerdict::Denied,
            LegitimacyOutcome::Deferred,
            LegitimacyOutcome::Investigate => SecurityVerdict::ReviewRequired,
        };
    }
}
