# Round 39-RGA — Research Governance Architecture (v1.0)

**Program:** NRNA DDD Trustworthiness Research Program
**Workstream:** Round 39 — closing architectural synthesis
**Status:** 📐 DESCRIPTIVE — explains the architecture that governs the research program. **No operational/methodology changes.** MB-39.1 remains LOCKED; the Prediction Register remains LOCKED.
**Date:** 2026-06-25

> **What this document is.** A single architectural description of *how the research program is governed*. It is the bridge between **Research Methodology** and future **Strategic DDD**. It introduces **no new concepts** — it names, locates, and justifies the six layers that already exist.
> **What this document is not.** Not methodology design, not governance discovery, not Strategic DDD. It changes nothing.

---

## 1. Architecture Vision

**Why does the research require governance?** The program's object — the constitutional trustworthiness of an online election system for a founding-stage diaspora organisation — is high-assurance: its conclusions may shape a real constitution. A conclusion is only as trustworthy as the *process* that produced it. An ungoverned process (informal evolution, retrofitted predictions, edited evidence) yields conclusions that cannot be defended six months later. Governance exists so that **every conclusion is attributable to a known, frozen, auditable process.**

**Why is the methodology itself governed?** Through F-PROC the program discovered that *the methodology evolves* (M-01 narrowed; M-07 introduced). An evolving instrument silently changing mid-measurement is the classic threat to replication. So the methodology was promoted from a *practice* to a *governed artifact*: versioned, change-controlled, validated, and capped by a constitution. The result is no longer "a methodology" — it is a **Research Governance Architecture**: three distinct architectural concerns layered deliberately.

> **The three concerns (the key distinction this round surfaced):**
> 1. **Governance Research** — discovering the governance architecture (capabilities, mechanisms, constitutional properties).
> 2. **Methodology Governance** — controlling how that discovery is *done* (Spec, ADRs, validator, baseline).
> 3. **Research Governance** — the *architecture* that holds the second concern together and keeps it independent of the first and third.
>
> This document is the artifact for concern (3).

---

## 2. Architecture Layers

Six layers, most-entrenched at the top. Each is defined by Purpose · Responsibility · Inputs · Outputs · Consumers · Authority.

### L0 — Methodology Constitution (`Round39-MC`)
- **Purpose:** state the principles no ADR may override (MC-01..MC-08).
- **Responsibility:** entrench the invariants and the research-object boundaries.
- **Inputs:** invariants (INV-1..5), boundary contract, two-vocabularies separation.
- **Outputs:** 8 constitutional principles + amendment rule.
- **Consumers:** every layer below; conformance audits.
- **Authority:** sponsor **+** ARB, via a Constitutional Amendment Record (never a routine ADR).

### L1 — Specification (`Round39-01`, v0.9.1)
- **Purpose:** the executable methodology — rules, definitions, invariants, extension points.
- **Responsibility:** be the single source an independent researcher executes from.
- **Inputs:** L0 principles; consolidated P2-00/17/18/19/SYN-01.
- **Outputs:** RFC-2119 rules, ontology, INV-1..5, extension points.
- **Consumers:** ADRs, validator, baseline, families.
- **Authority:** ARB/sponsor, via ADR-M + version bump.

### L2 — ADR Governance (`Round39-D6`, ADR-M-001..012)
- **Purpose:** record *why* each methodology decision was made and when it may change.
- **Responsibility:** ensure no silent change; preserve rejected alternatives; bind every change to evidence + review trigger.
- **Inputs:** evidence (families), validation, integrity findings, sponsor decisions.
- **Outputs:** decision records (status / class / evidence / consequences / triggers).
- **Consumers:** the Specification (amended only via ADR), future reviewers.
- **Authority:** Methodology Governance (sponsor + ARB).

### L3 — Integrity Validator (`Round39-02`)
- **Purpose:** verify the methodology is internally consistent before each execution.
- **Responsibility:** detect contradiction, lifecycle drift, broken traceability, boundary leakage, unverified rules — typed by severity.
- **Inputs:** L0–L2 + family docs.
- **Outputs:** Pass/Warning/Failure findings (only **Critical** blocks execution).
- **Consumers:** the Baseline (assembled only after a clean run); the governance review.
- **Authority:** descriptive/advisory — it *reports*; it does not *decide* (decisions are L2).

### L4 — Methodology Baseline (`Round39-03`, MB-39.1)
- **Purpose:** bundle L0–L3 + the locked register into one immutable, named identifier.
- **Responsibility:** guarantee that an execution runs under exactly one frozen state.
- **Inputs:** Spec v0.9.1, Register v3-locked, ADR-M-001..012, validator(no-Critical), P2-19, P2-SYN-01, P2-00/17, GLOSSARY-01.
- **Outputs:** identifier **MB-39.1** + freeze discipline.
- **Consumers:** family executions (which cite it); the post-execution review.
- **Authority:** Baseline Lock (sponsor + ARB); any change ⇒ MB-39.2, never an edit.

### L5 — Execution (families: F-AUTH ✓, F-PROC ✓, **F-THR next**, F-REV)
- **Purpose:** generate evidence by hostile replication under a frozen baseline.
- **Responsibility:** discover, classify, test locked predictions; **propose** changes, never enact them.
- **Inputs:** MB-39.1; the family's domain.
- **Outputs:** observations, prediction outcomes, Draft ADR proposals, an Execution Report.
- **Consumers:** the Prediction Register (post-unlock), the governance review.
- **Authority:** none over the methodology — execution is a *consumer*, not a *legislator*.

---

## 3. Dependency Rules

**Permitted:** each layer may depend **only downward** on the layer(s) above it (a consumer reads its governing layers). **Forbidden:** no layer may bypass a layer above it, and **no layer may write upward.**

- Execution (L5) **MUST NOT** modify the Baseline, Validator, ADRs, Spec, or Constitution. It reads MB-39.1 and emits proposals.
- The Baseline (L4) **MUST NOT** alter its constituents — it references frozen versions.
- ADRs (L2) **MUST NOT** override the Constitution (L0) — a conflicting ADR is void.
- The Validator (L3) **reports**; it never **decides** (no upward write to L2).
- **Software Translation (future Strategic DDD) MUST NOT** influence any layer — it is gated and strictly downstream of L5's stabilized output.

```
        ┌─────────────────────────────────────────┐
        │  L0  Methodology Constitution            │  amend: Constitutional Amendment Record
        └───────────────▲─────────────────────────┘
                        │ governs (read-down)
        ┌───────────────┴─────────────────────────┐
        │  L1  Specification v0.9.1                │  amend: ADR-M + version bump
        └───────────────▲─────────────────────────┘
                        │
        ┌───────────────┴─────────────────────────┐
        │  L2  ADR Governance (ADR-M-001..012)     │  the ONLY legislator of L1
        └───────────────▲─────────────────────────┘
                        │ informed by
        ┌───────────────┴─────────────────────────┐
        │  L3  Integrity Validator (reports only)  │
        └───────────────▲─────────────────────────┘
                        │ assembled into
        ┌───────────────┴─────────────────────────┐
        │  L4  Baseline MB-39.1 (LOCKED)           │  change ⇒ MB-39.2 (never edit)
        └───────────────▲─────────────────────────┘
                        │ executed by (cites, never writes up)
        ┌───────────────┴─────────────────────────┐
        │  L5  Family Execution (F-THR next)       │ ──► Draft ADR proposals (flow back via L2 review)
        └──────────────────────────────────────────┘

   Upward writes: FORBIDDEN.  Bypass of any layer: FORBIDDEN.
   Proposals from L5 re-enter at L2 ONLY after the execution completes.
```

---

## 4. Governance Responsibilities

| Layer | Governed by | Why |
|-------|-------------|-----|
| Constitution | sponsor **+** ARB (Amendment Record) | principles bind everything; must be hardest to change |
| Specification | ARB/sponsor (ADR-M + bump) | executable rules need controlled, evidenced change |
| ADR Governance | Methodology Governance (sponsor + ARB) | decisions must cite evidence + rejected alternatives + triggers |
| Validator | advisory (reports to the review) | a checker must not also be a decider (separation of duties) |
| Baseline | Baseline Lock authority (sponsor + ARB) | one immutable identifier per execution |
| Execution | family researcher | discovers and proposes — never legislates (MC-05) |

The recurring principle is **separation of duties**: who *discovers* ≠ who *decides* ≠ who *checks* ≠ who *entrenches*. This is what prevents the protocol from adapting to its own evidence mid-experiment.

---

## 5. Research Object Interaction

Three independent domains (MC-07):

- **Governance Domain** — constitutional properties, capabilities, mechanisms, governance architecture.
- **Methodology Domain** — predictions, evidence, ADRs, validation, baselines, replication, maturity.
- **Software Translation Domain** — Strategic DDD, context mapping, aggregates, hexagonal architecture, implementation.

**Controlled interaction (the only permitted flows — boundary contract):**

| From → To | Allowed | Where |
|-----------|---------|-------|
| Governance → Methodology | **Evidence only** | families feed observations |
| Methodology → Governance | **Protocol only** (never alters properties — MC-01/INV-5) | how discovery is done |
| Governance → Software | **Semantically-stable concepts only** (DDD-gated) | future Strategic DDD |
| Software → Governance | **Never** (MC-06) | DDD must not steer discovery |
| Software → Methodology | **Never** | implementation never shapes the process |
| Methodology → Software | **Never** until DDD authorized | gate stays closed |

They are separated because conflating them is the program's dominant integrity risk: letting a desired *implementation* shape what *governance* gets "discovered," or letting *methodological convenience* invent a *governance requirement*. The architecture makes those flows structurally impossible to take silently.

---

## 6. Lifecycle

```
Research Question
   ↓
Discovery            (family execution under a frozen baseline — hostile replication)
   ↓
Methodology Validation   (integrity validator; P2-SYN-01 lifecycle/maturity)
   ↓
Governance Review        (sponsor + ARB assess Draft ADR proposals)
   ↓
Baseline                 (accepted changes ⇒ new MB-x.y; else baseline unchanged)
   ↓
Execution                (next family under that baseline)
   ↓
Review → Version → Repeat
```

The invariant across the loop: **the methodology changes only *between* executions, never *during* one.** Discovery proposes; the review between families decides; the next baseline carries the decision forward.

---

## 7. Architectural Quality Attributes

| Attribute | How the architecture delivers it |
|-----------|----------------------------------|
| **Traceability** | L2 ADRs bind every change to evidence + trigger; L4 names the exact state per execution |
| **Reproducibility** | L4 single immutable identifier (MB-39.1) — an independent researcher re-runs from one token |
| **Versionability** | L1 governed versioning; L4 new identifier on any change (never in-place edit) |
| **Auditability** | L3 typed-severity findings; L2 retained rejected alternatives; append-only evidence (MC-03) |
| **Scientific integrity** | L0 MC-04 (predictions precede observation) + locked register + hostile replication (L5) |
| **Maintainability** | L0–L5 separation lets a layer be understood and changed without re-reading the rest |
| **Extensibility** | L1 extension points + L2 backlog (ADR-M-013) admit growth without redesign |
| **Boundary protection** | L0 MC-06/MC-07 + L3 Validator 4/5 keep the three domains independent |

---

## 8. Failure Modes

| If… | Architectural consequence | Caught/Prevented by |
|-----|---------------------------|---------------------|
| **Constitution bypassed** | a principle (e.g. evidence rewritten) silently violated → all downstream conclusions suspect | conflicting artifact is *void to the extent of conflict* (L0); conformance audit |
| **ADR bypassed** (silent Spec edit) | undocumented evolution → reproducibility lost | "no silent change" rule (L2); Validator 9 (ADR-consistency) |
| **Validator bypassed** | an inconsistent methodology executes → contaminated evidence | freeze-readiness gate (L3/L4); baseline assembled only after a clean run |
| **Baseline changed mid-execution** | evidence no longer attributable to one state → replication broken | Baseline Lock + freeze discipline (L4); change ⇒ MB-39.2 |
| **Prediction rewritten** | hindsight bias → falsification meaningless | Lock Rule (MC-04 / ADR-M-004); Validator 6 (Critical) |
| **DDD leaks upstream** | implementation steers "discovery" → biased governance findings | DDD gate (MC-06); Validator 5 (Software→Governance = Never) |

Each failure mode maps to a specific guard — which is the point of having layers rather than a single document.

---

## 9. Architecture Decision Summary (rationale, not a re-listing of ADRs)

The architecture evolved because **each integrity problem the program hit could only be solved one layer up from where it appeared.** Informal observations were unreliable → a *register* (L2-ish). An editable register reintroduced bias → a *lock* (process rule). Scattered rules drifted → a *consolidated, versioned Spec* (L1). A Spec clause cannot bind clauses above itself → a *constitution* (L0). Verifying consistency by hand was irreproducible → a *validator* (L3). "Which methodology produced this?" had no single answer → a *baseline identifier* (L4). And discoveries threatening to rewrite the process mid-flight → the *propose-not-enact* boundary between L5 and L2.

The through-line is **escalating entrenchment matched to escalating stakes**: the more a rule must resist convenient change, the higher it sits and the harder it is to amend. That is the architectural rationale — not any single ADR, but the *gradient* the ADRs collectively trace.

---

## 10. Transition to F-THR

MB-39.1 is **sufficient** to govern the next hostile replication because every prerequisite a controlled experiment needs is present and frozen:

- a single executable methodology (Spec v0.9.1) under a constitutional ceiling (MC-01..08);
- predictions locked **before** discovery (Register v3 — P-PROFILE / P-CAP / M-07);
- a clean integrity run (no Critical findings);
- one immutable identifier (MB-39.1) so the evidence is attributable;
- a defined path for anything F-THR discovers — **Draft ADR, reviewed after execution** — so the methodology cannot drift during the experiment.

Nothing further is required, and per MC-05 nothing further is *permitted* to change until F-THR completes. **F-THR is cleared to begin under MB-39.1.**

---

*Round 39-RGA — Research Governance Architecture v1.0 — ISSUED (descriptive; no operational change)*
*Six layers (Constitution→Spec→ADR→Validator→Baseline→Execution); downward-only dependencies; three independent research objects; failure modes mapped to guards. **Round 39 CLOSED.** Next: F-THR under MB-39.1. Strategic DDD GATED.*
