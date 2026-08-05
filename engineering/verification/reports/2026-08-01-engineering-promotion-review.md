# Engineering Rule Promotion Review

**Date:** 2026-08-01 · **Prepared by:** Recording Architect · **Rule under application:** the promotion lifecycle — *Observation → Candidate → Repeated Evidence → Operational Validation → ARB Approval → Engineering Canon*
**Repository Integrity Gate:** ✅ PASSED. **No file in `engineering/` was moved, added or removed by this review.**

---

> ## The first thing the rule catches is something already in `engineering/`
>
> **`engineering/knowledge/methodology/Layer_Verification_Rule.md` was created during this session, in the canonical tree, on the evidence of a single work package.** It has **never** been assessed against these criteria and has **never** been adopted.
>
> **Its placement front-ran its promotion.** The file sits where canon lives; its status line says `PROPOSED — NOT ADOPTED`. **Location implies canon; status denies it.**
>
> **Applying the criteria retroactively is therefore the highest-value use of the rule** — a promotion review whose first act is to review the promotion that already happened.

---

## Candidate 1 — the Layer Verification Rule module *(already in `engineering/`)*

| # | Criterion | Result | Evidence |
|---|---|---|---|
| **1** | **Repeated operational evidence** | ⚠️ **PARTIAL** | **Multiple instances, one context.** G-1 (prospective) · AP-1 and AP-2 (retrospective) · the R-52/R-60 misclassification — **all inside EPIC-004.** The module's own §4 already records this: *"one work package … thinner than the DDD module's evidence base at its promotion, which was itself a recorded exception (R-39)."* **Repetition within a context is not repetition across contexts** |
| **2** | **Domain independence** | ✅ **YES** | No Election, Adjudication or Contestation term appears in any rule statement. `Business Policy → Architectural Invariant → Mechanism → Implementation` is domain-neutral by construction |
| **3** | **Architectural stability** | ❌ **NO** | **Four post-freeze amendments in a single day.** The module records the verdict itself: *"a module amended four times was, on the evidence, frozen before it was finished."* **A rule still changing shape is not yet a stable principle** |
| **4** | **Reusability without modification** | ⚠️ **UNTESTED** | No second team, no second context. **And the operational validation just showed its governance-category section has a scope narrower than its use** (R-63) — evidence *against* unmodified reuse |
| **5** | **Simplicity — does promotion REDUCE complexity?** | ⚠️ **MIXED** | The **layer rule** (§1–2) is one question and reduces judgement to a lookup. The **governance-category taxonomy** (§3) has grown to four categories × two axes + an invariant + a naming-hazard note, and **needed a scope declaration to stay coherent** |

### Verdict — **Outcome B: remain as reusable project knowledge; not yet Engineering canon**

**Two of five criteria fail or are untested, and the failing one is stability** — the criterion least amenable to argument, because it is measured in amendments rather than opinion.

**This is not a criticism of the module's content.** §1–2 (the four-level model and the layer verification rule) look like the strongest candidate the programme has produced: they are domain-neutral, they discriminate — **they rejected AP-1 and AP-2 retrospectively and G-1 prospectively** — and the platform's own Methodological Fitness Rule is satisfied. **What is missing is a second context**, and no amount of refinement inside EPIC-004 can supply it.

**The §3 governance taxonomy is a different case.** It has been amended four times, needed a scope declaration after one validation, and has **no** cross-context evidence at all. **It is the least ready part of the module, and it is the part that grew fastest.**

## ⚠️ The placement question — for the ARB, not for me

**The module is physically in `engineering/` while failing the criteria for being there.**

| Option | Consequence |
|---|---|
| **(a)** Leave it in place, `PROPOSED`, with this assessment attached | Cheapest, and honest as long as the status line is read. **But a file in the canonical tree accrues authority by location** — every future reader finds it under `engineering/knowledge/methodology/` beside an **adopted** module |
| **(b)** Relocate it to project knowledge until a second context supplies evidence | Matches the promotion rule exactly. **But moving a file in `engineering/` is itself an update to `engineering/`** — precisely what this commission forbids me to do unilaterally |

**Not decided here.** *(The DDD module beside it was promoted early too — but as an explicit, recorded governance exception, **R-39**. That is the honest precedent: **early promotion is permitted when it is ruled, named and given a validation expectation** — none of which this module has.)*

## Candidates 2–5 — currently project-local, assessed and **not** proposed

**Recorded so they are visible as candidates rather than promoted by drift.** All currently live in `.claude/MEMORY.md` — project knowledge, exactly where the rule says they belong.

| Candidate | Repeated | Domain-independent | Stable | Reusable | Promote? |
|---|---|---|---|---|---|
| **Historical claims need historical evidence** *(reproduce the run; don't infer past state from present files)* | ⚠️ once, but **validated by execution** | ✅ | ✅ unchanged since recorded | ✅ likely | **No — one instance** |
| **Analysis cannot ratify itself** *(analysis → recommendation → authority → resolution)* | ✅ **three times this session** | ✅ | ✅ | ✅ | **No — one context** |
| **Opening creates intent; acceptance creates completion** | ✅ twice (R-52, R-60) | ✅ | ⚠️ new | ✅ | **No — one context** |
| **Reproduction protocol with a Validity Gate** *(reject invalid experiments; disclose them)* | ⚠️ once | ✅ | ✅ | ✅ **strong** | **No — one instance** |

**Every one fails the same criterion: a single context.** That is not a coincidence — **EPIC-004 is the only context this programme has run**, so *nothing* produced here can yet satisfy criterion 1 on its own.

> ### The observation that follows, and it is worth stating plainly
>
> **Under a strict reading, no insight from a single-context programme can ever be promoted.** The DDD module resolved this with **R-39** — an explicit, recorded exception with a stated validation expectation.
>
> **So the real question is not "promote or not." It is: does this programme want a standing exception mechanism, or does `engineering/` stay closed until a second context exists?** That is the ARB's, and it is a larger question than any single candidate.

## What I did not do

⛔ No file added, moved or removed in `engineering/` · ⛔ no promotion performed · ⛔ no candidate advanced · ⛔ the Layer Verification Rule module **not** relocated, **not** adopted, **not** amended.

---

**Traceability:** promotion criteria as instructed (repeated evidence · domain independence · stability · reusability · simplicity) · `Layer_Verification_Rule.md` (the candidate, and its own recorded limits) · **R-39** (the early-promotion precedent, with its exception recorded) · **R-63** (the scope declaration that is itself evidence on criterion 4) · `DDD_Tactical_Governance_Principles.md` §1 Methodological Fitness Rule. **Evidence produced; no disposition taken.**
