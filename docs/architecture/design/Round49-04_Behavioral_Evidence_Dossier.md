# Round 49-04 — Behavioral Evidence Dossier (L3 evidence collection)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** EBSD — evidence collection · **Built against:** Landscape v1.0
**Status:** 📋 EVIDENCE DOSSIER — **collects behavioral evidence ONLY. No decisions.** Decisions are made later (49-05 EBSD Evaluation → 49-06 BDR). Evidence carries IDs (`EV-NNN`) the BDR will reference.
**Date:** 2026-06-26

> **Separation of activities (per review).** Observation ≠ evaluation — as discovery ≠ certification earlier. This document **observes**; it does **not** decide Confirmed/Merge/etc. Falsification stance: for each candidate ask *"can we **falsify** that this is an independent bounded context?"* (Popper), not "can we find support?"
> **L3 requirement (objective):** a candidate reaches **L3** only when **runtime behavior + tests + transaction boundaries + observed responsibilities all agree.** Otherwise it stays **L2** (read) or **L1** (name). Each entry records the question set: *owns decisions? · owns truth? · transactions? · events? · dependencies? · tests?*

---

## EV — Adjudication  *(read: Kernel, Decision, tests)*
- **EV-001** `ConstitutionalArbitrationKernel.decide(ctx, capability, at) → ConstitutionalGovernanceDecision` — stateless service. [R]
- **EV-002** `ConstitutionalDecision` = `readonly` VO `{winner: JurisdictionNode, legitimacy, reason, evaluatedAt, trace}` → adjudicates **committee/jurisdiction** conflicts (`Geo/Graph`), **NOT** election-determination/contestation. [R]
- **EV-003** Location: `app/Contexts/Membership/Domain/Committee/Constitutional`. Tests: `ConstitutionalPolicyTest`, `ConstitutionalAssertionsTest`. [R]
- *owns decisions?* yes (jurisdiction arbitration) · *owns truth?* produces a decision record, no persistent SoR observed · *transactions?* single decision · *events?* `LegitimacyEvaluated`/`LegitimacyGranted` exist · *tests?* yes.
- **Level L2–L3 · Confidence Medium.** **Falsification note:** the certified *election-determination* Adjudication is **not behaviorally present**; what exists is **committee-jurisdiction arbitration**. *Open:* is certified Adjudication = this (scope mismatch) or **unrealized**?

## EV — Authorization  *(read: ElectionCapabilityResolver)*
- **EV-030** `ElectionCapabilityResolver.evaluate(context) → CapabilityDecision`; documented INVARIANT: **side-effect-free · deterministic · non-I/O · non-temporal** ("violation destroys constitutional recomputability"). [R]
- **EV-031** evaluates sorted policies → `CapabilityDecision` + `CapabilityTrace`. [R]
- *owns decisions?* computes capability decisions (decisional) · *owns truth?* **NO** (pure function, no persistence) · *transactions?* none (pure) · *events?* none observed · *tests?* capability/policy tests exist.
- **Level L2 · Confidence Medium.** **Falsification note:** "Authorization is a truth-owning BC" appears **falsifiable** — it *computes*, owns no truth; behaves as a **domain service** (candidate merge / supporting).

## EV — Appointment  *(globbed; deeper L3 read OWED)*
- **EV-050** `app/Contexts/Governance/Domain/Authority/Policies/DelegationLifecyclePolicy`; `RemoveCommitteeMemberCommand`/`Handler`; `Contexts/Committee/*` read models. [G]
- *owns decisions?* ? (delegation/committee membership) · *owns truth?* ? (committee membership records) · *transactions?* ? · *events?* `CommitteeLifecycleChanged` · *tests?* membership tests exist.
- **Level L1 · Confidence Low.** **Falsification note:** evidence too thin — **L3 read owed** before evaluating the "merge into Authorization" hypothesis.

## EV — Election Lifecycle  *(globbed; deeper L3 read OWED)*
- **EV-060** `ElectionLifecycleEngine`/`Impl`, `ElectionLifecycleState`, `TransitionMatrix`, events (`VotingOpened/Closed`, `ResultsPublished`, …). [G]
- *owns decisions?* state transitions · *owns truth?* election lifecycle state · *transactions?* ? · *events?* many lifecycle events · *tests?* transition-guard tests exist.
- **Level L1–L2 · Confidence Low-Med.** **Falsification note:** engine could be workflow/service vs BC — **L3 read owed** for "merge into Voting".

## EV — Voting  *(globbed; deeper L3 read OWED)*
- **EV-070** `Vote`/`BaseVote` (**no `user_id`** — anonymity), `Domain/Voting/{VotingEngine,VoteAggregator,QuorumRule}`. [G]
- *owns decisions?* vote validity · *owns truth?* **anonymous vote records (SoR)** · *transactions?* per-vote · *events?* `VoteAccepted` (inferred) · *tests?* vote/constitutional voting tests exist.
- **Level L1–L2 · Confidence Med.** **Falsification note:** engine ≠ BC; **L3 read owed**; anonymity invariant strongly present.

## EV — Evidence  *(read: ReplayEvidenceEnvelope)*
- **EV-010** `ReplayEvidenceEnvelope` `readonly`, frozen evidence, deterministic `envelopeHash`, schema-versioned, **hashed** `voterIdentifier`. [R]
- **EV-011** `EvidenceClassification`/`EvidenceSnapshot`/`EvaluationAuditTrail`; tests `ConstitutionalEvidenceSnapshotTest`. [R]
- *owns decisions?* no · *owns truth?* **YES (immutable SoR)** · *transactions?* append-only immutable · *events?* `EvidenceRecorded` (inferred) · *tests?* yes.
- **Level L2–L3 · Confidence High.** **Falsification note:** hard to falsify as a truth-owner — strong system-of-record evidence.

## EV — Replay  *(read: ReplayDeterminismContractTest)*
- **EV-020** `ReplayDeterminismContractTest`: *"same evidence input → same evaluation output, every time"* — a **determinism contract over the evidence-evaluation pipeline**. [R]
- **EV-021** `ReplaySession`, `ReplayCertification`, `GovernanceReplayService`, `ScopeAwareReplayValidator`. [R]
- *owns decisions?* **no** (verifies determinism) · *owns truth?* **no** (operates over Evidence) · *transactions?* none · *events?* `ReplaySessionOpened`/`ReplayCertificationIssued`/`ReplayDivergenceDetected` · *tests?* yes (contract).
- **Level L2–L3 · Confidence Medium.** **Falsification note:** "Replay is a separate BC" appears **falsifiable** — behaves as a **verification capability over Evidence**, not an independent owner.

## EV — Audit  *(read: ElectionAuditService)*
- **EV-040** `ElectionAuditService.log(...)` → JSONL files, rotation (100 MB), 30-day retention, categories; deps `File` facade + `Carbon`. Fire-and-forget. [R]
- **EV-041** stores **IP in full** for operational events (actions, not votes). [R]
- *owns decisions?* **no** · *owns truth?* **no** (observability logs, not SoR) · *transactions?* none · *events?* receives all · *dependencies?* **infrastructure** (filesystem/clock) · *tests?* replay-determinism audit test.
- **Level L2 · Confidence Medium.** **Falsification note:** "Audit is a domain BC" appears **falsifiable** → behaves as **Infrastructure/Platform**. *(Side-note for Anonymity review: full-IP storage in audit logs — operational, not vote-linked — flag for the L3 Anonymity check.)*

## EV — Contestation  *(confirmed absence)*
- **EV-090** Zero files (challenge/appeal/standing/dispute). [R]
- **Level: confirmed-absence (Expected).** **Falsification note:** the gap is real and **expected** (never implemented) — absence ≠ wrong BC (symmetry principle).

---

## Dossier status
| Candidate | Level | Confidence | Deeper L3 read owed? |
|-----------|-------|-----------|----------------------|
| Adjudication | L2–L3 | Medium | scope question open |
| Authorization | L2 | Medium | — |
| Appointment | **L1** | Low | **yes** |
| Lifecycle | L1–L2 | Low-Med | **yes** |
| Voting | L1–L2 | Medium | **yes** |
| Evidence | L2–L3 | High | — |
| Replay | L2–L3 | Medium | — |
| Audit | L2 | Medium | — |
| Contestation | confirmed-absence | High | n/a |

**No decisions recorded here.** Three candidates (Appointment, Lifecycle, Voting) still owe a deeper L3 read before the EBSD evaluation. **Next:** complete those reads, then **49-05 EBSD Evaluation** (Hypothesis→Evidence→Analysis→Decision) → **49-06 Boundary Decision Register** (Decision · Reason · Evidence-reference only).

---

*Round 49-04 — Behavioral Evidence Dossier — ISSUED (evidence only; no decisions).*
*Falsification-positive signals already: Adjudication-in-code = committee-jurisdiction arbitration (NOT certified election-determination); Authorization = pure resolver (owns no truth); Audit = fire-and-forget infra logger; Replay = determinism capability over Evidence. Strong: Evidence = immutable SoR. Expected-absent: Contestation. EV-IDs assigned. Appointment/Lifecycle/Voting owe deeper L3. Next: finish L3 → 49-05 EBSD Evaluation → 49-06 BDR.*
