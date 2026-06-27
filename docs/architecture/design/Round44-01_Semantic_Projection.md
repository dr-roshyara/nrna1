# Round 44-01 — Semantic Projection (Ontology → Computational Semantics)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Round 44 · **Under MB-39.1 (frozen)**
**Status:** 🔭 PROJECTION REPORT (discovery, not design). **Strategic DDD GATED.** No bounded contexts drawn, no aggregates designed, no ubiquitous language promoted.
**Date:** 2026-06-26

> **Method.** Every Governance Ontology v1.0 concept is **guilty until proven computable.** For each, discover its computational *nature*, persistence, temporality, ownership — and its candidate software projection, **including "none."** Stable concepts firmly; Candidate/Experimental tentatively (flagged).
> **Discipline.** This identifies *what kind of computational thing* a concept is — it does **not** design contexts/aggregates. Ownership clusters (D6) are *observations* feeding a future Strategic DDD, **not** a context map.

---

## D1 — Ontology Projection Matrix

| Ontology concept | Candidate software meaning(s) | Confidence | Evidence | Alternatives |
|------------------|-------------------------------|-----------|----------|--------------|
| **Evidence** | **Aggregate** `EvidenceRecord` + append-only store | High | clear identity/lifecycle; immutable record | external system (if 3rd-party notarized) |
| **Finality** | **Domain Event** `DeterminationFinalized` + decision **state** | High | a point-in-time status change | a flag on a decision aggregate |
| **Review** (mechanism) | **Aggregate** `CaseDecision` + **Process** | High | stateful workflow with identity | domain service if stateless |
| **Appeal** (mechanism) | **Process** + child of `CaseDecision` | Med-High | re-opens a determination | event on CaseDecision |
| **Audit** (mechanism) | **Domain Service** producing Evidence | High | function over records | scheduled process |
| **Consent** | **Event stream** (`ConsentGiven`/`Withdrawn`) + **derived state** | Med (scoped) | dynamic, renewable | read model of acceptance |
| **Authority-Delegation** | **Value Object** `Mandate` + **Policy** | Med | configuration of who-may-what | entity if it has lifecycle |
| **Contestability** | **Policy** `StandingPolicy` + **Process** | Med | rules + challenge instances | capability flag |
| **Transparency** | **Read models / Disclosure Policy** (cross-cutting) | Med | exposes persisted state | API surface; not a component |
| **Accountability** | **Read model + Policy** (consequence attribution) | Med | derived from evidence+finality | none (pure reporting) |
| **Independence** (×4) | **Specification** (institutional) · **Policy/guard** (decisional) · **Read-model/metric** (perceived) | Med-High | structural check + runtime guard + measured perception | mixed; not one artifact |
| **Trust-Anchor** | **External actor / boundary** — *or NONE* | High | it is the *external* terminus (constituent/enforcement) | none |
| **Legitimacy** | **Derived read model / computed projection** — *or NONE* | High | emergent; must not be stored as truth | dashboard metric only |
| **Resilience** | **None / computed metric** | Med | emergent like legitimacy | observability metric |
| **Anonymity** | **Cross-cutting invariant / constraint** — *NOT an artifact* | High | a negative rule (forbids linkage) | enforced policy everywhere |
| **Equality-of-consent** (excluded) | **Config/Policy** (one-party-one-vote) *only in NRNA's class* | Low | scope assumption, not primitive | none |

**Finding (P-01).** **Not every ontology concept becomes a component.** **Trust-Anchor → external/none**, **Legitimacy & Resilience → computed/none**, **Anonymity → an invariant, not an artifact.** Roughly a third of the ontology has **no aggregate** — confirming the user's warning that ontology ≠ DDD model.

---

## D2 — Semantic Stability Matrix (of the *projection*)

| Projection stability | Concepts |
|----------------------|----------|
| **Stable** (projection well-supported) | Evidence→Aggregate · Finality→Event · Anonymity→invariant · Legitimacy→computed/none · Trust-Anchor→external/none |
| **Candidate** (plausible, needs DDD to confirm) | Review→CaseDecision · Consent→event-stream · Authority-Delegation→Mandate VO · Independence→spec/policy/metric |
| **Experimental** (weak/uncertain) | Contestability→policy · Transparency→read-models · Accountability→read-model · Resilience→metric |
| **Rejected** (no stable software identity) | Equality-of-consent (scope assumption, not a domain primitive) |

---

## D3 — Computational Nature (per concept)

| Concept | Nature |
|---------|--------|
| Evidence | **Entity/Aggregate** |
| Finality | **Event + State** |
| Review/Appeal | **Process (+ Aggregate)** |
| Audit | **Service** |
| Consent | **Event-stream + derived State** |
| Authority-Delegation | **Value Object + Policy** |
| Contestability | **Policy + Process** |
| Transparency | **Read-model/Query capability + Policy** |
| Accountability | **Derived metric + Policy** |
| Independence | **Specification + Policy + Derived metric** (3 natures) |
| Trust-Anchor | **External actor** (or none) |
| Legitimacy | **Emergent / continuously-computed** (no entity) |
| Resilience | **Emergent** (no entity) |
| Anonymity | **Constraint / invariant** (no entity) |

**Finding (P-02).** Independence has **three computational natures at once** (its institutional facet is a *specification*, decisional a *runtime policy*, perceived a *measured read-model*) — the four-concept ontology split (§3 of v1.0) is *why* it cannot be a single software object.

---

## D4 — Persistence Analysis (the strongest architectural signal)

| Must PERSIST (authoritative, often immutable) | Must NEVER persist (as authoritative) | Derived (computed, may cache) |
|----------------------------------------------|----------------------------------------|-------------------------------|
| **Evidence** (append-only, immutable) · Finality events · Consent events · Mandates · Challenge/Case records | **Legitimacy** (emergent — storing it makes it stale/false) · **Resilience** · **voter↔vote linkage (Anonymity)** | Perceived independence · Accountability · Transparency views · "current consent" |

**Finding (P-03).** Persistence splits the ontology into a **truth-of-record layer** (Evidence, events — immutable) and a **never-store layer** (Legitimacy, voter↔vote linkage). **Anonymity is the negative of persistence** — the one thing the system is *forbidden* to store. *(This is the single point where the theory meets the existing platform's hard rule: the votes table has no `user_id`. Recorded as observation, not design.)*

---

## D5 — Temporal Analysis

| Temporal class | Concepts |
|----------------|----------|
| **Static** (changes rarely, at config/appointment) | Authority-Delegation (Mandate); Institutional independence |
| **Event-based** (point-in-time) | Finality; individual Consent/Challenge acts |
| **Historical** (append-only past) | Evidence; audit trail |
| **Dynamic** (changes continuously) | Consent (current); Perceived independence |
| **Continuously computed** (never stored) | Legitimacy; Resilience |

**Finding (P-04).** The temporal profile is **event-sourcing-shaped** (events + append-only history + derived current-state + computed projections) — *recorded as an observation about the domain's nature, **not** a prescription.* No architecture is chosen here.

---

## D6 — Ownership Analysis (reveals candidate seams — NOT a context map)

| Concept | Owner | Ownership type |
|---------|-------|----------------|
| Evidence, Audit | the record-keeping function | **single owner** |
| Review, Finality, CaseDecision | the adjudication function | **single owner** |
| Appeal, Contestability, Standing | the contestation function | **single owner** |
| Authority-Delegation, Institutional independence | the appointment function | **single owner** |
| Transparency, Accountability | **everybody** (cross-cutting) | **shared** |
| Anonymity | **everybody** (cross-cutting invariant) | **shared (negative)** |
| Legitimacy, Resilience, Consent (anchor) | **nobody** (emergent / external) | **unowned** |

**Finding (P-05).** Ownership clusters into **four single-owner groups** (record-keeping · adjudication · contestation · appointment) plus **cross-cutting shared** concerns (transparency, anonymity) and **unowned** emergent/external concepts (legitimacy, consent). *These clusters are the **candidate seams** a future Strategic DDD would examine — recorded as observation. **No context map is drawn here** (that is Strategic DDD, gated).*

---

## D7 — Projection verdicts ("guilty until proven computable")

| Verdict | Concepts |
|---------|----------|
| **Proven computable — as a component** | Evidence (aggregate), Review/CaseDecision, Mandate, Challenge |
| **Proven computable — as event/state** | Finality, Consent |
| **Proven computable — as policy/spec/metric** | Independence, Contestability, Accountability, Transparency |
| **Proven NON-component** (constraint) | Anonymity (invariant, enforced everywhere) |
| **Proven NON-component** (emergent/computed) | Legitimacy, Resilience |
| **Proven NON-component** (external) | Trust-Anchor |
| **Acquitted of software identity** (no artifact) | Equality-of-consent (scope assumption) |

---

## D8 — Findings & threats

- **P-06 (key):** the ontology projects into **four single-owner functional clusters + cross-cutting constraints + non-components.** Strategic DDD, when it opens, should start from the **ownership clusters (D6)** and the **persistence split (D4)** — not from the concept list.
- **P-07:** **event-sourcing-shaped** temporality is an *observation*, not a chosen architecture.
- **Threat T-1:** projection is analyst judgment, not validated by implementation — Candidate/Experimental projections must be confirmed *during* Strategic DDD, not assumed.
- **Threat T-2:** the existing platform already embodies some of these (immutable trail, no-`user_id`); risk of *retrofitting* the theory onto current code rather than letting DDD design freshly — flagged.
- **Gate:** nothing here promotes ubiquitous language or designs a context. DDD stays closed.

---

## D9 — Executive summary

Semantic Projection treated every Governance Ontology v1.0 concept as **guilty until proven computable** and discovered that **the ontology does not map one-to-one onto software.** Roughly a third of it has **no component**: **Anonymity** is a cross-cutting **invariant** (the system's one forbidden-to-store rule — the negative of persistence), **Legitimacy** and **Resilience** are **emergent, continuously-computed, never-persisted** properties, and **Trust-Anchor** is **external** (the constituent/enforcement, not a component). The concepts that *are* components cluster cleanly: **Evidence** → immutable aggregate, **Review/Finality** → adjudication aggregate + event, **Consent** → event-stream + derived state, **Authority-Delegation** → a Mandate value object — and **Independence** resists single-object projection because its four facets have **three different computational natures** (specification, runtime policy, measured read-model). The two strongest architectural signals are the **persistence split** (a truth-of-record layer vs a never-store layer, with anonymity as the firewall) and the **ownership analysis**, which reveals **four single-owner functional clusters** (record-keeping, adjudication, contestation, appointment) plus shared cross-cutting concerns — the candidate seams a future Strategic DDD would examine.

Nothing was designed: no bounded contexts, no aggregates, no ubiquitous language; Strategic DDD remains **gated**; MB-39.1 and the Constitution are unchanged. The projection is analyst-level discovery (T-1) and must be confirmed during Strategic DDD, which **may now begin** — starting, per these findings, from ownership clusters and the persistence split rather than from the concept list.

```
... Governance Ontology v1.0 ✓ → Semantic Projection (this) ✓
   → Strategic DDD Readiness Assessment → [gate decision] → Strategic DDD (context map from ownership clusters)
   (parallel, still pending: Methodology Governance Review → MB-39.2?)
```

---

*Round 44-01 — Semantic Projection — ISSUED (discovery, not design; DDD GATED).*
*~1/3 of the ontology has NO component (Anonymity=invariant, Legitimacy/Resilience=emergent, Trust-Anchor=external). Persistence split (truth-of-record vs never-store, anonymity firewall) + 4 single-owner ownership clusters = the candidate seams for future Strategic DDD. Event-sourcing-shaped (observation only). No UL promoted. MB-39.1 FROZEN.*
