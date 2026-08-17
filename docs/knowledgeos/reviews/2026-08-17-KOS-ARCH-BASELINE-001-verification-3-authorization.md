# KOS-ARCH-BASELINE-001 — Verification #3 registered
# **Correction verification only · ⛔ NOT STARTED — the START is the PO/ARB's act**

**2026-08-17 · Session 2 (Governance)**

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #19
 Responsibility : governance
 Operator       : Session 2 (Governance)              [declared]
 Approver       : PO/ARB — review + scope 2026-08-17   [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

---

## 1 · 🔴 The delivered prompt cited the wrong report — corrected, not propagated

The PO/ARB's prompt listed as source **1**:

> *"Original Verification #2: `…-phase-a-independent-verification.md`"*

**That file is Verification #1** (`ad914714` — *"D-1/D-2/D-3 CONFIRMED + new V-A"*). **Measured:**

| File | `N-1` | `N-2` |
|---|---|---|
| `…-phase-a-independent-verification.md` (**#1**) | **0** | **0** |
| `…-phase-a-v1.1-correction-verification.md` (**#2**, `749e14df`) | **8** | **7** |

> **Verification #3 would have opened the one report that never mentions the findings it was sent to verify.** It would have found `D-1/D-2/D-3/V-A` — the *previous* round — and either verified the wrong things or stalled.
>
> **Corrected in the grant and in the assignment's `executionContext`, with the correction disclosed rather than silently applied.** Verification #1 is retained as **context only**.

**This is the third instance of the same class** — `V-4` had no referent; `PBDIGIT-64` was asserted open after it had shipped. **Checking the referent before registering is now demonstrably load-bearing, not ceremony.**

## 2 · Two premises that did not hold

### 2.1 · The correction lane had never been closed

`S4-architecture-baseline` was **`ACTIVE` and still held mutation ownership** after delivering both correction passes. **No handoff to any verification lane was possible.** Your diagram's *"COMPLETE? NO"* was exactly right.

**`COMPLETE` recorded at seq 10 by Governance** — a lane may not complete its own assignment (`G-1`). **`COMPLETE` = delivered, NOT accepted.** Acceptance remains yours, after #3. Ownership released.

### 2.2 · Verification #2 ran with no assignment of its own

The record holds **only two lanes**. At seq 7 ownership went **back** to `S4`, and seq 8 and seq 9 are **two consecutive `START`s on `S4`** — the two correction passes.

> **There is no lane, no `START` and no grant under which Verification #2 was performed.** The document exists and is durably committed (`749e14df`); **its independence is not recorded anywhere.**
>
> **This does not make its content wrong, and its two findings are being acted on.** It means the assurance chain you are building rests, at that link, on **an artifact produced outside the governed lane** — precisely what `INV-ATTR-2` says the record cannot attest. **Recorded so it is weighed at acceptance, not discovered afterwards.**

## 3 · What was registered

| Act | Value |
|---|---|
| seq **10** | **`COMPLETE`** — `S4-architecture-baseline` (`G-1`, by Governance) |
| seq **11** | **`REGISTER`** — `S1-verification-baseline-n1-n2`, role `verification`, predecessor `S4-architecture-baseline` |
| **Grant** | **`G-KOS-ARCHBASE-A-VERIFY3`** — `AUTHORIZED` |
| seq **12** | **`HANDOFF`** — bootstrap (ownership released at seq 10) |
| **START** | ⛔ **NOT performed — yours** |

**A new assignment, per `R8`** — the old verification lane is `HANDED_OFF` and its role is immutable. **A fresh lane is what "fresh Verification #3" requires.**

Your recommendation of **a different model** for #3 is carried in `executionContext` **as a recommendation, marked distinct from the `R-34`/`P-2` obligation** that also applies — and it does not block the START.

## 4 · Scope — your wording, bounded

**N-1:** title states `v1.1` · agrees with the existing banner and artifact identity · nothing else changed on its account.
**N-2:** order `6.2 → 6.3 → 6.4` · **the identifier `6.4` is PRESERVED** — moved, never renumbered · **no reference broken** (banner, §1, Verification #1 and #2).
**Integrity:** snapshot still `2026-08-15` with no post-snapshot leakage · no scope expansion · **no classification changed** · no finding added, removed or softened · Verification #2 not rewritten.

**Excluded:** judging the whole Phase A architecture · reopening `D-1`/`D-3`/`V-E` · **repairing anything — a defect found is reported, not fixed** · modifying any artifact or record · **accepting the baseline** · Phase B/C · mechanism change · Election (`A-8`) · self-certification.

## 5 · 🟠 A third production instance of `D-2` — and it is worse than the first two

Both existing lanes reported `authorizationLinkage: G-KOS-ARCHBASE-A-CORRECT2` — including the **verification** lane, whose work **predates that grant**. The linkage is not merely wrong-scoped; it is **anachronistic**.

**Probed further, and the result is sharper:**

```
authorized --session=S1-verification-baseline-n1-n2  --scope=<VERIFY3 scope>  → true
authorized --session=S4-architecture-baseline        --scope=<VERIFY3 scope>  → true   ← COMPLETED architecture lane
```

> **`authorized` never consults the session.** It answers *"does any AUTHORIZED grant carry exactly this scope string"* — not *"is this session authorized for this scope."* The `--session` argument is checked for existence and then **ignored**.
>
> **A completed architecture lane reports itself authorized under a verification grant.** This is direct, reproducible evidence for `KOS-GOV-GAPS-VERIFY-001` — **carried there as a finding, decided nowhere.**

## 6 · One observation, no action taken

This work item's evidence chain now spans **two documentation roots** — `docs/publicdigit/reviews/` (commission, baseline, verification authorization, intake) and `docs/knowledgeos/reviews/` (Verifications #1 and #2, correction delivery, self-review disclosure). `php scripts/doc-placement.php` returns **no matching rule**.

**For a work item whose subject is evidence integrity, a split evidence chain is worth naming.** **Nothing was moved** — relocation would break the references Verification #3 is about to check. This record follows the active chain's location.

## 7 · Nothing else done

**No START · no verification performed · nothing accepted · no artifact modified · `D-1`/`D-3`/`V-E` untouched · Phase B/C still unauthorized · Stage 2 not opened.**

---

*Technical references: PO/ARB review and scope 2026-08-17 (§4 verbatim) · `KOS-ARCH-BASELINE-001` seq 10–12 · `G-KOS-ARCHBASE-A-VERIFY3` · Verification #1 `ad914714` (context only) · **Verification #2 `749e14df` — the report stating N-1/N-2*** · correction delivery `40026b12` · `R8` · `R-34`/`P-2` · `G-1` · `D-2` (§5 probe) · `INV-ATTR-2` · `A-8`.*
