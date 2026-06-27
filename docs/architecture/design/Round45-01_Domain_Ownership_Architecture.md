# Round 45-01 — Domain Ownership Architecture

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Round 45 · **Under MB-39.1 (frozen)**
**Status:** 🧭 OWNERSHIP ARCHITECTURE (discovery, not design). **Strategic DDD GATED.** No bounded contexts / aggregates / microservices.
**Date:** 2026-06-26

> **Method.** Consolidate Round 44's projection findings into one **semantic-ownership** architecture. The unit of analysis is the **semantic owner** — the function responsible for a concept's *authoritative meaning* — not a software module. "Seams" (D8) are **observations**, not contexts.

The four discovered owning functions (named for reference, **not** as contexts): **Record-Keeping · Adjudication · Contestation · Appointment.** Plus cross-cutting concerns and external/emergent non-owners.

---

## D1 — Semantic Ownership Matrix

| Concept | Semantic owner | Owner type |
|---------|----------------|-----------|
| Evidence | **Record-Keeping** | single |
| Audit | **Record-Keeping** | single |
| Finality | **Adjudication** | single |
| Review / CaseDecision | **Adjudication** | single |
| Appeal | **Contestation** | single |
| Contestability / Standing | **Contestation** | single |
| Authority-Delegation / Mandate | **Appointment** | single |
| Independence — institutional | **Appointment** | single |
| Independence — operational | **Oversight** (cross-cutting) | shared |
| Independence — decisional | **Adjudication** | single |
| Independence — perceived | **Constituent** (external) | external |
| Transparency | **everybody** (disclosure obligation) | cross-cutting |
| Accountability | **shared** (derived) | shared |
| Anonymity | **everybody / the architecture** | cross-cutting (negative) |
| Consent | **Constituent** (external); recorded by an Acceptance fn | external + recorder |
| Trust-Anchor | **Constituent / enforcement** | **outside software** |
| Legitimacy | **nobody** (emergent; single computed projection) | unowned |
| Resilience | **nobody** (emergent) | unowned |

**Finding (OW-01).** Ownership is **not uniform**: four concepts have a **single clear owner**, several are **cross-cutting** (transparency, anonymity), some are **external** (consent, trust-anchor, perceived independence), and two are **unowned/emergent** (legitimacy, resilience). **Independence alone spans four different owners.**

---

## D2 — Authoritative Truth Matrix

| Mode | Concepts |
|------|----------|
| **OWNS authoritative truth (system of record)** | Evidence (Record-Keeping) · Finality events (Adjudication) · Mandate (Appointment) · Consent acts (Acceptance recorder) · Challenge/Case records (Contestation/Adjudication) |
| **CONSUMES truth** | Review ⟵ Evidence; Adjudication ⟵ Evidence+Mandate; Accountability ⟵ Evidence+Finality |
| **DERIVES truth** | Perceived independence; current-Consent (⟵ events); Accountability; Transparency views |
| **OBSERVES (owns nothing authoritative)** | Legitimacy; Resilience |
| **OUTSIDE software** | Trust-Anchor; the constituent's actual will (consent-origin); Tradition |

**Finding (OW-02).** There are exactly **five systems-of-record** (Evidence, Finality, Mandate, Consent-acts, Cases). Everything else **consumes, derives, observes, or lives outside software.** *The systems-of-record are the load-bearing ownership claims; the rest must not duplicate authoritative state.*

---

## D3 — Computational Dependency Graph

```
INPUTS (owned records):  Evidence · Consent-events · Mandate
        │                      │            │
        ▼                      │            ▼
   Review (Adjudication) ◄─────┼──── constrains: Independence(decisional ⟸ operational ⟸ institutional[Mandate])
        │ produces                   │
        ▼                            │
   Finality (event) ──► Accountability (derived)        derive current-Consent ◄── Consent-events
        │                            │                          │
        └────────────► LEGITIMACY (computed, unowned) ◄─────────┘  ◄── Perceived-independence ◄── Transparency ◄── reads Evidence/Finality
                              │
                              ▼
                         RESILIENCE (computed, unowned)

   Anonymity ──constrains──► what Evidence/records may store (negative dependency)
```

**Finding (OW-03).** All computation **flows from three owned inputs (Evidence, Consent-events, Mandate) to two unowned outputs (Legitimacy, Resilience).** **No authoritative state depends on a derived value** — derivations are downstream-only. This is the integrity property a future architecture must preserve.

---

## D4 — Persistence Ownership Matrix

| Persistence class | Concept → owner of the record |
|-------------------|-------------------------------|
| **Immutable / append-only (owned)** | Evidence → Record-Keeping · Finality events → Adjudication · Consent events → Acceptance · Challenge/Case → Contestation/Adjudication · Mandate → Appointment |
| **MUST NEVER persist (as authoritative)** | Legitimacy · Resilience · **voter↔vote linkage (Anonymity — owned by no one, enforced by all)** |
| **Derived / cacheable (non-authoritative)** | Perceived-independence · Accountability · Transparency views · current-Consent |

**Finding (OW-04).** Persistence ownership confirms the Round 44 split and sharpens it: **the never-persist set has no owner by design** — legitimacy/resilience because they are emergent, voter↔vote linkage because anonymity *forbids* an owner. **"No owner" is itself an architectural decision, not an omission.**

---

## D5 — Lifecycle Ownership Matrix

| Concept | Lifecycle controller | Transitions |
|---------|----------------------|-------------|
| Evidence | Record-Keeping | create → (never mutate) — immutable |
| CaseDecision | Adjudication | Potential→Suspected→…→Final→Audited→Closed (R40-03 D5) |
| Consent | Constituent triggers; Acceptance records | Given ⇄ Withdrawn |
| Mandate | Appointment | Granted → Revoked/Expired |
| Challenge | Contestation | Raised → Resolved/Dismissed |
| Legitimacy | **no controller** | continuously recomputed |

**Finding (OW-05).** Only **owned** concepts have a lifecycle controller; **emergent** concepts (legitimacy) have **none** — they are recomputed, never transitioned. **Consent's lifecycle is triggered externally** (the constituent), software only records — a boundary a future architecture cannot internalize.

---

## D6 — Cross-Boundary Interaction Matrix

| Concept | Crosses | Pattern |
|---------|---------|---------|
| **Evidence** | Record-Keeping (write) → Adjudication, Contestation, Accountability (read) | **single-write, shared-read** |
| **Finality** | Adjudication (write) → Accountability, Transparency, Legitimacy (read) | single-write, shared-read |
| **Independence** | Appointment ↔ Oversight ↔ Adjudication ↔ Constituent | **spans 4 owners** ⚠ |
| **Consent** | Constituent (external) ↔ Acceptance ↔ Legitimacy-computation | external→internal |
| **Transparency / Anonymity** | all functions | cross-cutting (in tension) |

**Finding (OW-06).** The dominant cross-boundary pattern is **single-write / shared-read** (Evidence, Finality) — the safest. **Independence is the exception**: it spans four owners with no single writer → the hardest ownership problem in the system.

---

## D7 — Ownership Conflict Analysis

| # | Conflict | Risk | Resolution principle (for DDD to honor) |
|---|----------|------|------------------------------------------|
| **OC-1** | **Independence spans 4 owners** | diffusion — "everyone's = no one's" | federate by facet; no concept should claim "Independence" wholesale |
| **OC-2** | **Enforcement has no owner** (F-REV Enforcer gap) | software falsely claiming enforcement authority | software **must not own enforcement** — it is consent/external |
| **OC-3** | **Legitimacy unowned but everyone reports it** | divergent/competing legitimacy metrics | **exactly one** computed projection; no second source |
| **OC-4** | **Transparency vs Anonymity** (both cross-cutting) | disclosure exposing voter↔vote linkage | **Anonymity is constitutionally supreme** — wins every conflict |
| **OC-5** | **Meta-audit** (who audits the auditor?) | infinite regress of record ownership | terminates at consent/axiom (R41 recursion theory), not at an owner |

**Finding (OW-07).** Five ownership conflicts — and **four resolve by *refusing* ownership** (federate independence; don't own enforcement; single legitimacy projection; anonymity supreme). **The architecture is defined as much by what it refuses to own as by what it owns.**

---

## D8 — Candidate Architectural Seams (OBSERVATIONAL ONLY)

Natural seams implied by ownership — **NOT bounded contexts; Strategic DDD decides:**

| Seam (candidate) | Owns | Kind |
|------------------|------|------|
| **Record-Keeping / Evidence** | Evidence, Audit | owning seam (system of record) |
| **Adjudication** | Review, Finality, decisional-independence | owning seam |
| **Contestation** | Appeal, Standing, Challenges | owning seam |
| **Appointment** | Mandate, institutional-independence | owning seam |
| Transparency | (reads all) | **cross-cutting — not a context** |
| Anonymity | (constrains all) | **cross-cutting invariant — not a context** |
| Legitimacy/Resilience | (computes) | **computation/read-model — not an owning context** |
| Trust-Anchor / Consent-origin | — | **external — outside the software boundary** |

**Finding (OW-08).** **Four owning seams + two cross-cutting concerns + one computation surface + one external boundary.** This is the candidate structure — *evidence for*, not a decision about, bounded contexts. Strategic DDD must confirm or revise it.

---

## D9 — Threats to Ownership Integrity

- **TO-1:** Independence's 4-owner span (OC-1) + its *perceived* facet owned **externally** by the constituent — software cannot control perception.
- **TO-2:** Anonymity must be architecturally **supreme** over transparency (OC-4); if any disclosure path can expose linkage, the constitutional invariant fails.
- **TO-3:** Legitimacy needs **one** authoritative projection (OC-3); multiple consumers computing it independently → divergence.
- **TO-4:** Enforcement unowned (OC-2) — software must not simulate enforcement authority it does not have.
- **TO-5 (retrofit, carried from R44 T-2):** the **existing platform's ownership** (controllers/models — e.g. votes/codes/posts) may **not** match these semantic owners; mapping seams onto current code structure would corrupt the architecture. Strategic DDD must design from ownership, then reconcile — not the reverse.
- **TO-6:** meta-audit regress (OC-5) terminates at consent, not an owner — the system must not invent an internal "final auditor."

---

## D10 — Strategic DDD Readiness Inputs (the reframed gate)

**Reframed gate question:** *not* "can we start DDD?" but **"Has every Stable ontology concept reached a stable computational identity AND a clear semantic owner?"**

| Stable concept | Computational identity (R44) | Semantic owner (R45) | Ready? |
|----------------|------------------------------|----------------------|--------|
| Evidence | Aggregate (immutable) | Record-Keeping | **READY** |
| Finality | Event + state | Adjudication | **READY** |
| Anonymity | Invariant | everybody (supreme) | **READY** (as supreme constraint) |
| Legitimacy | Computed / none | nobody (single projection) | **READY** (as one read model) |
| Trust-Anchor | External | external | **READY** (as external boundary) |
| Consent (scoped) | Event-stream + derived | external + recorder | **READY w/ caveat** (external origin) |
| **Independence (×4)** | spec + policy + read-model (3 natures) | **spans 4 owners** | **CONDITIONAL** — must be federated by facet in DDD |

**Readiness inputs verdict:** the **universal-core Stable concepts have both a stable identity and a clear owner → the gate may open** — **provided** the DDD phase carries these as binding constraints: **(1)** federate Independence by facet (never own it wholesale); **(2)** Anonymity is supreme; **(3)** exactly one Legitimacy projection; **(4)** software does not own enforcement; **(5)** design from ownership, then reconcile with existing code (don't retrofit). **Independence is the one CONDITIONAL concept** — not blocking, but it must be handled facet-by-facet.

---

## D11 — Executive summary

Consolidating Round 44's observations into a semantic-ownership architecture revealed that **the governance system is defined as much by what it refuses to own as by what it owns.** Four concepts have a **single clear owner** (Evidence→Record-Keeping, Finality/Review→Adjudication, Appeal/Standing→Contestation, Mandate→Appointment), forming **five systems-of-record**; everything else **consumes, derives, observes, or lives outside software.** Computation flows cleanly from **three owned inputs (Evidence, Consent-events, Mandate)** to **two unowned emergent outputs (Legitimacy, Resilience)** with **no authoritative state depending on a derived value.** The dominant cross-boundary pattern is the safe **single-write/shared-read** (Evidence, Finality); the lone hard case is **Independence, which spans four owners** and whose *perceived* facet is owned **externally** by the constituent. Five ownership conflicts surfaced — and tellingly **four resolve by refusing ownership**: federate Independence, don't own enforcement, keep a single Legitimacy projection, and make **Anonymity constitutionally supreme** over transparency. The candidate structure is **four owning seams + two cross-cutting concerns + one computation surface + one external boundary** — evidence for, not a decision about, bounded contexts.

Against the reframed gate — *stable computational identity **and** clear owner* — the **universal-core Stable concepts are READY**, with **Independence CONDITIONAL** (must be federated by facet). The gate may open **provided five constraints are carried into Strategic DDD** (federate independence · anonymity supreme · single legitimacy projection · don't own enforcement · design-from-ownership-not-retrofit). Nothing was designed: no contexts, no aggregates; Strategic DDD remains **gated**; MB-39.1 and the Constitution are unchanged.

```
... Semantic Projection (44) ✓ → Domain Ownership Architecture (this) ✓
   → Strategic DDD Readiness Assessment (formal gate, using these inputs)
   → [gate decision] → Strategic DDD (context map from the 4 owning seams)
   (parallel pending: Methodology Governance Review → MB-39.2?)
```

---

*Round 45-01 — Domain Ownership Architecture — ISSUED (discovery, not design; DDD GATED).*
*5 systems-of-record; 3 owned inputs → 2 unowned emergent outputs; single-write/shared-read dominant; Independence spans 4 owners (CONDITIONAL). 5 ownership conflicts, 4 resolved by REFUSING ownership (anonymity supreme · don't own enforcement · single legitimacy projection · federate independence). 4 owning seams + 2 cross-cutting + 1 computation + 1 external = candidate structure (observational). Readiness inputs: universal-core READY, Independence CONDITIONAL, 5 carried constraints. MB-39.1 FROZEN.*
