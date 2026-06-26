# Round 48-01 — Strategic Context Mapping

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Strategic DDD (Phase II) · **Built against:** Certified Release v1.0 / Strategic Domain Landscape v1.0 / Package 1.0.0 / Vocabulary 1.0.0 / Ontology 1.0.0 (SD-7 traceability)
**Status:** 🗺️ CONTEXT MAP — relationships among the **frozen** landscape contexts. Consumes only Landscape v1.0; adds/removes nothing (SD-2). Scored against ADQC (`Round48-00`).
**Date:** 2026-06-26

> **Scope.** Map the **integration relationships** (upstream/downstream + DDD patterns) among the 8 contexts + 2 read models + external boundary + the Anonymity invariant. This is the test of whether the ownership seams translate into coherent context relationships (ADQC Q3/Q5).
> **⚠️ Discovery-discipline note (added per `Round47-OP`):** these contexts are **CANDIDATE** bounded contexts. *Semantic truth ≠ software structure* — whether each seam is truly a separate bounded context (vs subdomain/aggregate/shared) still requires confirmation by cohesion · lifecycle · transactional consistency · integration needs. The map below is a candidate; boundary confirmation is owed (esp. for the Supporting contexts).

## 1. The contexts (from Landscape v1.0)

Owning: **Appointment** · **Authorization** · **Adjudication** · **Contestation** · **Evidence&Replay** · **Audit**. Substrate (F-PROC): **Voting** · **Election Lifecycle Governance**. Read Models: **Results** · **Legitimacy**. External: **Constitutional Trust-Anchor/Consent**. Invariant: **Anonymity**.

## 2. Context map (relationships + DDD patterns)

| Upstream (supplier) | → | Downstream (consumer) | Pattern | Notes |
|---------------------|---|------------------------|---------|-------|
| **Consent / Trust-Anchor** (external) | → | Appointment, Adjudication | **ACL** | external→internal; translate consent/will into certified model (only ACL needed is at the external edge) |
| **Appointment** | → | Authorization | Customer-Supplier (PL: `Mandate`) | Authorization consumes Mandate VO |
| **Authorization** | → | Voting | Conformist (read-only) | Voting checks capability; no write-back |
| **Election Lifecycle Governance** | → | Voting | Conformist (read-only state) | Voting gated by `voting_active` |
| **Voting** | → | **Evidence&Replay** | Published Language (vote/evidence schema), single-write | Voting writes the authoritative anonymous record |
| **Voting** | → | **Results** (read model) | derived projection | reconstructable from Vote data (Projection Test) |
| **Evidence&Replay** | → | Adjudication | Published Language (`EvidenceEnvelope`) | Adjudication consumes immutable evidence |
| **Contestation** | → | Adjudication | Customer-Supplier (request: challenge) | standing (S-5) raises a case; Adjudication supplies the Determination |
| **Adjudication** | → | **Legitimacy** (read model) | derived (single resolver) | `LegitimacyOutcome`; never persisted |
| **Adjudication** | → | Election Lifecycle Governance | Customer-Supplier (binding Determination) | **closes the correction loop** (e.g. invalidate/re-run) |
| **all contexts** | → | **Audit** | fire-and-forget, one-way | observability; no feedback (R29 invariant) |

**Anonymity (invariant, not a relationship):** constrains the `Voting → Evidence&Replay → Results` path — no stored/derivable voter↔vote linkage anywhere along it (ADQC Q7, gating).

## 3. Context-map diagram

```
   Consent / Trust-Anchor (EXTERNAL) ──ACL──► Appointment ──Mandate(PL)──► Authorization
                                   └──ACL──► Adjudication                      │ (read-only)
                                                  ▲                            ▼
   Election Lifecycle Governance ──state(CF)────► │              Voting ◄──────┘
        ▲   ▲                                     │                 │ writes (PL, single-write, ANONYMITY)
        │   └──── binding Determination (C/S) ────┘                 ▼
        │                                              Evidence & Replay (System of Record) ──PL──► Adjudication
   correction loop closes here                                      │                                 │
                                                                     │                                 ▼
   Contestation ──challenge (C/S, S-5 standing)──────────────────────────────────────────► Adjudication ──derive──► Legitimacy (RM)
                                                                     │
                              Voting ──projection──► Results (RM)    └── all ──fire-and-forget──► Audit
```

## 4. Domain distillation (Core / Supporting / Generic)

| Class | Contexts | Rationale |
|-------|----------|-----------|
| **Core Domain** | **Adjudication · Contestation · Evidence&Replay** (+ Anonymity invariant) | the **closed correction loop** + immutable evidence = the trustworthiness *differentiator* (the whole program's reason to exist) |
| **Supporting** | Voting · Election Lifecycle Governance · Authorization · Appointment | necessary, valuable, but not the differentiator (standard election machinery) |
| **Generic** | Audit (observability) · Results (reporting read model) · device-Trust (PKI, GI-2) | commodity capabilities |

**Finding (CM-1):** the **Core Domain is the correction loop** (Adjudication ← Evidence ← Contestation), not Voting. This matches the certified meta-architecture (legitimacy emerges from the closed loop) and tells implementation where to invest the most design care.

## 5. ADQC scorecard (the map as a decision)

| Criterion | Verdict | Note |
|-----------|---------|------|
| Q1 Semantic fidelity | **Pass** | all relationships use Canonical Vocabulary |
| Q2 Ownership consistency | **Pass** | single-write per system-of-record (Voting→Evidence; Adjudication→Determination) |
| Q3 Autonomy | **Pass** | dependencies are read-only/async; only the correction-loop edge is C/S |
| Q4 Cohesion | **Pass** | one decision-ownership per context (R29/R45 confirmed) |
| Q5 Coupling | **Pass (strong)** | mostly Conformist + read-only + fire-and-forget; **ACL only at the external edge** — the Canonical Vocabulary removes the need for internal ACLs |
| Q6 Traceability | **Pass** | built against Release v1.0 / Landscape v1.0 (declared above) |
| Q7 Anonymity (gating) | **Pass** | Voting→Evidence→Results path carries the invariant; no linkage edge exists |
| Q8 Evolutionary stability | **Pass w/ note** | a Minor release wouldn't reshape the map; **GI-1 Eligibility = Breaking** would add an upstream context to Voting/Authorization |
| Q9 Certification compliance (gating) | **Pass** | only admitted concepts mapped; Eligibility/Identity-Trust deferred (not mapped); Legitimacy/Results as Read Models; no Forbidden Transformation |

**No gating failure.** The ownership seams **do** translate into a coherent, low-coupling context map — the key result of Round 48.

**Finding (CM-2):** the Canonical Vocabulary (one shared ubiquitous language) **eliminates most internal Anti-Corruption Layers** — ACL is needed **only** at the external boundary (Consent/Trust-Anchor). That is a direct architectural dividend of the Phase-I certification discipline.

## 6. Carried items / next

- GI-1 (Eligibility) would, if admitted, insert an **Eligibility** context upstream of Voting/Authorization → **Breaking** landscape release (recorded, not actioned).
- GI-2 (device-Trust) sits as a **Generic** supporting concern feeding Voting/Authorization as evidence — distinct from the external Constitutional Trust-Anchor.

**Next: Round 49 Bounded Context Discovery** — define each Core/Supporting context's internal boundary (aggregates candidates), starting with the **Core Domain correction loop** (Adjudication/Evidence/Contestation), scored against ADQC. Then LIT-3 (validate the map vs Strategic DDD literature), then prototype one Core context.

```
47-02 Landscape ✓ → 48-00 ADQC ✓ → 48-01 Context Map (this) ✓
   → 49 Bounded Contexts (Core first) → LIT-3 → prototype one Core context → ...
```

---

*Round 48-01 — Strategic Context Mapping — ISSUED (built against Release v1.0 / Landscape v1.0).*
*8 contexts + 2 read models + external; patterns mostly Conformist/read-only/fire-and-forget; ACL only at the external Consent edge (CM-2). Core Domain = the correction loop Adjudication←Evidence←Contestation (CM-1), not Voting. ADQC: no gating failure (Q7 Anonymity + Q9 compliance Pass). Next: Round 49 Bounded Contexts (Core first).*
