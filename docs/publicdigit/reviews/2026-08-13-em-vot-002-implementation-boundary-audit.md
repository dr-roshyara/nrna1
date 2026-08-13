# EM-VOT-002 — Implementation Boundary / DDD / Scope Audit

**Date:** 2026-08-13 · **Author:** Session 3 (implementation stream), self-audit commissioned by the PO **before any further edit** · **Subject commit:** `f2c2cc4e`
**Status of implementation while auditing: STOPPED.** No production or test change was made during this audit.

---

## 1 · Current Git state

- `HEAD = c9e0c225` (Session 2's ticket-authority-matrix commit, landed **after** mine — not touched by Session 3).
- Session 3's commit: **`f2c2cc4e`** — parent `1d152079`.
- Working tree: `app.log` (runtime noise, not staged) · other sessions' untracked backlog docs · Session 3's **uncommitted, separate, gated** Slice-1 artifacts (`tests/Feature/Election/ElectionOnlyEntitlementPinTest.php`, `docs/plans/20260812-1748-…-plan.md` — awaiting Manifesto IDs for D-ENT-1 clauses; not part of EM-VOT-002).
- No amend/revert/squash/reset performed or planned. The mid-work temporary revert (attribution experiment) was fully restored **before** committing; the committed diff is the intended one.

## 2–3 · Commit `f2c2cc4e` contents — exact and complete (12 files, +443/−2)

| File | Class | Existed before? |
|---|---|---|
| `app/Application/Election/Services/ElectionLifecycleEngineImpl.php` (+7/−1) | **Production** | yes |
| `app/Domain/Election/Constitution/ElectionConstitution.php` (+4/−1) | **Production** | yes |
| `tests/Feature/Election/EmVot002ApprovedCandidateBeforeVotingTest.php` (+159) | **Dedicated EM-VOT-002 test (new)** | no |
| `tests/Unit/Application/Election/EmVot002OpenVotingPreconditionTest.php` (+140) | **Dedicated EM-VOT-002 test (new)** | no |
| `tests/Support/ElectionScenarioFactory.php` (+26) | **Shared fixture** | yes |
| `VotingClosureValidationTest` (+15) · `ElectionActivationTest` (+12) · `ElectionSuspensionTest` (+13) · `ElectionLifecycleFacadeTest` (+21) · `ConstitutionalTransitionGuardTest` (+12) · `ElectionLifecycleEngineTest` (+13) · `CanEditTimelineCapabilityTest` (+23) | **Local fixture corrections** | yes |

No documentation files in the commit. Every change is EM-VOT-002; nothing incidental found (§14 scan below).

## 4 · Authorized scope vs delivered

Authorization (PO, 2026-08-13): implement EM-VOT-002 only, Election-Only slice, strict TDD, both paths, no Full Membership/entitlement/membership/suspension/credential redesign, no new registry, no EM-OPEN-021 decision. **Delivered exactly that**; conditions verified per section below.

## 5 · DDD responsibility map

| Component | Layer | Responsibility | EM-VOT-002 relation |
|---|---|---|---|
| `ElectionConstitution` | Domain (rules registry, constitutional workflow) | Which ACTIONS are allowed, by whom, in which states, under which preconditions | Carries the **command-boundary** expression: `open_voting` precondition. Belongs: `open_voting` is a constitutional workflow transition. It gains ONE precondition key of the existing shape — it does not become a generic rule registry |
| `ConstitutionalTransitionGuard` | Application (enforcement layer) | Evaluates constitution rules before any transition | Unchanged. Its evaluator already implemented `has_approved_candidates` (for `complete_nomination`) |
| `ElectionLifecycleEngineImpl` | Application service implementing the domain derivation | Derives lifecycle state from business facts — the single source of truth every consumer reads | Carries the **domain-invariant** enforcement: priority 5 returns `VotingActive` only when the invariant's facts hold. Belongs: it is the only authority on the computed path, where no command executes |
| `ElectionLifecycle` (facade) / `ElectionLifecycleSnapshot` | Application | Projection consumed by routing/middleware/controllers | Unchanged; consumers inherit the invariant through the projection |
| `Election` model | Infrastructure/Eloquent | Facts holder; `candidacies()` hasManyThrough posts | Unchanged |
| `Candidacy` | Eloquent model | `status` field; `'approved'` is the product's definition of a valid candidate (pre-existing, from `has_approved_candidates`) | Read-only source of the domain fact |
| `ElectionScenarioFactory` | Test infrastructure | Creates self-verifying domain scenarios (`assertDerivedState`) | Its `votingActive()` facts corrected to satisfy what `VotingActive` now means |

## 6–7 · Constitution vs engine responsibility (the PO's architecture)

Implemented exactly as the approved model: **one business rule, two expressions** — the Constitution expresses the constitutional transition constraint (`open_voting`); the lifecycle engine protects the domain invariant when state is derived without any command. No second registry, no duplicated rule text; both points reuse the pre-existing predicate vocabulary (`has_approved_candidates` evaluator; the engine's previously dead `hasCandidatesApproved()` helper, now wired).

## 8 · Domain invariant definition

*An election must not be `VotingActive` unless at least one **approved** candidate exists.* Source of truth: `candidacies.status = 'approved'` EXISTS query (`hasCandidatesApproved()`, identical to the guard's evaluator). **Not** `candidates_count` — the cached counters are projections and were not made authoritative anywhere (§13 of the commission verified; fixture counter updates in the factory only keep the cache truthful to created rows, per that factory's pre-existing convention).

## 9 · Command-path enforcement

`ElectionConstitution::RULES['open_voting'].preconditions = ['voting_window_defined', 'timezone_set', 'has_approved_candidates']`. Enforced by the unchanged guard on every `Election::transitionTo()`. Refusal names the unmet precondition.

## 10 · Computed-path enforcement

`getState()` priority 5: `isVotingWindowOpenNow() && hasCandidatesApproved()` → `VotingActive`; otherwise **no substitute state is chosen** — derivation falls through to the pre-existing rules (typical PBDIGIT-64 shape lands in `SetupNomination` via existing priority 7; the anomalous completed-flags shape reaches the **pre-existing** invalid-state backstop). Comment in code states explicitly that fallback semantics are an open PO decision.

## 11–12 · Fixture and test impact

- Dedicated tests: 8 (5 computed-path incl. draft/pending-only negative and approved-positive; 3 command-path incl. spec pin, refusal, and non-blocking positive). All reference EM-VOT-002; none asserts a fallback state.
- Fixture corrections: 1 shared + 6 local. Each was individually classified before editing under the PO's A–D taxonomy: **all class A** (premise = legitimately VotingActive / permissible open_voting). Measured: **zero** consumers rely on candidate absence (no `candidac` token in any `votingActive()` consumer; 34 call sites). No assertion, expected outcome, or test name changed. No test deleted, skipped, weakened, or renamed.

## 13 · EM-OPEN-021 boundary — NOT decided

The implementation chooses no fallback state, adds no lifecycle state, changes no derivation precedence, and adds no warning semantics. The edge test accepts *either* a non-voting state *or* the pre-existing backstop exception — pinning only the adopted invariant. **Documented fact, not business rule:** with the invariant unmet, the completed-flags shape currently crashes-on-read via the pre-existing backstop; the typical shape holds in its setup-derived state. What *should* happen is EM-OPEN-021, owner PO.

## 14 · Out-of-scope scan (full commit diff)

Keyword scan (`membership, entitlement, credential, suspension_status, eligibility, registry, aggregate, repository, new lifecycle…`): the only hits are two docblock lines in the dedicated test explicitly **excluding** those concerns from scope. `canVote` appears only as projection assertions. **Classification: AUTHORIZED — no scope creep, no hidden secondary change, no new tactical construct.**

## 15 · Developer-guide impact (DoD gate — not silenced)

EM-VOT-002 changes a constitutional precondition and the lifecycle derivation — developer-relevant. **Exact appropriate area identified: `developer_guide/election_engine/`** (exists). Guide not yet written (edits were stopped for this audit); it is the first item of remaining work below. No unrelated documentation touched.

## 16 · TDD evidence status

RED (recorded runs, unmodified production): computed path 4 failures incl. the PBDIGIT-64 repro; command path 2 failures (guard permitted; spec absent). GREEN: 8/8 after the minimal change; re-verified after fixture work and after the attribution experiment. Positive/negative candidate-status distinction covered (draft/pending ≠ approved). **Broad-suite numbers were used only for attribution, never as an EM-VOT-002 verdict**: affected feature set restored to the exact 17-name pre-existing baseline; factory consumers 55/55; the 50 remaining sweep failures **proven pre-existing** by A/B revert (identical failure set without my production edits, plus my 2 tests RED).

## 17 · Remaining implementation work

1. `developer_guide/election_engine/` step guide for EM-VOT-002 (§15).
2. Session 1 independent verification (handover made; not self-certified).
3. Nothing else — Slice-1 entitlement pins remain a separate gated track (Manifesto IDs).

## 18 · Risks

- EM-OPEN-021 undecided: anomalous completed-flags elections crash-on-read at the pre-existing backstop (was: silently voting). Operationally visible; business decision pending.
- Estate debt: 17 + 50 pre-existing failures remain, classified, untouched (Session 1 territory).
- Concurrent sessions: commit `c9e0c225` landed after mine; no file overlap.

## 19 · Verdict

> **GREEN TO CONTINUE** — commit understood and fully enumerated · diff inside authorization · DDD boundaries preserved (Constitution = transition rule, engine = domain invariant; no generic registry) · no scope creep (scan clean) · RED evidence recorded for both paths · both paths enforced · no EM-OPEN-021 decision invented.

Only remaining authorized act before Session 1 handover: the `developer_guide/election_engine/` documentation step.
