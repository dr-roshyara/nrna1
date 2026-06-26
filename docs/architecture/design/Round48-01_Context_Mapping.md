# Round 48-01 — Strategic Context Mapping (v1.1)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Strategic DDD (Phase II) · **Built against:** Certified Release v1.0 / Strategic Domain Landscape v1.0 / Package 1.0.0 / Vocabulary 1.0.0 / Ontology 1.0.0 (SD-7)
**Status:** 🗺️ CONTEXT MAP (v1.1 — revised per architect review). Maps **relationships** among **candidate** contexts. Consumes only Landscape v1.0; adds/removes nothing (SD-2). Scored against ADQC v1.1.
**Date:** 2026-06-26 · *(v1.0→v1.1: fixed Conformist→OHS for consult dependencies; softened CM-2; added domain events, Context Responsibilities, Context Type, Rejected Patterns; arrow legend; provisional emphasis.)*

> **Scope.** Map **integration relationships** (direction + DDD pattern + flow type) among the candidate contexts. Does **not** invent or redesign contexts.
> **⚠️ PROVISIONAL (per `Round47-OP` discovery discipline).** These are **CANDIDATE** contexts; *semantic truth ≠ software structure*. **All relationships below are provisional until Round 49 confirms or MERGES candidate boundaries.** Likely merge candidates to challenge in Round 49: **Appointment↔Authorization** and **Lifecycle↔Voting**. The map must stay valid even if two candidates merge.

## Flow legend
**[CMD]** command (solid) · **[QRY]** query/consult (dashed) · **[EVT]** domain event (dotted). DDD patterns: ACL · OHS (Open Host Service) · PL (Published Language) · C/S (Customer-Supplier) · CF (Conformist). **C/S convention: the *Supplier* owns evolution; the *Consumer* adapts.**

## 1. Context types (explicit)

| Context | Type |
|---------|------|
| Adjudication · Evidence&Replay · Contestation | **Strategic Core** |
| Voting · Election Lifecycle Governance · Authorization · Appointment | **Supporting** (mission-critical) |
| Audit | **Generic / Observability** *(see §6 — likely not a business BC)* |
| Results · Legitimacy | **Projection (Read Model)** |
| Anonymity | **Constraint (invariant)** |
| Constitutional Trust-Anchor / Consent | **External** |

## 2. Context map (relationship · pattern · flow)

| Upstream | Downstream | Pattern | Flow | Notes |
|----------|------------|---------|------|-------|
| **Consent / Trust-Anchor** (external) | Appointment, Adjudication | **ACL** | [QRY] | translate external will into the certified model — the **only** external ACL |
| **Appointment** (Supplier) | Authorization (Consumer) | **C/S** (PL: `Mandate`) | [QRY] | Authorization adapts to Mandate; Appointment owns Mandate evolution |
| **Authorization** | Voting | **OHS** *(was Conformist — corrected)* | [QRY] | Voting **consults** "may this proceed?" — a **policy** dependency, **not** language conformity |
| **Election Lifecycle Governance** | Voting | **OHS** + **PL** (state vocabulary) *(was Conformist — corrected)* | [QRY] | Voting **consults** `voting_active`; consults, does not conform |
| **Voting** | Evidence & Replay | **PL** (single-write) | [EVT] `VoteAccepted → EvidenceRecorded` | anonymous authoritative record |
| **Voting** | Results (read model) | derived projection | [EVT] `VoteAccepted →` tally projection | Projection Test (R25) |
| **Evidence & Replay** (Supplier) | Adjudication (Consumer) | **PL** (`EvidenceEnvelope`) | [EVT] `EvidenceRecorded` | immutable published facts, not an API |
| **Contestation** (Consumer) | Adjudication (Supplier) | **C/S** | [CMD] `ChallengeRaised →` | standing (S-5) raises a case; Adjudication supplies the Determination |
| **Adjudication** | Legitimacy (read model) | derived (single resolver) | [EVT] `DeterminationIssued → LegitimacyEvaluated` | never persisted as authoritative |
| **Adjudication** (Supplier) | Election Lifecycle Governance (Consumer) | **C/S** (binding) | [EVT] `DeterminationIssued → LifecycleTransitioned` | **closes the correction loop** |
| **all contexts** | Audit | observer | [EVT] fire-and-forget | one-way; no feedback |

## 3. Context-map diagram (with flow types)

```
 legend:  ──►[CMD]   ⇠⇢[QRY]   ┄┄►[EVT]

 Consent/Trust-Anchor(EXT) ⇠⇢ACL⇢ Appointment ⇠⇢C/S(Mandate)⇢ Authorization
                          ⇠⇢ACL⇢ Adjudication                     ⇡ [QRY] OHS
                                     ▲                              Voting
 Election Lifecycle Gov ⇠⇢OHS/PL[QRY]⇢ Voting ◄────────────────────┘
        ▲                                │ ┄[EVT VoteAccepted]┄►  Evidence&Replay ┄[EVT EvidenceRecorded]┄► Adjudication
        │ ┄[EVT DeterminationIssued→LifecycleTransitioned]┄────────────────────────────────────────────────────┘ │
        │  (correction loop closes)                                                                               │ ┄[EVT]┄► Legitimacy (RM)
 Contestation ──[CMD ChallengeRaised]──► Adjudication                          Voting ┄[EVT]┄► Results (RM)
 all contexts ┄[EVT]┄► Audit (observer, fire-and-forget)
```

## 4. Domain distillation (Strategic Core / Supporting / Generic)

| Class | Contexts | Rationale |
|-------|----------|-----------|
| **Strategic Core Domain** *(was "Core")* | Adjudication · Contestation · Evidence&Replay (+ Anonymity invariant) | the **closed correction loop** + immutable evidence = the trustworthiness *differentiator* |
| **Supporting** | Voting · Lifecycle · Authorization · Appointment | **mission-critical** but not the differentiator |
| **Generic** | Audit · device-Trust | commodity |
| **Projection** | Results · Legitimacy | derived read models |

**CM-1 (refined):** the **Strategic Core is the correction loop**, not Voting — Voting remains **mission-critical Supporting**. ("Strategic Core" used deliberately; "Core" in Evans's sense is reserved.)

## 5. Context Responsibilities (Owns / Never Owns)

| Context | Owns (authoritative) | Never owns |
|---------|----------------------|------------|
| Evidence & Replay | Evidence · EvidenceEnvelope · ReplaySession · Certification | Determination · vote content · Legitimacy |
| Adjudication | Determination · Finality | Evidence · votes · Legitimacy (derived) · Mandate |
| Contestation | Challenge · Standing | Determination (routes to Adjudication) |
| Appointment | Mandate · institutional independence | capability decisions · Determination |
| Authorization | capability resolution (decisional indep.) | Mandate (consumes) · Determination |
| Voting | Vote/Ballot (anonymous) | voter↔vote linkage · Results (derived) · eligibility *definition* |
| Election Lifecycle Gov | election state · transitions | votes · Determination |
| Audit | observation record | any business decision / authoritative truth |
| Results / Legitimacy | *(nothing authoritative — projections)* | any system-of-record |

## 6. ADQC v1.1 scorecard (the map as a decision)

| Criterion | Verdict | Note |
|-----------|---------|------|
| Q1 Domain Semantic Integrity | PASS | Canonical Vocabulary throughout |
| Q2 Ownership Integrity | PASS | single-write per SoR; many consumers |
| Q3 Autonomy | PASS | dependencies are [QRY]/[EVT]; only correction-loop edge is C/S |
| Q4 Cohesion (business) | PASS | one cohesive responsibility per context |
| Q5 Coupling (incl. temporal) | PASS | event-driven + query — **no temporal coupling** on the write path |
| Q10 Business Invariant Integrity | **PASS** | correction loop is **event-driven** → **no cross-context transaction** for any invariant |
| Q11 Context Boundary Clarity | **MINOR CONCERN** | Appointment↔Authorization and Lifecycle↔Voting boundaries are candidate-merge → Round 49 |
| Q6 Traceability | PASS | built against Release v1.0 / Landscape v1.0 |
| Q7 Anonymity (gating) | PASS | Voting→Evidence→Results path carries no reconstruction edge |
| Q8 Evolutionary Stability | PASS w/ note | GI-1 Eligibility admission = Breaking (adds upstream context) |
| Q9 Certification Compliance (gating) | PASS | only admitted concepts; no Forbidden Transformation |

**No gating FAIL.** One MINOR CONCERN (Q11) → recorded for Round 49 boundary confirmation (not an ADR-triggering FAIL).

## 7. Rejected context-mapping patterns (why not)

| Pattern | Decision | Reason |
|---------|----------|--------|
| **Conformist** (internal) | **Rejected** for Authorization→Voting & Lifecycle→Voting | those are **consult (OHS/QRY)** dependencies; Voting does not adopt another team's model |
| **Shared Kernel** | **Rejected** | would breach context integrity for high-assurance core; the Canonical Vocabulary is a **Published Language**, not shared mutable model/code |
| **Separate Ways** | **Rejected** for connected contexts | the correction loop requires integration; only **Deferred/Blocked** contexts (Eligibility) sit apart until admitted |
| **Additional internal ACLs** | **Not currently needed** | Canonical Vocabulary removes most *translation* need — **but may be introduced later** for versioning / lifecycle / bounded evolution (see CM-2) |

## 8. CM-2 (softened)

**The Canonical Vocabulary *significantly reduces the need for* internal Anti-Corruption Layers** — *not* eliminates. ACL is currently needed only at the external Consent/Trust-Anchor boundary; internal ACLs **may still emerge** from **versioning, lifecycle independence, or bounded evolution** (not vocabulary translation). A dividend of Phase-I certification, stated as an engineering claim, not an absolute.

## 9. Provisional status & next

**All relationships are provisional until Round 49 Bounded Context Confirmation** (ADQC v1.1 + boundary-evidence criteria + confidence levels) confirms or **merges** candidate boundaries. The purpose of Round 49 is to *discover* boundaries from cohesion/lifecycle/transactional-consistency/autonomy — **not** to preserve this candidate decomposition. Specifically challenge: **Appointment↔Authorization**, **Lifecycle↔Voting**, and **Audit** (likely an Observability/Platform context, not a business BC). No code until the strategic model stabilizes.

```
48-00 ADQC v1.1 ✓ → 48-01 Context Map v1.1 (this) ✓
   → Round 49 Bounded Context Confirmation (confirm/merge/reject candidates; confidence-graded)
   → 50 Aggregates → tactical → code
```

---

*Round 48-01 — Strategic Context Mapping v1.1 — ISSUED (provisional; built against Release v1.0 / Landscape v1.0).*
*Corrected: Authorization/Lifecycle→Voting = OHS consult (not Conformist). Added: domain events ([CMD]/[QRY]/[EVT] legend), Context Responsibilities (Owns/Never-Owns), Context Type, Rejected Patterns. Softened CM-2 (reduces≠eliminates ACLs). Strategic Core = correction loop (Voting = mission-critical Supporting). ADQC v1.1: no gating FAIL; Q11 MINOR CONCERN (Appointment/Authorization, Lifecycle/Voting merges → Round 49). Relationships PROVISIONAL until Round 49.*
