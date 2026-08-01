# ARB Transition Session — Record

**Date:** 2026-08-01 · **Recording architect:** Senior Principal DDD Architect · **Session:** WP-6 → WP-7 transition, **five decisions**
**Repository Integrity Gate:** ✅ PASSED — working tree clean; `feature/pb003`, 7 ahead.
**Status:** 🔴 **SESSION OPEN — AWAITING RULINGS. NO OUTCOME RECORDED.**

> ## THE ONE BOUNDARY I HOLD
>
> **I am the recording architect. I am not the ARB.** The session is prepared, sequenced and presented below — **but no ruling has been issued, so no outcome is recorded, and I will not record one on the ARB's behalf.**
>
> This is the principle the whole programme has been built on, applied to its own final step: ***authority is created only by explicit issuance* (R-34)** · ***analysis cannot ratify itself*** · ***readiness is evidence; acceptance is authority.*** **Fabricating five approvals to produce a tidy record would be the single largest governance failure available to me right now** — and it would invalidate everything the preceding commissions established.
>
> **What I need to close the session: five rulings, one line each.** They are listed in §4.

---

## 1. Governance State Machine (ARB refinements — adopted)

**Renamed from "Programme State Machine."** The states below are **not programme progress** — *Pending Acceptance · Accepted · Approved · Authorized* are **governance concepts**. **The programme consumes them; it does not own them.** The earlier name described the consumer rather than the thing.

> **Modelling principle:** *dependencies explain **why**; states record **what exists now**.* → **Model causality with dependencies. Model authority with state transitions.** *(Promoted to the methodology module — §3 of `Layer_Verification_Rule.md`.)*

### 1.1 Classification — two independent axes: state CATEGORY and transition TYPE

| # | State transition | **State category** | **Transition type** | Authority |
|---|---|---|---|---|
| D1 | WP-6 `Pending Acceptance` → `Accepted` | **Delivery Governance** | **Approval** | ARB |
| D2 | G-1 `Analysed` → `Resolved` | **Architecture Governance** | **Ratification** | ARB |
| D3 | Guard boundary `one` → `two` | **Architecture Governance** | **Ratification** | ARB |
| D4a | Plan `Awaiting EP-01` → `Approved` | **Planning Governance** | **Planning** | **Decision Authority** |
| D4b | Execution `Not authorized` → `Slice 7A authorized` | **Execution Governance** | **Execution** | ARB |

**Category definitions live in ONE place** — `engineering/knowledge/methodology/Layer_Verification_Rule.md` §3 — and are **cited here, not restated**, per the project's rules-live-once principle. The load-bearing one for this session:

> **Delivery Governance governs the acceptance and progression of bounded work packages through the engineering lifecycle.** *(Renamed from "Programme Governance": that term still reads as portfolio, funding or schedule governance. The definition fixes where delivery begins and ends — **it starts at a bounded work package and ends at its acceptance or progression**.)*

**What the classification exposes at a glance:** the machine spans **four distinct governance categories**, and **the Planning Governance state is the only one not owned by the ARB.** That is not a defect — **it is EP-01's separation made visible**, and it is precisely why item 4a is a separate vote with a separate authority (DD-1).

**What the TRANSITION column adds beyond the state column:** the state says *what changes*; **the transition type says *what kind of authority act* is being performed.** D2 and D3 share a category yet are both **Ratifications**, while D1 in a different category is an **Approval** — **the two axes are genuinely independent, so neither column is derivable from the other.** *(A ratification confirms an existing thing's reading; an approval admits a delivered thing; a planning act binds a forward commitment; an execution act unlocks work. Conflating them is how a "yes" comes to mean four different things in one minute-book.)*

### 1.2 Current governance state — verified, not assumed

```
WP-6 ..................... DELIVERED / PENDING ACCEPTANCE
G-1 ...................... ANALYSED  (realization recommended, unratified)
Guard boundary ........... DESCRIBED AS ONE
WP-7 plan ................ AWAITING EP-01 APPROVAL
WP-7 execution ........... NOT AUTHORIZED
RED ...................... BLOCKED
```

### 1.3 The state machine

```
        ┌──────────────────────────┐          ┌──────────────────────────┐
  D1 →  │ WP-6: PENDING ACCEPTANCE │──────────▶│ WP-6: ACCEPTED / CLOSED  │
        └──────────────────────────┘          └────────────┬─────────────┘
                                                           │
        ┌──────────────────────────┐          ┌────────────▼─────────────┐
  D2 →  │ G-1: ANALYSED            │──────────▶│ G-1: RESOLVED            │
        └──────────────────────────┘          └────────────┬─────────────┘
                                                           │ guard: G-1 RESOLVED
        ┌──────────────────────────┐          ┌────────────▼─────────────┐
  D4a → │ plan: AWAITING EP-01     │──────────▶│ plan: APPROVED           │
        └──────────────────────────┘          └────────────┬─────────────┘
                                                           │
                    guard:  WP-6 ACCEPTED  ∧  PLAN APPROVED
                                                           │
        ┌──────────────────────────┐          ┌────────────▼─────────────┐
  D4b → │ execution: NOT AUTHORIZED│──────────▶│ SLICE 7A: AUTHORIZED     │
        └──────────────────────────┘          └──────────────────────────┘

  D3 →  boundary: DESCRIBED AS ONE ──────────▶ NAMED AS TWO      [gates 7B only —
                                                                  no edge into 7A]
```

**The guard, stated as the ARB asked:**

> **`WP-6 ACCEPTED` ∧ `WP-7 PLAN APPROVED` ⇒ execution authorization is LEGAL.**
> Neither alone is sufficient. **`PLAN APPROVED` itself has the guard `G-1 RESOLVED`.**

**Why the state view beats the dependency view here:** a dependency graph says *4b needs 1 and 4a*. **The state machine says something stronger — which states may legally coexist.** It makes `execution AUTHORIZED ∧ plan AWAITING EP-01` **unreachable**, rather than merely discouraged. **That is EP-01 enforced by the model instead of by memory.**

## 2. Decisions, presented in dependency order (Phases 1–2)

**Presented independently. Not merged. Outcomes blank.**

### ▶ Item 1 — Accept WP-6 within its approved scope
**Authority:** ARB · **Category:** Delivery Governance · **Transition type:** **Approval** · **State affected:** WP-6 · **Prerequisites:** none — **may be taken first or last**
**Evidence:** closure package — 3/3 scope · keystones 10 tests / 22 assertions · contexts 91 / 260 · PHPStan max clean (4 root fixes, **none suppressed**) · Deptrac 0 · Architecture 146 green · APR · ADPR · AGIR · authority verification · dev guide + operational record. **Verified complete.**
**Caveat the ARB should hold while reading it:** *"Deptrac 0 / 146 green"* **does not cover the AP-1/AP-2 class** (*a business value was invented*) — **no automated gate does.** Both were found and fixed before acceptance.
**Recommendation (architect's, not a vote):** **accept within the approved scope.**
> **OUTCOME: ☐ Approved ☐ Rejected ☐ Deferred — _______ · RATIONALE: _______**

### ▶ Item 2 — A-1: was AP-2's binding content the **invariant** or the **sentence**?
**Authority:** ARB · **Category:** Architecture Governance · **Transition type:** **Ratification** · **State affected:** G-1 · **Prerequisites:** none — **independent of item 1**
**Evidence:** alignment commission §2 — invariant/mechanism split; four-level model; TP-1 in `deptrac.yaml`.
**Limit, labelled rather than hidden:** *"Deptrac passes unmodified"* is an **analytical prediction, not an executed result** — no code exists. Structurally sound; **not empirically demonstrated.**
**⚠️ Rejection branch:** ruling that *the sentence* was binding mandates a direct cross-context import — **a TP-1 violation Deptrac would fail** — requiring **option (c) or a plan revision, neither on this agenda.** **Option (c) is NOT pre-authorized here.**
**Recommendation:** **rule that the INVARIANT was binding.** The consumer-side port then follows as **engineering's level-3 choice**, needing no further ARB act.
> **OUTCOME: ☐ Approved ☐ Rejected ☐ Deferred — _______ · RATIONALE: _______**

### ▶ Item 3 — A-2: Election **answers**; Audit/Retention **acts**
**Authority:** ARB · **Category:** Architecture Governance · **Transition type:** **Ratification** · **State affected:** guard boundary · **Prerequisites:** none · **Gates slice 7B only — no effect on 7A**
**Evidence:** the frozen allocation already assigns Audit/Retention *"Consumption (acting on the answer)"*; the ownership commission places **the question** in Election.
**Recommendation:** **ratify.** It names as two what was described as one; **no responsibility holder changes.**
> **OUTCOME: ☐ Approved ☐ Rejected ☐ Deferred — _______ · RATIONALE: _______**

### ▶ Item 4a — Approve the WP-7 plan (EP-01)
**Authority:** **Decision Authority** · **Category:** Planning Governance · **Transition type:** **Planning** · **State affected:** plan status · **Guard: item 2 approved**
**Evidence:** `.claude/plans/WP-7-retention-alignment.md` — **current text, amended today.** Approving a superseded text would be worse than not approving.
**Recommendation:** **approve, conditional on item 2.**
> **OUTCOME: ☐ Approved ☐ Rejected ☐ Deferred — _______ · RATIONALE: _______**

### ▶ Item 4b — Authorize execution of **slice 7A**
**Authority:** ARB · **Category:** Execution Governance · **Transition type:** **Execution** · **State affected:** execution status · **Guard: `WP-6 ACCEPTED` ∧ `PLAN APPROVED`**
**Scope:** **slice 7A only.** 7B and 7C follow their own gates — a blanket WP-7 authorization would authorize more than the evidence supports.
**Recommendation:** **authorize slice 7A**, conditional on the guard.
> **OUTCOME: ☐ Approved ☐ Rejected ☐ Deferred — _______ · RATIONALE: _______**

### Optional, non-blocking
Adoption of the **Layer Verification Rule** methodology module *(PROPOSED — WP-7 does not depend on it; deferral has no effect on any state above)*.
> **OUTCOME: ☐ Approved ☐ Deferred — _______**

## 3. Authorization Verification (Phase 4) — **cannot be completed**

| Guard condition | Status |
|---|---|
| `WP-6 ACCEPTED` | ⬜ **not established** — item 1 unruled |
| `G-1 RESOLVED` | ⬜ **not established** — item 2 unruled |
| `PLAN APPROVED` | ⬜ **not established** — item 4a unruled |
| **⇒ execution authorization legal?** | ⬜ **CANNOT BE DETERMINED** |

**Governance state is unchanged from §1.2.** **No transition has occurred. RED remains BLOCKED**, and the reason is not evidence — **the evidence prepared for these five decisions is complete** — it is that **no authority has been exercised.**

**Scope of that claim, stated precisely** *(ARB correction — the earlier wording "nothing else is incomplete" was broader than the evidence supports)*: **within the governance methodology and the WP-6→WP-7 transition, no further architectural or methodological preparation appears necessary, and the remaining recorded actions are authority decisions.** That is **not** a claim that every aspect of the programme is complete — the governance register still carries ARB-owned items, and **C-1 automation remains an engineering deliverable inside 7A.**

## 4. What is required to close this session

**Five rulings, one line each. Nothing else is outstanding.**

| # | Ruling required | Authority |
|---|---|---|
| 1 | WP-6 acceptance — approve / reject / defer | ARB |
| 2 | A-1 — invariant or sentence? | ARB |
| 3 | A-2 — ratify the answer/act split? | ARB |
| 4a | WP-7 plan (EP-01) — approve? | Decision Authority |
| 4b | Authorize slice 7A? | ARB |

**A rationale per ruling is required, not optional** — six months on, the rationale is the only part that explains *why*; **an outcome without one is unauditable.**

## 5. On approval — the first authorized engineering activity

*(Stated now so that **no further governance PREPARATION is required after the outstanding authority decisions are exercised**. **Conditional. Not authorized.** — Corrected from *"needs no further governance step"*: the five outstanding items **are** governance acts, so that wording contradicted the list above it. **Preparation, authority and execution are separate phases; what is complete is preparation.**)*

> **Slice 7A — RED.** Failing tests for retention-duration resolution: **per election type** · **organisation override** · **fail closed on missing or invalid** — against **Election's own `EvidencePreservationDurations` port**, mirroring the verified `AdjudicationDurations` shape.
> **In the same slice:** the **C-1** guard *(engineering's call — the one constraint whose manual enforcement is insufficient)* and, recommended, **R-D1** *(both adapters resolve the same MAD for the same `(electionType, organisationId)`)*.
> **7A is inert** — nothing consumes it; **no observable behaviour changes until 7C.**

**Governance responsibility transfers to engineering only when items 1, 2, 4a and 4b are approved.** Until then it remains with the ARB.

**Sequence after the rulings, with the roles kept apart:** the **Authority** exercises the decisions → the **Recording Architect** updates this record with each outcome and rationale and files them to the rulings register → **engineering** begins the first authorized activity, slice 7A. **Three roles, three acts — recording is not deciding, and deciding is not executing.**

---

## Completion status

| Criterion | Status |
|---|---|
| All decisions presented independently, in dependency order | ✅ |
| **Governance** state modelled explicitly, with guards **and per-state categories** | ✅ |
| **All approved decisions recorded** | ⬜ **none approved — none recorded** |
| Resulting governance state explicit | ✅ **unchanged; no transition occurred** |
| First authorized engineering activity identified | ✅ **conditionally** |
| Governance responsibility transferred to engineering | ⬜ **NO — retained by the ARB** |

**No architecture redesigned · no work package reopened · no governance added · no implementation performed · no outcome fabricated.**

---

**Traceability:** ARB session decision pack **v2** (five items, post-amendment) · decision dependency verification (DD-1/DD-2/DD-3; the DAG this state machine supersedes with guards) · ARB transition authorization commission · WP-6 closure package · alignment commission (A-1, A-2) · implementation guard commission (C-1) · `.claude/CLAUDE.md` §EP-01 · **R-34**.
