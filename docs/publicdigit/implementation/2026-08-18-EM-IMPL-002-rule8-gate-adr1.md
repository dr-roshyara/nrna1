# `EM-IMPL-002` — Rule-8 Authorization / Dependency Gate on the ADR-1 ruling

**Type:** Governance gate result (Session 2) · **Date:** 2026-08-18 · **Trigger:** ADR-1 signed ACCEPTED/DECIDED · **Method:** the domain core inspected read-only; nothing modified.
**Rule 8:** *a signature settles a QUESTION; it does not grant authority over every layer the answer touches. The first act after a decision is an authorization/dependency check — never coding.*

## 1 · Gate result

> ## ⛔ **THE NORMALIZATION SLICE IS BLOCKED. A SEPARATE DOMAIN SLICE IS REQUIRED FIRST.**
> **Authorized by the ruling:** the **semantic direction** and the **bounded normalization SCOPE**. **Not authorized:** normalization itself, because the contract §6(b) requires it to consume **does not exist**.

## 2 · The five gate questions and their answers *(each verified, not assumed)*

| # | Question | Answer |
|---|---|---|
| **Q1** | Does the domain expose a consumable contract for **Committee required-existence**? | ⛔ **NONE** |
| **Q2** | Does the domain expose a contract **discriminating the two `AcceptanceDecision` meanings** — *not-yet-reached-phase* vs *required-but-absent*? | ⛔ **NONE** — and this is the ruling's **new** requirement: §6(a) now names **two** distinct meanings for the same reference, so something must distinguish them, and nothing does |
| **Q3** | Do policies P-1…P-7 accept or return an absence/existence concept? | ⛔ **No.** Their signatures take intervals, halts, threshold rules, seat counts and return integers, gate designations, vote counts and booleans. **Not one takes or returns an absence concept.** |
| **Q4** | Is `RecoveryProcess`-absence handling already conformant? | ✅ **Yes** — `if ($restoration === null) { return; }` / `if ($halted === null) { return; }`. Per §6(c) it **must not be changed for symmetry.** |
| **Q5** | What does the binding RED pin still report? | `FillCommitteeSeatHandler::$committee` · `FillCommitteeSeatHandler::$decision` — **both still unresolved** |

## 3 · Why this blocks, in the ruling's own terms

**§6(b):** the Application *"may not interpret the business meaning of that absence from the technical observation alone"*, and an invariant is consumable *"ONLY when its meaning is exposed through an authorized domain-owned contract."*
**§6(b) further:** *"If the required domain contract does not exist, that is a DOMAIN-MODEL GAP, not permission for the Application layer to invent the meaning."*
**Binding constraint ⑥:** *"If the required domain contract does not exist, implementation stops and a separate domain slice is required."*

> **Q1–Q3 establish that the contract does not exist. Constraint ⑥ therefore applies by its own terms: implementation stops.** ⛔ **And the prohibited shape in §6(b) is precisely what UC-1 and UC-2 do today** (`?? throw new InvalidArgumentException(...)` / `?? throw $this->unresolvedReference(...)`), so normalization cannot proceed by generalising either of them.

## 4 · What the gate does NOT conclude

⛔ It does not design the missing contract · does not name a class, method, policy, value object or status model · does not decide **where** the discrimination in Q2 belongs · does not select an owner · and does not authorize the domain slice it identifies as required. **Requesting that authorization is the PO's next act, not Governance's.**

## 5 · Consequent sequence

```
ADR-1 signed ✅
   → Rule-8 gate: CONTRACT ABSENT (this record)
   → PO/ARB authorization for a DOMAIN slice   ← next act, and it is the PO's
        → domain RED → domain implementation → verification
   → the bounded normalization slice (UC-1/UC-2/UC-3, per §6(c))
        → AbsentAggregateReferenceRedTest becomes GREEN
   → GREEN-5
```

⚠️ **ADR-2 remains UNSIGNED and is untouched by this gate** (§6 constraint ⑧). Its ruling may add a further domain dependency; if so, the two domain needs should be weighed together before either slice is authorized — **an observation, not a recommendation to combine them.**

**Traceability.** ADR-1 §6 (recorded ruling) · §6c + H-1 · Operating Rules 8 and 9 · the RED pin · this gate's own read-only inspection.
