# PBDIGIT-240 — One security authority on the voting path (I3): design and discovery proposal

**Date:** 2026-10-05 · **Status:** **ACCEPTED (PO 2026-10-05): D-240-1 … D-240-4 as recommended** — RED not yet authorised; no implementation authorised · **Placement:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Story:** `docs/publicdigit/backlog/PBDIGIT-240-votecontroller-security-assessment-consolidation-stage-c.md` · **Repository state:** HEAD `a420a40fd`
**Author:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). This is evidence and a recommendation, not a decision.
**Evidence labels:** [S] read in source at HEAD · [D] committed test · [I] inference.

## 0 · The invariant this proposal serves

> **No voting-path component may independently re-evaluate a security policy that the Security Verdict Authority already evaluates authoritatively.** (PO review 2026-10-05, refining ADR_20261005_0950 I3: "One security authority; no IP / device check outside it.")

PBDIGIT-240 is the **final-cast part** of I3: `VoteController::store()` must consume the one authority's assessment instead of running its own. The other IP checks on the voting path (§5) belong to Q3 / 2B and are **not** in PBDIGIT-240.

## 1 · What exists today [S]

**The authority (PBDIGIT-239 Stage A):**
- Port `VotingSecurityVerdictQuery::verdictFor(SecurityAttemptContext): SecurityVerdict`.
- Adapter `TrustLegitimacySecurityVerdict`. It assembles evidence (registered-IP hash, tenant-scoped density), then calls `TrustPolicyEvaluator::evaluate()` → `ConstitutionalLegitimacyDecision::decide()` → maps the four-valued `LegitimacyOutcome` to the three-valued `SecurityVerdict`.
- Consumed by `forEntry()` at the code step.

**The final cast (`VoteController::store()`, ≈1490–1616):**
- Runs a **line-for-line copy of the same chain inline** (≈1492–1524). That is the second evaluation path I3 forbids.
- Then uses more than the verdict:

| Use in `store()` | Needs |
|---|---|
| Audit log "Trust evaluation completed" (≈1527) | trust state (`envelope->result->evaluationState`), four-valued outcome |
| D.0.3a gate (≈1540–1574): three distinct user messages for Denied / Deferred / Investigate; warning log with trust state, reason, policy sequence | four-valued `LegitimacyOutcome`; `envelope->result->reason`, `->policyOutcomeSequence` |
| `trackSovereigntyDivergence()` at the gate, at the lifecycle denial (≈1581) and at the membership denial (≈1606) | four-valued outcome + `TrustEvaluationEnvelope` (snapshot hash) |
| `forCast()` input (`securityVerdictOf()`, ≈2022) | `SecurityVerdict` only |

**Side effect of each evaluation [S]:** `TrustPolicyEvaluator::evaluate()` fires its event recorder (`$this->eventRecorder->record(...)`). Two evaluations would mean two security events for one attempt.

**Boundary rule [D]:** `ContextBoundaryFitnessTest` F11.1d says controllers must not *derive* `LegitimacyOutcome`. `VoteController` is exempt because it *receives* the outcome "for telemetry".

**Equivalence oracle [D]:** `VotingSecurityVerdictAdapterTest` keeps the adapter equal to the inline path.

**Not covered by any test [S]:** a search of `tests/` found no assertion of the three D.0.3a user messages.

## 2 · Answers to the twelve questions

### Q1 — What exactly is the authoritative security assessment?

One evaluation of the Trust → Legitimacy chain for one `SecurityAttemptContext`. Its result has three parts, all produced inside the authority:

- `verdict: SecurityVerdict` — what authorization consumes (`forEntry()`, `forCast()`).
- `outcome: LegitimacyOutcome` — the four-valued constitutional outcome (Allowed / Denied / Deferred / Investigate).
- `envelope: TrustEvaluationEnvelope` — the evaluation's evidence and provenance (trust state, reason, policy sequence, snapshot).

The verdict is **derived** from the outcome (the existing mapping), never computed separately.

### Q2 — Verdict-only or richer?

**Recommendation: richer (Option B), with the verdict derived from it.**

- **Verdict-only (Option A)** would make `store()` lose the three messages and the trust-state / divergence telemetry. That is a behaviour change, already rejected as (c) by the PO. Or it would force `store()` to evaluate a second time, which is exactly what I3 forbids.
- **The richer assessment** keeps one evaluation and gives each consumer what it needs. `verdictFor()` remains available as `assess(...)->verdict`, so `forEntry()` and `CodeController` need no change.

### Q3 — Where is it created?

**In the existing adapter** (`TrustLegitimacySecurityVerdict`, which may be renamed), behind a new port method `VotingSecurityAssessmentQuery::assess(SecurityAttemptContext): SecurityAssessment`. The existing `verdictFor()` becomes a thin derivation over `assess()`.

**No new engine:** evidence assembly, evaluator, resolver, mapping and tenant semantics move unchanged (the same transformations already documented in the adapter).

### Q4 — Where is it consumed by `store()`?

**Exactly once**, at the position where the inline block runs today (after context resolution, inside the cast transaction, before the D.0.3a gate):

```
$assessment = app(VotingSecurityAssessmentQuery::class)->assess(new SecurityAttemptContext(
    userId, electionId, request()->ip(), $request->input('device_fingerprint'), session id));
```

Then:
- the audit log reads `$assessment->outcome` and `$assessment->envelope`;
- the D.0.3a gate switches on `$assessment->outcome` (the same three messages);
- `trackSovereigntyDivergence()` receives `$assessment->outcome` and `$assessment->envelope`;
- `forCast()` receives `$assessment->verdict`. That replaces `securityVerdictOf()`, which `store()` then no longer needs.

`store()` keeps **no** reference to `TrustPolicyEvaluator`, `ConstitutionalLegitimacyDecision`, `TrustEvidencePrivacyPolicy` or the density query.

### Q5 — What performs the inline IP / security checks today?

There are seven, not five. All real-election voting path [S]:

| # | Where | What it evaluates | Settings / data read | Same rule as the authority? |
|---|---|---|---|---|
| 1 | `VoteController::store()` ≈1492–1524 | full Trust → Legitimacy chain (copy) | `max_votes_per_ip`, `network_binding_strategy`, `ip_whitelist` (via the evaluator); tenant-scoped density | **yes, it is a duplicate** |
| 2 | `store()` ≈1736 `validateVotingIpWithResponse()` | `users.voting_ip` ≠ request IP | `users.voting_ip` (column missing → always passes; PBDIGIT-234); fails closed since PBDIGIT-243 | no — a separate rule |
| 3 | `CodeController::verifyCode()` ≈932 | voted codes from this IP ≥ limit | `config('app.max_use_clientIP') ?? 7`, count **without** global scopes | no — different threshold and scope |
| 4 | `VoteController::verify()` (step 4) → `vote_post_check()` ≈3239 | voted codes from this IP ≥ limit | `config('app.max_use_clientIP')`, count without global scopes; **real elections only** (demo skips) | no — different threshold and scope |
| 5 | Route middleware `validate.voting.ip` (`v/{vslug}` group, `ValidateVotingIp`) | `users.voting_ip` binding | `config('voting_security.control_ip_address')`, `users.voting_ip` (missing → passes) | no |
| 6 | `ElectionVotingController::show()` / `start()` → `resolveIpBlock()` | whitelist, else per-election limit, else global limit | `ip_whitelist`, **`ip_restriction_enabled` / `ip_restriction_max_per_ip`**, `config('app.max_use_clientIP')`; counts `voter_slugs.step_1_ip` (recorded earlier as never written — VE-15) | no |
| 7 | `voter_verification_mode` | — | **no runtime reader**: `requiresVoterVerification()`, `checksIp()`, `checksFingerprint()` have no callers; the value is only passed to a page | — |

**Documentation defect found [S]:** the completion matrix §I3 lists `vote_post_check()` as "demo only". That is true at `store()`, but `verify()` (step 4) calls it on real elections. **Correction proposed** in the matrix (docs only), not made here.

### Q6 — What is migrated, retired or retained?

| # | In PBDIGIT-240 | Fate |
|---|---|---|
| 1 inline chain in `store()` | **migrated** | `store()` consumes `assess()`; the inline copy is removed |
| 2 `validateVotingIpWithResponse()` | retained | decided with PBDIGIT-234 / Q3 (2B) |
| 3 `verifyCode()` IP count | retained | 2B after Q3 (already planned) |
| 4 `vote_post_check()` IP count in `verify()` | retained | 2B after Q3 |
| 5 `validate.voting.ip` middleware | retained | Q6 / 2B |
| 6 `resolveIpBlock()` | retained | 2B after Q3 |
| 7 `voter_verification_mode` | untouched | Q3: is it policy at all? |

So after PBDIGIT-240, I3 holds **for the authority's own rule** (one evaluation per attempt, one implementation). It does **not** yet hold for the voting path as a whole: #2–#6 remain until 2B.

### Q7 — Who owns telemetry?

**Recommendation:**
- **The security context owns the evaluation's facts.** `TrustEvaluationEnvelope` and `LegitimacyOutcome` stay where they are (`App\Domain\Election\Security`), produced only by the evaluator and the resolver. The security event stays with the evaluator's recorder.
- **The Elections port carries them read-only.** `SecurityAssessment` is a DTO of the Elections application layer that *holds* the three parts. It defines no security type of its own.
- **The consumer owns its observation records.** The cast's audit line and the sovereignty-divergence records stay in `store()`, because divergence compares the security outcome with **`store()`'s own** lifecycle and membership outcomes, which the authority does not know.

**Alternative considered: move divergence telemetry into the adapter.** Rejected. The adapter would need the legacy lifecycle and membership outcomes, so it would take on authorization facts and become more than a security authority.

**Decision needed (PBDIGIT-240's "ownership first"):** accept this ownership split, or choose another.

### Q8 — Can telemetry observe without becoming a second authority?

Yes, under three rules that the tests in Q10 enforce:

1. Telemetry only **reads** `$assessment` (outcome, envelope). It never calls the evaluator or the resolver.
2. Telemetry never **decides**. No refusal depends on telemetry; the D.0.3a gate decides on the received outcome, as F11.1d already allows for `VoteController`.
3. Divergence records carry voter-side facts only. No ballot-side identifier (I8), as today: the record context holds `election_id`, `user_id`, `route` [S].

### Q9 — `verifyCode()`, middleware, `vote_post_check()` and other legacy paths?

Unchanged by PBDIGIT-240 (see Q6). They are the substance of **2B after Q3**. PBDIGIT-240 neither widens nor narrows them.

**Q3 input (facts, not a decision):**
- These paths apply **different thresholds and scopes** from the authority. For example, `verifyCode()` blocks at ≥ `max_use_clientIP` (7) counted across tenants. The authority denies above `max(1, max_votes_per_ip / 2)` (default 3) with a tenant-scoped count.
- The same IP history can therefore get different answers at different steps.

### Q10 — What tests prove "one authority, one evaluation"?

**RED (before GREEN), all synthetic data:**

1. **Architecture (property, not class names):** `VoteController` contains no call to `TrustPolicyEvaluator::evaluate`, `ConstitutionalLegitimacyDecision::decide`, or a `client_ip` density query. The same property for every voting-path controller's own code, with today's exceptions (#3, #4, #6) listed explicitly as known 2B debt, so the test pins them and fails if a new one appears.
2. **One evaluation per cast (dynamic):** a recording decorator on `TrustPolicyEvaluator` (container instance) counts `evaluate()` calls during one `slug.vote.store` request. Exactly **1**; today it is 1 from the inline copy, so the test must also assert the call comes **through the port** (a recording port double sees exactly one `assess()`).
3. **Assessment equivalence:** extend the `VotingSecurityVerdictAdapterTest` oracle so that `assess()` gives the same outcome **and** verdict as the inline path, for the characterized contexts (Allowed, Denied over the density limit, strategy `none`, ReviewRequired mapping).
4. **Behaviour preservation at the final cast**, characterized first (they don't exist today):
   - the three D.0.3a messages for Denied / Deferred / Investigate;
   - the audit line fields;
   - one divergence record at each of the three denial sites.

   Then unchanged after GREEN.
5. **Regression:**
   - the existing Phase 4 suites (`CastFailClosedTest`, `CastDecisionEnforcementTest`, `AtomicParticipationConsumptionTest`, `FinalDenialCharacterizationTest`);
   - `FinalCastExitSafetyTest`, `FinalCastBallotInvariantTest`, `ContextBoundaryFitnessTest`;
   - the 97-suite comparison.

### Q11 — Explicitly out of scope for PBDIGIT-240

- Checks #2–#7 and every IP policy decision (Q3, 2B).
- The whitelist defects.
- PBDIGIT-234 (`users.voting_ip`).
- Middleware (Q6).
- `forCast()` redesign and its unreachable security branches (preserved observation).
- CAS, VE-01, I9 / PBDIGIT-244.
- Tenancy of the density count. It is kept tenant-scoped as today; changing it is a separate decision.
- Demo elections: `DemoVoteController`, and the demo branch of `store()`, which runs the chain but skips the gate.
- Anonymity items A1 / A5 / A6 / A8.
- D6, PBDIGIT-242, D4.1.

### Q12 — What remains after PBDIGIT-240, toward Q3?

1. **Q3 — define the security-policy surface.** Facts [S] for the decision:
   - **The authority reads** `max_votes_per_ip`, `network_binding_strategy` and `ip_whitelist`. The first two are snapshotted into `ConstitutionalArticlesSnapshot` at creation and no application path edits them.
   - **The fields PBDIGIT-244 locked** (`ip_restriction_enabled`, `ip_restriction_max_per_ip`) are read only by `resolveIpBlock()` (#6), **not** by the authority.
   - **`voter_verification_mode`** has no runtime reader.
   - So the settings page edits a policy the authority does not apply, while the authority applies settings the page does not show.
2. **2B — consolidate or retire #2–#6** according to Q3, so that I3 holds for the whole voting path.
3. **Q6 — the middleware's role.**
4. **Then re-assess I3 in the completion matrix**, plus the I9 surface after Q3.

## 3 · Proposed sequence (each step separately authorised)

1. **This proposal → PO decisions** D-240-1 … D-240-3 (below) → STOP.
2. **RED:** tests Q10-1 … Q10-4 (characterizations first; no production change) → STOP.
3. **GREEN:**
   - add `assess()` and `SecurityAssessment`;
   - derive `verdictFor()` from `assess()`;
   - `store()` consumes `assess()` once;
   - remove the inline copy and `securityVerdictOf()`;
   - the oracle updated in the same change (drift rule).

   Then regression → STOP.
4. **Matrix update:** I3 for the final cast; also the `vote_post_check()` correction from §Q5, if you approve it.

## 4 · Decisions requested

| ID | Decision | Recommendation |
|---|---|---|
| **D-240-1** | Verdict-only or rich assessment | **Rich assessment**, with the verdict derived from it |
| **D-240-2** | Ownership | Security context owns the envelope, outcome and security event · Elections port carries them read-only in `SecurityAssessment` · `store()` owns its audit and divergence records as an observer |
| **D-240-3** | Port shape | New method `assess()` on a renamed / extended port (`VotingSecurityAssessmentQuery`), `verdictFor()` derived. Alternative: keep two ports, one implementation |
| D-240-4 (docs) | Matrix §I3 correction: `vote_post_check()` runs on real elections at step 4 | Approve the one-line correction |

**Risks:**
- Behaviour drift in user messages and telemetry. Mitigation: characterize them first (Q10-4).
- `ContextBoundaryFitnessTest` F11.1d. The exemption for `VoteController` stays valid ("receives"); GREEN must not add `LegitimacyOutcome::` constructions outside the gate's existing `match`.
- Tenancy. The density count stays tenant-scoped through the port, as today. No change, but still a known transitional dependency.

**Traceability:** ADR_20261005_0950 §3.0, I3 · PBDIGIT-239 (Stage A `0578091f8`, Stage B `d90131361`) · PBDIGIT-240 (deferral `f5d920b80`) · completion matrix §I3 · `ContextBoundaryFitnessTest` F11.1d · PO review 2026-10-05 (twelve questions).
