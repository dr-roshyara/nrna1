# `EM-BRQ-001` — Business-Rule Scenario Qualification (Model A) · WORK ITEM PREPARED

**Type:** Work-item preparation (Governance) · **Date:** 2026-08-16 · **Name provisional pending PO.**
**⛔ PREPARED — NOT AUTHORIZED, NOT GRANTED, NOT STARTED, NOT EXECUTED. No scenario has been written, no rule has been simulated. Architecture is not commissioned by this exercise and the Election-track Architecture pause survives it.**

---

## 1 · Objective — one sentence

> **Determine whether the ADOPTED Election rules, exercised together along the Model A Committee-only acceptance path, ever produce a ⚫ CONTRADICTION — with Determinate / Multiple-Permitted / Undefined findings recorded on the way.**

**Contradiction detection is the PRIMARY objective** (PO direction). This is a test of the **rule corpus**, not of any implementation — none exists to test. The pass criterion is the PO's: *can two independent people take the same scenario, apply only the adopted rules, and reach the same business outcome?*

## 2 · ⚠️ Two count corrections, so the work item rests on the record

* The corpus is **58 adopted-marked rules** in the Manifesto (not 45).
* **76 `EM-OPEN` rows still stand** (several 🟡 partially resolved); **110 is the highest identifier ever issued, not the open count.**

## 3 · Scope — narrow, as directed

**IN:** the Model A path end-to-end, including everything that path pulls in when stressed: lifecycle progression and halting (`EM-GOV-011`–`014`) · Election Appointment (`026`/`028`) · Committee definition, thresholds and arithmetic (`029`/`031`/`033`–`036`/`038`) · the freeze (`046`) · gate failure (`052`) · substitution preconditions (`054`) · vacancy filling and denominator (`056`/`057`) · Inoperative and the clocks (`058`–`062`) · the terminal-expiry rule (`063`) · the vocabulary rules those depend on (`EM-VOC-004`/`005`, `P-2H`).
**OUT:** ⛔ any survey of the 76 open rows · Model C / representative arithmetic beyond what Model A touches · `EM-OPEN-110`'s naming question (its *absence* is expected to surface as a KNOWN UNDEFINED, not to be solved) · organisational-governance questions (`049`/`066` are walls, not gaps) · implementation, technology, Architecture.

**The rule corpus is FROZEN at START by commit hash; scenarios are then FROZEN before any Verification pass.**

## 4 · Method — the rule that makes it a test

> ## ⛔ **THE SIMULATOR MUST NOT INVENT MISSING BEHAVIOUR.**
> **Every decision point is answered ONLY by citing adopted rule IDs. Where no adopted rule answers, the verdict is `UNRESOLVED — Governance rule missing` — never an assumption, never an architectural guess.**

Each scenario is recorded on the PO's thirteen-question decision-point table *(state · condition · responsible actor · permitted action · consequence of acting / not acting · deadline · expiry · recoverability · configuration mutability · authority · record · next state)*, each cell carrying its rule citation or `UNRESOLVED`.

**Classification (PO's four, verbatim in effect):**
🟢 **DETERMINATE** — the adopted rules uniquely determine the outcome.
🟡 **MULTIPLE PERMITTED** — alternatives deliberately allowed, AND the rules define who/what chooses.
🔴 **UNDEFINED** — split at recording time into **KNOWN** (maps to an existing `EM-OPEN` id — expected, may be acceptable) and **NEW** (no existing id — a discovered gap; opens one).
⚫ **CONTRADICTION** — two adopted rules produce incompatible outcomes. **The critical finding; each escalates to the PO individually.**

## 5 · Scenario families (scope enumeration — the suite itself is EXECUTION, not preparation)

**F-1 Normal Model A lifecycle** — full pass, no interpretation permitted. · **F-2 Committee arithmetic at N=3** — 3/0, 2/1, 1/2 accept/object; unavailability at 1 and 2; abstention vs unavailability vs objection vs resignation kept distinct. · **F-3 Attrition + vacancy** — resignation → freeze interaction → organisational filling → denominator immobility (`057`) verified at every step. · **F-4 Committee failure chain** — cannot function → Inoperative → restoration vs non-restoration → clock pause/resume (`060`–`062`) → cancellation (`058`). · **F-5 Chief-failure chain** — halt → Chief acts vs does not act → service-policy expiry → `EM-GOV-063`: terminal consequence fires, **no sanction, no invented removal, no Deputy assumption**. · **F-6 Impossibility** — a `C-3` case decided by rules alone; the inaction-≠-impossibility test; **both-clocks interaction when a halt and Inoperative overlap.** · **F-7 Version-binding under recovery** — reschedule mid-path; superseded opportunities keep their records; nothing carries over.

*(Order-of-magnitude: ~20 scenarios across seven families — sized at suite construction, not here.)*

## 6 · Ownership and independence — as directed

**Governance:** constructs the suite · runs the first pass · classifies · escalates ⚫ findings. **Verification (later, separately started):** receives ONLY the frozen scenario set + the frozen rule corpus — **never Governance's outcomes** — performs the blind re-derivation; divergence between the two passes is itself a finding *(a rule two readers apply differently is not determinate, whatever each reader believed)*. **The PO/ARB:** disposes of contradictions; accepts or resolves NEW UNDEFINED findings.

## 7 · The four gates — none passed; all are the PO's acts

| Gate | State |
|---|---|
| **① Sequencing** | ⛔ **The recorded decision *"platform-first then Election"* stands. It must be EXPLICITLY revised or excepted before any Election work starts — this work item does not revise it.** |
| **② Assignment** | ⛔ PO creates/confirms the work item (this document is the draft). |
| **③ Grant** | ⛔ Election grants are **NONE**; a scoped grant is required. |
| **④ START** | ⛔ A recorded human START on the performing lane. |

**Traceability.** PO commission (this thread, 2026-08-16) · Manifesto @ HEAD (`f6e73147` lineage) · sequencing decision (CONTEXT, architecture-approval row) · Election register (grants NONE · Session 3 stopped · `EM-OPEN-021` open) · A-3.
