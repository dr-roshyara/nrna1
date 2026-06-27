# Round 46-VOCAB — Canonical Vocabulary Dictionary v1.0

**Program:** NRNA DDD Trustworthiness Research Program · **Under MB-39.1 (frozen)** · **Scope:** NRNA class (voluntary · online · anonymous · no external sovereign)
**Status:** 📕 CANONICAL DICTIONARY — the developer-facing vocabulary every Strategic DDD artifact must use. Seeds the eventual Ubiquitous Language. Frozen at Round 46B.
**Date:** 2026-06-26

> **Purpose.** One authoritative dictionary so the same concept never acquires four class names. Each entry: **Definition · Allowed synonym · Forbidden synonyms · Scope · DDD status.** Built from LIT-2 (terminology) + Ontology v1.0 + Admissibility (46-01) + Forbidden Transformations (46-02).
> **Rule.** Strategic DDD names classes/contexts/events **only** from this dictionary. Overloaded terms **must** be qualified. Forbidden synonyms **must not** appear in code.

---

## Core admitted concepts

| Concept | Definition | Allowed synonym | Forbidden synonyms | DDD status |
|---------|-----------|-----------------|--------------------|------------|
| **Evidence** | The reviewable, immutable record on which determinations are grounded. | (none) | "Proof", "Log" (too generic) | Admissible — Aggregate (System of Record) |
| **Determination** | A constitutionally final decision issued by an authorized review body. | "Ruling" | **CaseDecision · Judgment · Decision · Verdict** | Admissible — Aggregate |
| **Finality** | The property by which a Determination becomes binding and terminates a dispute (anchor: *res judicata*). | (none) | "Closure", "Completion" | Admissible — Event + state |
| **Review** *(MUST qualify)* | Examination of an act for conformity. **Always qualified:** *Constitutional Review* (interpret rules) · *Case Review* (adjudicate a dispute) · *Audit Review* (check evidence). | per qualifier | bare "Review" | Admissible — Process |
| **Appeal** | A mechanism re-opening a Determination before Finality. | (none) | "Challenge" (use Contestability) | Admissible — Process |
| **Audit** | A mechanism that produces/checks Evidence. | (none) | "Inspection" | Admissible — Service |
| **Mandate** | A grant of authority to a body for a scope and term. | "Delegation of Authority" | "Permission", "Role" (RBAC) | Admissible — Value Object |
| **Consent** | Acceptance by the constituent that confers and renews authority. | (none) | "Approval", "Agreement" (too weak) | Admissible-with-restrictions (external origin; NRNA scope) |
| **Anonymity** | The invariant that voter↔vote linkage is impossible (= *ballot secrecy*). | "Ballot secrecy" | "Privacy" (broader/weaker) | Architectural-Invariant (supreme) |
| **Legitimacy** | Emergent property by which governed parties accept outcomes as rightfully binding. | (none) | "Validity", "Authority" | Derived-Read-Model (single projection; never persisted) |
| **Constitutional Trust-Anchor** | The terminus at which governance recursion stops (NRNA = consent). | (none) | **"Trust Anchor"/"Root of Trust"** (PKI — different domain) | External-Boundary (not an Entity) |

## Independence (NEVER unqualified — 4 facets)

| Facet | Definition | Established term | DDD status |
|-------|-----------|------------------|------------|
| **Institutional Independence** | Insulated appointment/source (S-3). | institutional/structural independence | Specification |
| **Operational Independence** | Freedom to act/detect without interference. | administrative independence | Policy |
| **Decisional Independence** | Freedom to rule without influence. | decisional/adjudicative independence | Policy / guard |
| **Perceived Independence** | Independence as the constituent perceives it. | "appearance of independence" | Read-model / metric |

**Forbidden:** bare "Independence" as a class/aggregate. **Dependency rule:** Decisional ⟹ Operational ⟹ Institutional.

## Infrastructure vocabulary (from LIT-2 alignment)

| Concept | Definition | Was called | DDD status |
|---------|-----------|-----------|------------|
| **System of Record** | The authoritative, immutable store of a fact. | "Truth-of-Record" | layer term |
| **Derived View / Read Model** | A computed, non-authoritative projection. | "Truth-of-Computation" | layer term |
| **Ontology-to-Model Transformation** | The governance-ontology → software-semantics mapping. | "Semantic Projection" | method term (shorthand retained) |

## Held / derived (not yet for class names)

| Concept | Status | Note |
|---------|--------|------|
| Contestability | Experimental (held) | qualified; cf. republican theory (RQ-ANCHOR-01) |
| Transparency | Experimental (held) | cross-cutting; coupled with Independence |
| Accountability | Experimental (held) | derived |
| Resilience | Not-a-component | emergent metric only |

## Blocked (FORBIDDEN in DDD until discovered)

| Concept | Status |
|---------|--------|
| **Eligibility / Franchise** | Blocked (RQ-EL-01) — must not appear as a context/aggregate |
| **Coercion-resistance / receipt-freeness** | Blocked (RQ-COERCE-01) |
| **Voter-identity** | Blocked (RQ-ID-01) |
| **Amendment-governance** | Blocked (RQ-AMEND-01) |

*Semantic smuggling (Blocked logic hidden inside an admitted aggregate) is itself forbidden — see 46C Finding TA-3 (semantic review gate).*

---

## Certified Vocabulary Governance (binding at Round 46B) *(was "Vocabulary Freeze")*

Vocabulary is governed against **uncontrolled** evolution — **not** frozen forever (it evolves only via the `Round46-KRG` release lifecycle + compatibility policy). After certification (46B), **no new core governance terminology may be introduced inside Strategic DDD.** Discovering a new term during implementation means:

```
New term proposed during DDD
   → STOP (do not name a class)
   → Governance backlog (new RQ)
   → future Ontology version (v1.x)
   → future Domain Knowledge Package version
   → future certified DDD release
```

This prevents developers from **accidentally restarting governance research during implementation.** The dictionary changes only by a **versioned governance release**, never ad hoc in code.

---

*Round 46-VOCAB — Canonical Vocabulary Dictionary v1.0 — ISSUED (freezes at 46B).*
*One dictionary; Determination canonical (Ruling allowed; CaseDecision/Judgment/Decision forbidden); Review/Authority/Independence always qualified; Constitutional Trust-Anchor disambiguated; SoR / Read-Model / ontology-to-model aligned; Blocked terms forbidden. Vocabulary Freeze binds at 46B. MB-39.1 FROZEN · DDD GATED.*
