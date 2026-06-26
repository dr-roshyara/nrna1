# Round 49-02 — Architecture Conformance Analysis (v1.2) — within Evidence-Based Strategic DDD Discovery

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Strategic DDD (Phase II) · **Built against:** Certified Release v1.0 / Strategic Domain Landscape v1.0
**Status:** 🔬 ARCHITECTURE CONFORMANCE ANALYSIS — the empirical stage of **Evidence-Based Strategic DDD Discovery (EBSD)**. Findings **provisional**; behavioral (L3) verification still required. Feeds Round 49 (BC Evaluation → BDR).
**Date:** 2026-06-26

> **Methodology rename (v1.1→v1.2).** This is no longer "conformance checking." The full method is **Evidence-Based Strategic DDD Discovery**: *certified semantics → candidate contexts → architecture conformance → evidence grading → boundary falsification → boundary confirmation → BDR.* This document is the **architecture-conformance** stage; the **reflexion model is one of four** evidence perspectives within it.

## 0a. Evidence perspectives (4) — a context is classified only when all agree
**(1) Structural** (modules/namespaces/dependencies — reflexion model) · **(2) Behavioral** (runtime/tests/responsibilities) · **(3) Architectural** (ownership/autonomy/lifecycle) · **(4) Strategic-DDD** (`Round48A` T1–T9). This stage supplies **(1)** + partial **(3)**; **(2)** and **(4)** are owed by Round 49.

## 0b. ⭐ Symmetry principle of evidence (completes the discipline)
> **Implementation presence does NOT prove a bounded context. Implementation absence does NOT disprove one.**
- `ReplaySession` existing ≠ "Replay is a BC" (capability ≠ context).
- Contestation being absent ≠ "Contestation is the wrong BC" (it is simply **not yet implemented**).

Both directions are merely **evidence-level signals**, never verdicts. *(Aligns with the falsification stance: code is empirical evidence, not authoritative — in either direction.)*

## 1. Evidence levels (only L3 = real convergence)
**L1** name correspondence · **L2** structural — sub-graded **L2a** organization (namespace/module) < **L2b** dependency (graph) < **L2c** business responsibility · **L3** behavioral (runtime/tests). **Convergence claimed only at L3. Zero rows at L3 yet.**

## 1b. Architecture-correspondence determinants (so "High" isn't subjective)
Overall architecture correspondence = a roll-up of: **decision-ownership · truth-ownership · autonomy · lifecycle · integration · language.** A row is "High" only if most determinants are High *and* not contradicted; until T1–T9 run, the roll-up is labelled **Provisionally** High/Medium/Low.

## 2. Two-dimensional conformance map (provisional)

Dim A **Architecture correspondence** (Provisional) · Dim B **Implementation status** · Evidence level · Conf (**R** read / **G** name-only).

| Element | Arch. corr. (provisional) | Implementation | Level | Conf | Alternative interpretation |
|---------|---------------------------|----------------|-------|------|----------------------------|
| **Anonymity** (invariant) | **High** | Implemented (no `user_id`; hashed envelope) | L2c→L3-partial | R | — (invariant, not a BC) |
| **Evidence** | **Prov. High** | Implemented (immutable hashed envelope) | L2b | R | aggregate within a broader context |
| **Adjudication** | **Prov. High** | Partial (kernel+policy+decision; vocab differs) | L2b | R | subdomain of an Oversight BC |
| **Legitimacy** (RM) | **Prov. High** | Implemented (single-resolver enum) | L2b | R | — (read model) |
| **Replay** | **Prov. Medium** | Implemented (session/cert/validator) | L2b | R | **Application/Infra Capability over Evidence** (evidence needed: independent lifecycle/txn) |
| **Voting** | **Prov. Medium** | Present (`VotingEngine`) | L1/L2a | G | engine is a service, not a BC |
| **Authorization** | **Prov. Medium** | Present (`ElectionCapabilityResolver`) | L1/L2a | G | computes, may not own → merge w/ Appointment |
| **Election Lifecycle** | **Prov. Medium** | Present (`LifecycleEngine`/`TransitionMatrix`) | L1/L2a | G | workflow/service; merge into Voting |
| **Results** (RM) | **Prov. Medium** | Present (`ResultController`) | L1 | G | exposed SQL, not a Read-Model philosophy |
| **Audit** | **Unknown** | Present (`ElectionAuditService`) | L1 | G | **Infrastructure/Platform**, not a domain BC |
| **Appointment** | **Prov. Low** | Scattered (Authority/Delegation + Committee) | L1 | G | **Merge into Authorization** |
| **Contestation** | **Prov. High** (certified) | **Expected Absence** (never implemented) | — | R | module within Adjudication |
| **Trust-Anchor / Consent** | Architecture: **known external boundary** | **Not represented — EXPECTED** (external; correctly not in software) + device-PKI naming divergence | L1 | R/G | — (external; absence ≠ defect) |

**Expected vs unexpected absence:** Contestation and Trust-Anchor/Consent absences are **Expected** (greenfield / external) — *not defects.* An *unexpected* absence (a certified, supposed-to-exist capability missing) would be a finding; neither here is that.

## 3. Headline (defensible)
> **The current implementation exhibits substantial *provisional architectural correspondence* with the certified landscape. Behavioral (L3) verification and `Round48A` T1–T9 remain necessary before any conformance or bounded-context claim.** *(Not "already implemented.")* 4 rows read (R, ≤L2b); rest inferred (G, L1); **none at L3**.

## 4. Boundary confidence (Round-49 roadmap)
Adjudication **High** · Evidence **High** · Contestation **High (expected gap)** · Voting Med-High · Authorization **Med** · Lifecycle **Med** · Replay **Low (Capability?)** · Appointment **Low (merge?)** · Audit **Low (infra?)**.

## 5. What would falsify the landscape?
Replay can't evolve independently → merge into Evidence / app-infra · Appointment never owns independent decisions → merge into Authorization · Audit is only telemetry → Platform/Infra · Lifecycle can't exist independent of vote flow → merge into Voting · "Engines" are God-objects → boundary is in code, needs structural refactor.

## 6. Actions — by layer
**Governance (→ KRG):** GI-1 Eligibility · GI-2 Consent/Trust. **Architecture (Strategic DDD):** evaluate Merge/Capability for Appointment/Lifecycle/Audit/Replay (Round 49); AI-1 consolidate to `app/Contexts/*`. **Implementation (later):** L3 behavioral verification of G/L1 rows; Contestation prototype (post-confirmation); NM-1 vocabulary.

## 7. Current interpretation + reviewer confidence (with reasons)
- **Evidence/Adjudication/Legitimacy/Anonymity** — *current interpretation:* strong provisional correspondence. *Confidence: High — reason: direct code read; immutable/single-resolver models; separate responsibilities.* L3 owed.
- **Voting/Authorization/Lifecycle/Results/Audit** — *current interpretation:* capability present, correspondence inferred. *Confidence: Low — reason: name/location only (L1); responsibility unverified.*
- **Replay** — *current:* possible BC; *alternative:* application/infra capability; *evidence needed:* independent lifecycle/txn boundary.
- **Appointment** — *current:* scattered → likely Merge (defer to Round 49).
- **Contestation** — *current:* expected absence (genuine, expected gap).

## 8. Threats to validity
G/L1 rows unverified at runtime (no L3); multi-home (AI-1); single-analyst, static; only structural + partial architectural perspectives supplied. **Code = empirical evidence, not authoritative — in both directions (§0b).**

## 9. Next — Round 49 produces TWO artifacts
```
49-02 Architecture Conformance Analysis (this; perspectives 1+partial-3) ✓
   → Round 49 BC Evaluation (Round48A T1-T9 + perspectives 2/4; falsify each candidate)
       → Output A: ARCHITECTURE CONFORMANCE REPORT (evidence from all 4 perspectives)
       → Output B: BOUNDARY DECISION REGISTER (Confirmed/Merged/Supporting/Application/Infrastructure/Deferred/Rejected + evidence/confidence/reason)
   → only THEN Round 50 Aggregate Discovery (not before the BDR is complete)
```

---

*Round 49-02 — Architecture Conformance Analysis v1.2 — ISSUED (stage of Evidence-Based Strategic DDD Discovery).*
*Renamed methodology = EBSD; reflexion = 1 of 4 perspectives. SYMMETRY PRINCIPLE: presence ≠ proof, absence ≠ disproof. Evidence levels L1/L2a/L2b/L2c/L3 (only L3 = convergence; none yet). Arch-correspondence decomposed (6 determinants) → "Provisionally High/Med/Low" until T1-T9. Trust-Anchor/Contestation = EXPECTED absence (not defects). +Alternative-interpretation +reviewer-confidence-with-reasons. Round 49 → TWO outputs (Conformance Report + BDR); aggregates only after BDR. Code = evidence not authoritative (both directions).*
