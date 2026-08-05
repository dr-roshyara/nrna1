# Artifact Classification — Correction of Two Placements

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Finding:** I derived placement from **scope and domain** without establishing **classification**. Two artifacts were placed wrongly as a result.
**Repository Integrity Gate:** ✅ PASSED. **Two same-day artifacts of my own relocated; no pre-existing document moved; no standard amended.**

---

## 1. The diagnosis is correct

**I wrote:** *"Placement derived — `--scope=product-specific --domain=publicdigit` → `docs/publicdigit`."*

**That derivation was mechanically valid and architecturally incomplete.** The resolver answers *where does a product-specific PublicDigit artifact live* — **it does not ask what kind of artifact it is.** **A document's role determines its home before its scope does**, and I skipped that step on the very principle I had spent the session recording.

## 2. But the taxonomy is not missing a class — canon has it exactly

**The suggested conclusion was that engineering execution artifacts have no classification. They do, and it is precise.**

> **ES-004.2 — Plans** *(Plan Concept Decision Paper, ADOPTED)*:
>
> *"This rule governs **Engineering Plans** — deliberate, approved EP-01 deliverables. Provider plan-mode **Work Plans** are **Runtime artifacts (ES-005.1)** outside this rule's scope; **at EP-01 approval, plan content is promoted into a governed Engineering Plan**."*

> **ES-004.3 — Artifact Roles:** **Runtime** | *"CONTEXT.md · **the active Work Plan**"* | *"must describe **today's execution state**."*
>
> **Work-plan lifecycle:** Draft → Authorized → Executing → Accepted → Closed.

**So the family exists, is named, has a lifecycle, has a role, and has a home.** **Two classes, distinguished by approval:**

| Class | Trigger | Role | Home |
|---|---|---|---|
| **Work Plan** | pre-approval execution state | **Runtime** | **the runtime mount** (ES-005.1) |
| **Engineering Plan** | an approved EP-01 deliverable | Runtime → governed | `docs/plans/`, `YYYYMMDD-HHMM-<what>-plan.md` |

**This is the sixth time this session that a "missing" concept was already on the record.** The pattern holds: **the framework had the answer; I did not consult it.**

## 3. The two corrections

### 3.1 `WP-7C_Engineering_Readiness.md` → `.claude/plans/WP-7C-engineering-readiness.md`

**Classification: Work Plan.** It describes today's execution state for an **unauthorized** slice — there is no approved EP-01 plan for 7C, so it cannot be an Engineering Plan. **Role: Runtime → the runtime mount.**

**`docs/publicdigit/` was wrong for a reason worth stating: a documentation root holds product knowledge, and this artifact holds execution state.** Placing ephemeral execution state in a product documentation root would have made the root's contents mean two different things.

> **A second error, which the classification exposes:** `.claude/plans/WP-7-retention-alignment.md` already exists and already carries WP-7's slice definitions, invariants and status. **`CLAUDE.md`: *"Plans are living documents. Update them continuously rather than creating new ones."***
>
> **The readiness content should be folded into the WP-7 work plan, not held separately — and ES-004.2 already says when: *at EP-01 approval, plan content is promoted into a governed Engineering Plan.*** **The artifact now sits beside the WP-7 plan and is annotated to that effect, rather than competing with it.**

### 3.2 `PublicDigit_Engineering_Protocol.md` → `docs/implementation/PublicDigit_Engineering_Protocol_Proposal.md`

**Classification: proposal paper** — pre-adoption process content, PROPOSED and not adopted. **Precedent in the same folder: `Placement_Rule_Decision_Paper.md`, `Artifact_Ownership_Decision_Paper.md`, `Plan_Concept_Decision_Paper.md`.**

**It is process, not product knowledge**, so a product documentation root was wrong for it too. **On adoption its content moves into the EP section of the Implementation Process — it does not remain here as a second home**, which is what its own pointer discipline requires.

## 4. What the placement model is actually missing — stated narrowly

**The adopted classification is `Scope · Steward · Maturity · Domain`. Document *role* is not among them** — yet **ES-004.3 already defines four roles (Runtime · Historical · Reference · Decision)** and those roles carry placement consequences, as both corrections demonstrate.

> **The narrow claim the evidence supports: the four-property classification and the four artifact roles are two models that both bear on placement, and nothing joins them.** The resolver reads Scope and Domain; **it cannot read Role, so it cannot catch either error made here.**
>
> **This is a real gap, and it is smaller than "the taxonomy lacks a class."** **It is not raised as a request to extend the model** — it is one observation, and the standing rule is that a model is extended on **recurrence**, not on a single instance. **Recorded; the ES-005 amendment package is where it would belong if it recurs.**

## 5. Standing correction to my own practice

> **Derive placement from classification, never from scope alone. Before creating any document: what kind of artifact is this, what role does it carry, and where does that role live? Only then run the resolver.**

**The resolver is not wrong — it answers the question it is asked.** **The error was asking it before establishing what was being placed.**

---

**Traceability:** **ES-004.2** (Plans — Engineering Plan vs Work Plan; promotion at EP-01 approval) · **ES-004.3** (Artifact Roles; work-plan lifecycle) · **ES-005.1** (runtime mount) · `docs/implementation/Plan_Concept_Decision_Paper.md` (the adopted distinction) · `.claude/plans/WP-7-retention-alignment.md` (the living plan this should fold into) · `docs/implementation/Placement_Rule_Decision_Paper.md` · `CLAUDE.md` (*update existing documents rather than creating new ones*). **Two same-day artifacts relocated · no pre-existing document moved · no standard amended · no classification invented.**
