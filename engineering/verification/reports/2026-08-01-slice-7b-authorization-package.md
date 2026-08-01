# Slice 7B — Authorization Package

**Date prepared:** 2026-08-01 · **Prepared by:** Recording Architect · **Authorized by:** **R-51** (preparation authorized) · **R-54** (preparation active)
**Status:** 📋 **PREPARED — awaiting EP-01 / ARB approval.** ⛔ **No implementation. No production code. No tests written.**
**Repository Integrity Gate:** ✅ PASSED.
**Two artifact kinds, never mixed:** **ARB Decision** *(none in this document — it decides nothing)* · **📝 Recording Note** *(the Recording Architect's explanation; never citable as authority)*.

---

> ## ⚠️ TWO PLANNING FINDINGS — surfaced here, which is what planning is for
>
> **P7B-1 · The approved plan's 7B row carries a STALE MECHANISM.** It reads *"consuming `AdjudicationDurations` for MAD"* and *"MAD comes from the **existing** port."* **R-44 superseded exactly that wording** — Election declares its **own** `EvidencePreservationDurations` port, and importing Adjudication's is the TP-1 violation R-44 rejected. **This is the same stale-reference class corrected for 7A in the alignment commission; the 7B row was not updated at the time.** The **invariant** (MAD keeps one home) is untouched; only the **mechanism** sentence is stale.
>
> **P7B-2 · Two proposed keystones assign the same rule to two different homes.** *"Open window requires an anchor"* (a VO construction rule) and *"absent anchor ⇒ window open"* (a permissive fallback) **cannot both live in the Value Object.** If the VO **requires** an anchor to construct, then *absent anchor* is a case the VO can never see — it is the **caller's** case, and the fallback belongs to the **application service**. **Assigning it to the VO would push a policy decision into a value that cannot legally hold it.**
>
> **Neither is resolved here.** Planning surfaces them; §7 routes them.

---

## 1. Scope Statement

| Element | Content |
|---|---|
| **Objective** | The **`EvidencePreservationWindow` Value Object** — Constitutional Policy 2's arithmetic, expressed once: **EPW = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin**, anchored to an election instance, answering *is this window open at T?* |
| **Owner** | **Election** bounded context (ownership commission; VO placed in `app/Contexts/Election/Domain/`) |
| **Boundary** | The value and its arithmetic. **Nothing consumes it** — 7B is inert, exactly as 7A was |
| **Non-goals** | ⛔ the deletion guard (**7C**) · ⛔ any change to `audit:cleanup` (**7C**) · ⛔ changing how audit evidence is written · ⛔ artifacts B and C · ⛔ **F-WP6R-1** |
| **Dependencies** | **7A** — delivered and **accepted (R-48)**; the durations port is in the baseline · **merge gate green (R-55)** · **the EPW anchor** — an outstanding **business** decision (see §5) |
| **Releasable independently** | ✅ yes — still inert |

## 2. Evidence Traceability — every element traces to existing authority

| Authority | What it authorizes here |
|---|---|
| **Constitutional Policy 2** | the concept itself: *"a single **domain concept**, not a configuration value"*, *"attached to the election instance"* |
| **EPIC-004K §142** | the three-term sum |
| **R-46** | the WP-7 plan, of which 7B is a slice |
| **R-44** (A-1) | **the mechanism**: Election's own consumer-side port — **this is what makes P7B-1 stale** |
| **R-45** (A-2) | **Election ANSWERS**; Audit/Retention **ACTS** — 7B builds the *answering* half only |
| **R-48** | 7A accepted; the durations port is a usable baseline dependency |
| **R-51 · R-54** | preparation authorized, then active |
| **R-55** | merge gate green — 7B can present a complete gate at acceptance, which 7A could not |
| Ownership commission | Election owns EPW; placement `app/Contexts/Election/Domain/` |
| Construction commission | **private constructor + named static factory on the VO**; **business values only**; `is-open-at-T` takes the instant as an **argument** |
| **AP-1 · AP-2** | fail closed; one home per parameter |

**No new architectural decision is created by this package.** Every element above already exists.

## 3. Engineering Keystones — the minimum outcomes, not a design

**Numbered for the future RED. Each states a *property*, not an implementation.**

| # | Keystone | Traces to |
|---|---|---|
| **K1** | The window is constructed **only** through its named static factory — direct construction is impossible | construction commission (house idiom) |
| **K2** | **EPW = CW + MAD + LSM** — the three-term sum, matching §142 exactly | Policy 2 · §142 |
| **K3** | The window is **open** at an instant inside it | Policy 2 |
| **K4** | The window is **closed** at an instant after it | Policy 2 |
| **K5** | The window is **closed** at an instant before its anchor | Policy 2 |
| **K6** | The constructor accepts **business values only** — no port, config, Eloquent model or clock; the instant is an **argument** | construction commission |
| **K7** | **MAD is obtained through Election's OWN port** — no second home, and **no import of Adjudication's port** | **R-44** · AP-2 |
| **K8** | A **non-positive or missing** duration **throws** — no default, no clamp | **AP-1** |
| **K9** | **Absent anchor ⇒ the window is treated as OPEN** *(placement subject to **P7B-2**)* | plan §5 7B acceptance |
| **K10** | The value is **immutable** and has **no identity** — two windows with the same inputs are the same window | ownership commission |

**K9 is the one keystone whose *home* is unsettled.** Every other keystone's home follows from existing decisions.

## 4. Verification Strategy

| Phase | Activity | Gate |
|---|---|---|
| **RED** | Write K1–K10; confirm each fails **for the expected reason** | no production code |
| **GREEN** | Implement the VO; make K1–K10 pass | — |
| **VERIFY** | `composer merge-gate` — **now achievable**, per R-55 | Architecture · Deptrac · PHPStan max · regression |
| **GUARD** | Confirm the VO falls under `test_greenfield_domain_is_framework_free` by placement | automatic |
| **DOCUMENT** | Developer guide — Definition of Done | `developer_guide/election/` |
| **ACCEPT** | ARB reviews evidence | ruling |

**Acceptance criteria (for the future ARB, not decided here):** the three-term sum matches §142 · **MAD via Election's own port, no second home** · absent anchor yields *open* · no duration defined, defaulted or clamped · **no context crossing** · merge gate complete.

**Completion evidence:** RED report · GREEN report · merge-gate output · developer guide.

**Rollback:** **7B is inert — nothing consumes the VO.** Reverting is deleting an unreferenced value object; **there is no runtime behaviour to roll back.** *(That is a property of the slice order, not luck: the observable change is deliberately deferred to 7C.)*

## 5. Preconditions and Open Items

| Item | Status | Blocking? |
|---|---|---|
| 7A accepted (R-48) | ✅ satisfied | — |
| Merge gate green (R-55) | ✅ satisfied | — |
| **P7B-1** — stale mechanism in the plan's 7B row | ⚠️ **open** | **Yes, for accuracy** — the plan text put to EP-01 must not instruct the TP-1 violation R-44 rejected |
| **P7B-2** — K9's home (VO vs application service) | ⚠️ **open** | **Yes for RED**, no for approval — RED cannot write K9 without knowing where it lives |
| **The EPW anchor** — which date anchors the window | ⚠️ **open business decision (Q-2)** | **No** — *fail closed* covers every case, and **K9 is that treatment** |
| F-WP6R-1 | ⬜ recorded, separate | **No** — excluded from 7B |

**📝 Recording Note.** The anchor has been open since WP-7 planning began and has never blocked, because fail-closed decides nothing on Q-2's behalf. **P7B-1 and P7B-2 are different**: they are not business values but **statements in the plan and keystone set that are internally inconsistent with issued rulings.** Both are cheap to settle and neither requires new architecture.

## 6. Decision Templates — **BLANK**

*(For the future ARB. Nothing pre-filled; no outcome implied.)*

### Decision — Slice 7B EP-01 Plan Approval · *Planning Governance · Approval*
| Field | Value |
|---|---|
| Evidence reviewed | this package |
| Decision | ☐ APPROVED ☐ REJECTED ☐ DEFERRED |
| Reason | |
| Effect | |

### Decision — Slice 7B Execution Authorization · *Execution Governance · Authorization*
| Field | Value |
|---|---|
| Guard | ☐ plan approved ☐ P7B-1 settled ☐ P7B-2 settled |
| Decision | ☐ AUTHORIZED ☐ DEFERRED |
| Reason | |
| Effect | |

### Decision — P7B-1 · plan-text correction *(mechanism only; the invariant is untouched)*
| Field | Value |
|---|---|
| Decision | ☐ correct the 7B row to R-44's mechanism ☐ other |
| Reason | |

### Decision — P7B-2 · where K9 lives
| Field | Value |
|---|---|
| Decision | ☐ application service ☐ Value Object ☐ other |
| Reason | |

## 7. Routing of the two findings

| Finding | Nature | Owner | Why not settled here |
|---|---|---|---|
| **P7B-1** | a **plan text** inconsistent with an issued ruling | **ARB / Decision Authority** — it is the approved plan | Amending an approved plan is a governance act; **this package decides nothing** |
| **P7B-2** | a **keystone-placement** question | **engineering**, within the frozen architecture — *(the construction commission already implies the answer: a VO that requires an anchor cannot observe its absence)* | It affects **where a test goes**, so it should be settled **before RED**, not during it |

---

## Package Completion Status

| Criterion | Status |
|---|---|
| Scope statement — objectives · boundaries · non-goals · dependencies | ✅ |
| Evidence traceability to existing authority | ✅ — **no new architectural decision created** |
| Engineering keystones identified | ✅ K1–K10 |
| Verification strategy | ✅ incl. rollback |
| Decision templates **blank** | ✅ |
| **No implementation** | ✅ **no production code, no tests, no config** |
| WP-6 not reopened · R-55 not reconsidered · F-WP6R-1 excluded · 7C excluded | ✅ |
| **No governance decision exercised** | ✅ |

**The programme is ready for EP-01 / ARB consideration of Slice 7B, with two open items named rather than buried.**

---

**Traceability:** R-51 · R-54 (this authorization) · R-44 · R-45 · R-46 · R-48 · R-55 · Constitutional Policy 2 · EPIC-004K §142 · WP-7 plan §5 slice 7B · ownership and construction commissions · AP-1 · AP-2. **No implementation · no architecture redesigned · no ruling reinterpreted · WP-6 closed and untouched.**
