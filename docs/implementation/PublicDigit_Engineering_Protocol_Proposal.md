# PublicDigit Engineering Protocol

**Date:** 2026-08-01 · **Prepared by:** Engineering AI, under Principal Architect discipline
**Status:** **PROPOSED — not adopted.** Adoption is an Engineering-Process act (see §Adoption).
**Classification:** **proposal paper** — pre-adoption process content. Home follows the `Placement_Rule_Decision_Paper` / `Artifact_Ownership_Decision_Paper` precedent: `docs/implementation/`. **On adoption its content moves into the EP section of the Implementation Process; it does not stay here as a second home.**
**Scope:** the default operating model for every PublicDigit work package — WP-7C, WP-8, EPIC-005, EPIC-006 and successors.

> ## This document POINTS. It does not restate.
>
> **Nine of the twelve phases are already canonical.** Writing their rules here would create a second home for them and violate **ES-005.4 (never a copy)** and *rules live once* — the defect this programme has spent the session removing.
>
> **So each phase below carries its rule's home, not its rule's text.** **Three phases have no existing home; they are marked ⭐ NEW and require adoption authority.**

---

## The sequence

```
0  Business Capability          →  1  Strategic DDD      →  2  Tactical DDD
→  3  Architectural Stewardship →  4  Impact Classification ⭐
→  5  Engineering Readiness     →  6  Execution Contract ⭐
→  7  RED  →  8  GREEN  →  9  VERIFY  →  10  ACCEPT  →  11  Operational Evidence ⭐
```

## Phases 0–2 · Business → Strategic → Tactical

**Canonical home:** `.claude/CLAUDE.md` § *Development Discipline — Business → DDD → Architecture → Tests → Implementation (STANDING RULE)* · **EP-03 Engineering Readiness Review** (`docs/implementation/Implementation_Process_v1.1_Draft.md`).

**The standing rule already requires:** business need first · DDD model before architecture (*"resolve ownership of every invariant before protecting it"*) · architecture decision before tests · tests before implementation · and for a discovered gap, `Finding → Architecture Decision → RED → GREEN → Certification`, **never** `Finding → Implementation → Certification`.

**EP-03 already requires** deriving answers from authoritative project knowledge and **asking the human only what cannot be determined confidently.**

**Operating checklist (execution aid, not a rule):**

| Phase | Answer explicitly |
|---|---|
| **0** | business capability · objective · rule being protected · constitutional policy · expected outcome. **If the capability cannot be identified — stop and ask.** |
| **1** | owning bounded context · capability ownership · context-map relationships · Published Language · Ubiquitous Language · strategic invariants. **Does ownership, the context map, or the UL change? Any YES → stop, refer to architecture.** |
| **2** | aggregates · entities · value objects · domain services · application services · repositories · ports · domain events. **Consume or modify only what is already authorized. Invent nothing.** |

## Phase 3 · Architectural Stewardship

**Canonical home: ES-002.1 — Implementation-First Default.** It already states the rule the ARB asked for, and states it as a default rather than an invitation:

> *"Every new ticket is an IMPLEMENTATION ticket by default. The question is 'can I implement this capability within the approved architecture?' — **only a NO, carrying implementation evidence of insufficiency, opens an ADR/ARB discussion.**"*

**Companion: ES-002.2 — Observation Stop During Execution.** *"Unless implementation exposes a genuine deficiency, no further platform observations are recorded during ticket work."*

> **The ARB's formulation — *"does the evidence justify revisiting an accepted decision?"* — is ES-002.1 asked from the other side. Recorded as an equivalent phrasing, not a new rule.** **Do not search for new bounded contexts, aggregates, or abstractions.**

## Phase 4 ⭐ · Architectural Impact Classification — NEW

**No canonical home.** Every finding takes **exactly one primary classification**:

`Strategic Architecture` · `Tactical Architecture` · `Governance` · `Engineering` · `Repository` · `Operational` · `PKS Observation` · `KnowledgeOS Candidate`

**Purpose: classify before recommending.** A finding whose classification is unsettled is not yet actionable — **and misclassification is how governance debt becomes engineering debt.**

**Precedent, not authority:** a six-category version of this taxonomy was used in the Programme State Convergence Package (2026-08-01) and produced a usable result. **One use is not adoption.**

## Phase 5 · Engineering Readiness

**Canonical home: EP-01 Plan First · EP-01-Light · EP-03.** *"Does an approved plan exist? If not: STOP — produce one."*

**Determine:** authorization status · entry criteria · required tests · architectural invariants · dependency rules · verification gates · expected RED scope.

> **If authorization is absent — STOP. Do not begin implementation.** *(EP-01: approval applies to the plan, not merely to the task request.)*

## Phase 6 ⭐ · Execution Contract — NEW as an enumerated trigger list

**The principle is ES-002.1's; the enumeration is new.** Each work package states:

| | |
|---|---|
| **MAY** | the approved implementation changes, named |
| **MUST NOT** | the protected architectural boundaries, named |
| **MUST STOP AND REFER IF it needs** | a new **bounded context** · **aggregate** · **repository** · **port** · **domain term** · **context crossing** · **architectural policy change** |

> **The trigger list is the new part, and its value is that it is enumerable in advance.** A slice that cannot name its MAY and MUST-NOT sets has not completed Phase 5.

## Phases 7–9 · RED → GREEN → VERIFY

**Canonical home:** the standing rule (*tests before implementation; RED first, then minimal GREEN*) · **ER-08** (*reviews record, implementations repair* — behavioural corrections belong to a subsequent approved slice, never to a review) · **ES-002.2** (no opportunistic observation) · the project's verification gates.

| Phase | |
|---|---|
| **7 RED** | failing tests · implementation boundary · expected verification · acceptance criteria. **Do not implement.** |
| **8 GREEN** | the approved scope only. **No opportunistic refactoring, no scope expansion, no redesign.** |
| **9 VERIFY** | tests pass · `composer merge-gate` passes · architectural boundaries preserved · repository health preserved · **developer guide updated (Definition of Done)** |

## Phase 10 · ACCEPT

**Canonical home: EP-02 Completion Review** — *"did we implement the approved plan?"* — plus the standing acceptance rule.

> **Acceptance is a governance act. Engineering never accepts its own work.** **Readiness is evidence; acceptance is authority; analysis cannot ratify itself.**

**EP-02's report format is mandatory and already specified:** changes made · **changes deliberately NOT made** · verification/evidence · commit refs · next recommended action.

## Phase 11 ⭐ · Operational Evidence — NEW as a four-outcome evaluation

**The ladder is canonical (ES-006.1). The evaluation gate at ACCEPT is new.**

| Outcome | Criterion |
|---|---|
| **No reusable knowledge** | **The default — and a result, not a failure.** Most slices land here |
| **PKS Observation** | revealed something about **how engineering knowledge behaves**, as distinct from something about the product |
| **Repeated Operational Evidence** | the **Nth** occurrence of a recorded pattern. **Recurrence is the trigger, not novelty** |
| **KnowledgeOS Candidate** | **cross-product** — adoptable unchanged elsewhere. **One corpus is one observation; promotion needs another repository to produce the same finding** |

> **Promotion is never automatic, and never engineering's act (R-34).** **A cross-product candidate at research maturity resolves to `PENDING` under the placement rule — the unruled classification that has now arisen three times. If it does, record it inside its evidence artifact; do not invent a home.**

---

## Cross-cutting ⭐ · The Model Integrity Rule — NEW, and general

**Extracted from the classification exercise of 2026-08-01, where it disproved a proposal I had already made.** **It is not specific to documentation** — it applies whenever a new **value object, aggregate, classification attribute, engineering capability, or architectural dimension** is proposed.

> **Whenever a new attribute or dimension is proposed, first determine which of three things it actually is:**
>
> | | |
> |---|---|
> | **a new dimension** | genuinely independent of every existing one |
> | **an overloaded existing dimension** | the concept is already present, carried by the wrong field |
> | **another value of an existing dimension** | no model change is required at all |
>
> **Introduce a new dimension only if it passes all three qualities:**
>
> | Quality | Question |
> |---|---|
> | **Orthogonality** | is it genuinely independent of the existing dimensions? |
> | **Necessity** | does its absence explain **every** observed failure? |
> | **Sufficiency** | do the proposed additions **alone** resolve those failures, with no further dimension? |
>
> **All three, or the change does not proceed.**
>
> **And if an existing dimension is found to contain mixed concepts, that is a modelling defect — to be surfaced before, not as part of, architectural evolution.**

**Why it earns a place here rather than in a report:** applied once, it **falsified a proposal that had already been recommended and looked reasonable.** **A discipline that catches its author is worth more than the conclusion it overturned.**

**Placement note, recorded rather than acted on:** this rule is **cross-product methodology at research maturity** — `--scope=cross-product --maturity=research` resolves to **PENDING**, the **fourth arrival** at that unruled cell. **It is therefore recorded inside this proposal, which has a home, rather than given one of its own.**

## Phases 4a–4d ⭐ · Integrity checks — NEW

**Refines Phase 4 from a single classification step into four checks, each answering a different question:**

| | Check | Question |
|---|---|---|
| **4a** | **Capability Integrity** | is the capability being consumed, extended, or invented? **Only the first needs no authority.** |
| **4b** | **Model Integrity** | the rule above — new dimension, overloaded dimension, or new value? |
| **4c** | **Classification Integrity** | is the artifact's **type and role** established *before* its placement is derived? |
| **4d** | **Governance Separation** | which findings are architecture's to conclude, and which are governance's to decide? |

**4c is the check whose absence caused two misplacements on 2026-08-01.** **4d is the check whose absence let a recommendation and an architectural conclusion travel in the same sentence.**

## What is actually new here

| Element | Status |
|---|---|
| Phases 0–3, 5, 7–10 | **Already canonical.** This document points at them and adds an execution checklist |
| **Phase 4** — impact classification taxonomy | ⭐ **NEW** — one prior use, no adoption |
| **Phase 6** — enumerated stop-and-refer triggers | ⭐ **NEW** — principle canonical, enumeration new |
| **Phase 11** — four-outcome evaluation at ACCEPT | ⭐ **NEW** — ladder canonical, gate new |
| **Phases 4a–4d** — the four integrity checks | ⭐ **NEW** — added 2026-08-01 |
| **The Model Integrity Rule** (cross-cutting) | ⭐ **NEW** — general modelling discipline; **cross-product, resolves to PENDING, so recorded here rather than homed separately** |

## Adoption

**This protocol is PROPOSED, not adopted.** Adopting it is an **Engineering-Process act** — its natural home is the EP section of `docs/implementation/Implementation_Process_v1.1_Draft.md`, alongside EP-01/02/03. **It is not an architecture act and not an ARB ruling**, unless the ARB chooses to rule it.

**Two options, and architecture prefers neither:**

| Option | Effect |
|---|---|
| **Adopt the three new elements into the EP section** | rules stay in one home; this document becomes a pointer-only operating aid |
| **Adopt this document as the protocol** | one place to read; **requires the pointer discipline above to be maintained, or it silently becomes a second home** |

**A note on scope that decided this document's own placement:** the ARB scoped the protocol to **PublicDigit**, which made it product-specific and gave it a home. **Framed as cross-product engineering methodology it would have been unqualified cross-product material and resolved to `PENDING`** — the fourth arrival at that unruled cell. **The scoping was what made it placeable.**

---

## Guiding principles

**Recorded as given, because they are the programme's own words and are already visible in its artifacts:**

Business capability before implementation · Strategic DDD before Tactical DDD · Architecture before engineering · Governance before execution · Engineering before optimization · **Evidence before architectural evolution** · Stable architecture, evolving implementation · **PublicDigit remains the primary product** · KnowledgeOS evolves only through validated operational evidence · PKS preserves engineering knowledge · **the strongest claim made must never exceed the available evidence.**

---

**Traceability:** `.claude/CLAUDE.md` § Development Discipline (STANDING RULE) · `docs/implementation/Implementation_Process_v1.1_Draft.md` (EP-01 · EP-01-Light · EP-02 + report format · EP-03 · ER-08 · process gates) · **ES-002.1** (Implementation-First Default — Phase 3's home) · **ES-002.2** (Observation Stop) · **ES-005.4** (never a copy — why this document points) · **ES-006.1** (the promotion ladder) · **R-34** (promotion requires issuance) · `2026-08-01-programme-state-convergence-package.md` (Phase 4's single prior use) · `docs/publicdigit/WP-7C_Engineering_Readiness.md` (Phases 0–6 executed once, for 7C). **No rule restated · no architecture reopened · nothing adopted.**
