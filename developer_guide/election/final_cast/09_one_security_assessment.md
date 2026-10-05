# 09 — The final cast consumes one security assessment (I3, PBDIGIT-240)

**Story:** PBDIGIT-240 · **Design:** `docs/publicdigit/reviews/2026-10-05-pbdigit-240-security-authority-design-proposal.md` (D-240-1 … 4) · **RED:** `c6490376c` · **GREEN:** this step's commit · **Decision:** ADR_20261005_0950 I3

## Purpose

`VoteController::store()` used to run its own copy of the Trust → Legitimacy chain: evidence assembly, density count, `TrustPolicyEvaluator::evaluate()`, `ConstitutionalLegitimacyDecision::decide()`. The same chain also ran in the Security Verdict Authority for the code step, so there were two evaluation paths.

Now the final cast asks the authority **once** and only reads the answer. **I3: one security authority is enforced for the final-cast security assessment.** The remaining IP / security checks (#2–#6 in the proposal) stay outside this story and are deferred to Q3 / 2B.

## The contract

```php
// app/Contexts/Elections/Application/Ports/VotingSecurityAssessmentQuery.php
interface VotingSecurityAssessmentQuery
{
    public function assess(SecurityAttemptContext $context): SecurityAssessment;
}

// app/Contexts/Elections/Application/DTO/SecurityAssessment.php
final readonly class SecurityAssessment
{
    public function __construct(
        public SecurityVerdict $verdict,          // what authorization consumes
        public LegitimacyOutcome $outcome,        // four-valued constitutional outcome
        public TrustEvaluationEnvelope $envelope, // trust state, reason, policy sequence, snapshot
    ) {}
}
```

The adapter `TrustLegitimacySecurityVerdict` implements both ports. `assess()` runs the chain once, unchanged. `verdictFor()` is now just `return $this->assess($context)->verdict;`, so the code step (`forEntry()`) and the final cast share one evaluation path.

## Ownership (D-240-2)

| Thing | Owner |
|---|---|
| `TrustEvaluationEnvelope`, `LegitimacyOutcome`, the security event | the security context (evaluator, resolver, event recorder) |
| `SecurityAssessment` | the Elections port. It carries those facts read-only |
| The cast's audit line and sovereignty-divergence records | `store()`, as an **observer**. Divergence compares the security outcome with `store()`'s own lifecycle and membership results |

## How `store()` uses it

```php
$securityAssessment = app(VotingSecurityAssessmentQuery::class)->assess(new SecurityAttemptContext(
    userId: $auth_user->id,
    electionId: $election->id,
    clientIp: request()->ip(),
    deviceFingerprint: $request->input('device_fingerprint'),
    sessionId: $request->session()->getId(),
));
$trustEnvelope = $securityAssessment->envelope;
$constitutionalOutcome = $securityAssessment->outcome;
```

- **D.0.3a gate:** switches on `$constitutionalOutcome`. The same three messages as before.
- **Audit line and divergence tracking:** read `$constitutionalOutcome` and `$trustEnvelope`.
- **`forCast()`:** gets `security: $securityAssessment->verdict`. `securityVerdictOf()` is gone.
- **Constructor:** `VoteController` no longer injects the evaluator or the resolver.

## Semantic note: the voter is reloaded by id

The adapter loads the voter with `User::findOrFail($context->userId)`. The former inline path used the authenticated `User` object. Checked 2026-10-05 [S]:

- **What the chain reads from the voter:**
  - inside `TrustPolicyEvaluator`, only `$user->id`, for the eligibility evidence; no overlay, policy, assembler or recorder reads another attribute;
  - in the evidence assembly, `voting_ip`.
- **`voting_ip` is not a column** of `users` (PBDIGIT-234), so both objects give `null` in production.
- **No production code sets `voting_ip` in memory.** Its only writers (`BulkApproveVoters`, `ElectionProcessController`) persist through `update()`, so a reload would see them too.
- **`User` has no tenant global scope.** Its only scope is `SoftDeletes`, which the auth guard applies too.
- **The code step** has used the same reload since PBDIGIT-239 Stage B.

The only observed difference is the Exit B test double in `FinalCastExitSafetyTest`, which sets an unsaved in-memory `voting_ip`. That is a test artifact: Exit B is still reached and the test passes.

## Testing

| Suite | What it proves |
|---|---|
| `tests/Feature/Voting/SecurityAssessmentConsolidationTest.php` | P1: no voting controller uses the evaluator or resolver (demo excluded) · P1b: IP-density queries only in the pinned 2B debt (`verifyCode`, `vote_post_check`, `evaluateIpCount`) · P2: one cast → exactly one `evaluate()`, through the authority · C1–C3: messages, audit line and divergence records unchanged · the lifecycle site is unreachable on the slug route (`voting.active` refuses first) |
| `tests/Feature/Voting/VotingSecurityVerdictAdapterTest.php` | E1: `assess()` outcome and verdict equal the **frozen** inline oracle (`inlineOutcome()`), and `verdictFor()` equals `assess()->verdict` |

**Test-only seam:** a recording subclass of `TrustPolicyEvaluator`, bound with `app()->instance()`. It counts calls, captures the call stack, and can force the trust state.

**Validation evidence (2026-10-05):**
- 97-suite regression vs `5cbba6f15`: 579 / 334 / 19 → **583 / 333 / 19**, with no pass → fail.
- The four-test gain: 3 new E1 tests, plus 1 pre-existing failure (`VoteSubmissionRedirectTest::vote_pre_check_allows_submitted_votes`, an `ArgumentCountError` on `new VoteController()`). That one passes only because the constructor dependencies were removed; it is not a newly fixed unrelated defect.
- `ContextBoundaryFitnessTest`: unchanged, 10 passed and 2 incomplete.

## Pitfalls

- **Drift rule:** the oracle's `inlineOutcome()` is the frozen reference of the former assembly. Change it only together with a decided change to the authority.
- **Never evaluate again.** Do not call the evaluator or the resolver from a controller. P1 fails if you do.
- **No new IP-density queries in controllers.** P1b fails on a new one; the known 2B debt is pinned by name.
- **Telemetry reads the assessment; it never decides.** F11.1d allows `VoteController` to *receive* `LegitimacyOutcome`, not to derive it.

## Still open

- Checks #2–#6 (`validateVotingIpWithResponse`, `verifyCode`, `vote_post_check` at step 4, the `validate.voting.ip` middleware, `resolveIpBlock`) → Q3 / 2B.
- `voter_verification_mode` has no runtime reader → Q3.

**Traceability:** ADR_20261005_0950 I3 · PBDIGIT-240 (design `7d948969f`, RED `c6490376c`) · PBDIGIT-239 Stage A / B · completion matrix §I3 · `ContextBoundaryFitnessTest` F11.1d.
