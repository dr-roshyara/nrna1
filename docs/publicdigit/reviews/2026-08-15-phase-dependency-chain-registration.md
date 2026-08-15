# Registration — The Phase Dependency Chain, and `EM-OPEN-037`(b) Reframed

**Type:** Governance registration (Session 2) · **Date:** 2026-08-15 · **Basis:** PO/ARB refinement of the plan-first model
**⛔ Registration only. Nothing adopted. No architecture. `EM-OPEN-026`(a) untouched · `EM-OPEN-022` settled and not reopened. Architecture ⏸️, Session 3 🛑.**

---

## 1 · The correction accepted

**Publishing the complete schedule does not make all five phase opportunities immediately actionable, and the phases are not independent.** The election has a **dependency chain**: administration → candidacy → voting → counting → result publication, each conditioned on its predecessor **legitimately completing**, not merely on its own window arriving.

**`EM-OPEN-037`(b) is reframed accordingly** — the earlier form (*"are all five created simultaneously?"*) was too simplistic and is replaced by the PO's:

> **A** — planned opportunities are created at publication and remain **dormant** until their predecessor legitimately completes.
> **B** — a phase becomes an opportunity **only when** its predecessor successfully completes.

**PO leaning registered as a leaning: A**, because the published plan is a fact and the protocol should be able to say *"Voting was scheduled for 11 September, but Candidacy did not legitimately complete, therefore Voting never became eligible"* — richer than pretending the phase never existed. **Not adopted; the PO expressly asked for a Governance ruling first, and Governance does not adopt new business policy.**

## 2 · Three findings

### ⚠️ **① A gap that opens only if A is chosen**

**`EM-VOC-004`'s five outcomes cannot name what happens to a planned opportunity that never became available.** *Expired unused* implies it **was** available and went unused. *Cancelled* implies an act. *Superseded* implies a later schedule. **A Voting opportunity whose predecessor failed is none of those.**

**If A is adopted, a sixth outcome is required — or an explicit ruling that one of the five covers it.** `EM-VOC-004` demands that outcomes stay **distinguishable**; leaving this unnamed would collapse *"never became available"* into *"expired unused"*, which is exactly the information loss that rule exists to prevent. **Under B the gap does not arise, because there was never an opportunity to give an outcome to.** *(Stated as a consequence of each option, not as an argument for either.)*

### ⚠️ **② The word "eligible" is about to carry two meanings**

The distinction **scheduled ≠ eligible ≠ actionable ≠ active** is useful. **But `EM-VOT-004` already defines *eligible*:** reaching the scheduled time *"makes the election **eligible for the Chief Election Officer's consideration**"* — **time-based only.** In the new four-way distinction, *eligible* reads as *time reached **and** prerequisites satisfied*.

**Two meanings for one word inside one domain.** ⛔ **Before the four-way distinction is adopted: either keep `EM-VOT-004`'s meaning and name the prerequisite-satisfied state something else, or amend `EM-VOT-004` deliberately.** *"Active"* also sits close to lifecycle vocabulary and should be given a business name if adopted. **Recorded as a caution, not adopted.**

### ⚠️ **③ The correction cascade**

Under plan-first, **the whole schedule is fixed at application and may change only by governed correction.** So when an early phase cannot complete within its window, **every later window may need correcting too.**

**One failure may therefore produce several corrections — each superseding its own opportunity, each requiring a new authorization** (`EM-VOC-005`, `EM-VOT-005`). **Does correcting one window require, permit, or forbid correcting the downstream windows within the same governed act? No adopted rule says.** Registered in `EM-OPEN-038`.

**This compounds the consequence already registered:** `EM-GOV-004`'s six unadopted boundaries now carry not only all schedule flexibility, but **multi-window corrections** as well.

## 3 · The dependency chain itself — `EM-OPEN-038`

**No adopted rule states the chain.** It is a **business prerequisite rule**, not merely schedule ordering, which sharpens `EM-OPEN-033`(c) — that question asked only about *ordering*, and the model asks for *legitimate completion of the predecessor*. **Registered for explicit adoption. It must not arrive by description.**

**Not in dispute, and already derived:** *"the clock says 6 September, therefore start candidacy"* is excluded. That is `EM-VOT-004`'s principle; **its generalisation beyond voting is `EM-OPEN-037`(a)**, still open, and this model relies on it.

## 4 · Status

```
REFRAMED     EM-OPEN-037(b)  A (dormant planned opportunities) vs B (created on completion)
                             PO leans A — recorded as a leaning; NOT adopted
                             ⚠️ under A a SIXTH OUTCOME is required (or a ruling that one of
                                the five covers "never became available")
OPENED       EM-OPEN-038     the dependency chain, for explicit adoption
                             + the CASCADE question (one failure → several corrections?)
RECORDED     vocabulary caution — "eligible" already means time-only in EM-VOT-004
UNCHANGED    EM-OPEN-037(a) · 026(a) · 033 · 034 · 035 · 036 · EM-GOV-004's six boundaries
ARCHITECTURE ⏸️ STOPPED      SESSION 3  🛑 STOPPED      IMPLEMENTATION  NOT AUTHORIZED
```

**Traceability.** Plan-first registration (`ca95560d`) → PO refinement → `EM-OPEN-037`(b) reframed · `EM-OPEN-038` opened · vocabulary caution recorded. Antecedents: `EM-VOC-004` · `EM-VOC-005` · `EM-VOT-004` · `EM-VOT-005` · `EM-GOV-004` · `EM-GOV-009` · `G-PROG-1` · `EM-OPEN-030` · `EM-OPEN-033`(c).
