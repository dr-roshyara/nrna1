# Round 46-01 — Domain Knowledge Stability Assessment + DDD Admissibility Matrix

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Round 46 · **Under MB-39.1 (frozen)**
**Status:** 🧾 KNOWLEDGE CONTRACT (curation, not design). **Strategic DDD GATED.** Defines what DDD may consume.
**Date:** 2026-06-26

> **Method.** Classify every governance concept by **stability**, then assign each a **DDD admissibility status** by tracing it through Research → Theory → Ontology (R43) → Projection (R44) → Ownership (R45). The result is a **contract**: Strategic DDD may consume **only** admitted concepts.

---

## 1. Domain Knowledge Stability Assessment

### Stable (frozen enough to enter DDD)
| Concept | Identity (R44) | Owner (R45) |
|---------|----------------|-------------|
| **Evidence** | immutable Aggregate | Record-Keeping |
| **Finality** | Event + state | Adjudication |
| **Review / CaseDecision** | Aggregate + Process | Adjudication |
| **Appeal** | Process | Contestation |
| **Audit** | Service | Record-Keeping |
| **Authority-Delegation (Mandate)** | Value Object | Appointment |
| **Trust-Anchor** | External boundary | external |
| **Legitimacy** | Derived read model (single projection) | nobody (computed) |
| **Anonymity** | Architectural invariant (supreme) | everybody |

### Stable-with-Constraints
| Concept | Constraint |
|---------|-----------|
| **Independence** | use **only** as the 4 facets (Institutional · Operational · Decisional · Perceived) — **never as one concept**; federate ownership |
| **Consent** | **NRNA scope only** (systems lacking an external sovereign); external origin — software records, never owns |

### Experimental (held OUTSIDE DDD for now)
Contestability · Transparency · Accountability · Resilience. *(context-dependent or derived; admissible only after Strategic DDD confirms them or a later round stabilizes them.)*

### Research Backlog (open RQs — BLOCKED from DDD)
Eligibility/Franchise (RQ-EL-01) · Coercion-resistance (RQ-COERCE-01) · Voter-identity (RQ-ID-01) · Amendment-governance (RQ-AMEND-01) · Silent legitimacy erosion (RQ-FAIL-01) · Participation collapse (RQ-FAIL-02) · Consent decomposition (O-REV-Q1).

### External Assumptions (scope conditions, not concepts to build)
- Consent-anchor applies **only** to constitutional systems **without an external sovereign**.
- Trust-Anchor taxonomy is **ternary {enforcement, consent, tradition}** — sacral branch under-explored.
- **Equality-of-consent is excluded** (a democratic value assumption of NRNA's class, not a primitive).

---

## 2. DDD Admissibility Matrix (the contract)

Trace: Research → Theory → Ontology → Projection → Ownership → **DDD status.**

| Concept | Ontology | Projection | Owner | **DDD Status** |
|---------|----------|-----------|-------|----------------|
| **Evidence** | Stable primitive | Aggregate | Record-Keeping | **Admissible** |
| **Finality** | Stable primitive | Event+state | Adjudication | **Admissible** |
| **Review / CaseDecision** | mechanism | Aggregate+Process | Adjudication | **Admissible** |
| **Appeal** | mechanism | Process | Contestation | **Admissible** |
| **Audit** | mechanism | Service | Record-Keeping | **Admissible** |
| **Authority-Delegation** | scoped primitive | Value Object (Mandate) | Appointment | **Admissible** |
| **Trust-Anchor** | Stable primitive | external | external | **External-Boundary** |
| **Legitimacy** | Stable (emergent) | computed/none | nobody | **Derived-Read-Model** (single projection) |
| **Anonymity** | constraint | invariant | everybody | **Architectural-Invariant** (supreme) |
| **Independence** | Stable (4 concepts) | spec+policy+metric | spans 4 owners | **Admissible-with-restrictions** (federate by facet) |
| **Consent** | class-relative primitive | event-stream+derived | external+recorder | **Admissible-with-restrictions** (NRNA scope; external origin) |
| **Contestability** | candidate | policy+process | Contestation | **Experimental — hold** |
| **Transparency** | candidate | read-models | cross-cutting | **Experimental — hold** |
| **Accountability** | derived | read-model | shared | **Experimental — hold** |
| **Resilience** | emergent | metric/none | nobody | **Not-a-component** (metric) |
| **Eligibility** | open RQ | — | — | **Blocked** |
| **Coercion-resistance** | open RQ | — | — | **Blocked** |
| **Voter-identity** | open RQ | — | — | **Blocked** |
| **Amendment-governance** | open RQ | — | — | **Blocked** |

**Carried constraints (binding on Strategic DDD — from R45 D10):** (1) federate Independence by facet; (2) Anonymity is supreme; (3) exactly one Legitimacy projection; (4) software does not own enforcement; (5) design from ownership, then reconcile with existing code (no retrofit).

---

## 3. Verdict

**Admissible to Strategic DDD:** the 9 Stable concepts + Independence (restricted) + Consent (scoped). **External boundary:** Trust-Anchor. **Read model only:** Legitimacy. **Supreme invariant:** Anonymity. **Held (Experimental):** Contestability, Transparency, Accountability, Resilience. **Blocked (Research):** Eligibility, Coercion-resistance, Voter-identity, Amendment-governance.

This is the **clean contract** Strategic DDD consumes. Anything not marked Admissible/with-restrictions/External-Boundary/Read-Model/Invariant **may not enter DDD.** No design performed; DDD gated; MB-39.1 unchanged.

---

*Round 46-01 — Domain Knowledge Stability Assessment + DDD Admissibility Matrix — ISSUED (contract; DDD GATED).*
*Stable: Evidence/Finality/Review/Appeal/Audit/Mandate/Trust-Anchor/Legitimacy/Anonymity. Restricted: Independence(4 facets)/Consent(scope). Held: Contestability/Transparency/Accountability/Resilience. Blocked: Eligibility/Coercion/Voter-ID/Amendment. 5 carried constraints. MB-39.1 FROZEN.*
